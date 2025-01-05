<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription\NewSubscription;
use DataTables;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    /**
     * listing the Subscriptions
    */
    public function index(Request $request)
    {
        if (!have_right('View-Subscriptions')) {
            access_denied();
        }

        $data = [];
        if ($request->ajax()) {
            $db_record = NewSubscription::orderBy('created_at', 'DESC')->get();
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
                $status = 'Disable';
                if ($row->status == 1) {
                    $status = 'Active';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('changeStatus', function ($row) {
                if ($row->status == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if (have_right('Change-Subscription-Status')) {

                    $subscription_status = '<label class="switch"> <input type="checkbox" class="is_active" id="chk_' . $row->id . '" name="is_active" onclick="is_active($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';
                    return $subscription_status;
                } else {
                    return '<span class=" badge badge-danger">No Permission</span>';
                }
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Subscriptions')) {
                    $actions .= '<a class="btn btn-primary d-none" href="' . url("admin/ceomessage/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Subscriptions')) {
                    $actions .= '<form method="POST" action="' . url("admin/subscriptions/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['statusColumn', 'status', 'action', 'changeStatus']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.subscription.listing', $data);
    }

    /**
     * removing the Subscriptions
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Subscriptions')) {
            access_denied();
        }

        $data = [];
        $data['row'] = NewSubscription::destroy($id);
        return redirect('admin/subscriptions')->with('message', 'Data deleted Successfully');
    }

    /**
     * Changing status of the Subscriptions
    */
    public function ChangeSubscriptionStatus($id = null)
    {
        if (!have_right('Change-Subscription-Status')) {
            access_denied();
        }
        $update_product = NewSubscription::where('id', $id)->update(['status' => $_GET['status']]);

        if ($update_product) {

            echo true;
            exit();
        }
    }
}
