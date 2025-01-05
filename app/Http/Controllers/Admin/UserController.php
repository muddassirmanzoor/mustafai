<?php







namespace App\Http\Controllers\Admin;







use App\Helper\ImageOptimize;

use App\Helper\UserFee;



use App\Http\Controllers\Controller;



use App\Models\Admin\CountryCode;



use App\Models\Admin\NotificationUser;



use App\Models\Admin\Occupation;



use App\Models\Admin\Role;

use App\Models\Admin\Designation;



use App\Models\Admin\UserSubscription;



use App\Models\User;



use App\Models\User\PermanentAddress;



use App\Models\User\UserEducation;



use App\Models\User\UserExperience;



use App\Models\User\UserOccupation;



use Carbon\Carbon;



use DataTables;



use Exception;



use Hash;



use Illuminate\Http\Request;



use Illuminate\Support\Facades\Validator;



use Illuminate\Support\Facades\View;



use Illuminate\Validation\Rule;



use Illuminate\Support\Facades\App;



use App\Models\Admin\Country;



use App\Models\Admin\City;



use App\Models\Admin\District;



use App\Models\Admin\Division;



use App\Models\Admin\Province;



use App\Models\Admin\Tehsil;



use App\Models\Admin\Zone;







use App\Models\Admin\UnionCouncil;

use App\Services\FirebaseNotificationService;

use DB;



use Mail;



use Symfony\Component\HttpFoundation\Response;







class UserController extends Controller



{



    protected $firebaseNotification;



    public function __construct(FirebaseNotificationService $firebaseNotification)

    {

        $this->firebaseNotification = $firebaseNotification;

    }







    /**



     * listing the Users



     */



    public function index(Request $request)



