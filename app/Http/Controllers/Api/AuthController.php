<?php



namespace App\Http\Controllers\APi;



use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

use Illuminate\Validation\Rule;

use App\Models\Admin\Role;

use App\Models\Admin\Cabinet;

use App\Models\Admin\CabinetUser;

use App\Helper\UserFee;

use App\Models\Admin\Admin;

use App\Models\Admin\Notification;



class AuthController extends Controller

{

    /**

     * @param Request $request

     * @return UserResource|\Illuminate\Http\JsonResponse

     */

    public function login(Request $request)

    {

        // validate incoming request

        $validator = $this->validateIncomingRequest($request, 'login');



        if ($validator->fails())

            return response()->json([

            'status' => 0,

            'message' => 'validation fails',

            'data' => $validator->errors()->toArray()

        ]);



        // Retrieve the validated input...

        $validated = $validator->validated();

        // create token and return user

        if (filter_var($validated['user_name'], FILTER_VALIDATE_EMAIL)){

            $validated['email'] = $validated['user_name'];

            unset($validated['user_name']);

        }

        if (Auth::attempt($validated))

        {

            if (isset($request->role_id)) {

                User::where('id', auth()->user()->id)->update(['login_role_id' => $request->role_id]);
                
            } elseif (auth()->user()->designation_id) {

                User::where('id', auth()->user()->id)->update(['login_role_id' => auth()->user()->designation_id]);

            }
            else {
                User::where('id', auth()->user()->id)->update(['login_role_id' => auth()->user()->role_id]);

            }

            if (isset($request->fcm_token)) {

                User::where('id', auth()->user()->id)->update(['fcm_token' => $request->fcm_token]);

            }

            if (isset($request->long) && isset($request->lat)) {

                $ip = $request->ip();

                $ip = ($ip == '127.0.0.1') ? '182.179.185.30' : $ip;

                $url = "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$request->lat}&longitude={$request->long}&localityLanguage=en";

                $ipdat = @json_decode(file_get_contents(

                    $url

                ));



                $city = !empty($ipdat->city) ? $ipdat->city : '';

                $country = !empty($ipdat->countryName) ? $ipdat->countryName : '';

                User::where('id', auth()->user()->id)->update(['location_city' => $city,'location_country' => $country , 'ip_address'=>$ip]);

            }

            else{



                $ip = $request->ip();

                $ip = ($ip == '127.0.0.1') ? '182.179.185.30' : $ip;

                $ipdata = @json_decode(file_get_contents(

                    "http://ip-api.com/json/" . $ip

                ));



                $city = !empty($ipdata->city) ? $ipdata->city : '';

                $country = !empty($ipdata->country) ? $ipdata->country : '';

                User::where('id', auth()->user()->id)->update(['location_city' => $city,'location_country' => $country , 'ip_address'=>$ip]);

            }

            User::where('id', auth()->user()->id)->update(['ip_address'=>$request->ip()]);

            $user = $this->createToken();

            return new UserResource($user);

        }

        if(request()->lang=='english'){

            $errorMessage='Invalid Credentials';

        }

        else{

            $errorMessage='غلط معلومات';

        }

        // if invalid credentials

        return response()->json(['status' => 0, 'message' => $errorMessage]);

    }

    /**

     *register user api

    */

    public function signUp(Request $request)

    {

        // validate incoming request

        $validator = $this->validateIncomingRequest($request, 'signup');



        if ($validator->fails())

            return response()->json([

                'status' => 0,

                'message' => 'validation fails',

                'data' => $validator->errors()->toArray()

            ]);



        // Retrieve the validated input...

        $validated = $validator->validated();

        $validated['user_name_english']=$validated['full_name'];

        $validated['status'] = 1;

        $validated['is_public'] = 1;

        $validated['original_password']= $validated['password'];

        $validated['password'] = bcrypt($validated['password']);

        $user=User::create($validated);

        $userFee = new UserFee();



        $userFee->subscribeUser($user, true, '', false);



        // send notification

        $notification = Notification::create([

            'title' => $user->user_name . ' has registered',

            'title_english' => $user->user_name . ' has registered',

            'title_urdu' => (($user->user_name_urdu != '' || $user->user_name_urdu != null) ? $user->user_name_urdu : $user->user_name) . 'نے رجسٹریشن کرائی ہے ',

            'title_arabic' => (($user->user_name_arabic != '' || $user->user_name_arabic != null) ? $user->user_name_arabic : $user->user_name) . 'تم تسجيله ',

            'link' => url('admin/users/' . $user->id . '/edit'),

            'module_id'=> 3,

            'right_id'=>10,

            'ip'=>request()->ip()

        ]);



        //_____________For  Email Sending_________//

        $details_user = [

            'subject' =>  'Registration form submitted',

            'user_name' =>  $user->user_name,

            'content'  => "<p>Your account registration details and request for account approval are sent to the admin.</p><p>Please wait until the admin approves your account.</p>",

            'links'    =>  "<a href='" . url('/') . "'>Click Here</a> To Go Mustfai Portal",

        ];

        $details_admin = [

            'subject' =>  "New User Registered",

            'user_name' =>  "Super Admin",

            'content'  => "<p>A new user named " . $user->user_name . " has just registered on the Mustafai Portal.</p>",

            'links'    =>  "<a href='" . url('/admin/users') . "'>Click here</a> to approve/disapprove the account.",

        ];

        // $adminEmail = Admin::find(1)->value('email');

        $adminEmail = settingValue('emailForNotification');

        // sendEmail($user->email, $details_user);

        saveEmail($user->email, $details_user);

        // sendEmail($adminEmail, $details_admin);

        saveEmail($adminEmail, $details_admin);



        $admin = Admin::first();



        $admin->notifications()->attach($notification->id);



        if(request()->lang=='english'){

            $successMessage='Signup successfully';

        }

        else{

            $successMessage='کامیابی سے سائن اپ ہو گیا';

        }

        // return response

        return response()->json(['status' => 1, 'message' => $successMessage]);

    }

