<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeRequest;
use App\Http\Resources\HomeResource;
use App\Models\Admin\Admin;
use App\Models\Admin\CeoMessage;
use App\Models\Admin\Headline;
use App\Models\Admin\EmployeeSection;
use App\Models\Admin\Event;
use App\Models\Admin\Occupation;
use App\Models\Admin\Page;
use App\Models\Admin\Setting;
use App\Models\Admin\Slider;
use App\Models\ContactForm\ContactRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin\Cabinet;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\Role;
use App\Models\Admin\Designation;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Donation;
use App\Models\Admin\DonationCategory;
use App\Models\Admin\Donor;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\BusinessPlan;
use App\Models\Admin\UnionCouncil;
use App\Models\Admin\Zone;
use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\BusinessBooster\BusinessPlanUserReliefDate;
use App\Models\Posts\Like\Like;
use App\Models\Posts\Post\Post;
use App\Models\User\ApplyJob;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DataTables;

use Session;

class HomeController extends Controller
{
    /**
     *get home screen contect api
    */
    public function home(HomeRequest $request)
    {
        $data=[];
        $lang = $request->input('lang');
        $query = array_merge(getQuery($lang, ['title']),['id']);
        $query_slider = array_merge(getQuery($lang, ['content','mobile_content','btn_text']),['id','image','btn_link']);
        $page_home = array_merge(getQuery($lang, ['title']),['id','url','image']);
        $query_events = array_merge(getQuery($lang, ['title', 'content', 'location']),['start_time','end_time','image','start_date_time','end_date_time','id'] );

        $today = date('Y-m-d H:i:s');
        $evnets_data = Event::with('images')->select($query_events)->where('status', 1)->where('end_date_time', '>', $today)->get();
        $viedoUrl    = Setting::where('option_name', 'video')->first()->toArray();
        $headline    = Headline::select($query)->where('status', 1)->get()->toArray();
        $slider_data = Slider::select($query_slider)->where('status', 1)->orderBy('order_rows')->get()->toArray();
        $page_data   = Page::select($page_home)->where('status', 1)->where('is_feature',1)->get();

        $query = getQuery($request->lang, ['message']);
        $query[] = 'image';
        $ceoMessage=  CeoMessage::select($query)->where('status', 1)->first();

        $occupation_query = array_merge(getQuery($lang, ['title']),['id','slug']);
        $occupations = Occupation::select($occupation_query)->where('status',1)->where('parent_id',null)
                ->when($request->id,function($query) use ($request){
                    $query->where('id',$request->id);
                })
                ->get();

        // $ceoMessage =  array_merge($ceoMessage,[url('ceo-message')]);

        $data['evnets_data']=$evnets_data;
        $data['viedoUrl']=$viedoUrl;
        $data['headline']=$headline;
        $data['slider_data']=$slider_data;
        $data['page_data']=$page_data;
        $data['ceo_message'] = $ceoMessage;
        $data['occupations'] = $occupations;
        // $data['ceo_message_url'] =url('ceo-message');
        // dd($data);

        // $result = array_merge($viedoUrl, $headline, $slider_data);
        if (empty($data)) {
            return response()->json(['status' => 0, 'message' => 'something goes wrong']);
        }
        return  new HomeResource($data);
    }
    /**
     *get employee section and their details
    */
    public function getAboutInfo(Request $request)
    {
        $ameerMessage = CeoMessage::where('status', 1)->first();

        $query = getQuery($request->lang, ['name', 'designation', 'short_description']);
        $query[] = 'image';
        $query[] = 'section_id';
        $employs = EmployeeSection::select($query)->where('status', 1)->get();
        // 1 = managers, 2 = our proud (current), 3 = wo jo hm mn nhi rhy (late)
        $managers = $employs->where('section_id', 1);
        $currently = $employs->where('section_id', 2);
        $late = $employs->where('section_id', 3);
        return response()->json([
            'status' => 1,
            'message' => 'success',
            'ameerMessage' => $ameerMessage,
            'managers' => $managers,
            'currently' => $currently,
            'late' => $late,
        ]);
    }
    /**
     *get professions list api
    */
    public function getProfessionsList()
    {
        $occupationCols = ['id', 'parent_id', 'title_english', 'title_urdu', 'title_arabic', 'status'];
        $occupations = Occupation::query()
            ->select($occupationCols)
            ->where('status', 1)
            ->where('parent_id', null)
            ->with(['subProfession' => fn ($q) => $q->select($occupationCols)])->get();

        return response()->json(['status' => 1, 'data' => $occupations]);
    }
    /**
     *save contact us details api
    */
    public function contactUS(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => "required|email",
            'subject' => "required|max:255",
            'phone' => "required",
            'message' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'validation fails',
                'data' => $validator->errors()->toArray(),

            ], 200);
        } else {
            $model = new ContactRecord();
            $model->fill($input);
            $model = $model->save();
            if ($model) {
                if(request()->lang=='english'){
                    $successMessage='Your query has been received successfully. One of our staff members will contact you in next couple of hours.';
                }
                else{
                    $successMessage='آپ کا استفسار کامیابی سے موصول ہو گیا ہے۔ ہمارے عملے کا ایک رکن اگلے چند گھنٹوں میں آپ سے رابطہ کرے گا۔';
                }
                return response()->json([
                    'status' => 1,
                    'message' => $successMessage,

                ], 200);

                $details = [
                    'subject' =>  "Query Received",
                    'user_name' =>  "Super Admin",
                    'content'  => "<p>A new user submitted a query via the contact us form.</p>",
                    'links'    =>  "<a href='" . url('admin/contacts') . "'>Click here </a> to log in and view all queries. ",
                ];
                // $adminEmail = Admin::find(1)->value('email');
                $adminEmail = settingValue('emailForNotification');
                saveEmail($adminEmail, $details);
                $details['subject'] = "Query Submitted Successfully";
                $details['user_name'] = $input['name'];
                $details['content'] = "<p>Your query has been received successfully. One of our staff members will contact you in next couple of hours.</p><p>Query details are as follows: <br> " . $input['subject'] . "  <br> </p><p>" . $input['message'] . "</p";
                $details['links'] = "";
                saveEmail($input['email'], $details);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Your query is not submitted due to some technical issue. Please try again later',

                ], 200);
            }
        }
    }
    /**
     *save home settings api
    */
    public function settings(Request $request)
    {
        $setting = Setting::all()->toArray();
        if (!empty($setting)) {
            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    =>  $setting,

            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Record Found',

            ], 200);
        }
    }

    /**
     *get userlist with filters and tabs data api
    */
    public function userList(Request $request)
    {
        $data = [];

        $isPermissionToSeeUsers = have_permission('View-User-List');

            $union_council_id=UnionCouncil::where('name_english','like', '%'.$request->council_name.'%')->orWhere('name_urdu','like', '%'.$request->council_name.'%')->pluck('id');
            if(isset($request->filter) && $request->filter == "filter"){
                // dd('checking');
                $db_record = User::query()->with(['country','province','division','district','tehsil','zone','union_council','city','role'])
                ->when($request->slug, function ($q) use ($request) {
                    $occupationCols = array_merge(getQuery($request->lang, ['title', 'content']), ['id']);
                    $occupation = Occupation::select($occupationCols)->where('slug', $request->slug)->where('status',1)->first();
                    return $q->whereHas('userOccupation', fn($query) => $query->where('status', 1)->where('occupation_id', $occupation->id));
                })
                ->when($request->country_id, fn($q) => $q->wherein('country_id', $request->country_id))
                ->when($request->province_id, fn($q) => $q->wherein('province_id', $request->province_id))
                ->when($request->division_id, fn($q) => $q->wherein('division_id', $request->division_id))
                ->when($request->city_id, fn($q) => $q->wherein('city_id', $request->city_id))
                ->when($request->district_id, fn($q) => $q->wherein('district_id', $request->district_id))
                ->when($request->tehsil_id, fn($q) => $q->wherein('tehsil_id', $request->tehsil_id))
                ->when($request->zone_id, fn($q) => $q->wherein('zone_id', $request->zone_id))
                ->when($request->occupation_id, fn($q) => $q->wherein('userOccupation', fn($q2) => $q2->where('occupation_id', $request->occupation_id)))
                ->when($request->council_name, fn($q) => $q->wherein('union_council_id',$union_council_id))
                ->paginate($request->limit ?? 10)
                ->each(function ($item) use ($request) {
                    if(!$item->cabinetUsers->isEmpty()){

                        $cabinets=Cabinet::whereIn('id',$item->cabinetUsers->pluck('cabinet_id'))->pluck('name_'.$request->lang)->toArray();
                        $cabinets_name = implode(", ", $cabinets);
                    }else{
                        $cabinets_name = 'N/A';
                    }
                    if(!$item->cabinetUsers->isEmpty()){

                        $cabinets_role=Designation::whereIn('id',$item->cabinetUsers->pluck('designation_id'))->pluck('name_'.$request->lang)->toArray();
                        $role_name = implode(", ", $cabinets_role);
                        // dd($cabinets_name);
                    }else{
                        $role_name = 'N/A';
                    }
                    $item->cabinets_names=$cabinets_name;
                    $item->cabinets_roles=$role_name;
                    if(!$item->manyOccupations->isEmpty()){
                        $item->ocuupationsAll =implode(',',array_unique($item->manyOccupations->pluck('title_english')->toArray()));
                        $item->makeHidden('manyOccupations');
                    }
                    else{
                        $item->ocuupationsAll=[];
                    }
                });
            }else{
                $cabinetsUsers=[];
                if ($request->type != 4) {
                    $cabinetsIds=Cabinet::when($request->type == 1, fn($q) => $q->where('tehsil_id', auth()->user()->tehsil_id)->where('tehsil_id', '!=', null))
                    ->when($request->type == 2, fn($q) => $q->where('district_id', auth()->user()->district_id)->where('district_id', '!=', null))
                    ->when($request->type == 3, fn($q) => $q->where('division_id', auth()->user()->division_id)->where('division_id', '!=', null))
                    ->when($request->type == 7, fn($q) => $q->where('province_id', auth()->user()->province_id)->where('province_id', '!=', null))
                    ->when($request->type == 8, fn($q) => $q->where('city_id', auth()->user()->city_id)->where('city_id', '!=', null))
                    ->where('status', 1)
                    ->pluck('id');
                    $cabinetsUsers=CabinetUser::whereIn('cabinet_id',$cabinetsIds)->pluck('user_id');
                }
                $db_record=User::with(['country','province','division','district','tehsil','zone','union_council','city','role'])->when(!empty($cabinetsUsers), fn($q) => $q->whereIn('id',$cabinetsUsers))->where('id', '!=', auth()->user()->id)->where('status', 1)
                ->paginate($request->limit ?? 10)
                ->each(function ($item) use ($request) {
                    if(!$item->cabinetUsers->isEmpty()){

                        $cabinets=Cabinet::whereIn('id',$item->cabinetUsers->pluck('cabinet_id'))->pluck('name_'.$request->lang)->toArray();
                        $cabinets_name = implode(", ", $cabinets);
                    }else{
                        $cabinets_name = 'N/A';
                    }
                    if(!$item->cabinetUsers->isEmpty()){

                        $cabinets_role=Designation::whereIn('id',$item->cabinetUsers->pluck('designation_id'))->pluck('name_'.$request->lang)->toArray();
                        $role_name = implode(", ", $cabinets_role);
                        // dd($cabinets_name);
                    }else{
                        $role_name = 'N/A';
                    }
                    $item->cabinets_names=$cabinets_name;
                    $item->cabinets_roles=$role_name;
                    if(!$item->manyOccupations->isEmpty()){
                        $item->ocuupationsAll =implode(',',array_unique($item->manyOccupations->pluck('title_english')->toArray()));
                        $item->makeHidden('manyOccupations');
                    }
                    else{
                        $item->ocuupationsAll=[];
                    }
                });
            }

            if ($request->type == 5) { // type 5 = defaulters
                $db_record = null;

                if (have_permission('View-Defaulter-Users')) {
                    $plan_query = array_merge(getQuery($request->lang, ['name']), ['id', 'created_by', 'type', 'total_invoices', 'invoice_amount', 'total_users', 'status', 'start_date', 'end_date', 'created_at', 'updated_at']);
                    $db_record = BusinessPlanApplication::with(['user', 'plan' => function ($q) use ($plan_query) {
                        $q->select($plan_query)->get($plan_query);
                    }])->where('status', 1)
                    ->paginate($request->limit ?? 10)
                    ->each(function ($application) {
                        $defaulterPlansArray = [];
                        $plan = $application->plan;

                        $defaulterPlansArray[] = $plan->name;

                        $startDate = date('Y-m-d', $plan->start_date);
                        $endDate = date('Y-m-d', $plan->end_date);

                        $period = CarbonPeriod::create($startDate, $endDate);

                        $paidInvoices = BusinessPlanInvoice::where('application_id', $application->id)->pluck('for_date')->toArray();
                        $paidInvoices = array_map(function ($date) {
                            return date('d-m-Y', $date);
                        }, $paidInvoices);

                        $reliefDatesArray = [];
                        $reliefDate = BusinessPlanUserReliefDate::where('application_id', $application->id)->where('user_id', $application->applicant_id)->first();
                        if ($reliefDate) {
                            $reliefStartDate = date('Y-m-d', $reliefDate->start_date);
                            $reliefEndDate = date('Y-m-d', $reliefDate->end_date);
                            $reliefPeriod = CarbonPeriod::create($reliefStartDate, $reliefEndDate);
                            foreach ($reliefPeriod as $reliefDatePeriod) {
                                $reliefDatesArray[] = $reliefDatePeriod->format('d-m-Y');
                            }
                        }

                        $planDates = [];
                        foreach ($period as $date) {
                            $planDates[] = $date->format('d-m-Y');
                        }

                        $todayDate = today()->format('d-m-Y');
                        // $todayDate = date('d-m-Y', strtotime('+3 days'));

                        $defaulterDates = [];
                        foreach ($planDates as $planDate) {
                            if (strtotime($planDate) < strtotime($todayDate) && !in_array($planDate, $paidInvoices) && !in_array($planDate, $reliefDatesArray)) {
                                $defaulterDates[] = $planDate;
                            }
                        }
                        if (count($defaulterDates)) {
                            $application->defaulterDates = $defaulterDates;
                        } else {
                            $application->defaulterDates = ['N/A'];
                        }
                    })->reject(fn($application) => empty($application->defaulterDates));
                    return response()->json([
                        'status'  => 1,
                        'message' => 'Success',
                        'data'    =>  $db_record,

                    ], 200);
                } else {
                    $db_record = collect([]);
                    return response()->json([
                        'status'  => 1,
                        'message' => 'Success',
                        'data'    =>  $db_record,

                    ], 200);
                }
            }
            if ($request->type == 9) { // type 5 = defaulters
                $db_record = null;
                $lang=$request->lang;
                $cabinet_query = array_merge(getQuery($lang, ['name']), ['id','status']);
                $db_record = Cabinet::select($cabinet_query)
                // ->get()
                ->paginate($request->limit ?? 10);
                $data = $db_record->items();
                // dd($db_record);
                    return response()->json([
                        'status'  => 1,
                        'message' => 'Success',
                        'data'    =>  $data,

                    ], 200);
            }

            // override db_record when user has no permission
            if ($request->type == 4 && !$isPermissionToSeeUsers) { // type 4= all users
                $db_record = collect([]);
            }
            if ($request->type == 3 && !have_permission('View-Cabinet-Members-Based-On-Divison')) {
                $db_record = collect([]);
            }
            if ($request->type == 2 && !have_permission('View-Cabinet-Members-Based-On-District')) {
                $db_record = collect([]);
            }
            if ($request->type == 1 && !have_permission('View-Cabinet-Members-Based-On-Tehsil')) {
                $db_record = collect([]);
            }
            if ($request->type == 7 && !have_permission('View-Cabinet-Members-Based-On-Province')) {
                $db_record = collect([]);
            }

            if ($request->type == 8 && !have_permission('View-Cabinet-Members-Based-On-City')) {
                $db_record = collect([]);
            }
            if ($request->type != 5) {
                return response()->json([
                    'status'  => 1,
                    'message' => 'Success',
                    'data'    => $db_record,

                ], 200);
            }
    }
    /**
     *get cabinet users api
    */
    public function cabinetUsers(Request $request)
    {
        $cabinetsUser = CabinetUser::where('cabinet_id', $request->cabinetId)->get()
        ->each(function ($item) use($request){
            $item->user_name=User::find($item->user_id)->{'user_name_'.$request->lang};
            $item->role=Role::find($item->role_id)->{'name_'.$request->lang};

        });
        return response()->json(['status' => 200, 'data' => $cabinetsUser]);
    }


}
