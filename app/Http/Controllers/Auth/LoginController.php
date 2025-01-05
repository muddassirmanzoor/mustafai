<?php

namespace App\Http\Controllers\Auth;

use App\Events\LoginHistory;
use App\Http\Controllers\Controller;
use App\Models\Admin\CabinetUser;
use App\Models\Admin\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->ajax()) {

            throw ValidationException::withMessages([
                'user_name' => [trans('auth.failed')],

            ]);
        } else {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }

    /**
     * checking input field for username and email
    */

    public function username()
    {
        if (request()->ajax()) {

            $login = request()->input('username');

            // if (is_numeric($login)) {
            //     $field = 'phone_number';
            // } else
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } else {
                $field = 'user_name';
            }

            request()->merge([$field => $login]);

            return $field;
        } else {
            return 'phone_number';
        }
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $column_name = $this->username();
        if (request()->ajax()) {
            $usernamedata = $request->username;
        } else {
            $usernamedata = $request->phone_number;
        }
        $userData = User::where($column_name, '=', $usernamedata)->first();
        $userId = $userData->id;

        $roleExist = CabinetUser::where('user_id', $userId)->where('role_id', $request->role_id)->count();
        if (!$roleExist > 0 && isset($request->role_id)) {
            return redirect()->back();
        }

        if (!empty($userData)) {
            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {

                    $request->session()->put('auth.password_confirmed_at', time());
                    // $user->setAttribute('orgId', $organisation->id);
                    if (isset($request->role_id)) {

                        User::where('id', auth()->user()->id)->update(['login_role_id' => $request->role_id]);
                        
                    } elseif (auth()->user()->designation_id) {
        
                        User::where('id', auth()->user()->id)->update(['login_role_id' => auth()->user()->designation_id]);
        
                    }
                    else {
                        User::where('id', auth()->user()->id)->update(['login_role_id' => auth()->user()->role_id]);
        
                    }
                }
                $user = auth()->user();
                // event(new LoginHistory($user));
                return $this->sendLoginResponse($request);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
