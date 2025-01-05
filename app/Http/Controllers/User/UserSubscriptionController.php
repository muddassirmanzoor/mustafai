<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\UserSubscription;
use Auth;
use DataTables;

class UserSubscriptionController extends Controller
{
    /**
     *Getting User Subscription List
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user()->id;

            $db_record = UserSubscription::where('user_id', $user);
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('start_date', function ($row) {
                $date = date('d-m-Y', $row->subscription_start_date);
                return $date;
            });

            $datatable = $datatable->editColumn('end_date', function ($row) {
                // $date = date('d-m-Y',($row->subscription_start_date + 720*60*60));
                $date = date('d-m-Y', ($row->subscription_end_date));
                return $date;
            });

            $datatable = $datatable->editColumn('reciept', function ($row) {
                $status = 'N/A';
                if (!empty($row->reciept)) {
                    $status = '<a class="btn btn-primary" href="' . asset($row->reciept) . '" title="Reciept"><i class="far fa-eye"></i></a>';
                }
                return $status;
            });

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                }
                return $status;
            });

            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="label label-danger">Pending</span>';
                if ($row->is_paid == 1) {
                    $status = '<span class="label label-success">Paid</span>';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                $actions .= '<a class="btn btn-primary" href="' . url("admin/roles/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i>Pay Now</a>';

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['start_date', 'end_date', 'reciept', 'status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('user.subscriptions', get_defined_vars());
    }
}
