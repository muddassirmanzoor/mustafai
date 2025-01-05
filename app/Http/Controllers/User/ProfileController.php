<?php

namespace App\Http\Controllers\User;

use App\Models\Admin\Notification;
use App\Models\Admin\Admin;
use App\Helper\ImageOptimize;
use App\Http\Controllers\Controller;
use App\Models\Admin\City;
use App\Models\Admin\Country;
use App\Models\Admin\CountryCode;
use App\Models\Admin\District;
use App\Models\Admin\Division;
use App\Models\Admin\Donor;
use App\Models\Admin\Occupation;
use App\Models\Admin\Province;
use App\Models\Admin\Tehsil;
use App\Models\Admin\Zone;
use App\Models\Chat\Contact;
use App\Models\Posts\Post\Post;
use App\Models\User;
use App\Models\User\UserEducation;
use App\Models\User\UserExperience;
use App\Models\User\UserOccupation;
use Hash;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PDF;
// use Storage;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;
use App\Helper\UserFee;
use App\Services\FirebaseNotificationService;
use Mpdf\Mpdf;
use LanguageDetection\Language;

class ProfileController extends Controller
{
    protected $firebaseNotification;

    public function __construct(FirebaseNotificationService $firebaseNotification)
    {
        $this->firebaseNotification = $firebaseNotification;
    }
    /**
     *Getting User Profile
    */
    public function index($id = null)
    {

        if($id == 'search')
        {
            return redirect('user/search?q='.$_GET['q']);
        }

        $friend = null;
        if ($id == null) {
            $id = auth()->user()->id;
        } else {
            $id = hashDecode($id);
            // dd($id);
            if($id == Auth::user()->id)
            {
                return redirect('user/profile');
            }
            $friend = $id;

            $friendStatus = Contact::where([
                'user_1' => auth()->user()->id,
                'user_2' => $friend
            ])->orWhere('user_1', $friend)->where('user_2', auth()->user()->id)->first();
        }
        // get id of user to show profile

        if ($id != auth()->user()->id && $person = User::find($id)->is_public === 0) {
            abort(403, 'profile is private');
        }
        //

        $cols = array_merge(getQuery(App::getLocale(), ['title']), ['id', 'admin_id', 'share_id', 'user_id', 'job_type', 'post_type', 'product_id', 'city', 'hospital', 'address', 'occupation', 'experience', 'skills', 'resume', 'description_english', 'created_at','status']);
        $cityCols =  array_merge(getQuery(App::getLocale(), ['name']), ['id']);

        $posts = Post::select($cols)->where('user_id', $id)
            // ->with(['images', 'comments.user', 'likes', 'user', 'admin', 'citi' => fn($q) => $q->select($cityCols)])
            // ->withCount(['comments', 'likes' => fn ($query) => $query->where('like', 1)])
            ->with(['images', 'comments.user', 'likes', 'user', 'admin', 'citi' => function ($q) use ($cityCols) {
                $q->select($cityCols);
            }])
            ->withCount(['comments', 'likes' => function ($query) {
                $query->where('like', 1);
            }])
            // ->active()
            ->where('user_id', $id)
            ->orWhere('share_id', $id)
//            ->simple()
            ->latest()
            ->get()
            ->each(function ($post) use ($id) {
                $post->likes()->where('user_id', $id)->exists() ? $post->is_like = 1 : $post->is_like = 0;
                $post->shared_user = $post->share_id != null ? User::find($post->share_id) : null;
                $post->lang ='urdu';
                if(!empty($post->title) || isset($post->title)){
                    $ld = new Language(['en', 'ur']);
                    $textD = $ld->detect($post->title);
                    $post->lang = $textD['en'] === 0.0 ? 'urdu' : 'english';
                }
            });

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
        $occupations = Occupation::select($title_query)->where('status', 1)->get();

        $query = array_merge(getQuery(App::getLocale(), ['tagline','address','skills','blood_group','about']),['full_name','user_name', 'profile_image','banner', 'tagline_urdu', 'tagline_arabic', 'tagline_english', 'address_english', 'address_urdu', 'address_arabic', 'blood_group_english', 'blood_group_urdu', 'blood_group_arabic', 'about_english', 'about_urdu', 'about_arabic', 'skills_english', 'skills_urdu', 'skills_arabic', 'user_name_english', 'user_name_urdu', 'user_name_arabic', 'cnic']);
        $query[] = 'id';
        $experience_query =array_merge(getQuery(App::getLocale(), ['title', 'experience_company','experience_location']), ['id','user_id' ,'experience_start_date', 'experience_end_date','is_currently_working','created_at','updated_at', 'title_english', 'title_urdu', 'title_arabic', 'experience_company_english', 'experience_company_urdu', 'experience_company_arabic', 'experience_location_english', 'experience_location_urdu', 'experience_location_arabic']);
        $edu_query =array_merge(getQuery(App::getLocale(), ['institute', 'degree_name']), ['id', 'user_id','start_date', 'end_date','created_at','updated_at', 'institute_english', 'institute_urdu', 'institute_arabic', 'degree_name_urdu', 'degree_name_urdu', 'degree_name_arabic']);
        $user = User::select($query)
        ->with(['experience'=>function ($q) use($experience_query){
            $q->select($experience_query)->get();
        },
        'education'=>function ($q) use($edu_query ){
            $q->select($edu_query)->get($edu_query);
        }])->where('id', $id)->first();

        $authUser = auth()->user();

        return view('user.profile', get_defined_vars());
    }

