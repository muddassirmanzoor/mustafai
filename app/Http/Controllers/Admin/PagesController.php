<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\Validator;
use Session;
use DataTables;
use App\Helper\ImageOptimize;

class PagesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * listing the Pages
    */
    public function index(Request $request)
    {
        if (!have_right('View-Pages'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = Page::orderBy('created_at', 'DESC')->get();

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
            $datatable = $datatable->addColumn('featured', function ($row) {
                if ($row->is_feature == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if (have_right('Set-Featured-Pages')) {
                    $featured = '<label class="switch"> <input type="checkbox" class="is_featured" id="chk_' . $row->id . '" name="is_featured" onclick="is_featured($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                    return $featured;
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Pages')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/pages/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Pages')) {
                    $actions .= '<form method="POST" action="' . url("admin/pages/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action', 'featured']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.pages.listing', $data);
    }

    /**
     * creating the Pages.
    */
    public function create()
    {
        if (!have_right('Create-Pages'))
            access_denied();

        $data = [];
        $data['row'] = new Page();
        $data['action'] = 'add';
        return View('admin.pages.form', $data);
    }

    /**
     * edit the Pages
    */
    public function edit($id)
    {
        if (!have_right('Edit-Pages'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        // $id = Hashids::decode($id)[0];
        $data['row'] = Page::find($id);
        $data['action'] = 'edit';
        return View('admin.pages.form', $data);
    }

    /**
     * storing the Pages
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string',
            'short_description_english' => 'required|string',
            'url' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->with('error', $validator->messages())->withInput();
        }
        if (isset($input['image'])) {

            $imagePath = uploadS3File($request , "images/page-images" ,"image","pagesImage",$filename = null);
            //$imagePath = $this->uploadImage($request);
            $input['image'] = $imagePath;
        }

        if ($input['action'] == 'add') {
            if (!have_right('Create-Pages'))
                access_denied();

            $input['admin_id'] = auth()->user()->id;
            $model = new Page();
            $model->fill($input);
            $model->save();

            return redirect('admin/pages')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Pages'))
                access_denied();

            $id = $input['id'];
            // $id = Hashids::decode($id)[0];
            $model = Page::find($id);
            deleteS3File($model->image);
            $model->fill($input);
            $model->update();
            return redirect('admin/pages')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * removing the Pages
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Pages'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $model = Page::find($id);
        $image_url =  $model->image;
        deleteS3File($image_url);
        $data['row'] = Page::destroy($id);
        return redirect('admin/pages')->with('message', 'Data deleted Successfully');
    }

    /**
     * to add pages to featured, admin can not add more than 3 pages
    */
    public function setFeaturedPage($id = null)
    {
        $featuredPages = Page::where('is_feature', 1)->count();
        //____ if already 4 pages are added and admin is going to add another page to featured___//
        if ($featuredPages > 4 && $_GET['status'] == 1) {
            echo '2';
            exit;
        } else {
            $update_product = Page::where('id', $id)->update(['is_feature' => $_GET['status']]);
            if ($update_product) {
                echo true;
                exit();
            }
        }
    }

    /**
     * uploading image for the pages
    */
    public function uploadImage(Request $request)
    {
        
        $path = '';
        if ($request->image) {
            $path = ImageOptimize::improve($request->image, 'images/page-images');
        }
        return $path;
    }
}
