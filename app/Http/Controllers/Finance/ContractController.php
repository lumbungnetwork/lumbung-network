<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Finance;
use App\Http\Controllers\Controller;
use App\Model\Finance\USDTbalance;
use App\Model\Finance\Contract;
use App\Jobs\ActivateContractJob;
use App\Model\Finance\Credit;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Model\Finance\_Yield;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use IEXBase\TronAPI\Exception\TronException;

class ContractController extends Controller
{
    public function getContractsPage()
    {
        $user = Auth::user();
        if ($user->is_active == 0) {
            Alert::warning('Inactive Account', 'You need to activate your account first');
            return redirect()->route('finance.account.activate');
        }

        $contracts = Contract::where('user_id', $user->id)->get();
        // Count Active contracts
        $activeContracts = Contract::where('user_id', $user->id)->where('status', '<', 2)->select('id')->count();
        // Count Referrals
        $referrals = Finance::where('sponsor_id', $user->id)->select('id')->count();
        $modelYield = new _Yield;
        $yields = $modelYield->getUserTotalYields($user->id);

        return view('finance.contracts')
            ->with('title', 'Contracts')
            ->with(compact('contracts'))
            ->with(compact('activeContracts'))
            ->with(compact('referrals'))
            ->with(compact('yields'))
            ->with(compact('user'));
    }

    public function getNewContractPage()
    {
        $user = Auth::user();
        $modelUSDTbalance = new USDTbalance;
        $USDTbalance = $modelUSDTbalance->getUserNetUSDTbalance($user->id);

        $Credit = new Credit;
        $creditBalance = $Credit->getUserNetCreditBalance($user->id);

        return view('finance.contracts.new')
            ->with('title', 'Open New Contract')
            ->with(compact('USDTbalance'))
            ->with(compact('creditBalance'))
            ->with(compact('user'));
    }

    public function getContractDetailPage($contract_id)
    {
        $user = Auth::user();
        // Check the contract
        $contract = Contract::find($contract_id);
        if ($contract == null) {
            Alert::error('Error', 'Contract not found!');
            return redirect()->route('finance.contracts');
        }

        // Check if the caller is the right owner of the called contract
        if ($contract->user_id !== $user->id) {
            Alert::error('Error', 'Access Denied!');
            return redirect()->route('finance.contracts');
        }

        $modelYield = new _Yield;
        $yield = $modelYield->getContractYield($contract_id);

        return view('finance.contracts.detail')
            ->with('title', 'Contract Details')
            ->with(compact('contract'))
            ->with(compact('yield'))
            ->with(compact('user'));
    }

    public function postNewContract(Request $request)
    {
        $user = Auth::user();
        $modelUSDTbalance = new USDTbalance;

        // Use Atomic Lock to prevent race condition
        $lock = Cache::lock('contract' . $user->id, 20);

        if ($lock->get()) {
            $USDTbalance = $modelUSDTbalance->getUserNetUSDTbalance($user->id);

            // Validate input
            $validator = Validator::make($request->all(), [
                'contract_strategy' => 'required|integer|between:1,2',
                'deposit' => "required|numeric|min:10|max:$USDTbalance"
            ]);

            if ($validator->fails()) {
                Alert::error('Failed', $validator->errors()->first());
                $lock->release();
                return redirect()->back();
            }

            $strategy = $request->contract_strategy;
            $deposit = $request->deposit;
            $newUSDTbalance = $USDTbalance - $deposit;

            if ($newUSDTbalance < 0) {
                Alert::error('Failed!', 'Insufficient USDT Balance!');
                $lock->release();
                return redirect()->back();
            }

            if ($strategy == 1 && $deposit < 100) {

                Alert::error('Failed!', 'Minimum 100 USDT needed for this strategy!');
                $lock->release();
                return redirect()->back();
            }

            if ($strategy == 2 && $deposit < 10) {

                Alert::error('Failed!', 'Minimum 10 USDT needed for this strategy!');
                $lock->release();
                return redirect()->back();
            }

            $expired_at = null;
            if ($strategy == 2) {
                $expired_at = date('Y-m-d 00:00:00', strtotime('+364 days'));
            }

            $next_yield_at = date('Y-m-d 00:00:00', strtotime('+32 days'));
            if ($strategy == 2) {
                $next_yield_at = date('Y-m-d 00:00:00', strtotime('+16 days'));
            }

            // Principal = Deposit - 4% Fee (50% fo fee goes to Referrer)
            $fee = $deposit * 4 / 100;
            $principal = round($deposit - $fee, 2, PHP_ROUND_HALF_DOWN);
            $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

            try {
                // Deduct User's USDTbalance
                $balance = new USDTbalance;
                $balance->user_id = $user->id;
                $balance->amount = $deposit;
                $balance->type = 0;
                $balance->status = 1;
                $balance->save();
            } catch (\Throwable $th) {
                Alert::error('Failed!', 'Something is wrong, please try again later!');
                $lock->release();
                return redirect()->back();
            }

            // Create the contract 
            $contract = new Contract;
            $contract->user_id = $user->id;
            $contract->strategy = $strategy;
            $contract->principal = $principal;
            $contract->expired_at = $expired_at;
            $contract->next_yield_at = $next_yield_at;
            $contract->save();

            // Add contract id reference to USDT balance record
            $balance->hash = sprintf('%07s', $contract->id);
            $balance->save();

            // Dispatch a 48hrs delayed job to Activate the Contract
            ActivateContractJob::dispatch($contract->id)->onQueue('mail')->delay(now()->addHours(48));

            // Credit the Referrer
            $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

            // Release atomic lock
            $lock->release();
        }


        Alert::success('Contract Created', 'New Contract Successfully Created.');
        return redirect()->route('finance.contracts');
    }