    /**

     *logout user api

    */

    public function logout(Request $request)

    {

       $request->user()->update(['fcm_token' => null]);

       $request->user()->tokens()->delete();

        if(request()->lang=='english'){

            $successMessage='logout successfully';

        }

        else{

            $successMessage='کامیابی سے لاگ آؤٹ ہو گیا';

        }

        return response()->json(['status' => 1, 'message' => $successMessage]);

    }



    /**

     * validate register form request

     * @param Request $request

     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator

     */

    public function validateIncomingRequest(Request $request, $action)

    {

        $userNameField = $request->user_name;

        $field = 'email';



        if (filter_var($userNameField, FILTER_VALIDATE_EMAIL)) $field = 'email';

        if (!filter_var($userNameField, FILTER_VALIDATE_EMAIL)) $field = 'user_name';

        request()->merge([$field => $userNameField]);



        switch ($action)

        {

            case 'login': $rules = ['user_name' => 'required', 'password' => 'required'];

            break;

            case 'signup': $rules = ['phone_number' => [ 'required','numeric',Rule::unique('users')->where(function ($query) use ($request) {return $query->where('country_code_id', $request->country_code_id);}),], 'password' => 'required', 'user_name' => ['required', 'alpha_num', 'regex:/\d+/',Rule::unique('users')], 'email' => 'required|unique:users','full_name'=>'required','country_code_id'=>'required'];

            break;

        }



        $validator = Validator::make($request->all(), $rules, [

            'required' => 'The :attribute field is required.'

        ]);

        return $validator;

    }





    /**

     * create token

     * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null

     */

    public function createToken()

    {

        $user = auth()->user();

        $user->token = Str::after(auth()->user()->createToken('loginToken')->plainTextToken, '|');

        return $user;

    }

    /**

     * fotget user password

    */

    public function forgetPassword()

    {

        return response()->json(['status' => 1, 'url' => url('password/reset')]);

    }



    /**

     * for check cabinet role before login user role

    */

    public function cabinetUserRole(Request $request)

    {

        $login =  $request->userField;

        $field = '';

        if (is_numeric($login)) {

            $field = 'phone_number';

        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            $field = 'email';

        } else {

            $field = 'user_name';

        }

        $user =   User::where($field, '=', $request->userField)->first();

        if($user){

            $cabinetUsers = (empty($user)) ? '' : $user->cabinetUsers;

            $cabinet=Cabinet::whereIn('id',$cabinetUsers->pluck('cabinet_id'))->where('status',1)->pluck('id')->toArray();

            if(!empty($cabinet) && isset($cabinet)){

                $cabinetUsers=CabinetUser::whereIn('cabinet_id',$cabinet)->where('user_id',$user->id)->get();

            }

            else{

                $cabinetUsers='';

            }

            if (!empty($cabinetUsers) &&  !$user->cabinetUsers->isEmpty()) {

                $roles_id = [];

                foreach ($cabinetUsers as $val) {

                    array_push($roles_id, $val->role_id);

                }

                $query = getQuery($request->lang, ['name']);

                $query[] = 'id';

                $data['roles'] = Role::select($query)->WhereIn('id', $roles_id)->get();



                return response()->json(['status' => 1, 'message' => 'success','data'=>$data]);

            } else {

                return response()->json(['status' => 0, 'message' => 'No data found!!']);

            }

        }

        else{

            return response()->json(['status' => 0, 'message' => 'User data not found!!']);

        }

    }

}

