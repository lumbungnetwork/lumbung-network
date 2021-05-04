<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Member\Sales;
use App\Model\Bonus;

class AppController extends Controller
{
    public function getHome()
    {
        $user = Auth::user();

        // get user's current month spending
        $Sales = new Sales;
        $spending = $Sales->getMemberSpending($user->id, date('m'), date('Y'));

        // get dividend pool and user's staked LMB
        $Bonus = new Bonus;
        $LMBDividendPool = $Bonus->getLMBDividendPool();
        $userStakedLMB = $Bonus->getUserStakedLMB($user->id);

        return view('member.app.home')
            ->with('title', 'Home')
            ->with(compact('user'))
            ->with(compact('LMBDividendPool'))
            ->with(compact('userStakedLMB'))
            ->with(compact('spending'));
    }
}