    public function postContractCompound($contract_id)
    {
        $user = Auth::user();
        // Check the contract
        $contract = Contract::find($contract_id);
        if ($contract == null) {
            Alert::error('Error', 'Contract not found!');
            return redirect()->route('finance.contracts');
        }

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        // Use Atomic Lock to prevent race condition
        $lock = Cache::lock('contract' . $user->id, 60);

        if ($lock->get()) {
            // Get current available yield from Yield object
            $modelYield = new _Yield;
            $yield = $modelYield->getContractYield($contract_id);

            // Check if yield net enough for action
            if ($yield->net < 1) {
                Alert::error('Oops!', 'Insuficient Yield amount for this action!');
                $lock->release();
                return redirect()->back();
            }

            // Deduct the fee from Compounding Amount
            $fee = $yield->net * 4 / 100;
            $amount = round($yield->net - $fee, 2, PHP_ROUND_HALF_DOWN);
            $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

            // Deduct the Yield and increment Compounded column on Contract
            $compounding = $modelYield->compound($contract_id, $yield->net, $amount);

            if ($compounding) {
                // Send half the fee as referrer bonus
                $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

                $lock->release();
                Alert::success('Success!', 'Yield just compounded successfully!');
                return redirect()->back();
            } else {
                $lock->release();
                Alert::error('Oops!', 'Something is wrong, Compounding Failed!');
                return redirect()->back();
            }
        }
    }

    public function postContractWithdraw($contract_id)
    {
        $user = Auth::user();
        // Check the contract
        $contract = Contract::find($contract_id);
        if ($contract == null) {
            Alert::error('Error', 'Contract not found!');
            return redirect()->route('finance.contracts');
        }

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        // Use Atomic Lock to prevent race condition
        $lock = Cache::lock('contract' . $user->id, 60);

        if ($lock->get()) {
            // Get current available yield from Yield object
            $modelYield = new _Yield;
            $yield = $modelYield->getContractYield($contract_id);

            // Check if yield net enough for action
            if ($yield->net < 1) {
                $lock->release();
                Alert::error('Oops!', 'Insuffiecient Yield amount for this action!');
                return redirect()->back();
            }

            // Deduct the fee from Withdraw Amount
            $fee = $yield->net * 4 / 100;
            $amount = round($yield->net - $fee, 2, PHP_ROUND_HALF_DOWN);
            $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

            // Deduct the Yield and send credit
            $withdraw = $modelYield->withdraw($contract_id, $amount, $fee);

            if ($withdraw) {
                // Send half the fee as referrer bonus
                $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

                $lock->release();
                Alert::success('Success!', 'Yield just withdrawed successfully!');
                return redirect()->back();
            } else {
                $lock->release();
                Alert::error('Oops!', 'Something is wrong, Withdraw Failed!');
                return redirect()->back();
            }
        }
    }