    /**
     *Update Banner image of user profile
    */
    public function updateBanner(Request $request)
    {
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');

            $path = ImageOptimize::improve($banner, 'profile-banner');

            User::where('id', Auth::id())->update(['banner' => $path]);
        }
        $banner = User::find(Auth::id())->banner;
        $html = '<img src="' . getS3File($banner) . '"  class="img-fluid">';
        echo $html;
    }

    /**
     *Update Banner image of user profile
    */
    public function updateProfileImage(Request $request)
    {
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');

            $path = ImageOptimize::improve($profileImage, 'profile-images');

            User::where('id', Auth::id())->update(['profile_image' => $path]);
        }
        $profile = User::find(Auth::id())->profile_image;
        $html = '<img src="' . getS3File($profile) . '"  class="img-fluid">';
        echo $html;
    }

    /**
     *Update tag line according to the language
    */
    public function updateTagline(Request $request)
    {
        // if ($request->has('tagline_english')) {
            User::where('id', Auth::id())->update(['tagline_english' => $request->tagline_english,'tagline_urdu' => $request->tagline_urdu,'tagline_arabic' => $request->tagline_arabic]);
        // }
        $query = array_merge(getQuery(App::getLocale(), ['tagline']), ['tagline_english', 'tagline_urdu', 'tagline_arabic']);
        $user = User::select($query)->where('id',Auth::id())->first();
        $user = $user->tagline ?? availableField($user->tagline, $user->tagline_english, $user->tagline_urdu, $user->tagline_arabic);
        return response()->json(['status' => 200, 'data' => $user]);
    }

    /**
     *Update user name
    */
    public function updateUserName(Request $request)
    {
        // if ($request->has('user_name_english')) {
            User::where('id', Auth::id())->update(['user_name_english' => $request->user_name_english,'user_name_urdu' => $request->user_name_urdu,'user_name_arabic' => $request->user_name_arabic]);
        // }
        $query = array_merge(getQuery(App::getLocale(), ['user_name']), ['user_name_english', 'user_name_urdu', 'user_name_arabic']);
        // return response()->json(['status' => 200, 'data' => User::find(Auth::id())->user_name]);
        $user = User::select($query)->where('id',Auth::id())->first();

        // $user = $user->user_name ?? availableField($user->user_name, $user->user_name_english, $user->user_name_urdu, $user->user_name_arabic);
        $user = $user->{'user_name_'.app()->getLocale()};

        return response()->json(['status' => 200, 'data' => $user]);
    }

    /**
     *Update user CNIC/ID card number
    */
    public function updateUserCnic(Request $request)
    {
        User::where('id', Auth::id())->update(['cnic' => $request->cnic]);
        $user = User::where('id',Auth::id())->first();
        $user = $user->cnic;
        return response()->json(['status' => 200, 'data' => $user]);
    }

    /**
     *Update user Address
    */
    public function updateAddress(Request $request)
    {
        if ($request->ajax()) {
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
            // $permanentAddress = $request->only([
            //     'permanent_address_english', 'permanent_address_urdu', 'permanent_address_arabic',
            //     'country_id_permanent', 'province_id_permanent', 'division_id_permanent', 'city_id_permanent', 'district_id_permanent', 'tehsil_id_permanent', 'zone_id_permanent',
            //     'postcode_permanent'
            // ]);
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
     *Add Experience of user
    */
    public function addExperience(Request $request)
    {
        if ($request->has('is_currently_working')) {
            $request = $request->except('experience_end_date', '_token');
        } else {
            $request = $request->except('_token');
        }

        auth()->user()->experience()->create($request);
        $data = [];
        $query = array_merge(getQuery(App::getLocale(), ['title','experience_company','experience_location']), ['id', 'experience_start_date','experience_end_date','is_currently_working','created_at','updated_at', 'user_id', 'title_english', 'title_urdu', 'title_arabic', 'experience_company_english', 'experience_company_urdu', 'experience_company_arabic', 'experience_location_english', 'experience_location_urdu', 'experience_location_arabic']);
        $data['experiences'] = UserExperience::select($query)->where('user_id',Auth::id())->get();
        $html = (string) View('user.partials.experience-list-partial', $data);
        echo $html;
    }

    /**
     *Edit Experience of user
    */
    public function editExperience(Request $request)
    {
        if ($request->has('is_currently_working')) {
            $request = $request->except('experience_end_date', '_token');
            $request['experience_end_date'] = null;
        } else {
            $request = $request->except('_token');
            $request['is_currently_working'] = 0;
        }
        auth()->user()->experience()->where('id', $request['id'])->update($request);
        $data = [];
        $query = array_merge(getQuery(App::getLocale(), ['title','experience_company','experience_location']), ['id', 'experience_start_date','experience_end_date','is_currently_working','created_at','updated_at', 'user_id', 'title_english', 'title_urdu', 'title_arabic', 'experience_company_english', 'experience_company_urdu', 'experience_company_arabic', 'experience_location_english', 'experience_location_urdu', 'experience_location_arabic']);
        $data['experiences'] = UserExperience::select($query)->where('user_id',Auth::id())->get();
        $html = (string) View('user.partials.experience-list-partial', $data);
        echo $html;
    }

    /**
     *Get Experience of user
    */
    public function getExperience(Request $request)
    {
        $data = UserExperience::where('id', $request->id)->first();
        $html = View('user.partials.edit-experience-partial', $data);
        echo $html;
    }

    /**
     *Add Education of user
    */
    public function addEducation(Request $request)
    {
        auth()->user()->education()->create($request->except('_token'));
        $data = [];
        $query = array_merge(getQuery(App::getLocale(), ['institute','degree_name']), ['id', 'start_date','end_date','created_at','updated_at', 'user_id', 'institute_english', 'institute_urdu', 'institute_arabic', 'degree_name_urdu', 'degree_name_urdu', 'degree_name_arabic']);
        $data['educations'] = UserEducation::select($query)->where('user_id',Auth::id())->get();
        $html = (string) View('user.partials.education-list-partial', $data);
        echo $html;
    }

    /**
     *get Education of user
    */
    public function getEducation(Request $request)
    {
        $data = UserEducation::where('id', $request->id)->first();
        $html = (string) View('user.partials.edit-education-partial', $data);
        echo $html;
    }

    /**
     *Edit Education of user
    */
    public function editEducation(Request $request)
    {
        auth()->user()->education()->where('id', $request->id)->update($request->except('_token'));
        $data = [];
        $query = array_merge(getQuery(App::getLocale(), ['institute','degree_name']), ['id', 'start_date','end_date','created_at','updated_at', 'user_id', 'institute_english', 'institute_urdu', 'institute_arabic', 'degree_name_english', 'degree_name_urdu', 'degree_name_arabic']);
        $data['educations'] = UserEducation::select($query)->where('user_id',Auth::id())->get();
        $html = (string) View('user.partials.education-list-partial', $data);
        echo $html;
    }

    /**
     *destroy Education of user
    */
    public function destroyEducation(Request $request)
    {
        auth()->user()->education()->where('id', $request->id)->delete();
        $data = [];
        $query = array_merge(getQuery(App::getLocale(), ['institute','degree_name']), ['id', 'start_date','end_date','created_at','updated_at', 'user_id']);
        $data['educations'] = UserEducation::select($query)->where('user_id',Auth::id())->get();
        $html = (string) View('user.partials.education-list-partial', $data);
        echo $html;
    }

    /**
     *Destroy Experience of user
    */
    public function destroyExperience(Request $request)
    {
        auth()->user()->experience()->where('id', $request->id)->delete();
        $data = [];
        $query = array_merge(getQuery(App::getLocale(), ['title','experience_company','experience_location']), ['id', 'experience_start_date','experience_end_date','is_currently_working','created_at','updated_at', 'user_id']);
        $data['experiences'] = UserExperience::select($query)->where('user_id',Auth::id())->get();
        $html = (string) View('user.partials.experience-list-partial', $data);
        echo $html;
    }

    /**
     *Making user visibility public
    */
    public function userProfileVisibility(Request $request)
    {
        User::where('id', Auth::id())->update(['is_public' => $request->visibility]);
        return response()->json(['status' => 200, 'data' => Auth::user()->is_public]);
    }

    /**
     *Getting Occupation
    */
    public function userOccupation(Request $request)
    {
        $cols =  array_merge(getQuery(App::getLocale(), ['title']), ['id','parent_id','status']);
        $child_col =  array_merge(getQuery(App::getLocale(), ['title']), ['id','parent_id','status']);
        $occupations = Occupation::select($cols)->with(['subProfession'=>function ($q) use($child_col){
            $q->select($child_col)->get();
        },])->where('parent_id', '=', null)->where('status',1)->get();
        $userOccupationIds = UserOccupation::where('user_id',$request->user_id)->pluck('occupation_id')->toArray();
        $user_id=$request->user_id;
        $html = view('user.partials.user-occupation', get_defined_vars())->render();
        return response()->json(['html' => $html, 'status' => 200]);
    }

    /**
     *Adding User Occupation
    */
    public function addOccupation(Request $request)
    {
        $occu = [];
        if (!empty($request->ids)) {
            for ($i = 0; $i < count($request->ids); $i++) {
                $occu[] = array(
                    'occupation_id' => $request->ids[$i],
                    'user_id' =>auth()->user()->id,
                    'status'=>1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                );
            }
        }
        if (!empty($request->ids) || $request->has('other_profession') && !empty($request->other_profession)) {
            UserOccupation::where('user_id', auth()->user()->id)->delete();
            $user_occupation = UserOccupation::insert($occu);
            $occupation_exists=Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->exists();
            if(!$occupation_exists){
                $occupation = new Occupation([
                    'title_english' => $request->other_profession,
                    'title_urdu' => $request->other_profession,
                    'slug' => strtolower(preg_replace('/\s+/', '-', $request->other_profession)),
                    'status'=> 1
                ]);

                if($occupation->save()){
                    $user_other_occupation = new UserOccupation([
                        'occupation_id' => $occupation->id,
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $user_other_occupation->save();
                }
            }
            else{
                $occupation_exists_id=Occupation::where('title_english', 'LIKE', "%{$request->other_profession}%")->orWhere('title_urdu', 'LIKE', "%{$request->other_profession}%")->pluck('id')->first();
                $user_other_occupation = new UserOccupation([
                    'occupation_id' => $occupation_exists_id,
                    'user_id' => auth()->user()->id,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $user_other_occupation->save();
            }
        } else {
            UserOccupation::where('user_id', auth()->user()->id)->delete();
        }
        return response()->json(['status' => 200, 'data' => Auth::user()->occupation_id]);
    }

    /**
     *Adding About data
    */
    public function about(Request $request)
    {
        if ($request->isMethod('get')) {
            $about = User::find(Auth::id())->about_english;
            return response()->json(['status' => 200, 'data' => $about]);
        }

        if ($request->isMethod('post')) {
            User::where('id', Auth::id())->update(['about_english' => $request->about_english,'about_urdu' => $request->about_urdu,'about_arabic' => $request->about_arabic]);
            $query = array_merge(getQuery(App::getLocale(), ['about']), ['about_english', 'about_urdu', 'about_arabic']);
            $user =  User::select($query)->where('id',Auth::id())->first();
            $user = $user->about ?? availableField($user->about, $user->about_english, $user->about_urdu, $user->about_arabic);
            return response()->json(['status' => 200, 'data' => $user]);
            // echo $html;
        }
    }

    /**
     *Getting donor data
    */
    public function donor(Request $request)
    {
        if ($request->isMethod('get')) {
            $data = [];
            $data['user'] = User::find(Auth::id());
            //            $data['cities']= District::where('status', 1)->get();
            $data['cities']= City::where('status', 1)->get();
            $html = (string) View('user.partials.user-donor-details', $data);
            echo $html;
        }
        if ($request->isMethod('post')) {
            $input = $request->all();
            if(Auth::check()){

                $input['user_id'] = Auth::id();
            }else{
                unset($input['user_id']);
            }
            $modal = new Donor();
            if (isset($input['image'])) {
                //$imagePath = $this->uploadimage($request);
                $imagePath = uploadS3File($request , "images/donors" ,"image","donor",$filename = null);
                $input['image'] = $imagePath;
            }
            $modal->fill($input);
            $modal->save();
            if(Auth::check()){

                return redirect('user/profile')->with('success', __('app.data-added'));
            }else{
                return redirect('/')->with('success', __('app.data-added'));
            }
        }
    }

    /**
     *Editing Resume of user
    */
    public function editResume(Request $request)
    {
        if ($request->isMethod('get')) {
            $data = [];
            $html = (string) View('user.partials.create-resume-partial', $data);
            echo $html;
        }
        if ($request->isMethod('post')) {
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
                // $this->deleteEditoImage($user->resume);
            }
            $user->resume = $path;
            $user->update();
            return redirect('user/profile')->with('success', __('app.resume-created'));
            }
            // $pdf = app('dompdf.wrapper');
            // $pdf->getDomPDF()->set_option("enable_php", true);
            // $pdf = PDF::loadView('user.pdf.resume')->setPaper('a2', 'potrait');
            // $fileName = Auth::user()->user_name . '_' . md5(time()) . '.pdf';
            // $savlfile = Storage::put('public/pdf/' . $fileName, $pdf->output());

            // if ($savlfile) {
            //     $path = 'storage/pdf/' . $fileName;
            //     $id = Auth::user()->id;
            //     $user = User::find($id);
            //     if (!empty($user->resume)) {

            //         $this->deleteEditoImage($user->resume);
            //     }
            //     $user->resume = $path;
            //     $user->update();
            //     return redirect('user/profile')->with('success', __('app.resume-created'));
            // }
        }
    }

    //___________ resume___________//
    /**
     *Showing Resume
    */
    public function showResume(Request $request)
    {
        return view('user.pdf.resume');

    }

    /**
     *Store and get skills
    */
    public function skills(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->skills_english =$request->skills_english;
            $user->skills_urdu = $request->skills_urdu;
            $user->skills_arabic =$request->skills_arabic;
            $user->update();
            $data = [];
            $query = array_merge(getQuery(App::getLocale(), ['skills']), ['skills_english', 'skills_urdu', 'skills_arabic']);
            $data['skills'] = User::select($query)->where('id',Auth::id())->first();
            // dd($data['skills']);
            $html = (string) View('user.partials.skills-partial', $data);
            echo $html;
        }
    }

    /**
     *Uploading image
    */
    public function uploadimage(Request $request)
    {
        $path = '';
        if ($request->image) {
            $imageName = 'donors' . time() . '.' . $request->image->extension();
            if ($request->image->move(public_path('images/donors'), $imageName)) {
                $path = 'images/donors/' . $imageName;
            }
        }
        return $path;
    }

    /**
     *Unlinking previous image while editing
    */
    public function deleteEditoImage($image)
    {
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }
    }

    /**
     *Updating blood group
    */
    public function updateBlood(Request $request)
    {
        if ($request->isMethod('post')) {
            User::where('id', Auth::id())->update(['blood_group_english' => $request->blood_group_english,'blood_group_urdu' => $request->blood_group_urdu,'blood_group_arabic' => $request->blood_group_arabic]);

            (!empty(User::find(Auth::id())->donor) ? User::find(Auth::id())->donor->update(['blood_group' => $request->blood_group_english]) : '');
            $data = [];
            $query = array_merge(getQuery(App::getLocale(), ['blood_group']), ['blood_group_english', 'blood_group_urdu', 'blood_group_arabic']);
            $user = User::select($query)->where('id',Auth::id())->first();
            $user = $user->blood_group ?? availableField($user->blood_group, $user->blood_group_english, $user->blood_group_urdu, $user->blood_group_arabic);
            return response()->json(['status' => 200, 'data' => $user]);
        }
    }

    public function specificProfile(Request $request)
    {
    }

    /**
     *Upating user records
    */
    public function profileSettings(Request $request)
    {
        if ($request->isMethod('get')) {
            $data = [];
            $data['country_codes'] = CountryCode::orderBy(DB::raw('id = 160'), 'DESC')->get();
            return view('user.profile-settings', $data);
        }
        if (\Request::isMethod('post')) {
            $data = $_POST;
            // dd($data);
            $hashedPassword = Auth::user()->password;
            if (!\Hash::check($data['old_password'], $hashedPassword)) {
                return redirect()->back()->withInput()->with('error', __('app.old-password-wrong'));
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
                $data['country_code_id'] = $data['country_code_idd'];
                if ($request->has('secondary_phone_number')) {
                    auth()->user()->secondaryPhones()->delete();
                    foreach ($request->secondary_phone_number as $key => $value) {
                        if(!empty($value)){

                            auth()->user()->secondaryPhones()->create(['phone_number' => $value, 'country_code_id' => $request->country_code_id[$key]]);
                        }
                        // dd("ok");
                    }
                }else{
                    auth()->user()->secondaryPhones()->delete();

                }


                $id = auth()->user()->id;
                $user = User::find($id);
                $user->fill($data);
                $user->save();
                return redirect()->back()->withInput()->with('success', __('app.update-record-successfully'));
            }
        }
    }

    /**
     *sending frinmed frequest and unfriend action
    */
    public function friendRequest(Request $request)
    {
        if ($request->has('unfriend')) {
            $contact = Contact::where([
                'user_1' => auth()->user()->id,
                'user_2' => $request->user_id
            ])->orWhere('user_1', $request->user_id)->where('user_2', auth()->user()->id)->first();

            if ($contact->exists()) {
                $contact->update(['status' => 2]);
                return redirect()->back()->with('success', __('app.unfriend-successfully'));
            }
        }

        if ($request->has('again_friend')) {
            /*Contact::updateOrCreate(['user_1' => auth()->user()->id, 'user_2' => $request->user_id], [
                'user_1' => auth()->user()->id,
                'user_2' =>$request->user_id,
                'status' => 0
            ]);*/
            $contact = Contact::where([
                'user_1' => auth()->user()->id,
                'user_2' => $request->user_id
            ])->orWhere('user_1', $request->user_id)->where('user_2', auth()->user()->id)->first();

            $contact->update(['user_1' => auth()->user()->id, 'user_2' => $request->user_id, 'status' => 0]);

            //push nottification
            $title=auth()->user()->full_name;
            $user = User::find($request->user_id);

            if($user->lang == 'english') {
                $body=auth()->user()->full_name.' sent you a friend request';
            }else{
                $body = auth()->user()->full_name.' نے آپ کو ایک دوست کی درخواست بھیجی ہے';
            }

            $firebaseToken = User::whereNotNull('fcm_token')->where('id', $request->user_id)->pluck('fcm_token')->toArray();

            $data = [
                'type' => 'send-friend-request',
                'data_id' => $request->user_id
            ];
            $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
            // sendNotificationrToUser($request->user_id,$title,$body,'send-friend-request','user_id',$request->user_id,'key2','val2','key3','val3');
            return redirect()->back()->with('success', __('app.friend-req-sent'));
        }

        Contact::updateOrCreate(['user_1' => auth()->user()->id, 'user_2' => $request->user_id], [
            'user_1' => auth()->user()->id,
            'user_2' =>$request->user_id
        ]);

        //push nottification
        $title=auth()->user()->full_name;
        $user = User::find($request->user_id);

        if($user->lang == 'english') {
            $body=auth()->user()->full_name.' sent you a friend request';
        }else{
            $body = auth()->user()->full_name.' نے آپ کو ایک دوست کی درخواست بھیجی ہے';
        }
        $firebaseToken = User::whereNotNull('fcm_token')->where('id', $request->user_id)->pluck('fcm_token')->toArray();

        $data = [
            'type' => 'send-friend-request',
            'data_id' => $request->user_id
        ];
        $this->firebaseNotification->sendNotification($title, $body, $firebaseToken, $data, 1);
        // sendNotificationrToUser($request->user_id,$title,$body,'send-friend-request','user_id',$request->user_id,'key2','val2','key3','val3');

        return redirect()->back()->with('success', __('app.friend-req-sent'));
    }

    /**
     *Store user address branch
    */
    public function storeBranch(Request $request){
        $branch_exsist=Zone::where('id',$request->option)->orWhere('name_english',$request->option)->orWhere('name_urdu',$request->option)->exists();
        if(!$branch_exsist){
            $branch = new Zone();
            $branch->name_english = $request->option;
            $branch->name_urdu = $request->option;
            $branch->tehsil_id = $request->tehsil_id;
            $branch->status =1;
            $branch->save();
            $branchValue = $branch->id;
            return response()->json(['name' => $request->option, 'value' => $branchValue,'new' => true]);
        }
        else{
            return response()->json(['message' => 'Option already exists.', 'new' => false]);
        }
    }
}