    {



        if (!have_right('View-User')) {



            access_denied();

        }







        $data = [];



        $data['users'] = User::orderBy('user_name', 'DESC')->get(['user_name']);



        $data['roles'] = Role::where('type', 2)->orderBy('order_rows', 'ASC')->get();



        $data['designations'] = Designation::where('type', 2)->orderBy('order_rows', 'ASC')->get();







        $data['occupations'] = Occupation::where('parent_id', null)->orderBy('id', 'DESC')->get(['title_english', 'id']);







        $data['countries'] = Country::select('id', 'name_english as name')->where('status', 1)->get();



        $data['provinces'] = Province::select('id', 'name_english as name')->where('status', 1)->get();



        $data['divisions'] = Division::select('id', 'name_english as name')->where('status', 1)->get();



        $data['districts'] = District::select('id', 'name_english as name')->where('status', 1)->get();



        $data['tehsils'] = Tehsil::select('id', 'name_english as name')->where('status', 1)->get();



        $data['zones']  = Zone::select('id', 'name_english as name')->where('status', 1)->get();



        $data['cities'] = City::select('id', 'name_english as name')->where('status', 1)->get();



        $data['union_councils'] =  UnionCouncil::select('id', 'name_english as name')->where('status', 1)->get();



        if ($request->ajax()) {



            $status = (isset($_GET['status']) && $_GET['status']) ? $_GET['status'] : '';



            $occupation = (isset($_GET['occupation']) && $_GET['occupation']) ? $_GET['occupation'] : '';



            $roleId = (isset($_GET['user_role']) && $_GET['user_role']) ? $_GET['user_role'] : '';



            $designationId = (isset($_GET['user_designation']) && $_GET['user_designation']) ? $_GET['user_designation'] : '';



            $child_occupation = (isset($_GET['child_occupation']) && $_GET['child_occupation']) ? $_GET['child_occupation'] : '';



            $from_date = (isset($_GET['from_date']) && $_GET['from_date']) ? $_GET['from_date'] : '';



            $to_date = (isset($_GET['to_date']) && $_GET['to_date']) ? $_GET['to_date'] : '';



            $profile_status = (isset($_GET['profile_status']) && $_GET['profile_status']) ? $_GET['profile_status'] : '';







            $countryId = (isset($_GET['country_id']) && $_GET['country_id']) ? $_GET['country_id'] : '';



            $provinceId = (isset($_GET['province_id']) && $_GET['province_id']) ? $_GET['province_id'] : '';



            $divisionId = (isset($_GET['division_id']) && $_GET['division_id']) ? $_GET['division_id'] : '';



            $cityId = (isset($_GET['city_id']) && $_GET['city_id']) ? $_GET['city_id'] : '';



            $districtId = (isset($_GET['district_id']) && $_GET['district_id']) ? $_GET['district_id'] : '';



            $tehsilId = (isset($_GET['tehsil_id']) && $_GET['tehsil_id']) ? $_GET['tehsil_id'] : '';



            $zoneId = (isset($_GET['zone_id']) && $_GET['zone_id']) ? $_GET['zone_id'] : '';



            $unionCouncilId = (isset($_GET['union_council_id']) && $_GET['union_council_id']) ? $_GET['union_council_id'] : '';







            $where = '';



            if ($profile_status == 'completed') {



                $where = 1;

            } elseif ($profile_status == 'incompleted') {



                $where = 0;

            } else {



                $where = 1;

            }







            $db_record = User::where('is_completed_profile', $where)->orderBy('full_name', 'ASC')



                ->with(['role', 'designation', 'userSubscriptions' => function ($query) {



                    $query->latest();

                }])



                ->get();







            $db_record = $db_record->when($status, function ($q, $status) {



                $status = $status == 'active' ? 1 : 0;



                return $q->where('status', $status);

            });



            $db_record = $db_record->when($roleId, function ($q, $roleId) {



                return $q->where('role_id', $roleId);

            });



            $db_record = $db_record->when($designationId, function ($q, $designationId) {

                return $q->where('designation_id', $designationId);

            });







            //Address filter



            $db_record = $db_record->when($countryId, function ($q, $countryId) {



                return $q->where('country_id', $countryId);

            });







            $db_record = $db_record->when($provinceId, function ($q, $provinceId) {



                return $q->where('province_id', $provinceId);

            });







            $db_record = $db_record->when($divisionId, function ($q, $divisionId) {



                return $q->where('division_id', $divisionId);

            });







            $db_record = $db_record->when($cityId, function ($q, $cityId) {



                return $q->where('city_id', $cityId);

            });







            $db_record = $db_record->when($districtId, function ($q, $districtId) {



                return $q->where('district_id', $districtId);

            });







            $db_record = $db_record->when($tehsilId, function ($q, $tehsilId) {



                return $q->where('tehsil_id', $tehsilId);

            });







            $db_record = $db_record->when($zoneId, function ($q, $zoneId) {



                return $q->where('zone_id', $zoneId);

            });







            $db_record = $db_record->when($unionCouncilId, function ($q, $unionCouncilId) {



                return $q->where('union_council_id', $unionCouncilId);

            });







            $db_record = $db_record->when($occupation, function ($q, $occupation) {



                $user_occupation = UserOccupation::where('occupation_id', $occupation)->groupBy('user_id')->pluck('user_id');



                return $q->whereIn('id', $user_occupation);

            });



            $db_record = $db_record->when($child_occupation, function ($q, $child_occupation) {



                $user_child_occupation = UserOccupation::whereIn('occupation_id', $child_occupation)->groupBy('user_id')->pluck('user_id');



                return $q->whereIn('id', $user_child_occupation);

            });



            $db_record = $db_record->when($from_date, function ($q, $from_date) {



                $startDate = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';



                return $q->where('created_at', '>=', $startDate);

            });



            $db_record = $db_record->when($to_date, function ($q, $to_date) {



                $endDate = date('Y-m-d', strtotime($to_date)) . ' 23:59:00';



                return $q->where("created_at", '<=', $endDate);

            });







            $datatable = Datatables::of($db_record);



            $datatable = $datatable->addIndexColumn();







            $datatable = $datatable->editColumn('status', function ($row) {



                $reciept_status = 'Pending';





                if (count($row->userSubscriptions) > 0) {



                    if ($row->userSubscriptions[0]->reciept == null && $row->userSubscriptions[0]->is_paid == 0) {



                        $reciept_status = 'Pending';

                    } elseif ($row->userSubscriptions[0]->reciept == null && $row->userSubscriptions[0]->is_paid == 2) {



                        $reciept_status = 'Skip';

                    } else if ($row->userSubscriptions[0]->reciept != null && $row->userSubscriptions[0]->is_paid == 0 || $row->userSubscriptions[0]->is_paid == 2) {



                        $reciept_status = 'Draft ' . '&nbsp;&nbsp;&nbsp;';



                        $reciept_status .= '<a href="' . getS3File($row->userSubscriptions[0]->reciept) . '" download style="cursor: pointer;">Download</a>';

                    } else if ($row->userSubscriptions[0]->reciept != null && $row->userSubscriptions[0]->is_paid == 1) {



                        $reciept_status = 'Paid ' . '&nbsp;&nbsp;&nbsp;';



                        $reciept_status .= '<a href="' . getS3File($row->userSubscriptions[0]->reciept) . '" download style="cursor: pointer;">Download</a>';

                    }

                }







                return $reciept_status;

            });







            // $datatable = $datatable->editColumn('status', function ($row) {



            //     $status = '<span class="badge badge-danger">Disable</span>';



            //     if ($row->status == 1) {



            //         $status = '<span class="badge badge-success">Active</span>';



            //     }



            //     return $status;



            // });



            $datatable = $datatable->editColumn('statusColumn', function ($row) {



                if ($row->status == 1) {



                    $status = 'Active';

                } else {



                    $status = 'Disable';

                }



                return $status;

            });



            $datatable = $datatable->addColumn('approved', function ($row) {



                if ($row->is_aproved == 1) {



                    $checked = 'checked';

                } else {



                    $checked = '';

                }







                $featured = '<label class="switch"> <input type="checkbox" class="is_approved" id="chk_' . $row->id . '" name="is_approved" onclick="is_approved($(this),' . $row->id . ')" ' . $checked . ' > <span class="slider round"></span></label>';



                return $featured;

            });



            $datatable = $datatable->addColumn('action', function ($row) {



                $actions = '<span class="actions">';







                if (have_right('Edit-User')) {



                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/users/" . $row->id . '/edit') . '" title="Edit"><i class="far fa-edit"></i></a>';

                }







                if (have_right('View-User')) {



                    $actions .= '&nbsp;<a data-toggle="modal" data-target="#showUserModal" class="btn btn-primary show_user" href="javascript:void(0)" data-user-id="' . $row->id . '" title="Show"><i class="far fa-eye"></i></a>';

                }







                if (have_right('Set-Users-Subscription-Amount')) {



                    $actions .= '&nbsp;<a class="btn btn-primary" href="javascript:void(0)" onclick="subscriptionAmount(' . $row->id . ')" title="Subscription Amount"><i class="far fa-credit-card"></i></a>';

                }



                if (have_right('View-User-Subscription')) {



                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/users/" . $row->id . '/subscription') . '" title="View User Subscription"><i class="fa fa-address-card" aria-hidden="true"></i></a>';

                }







                if (have_right('Delete-User')) {



                    $actions .= '<form method="POST" action="' . url("admin/users/" . $row->id) . '" accept-charset="UTF-8" style="display:inline;">';



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







            $datatable = $datatable->rawColumns(['status', 'statusColumn', 'action', 'approved']);



            $datatable = $datatable->make(true);







            return $datatable;

        }







        return view('admin.users.listing', $data);

    }







    /**



     * creating the Users



     */



    public function create()



    {



        if (!have_right('Create-User')) {



            access_denied();

        }







        $data = [];



        $data['row'] = new User();



        $data['roles'] = Role::where('type', 2)->orderBy('order_rows', 'ASC')->get();



        $data['designations'] = Designation::where('type', 2)->orderBy('order_rows', 'ASC')->get();



        $data['country_codes'] = CountryCode::orderBy(DB::raw('id = 160'), 'DESC')->get();



        $data['countries'] = Country::select('id', 'name_english as name')->where('status', 1)->get();







        $data['provinces'] = Province::select('id', 'name_english as name')->where('status', 1)->get();



        $data['divisions'] = Division::select('id', 'name_english as name')->where('status', 1)->get();



        $data['districts'] = District::select('id', 'name_english as name')->where('status', 1)->get();



        $data['tehsils'] = Tehsil::select('id', 'name_english as name')->where('status', 1)->get();



        $data['zones']  = Zone::select('id', 'name_english as name')->where('status', 1)->get();



        $data['cities'] = City::select('id', 'name_english as name')->where('status', 1)->get();



        $data['union_councils'] =  UnionCouncil::select('id', 'name_english as name')->where('status', 1)->get();



        $data['occupations'] = Occupation::with(['subProfession'])->where('parent_id', '=', null)->where('status', 1)->get();



        $data['userOccupationIds'] = [];



        $data['action'] = 'add';



        return View('admin.users.form', $data);

    }







    /**



     * edit the Users



     */



    public function edit($id)



    {



        if (!have_right('Edit-User')) {



            access_denied();

        }







        $toFindUser = User::where('id', $id)->exists();







        if (!$toFindUser) {



            return abort(403, 'The resource you want to access is deleted');

        }







        $data = [];







        $data['id'] = $id;



        $data['row'] = User::find($id);



        $data['roles'] = Role::where('type', 2)->orderBy('order_rows', 'ASC')->get();



        $data['designations'] = Designation::where('type', 2)->orderBy('order_rows', 'ASC')->get();



        $data['country_codes'] = CountryCode::orderBy(DB::raw('id = 160'), 'DESC')->get();



        $data['countries'] = Country::select('id', 'name_english as name')->where('status', 1)->get();







        $data['provinces'] = Province::select('id', 'name_english as name')->where('status', 1)->get();



        $data['divisions'] = Division::select('id', 'name_english as name')->where('status', 1)->get();



        $data['districts'] = District::select('id', 'name_english as name')->where('status', 1)->get();



        $data['tehsils'] = Tehsil::select('id', 'name_english as name')->where('status', 1)->get();



        $data['zones']  = Zone::select('id', 'name_english as name')->where('status', 1)->get();



        $data['cities'] = City::select('id', 'name_english as name')->where('status', 1)->get();



        $data['union_councils'] =  UnionCouncil::select('id', 'name_english as name')->where('status', 1)->get();



        $data['action'] = 'edit';



        $data['occupations'] = Occupation::with(['subProfession'])->where('parent_id', '=', null)->where('status', 1)->get();



        $data['userOccupationIds'] = UserOccupation::where('user_id', $id)->pluck('occupation_id')->toArray();







        return View('admin.users.form', $data);

    }







    /**



     * storing the Users



     */



    public function store(Request $request)



    {



        $request['user_name'] = $request->user_name;



        $input = $request->all();



        $exp = [];



        $occu = [];







        if (!empty($request->title_english)) {



            for ($i = 0; $i < count($request->title_english); $i++) {



                $exp[] = array(



                    'title_english' => $request->title_english[$i],



                    'title_urdu' => isset($request->title_urdu[$i]) ? $request->title_urdu[$i] : '',



                    'title_arabic' => isset($request->title_arabic[$i]) ?  $request->title_arabic[$i] : '',



                    'experience_company_english' => $request->experience_company_english[$i],



                    'experience_company_urdu' => isset($request->experience_company_urdu[$i]) ? $request->experience_company_urdu[$i] : '',



                    'experience_company_arabic' => isset($request->experience_company_arabic[$i]) ?  $request->experience_company_arabic[$i] : '',



                    'experience_location_english' => $request->experience_location_english[$i],



                    'experience_location_urdu' => isset($request->experience_location_urdu[$i]) ? $request->experience_location_urdu[$i] : '',



                    'experience_location_arabic' => isset($request->experience_location_arabic[$i]) ? $request->experience_location_arabic[$i] : '',



                    'experience_start_date' => $request->experience_start_date[$i],



                    'experience_end_date' => $request->experience_end_date[$i],



                    'is_currently_working' => isset($request->is_currently_working[$i]) ? $request->is_currently_working[$i] : 0,



                    'user_id' => $input['id'],



                    'created_at' => Carbon::now(),



                    'updated_at' => Carbon::now(),



                );

            }

        }



        if (!empty($request->institute_english)) {



            for ($i = 0; $i < count($request->institute_english); $i++) {



                $edu[] = array(



                    'institute_english' => $request->institute_english[$i],



                    'institute_urdu' => isset($request->institute_urdu[$i]) ? $request->institute_urdu[$i] : '',



                    'institute_arabic' => isset($request->institute_arabic[$i]) ? $request->institute_arabic[$i] : '',



                    'degree_name_english' => $request->degree_name_english[$i],



                    'degree_name_urdu' => isset($request->degree_name_urdu[$i]) ? $request->degree_name_urdu[$i] : '',



                    'degree_name_arabic' => isset($request->degree_name_arabic[$i]) ? $request->degree_name_arabic[$i] : '',



                    'start_date' => $request->start_date[$i],



                    'end_date' => $request->end_date[$i],



                    'user_id' => $input['id'],



                    'created_at' => Carbon::now(),



                    'updated_at' => Carbon::now(),



                );

            }

        }



        if (isset($input['password']) || isset($input['original_password'])) {



            $input['password'] = Hash::make($input['password']);



            $input['original_password'] = $input['repeat_password'];

        } else {



            unset($input['password'], $input['original_password']);

        }



        if ($input['action'] == 'add') {



            if (!have_right('Create-User')) {



                access_denied();

            }







            $messages = [



                'required' => 'The :attribute field is required.',



                'unique' => 'The :attribute has already been taken.',



            ];



            $validator = Validator::make($request->all(), [



                'user_name' => 'required|string|max:200',



                'email' => 'required|string|max:100|unique:users,email',



                'role_id' => 'required',



            ], $messages);







            if ($validator->fails()) {



                foreach ($validator->errors()->messages() as $message) {



                    return back()->with('error', $message[0]);

                }



                // Session::flash('flash_danger', $validator->messages());



                // return redirect()->back()->withInput();



            }



            unset($input['action']);



            unset($input['repeat_password']);



            $model = new User();



            $model->fill($input);



            if ($model->save()) {



                if ($this->hasAddress($model)) {



                    $user_profile = User::find($model->id);



                    $user_profile->update(['is_completed_profile' => 1]);

                }



                if ($request->has('permanent_address_english')) {



                    PermanentAddress::updateOrCreate(['user_id' => $model->id], [



                        'permanent_address_english' => $request->permanent_address_english,



                        'permanent_address_urdu' => $request->permanent_address_urdu,



                        'permanent_address_arabic' => $request->permanent_address_arabic,



                    ]);

                }



                if (!empty($request->title_english)) {



                    for ($i = 0; $i < count($exp); $i++) {



                        $exp[$i]['user_id'] = $model->id;

                    }



                    $experience = UserExperience::insert($exp);

                }



                if (!empty($request->institute_english)) {



                    for ($i = 0; $i < count($edu); $i++) {



                        $edu[$i]['user_id'] = $model->id;

                    }







                    $education = UserEducation::insert($edu);

                }



                $details = [



                    'subject' => "Successfully registered",



                    'user_name' => $input['user_name'],



                    'content' => "<p>You are registered successfully on Mustafai Portal by admin.</p><p>Your credentials are as follows:</p>



                    <bold><p> Email:" . $model->email . "</p><p>Password:" . $model->original_password . "</p></bold>",



                    'links' => "<a href='" . url('/login') . "'>Click Here</a> to log in to Mustafai Portal.",



                ];



                if (!empty($request->occupation_ids)) {



                    for ($i = 0; $i < count($request->occupation_ids); $i++) {



                        $occu[] = array(



                            'occupation_id' => $request->occupation_ids[$i],



                            'user_id' => $model->id,



                            'status' => 1,



                            'created_at' => Carbon::now(),



                            'updated_at' => Carbon::now(),



                        );

                    }

                }



                // Update user occupations



                if (!empty($request->occupation_ids) || $request->has('other_profession') && !empty($request->other_profession)) {



                    UserOccupation::where('user_id', $model->id)->delete();



                    $user_occupation = UserOccupation::insert($occu);



                    $occupation_exists = Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->exists();



                    if (!$occupation_exists) {



                        $occupation = new Occupation([



                            'title_english' => $request->other_profession,



                            'title_urdu' => $request->other_profession,



                            'slug' => strtolower(preg_replace('/\s+/', '-', $request->other_profession)),



                            'status' => 1



                        ]);







                        if ($occupation->save()) {



                            $user_other_occupation = new UserOccupation([



                                'occupation_id' => $occupation->id,



                                'user_id' => $model->id,



                                'status' => 1,



                                'created_at' => now(),



                                'updated_at' => now(),



                            ]);



                            $user_other_occupation->save();

                        }

                    } else {



                        $occupation_exists_id = Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->pluck('id')->first();



                        $user_other_occupation = new UserOccupation([



                            'occupation_id' => $occupation_exists_id,



                            'user_id' => $model->id,



                            'status' => 1,



                            'created_at' => now(),



                            'updated_at' => now(),



                        ]);



                        $user_other_occupation->save();

                    }

                } else {



                    UserOccupation::where('user_id', $model->id)->delete();

                }



                // sendEmail($model->email, $details);



                saveEmail($model->email, $details);

            }



            return redirect('admin/users')->with('message', 'Data added Successfully');

        } else {



            if (!have_right('Edit-User')) {



                access_denied();

            }



            if (!empty($request->occupation_ids)) {



                for ($i = 0; $i < count($request->occupation_ids); $i++) {



                    $occu[] = array(



                        'occupation_id' => $request->occupation_ids[$i],



                        'user_id' => $input['id'],



                        'status' => 1,



                        'created_at' => Carbon::now(),



                        'updated_at' => Carbon::now(),



                    );

                }

            }



            $messages = [



                'required' => 'The :attribute field is required.',



                'unique' => 'The :attribute has already been taken.',



            ];



            $validator = Validator::make($request->all(), [



                'email' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($input['id'])],



                'role_id' => 'required',



            ], $messages);



            if ($validator->fails()) {



                return back()->with('error', $validator->messages()->first());



                // Session::flash('flash_danger', $validator->messages()->first());



                // return redirect()->back()->withInput();



            }



            unset($input['action']);



            unset($input['repeat_password']);



            $id = $input['id'];



            $id = $id;



            if (!empty($input['designation_id'])) {



                $input['login_role_id'] = $input['designation_id'];

            } else {



                $input['login_role_id'] = $input['role_id'];

            }



            $model = User::find($id);

            $firebaseToken = User::whereNotNull('fcm_token')->where('id', $model->id)->pluck('fcm_token')->toArray();



            if ($input['role_id']) {

                if ($model->role_id != $input['role_id']) {

                    $title = 'Admin';

                    if($model->lang == 'english') {
                        $body = 'Admin has assigned you a new role';
                    }else{
                        $body = 'ایڈمن نے آپ کو ایک نیا کردار تفویض کیا ہے';
                    }



                    $data = [

                        'type' => 'new-role',

                        'data_id' => $model->id

                    ];

                    $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);

                }

            }

            if ($input['designation_id']) {

                if ($model->designation_id != $input['designation_id']) {

                    $title = 'Admin';

//                    $body = 'Admin has assigned you a new Designation';


                    if($model->lang == 'english') {
                        $body = 'Admin has assigned you a new Designation';
                    }else{
                        $body = 'ایڈمن نے آپ کو ایک نیا کردار تفویض کیا ہے';
                    }

                    $data = [

                        'type' => 'designation',

                        'data_id' => $model->id

                    ];

                    $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);

                }

            }

            $model->fill($input);



            if ($model->update()) {



                if ($this->hasAddress($model)) {



                    $user_profile = User::find($model->id);



                    $user_profile->update(['is_completed_profile' => 1]);

                }



                if ($request->has('permanent_address_english')) {



                    PermanentAddress::updateOrCreate(['user_id' => $model->id], [



                        'permanent_address_english' => $request->permanent_address_english,



                        'permanent_address_urdu' => $request->permanent_address_urdu,



                        'permanent_address_arabic' => $request->permanent_address_arabic,



                    ]);

                }



                if (!empty($request->title_english)) {



                    UserExperience::where('user_id', $id)->delete();



                    $experience = UserExperience::insert($exp);

                } else {



                    UserExperience::where('user_id', $id)->delete();

                }



                if (!empty($request->institute_english)) {



                    UserEducation::where('user_id', $id)->delete();



                    $education = UserEducation::insert($edu);

                } else {



                    UserEducation::where('user_id', $id)->delete();

                }

            }







            // Update user occupations



            if (!empty($request->occupation_ids) || $request->has('other_profession') && !empty($request->other_profession)) {



                UserOccupation::where('user_id', $input['id'])->delete();



                $user_occupation = UserOccupation::insert($occu);



                $occupation_exists = Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->exists();



                if (!$occupation_exists) {



                    $occupation = new Occupation([



                        'title_english' => $request->other_profession,



                        'title_urdu' => $request->other_profession,



                        'slug' => strtolower(preg_replace('/\s+/', '-', $request->other_profession)),



                        'status' => 1



                    ]);







                    if ($occupation->save()) {



                        $user_other_occupation = new UserOccupation([



                            'occupation_id' => $occupation->id,



                            'user_id' => $input['id'],



                            'status' => 1,



                            'created_at' => now(),



                            'updated_at' => now(),



                        ]);



                        $user_other_occupation->save();

                    }

                } else {



                    $occupation_exists_id = Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->pluck('id')->first();



                    $user_other_occupation = new UserOccupation([



                        'occupation_id' => $occupation_exists_id,



                        'user_id' => $input['id'],



                        'status' => 1,



                        'created_at' => now(),



                        'updated_at' => now(),



                    ]);



                    $user_other_occupation->save();

                }

            } else {



                UserOccupation::where('user_id', $input['id'])->delete();

            }







            return redirect('admin/users')->with('message', 'Data updated Successfully');

        }

    }







