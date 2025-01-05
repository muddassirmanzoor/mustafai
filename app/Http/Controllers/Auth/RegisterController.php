<?php



namespace App\Http\Controllers\Auth;



use App\Helper\UserFee;

use App\Http\Controllers\Controller;

use App\Models\Admin\Admin;

use App\Models\Admin\Notification;

use App\Providers\RouteServiceProvider;

use App\Models\User;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use Illuminate\Auth\Events\Registered;

use Illuminate\Http\JsonResponse;

use App\Models\Admin\Setting;



class RegisterController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Register Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles the registration of new users as well as their

    | validation and creation. By default this controller uses a trait to

    | provide this functionality without requiring any additional code.

    |

    */



    use RegistersUsers;



    /**

     * Where to redirect users after registration.

     *

     * @var string

     */

    protected $redirectTo = RouteServiceProvider::HOME;



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('guest');

    }



    /**

     * Get a validator for an incoming registration request.

     *

     * @param  array  $data

     * @return \Illuminate\Contracts\Validation\Validator

     */

    protected function validator(array $data)

    {

        $messages = [

            'email.unique' => __('app.email-unique'),

            'phone_number.unique' => __('app.phone-unique'),

            'user_name.unique' => __('app.username-unique'),



        ];

        return Validator::make($data, [

            'user_name' => ['required', 'string', 'max:255', 'regex:/[^\d]+/'],

            'email' => ['required', 'string','unique:users,email'],

            'phone_number' => ['required', 'numeric'],

            'country_code_id' => ['required'],

            'password' => ['required', 'string', 'min:8', 'confirmed:password_confirmation_field'],

        ], $messages);

    }



    /**

     * Create a new user instance after a valid registration.

     *

     * @param  array  $data

     * @return \App\Models\User

     */

    protected function create(array $data)

    {
        $data['user_name'] = preg_replace('/\s+/','',$data['user_name']);

        $user = User::create([

            'full_name'         => $data['full_name'],

            'user_name'         => $data['user_name'],

            'user_name_english' => $data['user_name'],

            'role_id'           => 2,

            'country_code_id'   => $data['country_code_id'],

            'phone_number'      => $data['phone_number'],

            'email'             => $data['email'],

            'status'            => 1,

            'is_public'         => 1,

            'password'          => Hash::make($data['password']),

            'original_password' => $data['password'],

        ]);



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

            'ip'=> request()->ip()

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



        return $user;

    }

    /**

     * Handle a registration request for the application.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse

     */

    public function register(Request $request)

    {

        $this->validator($request->all())->validate();



        event(new Registered($user = $this->create($request->all())));



        if ($response = $this->registered($request, $user)) {

            return $response;

        }

        return $request->wantsJson()

            ? new JsonResponse([], 201)

            : redirect($this->redirectPath());

    }



    protected function redirectTo()

    {



        return '/after-register-message';

    }

}

