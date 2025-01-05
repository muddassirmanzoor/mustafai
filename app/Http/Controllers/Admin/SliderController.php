<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Slider;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;
use DataTables;
use App\Helper\ImageOptimize;


class SliderController extends Controller
{
    /**
     * listing the Slider
    */
    public function index(Request $request)
    {
        if (!have_right('View-Slider'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = Slider::where('id', '>', 0)->orderBy('order_rows')->get();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('statusColumn', function ($row) {
                if ($row->status == 1) {
                    $status = 'Active';
                } else {
                    $status = 'Disable';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('order_rows', function ($row) {

                if (have_right('Set-Order-Slider')) {

                    $order = '<input data-slider-id="' . $row->id . '"  min="0" id="order_set_' . $row->id . '" class="slider_input form-control input-sm" type="number" placeholder="Enter order" value="' . $row->order_rows . '">';
                    $order .= "<button data-slider-id='" . $row->id . "'  class='btn btn-primary order-set-button' >Enter</button>";
                } else {
                    $order = '<input readonly data-slider-id="' . $row->id . '"  min="0" id="order_set_' . $row->id . '" class=" form-control input-sm" type="number" placeholder="Enter order" value="' . $row->order_rows . '">';
                }
                return $order;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Slider')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/slider/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Slider')) {
                    $actions .= '<form method="POST" action="' . url("admin/slider/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'statusColumn','order_rows', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.slider.listing', $data);
    }

    /**
     * creating the Slider
    */
    public function create()
    {
        if (!have_right('Create-Slider'))
            access_denied();
        $data = [];
        $data['row'] = new Slider;
        $data['action'] = 'add';
        return View('admin.slider.form', $data);
    }

    /**
     * storing the Slider
    */
    public function store(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content_english' => 'required',
            'image' => 'mimes:jpeg,png,jpg',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
        $url_image = array();

        if ($input['action'] == 'add') {
            if (!have_right('Create-Slider'))
                access_denied();

            if (isset($input['image'])) {
                // $imagePath = $this->uploadImage($request);
                $imagePath = uploadS3File($request , "images/slider" ,"image","slider",$filename = null);
                $input['image'] = $imagePath;
            }
            $input['admin_id'] = auth()->user()->id;
            $model = new Slider();
            $model->fill($input);
            $model->save();

            return redirect('admin/slider')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Slider'))
                access_denied();

            $id = $input['id'];
            $model = Slider::find($id);
            // @for delete images
            if (isset($input['image'])) {
                // $imagePath = $this->uploadImage($request);
                deleteS3File($model->image);
                $imagePath = uploadS3File($request , "images/slider" ,"image","slider",$filename = null);
                $input['image'] = $imagePath;
                $image_url =  $model->image;
                if (file_exists(public_path($image_url))) {
                    unlink($image_url);
                }
            } else {
                unset($input['image']);
            }
            // @for delete images
            $model->fill($input);
            $model->update();
            return redirect('admin/slider')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Slider
    */
    public function edit($id)
    {
        if (!have_right('Edit-Slider'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        // $id = Hashids::decode($id)[0];
        $data['row'] = Slider::find($id);
        $data['action'] = 'edit';
        return View('admin.slider.form', $data);
    }

    /**
     * deleting the Slider
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Slider'))
            access_denied();

        $model = Slider::find($id);
        $image_url =  $model->image;

        // if (file_exists(public_path($image_url))) {
        //     unlink($image_url);
        // }
        deleteS3File($image_url);
        $data = [];
        $data['row'] = Slider::destroy($id);
        return redirect('admin/slider')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading image for the Slider
    */
    public function uploadImage(Request $request)
    {
        // $path = '';
        // if ($request->image) {
        //     $imageName = 'slider' . time() . '.' . 'webp';
        //     if ($request->image->move(public_path('images/slider'), $imageName)) {
        //         $path =  'images/slider/' . $imageName;
        //     }
        // }
        // return $path;
        $path = '';
        if ($request->image) {
            $path = ImageOptimize::improveSlider($request->image, 'images/slider');
        }
        return $path;
    }
    public function updateOrder(Request $request)
    {
        $slider = Slider::find($request->id);
        if ($slider) {
            $slider->order_rows = $request->order;
            $slider->save();
            return response()->json(['status' => 1, 'data' => $slider]);
        }
        return response()->json(['status' => 0]);
    }

}
