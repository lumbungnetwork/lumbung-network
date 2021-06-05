<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Jobs\SendRegistrationEmailJob;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('member.auth.register')
            ->with('title', 'Register');
    }

    public function getRegisterRef($ref)
    {
        $referral = User::where('username', $ref)->select('id', 'username')->first();
        if ($referral == null) {
            return redirect()->to('page-not-found');
        }
        return view('member.auth.register')
            ->with(compact('referral'))
            ->with('title', 'Register');
    }

    public function postRegister(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username|max:32|alpha_dash',
            'email' => 'required|email:filter',
            'password' => 'required|confirmed|max:125',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $sponsor_id = 5;

        if (isset($request->referral)) {
            $sponsor_id = $request->referral;
        }

        User::create([
            'name' => $validated['username'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'sponsor_id' => $sponsor_id
        ]);

        $dataEmail = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        SendRegistrationEmailJob::dispatch($dataEmail, $validated['email'])->onQueue('mail');

        Alert::success('Selamat Datang!', 'Akun anda telah didaftarkan, silakan login dengan username dan password anda')->persistent(true);
        return redirect()->route('member.login');
    }
}
