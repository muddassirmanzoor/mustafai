<?php

namespace App\Http\Controllers\User;

use App;
use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use App\Models\Admin\BusinessPlan;
use App\Models\Admin\Admin;
use App\Models\Admin\BusinessHeading;
use App\Models\Admin\Notification;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\PaymentMethodDetail;
use App\Models\BusinessBooster\ApplicationDateChangeRequest;
use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\BusinessBooster\BusinessPlanUserReliefDate;
use App\Models\User;
use Auth;
use Carbon\CarbonPeriod;
use File;
use PDF;
use Illuminate\Http\Request;
use Storage;
use Dompdf\FontMetrics;
use Dompdf\FontLoader;
// require_once 'vendor/autoload.php';

class BusinesPlanControllerbk extends Controller
{
    /**
     *Getting business plans and listing them
     */
    public function plans(Request $request)
    {
        // return 'test';
        if (!have_permission('View-Business-Booster')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $data = [];
        $type = $_GET['type'];
        $userID = Auth::user()->id;
        $plans = new BusinessPlan();
        $data['type'] = $type;
        $data['businessHeading'] = BusinessHeading::first();

        $data['applied'] = BusinessPlanApplication::where('applicant_id', Auth::user()->id)->groupBy('plan_id')->pluck('plan_id')->toArray();

        if ($type == 1) {
            if (have_permission('View-Upcoming-Business-Plans')) {
                $data['plans'] = $plans->availablePlans()->whereNotIn('id', $data['applied']);
            } else {
                $data['plans'] = collect([]);
            }
        } else if ($type == 2) {
            if (have_permission('View-Applied-Plans')) {
                $query = getQuery(App::getLocale(), ['name', 'description', 'term_conditions']);
                $query[] = 'id';
                $query[] = 'type';
                $query[] = 'invoice_amount';
                $query[] = 'total_invoices';
                $query[] = 'total_users';
                $query[] = 'start_date';

                $data['plans'] = $plans->select($query)->whereHas('applications', function ($query) use ($userID) {
                    $query->where('applicant_id', $userID);
                    $query->where('status', 0);
                })->get();
            } else {
                $data['plans'] = collect([]);
            }
        } else if ($type == 3) {
            if (have_permission('View-My-Activated-Plans')) {

                $query = getQuery(App::getLocale(), ['name', 'description', 'term_conditions']);
                $query[] = 'id';
                $query[] = 'type';
                $query[] = 'invoice_amount';
                $query[] = 'total_invoices';
                $query[] = 'total_users';
                $query[] = 'start_date';
                $query[] = 'end_date';

                $data['plans'] = $plans->select($query)->whereHas('applications', function ($query) use ($userID) {
                    $query->where('applicant_id', $userID);
                    $query->where('status', 1);
                })->get();
            } else {
                $data['plans'] = collect([]);
            }
        }
        return view('user.busines-plans', $data);
    }

    /**
     * Retrieving plan details for display/edit.
     */
    public function getPlanDetailsBack(Request $request)
    {
        $input = $request->all();
        $planId = $input['planId'];
        $planId = hashDecode($planId);
        $applicationId = $input['applicationId'];
        $plan = BusinessPlan::find($planId);

        if (empty($plan)) {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Plan Not Found.';
            echo json_encode($response);
            exit();
        }

        $startDate = date('Y-m-d', $plan->start_date);
        $endDate = date('Y-m-d', $plan->end_date);
        $period = CarbonPeriod::create($startDate, $endDate);

        $dates = [];
        // Iterate over the period
        foreach ($period as $date) {
            $dates[$date->format('F Y')][] = $date->format('d-m-Y');
        }

        $data = [];
        $data['application_id'] = $applicationId;
        $data['applied'] = $plan->applications()->pluck('selected_date')->toArray();

        $data['applied'] = array_map(function ($date) {
            return date('d-m-Y', $date);
        }, $data['applied']);

        $data['dates'] = $dates;
        $data['plan'] = $plan;
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();
        $data['action'] = 'add';

        if ($applicationId) {

            $applicationId = hashDecode($applicationId);
            $data['action'] = 'edit';
            $application = BusinessPlanApplication::find($applicationId);

            if (!empty($application)) {
                $data['application'] = $application;
                $data['witnesses'] = $application->applicationWitnesses;
                $data['pronote'] = $application->applicationPronotes()->where('type', 1)->first();
                $data['raseed_pronote'] = $application->applicationPronotes()->where('type', 2)->first();

                $accountDetails = [];

                foreach ($application->applicationAccounts as $key => $detail) {
                    $detail->type = ($detail->type == 1) ? 'sending' : 'recieving';
                    $accountDetails[$detail->type]['payment_method_id'][] = $detail->payment_method_id;
                    $accountDetails[$detail->type]['payment_method_field_' . $detail->payment_method_detail_id] = $detail->payment_method_detail_value;
                }
                $data['accounts'] = $accountDetails;
            }
        }

        $data['serial_number'] = date('Y-') . rand(2, 3) . '-' . time();
        $data['def_date'] = date('Y-m-d');
        $view = (string) View('user.partials.plan-apply-now-details-partial', $data);

        $response = [];
        $response['status'] = 1;
        $response['html'] = $view;

        echo json_encode($response);
        exit();
    }

    /**
     * Fetching plan details for display/edit and rendering the associated view.
     */
    public function getPlanDetails(Request $request)
    {
        $input = $request->all();
        $planId = $input['planId'];
        $planId = hashDecode($planId);

        $applicationId = $input['applicationId'];
        $plan = BusinessPlan::find($planId);
        $data['plan'] = BusinessPlan::find($planId);

        if (empty($plan)) {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Plan Not Found.';
            echo json_encode($response);
            exit();
        }
        $startDate = date('Y-m-d', $plan->start_date);
        $endDate = date('Y-m-d', $plan->end_date);
        $period = CarbonPeriod::create($startDate, $endDate);

        $dates = [];

        // Iterate over the period
        foreach ($period as $date) {
            $dates[$date->format('F Y')][] = $date->format('d-m-Y');
        }
        $data = [];
        $data['application_id'] = $applicationId;
        $data['applied'] = $plan->applications()->pluck('selected_date')->toArray();
        $data['applied'] = array_map(function ($date) {
            return date('d-m-Y', $date);
        }, $data['applied']);

        $data['dates'] = $dates;
        $data['plan'] = $plan;
        $data['payment_methods'] = PaymentMethod::where('status', 1)->get();
        $data['action'] = 'add';

        if ($applicationId) {
            $applicationId = hashDecode($applicationId);
            $data['action'] = 'edit';

            $application = BusinessPlanApplication::find($applicationId);

            if (!empty($application)) {
                $data['application'] = $application;
                $data['witnesses'] = $application->applicationWitnesses;
                $data['pronote'] = $application->applicationPronotes()->where('type', 1)->first();
                $data['raseed_pronote'] = $application->applicationPronotes()->where('type', 2)->first();

                $accountDetails = [];

                foreach ($application->applicationAccounts as $key => $detail) {
                    $detail->type = ($detail->type == 1) ? 'sending' : 'recieving';
                    $accountDetails[$detail->type]['payment_method_id'][] = $detail->payment_method_id;
                    $accountDetails[$detail->type]['payment_method_field_' . $detail->payment_method_detail_id] = $detail->payment_method_detail_value;
                }

                $data['accounts'] = $accountDetails;
            }
        }

        $data['serial_number'] = date('Y-') . rand(2, 3) . '-' . time();
        $data['def_date'] = date('Y-m-d');

        return view('user.apply-business-application', $data);
    }

    /**
     * Submitting a business plan application and handling related actions.
     */
    public function submitApplication(Request $request)
    {
        //dd($request);
        return 'ok';
        exit;
        $user = Auth::user();
        $input = $request->all();
        $selectedDate = strtotime($input['selected_date']);
        $planID = $input['plan_id'];
        $planID = hashDecode($planID);
        $input['plan_id'] = $planID;
        $alraedy = BusinessPlanApplication::where(['plan_id' => $planID, 'selected_date' => $selectedDate])->where('applicant_id', '<>', $user->id)->first();

        if (!empty($alraedy)) {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'This already chosen by someone.';
            echo json_encode($response);
            die();
        }
        $action = $input['action'];
        $input['selected_date'] = strtotime($input['selected_date']);
        $input['form_date'] = strtotime($input['form_date']);
        $input['form_pronote_date'] = strtotime($input['form_pronote_date']);
        $input['form_raseed_pronote_date'] = strtotime($input['form_raseed_pronote_date']);
        $input['check_date'] = strtotime($input['form_raseed_pronote_check_date']);
        $input['applicant_id'] = $user->id;

        if ($request->hasFile('form_image')) {
            $formImage = $request->file('form_image');
            $fileName = 'business-plan-application' . time() . rand(1, 100) . '.' . $formImage->extension();
            if ($formImage->move(public_path('application-images'), $fileName)) {
                $path = 'application-images/' . $fileName;
                $input['form_image'] = $path;
            }
        }

        if (isset($input['form_image'])) {
            if (\File::exists(public_path($input['form_old_image']))) {
                \File::delete(public_path($input['form_old_image']));
            }
        } else {
            $input['form_image'] = $input['form_old_image'];
        }

        if ($request->hasFile('form_witness1_nic_front')) {
            $formImage = $request->file('form_witness1_nic_front');
            $fileName = 'business-plan-witness-family-front' . time() . rand(1, 100) . '.' . $formImage->extension();
            if ($formImage->move(public_path('application-images'), $fileName)) {
                $path = 'application-images/' . $fileName;
                $input['form_witness1_nic_front'] = $path;
            }
        }

        if (isset($input['form_witness1_nic_front'])) {
            if (\File::exists(public_path($input['witness1_nic_front_old']))) {
                \File::delete(public_path($input['witness1_nic_front_old']));
            }
        } else {
            $input['form_witness1_nic_front'] = $input['witness1_nic_front_old'];
        }

        if ($request->hasFile('form_witness1_nic_back')) {
            $formImage = $request->file('form_witness1_nic_back');
            $fileName = 'business-plan-witness-family-back' . time() . rand(1, 100) . '.' . $formImage->extension();
            if ($formImage->move(public_path('application-images'), $fileName)) {
                $path = 'application-images/' . $fileName;
                $input['form_witness1_nic_back'] = $path;
            }
        }

        if (isset($input['form_witness1_nic_back'])) {
            if (\File::exists(public_path($input['witness1_nic_back_old']))) {
                \File::delete(public_path($input['witness1_nic_back_old']));
            }
        } else {
            $input['form_witness1_nic_back'] = $input['witness1_nic_back_old'];
        }

        if ($request->hasFile('form_witness2_nic_front')) {
            $formImage = $request->file('form_witness2_nic_front');
            $fileName = 'business-plan-witness-outsider-front' . time() . rand(1, 100) . '.' . $formImage->extension();
            if ($formImage->move(public_path('application-images'), $fileName)) {
                $path = 'application-images/' . $fileName;
                $input['form_witness2_nic_front'] = $path;
            }
        }

        if (isset($input['form_witness2_nic_front'])) {
            if (\File::exists(public_path($input['witness2_nic_front_old']))) {
                \File::delete(public_path($input['witness2_nic_front_old']));
            }
        } else {
            $input['form_witness2_nic_front'] = $input['witness2_nic_front_old'];
        }

        if ($request->hasFile('form_witness2_nic_back')) {
            $formImage = $request->file('form_witness2_nic_back');
            $fileName = 'business-plan-witness-outsider-back' . time() . rand(1, 100) . '.' . $formImage->extension();
            if ($formImage->move(public_path('application-images'), $fileName)) {
                $path = 'application-images/' . $fileName;
                $input['form_witness2_nic_back'] = $path;
            }
        }

        if (isset($input['form_witness2_nic_back'])) {
            if (\File::exists(public_path($input['witness2_nic_back_old']))) {
                \File::delete(public_path($input['witness2_nic_back_old']));
            }
        } else {
            $input['form_witness2_nic_back'] = $input['witness2_nic_back_old'];
        }

        if ($action == 'edit') {
            $input['application_id'] = hashDecode($input['application_id']);
            $applicationModel = BusinessPlanApplication::find($input['application_id']);
        } else {
            $applicationModel = new BusinessPlanApplication();
        }

        $isSend = false;

        if ($applicationModel) {
            $applicationModel->fill($input);
            $isSend = $applicationModel->save();
        }
        $response = [];

        if ($isSend) {

            if ($action == 'edit') {
                $applicationModel->applicationAccounts()->delete();
                $applicationModel->applicationWitnesses()->delete();
                $applicationModel->applicationPronotes()->delete();
            }

            foreach ($input['sending_payment_method_ids'] as $key => $methodId) {
                $paymentArray = [];
                $inc = 0;
                foreach ($input['sending_details'][$methodId] as $detailID => $detail) {
                    $paymentArray[$inc]['type'] = 1;
                    $paymentArray[$inc]['payment_method_id'] = $methodId;
                    $paymentArray[$inc]['payment_method_detail_id'] = $detailID;
                    $paymentArray[$inc]['payment_method_detail_value'] = $detail;
                    $inc++;
                }
                $applicationModel->applicationAccounts()->createMany($paymentArray);
            }

            foreach ($input['recieving_payment_method_ids'] as $key => $methodId) {
                $paymentArray = [];
                $inc = 0;
                foreach ($input['recieving_details'][$methodId] as $detailID => $detail) {
                    $paymentArray[$inc]['type'] = 2;
                    $paymentArray[$inc]['payment_method_id'] = $methodId;
                    $paymentArray[$inc]['payment_method_detail_id'] = $detailID;
                    $paymentArray[$inc]['payment_method_detail_value'] = $detail;
                }

                $applicationModel->applicationAccounts()->createMany($paymentArray);
            }

            $witnessArray = [];
            $witnessArray[0]['type'] = 1;
            $witnessArray[0]['name'] = $input['form_witness1_name'];
            $witnessArray[0]['guardian_name'] = $input['form_witness1_guardian'];
            $witnessArray[0]['nic'] = $input['form_witness1_nic'];
            $witnessArray[0]['relation'] = $input['form_witness1_relation'];
            $witnessArray[0]['business'] = $input['form_witness1_business'];
            $witnessArray[0]['contact_number'] = $input['form_witness1_contact_number'];
            $witnessArray[0]['nic_front'] = $input['form_witness1_nic_front'];
            $witnessArray[0]['nic_back'] = $input['form_witness1_nic_back'];

            $witnessArray[1]['type'] = 2;
            $witnessArray[1]['name'] = $input['form_witness2_name'];
            $witnessArray[1]['guardian_name'] = $input['form_witness2_guardian'];
            $witnessArray[1]['nic'] = $input['form_witness2_nic'];
            $witnessArray[1]['relation'] = $input['form_witness2_relation'];
            $witnessArray[1]['business'] = $input['form_witness2_business'];
            $witnessArray[1]['contact_number'] = $input['form_witness2_contact_number'];
            $witnessArray[1]['nic_front'] = $input['form_witness2_nic_front'];
            $witnessArray[1]['nic_back'] = $input['form_witness2_nic_back'];

            $applicationModel->applicationWitnesses()->createMany($witnessArray);

            $pronoteArray = [];
            $pronoteArray[0]['type'] = 1;
            $pronoteArray[0]['name'] = $input['form_pronote_name'];
            $pronoteArray[0]['guardian_name'] = $input['form_pronote_guardian'];
            $pronoteArray[0]['nic'] = $input['form_pronote_nic'];
            $pronoteArray[0]['address'] = $input['form_pronote_address'];
            $pronoteArray[0]['tehcil'] = $input['form_pronote_tehcil'];
            $pronoteArray[0]['district'] = $input['form_pronote_district'];
            $pronoteArray[0]['amount'] = $input['form_pronote_ammount'];
            $pronoteArray[0]['amount_half'] = $input['form_pronote_ammount_half'];
            $pronoteArray[0]['service_charges'] = $input['form_pronote_charge'];
            $pronoteArray[0]['check_number'] = null;
            $pronoteArray[0]['owner'] = null;
            $pronoteArray[0]['bank'] = null;
            $pronoteArray[0]['alabd'] = $input['form_pronote_alabd'];
            $pronoteArray[0]['date'] = $input['form_pronote_date'];

            $pronoteArray[1]['type'] = 2;
            $pronoteArray[1]['name'] = null;
            $pronoteArray[1]['guardian_name'] = null;
            $pronoteArray[1]['nic'] = null;
            $pronoteArray[1]['address'] = null;
            $pronoteArray[1]['tehcil'] = null;
            $pronoteArray[1]['district'] = null;

            $pronoteArray[1]['amount'] = $input['form_raseed_pronote_amount'];
            $pronoteArray[1]['amount_half'] = $input['form_raseed_pronote_amount_half'];
            $pronoteArray[1]['service_charges'] = null;
            $pronoteArray[1]['check_number'] = $input['form_raseed_pronote_check_number'];
            $pronoteArray[1]['owner'] = $input['form_raseed_pronote_owner'];
            $pronoteArray[1]['bank'] = $input['form_raseed_pronote_bank'];
            $pronoteArray[1]['alabd'] = $input['form_raseed_pronote_alabad'];
            $pronoteArray[1]['check_date'] = $input['check_date'];
            $pronoteArray[1]['date'] = $input['form_raseed_pronote_date'];

            $applicationModel->applicationPronotes()->createMany($pronoteArray);

            $response['status'] = 1;
            $response['message'] = __('app.appliction-submitted');
            $notification = Notification::create([
                'title' => $user->user_name . ' has applied to business plan',
                'title_english' => $user->user_name . ' has applied to business plan',
                'link' => route('busines_plans.edit', $planID),
                'module_id' => 35,
                'right_id' => 130,
                'ip' => request()->ip()
            ]);

            $admin = Admin::first();
            $admin->notifications()->attach($notification->id);
        } else {
            $response['status'] = 0;
            $response['message'] = 'Something went wrong.';
        }
        $response['plan_id'] = $planID;
        echo json_encode($response);
        exit();
    }

    /**
     * Submit date change application request.
     */
    public function submitDateChangeApplication(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::user()->id;

        $input['req_change_application_id'] = hashDecode($input['req_change_application_id']);

        $reqDate = strtotime($input['date_request']);

        $application = BusinessPlanApplication::where('id', $input['req_change_application_id'])->first();

        if (!empty($application)) {
            //     $user_selected_date = $reqDate;
            //     $results = DB::table('business_plans')
            //         ->select('start_date', 'end_date')
            //         ->where('plan_id', $application->plan_id)
            //         ->whereBetween('start_date', [$user_selected_date, DB::raw("end_date")])
            //         ->orWhereBetween('end_date', [DB::raw("start_date"), $user_selected_date])
            //         ->get();
            //    if(!empty($results)){
            //         dd("you are fake");
            //    }
            $dataBusiness = BusinessPlanApplication::where('selected_date', $reqDate)->where('plan_id', $application->plan_id)->where('status', 1)->first();

            $assignee_id = empty($dataBusiness) ? null : $dataBusiness->user->id;

            $modal = new ApplicationDateChangeRequest();
            $modal->application_id = $input['req_change_application_id'];
            $modal->user_id = $user_id;
            $modal->date = strtotime($input['date_request']);
            $modal->old_date = $application->selected_date;
            $modal->assignee_id = $assignee_id;
            $modal->status = 0;
            $isSave = $modal->save();

            $response = [];
            if ($isSave) {
                $response['status'] = 1;
                $response['message'] = __('app.req-submitted');
            } else {
                $response['status'] = 0;
                $response['message'] = 'Something went wrong.';
            }

            echo json_encode($response);
            die();
        } else {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Something went wrong.';

            echo json_encode($response);
            die();
        }
    }

    /**
     * View and manage invoices for an application.
     */
    public function invoices($applicationID)
    {
        $user = Auth::user();

        $data = [];
        $data['application_id'] = $applicationID;

        $applicationID = hashDecode($applicationID);
        $application = BusinessPlanApplication::find($applicationID);

        if (!empty($application)) {
            $paymentMethodsAccounts = [];

            foreach ($application->plan->businessPlanPaymentMethod as $pmethod) {

                $methodName = 'method_name_' . App::getLocale();
                $methodDetailName = 'method_fields_' . App::getLocale();

                $paymentMethodsAccounts[$pmethod->paymentMethod->{$methodName}]['id'] = $pmethod->paymentMethod->id;
                $paymentMethodsAccounts[$pmethod->paymentMethod->{$methodName}]['name'][] = $pmethod->paymentMethodDetail->{$methodDetailName} . ' : ' . $pmethod->payment_method_detail_value;
            }

            $accounts = [];

            foreach ($application->applicationAccounts()->where('type', 1)->get() as $pmethod) {

                $methodName = 'method_name_' . App::getLocale();
                $methodDetailName = 'method_fields_' . App::getLocale();

                $accounts[$pmethod->paymentMethod->{$methodName}]['id'] = $pmethod->paymentMethod->id;
                $accounts[$pmethod->paymentMethod->{$methodName}]['name'][] = $pmethod->paymentMethodDetail->{$methodDetailName} . ' : ' . $pmethod->payment_method_detail_value;
            }

            $plan = $application->plan;
            $startDate = date('Y-m-d', $plan->start_date);
            $endDate = date('Y-m-d', $plan->end_date);
            $period = CarbonPeriod::create($startDate, $endDate);

            $dates = [];
            // Iterate over the period
            foreach ($period as $date) {
                $dates[$date->format('F Y')][] = $date->format('d-m-Y');
            }

            $paidInvoices = BusinessPlanInvoice::where(['application_id' => $applicationID, 'status' => 1])->pluck('for_date')->toArray();
            $unPaidInvoices = BusinessPlanInvoice::where(['application_id' => $applicationID, 'status' => 0])->pluck('for_date')->toArray();
            $reliefDates = BusinessPlanUserReliefDate::where('application_id', $applicationID)->where('user_id', $user->id)->first();

            $paidInvoices = array_map(function ($date) {
                return date('d-m-Y', $date);
            }, $paidInvoices);
            $unPaidInvoices = array_map(function ($date) {
                return date('d-m-Y', $date);
            }, $unPaidInvoices);

            $data['dates'] = $dates;
            $data['relief_date'] = $reliefDates;
            $data['paid'] = $paidInvoices;
            $data['unpaid'] = $unPaidInvoices;
            $data['accounts'] = $accounts;
            $data['planAccounts'] = $paymentMethodsAccounts;
            $data['amount'] = $plan->invoice_amount;
            return view('user.busines-plan-invoices', $data);
        } else {
            return redirect()->back();
        }
    }

    /**
     *Display invoices and payment details for a specific application.
     */
    public function payInvoice(Request $request)
    {
        $input = $request->all();
        $request->application_id = hashDecode($request->application_id);
        $application = BusinessPlanApplication::find($request->application_id);

        if (empty($application) || $application->applicant_id != Auth::user()->id) {
            $resposne['status'] = 0;
            $resposne['message'] = 'You are not authorized.';
            echo json_encode($resposne);
            exit();
        }

        $dates = explode(',', $input['selected_dates']);

        if ($request->hasFile('invoice')) {
            $input['invoice'] = '';
            $invoice = $request->file('invoice');
            $fileName = 'application-invoices' . time() . rand(1, 100) . '.' . $invoice->extension();
            if ($invoice->move(public_path('application-invoices'), $fileName)) {
                $path = 'application-invoices/' . $fileName;
                $input['invoice'] = $path;
            }
        } else {
            $input['invoice'] = '';
        }

        $incr = 0;
        foreach ($dates as $key => $date) {

            $already = BusinessPlanInvoice::where([
                'application_id' => $request->application_id,
                'for_date' => strtotime($date),
            ])->first();

            if (empty($already)) {
                if (!$incr) {
                    $firstRecord =  BusinessPlanInvoice::create([
                        'application_id' => $request->application_id,
                        'for_date' => strtotime($date),
                        'invoice' => $input['invoice'],
                        'sender_account_id' => hashDecode($input['account_id']),
                        'plan_account_id' => hashDecode($input['plan_account_id'])
                    ]);
                }

                if ($incr) {
                    BusinessPlanInvoice::create([
                        'application_id' => $request->application_id,
                        'for_date' => strtotime($date),
                        'invoice' => $input['invoice'],
                        'parent_id' => ($firstRecord) ? $firstRecord->id : '',
                        'sender_account_id' => hashDecode($input['account_id']),
                        'plan_account_id' => hashDecode($input['plan_account_id'])
                    ]);
                }
                $incr++;
            }
        }

        $resposne['status'] = 1;
        $resposne['message'] = __('app.transection-submitted');
        echo json_encode($resposne);
        exit();
    }

    /**
     *Download the application as a PDF && Check user authorization and generate PDF.
     */
    function downloadApplication()
    {
        // dd("oko");
        $id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : 0;
        $id = hashDecode($id);
        $userID = Auth::user()->id;

        if ($id) {
            $application = BusinessPlanApplication::find($id);
            $data = [];
            $data['plan'] = BusinessPlan::find($application->plan->id);

            if (!empty($application)) {
                if ($userID != $application->applicant_id) {
                    echo "<script>window.close();</script>";
                } else {
                    // dd("else");
                    // Load the font
                    $fontPath = public_path('fonts/NotoNastaliqUrdu-Regular.ttf');
                    $font = FontLoader::load($fontPath, FontLoader::DEFAULT_FONT);
                    FontMetrics::get_font('NotoNastaliqUrdu', 'normal');

                    // Generate the PDF
                    $pdf = new Dompdf();
                    $pdf->set_option('isHtml5ParserEnabled', true);
                    $pdf->set_option('isRemoteEnabled', true);
                    $pdf->set_option('defaultFont', public_path('fonts/NotoNastaliqUrdu-Regular.ttf'));
                    $pdf->loadHtml('<h1>Urdu Text: یہ اردو متن ہے</h1>');
                    $pdf->setPaper('A4', 'portrait');
                    $pdf->render();
                    return $pdf->stream();
                    exit();

                    //testing end
                    $pdf = app('dompdf.wrapper');
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    $pdf->getDomPDF()->set_option("font_dir", base_path('public/fonts'));
                    $pdf->getDomPDF()->set_option("default_font", 'Jameel Noori Nastaleeq');
                    // $pdf = PDF::loadView('user.download-plan',$data)->setPaper('a2', 'potrait');
                    // dd(asset('fonts/'));
                    // dd("ok");
                    // dd(base_path('public/fonts/'));
                    $pdf = PDF::loadView('user.download-plan', $data);
                    // return $pdf->stream();

                    $fileName = Auth::user()->user_name . '_' . md5(time()) . '.pdf';
                    $savlfile = Storage::put('public/pdf/' . $fileName, $pdf->output());
                    $path = 'storage/pdf/' . $fileName;
                    return $pdf->download();

                    // return view('user.download-plan', $data);
                }
            }
        } else {
            // echo "<script>window.close();</script>";
        }
    }
}