    /**



     * Show the specific Users



     */



    public function show(Request $request)



    {



        $user = User::find($request->user_id);



        $html = view('admin.partial.show-user', get_defined_vars())->render();







        return response()->json(['html' => $html, 'status' => 200]);

    }







    /**



     * reoving the Users



     */



    public function destroy($id)



    {



        if (!have_right('Delete-User')) {



            access_denied();

        }







        $data = [];



        $data['row'] = User::destroy($id);







        // delete user notifications when user is deleted



        NotificationUser::where('user_id', $id)->delete();







        return redirect('admin/users')->with('message', 'Data deleted Successfully');

    }







    /**



     * Changing Login Status for the Users



     */



    public function changeUserLoginStatus($id = null)



    {



        $user_data = User::where('id', $id)->first();



        $user_mail = $user_data->email;



        $welcome_mail_status = 1;



        if ($_GET['status'] == 1 && $user_data->welcome_mail_status == 0) {



            $details = [



                'subject' => " Account Approval",



                'user_name' => $user_data->user_name,



                'content' => "<p>The admin has approved your account, and you are successfully registered on the Mustafai Portal.</p>",



                'links' => "<a href='" . url('login') . "'>Click Here</a> to log in.",



            ];







            try {



                \Mail::to($user_mail)->send(new \App\Mail\CommonMail($details));



                $welcome_mail_status = 1;

            } catch (Exception $e) {



                // Never reached



                $welcome_mail_status = 0;

            }

        }







        $update_user = User::where('id', $id)->update([



            'is_aproved' => $_GET['status'],



            'status' => $_GET['status'],



            'welcome_mail_status' => $welcome_mail_status,



        ]);







        if ($update_user) {



            echo true;



            exit();

        }

    }







