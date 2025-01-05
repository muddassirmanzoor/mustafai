<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Bank;
use Illuminate\Http\Request;
use App\Models\Admin\BankAccount;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    /**
     *listing bank accounts
    */
    public function index(Request $request)
    {
        if (!have_right('View-Bank-Account'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = BankAccount::all();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('bank_name', function ($row) {
                $bank_name = $row->bank;
                return !empty($bank_name) ? $bank_name->name_english : '';
            });
            $datatable = $datatable->addColumn('module_name', function ($row) {
                if ($row->module_id == 1) {
                    $module_name = 'Donation';
                }
                if ($row->module_id == 2) {
                    $module_name = 'Business Plan';
                }
                if ($row->module_id == 3) {
                    $module_name = 'Mustafai Store';
                }
                if ($row->module_id == 4) {
                    $module_name = 'Monthly Subscription	';
                }
                return $module_name;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Bank-Account')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/bank-accounts/" . $row->id) . '/edit' . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Bank-Account')) {
                    $actions .= '<form method="POST" action="' . url("admin/bank-accounts/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

        return view('admin.bank-accounts.listing', $data);
    }

    /**
     *creating bank accounts
    */
    public function create()
    {
        if (!have_right('Create-Bank-Account'))
            access_denied();

        $data = [];
        $data['row'] = new BankAccount();
        $data['action'] = 'add';
        $data['banks'] = Bank::where('status', 1)->get();
        return View('admin.bank-accounts.form', $data);
    }

    /**
     *storing bank accounts
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'account_title_english' => 'required|string|max:200',
            'bank_id' => 'required',
            'module_id' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Bank-Account'))
                access_denied();
            $model = new BankAccount();
            $model->fill($input);
            $model->save();

            return redirect('admin/bank-accounts')->with('message', 'Data added Successfully');
        } else {
            if (!have_right('Edit-Bank-Account'))
                access_denied();

            unset($input['action']);
            $id = $input['id'];
            $model = BankAccount::find($id);
            $model->fill($input);
            $model->update();
            return redirect('admin/bank-accounts')->with('message', 'Data updated Successfully');
        }
    }

    /**
     *edit bank accounts
    */
    public function edit($id)
    {
        if (!have_right('Edit-Bank-Account'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = BankAccount::find($id);
        $data['action'] = 'edit';
        $data['banks'] = Bank::where('status', 1)->get();
        return View('admin.bank-accounts.form', $data);
    }

    /**
     *destroy bank accounts
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Bank-Account'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = BankAccount::destroy($id);
        return redirect('admin/bank-accounts')->with('message', 'Data deleted Successfully');
    }
}
