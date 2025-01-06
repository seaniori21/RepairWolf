<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Admin;

use App\Mail\AdminPasswordReset;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use DB;


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

    /**
     * exclude unless admin guest
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('backend.auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $user = $this->getUser($request->email);

        if (is_null($user)) { 
            return back()->with('error','We can\'t find a user with that email address.');
        }

        $token = $this->createToken($user);
        $this->sendPasswordResetNotification($token,$user);

        return back()->with('status','We have emailed your password reset link!');
    }

    protected function getUser($email)
    {
        $user = Admin::where('email', $email)->first();        
        return $user;
    }

    protected function createToken($user)
    {
        $token = Str::random(60);
        DB::table('password_resets')->insert(['email' => $user->email, 'token' => $token, 'created_at' => Carbon::now()]);
        return $token;
    }

    protected function sendPasswordResetNotification($token,$user)
    {
        $data = [
            'token' => $token,
            'user' => $user,
            'subject' => 'Reset Password Notification',
        ];

        Mail::to($user->email)->send(new AdminPasswordReset($data));
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:admins,email']);
    }
}
