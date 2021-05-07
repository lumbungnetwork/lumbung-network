<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Member\Sales;
use App\Model\Bonus;
use App\Model\Member\LMBreward;

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

    public function getClaimShoppingReward()
    {
        $user = Auth::user();

        // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
        $LMBreward = new LMBreward;
        $netLMBReward = $LMBreward->getUserNetLMBReward($user->id, 0);
        // Get Reward History (both credit and claimed)
        $rewardHistory = LMBreward::where('user_id', $user->id)->get();

        return view('member.claim.shopping_reward')
            ->with('title', 'Claim Reward')
            ->with(compact('user'))
            ->with(compact('netLMBReward'))
            ->with(compact('rewardHistory'));
    }

    public function getStake()
    {
        $user = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($user->user_type, $onlyUser)) {
            Alert::error('Oops!', 'Fitur Staking hanya untuk Premium Member!');
            return redirect()->route('member.home');
        }

        if ($user->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            Alert::error('Oops!', 'Keanggotaan anda sudah EXPIRED!');
            return redirect()->route('member.home');
        }

        $modelBonus = new Bonus;
        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $totalStakedLMB = $modelBonus->getStakedLMB();
        $userStakedLMB = $modelBonus->getUserStakedLMB($user->id);
        $userDividend = $modelBonus->getUserDividend($user->id);
        $userUnstaking = $modelBonus->getUserUnstakeProgress($user->id);

        return view('member.app.stake')
            ->with('title', 'Staking')
            ->with(compact('LMBDividendPool'))
            ->with(compact('totalStakedLMB'))
            ->with(compact('userStakedLMB'))
            ->with(compact('userDividend'))
            ->with(compact('userUnstaking'))
            ->with(compact('user'));
    }

    public function getStakeHistory()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $data = $Bonus->getUserStakingHistory($user->id);

        return view('member.app.stake_history')
            ->with('title', 'Stake History')
            ->with(compact('data'));
    }

    public function getStakeDivHistory()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $data = $Bonus->getUserDividendHistory($user->id);

        return view('member.app.stake_div_history')
            ->with('title', 'Dividend History')
            ->with(compact('data'));
    }

    public function getStakeClaimedDivHistory()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $data = $Bonus->getUserClaimedDividend($user->id);

        return view('member.app.stake_history')
            ->with('title', 'Claimed Dividend')
            ->with(compact('data'));
    }

    public function getStakeLeaderboard()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $totalStakedLMB = $Bonus->getStakedLMB();
        $stakers = $Bonus->getAllStakersLeaderboard();

        return view('member.app.stake_leaderboard')
            ->with('title', 'Staking Leaderboard')
            ->with(compact('totalStakedLMB'))
            ->with(compact('stakers'));
    }
}
