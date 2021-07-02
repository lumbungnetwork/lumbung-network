<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Cache;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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

    public function getResetPassword($token)
    {
        // Decrypt token and check if exist in cache
        try {
            $key = Crypt::decryptString($token);
        } catch (DecryptException $e) {
            abort(404, 'Not Found!');
        }
        
        $check = Cache::has($key);
        if (!$check) {
            abort(404, 'Not Found!');
        }

        // If exist, get the user
        $user_id = Cache::get($key);
        $user = User::find($user_id);

        return view('member.auth.passwords.reset')
            ->with(compact('token'))
            ->with('username', $user->username)
            ->with('title', 'Reset Password');

    }

    public function postResetPassword(Request $request)
    {
        // validate input
        $validated = $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:6|max:100|confirmed'
        ]);

        // Decrypt and check token validity
        try {
            $key = Crypt::decryptString($validated['token']);
        } catch (DecryptException $e) {
            abort(404, 'Bad Token!');
        }
        
        $check = Cache::has($key);
        if (!$check) {
            abort(404, 'Not Found!');
        }

        // If exist, get the user
        $user_id = Cache::pull($key);
        $user = User::find($user_id);

        // Set new password
        $user->password = Hash::make($validated['password']);
        $user->save();

        Alert::success('Berhasil', 'Password akun ' . $user->username . ' telah diubah, silakan Login dengan password baru');
        return redirect()->route('member.login')
        ->with('title', 'Login');
    }
}
