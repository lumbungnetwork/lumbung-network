<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Cache;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('member.auth.passwords.forgot')
            ->with('title', 'Request Reset Password');
    }

    public function postRequestResetPassword(Request $request)
    {
        // validate input
        $validated = $request->validate([
            'username' => 'required|string|exists:users,username'
        ]);

        // Get user's email
        $user = User::where('username', $validated['username'])->select('id', 'email')->first();

        // Create unique identifier and put it as Cache key
        $uniqueCode = uniqid();
        $key = 'reset_password_' . $uniqueCode;
        Cache::put($key, $user->id , 3600);

        // Encrypt key to use as URI token
        $token = Crypt::encryptString($key);

        // Send email with token
        $data = [
            'username' => $validated['username'],
            'token' => $token
        ];
        Mail::send('member.email.forgot', $data, function ($message) use ($user) {
            $message->to($user->email, 'Lumbung Network Reset Password')
                ->subject('Reset Password');
        });

        return redirect()->back()
            ->with('status', 'Link untuk Reset Password telah dikirimkan ke alamat email anda.');
    }
}
