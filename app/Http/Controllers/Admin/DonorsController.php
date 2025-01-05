<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Donor;
use App\Models\Admin\District;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use DataTables;

class DonorsController extends Controller
{
    /**
     * listing the donors.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Blood-Donors'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $db_record = Donor::orderBy('id', 'DESC')->get();
            $db_record = $db_record->when($status, function ($q, $status) {
                $status = $status == 'active' ? 1 : 0;
                return $q->where('status', $status);
            });
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
            $datatable = $datatable->editColumn('full_name', function ($row) {
                if (empty($row->user_id)) {
                    $field_name = $row->full_name;
                } else {
                    $field_name = $row->user->user_name;
                }
                return $field_name;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Blood-Donors')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/donors/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Blood-Donors')) {
                    $actions .= '<form method="POST" action="' . url("admin/donors/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action', 'full_name']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.donors.listing', $data);
    }

    /**
     * creating the donors.
    */
    public function create()
    {
        if (!have_right('Create-Blood-Donors'))
            access_denied();

        $data = [];
        $data['row'] = new Donor();
        $data['cities'] = City::where('status', 1)->get();
        $data['action'] = 'add';
        return view('admin.donors.form', $data);
    }

    /**
     * storing the donors.
    */
    public function store(Request $request)
    {
        // dd($request);
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:200',
            'email' => 'required|string|max:200',
            'phone_number' => 'required|string|max:200',
            'status' => 'required',
            'blood_group' => 'required',
            'dob' => 'required',
            'eligible_after' => 'nullable'
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Blood-Donors'))
                access_denied();
            if (isset($input['image'])) {
                $imagePath = uploadS3File($request , "images/donors" ,"image","donors",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
            }
            $model = new Donor();
            $model->fill($input);
            $model->save();

            return redirect('admin/donors')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Blood-Donors'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = Donor::find($id);
            if (isset($input['image'])) {
                deleteS3File($model->image);
                $imagePath = uploadS3File($request , "images/donors" ,"image","donors",$filename = null);
                //$imagePath = $this->uploadImage($request);
                $input['image'] = $imagePath;
                // $image_url =  $model->image;
                // if (file_exists(public_path($image_url))) {
                //     unlink($image_url);
                // }
            } else {
                unset($input['image']);
            }
            $model->fill($input);
            $model->update();
            return redirect('admin/donors')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the donors.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Blood-Donors'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Donor::find($id);
        $data['cities'] = City::where('status', 1)->get();
        $data['action'] = 'edit';
        return View('admin.donors.form', $data);
    }

    /**
     * removing the donors.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Blood-Donors'))
            access_denied();
        $model = Donor::find($id);
        $image_url =  $model->image;
        // if (file_exists(public_path($image_url))) {
        //     unlink($image_url);
        // }
        deleteS3File($image_url);
        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = Donor::destroy($id);
        return redirect('admin/donors')->with('message', 'Data deleted Successfully');
    }

    /**
     * uploading image for the donors.
    */
    public function uploadImage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $imageName = 'donors' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/donors'), $imageName)) {
                $path =  'images/donors/' . $imageName;
            }
        }
        return $path;
    }
}