    /**



     * Getting subscription details of the Users



     */



    public function getSubscriptionDetails(Request $request)



    {



        $input = $request->all();



        $userID = $input['usrID'];







        if ($userID) {



            $user = User::find($userID);



            if (!empty($user)) {



                $data = [];







                $data['amount'] = $user->subscription_amount;



                $data['cycle'] = $user->subscription_amount_cycles;







                echo json_encode($data);



                die();

            }

        }

    }







    /**



     * Getting and saves the subscription details of the Users



     */



    public function getSubscriptionDetailsSave(Request $request)



    {



        $input = $request->all();



        $userID = $input['userID'];







        $response = [];



        if ($userID) {



            $user = User::find($userID);



            if (!empty($user)) {



                $data = [];







                $data['subscription_amount'] = $input['subscription_amount'];



                $data['subscription_amount_cycles'] = $input['subscription_amount_cycles'];







                if ($user->update($data)) {



                    $response['status'] = 1;



                    $response['message'] = 'Subscription amount successfully update.';

                } else {



                    $response['status'] = 0;



                    $response['message'] = 'Something went wrong.';

                }

            } else {



                $response['status'] = 0;



                $response['message'] = 'User Not Found.';

            }

        } else {



            $response['status'] = 0;



            $response['message'] = 'You data is not correct.';

        }







        echo json_encode($response);



        die();

    }







