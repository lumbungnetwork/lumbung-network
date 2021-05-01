<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getDashboard()
    {
        $user = Auth::user();

        return view('member.app.dashboard')
            ->with(compact('user'));
    }
}
