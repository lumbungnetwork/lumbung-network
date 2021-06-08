<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class NetworkController extends Controller
{
    public function getNetwork()
    {
        $user = Auth::user();

        if ($user->user_type == 9) {
            Alert::error('Access Denied', 'Fitur Network hanya untuk Premium Member');
            return redirect()->back();
        }
        $qualified = false;
        $reward = null;
        $currentRank = null;
        if ($user->member_type >= 10) {
            $currentRank = DB::table('bonus_reward2')
                ->where('type', $user->member_type)
                ->select('name', 'reward_detail', 'image', 'member_type', 'type')
                ->first();
        }
        $directs = User::where('sponsor_id', $user->id)
            ->where('member_type', '>', 0)
            ->select('id', 'username', 'member_type', 'total_sponsor')
            ->get();
        if (count($directs) >= 4) {
            $qualifiedDirect = 0;
            foreach ($directs as $direct) {
                if ($direct->member_type >= $user->member_type) {
                    $qualifiedDirect++;
                }
            }
            if ($qualifiedDirect >= 4) {
                $qualified = true;
                $reward = DB::table('bonus_reward2')
                    ->where('member_type', $user->member_type)
                    ->select('id', 'name', 'reward_detail', 'image', 'member_type', 'type')
                    ->first();
            }
        }

        return view('member.app.network')
            ->with(compact('user'))
            ->with(compact('qualified'))
            ->with(compact('reward'))
            ->with(compact('currentRank'))
            ->with(compact('directs'))
            ->with('title', 'Network');
    }

    public function getRankQualification($user_id)
    {
        $user = User::find($user_id);
    }
}
