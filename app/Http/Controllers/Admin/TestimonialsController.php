<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\Testimonial;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TestimonialsController extends Controller
{
    /**
     * listing the Testimonials
    */
    public function index(Request $request)
    {
        if (!have_right('View-Testimonials'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = Testimonial::latest();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('order', function ($row) {
                if (have_right('Set-Order-Testimonials')) {
                    $order = '<input data-testimonial-id="' . $row->id . '"  id="order_set_' . $row->id . '" class="testimonial_input form-control input-sm" type="number" placeholder="Enter order" value="' . $row->testimonial_order . '">';
                    $order .= "<button data-testimonial-id='" . $row->id . "'  class='btn btn-primary order-set-button' >Enter</button>";
                } else {
                    $order = '<input  readonly data-testimonial-id="' . $row->id . '"  id="order_set_' . $row->id . '" class=" form-control input-sm" type="number" placeholder="Enter order" value="' . $row->testimonial_order . '">';
                }
                return $order;
            });

            $datatable = $datatable->addColumn('featured', function ($row) {
                if ($row->is_featured == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if (have_right('Set-Featured-Testimonials')) {
                    $featured = '<label class="switch"> <input type="checkbox" class="is_featured" id="chk_' . $row->id . '" name="is_featured" onclick="is_featured_testimonial($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                    return $featured;
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Testimonials')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/testimonials/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Testimonials')) {
                    $actions .= '<form method="POST" action="' . url("admin/testimonials/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action', 'featured', 'order']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.testimonials.listing', $data);
    }

    /**
     * creating the Testimonials
    */
    public function create()
    {
        if (!have_right('Create-Testimonials'))
            access_denied();

        $data = [];
        $data['row'] = new Testimonial();
        $data['action'] = 'add';
        return View('admin.testimonials.form', $data);
    }

    /**
     * storing the Testimonials
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name_english' => 'required|string|max:200',
            'message_english' => 'required|string',
            'image' => 'mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if (isset($input['image'])) {

            $imagePath = uploadS3File($request , "images/testimonials-images" ,"image","testimonialsImage",$filename = null);
            //$imagePath = $this->uploadImage($request);
            $input['image'] = $imagePath;
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Testimonials'))
                access_denied();

            $model = new Testimonial();
            $model->fill($input);
            $model->save();

            return redirect('admin/testimonials')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Testimonials'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = Testimonial::find($id);
            deleteS3File($model->image);
            $model->fill($input);
            $model->update();
            return redirect('admin/testimonials')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Testimonials
    */
    public function edit($id)
    {
        if (!have_right('Edit-Testimonials'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Testimonial::find($id);
        $data['action'] = 'edit';
        return View('admin.testimonials.form', $data);
    }

    /**
     * removing the Testimonials
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Testimonials'))
            access_denied();

        
        $data = [];
        $model = Testimonial::find($id);
        $image_url =  $model->image;
        deleteS3File($image_url);
        // $id = Hashids::decode($id)[0];
        $data['row'] = Testimonial::destroy($id);
        return redirect('admin/testimonials')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading image of the Testimonials
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            /*$imageName = 'testimonial' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/testimonials-images'), $imageName)) {
                $path =  'images/testimonials-images/' . $imageName;
            }*/
            $path = ImageOptimize::improve($request->image, 'images/testimonials-images');
        }
        return $path;
    }

    /**
     * to add testimonials to featured, admin can not add more than 3 testimonials
    */
    public function setFeaturedtesTimonials($id = null)
    {
        $featuredTestimonials = Testimonial::where('is_featured', 1)->count();
        //____ if already 3 testimonials are added and admin is going to add another testimonials to featured___//
        if ($featuredTestimonials > 4 && $_GET['status'] == 1) {
            echo '2';
            exit;
        } else {
            $update_testimonials = Testimonial::where('id', $id)->update(['is_featured' => $_GET['status']]);
            if ($_GET['status'] == 1) {
                Testimonial::where('id', $id)->update(['status' => $_GET['status']]);
            }
            if ($update_testimonials) {
                echo true;
                exit();
            }
        }
    }

    /**
     * updating order of the Testimonials
    */
    public function updateOrder(Request $request)
    {
        $testimonial = Testimonial::query()
            ->where('id', $request->id)
            ->update(['testimonial_order' => $request->order]);

        if ($testimonial) {
            return response()->json(['status' => 1, 'data' => $testimonial]);
        }

        return response()->json(['status' => 0]);
    }
}
