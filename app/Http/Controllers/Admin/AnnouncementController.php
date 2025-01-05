<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     *listing announcements
    */
    public function index(Request $request)
    {
        if (!have_right('view-announcement'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = Announcement::all();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('edit-announcement')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/announcements/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('delete-announcement')) {
                    $actions .= '<form method="POST" action="' . url("admin/announcements/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" style="margin-left:02px;"  type="button" onclick="showConfirmAlert(this)" title="Delete">';
                    $actions .= '<i class="far fa-trash-alt"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.announcements.listing', $data);
    }

    /**
     *creating announcements
    */
    public function create()
    {
        if (!have_right('add-announcement'))
            access_denied();

        $data = [];
        $data['row'] = new Announcement();
        $data['action'] = 'add';
        return View('admin.announcements.form', $data);
    }

    /**
     *storing announcements
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'title_english' => 'required|string|max:200',
            'title_urdu' => 'required|string|max:200',
            'title_arabic' => 'required|string|max:200',
            'description_english' => 'required',
            'description_urdu' => 'required',
            'description_arabic' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if (isset($input['image'])) {
            $imagePath = $this->uploadImage($request);
            $input['image'] = $imagePath;
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('add-announcement'))
                access_denied();

            $model = new Announcement();
            $model->fill($input);
            $model->save();

            return redirect('admin/announcements')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('edit-announcement'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = Announcement::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/announcements')->with('message', 'Data updated Successfully');
        }
    }

    /**
     *edit announcements
    */
    public function edit($id)
    {
        if (!have_right('edit-announcement'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Announcement::find($id);
        $data['action'] = 'edit';
        return View('admin.announcements.form', $data);
    }

    /**
     *deleting announcements
    */
    public function destroy($id)
    {
        if (!have_right('delete-announcement'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = Announcement::destroy($id);
        return redirect('admin/announcements')->with('message', 'Data deleted Successfully');
    }

    /**
     *uploading image for announcements
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $imageName = 'announcement' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/announcements-images'), $imageName)) {
                $path =  'images/announcements-images/' . $imageName;
            }
        }
        return $path;
    }
}