    /**



     * filter the occupation for the user



     */



    public function filterOccupation(Request $request)



    {



        if ($request->ajax()) {



            if ($request->has('occupation_id') && $request->occupation_id != null) {



                $occupation = Occupation::where(['status' => 1, 'parent_id' => $request->occupation_id])->get();



                return response()->json(['status' => 200, 'data' => $occupation, 'total' => $occupation->count()]);

            }

        }

    }







    /**



     * Getting subscription of the Users



     */



    public function getSubscription(Request $request, $id)



    {



        if (!have_right('View-user-Subscription')) {



            access_denied();

        }



        $data = [];



        $data['id'] = $id;



        if ($request->ajax()) {



            $db_record = UserSubscription::with('user_account')->orderBy('id', 'DESC')->where('user_id', $id)->where('is_trial_period', 0)->get();



            $datatable = Datatables::of($db_record);



            $datatable = $datatable->addIndexColumn();







            $datatable = $datatable->addColumn('receipt_status', function ($row) {



                $reciept_status = '';



                if ($row->reciept == null && $row->is_paid == 0) {



                    $reciept_status = '<span class="badge badge-danger">Pending</span>';

                } else if ($row->reciept != null && $row->is_paid == 0 || $row->is_paid == 2) {



                    $reciept_status = '<span class="badge badge-info">Draft</span>&nbsp;&nbsp;&nbsp;';



                    $reciept_status .= '<a href="' . getS3File($row->reciept) . '" download style="cursor: pointer;">Download</a>';

                } else if ($row->reciept != null && $row->is_paid == 1) {



                    $reciept_status = '<span class="badge badge-success">Paid</span>&nbsp;&nbsp;&nbsp;';



                    $reciept_status .= '<a href="' . getS3File($row->reciept) . '" download style="cursor: pointer;">Download</a>';

                }



                return $reciept_status;

            });



            $datatable = $datatable->addColumn('receipt_statusHidden', function ($row) {



                $reciept_status = '';



                if ($row->reciept == null && $row->is_paid == 0) {



                    $reciept_status = 'Pending';

                } else if ($row->reciept != null && $row->is_paid == 0 || $row->is_paid == 2) {



                    $reciept_status = 'Draft';

                } else if ($row->reciept != null && $row->is_paid == 1) {



                    $reciept_status = 'Paid';

                }



                return $reciept_status;

            });







            $datatable = $datatable->addColumn('subscription_status', function ($row) {



                $subscription_status = '';



                $time = time();



                if ($time > $row->subscription_start_date && $time < $row->subscription_end_date && $row->is_paid == 0 || $row->is_paid == 2) {



                    $subscription_status = '<span class="badge badge-danger">Pending</span>';

                } else if ($time > $row->subscription_start_date && $time < $row->subscription_end_date && $row->is_paid == 1) {



                    $subscription_status = '<span class="badge badge-success">Active</span>';

                } else if ($time > $row->subscription_end_date && $row->is_paid == 0 || $row->is_paid == 2) {



                    $subscription_status = '<span class="badge badge-danger">Pending</span>';

                } else if ($time > $row->subscription_end_date && $row->is_paid == 1) {



                    $subscription_status = '<span class="badge badge-success">Completed</span>';

                }



                return $subscription_status;

            });



            $datatable = $datatable->addColumn('subscription_statusHidden', function ($row) {



                $subscription_status = '';



                $time = time();



                if ($time > $row->subscription_start_date && $time < $row->subscription_end_date && $row->is_paid == 0 || $row->is_paid == 2) {



                    $subscription_status = 'Pending';

                } else if ($time > $row->subscription_start_date && $time < $row->subscription_end_date && $row->is_paid == 1) {



                    $subscription_status = 'Active';

                } else if ($time > $row->subscription_end_date && $row->is_paid == 0 || $row->is_paid == 2) {



                    $subscription_status = 'Pending';

                } else if ($time > $row->subscription_end_date && $row->is_paid == 1) {



                    $subscription_status = 'Completed';

                }



                return $subscription_status;

            });



            $datatable = $datatable->addColumn('subscription_start_date', function ($row) {

                $start_date = '';

                if ($row->subscription_start_date) {

                    $start_date = date("d M Y", ($row->subscription_start_date));

                }



                return $start_date;

            });



            $datatable = $datatable->addColumn('subscription_start_dateHidden', function ($row) {



                $start_date = '';

                if ($row->subscription_start_date) {

                    $start_date = date("d M Y", ($row->subscription_start_date));

                }



                return $start_date;

            });



            $datatable = $datatable->addColumn('subscription_end_date', function ($row) {

                $end_date = '';

                if ($row->subscription_end_date) {

                    $end_date = date("d M Y", ($row->subscription_end_date));

                }



                return $end_date;

            });



            $datatable = $datatable->addColumn('subscription_end_dateHidden', function ($row) {



                $end_date = '';

                if ($row->subscription_end_date) {

                    $end_date = date("d M Y", ($row->subscription_end_date));

                }



                return $end_date;

            });



            $datatable = $datatable->addColumn('subscription_moths', function ($row) {

                if ($row->subscription_start_date) {

                    $start_date = Carbon::createFromTimestamp($row->subscription_start_date);

                }



                if ($row->subscription_end_date) {

                    $end_date = Carbon::createFromTimestamp($row->subscription_end_date);

                }

                $months = '';



                // Ensure both dates are set

                if ($start_date && $end_date) {

                    // Get the first month name

                    $firstMonth = $start_date->format('F');



                    // Get the last month name

                    $lastMonth = $end_date->format('F');



                    $months = $firstMonth . ' to ' . $lastMonth;

                }





                return $months;

            });



            $datatable = $datatable->addColumn('subscription_mothsHidden', function ($row) {

                if ($row->subscription_start_date) {

                    $start_date = Carbon::createFromTimestamp($row->subscription_start_date);

                }



                if ($row->subscription_end_date) {

                    $end_date = Carbon::createFromTimestamp($row->subscription_end_date);

                }

                $months = '';



                // Ensure both dates are set

                if ($start_date && $end_date) {

                    // Get the first month name

                    $firstMonth = $start_date->format('F');



                    // Get the last month name

                    $lastMonth = $end_date->format('F');



                    $months = $firstMonth . ' to ' . $lastMonth;

                }



                return $months;

            });



            $datatable = $datatable->addColumn('subscription_method', function ($row) {

                $bank_name = '';

                if ($row->user_account) {

                    $bank_name = $row->user_account->bank_name;

                }

                return $bank_name;

            });



            $datatable = $datatable->addColumn('subscription_methodHidden', function ($row) {



                $bank_name = '';

                if ($row->user_account) {

                    $bank_name = $row->user_account->bank_name;

                }

                return $bank_name;

            });







            $datatable = $datatable->editColumn('status', function ($row) {



                $status = '<span class="badge badge-danger">Un-Paid</span>';



                if ($row->is_paid == 1) {



                    $status = '<span class="badge badge-success">Paid</span>';

                } else if ($row->is_paid == 2) {



                    $status = '<span class="badge badge-info">Skipped</span>';

                }



                return $status;

            });



            $datatable = $datatable->editColumn('statusHidden', function ($row) {



                $status = 'Un-Paid';



                if ($row->is_paid == 1) {



                    $status = 'Paid';

                } else if ($row->is_paid == 2) {



                    $status = 'Skipped';

                }



                return $status;

            });



            $datatable = $datatable->editColumn('action', function ($row) {



                $action = '';



                if (have_right('Subscription-Update-Status')) {



                    $action = '<select class="" name="status" data-id="' . hashEncode($row->id) . '" onChange="updateSubscriptionStatus($(this))">



                        <option value="0" ' . (($row->is_paid == 0) ? 'selected' : '') . '>Pending</option>



                        <option value="1" ' . (($row->is_paid == 1) ? 'selected' : '') . '>Paid</option>



                        <option value="2" ' . (($row->is_paid == 2) ? 'selected' : '') . '>Skip</option>

                        <optgroup label="──────────"></optgroup>

                        <option value="12" ' . (($row->subscription_plan == 12) ? 'selected' : '') . '>Paid-2</option>

                        <option value="13" ' . (($row->subscription_plan == 13) ? 'selected' : '') . '>Paid-3</option>

                        <option value="14" ' . (($row->subscription_plan == 14) ? 'selected' : '') . '>Paid-4</option>

                        <option value="15" ' . (($row->subscription_plan == 15) ? 'selected' : '') . '>Paid-5</option>

                        <option value="16" ' . (($row->subscription_plan == 16) ? 'selected' : '') . '>Paid-6</option>

                        <option value="17" ' . (($row->subscription_plan == 17) ? 'selected' : '') . '>Paid-7</option>

                        <option value="18" ' . (($row->subscription_plan == 18) ? 'selected' : '') . '>Paid-8</option>

                        <option value="19" ' . (($row->subscription_plan == 19) ? 'selected' : '') . '>Paid-9</option>

                        <option value="20" ' . (($row->subscription_plan == 20) ? 'selected' : '') . '>Paid-10</option>

                        <option value="21" ' . (($row->subscription_plan == 21) ? 'selected' : '') . '>Paid-11</option>

                        <option value="22" ' . (($row->subscription_plan == 22) ? 'selected' : '') . '>Paid-12</option>



                    </select>';

                }



                if (have_right('View-Subscription')) {



                    $action .= '&nbsp;<a data-toggle="modal" data-target="#showSubscriptionModal" class="btn btn-primary show_subscription" href="javascript:void(0)" data-subscription-id="' . $row->id . '" title="Show Subscription"><i class="far fa-eye"></i></a>';

                    $action .= '&nbsp;<a data-toggle="modal" data-target="#editSubscriptionModal" class="btn btn-primary edit_subscription" href="javascript:void(0)" data-subscription-id="' . $row->id . '" title="Edit Subscription"><i class="far fa-edit"></i></a>';

                }



                return $action;

            });







            $datatable = $datatable->rawColumns([

                'receipt_status',

                'receipt_statusHidden',

                'subscription_status',

                'subscription_statusHidden',

                'status',

                'statusHidden',

                'subscription_start_date',

                'subscription_start_dateHidden',

                'subscription_end_date',

                'subscription_end_dateHidden',

                'action'

            ]);



            $datatable = $datatable->make(true);



            return $datatable;

        }



        return view('admin.users.user-subscription', $data);

    }







