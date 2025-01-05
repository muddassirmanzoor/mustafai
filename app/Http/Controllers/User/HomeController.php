<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Donation;
use App\Models\Admin\DonationCategory;
use App\Models\Admin\Donor;
use App\Models\Admin\Occupation;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\BusinessPlan;
use App\Models\Admin\Cabinet;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\Role;
use App\Models\Admin\Designation;
use App\Models\Admin\UnionCouncil;
use App\Models\Admin\Zone;
use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\BusinessBooster\BusinessPlanUserReliefDate;
use App\Models\Posts\Like\Like;
use App\Models\Posts\Post\Post;
use App\Models\User;
use App\Models\User\ApplyJob;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Session;
use LanguageDetection\Language;

class HomeController extends Controller
{
    /**
     *Retrieves and paginates posts data along with their related information, and returns the view for the user dashboard.
     */
    public function dashboard(Request $request)
    {
        if (!have_permission('View-News-Feed-Posts')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $cols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'admin_id', 'blood_for', 'share_id', 'user_id', 'job_type', 'hiring_company_name', 'post_type', 'product_id', 'city', 'hospital', 'address', 'occupation', 'experience', 'skills', 'resume', 'description_english', 'blood_group', 'created_at']);
        $cityCols = array_merge(getQuery(App::getLocale(), ['name']), ['id']);
        $userNameCols = array_merge(getQuery(App::getLocale(), ['user_name']), ['id', 'user_name_english', 'user_name_urdu', 'user_name_arabic', 'is_public', 'profile_image']);
        $posts = Post::select($cols)->with(['images', 'comments.user', 'likes', 'user' => fn ($q) => $q->select($userNameCols), 'admin', 'citi' => fn ($q) => $q->select($cityCols)])
            ->withCount(['comments', 'likes'])
            ->active()
            ->latest()
            ->addSelect(['is_like' => Like::select('id')->where('user_id', auth()->id())->whereColumn('post_id', 'posts.id')])
            ->paginate(5)
            ->each(function ($post) use ($userNameCols) {
                // $post->likes()->where('user_id', Auth::id())->exists() ? $post->is_like = 1 : $post->is_like = 0;
                $post->shared_user = $post->share_id != null ? User::select($userNameCols)->find($post->share_id) : null;
                $post->lang ='urdu';
                if(!empty($post->title) || isset($post->title)){
                    $ld = new Language(['en', 'ur']);
                    $textD = $ld->detect($post->title);
                    $post->lang = $textD['en'] === 0.0 ? 'urdu' : 'english';
                }
            });
        if ($request->ajax()) {
            $view = view('user.partials.posts-partial', compact('posts'))->render();
            return response()->json(['html' => $view]);
        }
        return view('user.dashboard', get_defined_vars());
    }

    /**
     *Retrieves user data based on different conditions, handles permissions, and uses Datatables for displaying the user list with pagination and filtering options.
     */
    public function userList(Request $request)
    {
        if (!have_permission('View-User-List')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        if (!auth()->user()->manyOccupations->isEmpty()) {

            $ocuupationsAll = implode(',', array_unique(auth()->user()->manyOccupations->pluck('title_english')->toArray()));
        } else {
            $ocuupationsAll = 'N/A';
        }
        $data = [];
        $isPermissionToSeeUsers = have_permission('View-User-List');

        if ($request->ajax()) {
            $union_council_id = UnionCouncil::where('name_english', 'like', '%' . $request->council_name . '%')->orWhere('name_urdu', 'like', '%' . $request->council_name . '%')->pluck('id');
            // type1 = tehsil, type2 = district, type3 = division
            if (isset($request->filter) && $request->filter == "filter") {
                $db_record = User::query()
                    ->when($request->slug, function ($q) use ($request) {
                        $occupationCols = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id']);
                        $occupation = Occupation::select($occupationCols)->where('slug', $request->slug)->where('status', 1)->first();
                        return $q->whereHas('userOccupation', fn ($query) => $query->where('status', 1)->where('occupation_id', $occupation->id));
                    })
                    ->when($request->country_id, fn ($q) => $q->whereIn('country_id', $request->country_id))
                    ->when($request->province_id, fn ($q) => $q->whereIn('province_id', $request->province_id))
                    ->when($request->division_id, fn ($q) => $q->whereIn('division_id', $request->division_id))
                    ->when($request->city_id, fn ($q) => $q->whereIn('city_id', $request->city_id))
                    ->when($request->district_id, fn ($q) => $q->whereIn('district_id', $request->district_id))
                    ->when($request->tehsil_id, fn ($q) => $q->whereIn('tehsil_id', $request->tehsil_id))
                    ->when($request->zone_id, fn ($q) => $q->whereIn('zone_id', $request->zone_id))
                    ->when($request->occupation_id, fn ($q) => $q->whereHas('userOccupation', fn ($q2) => $q2->whereIn('occupation_id', $request->occupation_id)))
                    ->when($request->council_name, fn ($q) => $q->whereIn('union_council_id', $union_council_id))
                    ->get();
            } else {
                $cabinetsUsers = [];
                if ($request->type != 4) {
                    $cabinetsIds = Cabinet::when($request->type == 1, fn ($q) => $q->where('tehsil_id', auth()->user()->tehsil_id)->where('tehsil_id', '!=', null))
                        ->when($request->type == 2, fn ($q) => $q->where('district_id', auth()->user()->district_id)->where('district_id', '!=', null))
                        ->when($request->type == 3, fn ($q) => $q->where('division_id', auth()->user()->division_id)->where('division_id', '!=', null))
                        ->when($request->type == 7, fn ($q) => $q->where('province_id', auth()->user()->province_id)->where('province_id', '!=', null))
                        ->when($request->type == 8, fn ($q) => $q->where('city_id', auth()->user()->city_id)->where('city_id', '!=', null))
                        ->where('status', 1)
                        ->pluck('id');
                    $cabinetsUsers = CabinetUser::whereIn('cabinet_id', $cabinetsIds)->pluck('user_id');
                }
                $db_record = User::when(!empty($cabinetsUsers), fn ($q) => $q->whereIn('id', $cabinetsUsers))->where('id', '!=', auth()->user()->id)->where('status', 1)->get();
                // $db_record = User::when($request->type == 1, fn($q) => $q->whereHas('cabinetUsers')->where('tehsil_id', auth()->user()->tehsil_id)->where('tehsil_id', '!=', null))
                //     ->when($request->type == 2, fn($q) => $q->whereHas('cabinetUsers')->where('district_id', auth()->user()->district_id)->where('district_id', '!=', null))
                //     ->when($request->type == 3, fn($q) => $q->whereHas('cabinetUsers')->where('division_id', auth()->user()->division_id)->where('division_id', '!=', null))
                //     ->when($request->type == 7, fn($q) => $q->whereHas('cabinetUsers')->where('province_id', auth()->user()->province_id)->where('province_id', '!=', null))
                //     ->when($request->type == 8, fn($q) => $q->whereHas('cabinetUsers')->where('city_id', auth()->user()->city_id)->where('city_id', '!=', null))
                //     ->where('status', 1)
                //     ->where('id', '!=', auth()->user()->id)
                //     ->get();
            }

            if ($request->type == 5) { // type 5 = defaulters
                $db_record = null;

                if (have_permission('View-Defaulter-Users')) {
                    $plan_query = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'created_by', 'type', 'total_invoices', 'invoice_amount', 'total_users', 'status', 'start_date', 'end_date', 'created_at', 'updated_at']);
                    $db_record = BusinessPlanApplication::with(['user', 'plan' => function ($q) use ($plan_query) {
                        $q->select($plan_query)->get($plan_query);
                    }])->where('status', 1)->get()->each(function ($application) {
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
                        //                        $todayDate = date('d-m-Y', strtotime('+3 days'));

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
                    })->reject(fn ($application) => empty($application->defaulterDates));

                    $datatable = Datatables::of($db_record);

                    $datatable = $datatable->editColumn('name', function ($row) {
                        return $row->user->user_name;
                    });

                    $datatable = $datatable->editColumn('plan', function ($row) {
                        return $row->plan->name;
                    });

                    $datatable = $datatable->addColumn('defaulter_dates', function ($row) {
                        return '<span data-toggle="modal" data-target="#defaulterDatesModal" title="Defaulter Dates" style="cursor:pointer;" data-dates="' . implode(',', $row->defaulterDates) . '" onclick="showDefaulterDates($(this))"><span class="btn btn-primary"><i class="fa fa-eye"></i></span></span>';
                    });

                    $datatable = $datatable->editColumn('profile', function ($row) {
                        $visibility = $row->user->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                        if ($row->user->is_public == 1) {
                            $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->user->id)]) . '">
                               ' . $visibility . '
                            </a>';
                            return $profile;
                        } else {
                            $profile = '<span href="javascript:void(0)">
                               ' . $visibility . '
                            </span>';
                            return $profile;
                        }
                    });
                    $datatable = $datatable->editColumn('profileStatus', function ($row) {
                        $visibility = $row->user->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                        return $visibility;
                    });

                    $datatable = $datatable->editColumn('full_name', function ($row) {
                        if (!empty($row)) {
                            $field_name = $row->user->user_name;
                            $profile_img = getS3File($row->user->profile_image);
                        }
                        $img = '<figure class="mb-0 me-2 user-img"> <img src="' . $profile_img . '" class="img-fluid"> </figure>';
                        return $img . $field_name;
                    });

                    $datatable = $datatable->rawColumns(['full_name','profile', 'defaulter_dates', 'profileStatus']);
                    $datatable = $datatable->make(true);
                    return $datatable;
                } else {
                    $db_record = collect([]);
                    $datatable = Datatables::of($db_record);
                    $datatable = $datatable->make(true);
                    return $datatable;
                }
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
                $datatable = Datatables::of($db_record);
                $datatable = $datatable->addIndexColumn();

                $datatable = $datatable->addColumn('profile', function ($row) {
                    if ((have_permission('Can-View-User-All-Detail'))) {
                        $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                        if ($row->is_public == 1) {
                            $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->id)]) . '">
                                ' . $visibility . '
                                </a>';
                            return $profile;
                        } else {
                            $profile = '<span href="javascript:void(0)">
                                ' . $visibility . '
                                </span>';
                            return $profile;
                        }
                    } else {
                        return '';
                    }
                });
                $datatable = $datatable->addColumn('profileStatus', function ($row) {
                    $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                    return $visibility;
                });
                $datatable = $datatable->editColumn('country', function ($row) {

                    $country =  optional($row->country)->{'name_' . App::getLocale()};
                    return (!empty($country)) ? $country : 'N/A';
                });
                $datatable = $datatable->editColumn('province', function ($row) {

                    $province =  optional($row->province)->{'name_' . App::getLocale()};
                    return (!empty($province)) ? $province : 'N/A';
                });
                $datatable = $datatable->editColumn('division', function ($row) {

                    $division =  optional($row->division)->{'name_' . App::getLocale()};
                    return (!empty($division)) ? $division : 'N/A';
                });
                $datatable = $datatable->editColumn('district', function ($row) {

                    $district =  optional($row->district)->{'name_' . App::getLocale()};
                    return (!empty($district)) ? $district : 'N/A';
                });
                $datatable = $datatable->editColumn('tehsil', function ($row) {

                    $tehsil =  optional($row->tehsil)->{'name_' . App::getLocale()};
                    return (!empty($tehsil)) ? $tehsil : 'N/A';
                });
                $datatable = $datatable->editColumn('zone', function ($row) {

                    $zone =  optional($row->zone)->{'name_' . App::getLocale()};
                    return (!empty($zone)) ? $zone : 'N/A';
                });
                $datatable = $datatable->editColumn('union_council', function ($row) {
                    $union_council =  optional($row->union_council)->{'name_' . App::getLocale()};
                    return (!empty($union_council)) ? $union_council : 'N/A';
                });
                $datatable = $datatable->editColumn('city_id', function ($row) {

                    $city =  optional($row->city)->{'name_' . App::getLocale()};
                    return (!empty($city)) ? $city : 'N/A';
                });
                $datatable = $datatable->editColumn('address_' . App::getLocale() . '', function ($row) {
                    $address = 'address_' . lang() . '';
                    return (!empty($row->{$address})) ? $row->{$address} : 'N/A';
                });
                $datatable = $datatable->editColumn('phone_number', function ($row) {
                    if ((have_permission('Can-View-User-All-Detail'))) {
                        if ($row->has('countryCode')) {
                            $country_code = optional($row->countryCode)->phonecode;
                        } else {
                            $country_code = "";
                        }
                        return $country_code . $row->phone_number;
                    } else {
                        return '';
                    }
                    // return "o303";
                });
                $datatable = $datatable->editColumn('email', function ($row) {
                    if ((have_permission('Can-View-User-All-Detail'))) {
                        $email =  $row->email;
                        return (!empty($email)) ? $email : 'N/A';
                    } else {
                        return '';
                    }
                });
                $datatable = $datatable->addColumn('occupation_data', function ($row) {
                    return  '<a onclick="userOccupation(' . $row->id . ')" data-toggle="modal" data-target="#showPostModal" class="btn btn-primary show_post" href="javascript:void(0)" data-post-id="' . $row->id . '" title="Profession"><i class="fa fa-eye"></i></a>';
                });

                $datatable = $datatable->addColumn('occupationAll', function ($row) {
                    if (!$row->manyOccupations->isEmpty()) {

                        $ocuupationsAll = implode(',', array_unique($row->manyOccupations->pluck('title_english')->toArray()));
                    } else {
                        $ocuupationsAll = 'N/A';
                    }
                    return $ocuupationsAll;
                    // dd($ocuupationsAll);
                });
                $datatable = $datatable->addColumn('cabinets', function ($row) {

                    if (!$row->cabinetUsers->isEmpty()) {

                        $cabinets = Cabinet::whereIn('id', $row->cabinetUsers->pluck('cabinet_id'))->pluck('name_' . App::getLocale())->toArray();
                        $cabinets_name = implode(", ", $cabinets);
                        // dd($cabinets_name);
                    } else {
                        $cabinets_name = 'N/A';
                    }
                    return $cabinets_name;
                    // dd($cabinetUsers);
                });
                $datatable = $datatable->addColumn('cabinets_role', function ($row) {

                    if (!$row->cabinetUsers->isEmpty()) {

                        $cabinets_role = Designation::whereIn('id', $row->cabinetUsers->pluck('designation_id'))->pluck('name_' . App::getLocale())->toArray();
                        $role_name = implode(", ", $cabinets_role);
                        // dd($cabinets_name);
                    } else {
                        $role_name = 'N/A';
                    }
                    return $role_name;
                    // dd($cabinetUsers);
                });
                $datatable = $datatable->editColumn('full_name', function ($row) {
                    if (!empty($row)) {
                        $field_name = $row->user_name;
                        $profile_img = getS3File($row->profile_image);
                    }
                    $img = '<figure class="mb-0 me-2 user-img"> <img src="' . $profile_img . '" class="img-fluid"> </figure>';
                    return $img . $field_name;
                });
                $datatable = $datatable->rawColumns(['profile', 'profileStatus', 'phone_number', 'address_' . App::getLocale() . '', 'city_id', 'occupation_data', 'occupationAll', 'full_name', 'cabinets', 'cabinets_role']);
                $datatable = $datatable->make(true);
                return $datatable;
            }
        }

        $name_query = getQuery(App::getLocale(), ['name']);
        $name_query[] = 'id';
        $countries = Country::select($name_query)->where('status', 1)->get();
        $provinces = Province::select($name_query)->where('status', 1)->get();
        $divisions = Division::select($name_query)->where('status', 1)->get();
        $cities = City::select($name_query)->where('status', 1)->get();
        $districts = District::select($name_query)->where('status', 1)->get();
        $tehsils = Tehsil::select($name_query)->where('status', 1)->get();
        $zones = Zone::select($name_query)->where('status', 1)->get();
        $councils = UnionCouncil::select($name_query)->where('status', 1)->get();

        $occupationCols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'parent_id']);
        $occupations = Occupation::query()
            ->select($occupationCols)
            ->where('status', 1)
            ->where('parent_id', null)
            ->with(['subProfession' => fn ($q) => $q->select($occupationCols)])->get();

        return view('user.user-list', get_defined_vars());
    }

    /**
     *Getting the cabinet list
     */
    public function cabinentList(Request $request)
    {
        if (have_permission('Can-View-ALL-Cabinet')) {
            $cabinet_query = array_merge(getQuery(App::getLocale(), ['name']), ['status', 'id']);
            $db_record = Cabinet::select($cabinet_query)->get();

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="badge badge-danger" style="background-color: red;">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-success" style="background-color: #009a71;">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->addColumn('HiddenStatus', function ($row) {
                $HiddenStatus = $row->status == 1 ? 'Active' : 'Disable';
                return $HiddenStatus;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                if (have_permission('Can-View-Cabinet-Users')) {
                    return  '<a class="btn btn-primary" href="javascript:void(0)" onclick="viewCabinetUsers(this)" data-cabinet-id="' . $row->id . '">' . __('app.view-cabinet-user') . '</a> ';
                } else {
                    return '';
                }
            });

            $datatable = $datatable->rawColumns(['name', 'status', 'HiddenStatus', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        } else {
            $db_record = collect([]);
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->make(true);
            return $datatable;
        }
    }

    /**
     *Retrieves user data based on different conditions, handles permissions, and uses Datatables for displaying the user list with pagination
     */
    public function userListbk(Request $request)
    {
        if (!have_permission('View-User-List')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $data = [];
        $isPermissionToSeeUsers = have_permission('View-User-List');

        if ($request->ajax()) {
            // type1 = tehsil, type2 = district, type3 = division
            $db_record = User::when($request->type == 1, fn ($q) => $q->whereHas('cabinetUsers')->where('tehsil_id', auth()->user()->tehsil_id)->where('tehsil_id', '!=', null))
                ->when($request->type == 2, fn ($q) => $q->whereHas('cabinetUsers')->where('district_id', auth()->user()->district_id)->where('district_id', '!=', null))
                ->when($request->type == 3, fn ($q) => $q->whereHas('cabinetUsers')->where('division_id', auth()->user()->division_id)->where('division_id', '!=', null))
                ->when($request->type == 7, fn ($q) => $q->whereHas('cabinetUsers')->where('province_id', auth()->user()->province_id)->where('province_id', '!=', null))
                ->when($request->type == 8, fn ($q) => $q->whereHas('cabinetUsers')->where('city_id', auth()->user()->city_id)->where('city_id', '!=', null))
                ->where('status', 1)
                ->where('id', '!=', auth()->user()->id)
                ->get();

            if ($request->type == 5) { // type 5 = defaulters
                $db_record = null;

                if (have_permission('View-Defaulter-Users')) {
                    $plan_query = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'created_by', 'type', 'total_invoices', 'invoice_amount', 'total_users', 'status', 'start_date', 'end_date', 'created_at', 'updated_at']);
                    $db_record = BusinessPlanApplication::with(['user', 'plan' => function ($q) use ($plan_query) {
                        $q->select($plan_query)->get($plan_query);
                    }])->where('status', 1)->get()->each(function ($application) {
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
                        //                        $todayDate = date('d-m-Y', strtotime('+3 days'));

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
                    })->reject(fn ($application) => empty($application->defaulterDates));

                    $datatable = Datatables::of($db_record);

                    $datatable = $datatable->editColumn('name', function ($row) {
                        return $row->user->user_name;
                    });

                    $datatable = $datatable->editColumn('plan', function ($row) {
                        return $row->plan->name;
                    });

                    $datatable = $datatable->addColumn('defaulter_dates', function ($row) {
                        return '<span data-toggle="modal" data-target="#defaulterDatesModal" title="Defaulter Dates" style="cursor:pointer;" data-dates="' . implode(',', $row->defaulterDates) . '" onclick="showDefaulterDates($(this))"><span class="btn btn-primary"><i class="fa fa-eye"></i></span></span>';
                    });

                    $datatable = $datatable->editColumn('profile', function ($row) {
                        $visibility = $row->user->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                        if ($row->user->is_public == 1) {
                            $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->user->id)]) . '">
                               ' . $visibility . '
                            </a>';
                            return $profile;
                        } else {
                            $profile = '<span href="javascript:void(0)">
                               ' . $visibility . '
                            </span>';
                            return $profile;
                        }
                    });
                    $datatable = $datatable->editColumn('profileStatus', function ($row) {
                        $visibility = $row->user->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                        return $visibility;
                    });

                    $datatable = $datatable->rawColumns(['profile', 'defaulter_dates', 'profileStatus']);
                    $datatable = $datatable->make(true);
                    return $datatable;
                } else {
                    $db_record = collect([]);
                    $datatable = Datatables::of($db_record);
                    $datatable = $datatable->make(true);
                    return $datatable;
                }
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
                $datatable = Datatables::of($db_record);
                $datatable = $datatable->addIndexColumn();
                $datatable = $datatable->addColumn('profile', function ($row) {
                    $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                    if ($row->is_public == 1) {
                        $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->id)]) . '">
                               ' . $visibility . '
                            </a>';
                        return $profile;
                    } else {
                        $profile = '<span href="javascript:void(0)">
                               ' . $visibility . '
                            </span>';
                        return $profile;
                    }
                });

                $datatable = $datatable->editColumn('city_id', function ($row) {
                    // return "lahore";
                    $city =  optional($row->city)->{'name_' . App::getLocale()};
                    return (!empty($city)) ? $city : 'N/A';
                });
                $datatable = $datatable->editColumn('address_' . App::getLocale() . '', function ($row) {
                    $address = 'address_' . lang() . '';
                    return (!empty($row->{$address})) ? $row->{$address} : 'N/A';
                });
                $datatable = $datatable->editColumn('phone_number', function ($row) {
                    if ($row->has('countryCode')) {
                        $country_code = optional($row->countryCode)->phonecode;
                    } else {
                        $country_code = "";
                    }
                    return $country_code . $row->phone_number;
                });

                $datatable = $datatable->addColumn('occupation_data', function ($row) {
                    $actions = '';
                    $actions .= '<a onclick="userOccupation(' . $row->id . ')" data-toggle="modal" data-target="#showPostModal" class="btn btn-primary show_post" href="javascript:void(0)" data-post-id="' . $row->id . '" title="Profession"><i class="fa fa-eye"></i></a>';
                    return $actions;
                });


                $datatable = $datatable->rawColumns(['profile', 'phone_number', 'address_' . App::getLocale() . '', 'city_id', 'occupation_data']);
                $datatable = $datatable->make(true);
                return $datatable;
            }
        }

        $name_query = getQuery(App::getLocale(), ['name']);
        $name_query[] = 'id';
        $countries = Country::select($name_query)->where('status', 1)->get();
        $provinces = Province::select($name_query)->where('status', 1)->get();
        $divisions = Division::select($name_query)->where('status', 1)->get();
        $cities = City::select($name_query)->where('status', 1)->get();
        $districts = District::select($name_query)->where('status', 1)->get();
        $tehsils = Tehsil::select($name_query)->where('status', 1)->get();
        $zones = Zone::select($name_query)->where('status', 1)->get();
        $councils = UnionCouncil::select($name_query)->where('status', 1)->get();

        $occupationCols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'parent_id']);
        $occupations = Occupation::query()
            ->select($occupationCols)
            ->where('status', 1)
            ->where('parent_id', null)
            ->with(['subProfession' => fn ($q) => $q->select($occupationCols)])->get();

        return view('user.user-list', get_defined_vars());
    }

    /**
     *Retrieves and displays data related to blood donors, including filtering options for blood group and city, using Datatables for pagination and custom columns.
     */
    public function bloodBank(Request $request)
    {

        if (!have_permission('View-Blood-Bank')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        $cols = array_merge(getQuery(App::getLocale(), ['name']), ['id']);
        $cities = City::select($cols)->where('status', 1)->get();

        $data = [];
        if ($request->ajax()) {

            $group = (isset($_GET['group']) && $_GET['group']) ? $_GET['group'] : '';
            $city = (isset($_GET['city']) && $_GET['city']) ? $_GET['city'] : '';

            $city = ($city && $city == 'my') ? Auth::user()->city_id : '';

            $db_record = Donor::query()
                ->where('status', 1)
                ->whereDate('eligible_after', '<', date('Y-m-d'))
                ->orWhere('eligible_after', '=', null)
                ->orderBy('created_at', 'DESC')
                ->get();

            $db_record = $db_record->when($group, function ($q, $group) {
                return $q->where('blood_group', $group);
            });

            $db_record = $db_record->when($city, function ($q, $city) {
                if ($city) {
                    return $q->where('city_id', $city);
                }
            });

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('age', function ($row) {
                $dateOfBirth = $row->dob;
                $age = Carbon::parse($dateOfBirth)->age;
                return $age;
            });
            $datatable = $datatable->editColumn('dob', function ($row) {
                $dateTime = Carbon::parse($row->dob);
                $dateOnly = $dateTime->toDateString();
                return $dateOnly;
            });
            $datatable = $datatable->addColumn('city', function ($row) {
                return (!empty($row->city)) ? $row->city->{'name_'.App::getLocale()} : 'N/A';
            });
            $datatable = $datatable->editColumn('full_name', function ($row) {
                if (empty($row->user_id)) {
                    $field_name = $row->full_name;
                    $profile_img = getS3File($row->image);
                } else {
                    $field_name = $row->user->user_name;
                    $profile_img = getS3File($row->user->profile_image);
                }
                $img = '<figure class="mb-0 me-2 user-img"> <img src="' . $profile_img . '" class="img-fluid"> </figure>';
                return $img . $field_name;
            });
            $datatable = $datatable->editColumn('full_nameHidden', function ($row) {
                if (empty($row->user_id)) {
                    $field_name = $row->full_name;
                } else {
                    $field_name = $row->user->user_name;
                }
                return $field_name;
            });

            $datatable = $datatable->rawColumns(['age', 'city', 'full_name', 'full_nameHidden']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('user.blood-bank', get_defined_vars());
    }

    /**
     *Retrieves and displays data related to job seekers' posts, including filtering options and custom columns, using Datatables for pagination and actions like downloading resumes and editing posts.
     */
    public function jobBank(Request $request)
    {
        if (!have_permission('View-Job-Bank')) {
            return redirect(route('user.profile'))->with('error', __('app.not-permission'));
        }

        if ($request->ajax()) {
            // $seekersPosts = Post::with(['files', 'user'])->active()->job()->seekers()->latest();
            if (!have_permission('View-Seeking-Job-Bank')) {
                $seekersPosts = collect([]);
            } else {
                $seekersPosts = Post::select('posts.*')->with(['files'])->join('users', function ($join) {
                    $join->on('posts.user_id', '=', 'users.id');
                })
                    ->active()->job()->seekers()->orderBy('posts.created_at', 'DESC')->get();
            }

            $datatable = Datatables::of($seekersPosts);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('name', fn (Post $post) => $post->admin_id != null ? $post->admin->first_name : $post->user->user_name);
            $datatable = $datatable->addColumn('resume', fn (Post $post) => '<a href="' . getS3File($post->resume) . '" download><i class="fa fa-download" aria-hidden="true"></i></a>');
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';
                if ($row->user_id == auth()->user()->id) {
                    // if (have_right('Edit-Admin')) {
                    $actions .= '<span class="btn btn-primary edit_post" data-toggle="modal" data-target="#editPostModal" data-post-id="' . $row->id . '" title="Edit">' . __("app.edit") . '</span>';
                    // }
                } else {
                    $actions .= '<button class="btn btn-secondary job-seeking-btn"  title="Edit" disabled>' . __("app.edit") . '</button>';
                }
                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['name', 'resume', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('user.job-bank', get_defined_vars());
    }

    /**
     *Retrieves and displays data related to job hiring posts, including actions for applying to job posts, using Datatables for pagination and custom columns.
     */
    public function hiringDataTable(Request $request)
    {
        if ($request->ajax()) {
            if (!have_permission('View-Hiring-Job-Bank')) {
                $hiringPosts = collect([]);
            } else {
                $hiringPosts = Post::query()
                    ->with(['files', 'user'])
                    ->active()
                    ->job()
                    ->hiring()
                    ->latest()
                    ->where('user_id', '!=', auth()->user()->id)->get();
            }
            $datatable = Datatables::of($hiringPosts);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('name', fn (Post $post) => $post->user_id != null || $post->user_id != '' ? $post->user->user_name : $post->admin->first_name);
            $datatable = $datatable->addColumn('action', function (Post $post) {
                $isApplied = ApplyJob::where('job_post_id', $post->id)->where('user_id', auth()->user()->id)->exists();
                $text = $isApplied == 1 ? __('app.applied') : __('app.apply');
                $class = $isApplied == 1 ? 'disable-link' : '';
                return '<a class="' . $class . '" href="javascript:void(0)" onclick="applyJob(this)" data-post-id="' . $post->id . '">' . $text . '</a>';
            });

            $datatable = $datatable->rawColumns(['name', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('user.job-bank', get_defined_vars());
    }

    /**
     *Retrieves and displays data related to job posts created by the authenticated user, including custom actions for viewing applicants and editing posts, using Datatables for pagination.
     */
    public function resumeDataTable(Request $request)
    {
        if ($request->ajax()) {
            if (!have_permission('View-CV-Resume-Applicants')) {
                $authUserPosts = collect([]);
            } else {
                $authUserPosts = Post::with('applied')->active()->job()->hiring()->where('user_id', Auth::id())->get();
            }

            $datatable = Datatables::of($authUserPosts);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('action', fn (Post $post) => '<a class="btn btn-primary" href="javascript:void(0)" onclick="viewApplicants(this)" data-post-id="' . $post->id . '"><i class="far fa-eye"></i></a> <span class="btn btn-primary edit_post mt-md-0 mt-1" data-toggle="modal" data-target="#editPostModal" data-post-id="' . $post->id . '" title="Edit"><i class="far fa-edit"></i></span>');

            $datatable = $datatable->rawColumns(['action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
    }

    /**
     *Retrieves and displays data related to applicants for a specific job post, including their name, experience, age, and resume download links, in JSON format.
     */
    public function applicants(Request $request)
    {
        $applicants = ApplyJob::where('job_post_id', $request->post_id)->get();
        $data = '';
        if (count($applicants) == 0) {
            $data .= "<p class='text-center'>" . __('app.no-data-available') . "</p>";
            return response()->json(['status' => 200, 'data' => $data]);
        }
        $count = 0;
        $data = "<table class='table' id='applicate_datatable'>
                              <thead>
                                <tr>
                                  <th scope='col'>#</th>
                                  <th scope='col'>" . __('app.name') . "</th>
                                  <th scope='col'>" . __('app.experience') . "</th>
                                  <th scope='col'>" . __('app.age') . "</th>
                                  <th scope='col'>" . __('app.resume') . "</th>
                                </tr>
                              </thead>
                              <tbody>";

        foreach ($applicants as $person) {
            $count = $count + 1;
            $data .= "<tr>
                    <th scope='row'>$count</th>
                    <td>$person->name</td>
                    <td>$person->experience</td>
                    <td>$person->age</td>
                    <td><a href='" . getS3File($person->resume) . "' download>Download</a></td>
                   </tr>";
        }

        $data .= "</tbody></table>";

        return response()->json(['status' => 200, 'data' => $data]);
    }

    /**
     *displaying cabinet users in table
     */
    public function cabinetUsers(Request $request)
    {
        $cabinetsUser = CabinetUser::where('cabinet_id', $request->cabinet_id)->get()
            ->each(function ($item) {
                $item->user_name = User::find($item->user_id)->{'user_name_' . app()->getLocale()};
                $item->role = Role::find($item->role_id)->{'name_' . app()->getLocale()};
            });
        $data = '';
        if (count($cabinetsUser) == 0) {
            $data .= "<p class='text-center'>" . __('app.no-data-available') . "</p>";
            return response()->json(['status' => 200, 'data' => $data]);
        }
        $count = 0;
        $data = "<table class='table' id='applicate_datatable'>
                              <thead>
                                <tr>
                                  <th scope='col'>#</th>
                                  <th scope='col'>" . __('app.name') . "</th>
                                  <th scope='col'>" . __('app.cabinet-role') . "</th>
                                </tr>
                              </thead>
                              <tbody>";

        foreach ($cabinetsUser as $item) {
            $count = $count + 1;
            $data .= "<tr>
                    <th scope='row'>$count</th>
                    <td>$item->user_name</td>
                    <td>$item->role</td>
                   </tr>";
        }

        $data .= "</tbody></table>";

        return response()->json(['status' => 200, 'data' => $data]);
    }

    /**
     *Retrieves and displays data related to donation items based on selected categories and excluded donation IDs, using AJAX requests for dynamic loading of donation items and Datatables for pagination.
     */
    public function donate(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->all());
            $catId = (isset($_GET['category'])) ? $_GET['category'] : '';

            $donation_ids = (isset($_GET['donationIds'])) ? $_GET['donationIds'] : [];
            $where = '1';
            if ($catId != '') {
                $where .= ' AND donation_category_id=' . $catId;
            }
            if (count($donation_ids) && $donation_ids[0] != "") {
                $donation_ids = implode(',', $donation_ids);
                $where .= ' AND id NOT IN (' . $donation_ids . ')';
            }

            $query = getQuery(App::getLocale(), ['title', 'description']);
            $query[] = 'id';
            $query[] = 'price';
            $query[] = 'file';
            $donations = Donation::select($query)->whereRaw($where)->where(['status' => 1, 'donation_type' => 1])->limit(9)->get();
            // dd($donations);

            $data = [];
            $data['donations'] = $donations;
            // dd($data);
            $html = (string) View('user.partials.donations-partial', $data);
            // dd($html);
            $response = [];
            $response['html'] = $html;
            $response['loadMore'] = count($donations);
            // echo json_encode($response);exit;

            $newResponse = mb_convert_encoding($response, "UTF-8", "auto");
            return response()->json($newResponse);

            // return response()->json($response);

        }

        $data = [];
        $query = getQuery(App::getLocale(), ['name']);
        $query[] = 'id';
        $data['donation_categories'] = DonationCategory::select($query)->where('status', 1)->get();
        return view('user.donations', $data);

        // $query = getQuery(App::getLocale(), ['title', 'description']);
        // $query[] = 'id';
        // $query[] = 'price';
        // $query[] = 'file';

        // $donations = Donation::select($query)->where(['status' => 1])->get();

        // return view('user.donations', get_defined_vars());
    }

    /**
     *Retrieves and displays paginated notifications for the authenticated user, including the user/admin profile image, using Datatables for pagination and AJAX requests for dynamic loading of notifications.
     */
    public function notifications(Request $request)
    {
        $cols = array_merge(getQuery(App::getLocale(), ['title']), ['notifications.id', 'notifications.created_at', 'notifications.link']);

        $notifications = auth()->user()->notifications()
            ->paginate(20, $cols)
            ->each(fn ($q) => $q->profile = $q->pivot->notification_type == 1 ? ((!empty(User::find($q->pivot->from_id))) ? ((!empty(User::find($q->pivot->from_id))) ? User::find($q->pivot->from_id)->profile_image : '') : '') : ((!empty(Admin::find($q->pivot->from_id))) ? Admin::find($q->pivot->from_id)->profile : ''));
        //            ->each(fn($q) => $q->profile = $q->pivot->notification_type == 1 ? ((!empty(User::find($q->pivot->from_id))) ? ((!empty(User::find($q->pivot->from_id))) ? User::find($q->pivot->from_id)->profile_image : '') : '') : Admin::find($q->pivot->from_id)->profile);

        if ($request->ajax()) {
            $view = view('user.partials.notifications-partial', get_defined_vars())->render();
            return response()->json(['html' => $view]);
        }

        return view('user.notifications', get_defined_vars());
    }

    /**
     *Retrieves and displays a specific job post based on the provided ID, including related data such as images, comments, likes, user, and admin, as well as the count of comments and likes.
     */
    public function specificPost($id)
    {
        $id = hashDecode($id);

        $cols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'admin_id', 'user_id', 'hiring_company_name', 'job_type', 'post_type', 'product_id', 'city', 'address', 'hospital', 'occupation', 'experience', 'skills', 'resume', 'description_english']);

        $posts = Post::select($cols)->with(['images', 'comments.user', 'likes', 'user', 'admin'])
            ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
            ->where('id', $id)
            ->get();

        return view('user.dashboard', get_defined_vars());
    }

    /**
     *Handles AJAX requests to filter addresses based on selected country, province, division, district, tehsil, and zone IDs, returning the filtered data in JSON format.
     */
    public function filterAddresses(Request $request)
    {
        // dd($request->tehsil_id);
        if (
            (isset($request->country_id) &&  !is_array($request->country_id))
            || (isset($request->province_id) &&  !is_array($request->province_id))
            || (isset($request->division_id) &&  !is_array($request->division_id))
            || (isset($request->district_id) &&  !is_array($request->district_id))
            || (isset($request->tehsil_id) &&  !is_array($request->tehsil_id))
            || (isset($request->zone_id) &&  !is_array($request->zone_id))

        ) {
            return  $this->addressFunction($request);
        }
        $name_query = getQuery(App::getLocale(), ['name']);
        $name_query[] = 'id';
        $provinceCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'country_id']);
        $divisionCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'province_id']);
        $districtCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'division_id']);
        $tehsilCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'district_id']);
        $zoneCols = array_merge(getQuery(App::getLocale(), ['name']), ['id', 'tehsil_id']);
        $unionCouncilCols = array_merge(getQuery(App::getLocale(), ['name']), ['tehsil_id', 'zone_id']);
        if ($request->ajax()) {
            if ($request->has('country_id')) {
                // dd("ok");
                //    $provinces = Province::select($name_query)->where('country_id', $request->country_id)->where('status', 1)->get();
                $country_id = is_array($request->country_id) ? $request->country_id : (array)$request->country_id;
                $provinces = Province::query()->select($provinceCols)->whereIn('country_id', $country_id)->get()
                    ->groupBy(fn ($province) => Country::where('id', $province->country_id)->first()->name_english);
                // dd($provinces);
                return response()->json(['status' => 200, 'data' => $provinces, 'total' => $provinces->count()]);
            }
            if ($request->has('province_id')) {
                //                $divisions = Division::select($name_query)->where('province_id', $request->province_id)->where('status', 1)->get();
                $divisions = Division::query()->select($divisionCols)->whereIn('province_id', $request->province_id)->get()
                    ->groupBy(fn ($division) => Province::where('id', $division->province_id)->first()->name_english);

                //                $cities = City::select($name_query)->where('province_id', $request->province_id)->where('status', 1)->get();
                $cities = City::query()->select($divisionCols)->whereIn('province_id', $request->province_id)->get()
                    ->groupBy(fn ($city) => Province::where('id', $city->province_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $divisions, 'total' => $divisions->count(), 'cities' => $cities, 'city_total' => $cities->count()]);
            }
            if ($request->has('division_id')) {
                //                $districts = District::select($name_query)->where('division_id', $request->division_id)->where('status', 1)->get();
                $districts = District::query()->select($districtCols)->whereIn('division_id', $request->division_id)->get()
                    ->groupBy(fn ($district) => Division::where('id', $district->division_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $districts, 'total' => $districts->count()]);
            }
            if ($request->has('district_id')) {
                //                $tehsils = Tehsil::select($name_query)->where('district_id', $request->district_id)->where('status', 1)->get();
                $tehsils = Tehsil::query()->select($tehsilCols)->whereIn('district_id', $request->district_id)->get()
                    ->groupBy(fn ($tehsil) => District::where('id', $tehsil->district_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $tehsils, 'total' => $tehsils->count()]);
            }
            if ($request->has('tehsil_id')) {
                //                $zones = Zone::select($name_query)->where('tehsil_id', $request->tehsil_id)->where('status', 1)->get();
                $zones = Zone::query()->select($zoneCols)->whereIn('tehsil_id', $request->tehsil_id)->get()
                    ->groupBy(fn ($zone) => Tehsil::where('id', $zone->tehsil_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $zones, 'total' => $zones->count()]);
            }
            if ($request->has('zone_id')) {
                //                $councils = UnionCouncil::select($name_query)->where('zone_id', $request->zone_id)->where('status', 1)->get();
                $councils = UnionCouncil::query()->select($unionCouncilCols)->whereIn('zone_id', $request->zone_id)->get()
                    ->groupBy(fn ($unionCouncil) => Zone::where('id', $unionCouncil->zone_id)->first()->name_english);

                return response()->json(['status' => 200, 'data' => $councils, 'total' => $councils->count()]);
            }
            // if ($request->has('council_id')) {
            //     //                $councils = UnionCouncil::select($name_query)->where('zone_id', $request->zone_id)->where('status', 1)->get();
            //         $councils = UnionCouncil::query()->select($unionCouncilCols)->whereIn('zone_id', $request->zone_id)->get()
            //             ->groupBy(fn($unionCouncil) => Zone::where('id', $unionCouncil->zone_id)->first()->name_english);

            //         return response()->json(['status' => 200, 'data' => $councils, 'total' => $councils->count()]);
            // }
        }
    }

    /**
     *handles AJAX requests to filter users based on selected country, province, division, district, tehsil, zone, occupation, and council IDs.
     */
    public function applyFilterAddresses(Request $request)
    {
        if ($request->ajax()) {
            // dd("ok before");
            if (isset($request->data_type) && $request->data_type == 'user-list') {
                $this->getfilterDataUserDashboard($request);
            }

            $data = User::query()
                ->when($request->slug, function ($q) use ($request) {
                    $occupationCols = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id']);
                    $occupation = Occupation::select($occupationCols)->where('slug', $request->slug)->where('status', 1)->first();
                    return $q->whereHas('userOccupation', fn ($query) => $query->where('status', 1)->where('occupation_id', $occupation->id));
                })
                ->when($request->country_id, fn ($q) => $q->whereIn('country_id', $request->country_id))
                ->when($request->province_id, fn ($q) => $q->whereIn('province_id', $request->province_id))
                ->when($request->division_id, fn ($q) => $q->whereIn('division_id', $request->division_id))
                ->when($request->city_id, fn ($q) => $q->whereIn('city_id', $request->city_id))
                ->when($request->district_id, fn ($q) => $q->whereIn('district_id', $request->district_id))
                ->when($request->tehsil_id, fn ($q) => $q->whereIn('tehsil_id', $request->tehsil_id))
                ->when($request->zone_id, fn ($q) => $q->whereIn('zone_id', $request->zone_id))
                ->when($request->occupation_id, fn ($q) => $q->whereHas('userOccupation', fn ($q2) => $q2->whereIn('occupation_id', $request->occupation_id)))
                ->when($request->council_id, fn ($q) => $q->whereIn('union_council_id', $request->council_id))
                ->get();
            $datatable = Datatables::of($data);

            $datatable = $datatable->addColumn('profile', function ($row) {
                $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                if ($row->is_public == 1) {
                    $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->id)]) . '">
                               ' . $visibility . '
                            </a>';
                    return $profile;
                } else {
                    $profile = '<span href="javascript:void(0)">
                               ' . $visibility . '
                            </span>';
                    return $profile;
                }
            });
            $datatable = $datatable->addColumn('profileStatus', function ($row) {
                $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                return $visibility;
            });

            $datatable = $datatable->editColumn('city_id', function ($row) {
                // return "lahore";
                $city =  optional($row->city)->{'name_' . App::getLocale()};
                return (!empty($city)) ? $city : 'N/A';
            });
            $datatable = $datatable->editColumn('address_' . App::getLocale() . '', function ($row) {
                $address = 'address_' . lang() . '';
                return (!empty($row->{$address})) ? $row->{$address} : 'N/A';
            });
            $datatable = $datatable->editColumn('phone_number', function ($row) {
                if ($row->has('countryCode')) {
                    $country_code = optional($row->countryCode)->phonecode;
                } else {
                    $country_code = "";
                }
                return $country_code . $row->phone_number;
                // return "o303";
            });
            $datatable = $datatable->addColumn('occupation_data', function ($row) {

                return '<a onclick="userOccupation(' . $row->id . ')" data-toggle="modal" data-target="#showPostModal" class="btn btn-primary show_post" href="javascript:void(0)" data-post-id="' . $row->id . '" title="Profession"><i class="fa fa-eye"></i></a>';
            });

            $datatable = $datatable->editColumn('profile_image', function ($row) {
                if (!empty($row)) {
                    $profile_img = getS3File($row->profile_image);
                }
                $img = '<img src="' . $profile_img . '"class="img-fluid" style="width: 20%;height: auto;">';
                return $img ;
            });

            $datatable = $datatable->rawColumns(['profile', 'profileStatus', 'phone_number', 'address_' . App::getLocale() . '', 'city_id', 'occupation_data', 'profile_image']);


            // $datatable = $datatable->rawColumns(['profile','profileStatus']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
    }

    /**
     *Filterr User Data and dispaly it on user dashboard.
     */
    public function getfilterDataUserDashboard($request)
    {
        // dd($request->slug );
        $data = User::query()
            ->when($request->slug, function ($q) use ($request) {
                $occupationCols = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id']);
                $occupation = Occupation::select($occupationCols)->where('slug', $request->slug)->where('status', 1)->first();
                return $q->whereHas('userOccupation', fn ($query) => $query->where('status', 1)->where('occupation_id', $occupation->id));
            })
            ->when($request->country_id, fn ($q) => $q->whereIn('country_id', $request->country_id))
            ->when($request->province_id, fn ($q) => $q->whereIn('province_id', $request->province_id))
            ->when($request->division_id, fn ($q) => $q->whereIn('division_id', $request->division_id))
            ->when($request->city_id, fn ($q) => $q->whereIn('city_id', $request->city_id))
            ->when($request->district_id, fn ($q) => $q->whereIn('district_id', $request->district_id))
            ->when($request->tehsil_id, fn ($q) => $q->whereIn('tehsil_id', $request->tehsil_id))
            ->when($request->zone_id, fn ($q) => $q->whereIn('zone_id', $request->zone_id))
            ->when($request->occupation_id, fn ($q) => $q->whereHas('userOccupation', fn ($q2) => $q2->whereIn('occupation_id', $request->occupation_id)))
            ->when($request->council_id, fn ($q) => $q->whereIn('union_council_id', $request->council_id))
            ->get();
        $datatable = Datatables::of($data);

        $datatable = $datatable->addColumn('profile', function ($row) {
            $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
            if ($row->is_public == 1) {
                $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->id)]) . '">
                        ' . $visibility . '
                        </a>';
                return $profile;
            } else {
                $profile = '<span href="javascript:void(0)">
                        ' . $visibility . '
                        </span>';
                return $profile;
            }
        });
        $datatable = $datatable->addColumn('profileStatus', function ($row) {
            $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
            return $visibility;
        });
        $datatable = $datatable->rawColumns(['profile', 'profileStatus']);
        $datatable = $datatable->make(true);
        return $datatable;
    }

    /**
     * handles AJAX requests to filter users based on selected country, province, division, district, tehsil, zone, occupation, and council IDs.
     */
    public function applyFilterAddressesDashboard(Request $request)
    {
        if ($request->ajax()) {
            // dd("ok before");
            $data = User::query()
                ->when($request->slug, function ($q) use ($request) {
                    $occupationCols = array_merge(getQuery(App::getLocale(), ['title', 'content']), ['id']);
                    $occupation = Occupation::select($occupationCols)->where('slug', $request->slug)->where('status', 1)->first();
                    return $q->whereHas('userOccupation', fn ($query) => $query->where('status', 1)->where('occupation_id', $occupation->id));
                })
                ->when($request->country_id, fn ($q) => $q->whereIn('country_id', $request->country_id))
                ->when($request->province_id, fn ($q) => $q->whereIn('province_id', $request->province_id))
                ->when($request->division_id, fn ($q) => $q->whereIn('division_id', $request->division_id))
                ->when($request->city_id, fn ($q) => $q->whereIn('city_id', $request->city_id))
                ->when($request->district_id, fn ($q) => $q->whereIn('district_id', $request->district_id))
                ->when($request->tehsil_id, fn ($q) => $q->whereIn('tehsil_id', $request->tehsil_id))
                ->when($request->zone_id, fn ($q) => $q->whereIn('zone_id', $request->zone_id))
                ->when($request->occupation_id, fn ($q) => $q->whereHas('userOccupation', fn ($q2) => $q2->whereIn('occupation_id', $request->occupation_id)))
                ->when($request->council_id, fn ($q) => $q->whereIn('union_council_id', $request->council_id))
                ->get();

            $datatable = Datatables::of($data);


            $datatable = $datatable->addColumn('profile', function ($row) {
                $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                if ($row->is_public == 1) {
                    $profile = '<a href="' . route('user.profile', ['id' => hashEncode($row->id)]) . '">
                               ' . $visibility . '
                            </a>';
                    return $profile;
                } else {
                    $profile = '<span href="javascript:void(0)">
                               ' . $visibility . '
                            </span>';
                    return $profile;
                }
            });
            $datatable = $datatable->addColumn('profileStatus', function ($row) {
                $visibility = $row->is_public == 0 ? __('app.private-profile') : __('app.public-profile');
                return $visibility;
            });


            $datatable = $datatable->rawColumns(['profile', 'profileStatus']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
    }

    /**
     *Used to set the langugae to locale at run time
     */
    public function setLanguageLocal(Request $request)
    {
        session()->put('locale', $request->lang);
        return redirect()->back();
    }

    /**
     *valudating user when email and username is unique
     */
    public function validateRegisteration(Request $request)
    {
        $type = $request->type;
        $colVal = $request->colVal;
        $colVal = explode(",", $colVal);
        $duplicate = '';
        if ($type == 'email') {
            $duplicate = User::where('email', $colVal[0])->count();
            $message = __('app.email-unique');
        } else if ($type == 'user_name') {
            $duplicate = User::where('user_name', $colVal[0])->count();
            $message = __('app.username-unique');
        } else if ($type == 'phone_number') {
            $duplicate = User::where(['country_code_id' => $colVal[1], 'phone_number' => $colVal[0]])->count();
            $message = __('app.phone-unique');
        }
        if ($duplicate > 0) {
            return response()->json($message);
        } else {
            return response()->json(true);
        }
    }

    /**
     *Terms and conditions for the user
     */
    public function termsCondition(Request $request)
    {
        $data = [];
        $data['plan'] = BusinessPlan::where('id', encodeDecode($request->plan_id))->first();
        return view('home.home-pages.terms-condition-pages.terms-condition', $data);
    }

    /**
     * used to retrieve location-related data based on the selected country, province, division, district, tehsil, and zone IDs. It's likely used to populate the dropdowns in the filter form dynamically.
     */
    public function addressFunction($request)
    {

        $name_query = getQuery(App::getLocale(), ['name']);
        $name_query[] = 'id';
        if ($request->ajax()) {
            if ($request->has('country_id')) {
                $provinces = Province::select($name_query)->where('country_id', $request->country_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $provinces, 'total' => $provinces->count()]);
            }
            if ($request->has('province_id')) {
                $divisions = Division::select($name_query)->where('province_id', $request->province_id)->where('status', 1)->get();
                $cities = City::select($name_query)->where('province_id', $request->province_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $divisions, 'total' => $divisions->count(), 'cities' => $cities, 'city_total' => $cities->count()]);
            }
            if ($request->has('division_id')) {
                $districts = District::select($name_query)->where('division_id', $request->division_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $districts, 'total' => $districts->count()]);
            }
            if ($request->has('district_id')) {
                $tehsils = Tehsil::select($name_query)->where('district_id', $request->district_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $tehsils, 'total' => $tehsils->count()]);
            }
            if ($request->has('tehsil_id')) {
                $zones = Zone::select($name_query)->where('tehsil_id', $request->tehsil_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $zones, 'total' => $zones->count()]);
            }
            if ($request->has('zone_id')) {
                $councils = UnionCouncil::select($name_query)->where('zone_id', $request->zone_id)->where('status', 1)->get();
                return response()->json(['status' => 200, 'data' => $councils, 'total' => $councils->count()]);
            }
        }
    }
}
