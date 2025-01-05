<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Http\Resources\EducationResource;

use App\Http\Resources\ExperienceResource;

use App\Http\Resources\ProfileResource;

use App\Http\Resources\SkillsResource;

use App\Models\User;

use App\Models\User\UserExperience;

use App\Models\Chat\Contact;

use App\Models\Admin\City;

use App\Models\Admin\Country;

use App\Models\Admin\District;

use App\Models\Admin\Division;

use App\Models\Admin\Province;

use App\Models\Admin\Tehsil;

use App\Models\Admin\Zone;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

use App\Helper\UserFee;

use App\Models\Admin\Notification;

use App\Models\Admin\Admin;

use PDF;

use Hash;

use Illuminate\Support\Facades\Storage;

use Dompdf\Dompdf;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\App;

use Mpdf\Mpdf;

use App\Models\Admin\Occupation;

use App\Models\Admin\NotificationUser;



class ProfileController extends Controller

{

    /**

     *get user address api

    */

    public function index(Request $request, $id = null)

    {

        $permanentAddress = auth()->user()->permanentAddress;



        $title_query = getQuery(App::getLocale(), ['title']);

        $title_query[] = 'id';

        $name_query = getQuery(App::getLocale(), ['name']);

        $name_query[] = 'id';

        $countries = Country::select($name_query)->where('status', 1)->get();

        $provinces = Province::select($name_query)->where('status', 1)->get();

        $divisions = Division::select($name_query)->where('status', 1)->get();

        $districts = District::select($name_query)->where('status', 1)->get();

        $tehsils = Tehsil::select($name_query)->where('status', 1)->get();

        $zones = Zone::select($name_query)->where('status', 1)->get();

        $cities = City::select($name_query)->where('status', 1)->get();



        $cols =  array_merge(getQuery($request->lang, ['title']), ['id','parent_id','status']);

        $child_col =  array_merge(getQuery($request->lang, ['title']), ['id','parent_id','status']);

        // dd($child_col);



        $occupations = Occupation::select($cols)->with(['subProfession'=>function ($q) use($child_col){

            $q->select($child_col)->get();

        },])->where('status',1)->get();

        $data=[];

        $authUser = auth()->user();

        $data['countries']=$countries;

        $data['provinces']=$provinces;

        $data['divisions']=$divisions;

        $data['districts']=$districts;

        $data['tehsils']=$tehsils;

        $data['zones']=$zones;

        $data['cities']=$cities;

        $data['authUser']=$authUser;

        $data['occupations']=$occupations;



        return response()->json([

            'status' => 1,

            'message' => 'Record',

            'data'=>$data]);

    }



    /**

     *update user address api

    */

    public function updateAddress(Request $request)