    /**



     * updating subscription staus of the Users



     */



    public function subscriptionStatusUpdate(Request $request)

{

    $input = $request->all();

    $response = [];



    if (isset($input['subscription_id'])) {

        $input['subscription_id'] = hashDecode($input['subscription_id']);



        if ($input['subscription_id']) {

            $userSubscription = UserSubscription::find($input['subscription_id']);



            if ($input['status'] == 1 && empty($userSubscription->reciept)) {

                $response['status'] = 0;

                $response['message'] = 'User Receipt is Empty!';

            } else {

                if (!empty($userSubscription)) {

                    $subscriptionValue = 11;

                    $months = 1;



                    if ($input['status'] >= 12 && $input['status'] <= 22) {

                        $subscriptionValue = $input['status'];

                        $months = $input['status'] - 10;

                        $input['status'] = 1;

                    }



                    $currentDate = Carbon::now();

                    $dateAfterMonths = $currentDate->copy()->addMonths($months)->subDay();

                    $daysAfterMonths = $currentDate->diffInDays($dateAfterMonths);

                    $end_date = strtotime($dateAfterMonths->toDateString());



                    $isUpdate = $userSubscription->update([

                        'is_paid' => $input['status'],

                        'subscription_plan' => $subscriptionValue,

                        'no_of_days' => $daysAfterMonths,

                        'subscription_end_date' => $end_date

                    ]);



                    // Notification Logic

                    if (!empty($isUpdate)) {

                        $checkAll = UserSubscription::where(['user_id' => $userSubscription->user_id, 'is_paid' => 0])->count();

                        $user = User::find($userSubscription->user_id);


                        if ($input['status'] == 1) { // Paid

                            User::where(['id' => $userSubscription->user_id])->update(['subscription_fallback_role_id' => 0]);

                            $title = 'Admin';

//                            $body = 'Admin has approved your payment.';

                            if($user->lang == 'english') {
                                $body = 'Admin has approved your payment.';
                            }else{
                                $body = 'ایڈمن نے آپ کی ادائیگی منظور کر لی ہے۔';
                            }

                        } elseif ($input['status'] == 2) { // Skip

                            User::where(['id' => $userSubscription->user_id])->update(['subscription_fallback_role_id' => null]);

                            $title = 'Admin';

//                            $body = 'Admin has granted you releif for subscription.';

                            if($user->lang == 'english') {
                                $body = 'Admin has granted you releif for subscription.';
                            }else{
                                $body = 'ایڈمن نے آپ کو سبسکرپشن میں رعایت دی ہے۔';
                            }

                        } else { // Pending

                            User::where(['id' => $userSubscription->user_id])->update(['subscription_fallback_role_id' => 19]);

                            $title = 'Admin';

//                            $body = 'Your payment is still pending.';

                            if($user->lang == 'english') {
                                $body = 'Your payment is still pending.';
                            }else{
                                $body = 'آپ کی ادائیگی ابھی تک زیرِ التواء ہے۔';
                            }
                        }



                        // Send Notification

                        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $userSubscription->user_id)->pluck('fcm_token')->toArray();

                        $data = [

                            'type' => 'subscription-update',

                            'data_id' => $userSubscription->user_id

                        ];

                        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);



                        $response['status'] = 1;

                        $response['message'] = 'Status Updated.';

                    } else {

                        $response['status'] = 0;

                        $response['message'] = 'Something went wrong.';

                    }

                } else {

                    $response['status'] = 0;

                    $response['message'] = 'Subscription Not Found.';

                }

            }

        } else {

            $response['status'] = 0;

            $response['message'] = 'Data is not correct.';

        }

    } else {

        $response['status'] = 0;

        $response['message'] = 'Subscription ID is required.';

    }



    echo json_encode($response);

    exit();

}



    /**



     * updating subscription receipt of the Users



     */



    public function subscriptionReceiptUpdate(Request $request)



    {



        $input = $request->all();



        if ($input['subscription_id']) {



            $userSubscription = UserSubscription::find($input['subscription_id']);



            if ($userSubscription) {



                $path = ImageOptimize::improve($request->reciept, 'user-subscription-reciepts');

                $subscription = UserSubscription::find($request->subscription_id);

                $subscription->update([

                    'reciept' => $path,

                ]);

            }

        }



        return redirect()->back();

    }







    /**



     * show specific subscription of the Users



     */



    public function showSubscription(Request $request)



    {



        $subscription = UserSubscription::find($request->subscription_Id);



        $html = view('admin.partial.show-subscription', get_defined_vars())->render();







        return response()->json(['html' => $html, 'status' => 200]);

    }







    /**



     * fetching users occupation



     */



    public function userOccupation(Request $request)



    {



        $occupations = Occupation::with(['subProfession'])->where('parent_id', '=', null)->where('status', 1)->get();



        $userOccupationIds = UserOccupation::where('user_id', $request->user_id)->pluck('occupation_id')->toArray();



        $user_id = $request->user_id;







        $html = (string)view('admin.partial.user-occupation', get_defined_vars())->render();



        return response()->json(['html' => $html, 'status' => 200]);

    }







    /**



     * Validating jquery



     */



    public function validateJquery(Request $request)



    {



        // return "ok";



        $table = $request->table;



        $user = DB::table($table);



        if (isset($request->email) && !empty($request->id)) {



            $user->where('email', $request->email)->where('id', '!=', $request->id);

        } else {



            $user->where('email', $request->email);

        }







        if ($user->count() > 0) {



            return response()->json('Email is already exist');



            exit;

        } else {



            return response()->json(true);







            exit;

        }

    }







    /**



     * Validating phone number



     */



    public function validatePhone(Request $request)



    {



        // return "ok";



        $table = $request->table;



        $user = DB::table($table);



        if (isset($request->phone_number) && !empty($request->id)) {



            $user->where('phone_number', $request->phone_number)->where('id', '!=', $request->id);

        } else {



            $user->where('phone_number', $request->phone_number);

        }







        if ($user->count() > 0) {



            return response()->json('Phone Number is already exist');



            exit;

        } else {



            return response()->json(true);







            exit;

        }

    }







    /**



     *check is_complete_profile



     */



    public function hasAddress($user): bool



    {



        if ($user->country_id == null || $user->province_id == null || $user->division_id == null || $user->district_id == null || $user->tehsil_id == null) {



            return false;

        }



        return true;

    }

}

