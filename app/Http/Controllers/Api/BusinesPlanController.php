<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessPlansResource;
use App\Http\Resources\UserResource;
use App\Models\Admin\BusinessPlan;
use App\Models\Admin\PaymentMethod;
use App\Models\BusinessBooster\ApplicationDateChangeRequest;
use App\Models\User;
use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\BusinessBooster\BusinessPlanUserReliefDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
// use File;
use Mpdf\Mpdf;
use Storage;
use Illuminate\Http\File;

class BusinesPlanController extends Controller
{
    /**
     * get business plans
     * @param Request $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function getBusinessPlans(Request $request)
    {
        $input = $request->all();
        $rules = [
            'user_id' => 'required',
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The :attribute field is required.'
        ]);

        if ($validator->fails())
            return response()->json([
            'status' => 0,
            'message' => 'validation fails',
            'data' => $validator->errors()->toArray()
        ]);

        $type   = $input['type'];
        $userID = $input['user_id'];
        $lang   = $input['lang'];
        $plans = new BusinessPlan();
        $data['type'] = $type;
        $data['applied'] = BusinessPlanApplication::where('applicant_id',$userID)->groupBy('plan_id')->pluck('plan_id')->toArray();
        //upcoming plans
        if($type == 1)
        {
            $plans = $plans->availablePlans($lang)->whereNotIn('id',$data['applied']);
            // dd($plans);
        }
        // Applied Plans
        else if($type == 2)
        {
            $query = getQuery($request->lang, ['name','description','term_conditions']);
                $query[] = 'id';
                $query[] = 'type';
                $query[] = 'invoice_amount';
                $query[] = 'total_invoices';
                $query[] = 'total_users';
                $query[] = 'start_date';
                $query[] = 'end_date';
            $plans = $plans->select($query)->whereHas('applications',function($query) use ($userID){
                $query->where('applicant_id',$userID);
                $query->where('status',0);
            })->get();


        }
        // My Activate Plans
        else if($type == 3)
        {
            $query = getQuery($request->lang, ['name','description','term_conditions']);
                $query[] = 'id';
                $query[] = 'type';
                $query[] = 'invoice_amount';
                $query[] = 'total_invoices';
                $query[] = 'total_users';
                $query[] = 'start_date';
                $query[] = 'end_date';

                $plans = $plans->select($query)->whereHas('applications', function ($query) use ($userID) {
                    $query->where('applicant_id', $userID);
                    $query->where('status', 1);
                })->get();
            // $plans = $plans->whereHas('applications',function($query) use ($userID){
            //     $query->where('applicant_id',$userID);
            //     $query->where('status',1);
            // })->get();

        }

        $result = [];
        foreach($plans as $key => $plan)
        {
            $result[$key]['bbId'] =  $plan->id;
            $result[$key]['planName'] =  $plan->name;
            $result[$key]['invoiceAmount'] =  $plan->invoice_amount;
            $result[$key]['totalInvoice'] =  $plan->total_invoices;
            $result[$key]['totalUsers'] =  $plan->total_users;
            $result[$key]['numberApplications'] =  $plan->applications()->count();
            $result[$key]['bbDetail'] =  $plan->description;
            $result[$key]['term_conditions'] =  $plan->term_conditions;
            $result[$key]['start_date'] =  Carbon::createFromTimestamp($plan->start_date)->format('d-m-Y');
            if($type == 2 || $type == 3)
            {
                $application = $plan->applications()->where('applicant_id',$userID)->first();
                $result[$key]['application_id'] =  (!empty($application)) ? $application->id : '';
            }
        }
        return BusinessPlansResource::collection($result);
    }
    /**
     * get business plans dates
    */
    public function getBusinesPlanDates(Request $request)
    {
        $input = $request->all();

        $planId = $input['planID'];

        $plan = BusinessPlan::find($planId);
        $applications = $plan->applications();

        $applied = (!empty($applications)) ? $applications->pluck('selected_date')->toArray() : [];

        $startDate = date('Y-m-d', $plan->start_date);
        $endDate = date('Y-m-d', $plan->end_date);
        $period = CarbonPeriod::create($startDate, $endDate);

        $dates = [];
        // Iterate over the period
        $inc = 0;
        $dateText = '';
        foreach ($period as $key => $date) {

            if(!$key)
            {
                $dateText = $date->format('F-Y');
            }

            if( $dateText != $date->format('F-Y') )
            {
                $inc = 0;
                $dateText = $date->format('F-Y');
            }

            $dates[$date->format('F Y')][$inc]['type'] = ( in_array(strtotime($date),$applied) ) ? 'Applied':'Available';
            $dates[$date->format('F Y')][$inc]['date'] = $date->format('d-m-Y');
            $inc++;
        }
        return response()->json([
            'success'=>1,
            'message'=>'success',
            'data'   => $dates,
        ]);
        // echo json_encode($dates);
        // exit();
    }
    /**
     * submit business plans application
    */
    public function submitApplication(Request $request)
    {
        $input = $request->all();
        // return response()->json(
        //     $input
        // );
        $userID = $input['user_id'];
        $planID = $input['plan_id'];
        $planID = $planID;

        $user = '';
        if($userID)
        {
            $user = User::find($userID);
        }

        if(empty($user))
        {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Login user not found.';
            echo json_encode($response);die();
        }

        $plan = '';
        if($planID)
        {
            $plan = BusinessPlan::find($planID);
        }

        if(empty($plan))
        {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Plan not found.';
            echo json_encode($response);die();
        }

        $selectedDate = strtotime($input['selected_date']);
        $input['plan_id'] = $planID;

        $alraedy = BusinessPlanApplication::where(['plan_id'=>$planID,'selected_date'=>$selectedDate])->where('applicant_id','<>',$user->id)->first();

        if(!empty($alraedy))
        {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'This already chosen by someone.';
            echo json_encode($response);die();
        }

        $action = $input['action'];


        $input['selected_date'] = strtotime($input['selected_date']);
        $input['form_date'] = strtotime($input['form_date']);
        $input['form_pronote_date'] = strtotime($input['form_pronote_date']);
        $input['form_raseed_pronote_date'] = strtotime($input['form_raseed_pronote_date']);
        $input['check_date'] = strtotime($input['form_raseed_pronote_check_date']);
        $input['applicant_id'] = $user->id;

        if ($request->hasFile('form_image')) {
            $path =  uploadS3File($request , "application-images" ,"form_image","application",$filename = null);
            $input['form_image'] = $path;
        }

        if (isset($input['form_image'])) {
            deleteS3File($input['form_old_image']);
            //     if (\File::exists(public_path($input['form_old_image']))) {
            //         \File::delete(public_path($input['form_old_image']));
            //     }
            // }
        } else {
            $input['form_image'] = $input['form_old_image'];
        }

        if ($request->hasFile('form_witness1_nic_front')) {
            $path2 =  uploadS3File($request , "application-images" ,"form_witness1_nic_front","form_witness1_nic_front",$filename = null);
            $input['form_witness1_nic_front'] = $path2;
        }

        if (isset($input['form_witness1_nic_front'])) {
            deleteS3File($input['witness1_nic_front_old']);
            // if(isset($input['witness1_nic_front_old'])){
            //     if (\File::exists(public_path($input['witness1_nic_front_old']))) {
            //         \File::delete(public_path($input['witness1_nic_front_old']));
            //     }
            // }

        } else {
            $input['form_witness1_nic_front'] = $input['witness1_nic_front_old'];
        }

        if ($request->hasFile('form_witness1_nic_back')) {
            $path3 =  uploadS3File($request , "application-images" ,"form_witness1_nic_back","form_witness1_nic_back",$filename = null);
            $input['form_witness1_nic_back'] = $path3;
        }

        if (isset($input['form_witness1_nic_back'])) {
            deleteS3File($input['witness1_nic_back_old']);
            // if(isset($input['witness1_nic_back_old'])){
            //     if (\File::exists(public_path($input['witness1_nic_back_old']))) {
            //         \File::delete(public_path($input['witness1_nic_back_old']));
            //     }
            // }
        } else {
            $input['form_witness1_nic_back'] = $input['witness1_nic_back_old'];
        }

        if ($request->hasFile('form_witness2_nic_front')) {
            $path4 =  uploadS3File($request , "application-images" ,"form_witness2_nic_front","form_witness2_nic_front",$filename = null);
            $input['form_witness2_nic_front'] = $path4;
        }

        if (isset($input['form_witness2_nic_front'])) {
            deleteS3File($input['witness2_nic_front_old']);
            // if(isset($input['witness2_nic_front_old'])){
            //     if (\File::exists(public_path($input['witness2_nic_front_old']))) {
            //         \File::delete(public_path($input['witness2_nic_front_old']));
            //     }
            // }
        } else {
            $input['form_witness2_nic_front'] = $input['witness2_nic_front_old'];
        }

        if ($request->hasFile('form_witness2_nic_back')) {
            $path5 =  uploadS3File($request , "application-images" ,"form_witness2_nic_back","form_witness2_nic_back",$filename = null);
            $input['form_witness2_nic_back'] = $path5;
        }

        if (isset($input['form_witness2_nic_back'])) {
            deleteS3File($input['witness2_nic_back_old']);
            // if(isset($input['witness2_nic_back_old'])){
            //     if (\File::exists(public_path($input['witness2_nic_back_old']))) {
            //         \File::delete(public_path($input['witness2_nic_back_old']));
            //     }
            // }
        } else {
            $input['form_witness2_nic_back'] = $input['witness2_nic_back_old'];
        }

        $zaminan_data = [
            "zaminan_name_1" => $request->input('zaminan_name_1'),
            "zaminan_cnic_1" => $request->input('zaminan_cnic_1'),
            "zaminan_signature_1" => $request->input('zaminan_signature_1'),
            "zaminan_name_2" => $request->input('zaminan_name_2'),
            "zaminan_cnic_2" => $request->input('zaminan_cnic_2'),
            "zaminan_signature_2" => $request->input('zaminan_signature_2')
        ];
        $zaminan_json = json_encode($zaminan_data);
        $input['zaminan_json']=$zaminan_json;

        if ($action == 'edit') {
            $input['application_id'] = $input['application_id'];
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


            foreach ((array)$input['sending_payment_method_ids'] as $key => $methodId) {
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
                    $inc++;
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

            if(request()->lang=='english'){
                $sucessMessage='Application has been submitted.';
            }
            else{
                $sucessMessage='درخواست جمع کرائی گئی ہے';
            }

            $response['status'] = 1;
            $response['message'] = $sucessMessage;
        } else {
            $response['status'] = 0;
            $response['message'] = 'Something went wrong.';
        }
        $response['plan_id'] = $planID;

        return response()->json(
            $response
        );

    }
    /**
     * get submitted business plans application detail
    */
    function getSubmittedBBInfo(Request $request)
    {
        $input = $request->all();
        $applicationId = $input['application_id'];
        $userID = $input['user_id'];

        $planId = $input['planId'];
        $response = [];
        $plan_query = getQuery($request->lang, ['name', 'description','term_conditions',]);
        $plan_query[] = 'invoice_amount';
        $data['plan'] = BusinessPlan::select($plan_query)->where('id', $planId)->first();

        if ($applicationId && $userID)
        {
            $data['action'] = 'edit';
            $data['base_url'] = asset('');
            $application = BusinessPlanApplication::find($applicationId);

            if (!empty($application))
            {
                if($application->applicant_id != $userID)
                {
                    $response['status'] = 0;
                    $response['message'] = 'You are not authorized.';
                }
                else
                {
                    $data['application'] = $application->toArray();
                    $data['witnesses'] = $application->applicationWitnesses->toArray();
                    $data['pronote'] = $application->applicationPronotes()->where('type', 1)->first()->toArray();
                    $data['raseed_pronote'] = $application->applicationPronotes()->where('type', 2)->first()->toArray();

                    $accountDetails = [];

                    foreach ($application->applicationAccounts as $key => $detail) {
                        $detail->type = ($detail->type == 1) ? 'sending' : 'recieving';
                        $accountDetails[$detail->type]['payment_method_id'][] = $detail->payment_method_id;
                        $accountDetails[$detail->type]['payment_method_field_' . $detail->payment_method_detail_id] = $detail->payment_method_detail_value;
                    }

                    $data['accounts'] = $accountDetails;

                    $response['status'] = 1;
                    $response['message'] = 'Data fethed successfully.';
                    $response['details'] = $data;
                }
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'Application not found.';
            }
        }
        else
        {
            $response['status'] = 1;
            $response['message'] = 'Data fethed successfully.';
            $response['details'] = $data;
        }
        return response()->json(
            $response
        );
        // echo json_encode($response);exit();
    }
    /**
     * get business booster plan invoice dates
    */
    function bbInvoicesDates(Request $request)
    {
        $input = $request->all();

        $applicationID = $input['application_id'];
        $userID = $input['user_id'];

        $response = [];

        $user = Auth::user();

        $data = [];
        $data['application_id'] = $applicationID;

        $applicationID = $applicationID;
        $application = BusinessPlanApplication::find($applicationID);

        if (!empty($application))
        {
            $paymentMethodsAccounts = [];

            foreach ($application->plan->businessPlanPaymentMethod as $pmethod) {
                $methodName = 'method_name_english' ;
                $methodDetailName = 'method_fields_english' ;

                $paymentMethodsAccounts[$pmethod->paymentMethod->{$methodName}]['id'] = $pmethod->paymentMethod->id;
                $paymentMethodsAccounts[$pmethod->paymentMethod->{$methodName}]['name'][] = $pmethod->paymentMethodDetail->{$methodDetailName} . ' : ' . $pmethod->payment_method_detail_value;
            }

            $accounts = [];

            foreach ($application->applicationAccounts()->where('type', 1)->get() as $pmethod) {
                $methodName = 'method_name_english' ;
                $methodDetailName = 'method_fields_english' ;

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

            $paidInvoices = BusinessPlanInvoice::where(['application_id'=> $applicationID,'status'=>1])->pluck('for_date')->toArray();
            $unPaidInvoices = BusinessPlanInvoice::where(['application_id'=> $applicationID,'status'=>0])->pluck('for_date')->toArray();
            $reliefDates = BusinessPlanUserReliefDate::where('application_id', $applicationID)->where('user_id', $user->id)->first();
            if ($reliefDates) {
                $reliefDates['start_date']=date('d-m-Y',  $reliefDates->start_date);
                $reliefDates['end_date']=date('d-m-Y',  $reliefDates->end_date);
            }

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

            $response['status'] = 1;
            $response['message'] = 'Data fetched.';
            $response['data'] = $data;
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = 'Application not found.';
        }

        return response()->json($response);
    }
    /**
     * submit business booster plan invoice
    */
    public function submitInvoices(Request $request)
    {
        $input = $request->all();

        // $request->application_id = $request->application_id;

        $application = BusinessPlanApplication::find($request->application_id);

        if(empty($application) || $application->applicant_id != $request->user_id)
        {
            return response()->json(['status' => 0, 'message' => 'You are not authorized.']);
        }

        $dates = explode(',', $input['selected_dates']);

        if ($request->hasFile('invoice')) {
            $imagePath = uploadS3File($request , "application-invoices" ,"invoice","invoice",$filename = null);
            $input['invoice'] = $imagePath;
        } else {
            $input['invoice'] = '';
        }

        $incr = 0;
        foreach ($dates as $key => $date) {

            $already = BusinessPlanInvoice::where([
                'application_id' => $request->application_id,
                'for_date' => strtotime($date),
            ])->first();

            if(empty($already))
            {
                if (!$incr) {
                    $firstRecord =  BusinessPlanInvoice::create([
                        'application_id' => $request->application_id,
                        'for_date' => strtotime($date),
                        'invoice' => $input['invoice'],
                        'sender_account_id' => $input['userSelectedAccount'],
                        'plan_account_id' => $input['adminSelectedAccount']
                    ]);
                }

                if ($incr) {
                    BusinessPlanInvoice::create([
                        'application_id' => $request->application_id,
                        'for_date' => strtotime($date),
                        'invoice' => $input['invoice'],
                        'parent_id' => ($firstRecord) ? $firstRecord->id : '',
                        'sender_account_id' => $input['userSelectedAccount'],
                        'plan_account_id' => $input['adminSelectedAccount']
                    ]);
                }
                $incr++;
            }
        }

        return response()->json(['status' => 1, 'message' => 'Transaction Submitted']);
    }
    /**
     * submit  business booster plan Application Date Change
    */
    public function submitApplicationDateChange(Request $request){
        $input = $request->all();
        $user_id = $request->user_id;

        $reqDate = strtotime($input['date_request']);

        $application = BusinessPlanApplication::where('id', $input['req_change_application_id'])->first();

        if(!empty($application))
        {

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
            return response()->json($response);
        }
        else
        {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Something went wrong.';

            echo json_encode($response);
            die();
        }
    }
    /**
     * get business plan details
    */
    public function getPlanDetails(Request $request)
    {
        $input = $request->all();

        $planId = $input['planId'];
        $applicationId = $input['applicationId'];

        $plan = BusinessPlan::find($planId);
        $data['plan'] = BusinessPlan::find($planId);

        if( empty($plan) )
        {
            $response = [];
            $response['status'] = 0;
            $response['message'] = 'Plan Not Found.';
            echo json_encode($response);exit();
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
            $applicationId = $applicationId;
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
        return response()->json(['status'=>1,'message'=>'success','data'=>$data]);

    }
    /**
     * delete image for edit form
    */
    public function deleteEditoImage($image)
    {
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }
    }
    /**
     * generate pdf for business plan application to store and downlaod
    */
    function downloadApplication()
    {
        $id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : 0;

        $userID = Auth::user()->id;

        if($id)
        {
            $application = BusinessPlanApplication::find($id);
            $data = [];
            $data['plan'] = BusinessPlan::find($application->plan->id);

            if(!empty($application))
            {

                $data['application'] = $application;
                $data['serial_number'] = $application->form_serial_number;
                $data['def_date'] = $application->form_date;
                $data['witnesses'] = $application->applicationWitnesses;
                $data['pronote'] = $application->applicationPronotes()->where('type', 1)->first();
                $data['raseed_pronote'] = $application->applicationPronotes()->where('type', 2)->first();

                $html = (string) View('user.download-plan-api', $data);

                $footer = '<p style="font-size: 8px; color: #666; text-align:center; margin-top: 0px;">.</p>';
                $mpdf = new Mpdf([ 'margin_left' => 10, 'margin_right' => 10, 'margin_top' => 10, 'margin_bottom' => 10, 'margin_header' => 0, 'margin_footer' => 0,]);
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
                $mpdf->SetHTMLFooter($footer);
                $mpdf->WriteHTML($html);
                $pdfContent = $mpdf->Output('', 'S'); // Output the PDF as a string
                // Define the folder and file name within the "resume" folder
                $folder = 'application-pdf';
                $fileName = $folder . '/' . Auth::user()->user_name . '_' . md5(time()) . '.pdf';
                // Store the PDF on S3 in the specified folder
                $pdfPath=Storage::disk('s3')->put($fileName, $pdfContent);
                if ($pdfPath) {
                $mpdf->Output($pdfPath, 'F');
                $path = $fileName;
                if (!empty($application->application_pdf)) {
                    deleteS3File($application->application_pdf);
                }
                $application->application_pdf = $path;
                $application->update();

                return response()->json(['status'=>1,'message'=>'success','data'=>getS3File($application->application_pdf)]);
            }
                // dd($savlfile);
                // $mpdf->Output('my-pdf-file.pdf', 'D');
                // return view('user.download-plan', $data);
            }
        }
    }
}
