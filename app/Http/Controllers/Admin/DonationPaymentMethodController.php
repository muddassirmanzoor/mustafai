<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DonationPaymentMethod;
use App\Models\Admin\DonationPaymentMethodDetails;
use App\Models\Admin\DonationRecieptDetail;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodDetail;
use Illuminate\Http\Request;
use DataTables;
use DB;
use \Exception;
use Illuminate\Support\Facades\Validator;


class DonationPaymentMethodController extends Controller
{
    /**
     * listing donations payment.
    */
    public function index(Request $request)
    {

        if (!have_right('View-Donation-Methods'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $db_record = PaymentMethod::orderBy('created_at', 'DESC')->get();
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

                if (have_right('Edit-Donation-Methods')) {
                    $actions .= '<a class="btn btn-primary" style="margin-left:02px;" href="' . url("admin/donation-payment-method/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('Delete-Donation-Methods')) {
                    $actions .= '<form method="POST" action="' . url("admin/donation-payment-method/" . $row->id) . '" accept-charset="UTF-8" style="display:none;">';
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

            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.donation-payment-method.listing', $data);
    }

    /**
     * creating donations payment.
    */
    public function create()
    {
        if (!have_right('View-Donation-Methods'))
            access_denied();
        $data = [];
        $data['row'] = new DonationPaymentMethod();
        $data['payment_methods'] = PaymentMethod::all();
        $data['action'] = 'add';
        return View('admin.donation-payment-method.form', $data);
    }

    /**
     * storing donations payment.
    */
    public function store(Request $request)
    {
        $input = $request->input();
        // dd($input);
        DB::beginTransaction();
        try {
            if ($input['action'] == 'add') {
                if (!have_right('Create-Donation-Methods'))
                    access_denied();
                $modal = new DonationPaymentMethod();
                $modal->payment_method_id = $input['payment_method_id'];
                $modal->account_title = $input['account_title'];
                $isModalSave = $modal->save();
                $donationPaymentid = $modal->id;
                foreach ($request->method_details as $key => $val) {
                    if (!empty($val)) {
                        $modelRecieptDetails = new DonationPaymentMethodDetails();
                        $modelRecieptDetails->donation_pay_method_id = $donationPaymentid;
                        $modelRecieptDetails->payment_method_id = $input['payment_method_id'];
                        $modelRecieptDetails->payment_method_detail_id = $key;
                        $modelRecieptDetails->payment_method_field_value = $val;
                        $modelRecieptDetails->save();
                    }
                }
                if ($isModalSave) {
                    DB::commit();
                    return redirect('admin/donation-payment-method')->with('message', 'Data added Successfully');
                }
            } else {
                if (!have_right('Edit-Doantions'))
                    access_denied();

                foreach ($request->method_details as $methodKey => $methodValue) {
                    $model = DonationPaymentMethodDetails::find($methodKey);
                    $model->payment_method_field_value = $methodValue;
                    $model->update();
                }
                foreach ($input['account_ttile'] as $donationPayKey => $donationPayVal) {
                    DonationPaymentMethod::where('id', $donationPayKey)->update(['account_title' => $donationPayVal]);
                }
                DB::commit();
                return redirect('admin/donation-payment-method')->with('message', 'Data updated Successfully');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect('admin/donation-payment-method')->with('error', 'Account is Already Exist');
        }
    }

    public function show($id)
    {
        //
    }

    /**
     * edit donations payment.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Donation-Methods'))
            access_denied();
        $data = [];
        $data['id'] = $id;  // this is is payment method id
        $data['donationPaymentMethod'] = DonationPaymentMethod::where('payment_method_id', $id)->get();
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();
        $data['action'] = 'edit';
        return View('admin.donation-payment-method.form', $data);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    /**
     * payment method details for donations.
    */
    public function getPaymentFields($id, $action)
    {
        $data = [];
        $paymentMethodDetails = PaymentMethodDetail::where('payment_method_id', $id)->get();
        $donationPaymentMethodDetails = DonationPaymentMethod::where('payment_method_id', $id)->get();
        $paymentMethodDetailsCount = PaymentMethodDetail::where('payment_method_id', $id)->count();
        $data['payment_methods_details'] = $paymentMethodDetails;
        $data['payment_methods_details_count'] = $paymentMethodDetailsCount;
        $data['donationPaymentMethodDetails'] = $donationPaymentMethodDetails;
        $data['action'] = $action;
        $html = ($action == 'add') ? ((string) View('admin.partial.payment-method-details', $data)) : ((string) View('admin.partial.payment-method-details', $data));

        echo $html;
    }

    /**
     * checking and deleting donations payment.
    */
    public function deleteDonationPayment()
    {
        if (!have_right('Delete-Donation-Methods'))
            access_denied();
        $id = (int) $_GET['id'];
        $delete = DonationPaymentMethod::destroy($id);
        $response = [];
        if ($delete) {
            $response['status'] = 'deleted';
        } else {
            $response['status'] = 'failed';
        }

        echo json_encode($response);
        exit();
    }
}
