<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Vendor;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;
use DataTables;

class VendorController extends Controller
{
    /**
     * listing the Vendor Collection
    */
    public function index(Request $request)
    {
        if (!have_right('View-Vendors'))
            access_denied();
        $data = [];
        if ($request->ajax()) {
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $db_record = Vendor::orderBy('created_at', 'DESC')->get();
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
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Vendors')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/vendors/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Vendors')) {
                    $actions .= '<form method="POST" action="' . url("admin/vendors/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.vendors.listing', $data);
    }

    /**
     * creating the Vendor Collection
    */
    public function create()
    {
        if (!have_right('Create-Vendors'))
            access_denied();
        $data = [];
        $data['row'] = new Vendor;
        $data['action'] = 'add';
        return View('admin.vendors.form', $data);
    }

    /**
     * storing the Vendor Collection
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name_english' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            if (!have_right('Create-Vendors'))
                access_denied();

            $model = new Vendor();
            $model->fill($input);
            $model->save();
            return redirect('admin/vendors')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Vendors'))
                access_denied();

            $id = $input['id'];
            $model = Vendor::find($id);

            $model->fill($input);
            $model->update();
            return redirect('admin/vendors')->with('message', 'Data updated Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    /**
     * edit the Vendor Collection
    */
    public function edit($id)
    {
        if (!have_right('Edit-Vendors'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = Vendor::find($id);
        $data['action'] = 'edit';
        return View('admin.vendors.form', $data);
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * removing the Vendor Collection
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Vendors'))
            access_denied();

        $data = [];
        $data['row'] = Vendor::destroy($id);
        return redirect('admin/vendors')->with('message', 'Data deleted Successfully');
    }
}