    {

        $union_council_id=UserFee::addUnionCouncil($request->union_council,$request->tehsil_id,$request->zone_id);

        $union_council_permanent_id=UserFee::addUnionCouncil($request->union_council_permanent,$request->tehsil_id_permanent,$request->zone_id_permanent);

        $request["union_council_id"]=$union_council_id;

        $request["union_council_permanent"]=$union_council_permanent_id;

        if($request->same_address!=1){

            $request["same_address"]='0';

        }

        User::where('id', auth()->user()->id)

            ->update($request->except(['_token','permanent_address_english', 'permanent_address_urdu', 'permanent_address_arabic', 'country_id_permanent', 'province_id_permanent',

                'division_id_permanent', 'city_id_permanent', 'district_id_permanent', 'tehsil_id_permanent', 'zone_id_permanent', 'postcode_permanent','union_council','union_council_permanent'

                ]));

        // dd($request->all());

        if ($request->has('same_address') && $request->same_address==1) {

            User\PermanentAddress::query()->updateOrCreate(

                ['user_id' => auth()->user()->id],

                [

                    'permanent_address_english' => $request->address_english,

                    'permanent_address_urdu' => $request->address_urdu,

                    'permanent_address_arabic' => $request->address_arabic,

                    'country_id_permanent' => $request->country_id,

                    'province_id_permanent' => $request->province_id,

                    'division_id_permanent' => $request->division_id,

                    'city_id_permanent' => $request->city_id,

                    'district_id_permanent' => $request->district_id,

                    'tehsil_id_permanent' => $request->tehsil_id,

                    'zone_id_permanent' => $request->zone_id,

                    'union_council_permanent' => $request->union_council_id,

                    'postcode_permanent' => $request->postcode,

                ]

            );

        }

        else{



            User\PermanentAddress::query()->updateOrCreate(

                ['user_id' => auth()->user()->id],

                [

                    'permanent_address_english' => $request->permanent_address_english,

                    'permanent_address_urdu' => $request->permanent_address_urdu,

                    'permanent_address_arabic' => $request->permanent_address_arabic,

                    'country_id_permanent' => $request->country_id_permanent,

                    'province_id_permanent' => $request->province_id_permanent,

                    'division_id_permanent' => $request->division_id_permanent,

                    'city_id_permanent' => $request->city_id_permanent,

                    'district_id_permanent' => $request->district_id_permanent,

                    'tehsil_id_permanent' => $request->tehsil_id_permanent,

                    'zone_id_permanent' => $request->zone_id_permanent,

                    'union_council_permanent' => $request->union_council_permanent,

                    'postcode_permanent' => $request->postcode_permanent,

                ]

            );



        }



        if(auth()->user()->is_completed_profile!=1){

            $user_profile = User::find(auth()->user()->id);

            $user_profile->update(['is_completed_profile' => 1]);

            $user = auth()->user();

            // send notification

            $notification = Notification::create([

                'title' => $user->user_name . ' has completed his profile',

                'title_english' => $user->user_name . 'has completed his profile',

                'title_urdu' => (($user->user_name_urdu != '' || $user->user_name_urdu != null) ? $user->user_name_urdu : $user->user_name) . ' نے اپنا پروفائل مکمل کر لیا ہے ',

                'title_arabic' => (($user->user_name_arabic != '' || $user->user_name_arabic != null) ? $user->user_name_arabic : $user->user_name) . 'أكمل ملفه الشخصي ',

                'link' => url('admin/users/' . $user->id . '/edit'),

                'module_id'=> 3,

                'right_id'=>11,

                'ip'=>request()->ip()

            ]);

            $admin = Admin::first();



            $admin->notifications()->attach($notification->id);

        }

        (!empty(User::find(Auth::id())->donor) ? User::find(Auth::id())->donor->update(['city_id' => $request->city_id]) : '');

        $query = array_merge(getQuery(App::getLocale(), ['address']), ['address_english', 'address_urdu', 'address_arabic', 'cnic', 'id', 'postcode']);



        $user = User::select($query)->with('permanentAddress')->where('id',Auth::id())->first();

        $userAddressData = $user;



        $user = $user->address ?? availableField($user->address, $user->address_english, $user->address_urdu, $user->address_arabic);

        return response()->json(['status' => 200, 'data' => $user, 'userAddressData' => $userAddressData]);

    }

    /**

     *get user profile api

    */

    public function getProfile(Request $request)

    {

        if (!$request->lang) {

            $request['lang'] = 'english';

        }

        $query = array_merge(getQuery($request->lang, ['user_name','about','address','tagline']), ['id','full_name','user_name as user_orignal_name','user_name_english','user_name_urdu','blood_group_english','address_english','address_urdu', 'occupation_id','is_public', 'profile_image','resume', 'banner', 'phone_number', 'country_code_id','email', 'skills_english', 'skills_urdu', 'skills_arabic','cnic','login_role_id','subscription_fallback_role_id','is_completed_profile','country_id','province_id','division_id','city_id','district_id','tehsil_id','zone_id','tehsil_id','union_council_id','postcode','tagline_english','tagline_urdu','about_english','about_urdu','fcm_token', 'lang']);

        $user = User::select($query)->where('id',$request->user_id)->first();

        if($user->id!=auth()->user()->id && $user->is_public == 0 ){

            return response()->json([

                'status'=>0,

                'message'=>'This user profile is private'

            ]);

        }





        if (! $user) return response()->json(['status' => 0, 'message' => 'no user found']);



        return new ProfileResource($user);

    }

