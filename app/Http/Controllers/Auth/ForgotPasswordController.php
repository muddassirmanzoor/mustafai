<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Str;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new MyCustomResetPasswordNotification($token)); <--- remove this, use Mail instead like below

        //_____________For  Email Sending_________//
        $details = [
            'user_name' =>  'saad',
            'content'  => "<p>We are very happy to welcome you on the Mustafai portral team .</p><p>You are register and login after Admin approval</p>",
            'links'    =>  "<a href='" . url('/') . "'>Click Here To Go To The Mustfai Portal</a>",
        ];

        try {
            \Mail::to('saad#gmail.com')->send(new \App\Mail\CommonMail($details));
        } catch (Exception $e) {
            // Never reached

        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}