    public function postContractUpgrade($contract_id, Request $request)
    {
        $user = Auth::user();

        // Check the contract
        $contract = Contract::find($contract_id);
        if ($contract == null) {
            Alert::error('Error', 'Contract not found!');
            return redirect()->route('finance.contracts');
        }
        // Get contract age
        $diff = time() - strtotime($contract->created_at);
        $days = floor($diff / (60 * 60 * 24));

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        if ($contract->grade == 1 && $days >= 16) {
            $contract->grade = 2;
            $contract->save();

            Alert::success('Done!', 'Contract successfully upgraded');
            return redirect()->back();
        }

        if ($request->hash != 0) {
            $hash = $request->hash;
        }

        if ($contract->grade == 2 && $days >= 106 || $contract->grade == 3 && $days >= 196) {
            $upgrade_to = $contract->grade + 1;
            // Check burn transaction hash
            $check = $this->checkContractUpgradeLMBBurns($hash, $contract->id);
            if ($check) {
                $contract->grade = $upgrade_to;
                $contract->save();

                // Log the upgrade tx
                DB::table('contract_upgrades')->insert([
                    'contract_id' => $contract->id,
                    'upgrade_to' => $upgrade_to,
                    'hash' => $hash,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                Alert::success('Done!', 'Contract successfully upgraded');
                return redirect()->back();
            }
        }

        Alert::error('Ouch!', 'Failed to Upgrade Contract');
        return redirect()->back();
    }

    public function checkContractUpgradeLMBBurns($hash, $contract_id)
    {
        // Check hash uniqueness
        $check1 = DB::table('finance_activations')->where('hash', $hash)->exists();
        $check2 = DB::table('contract_upgrades')->where('hash', $hash)->exists();

        if ($check1 || $check2) {
            return false;
        }

        $amount = 10000000; //10 LMB
        $burnAddress = 'TLsV52sRDL79HXGGm9yzwKibb6BeruhUzy';

        $tron = $this->getTron();
        $i = 1;
        sleep(2); // add 2 seconds wait before checking tx_id
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
            $text = 'Failed to verify Contract Upgrade' . chr(10);
            $text .= 'Contract ID: ' . $contract_id . chr(10);
            $text .= 'Hash: ' . $hash . chr(10);

            Telegram::sendMessage([
                'chat_id' => config('services.telegram.supervisor'),
                'text' => $text,
                'parse_mode' => 'markdown'
            ]);

            return false;
        };

        $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
        $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
        $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

        if ($hashReceiver != $burnAddress || $hashAsset != '1002640' || $hashAmount != $amount) {
            return false;
        }

        return true;
    }

    public function postContractBreak($contract_id)
    {
        $user = Auth::user();
        // Check the contract
        $contract = Contract::find($contract_id);
        if ($contract == null) {
            Alert::error('Error', 'Contract not found!');
            return redirect()->route('finance.contracts');
        }

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        // Use Atomic Lock to prevent race condition
        $lock = Cache::lock('contract' . $user->id, 60);

        if ($lock->get()) {
            // Check breakable traits
            $diff = time() - strtotime($contract->created_at);
            $days = floor($diff / (60 * 60 * 24));

            if ($contract->strategy == 1 && $days < 365) {
                $lock->release();
                Alert::error('Failed!', 'Contract have not matured, yet!');
                return redirect()->back();
            }

            $capital = $contract->principal + $contract->compounded;

            // Deduct the fee from Capital Amount
            $fee = $capital * 4 / 100;
            $amount = round($capital - $fee, 2, PHP_ROUND_HALF_DOWN);
            $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

            // Change contract properties (0 = Inactive, 1 = Active, 2 = Breaking, 3 = Ended)
            // Set next_yield_at for Crontab to pick, Strategy 1 = 48hrs, Strategy 2 = 7 days

            $release = '48 hours';
            $releaseDate = date('Y-m-d 00:00:00', strtotime('+2 days'));
            if ($contract->strategy == 2) {
                $release = '7 days';
                $releaseDate = date('Y-m-d 00:00:00', strtotime('+6 days'));
            }

            try {
                $contract->status = 2;
                $contract->principal = $amount;
                $contract->compounded = 0;
                $contract->next_yield_at = $releaseDate;
                $contract->save();
            } catch (\Throwable $th) {
                $lock->release();
                Alert::error('Failed!', 'Fail to break contract, please try again!');
                return redirect()->back();
            }

            // Send notification to supervisor
            $text = 'Break Contract emitted' . chr(10);
            $text .= 'User: ' . $user->username . chr(10);
            $text .= 'Contract ID: ' . $contract->id . chr(10);
            $text .= 'Amount: ' . $amount . chr(10);

            Telegram::sendMessage([
                'chat_id' => config('services.telegram.supervisor'),
                'text' => $text,
                'parse_mode' => 'markdown'
            ]);

            // Send half the fee as referrer bonus
            $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

            $lock->release();
            Alert::info('Contract breaked!', 'Contract\'s capital will be delivered to Yield within ' . $release)->persistent(true);
            return redirect()->back();
        } else {
            Alert::error('Error', 'Something is wrong, try again later.');
            return redirect()->back();
        }
    }
}
