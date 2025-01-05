<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\OfficeAddress;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OfficeAddressController extends Controller
{
    /**
     * listing the Office Addresses.
    */
    public function index(Request $request)
    {
        if (!have_right('View-Office-Address'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = OfficeAddress::all();
            $datatable = Datatables::of($db_record);

            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('featured', function ($row) {
                if ($row->featured == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if (have_right('Featured-Office-Address')) {

                    $featured = '<label class="switch"> <input type="checkbox" class="is_featured" id="chk_' . $row->id . '" name="is_featured" onclick="is_featured($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                    return $featured;
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Office-Address')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/office-addresses/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Office-Address')) {
                    $actions .= '<form method="POST" action="' . url("admin/office-addresses/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['status', 'featured', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.office-address.listing', $data);
    }

    /**
     * creating the Office Addresses.
    */
    public function create()
    {
        if (!have_right('Create-Office-Address'))
            access_denied();

        $data = [];
        $data['row'] = new OfficeAddress();
        $data['action'] = 'add';
        return View('admin.office-address.form', $data);
    }

    /**
     * storing the Office Addresses.
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'address_english' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Office-Address'))
                access_denied();

            $model = new OfficeAddress();
            $model->fill($input);
            $model->save();

            return redirect('admin/office-addresses')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Office-Address'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = OfficeAddress::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/office-addresses')->with('message', 'Data updated Successfully');
        }
    }

    /**
     * edit the Office Addresses.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Office-Address'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = OfficeAddress::find($id);
        $data['action'] = 'edit';
        return View('admin.office-address.form', $data);
    }

    /**
     * removing the Office Addresses.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Office-Address'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = OfficeAddress::destroy($id);
        return redirect('admin/office-addresses')->with('message', 'Data deleted Successfully');
    }

    /**
     * get Featured Addresses.
    */
    public function setFeaturedAddress($id = null)
    {
        if (!have_right('Featured-Office-Address'))
            access_denied();
        OfficeAddress::where('featured', 1)->update(['featured' => 0]);
        $update_product = OfficeAddress::where('id', $id)->update(['featured' => $_GET['status']]);

        if ($update_product) {

            echo true;
            exit();
        }
    }
}
