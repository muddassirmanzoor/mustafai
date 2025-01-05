<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BusinessPlan;
use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Validator;
use Auth;
use App\Models\Admin\PaymentMethod;
use App;
use App\Models\BusinessBooster\BusinessPlanPaidInvoice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BusinesPlanController extends Controller
{
    /**
     * Display a listing of the business plans.
    */
    public function index(Request $request)
    {
        // dd($request->ipinfo->all);
        if (!have_right('View-Business-Plans'))
            access_denied();

        $data = [];
        if ($request->ajax()) {
            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';
            $db_record = BusinessPlan::orderBy('created_at', 'DESC')->get();
            $db_record = $db_record->when($status, function ($q, $status) {
                $status = $status == 'active' ? 1 : 0;
                return $q->where('status', $status);
            });
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('name_english', function ($row) {
                $nameEnglish = "<a href='javascript:void(0)' data-business-id=$row->id data-toggle='modal' data-target='#showBusinessPlanModal' class='show_business_plan'>$row->name_english</a>";
                return $nameEnglish;
            });

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
            $datatable = $datatable->editColumn('name_englishHidden', function ($row) {
                $nameEnglish = $row->name_english;
                return $nameEnglish;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('Edit-Business-Plans')) {
                    $actions .= '<a class="btn btn-primary" href="' . url("admin/busines_plans/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';
                }

                if (have_right('View-Business-Plan-Applications')) {
                    $actions .= '<a href="' . url('admin/business-plans/applications?type=unapproved&plan_id=' . $row->id . '') . '" class="btn btn-info ml-2" title="Show"><i class="far fa-address-book"></i></a>';
                }

                if (have_right('Pay-Now-Business-Plans')) {
                    $actions .= '<a href="javascript:void(0)" data-plan-id="' . $row->id . '" data-toggle="modal"  onclick="showPayNow(this)" class="btn btn-success ml-2" title="Pay Now"><i class="fa fa-check-square" aria-hidden="true"></i></a>';
                }

                if (have_right('Plan-Invoices-Business-Plans')) {
                    $actions .= '<a href="' . url("admin/busines_plans/invoices/" . $row->id) . '" class="btn btn-secondary ml-2" title="Plan invoices"><i class="fa fa-share" aria-hidden="true"></i>
                    </a>';
                }
                if (have_right('Delete-Business-Plans')) {
                    $actions .= '<form method="POST" action="' . url("admin/busines_plans/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';
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

            $datatable = $datatable->rawColumns(['users', 'statusColumn', 'name_englishHidden', 'status', 'action', 'name_english']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.busines-plan.listing', $data);
    }

    /**
     * creating the business plans.
    */
    public function create()
    {
        if (!have_right('Create-Business-Plans'))
            access_denied();

        $data = [];
        $data['row'] = new BusinessPlan();
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();
        $data['action'] = 'add';
        return View('admin.busines-plan.form', $data);
    }

    /**
     * storing the business plans.
    */
    public function store(Request $request)
    {
        $input = $request->all();
        $userID = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name_english' => 'required|string',
            'type' => 'required',
            'total_invoices' => 'required',
            'invoice_amount' => 'required',
            'total_users' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);

        $input['start_date'] = strtotime($input['start_date']);
        $input['end_date'] = strtotime($input['end_date']);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        $sending_payment_method_ids = (isset($input['sending_payment_method_ids'])) ? $input['sending_payment_method_ids'] : [];
        $sending_details = (isset($input['sending_details'])) ? $input['sending_details'] : [];

        if ($input['action'] == 'add') {
            unset($input['action']);
            if (!have_right('Create-Business-Plans'))
                access_denied();

            $model = new BusinessPlan();
            unset($input['sending_payment_method_ids']);
            unset($input['sending_details']);
            $model->fill($input);
            $model->fill(['created_by' => $userID]);
            if ($model->save()) {
                $input = $request->all();
                foreach ($sending_payment_method_ids as $key => $methodId) {
                    $paymentArray = [];
                    $inc = 0;
                    foreach ($sending_details[$methodId] as $detailID => $detail) {
                        $paymentArray[$inc]['payment_method_id'] = $methodId;
                        $paymentArray[$inc]['payment_method_detail_id'] = $detailID;
                        $paymentArray[$inc]['payment_method_detail_value'] = $detail;
                        $inc++;
                    }

                    $model->businessPlanPaymentMethod()->createMany($paymentArray);
                }
            }

            $id = $model->id;
            $msg = 'Data added Successfully';
        } else {
            if (!have_right('Edit-Business-Plans'))
                access_denied();

            unset($input['action']);
            unset($input['sending_payment_method_ids']);
            unset($input['sending_details']);
            $id = $input['id'];
            $model = BusinessPlan::find($id);
            $model->fill($input);
            if ($model->update()) {
                $model->businessPlanPaymentMethod()->delete();
                $input = $request->all();
                foreach ($sending_payment_method_ids as $key => $methodId) {
                    $paymentArray = [];
                    $inc = 0;
                    $input = $request->all();
                    foreach ($sending_details[$methodId] as $detailID => $detail) {
                        $paymentArray[$inc]['payment_method_id'] = $methodId;
                        $paymentArray[$inc]['payment_method_detail_id'] = $detailID;
                        $paymentArray[$inc]['payment_method_detail_value'] = $detail;
                        $inc++;
                    }
                    $model->businessPlanPaymentMethod()->createMany($paymentArray);
                }
            }
            $msg = 'Data updated Successfully';
        }

        return redirect('admin/busines_plans')->with('message', $msg);
    }

    /**
     * edit the business plans.
    */
    public function edit($id)
    {
        if (!have_right('Edit-Business-Plans'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $data['row'] = BusinessPlan::find($id);

        $accountDetails = [];

        foreach ($data['row']->businessPlanPaymentMethod as $key => $detail) {
            $detail->type = 'sending';
            $accountDetails[$detail->type]['payment_method_id'][] = $detail->payment_method_id;
            $accountDetails[$detail->type]['payment_method_field_' . $detail->payment_method_detail_id] = $detail->payment_method_detail_value;
        }
        $data['accounts'] = $accountDetails;
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();
        $data['action'] = 'edit';
        // dd($data);

        return View('admin.busines-plan.form', $data);
    }

    /**
     * removing the business plans.
    */
    public function destroy($id)
    {
        if (!have_right('Delete-Business-Plans'))
            access_denied();

        $data = [];
        // $id = Hashids::decode($id)[0];
        $data['row'] = BusinessPlan::destroy($id);
        return redirect('admin/busines_plans')->with('message', 'Data deleted Successfully');
    }

    /**
     * get specific business plans.
    */
    public function show(Request $request)
    {
        $business = BusinessPlan::with('application')->find($request)->first();

        $html = view('admin.partial.show-business-plan', get_defined_vars())->render();

        return response()->json(['status' => 200, 'html' => $html]);
    }

    /**
     * pay details of business plans.
    */
    function payNowDetails(Request $request)
    {
        $input = $request->all();

        if (isset($input['planID'])) {
            $selectedDate = (isset($input['selectedDate'])) ? $input['selectedDate'] : '';

            $data = $this->getPlanDetails($input['planID'], $selectedDate);

            if ($data['status']) {
                $paidInvoice = BusinessPlanPaidInvoice::where(['plan_id' => $data['plan_id'], 'application_id' => $data['application_id'], 'user_id' => $data['user_id'], 'for_date' => $data['selected_date']])->first();

                if (!empty($paidInvoice)) {
                    $response['status'] = 0;
                    $response['message'] = 'Today invoice already paid to user.';
                    echo json_encode($response);
                    die();
                }
            }

            echo json_encode($data);
            die();
        }
    }

    /**
     * pay the business plans.
    */
    function payNow(Request $request)
    {
        $input = $request->all();
        if (isset($input['plan_id'])) {
            $selectedDate = (isset($input['selected_date'])) ? $input['selected_date'] : '';
            $data = $this->getPlanDetails($input['plan_id'], $selectedDate);

            if ($data['status']) {
                $invoice = '';
                if ($request->hasFile('reciept')) {
                    // $reciept = $request->file('reciept');
                    // $fileName = 'application-paid-invoices' . time() . rand(1, 100) . '.' . $reciept->extension();
                    // if ($reciept->move(public_path('application-paid-invoices'), $fileName)) {
                    //     $path =  'application-paid-invoices/' . $fileName;
                    //     $invoice = $path;
                    // }
                    $path = uploadS3File($request , "application-paid-invoices" ,"reciept","application-paid-invoices",$filename = null);
                    $invoice = $path;
                }

                $modal = new BusinessPlanPaidInvoice();
                $modal->plan_id = $input['plan_id'];
                $modal->application_id = $data['application_id'];
                $modal->user_id = $data['user_id'];
                $modal->for_date = $data['selected_date'];
                $modal->amount_required = $data['total_amount'];
                $modal->amount = $input['amount'];
                $modal->user_account_id = $input['account_id'];
                $modal->invoice = $invoice;

                $isSave = 0;

                $isSave = $modal->save();

                $response = [];

                if ($isSave) {
                    $response['status'] = 1;
                    $response['message'] = "Reciept paid to user.";
                } else {
                    $response['status'] = 0;
                    $response['message'] = "Something went wrong.";
                }
                echo json_encode($response);
                die();
            }
            echo json_encode($data);
            die();
        }
    }

    /**
     * getting business plans.
    */
    function getPlanDetails($planID, $selectedDate = '')
    {
        $plan = BusinessPlan::find($planID);

        $data = [];

        if (!empty($plan)) {
            if ($plan->start_date > strtotime(date('Y-m-d'))) {
                $data['status'] = 0;
                $data['message'] = 'Plan not started yet.';
            } else {
                $totalApplications = $plan->applications()->where('status', 1)->get();

                if (empty($totalApplications)) {
                    $data['status'] = 0;
                    $data['message'] = 'There is no application for this plan.';
                    return $data;
                }

                $date = (!empty($selectedDate)) ? strtotime($selectedDate) :  strtotime(date('Y-m-d'));

                $data['status'] = 1;
                $data['total_required_users'] = $plan->total_users;
                $data['total_approved_users'] = count($totalApplications);
                $data['total_amount'] = $plan->total_users * $plan->invoice_amount;
                $data['total_recieved_amount'] = $plan->invoice_amount * BusinessPlanInvoice::whereIn('application_id', $totalApplications->pluck('id')->toArray())->where('status', 1)->where('for_date', $date)->count();

                $planApplication = $plan->applications()->where('status', 1)->where('selected_date', $date)->first();

                if (empty($planApplication)) {
                    $data['status'] = 0;
                    $data['message'] = 'There is no application for today date.';
                    return $data;
                }

                $data['application_id'] = $planApplication->id;
                $data['plan_id'] = $planID;
                $data['selected_date'] = $planApplication->selected_date;
                $data['selected_date_human'] = date('d-m-Y', $planApplication->selected_date);
                $data['user_id'] = $planApplication->user->id;
                $data['user_name'] = $planApplication->user->user_name;

                $accounts = [];

                foreach ($planApplication->applicationAccounts()->where('type', 1)->get() as $pmethod) {
                    $methodName = 'method_name_english';
                    $methodDetailName = 'method_fields_english';

                    $accounts[$pmethod->paymentMethod->{$methodName}]['id'] = $pmethod->paymentMethod->id;
                    $accounts[$pmethod->paymentMethod->{$methodName}]['name'][] = $pmethod->paymentMethodDetail->{$methodDetailName} . ' : ' . $pmethod->payment_method_detail_value;
                }
                $data['accounts'] = $accounts;
            }
            return $data;
        }
    }

    /**
     * getting invoices of business plans.
    */
    function planInvoices($planId)
    {
        $data = [];
        $plan = BusinessPlan::find($planId);
        $response = [];

        if (!empty($plan)) {
            $startDate = date('Y-m-d', $plan->start_date);
            $endDate = date('Y-m-d', $plan->end_date);
            $period = CarbonPeriod::create($startDate, $endDate);

            // Iterate over the period
            foreach ($period as $key => $date) {
                $application = $plan->applications()->where('selected_date', strtotime($date))->first();

                $userName = 'N/A';
                $status = 'Pending';

                if (!empty($application)) {
                    $userName = $application->user->user_name;
                    $status = (!empty($application->applicationPaidInvoice)) ? 'Paid' : 'Pending';
                }

                $response[$key]['plan_id'] = $plan->id;
                $response[$key]['date'] = $date->format('d-m-Y');
                $response[$key]['user_name'] = $userName;
                $response[$key]['status'] = $status;
            }
        }

        $data['results'] = $response;
        $data['planID'] = $planId;
        return view('admin.busines-plan.invoices.listing', $data);
    }

    /**
     * default default users.
    */
    public function defaulter()
    {
        $applicationId = 1;
        $application = BusinessPlanApplication::find($applicationId);
        $plan = $application->plan;

        $startDate = date('Y-m-d', $plan->start_date);
        $endDate = date('Y-m-d', $plan->end_date);

        $period = CarbonPeriod::create($startDate, $endDate);

        $paidInvoices = BusinessPlanInvoice::where('application_id', $applicationId)->pluck('for_date')->toArray();
        $paidInvoices = array_map(function ($date) {
            return date('d-m-Y', $date);
        }, $paidInvoices);

        $planDates = [];
        foreach ($period as $date) {
            $planDates[] = $date->format('d-m-Y');
        }

        $todayDate = today()->format('d-m-Y');

        $defaulterDates = [];
        foreach ($planDates as $planDate) {
            if ($planDate <= $todayDate && !in_array($planDate, $paidInvoices)) $defaulterDates[] = $planDate;
        }
    }
}
