<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Jobs\ClaimNetworkRewardJob;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Cache;
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

    public function postClaimNetworkReward()
    {
        $user = Auth::user();
        if ($user->member_type < 1) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }
        if (!$user->tron) {
            Alert::warning('Alamat TRON dibutuhkan', 'Anda belum melengkapi data alamat TRON anda, silakan melengkapiya sebelum claim reward');
            return redirect()->route('member.tron');
        }

        // Recheck qualification (minimum 4 downline with same member_type)
        $qualifiedDirects = User::where('sponsor_id', $user->id)->where('member_type', $user->member_type)->count();
        if ($qualifiedDirects < 4) {
            Alert::error('Oops', 'Tidak memenuhi syarat kualifikasi');
            return redirect()->back();
        }

        // use Atomic Lock to prevent race condition
        $lock = Cache::lock('claim_reward_' . $user->id, 60);

        if ($lock->get()) {
            // get Reward  and double check from DB
            // reward_id 1 = Silver III (100), 2 = Silver II (200), 3 = Silver I (500), 4 = Gold III (2000)
            // member_type 1 = New Member, 10 = Silver III, 11 = Silver II, 12 = Silver I, 13 = Gold III
            $reward = DB::table('bonus_reward2')->where('member_type', $user->member_type)->first();
            $check = DB::table('claim_reward')->where('user_id', $user->id)->where('reward_id', $reward->id)->exists();
            if ($check) {
                $lock->release();
                Alert::info('Claimed', 'Reward telah diklaim');
                return redirect()->back();
            }
            // create claim_reward record
            $claimRewardID = DB::table('claim_reward')->insertGetId([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'claim_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Dispatch job to process TRON transfer
            ClaimNetworkRewardJob::dispatch($claimRewardID)->onQueue('tron');
            sleep(3);

            // Update user's member_type to next rank
            $user->member_type = $reward->type;
            $user->save();

            $lock->release();
            Alert::success('Berhasil', 'Reward ' . $reward->name . ' telah di-claim, ' . $reward->reward_detail . ' LMB akan segera masuk ke alamat TRON anda.');
            return redirect()->back();
        }

        Alert::error('Oops', 'Access Denied');
        return redirect()->back();
    }

    public function getBinaryTree($placing, Request $request)
    {
        $user = Auth::user();
        // set default node1 to session's user
        $node1 = $user;
        // enable back button on tree when node1 != session's user
        $back = false;
        // uplines detail as search downline constrain
        $uplines = $user->upline_detail . ',[' . $user->id . ']';
        if (!$user->upline_detail) {
            $uplines = '[' . $user->id . ']';
        }
        // handle if no downlines available to place
        if ($placing) {
            // Get direct downlines yet to place
            $downlines = User::where('sponsor_id', $user->id)
                ->where('member_type', '>', 0)
                ->whereNull('upline_id')
                ->select('id', 'username')
                ->count();
            if ($downlines < 1) {
                $placing = 0;
                Alert::info('Placing', 'Belum ada downline yang perlu di-placement saat ini.');
            }
        }
        // handle request if this function called from search form
        if ($request->user_id && $request->user_id != $user->id) {
            $back = true;
            $node1 = User::where('id', $request->user_id)
                ->where('member_type', '>', 0)
                ->where('upline_detail', 'LIKE', $uplines . '%')
                ->first();
        }
        if (!$node1) {
            $node1 = $user;
        }
        // get binary data
        $modelUser = new User;
        $binary = $modelUser->getBinary($node1->id);

        return view('member.app.network.binary')
            ->with('title', ($placing ? 'Placing Binary' : 'Binary Tree'))
            ->with(compact('user'))
            ->with(compact('node1'))
            ->with(compact('back'))
            ->with(compact('placing'))
            ->with(compact('binary'));
    }

    public function postBinaryPlacement(Request $request)
    {
        $user = Auth::user();
        // Double check upline and position
        $upline = User::find($request->upline_id);
        if (!$upline || !$upline->member_type) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->route('member.home');
        }
        $position = $request->position;
        if ($upline->$position) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->route('member.home');
        }
        // Double check the downline
        $downline = User::find($request->downline_id);
        if (!$downline || !$downline->member_type || $downline->upline_id) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->route('member.home');
        }

        // Update downline placement data
        $downline->upline_id = $upline->id;
        $downline->upline_detail = $upline->upline_detail . ',[' . $upline->id . ']';
        $downline->placement_at = date('Y-m-d H:i:s');
        $downline->save();

        // Update upline binary data
        $upline->$position = $downline->id;
        $upline->save();

        Alert::success('Berhasil', 'Downline telah di-placement di pohon binary');
        return redirect()->route('member.network.binaryTree', ['placing' => 0]);
    }

    public function getSponsorTree(Request $request)
    {
        $user = Auth::user();
        // set default node1 to session's user
        $node1 = $user;
        // handle request if this function called from search form
        if ($request->user_id && $request->user_id != $user->id) {
            $node1 = User::where('id', $request->user_id)
                ->where('member_type', '>', 0)
                ->where('id', '>', $user->id)
                ->first();
        }
        if (!$node1) {
            $node1 = $user;
        }
        // get directs
        $directs = User::where('sponsor_id', $node1->id)
            ->where('member_type', '>', 0)
            ->get();

        return view('member.app.network.sponsor_tree')
            ->with('title', 'Sponsor Tree')
            ->with(compact('user'))
            ->with(compact('node1'))
            ->with(compact('directs'));
    }
}
