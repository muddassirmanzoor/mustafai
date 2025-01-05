<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\LibraryExtention;
use App\Models\Admin\LibraryType;
use Illuminate\Http\Request;
use Session;
use DataTables;
use Illuminate\Support\Facades\Validator;



class librarySectionsController extends Controller
{
    /**
     * listing the library section.
    */
    public function index(Request $request)
    {

        if (!have_right('View-Library-Section'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $db_record = LibraryType::orderBy('created_at', 'DESC')->get();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('statusColumn', function ($row) {
                if ($row->status == 1) {
                    $status = 'Active';
                } else {
                    $status = 'Disable';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Library-Section')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/library-section/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                // if (have_right('Delete-Library-Section')) {
                //     $actions .= '<form method="POST" action="' . url("admin/library-section/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                //     $actions .= '<input type="hidden" name="_method" value="DELETE">';
                //     $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                //     $actions .= '<button class="btn btn-danger" style="margin-left:02px;" type="button" onclick="showConfirmAlert(this)" title="Delete">';
                //     $actions .= '<i class="far fa-trash-alt"></i>';
                //     $actions .= '</button>';
                //     $actions .= '</form>';
                // }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action', 'statusColumn']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.library-section.listing', $data);
    }

    /**
     * creating the library section.
    */
    public function create()
    {
        if (!have_right('Create-library-section'))
            access_denied();
        $data = [];
        $data['row'] = new LibraryType();
        $data['action'] = 'add';
        return View('admin.library-section.form', $data);
    }

    /**
     * storing the library section.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            // if (!have_right('Create-Library-Section'))
            //     access_denied();

            // if (isset($input['icon'])) {
            //     $imagePath = $this->uploadImage($request);
            //     $input['icon'] = $imagePath;
            // }

            // unset($input['action']);
            // unset($input['extentions']);
            // // $input['admin_id'] = auth()->user()->id;
            // $model = new LibraryType();
            // $model->fill($input);
            // $isSave = $model->save();
            // if ($isSave) {
            //     foreach ($request->extentions as $key => $val) {
            //         $libraryExtention = new LibraryExtention;
            //         $libraryExtention->type_id = $model->id;
            //         $libraryExtention->extention = $val;
            //         $libraryExtention->save();
            //     }
            // }
            // return redirect('admin/library-section')->with('message', 'Data added Successfully');
        } else {

            if (!have_right('Edit-Library-Section'))
                access_denied();

            $id = $input['id'];
            $model = LibraryType::find($id);
            $model->libraryextentions()->delete();
            // dd("ok");
            // @for delete images
            if (isset($input['icon'])) {
                deleteS3File($model->icon);
                $imagePath = uploadS3File($request , "images/lib-icons" ,"icon","librarySEction",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['icon'] = $imagePath;
                $image_url =  $model->icon;
                if (!empty($image_url)) {
                    if (file_exists(public_path($image_url))) {
                        unlink(public_path($image_url));
                    }
                }
            } else {
                unset($input['icon']);
            }
            unset($input['action']);
            unset($input['extentions']);
            $model->fill($input);
            $model->update();

            foreach ($request->extentions as $key => $val) {
                $libraryExtention = new LibraryExtention;
                $libraryExtention->type_id = $model->id;
                $libraryExtention->extention = $val;
                $libraryExtention->save();
            }
            return redirect('admin/library-section')->with('message', 'Data updated Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    /**
     * storing the library section.
    */
    public function edit($id)
    {
        if (!have_right('Edit-library-Section'))
            access_denied();
        $data = [];
        $data['id'] = $id;
        // $id = Hashids::decode($id)[0];
        $data['row'] = LibraryType::find($id);
        $data['action'] = 'edit';
        return View('admin.library-section.form', $data);
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * removing the library section.
    */
    public function destroy($id)
    {
        access_denied();
        if (!have_right('Delete-Library-Section'))
            access_denied();
        $model = LibraryType::find($id);
        $image_url =  $model->icon;
        if (!empty($image_url)) {
            if (file_exists(public_path($image_url))) {
                unlink($image_url);
            }
        }
        $data = [];
        $data['row'] = LibraryType::destroy($id);
        return redirect('admin/library-section')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading image for the library section.
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->icon) {
            $imageName = 'lib-icons' . time() . '.' . $request->icon->extension();
            if ($request->icon->move(public_path('images/lib-icons'), $imageName)) {
                $path =  'images/lib-icons/' . $imageName;
            }
        }
        return $path;
    }
}