    /**

     *Add user experience api

    */

    public function addExperience(Request $request)

    {



        $rules = [

            'title_english' => 'nullable',

            'title_urdu' => 'nullable',

            'title_arabic' => 'nullable',



            'experience_company_english' => 'nullable',

            'experience_company_urdu' => 'nullable',

            'experience_company_arabic' => 'nullable',



            'experience_location_english' => 'nullable',

            'experience_location_urdu' => 'nullable',

            'experience_location_arabic' => 'nullable',



            'user_id' => 'required',

            'is_currently_working' => 'required',

            'experience_start_date' => 'required',

            'experience_end_date' => Rule::requiredIf($request->is_currently_working == 0),

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

            $validated = $validator->validated();

        if($request['actionType'] == 'add'){



            // Retrieve the validated input...

            // dd($validated);

            UserExperience::create($validated);



            // return response

            $user = User::query()->findOrFail($request->user_id);

            $experience=ExperienceResource::collection($user->experience()->latest()->get());

            return response()->json(['status'=>  1, 'message' => 'Experience added successfully', 'experience' => $experience]);

        }

        else{

            UserExperience::find($request->experience_id)->update($validated);

            // User::find($request->user_id)->experience()->where('id', $request['id'])->update($request);



            // return response

            $user = User::query()->findOrFail($request->user_id);

            $experience=ExperienceResource::collection($user->experience()->latest()->get());

            return response()->json(['status'=>  1, 'message' => 'Experience updated successfully', 'experience' => $experience]);

        }



    }

    /**

     *Add update and delete user education api

    */

    public function addEducation(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'institute_english' =>'nullable',

            'institute_urdu' =>'nullable',

            'degree_name_english' => 'nullable',

            'degree_name_urdu' => 'nullable',

            'start_date' => Rule::requiredIf($request->actionType === 'edit' || $request->actionType === 'add'),

            'end_date' => Rule::requiredIf($request->actionType === 'edit' || $request->actionType === 'add'),

            'actionType' => 'required',

            'education_id' => Rule::requiredIf($request->actionType === 'edit' || $request->actionType === 'delete')

        ]);



        if ($validator->fails()) {

            return response()->json([

                'status' => 0,

                'message' => 'validation fails',

                'data' => $validator->errors()->toArray()

            ]);

        }



        // Retrieve the validated input...

        $validated = $validator->validated();



        unset($validated['education_id']);

        unset($validated['actionType']);

        unset($validated['user_id']);



        $user = User::query()->findOrFail($request->user_id);



        if ($request->actionType == 'add') {

            $education = $user->education()->create($validated);

            $user = User::query()->findOrFail($request->user_id);

            $education=EducationResource::collection($user->education()->latest()->get());

            return response()->json(['status'=>  1, 'message' => 'created', 'education' => $education]);

        }



        if ($request->actionType == 'edit') {

            $user->education()->where('id', $request->education_id)->where('user_id', $request->user_id)->update($validated);

            // $education = UserEducation::find($request->education_id);

            $user = User::query()->findOrFail($request->user_id);

            $education=EducationResource::collection($user->education()->latest()->get());



            return response()->json(['status'=>  1, 'message' => 'edited', 'education' => $education]);

        }



        if ($request->actionType == 'delete') {

            $user->education()->where('id', $request->education_id)->where('user_id', $request->user_id)->delete();



            return response()->json(['status'=>  1, 'message' => 'successfully deleted']);

        }



