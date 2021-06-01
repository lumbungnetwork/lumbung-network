<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
    }

    public function getFront()
    {
        return redirect()->route('areaLogin');
    }

    public function getAreaLogin()
    {
        // return view('member.login-member');
        return redirect()->route('member.login');
    }

    public function postAreaLogin(Request $request)
    {
        $email = $request->admin_email;
        $password = $request->admin_password;
        $userdata = array('username' => $email, 'password'  => $password, 'is_login' => 1);
        if ($this->guard()->attempt($userdata)) {
            Auth::logoutOtherDevices($password);
            $request->session()->regenerate();
            return redirect()->route('admDashboard');
        }
        return redirect()->route('areaLogin')
            ->with('message', 'Login gagal')
            ->with('messageclass', 'danger');
    }

    public function getUserLogout(Request $request)
    {
        $dataUser = Auth::user();
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('areaLogin');
    }
}
