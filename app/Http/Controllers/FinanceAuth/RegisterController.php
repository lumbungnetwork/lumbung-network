<?php

namespace App\Http\Controllers\FinanceAuth;

use App\Finance;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Jobs\SendFinanceRegistrationEmailJob;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('finance.guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:finances',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Finance
     */
    protected function create(array $data)
    {
        return Finance::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('finance.auth.register')
            ->with('title', 'Register Account');
    }

    public function getRegisterRef($ref)
    {
        $referral = Finance::where('username', $ref)->select('id', 'username')->first();
        if ($referral == null) {
            return redirect()->to('page-not-found');
        }
        return view('finance.auth.register')
            ->with(compact('referral'))
            ->with('title', 'Register Account');
    }

    public function postRegister(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:finances|max:32|alpha_dash',
            'email' => 'required|email:filter|unique:finances,email',
            'password' => 'required|confirmed|max:125',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $sponsor_id = 1;

        if (isset($request->referral)) {
            $sponsor_id = $request->referral;
        }

        Finance::create([
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

        SendFinanceRegistrationEmailJob::dispatch($dataEmail, $validated['email'])->onQueue('mail');

        Alert::success('Welcome!', 'Account successfuly registered, please login with your username and password')->persistent(true);
        return view('finance.auth.login')
            ->with('title', 'Login');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('finance');
    }
}