        return response()->json(['status' => 0, 'message' => 'Something goes wrong!']);

    }

    /**

     *Update user profile api

    */

    public function updateProfile(Request $request)

    {

        $rules = [

            'user_id' => 'required',

            'banner' => 'nullable',

            'profile_image' => 'nullable',

            'address_english' => 'nullable',

            'blood_group_english' => 'nullable',

            'phone_number' => 'required',

            'country_code_id' => 'required',

            'email' => 'required',

            'about_english' => 'nullable',

            'about_urdu' => 'nullable',

            'tagline_english' => 'nullable',

            'user_name_english' => 'nullable',

            'user_name_urdu' => 'nullable',

            'user_name_arabic' => 'nullable',

            'tagline_english' => 'nullable',

            'tagline_urdu' => 'nullable',

            'full_name' => 'nullable',

            'occupation_id' => 'nullable',

            'cnic' =>           'nullable',

            'lang' =>           'nullable',

            'user_name' => [

                Rule::unique('users')->ignore($request->user_id),

            ],

            'is_public' => Rule::in([0, 1]),

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



        // Retrieve the validated input...

        $validated = $validator->validated();



        if ($request->hasFile('banner')) {

            if (!empty(auth()->user()->banner) && auth()->user()->banner!= 'images/cover-image.png') {

                deleteS3File(auth()->user()->banner);

            }

            $path =  uploadS3File($request , "profile-banner" ,"banner","banner",$filename = null);

            $validated['banner'] = $path;



        }



        if ($request->hasFile('profile_image')) {

            if (!empty(auth()->user()->profile_image) && auth()->user()->profile_image!='images/avatar.png') {

                deleteS3File(auth()->user()->profile_image);

            }

            $path2 =  uploadS3File($request , "profile-images" ,"profile_image","profile_image",$filename = null);

            $validated['profile_image'] = $path2;



        }



        unset($validated['user_id']);

        User::where('id', $request->user_id)->update($validated);

        // $query = getQuery($request->lang, ['user_name']);

        if (!$request->lang) {

            $request['lang'] = 'english';

        }

        $query = array_merge(getQuery($request->lang, ['user_name','about','address','tagline']), ['id','full_name','user_name as user_orignal_name','user_name_english','user_name_urdu','blood_group_english','address_english','address_urdu', 'occupation_id','is_public', 'profile_image','resume', 'banner', 'phone_number', 'country_code_id', 'email', 'skills_english', 'skills_urdu', 'skills_arabic','cnic','login_role_id','subscription_fallback_role_id','is_completed_profile','country_id','province_id','division_id','city_id','district_id','tehsil_id','zone_id','tehsil_id','union_council_id','postcode','tagline_english','tagline_urdu','about_english','about_urdu', 'lang']);

        $user = User::select($query)->where('id',$request->user_id)->first();

        if($user->id!=auth()->user()->id && $user->is_public == 0 ){

            return response()->json([

                'status'=>0,

                'message'=>'This user profile is prvate'

            ]);

        }





        if (! $user) return response()->json(['status' => 0, 'message' => 'no user found']);

        return new ProfileResource($user);

    }

    /**

     *Change user password api

    */

    public function changePassword(Request $request)

    {

        if (\Request::isMethod('post')) {

            $data = $_POST;

            // dd($data);

            $profile_pass = User::select('password')->find($request->user_id);

            $hashedPassword =    $profile_pass->password;

            if (!\Hash::check($data['old_password'], $hashedPassword)) {

                return response()->json([

                    'status' => 500,

                    'message' => 'Failed API',

                ]);

            } else {



                if (isset($data['password']) && !empty($data['password'])) {

                    unset($data['old_password']);

                    unset($data['confirm_password']);

                    $data['original_password'] = $data['password'];

                    $data['password'] = Hash::make($data['password']);

                } else {

                    unset($data['password']);

                    unset($data['old_password']);

                    unset($data['confirm_password']);

                }



                $id = $request->user_id;

                $user = User::find($id);

                $user->fill($data);

                $user->save();

                return response()->json([

                    'status' => 1,

                    'message' => 'profile updated',

                ]);

            }

        }

    }



    /**

     *Add and update skills api

    */

    public function addSkills(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'skills_english' => 'nullable',

            'skills_urdu' => 'nullable',

            'actionType' => 'required',

        ]);



        if ($validator->fails()) {

            return response()->json([

                'status' => 0,

                'message' => 'validation fails',

                'data' => $validator->errors()->toArray()

            ]);

        }



        // Retrieve the validated input...

        $validated = $validator->validated();



        unset($validated['actionType']);

        unset($validated['user_id']);



        if ($request->actionType == 'add' || $request->actionType == 'edit') {

            User::query()->where('id', $request->user_id)->update(['skills_english' => $validated['skills_english'],'skills_urdu' => $validated['skills_urdu']]);

            $user = User::query()->findOrFail($request->user_id);

            $skills=new SkillsResource($user);



            return response()->json(['status'=>  1, 'message' => 'success','data'=>$skills]);

        }



        return response()->json(['status' => 0, 'message' => 'Something goes wrong!']);

    }

    /**

     *Add and update skills api

    */

    public function deleteEditoImage($image)

    {

        if (file_exists(public_path($image))) {

            unlink(public_path($image));

        }

    }

    /**

     *Edit store and generate resume pdf api

    */

    public function editResume(Request $request)

    {

        $html = (string) View('user.pdf.resume');

        $footer = '<p style="font-size: 8px; color: #666; text-align:center; margin-top: 0px;">.</p>';

        $mpdf = new Mpdf([ 'margin_left' => 10, 'margin_right' => 10, 'margin_top' => 10, 'margin_bottom' => 10, 'margin_header' => 0, 'margin_footer' => 0,]);

        $mpdf->autoScriptToLang = true;

        $mpdf->autoLangToFont = true;

        $mpdf->SetHTMLFooter($footer);

        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output('', 'S'); // Output the PDF as a string

        // Define the folder and file name within the "resume" folder

        $folder = 'Resume';

        $fileName = $folder . '/' . Auth::user()->user_name . '_' . md5(time()) . '.pdf';

        // Store the PDF on S3 in the specified folder

        $pdfPath=Storage::disk('s3')->put($fileName, $pdfContent);

        if ($pdfPath) {

        $mpdf->Output($pdfPath, 'F');

        $path = $fileName;

        $id = Auth::user()->id;

        $user = User::find($id);

        if (!empty($user->resume)) {

            deleteS3File($user->resume);

        }

        $user->resume = $path;

        $user->update();

        return response()->json(['status' => 1, 'message' => 'Your resume has been created in PDF successfully']);

        }

    }

    /**

     *get location by IP address

    */

    public function getLocation(Request $request){

        $ip = $request->ip();

        $ip = ($ip == '127.0.0.1') ? '182.179.185.30' : $ip;



        $ipdata = @json_decode(file_get_contents(

            "http://ip-api.com/json/" . $ip

        ));



        return response()->json(['status' => 1, 'message' => 'Record','data'=>$ipdata]);



    }

    /**

     *Delete user experience api

    */

    public function destroyExperience(Request $request)

    {

        User::find($request->user_id)->experience()->where('id', $request->id)->delete();

        $user = User::query()->findOrFail($request->user_id);

        $experience=ExperienceResource::collection($user->experience()->latest()->get());

        return response()->json(['status'=>  1, 'message' => 'Record deleted', 'experience' => $experience]);



    }

    /**

     *Delete user account api

    */

    public function destroyUser(Request $request)

    {

        $id=$request->userId;

        if(auth()->user()->id==$id){

            $user = User::destroy($id);

            // delete user notifications when user is deleted

            NotificationUser::where('user_id', $id)->delete();

            return response()->json(['status'=>  1, 'message' => 'Account Deleted Successfully!']);

        }

        else{

            return response()->json(['status'=>  1, 'message' => 'Access Denied!']);

        }



    }

}

