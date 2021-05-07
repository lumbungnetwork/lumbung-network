<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Auth;
use App\Model\Member\LMBreward;
use Illuminate\Http\Request;
use App\Jobs\Member\SendLMBRewardMarketplaceJob;
use App\Model\Bonus;
use App\Jobs\UserClaimDividendJob;
use Illuminate\Support\Facades\Cache;

class AjaxController extends Controller
{
    //LMB Rewards
    public function postClaimShoppingReward()
    {
        $user = Auth::user();

        if ($user->tron == null) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengatur alamat TRON anda']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('claim_' . $user->id, 20);

        if ($lock->get()) {
            // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
            $LMBreward = new LMBreward;
            $netLMBReward = $LMBreward->getUserNetLMBReward($user->id, 0);

            if ($netLMBReward > 0) {
                // Create negative LMBreward model record
                $reward = new LMBreward;
                $reward->user_id = $user->id;
                $reward->amount = $netLMBReward;
                $reward->type = 0;
                $reward->is_store = 0;
                $reward->save();

                // Dispatch job
                SendLMBRewardMarketplaceJob::dispatch($reward->id)->onQueue('tron');

                $lock->release();

                return response()->json(['success' => true, 'message' => 'Reward Claimed']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something is wrong, try again later']);
    }

    public function postStakeShoppingReward()
    {
        $user = Auth::user();

        if ($user->tron == null) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengatur alamat TRON anda']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
            $LMBreward = new LMBreward;
            $netLMBReward = $LMBreward->getUserNetLMBReward($user->id, 0);

            if ($netLMBReward > 0) {
                // Create negative LMBreward model record
                $reward = new LMBreward;
                $reward->user_id = $user->id;
                $reward->amount = $netLMBReward;
                $reward->type = 0;
                $reward->is_store = 0;
                $reward->hash = 'Staked';
                $reward->save();

                // Add Stake
                $Bonus = new Bonus;
                $Bonus->insertUserStake([
                    'user_id' => $user->id,
                    'type' => 1,
                    'amount' => $netLMBReward,
                    'hash' => 'Stake dari Shopping Reward',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $lock->release();

                return response()->json(['success' => true, 'message' => 'Reward Staked']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something is wrong, try again later']);
    }

    public function getStakeAdd(Request $request)
    {
        return view('member.ajax.get_stake_add')
            ->with('max', $request->max);
    }

    public function getStakeSubstract(Request $request)
    {
        return view('member.ajax.get_stake_substract')
            ->with('max', $request->max);
    }

    public function postStakeConfirm(Request $request)
    {
        $user = Auth::user();

        $Bonus = new Bonus;

        $hash = $request->hash;
        $check = $Bonus->checkUsedHashExist($hash, 'staking', 'hash');
        if ($check) {
            return response()->json(['success' => false, 'message' => 'Hash Transaksi sudah pernah digunakan pada transaksi sebelumnya']);
        }
        $receiver = 'TY8JfoCbsJ4qTh1r9HBtmZ88xQLsb6MKuZ';
        $amount = $request->amount;

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            $tron = $this->getTron();
            $i = 1;
            do {
                try {
                    sleep(1);
                    $response = $tron->getTransaction($hash);
                } catch (TronException $e) {
                    $i++;
                    continue;
                }
                break;
            } while ($i < 23);

            if ($i == 23) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Hash Transaksi Bermasalah!']);
            };

            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            if ($hashAmount == $amount * 1000000) {
                if ($hashAsset == '1002640') {
                    if ($hashReceiver == $receiver) {

                        $Bonus->insertUserStake([
                            'user_id' => $user->id,
                            'type' => 1,
                            'amount' => $amount,
                            'hash' => $hash,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        $lock->release();
                        return response()->json(['success' => true]);
                    } else {
                        $lock->release();
                        return response()->json(['success' => false, 'message' => 'Alamat Tujuan Transfer Salah!']);
                    }
                } else {
                    $lock->release();
                    return response()->json(['success' => false, 'message' => 'Bukan token LMB yang benar!']);
                }
            } else {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Nominal Transfer Salah!']);
            }
        }
    }

    public function postUnstake(Request $request)
    {
        $user = Auth::user();
        if ($user->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            return response()->json(['success' => false, 'message' => 'Membership Expired!']);
        }
        $Bonus = new Bonus;

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            $amount = $request->amount;

            if (
                $amount <= 0
            ) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Amount cannot be zero or less!']);
            }

            $userStakedLMB = $Bonus->getUserStakedLMB($user->id);
            $newStakedLMB = $userStakedLMB - $amount;
            if ($newStakedLMB < 0) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Amount exceeds staked balance!']);
            } else {
                $due_date = date('Y-m-d', strtotime('+7 days'));
                $unstake = $Bonus->insertUserStake([
                    'user_id' => $user->id,
                    'type' => 2,
                    'amount' => $amount,
                    'hash' => 'Unstaking Process (' . $due_date . ')',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $Bonus->insertUnstakingData([
                    'staking_id' => $unstake->lastID,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'due_date' => $due_date,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $lock->release();
                return response()->json(['success' => true]);
            }
        }
    }

    public function postClaimStakingDividend(Request $request)
    {
        $user = Auth::user();
        if ($user->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            return response()->json(['success' => false, 'message' => 'Membership Expired!']);
        }
        $Bonus = new Bonus;

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            $userDividend = $Bonus->getUserDividend($user->id);

            if ($userDividend->net >= 1000) {
                $deductDiv = $Bonus->insertUserDividend([
                    'user_id' => $user->id,
                    'type' => 0,
                    'amount' => $userDividend->net,
                    'date' => date('Y-m-d H:i:s')
                ]);
                UserClaimDividendJob::dispatch($user->id, $deductDiv->lastID)->onQueue('tron');
                sleep(4);
                $lock->release();
                return response()->json(['success' => true]);
            } else {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Not enough available dividend!']);
            }
        }
    }
}
