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
use App\Model\Finance\_Yield;

class ContractController extends Controller
{
    public function getContractsPage()
    {
        $user = Auth::user();
        if ($user->is_active == 0) {
            Alert::warning('Inactive Account', 'You need to activate your account first');
            return redirect()->route('finance.account.activate');
        }
        // $contracts = null;
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

        return view('finance.contracts.new')
            ->with('title', 'Open New Contract')
            ->with(compact('USDTbalance'))
            ->with(compact('user'));
    }

    public function getContractDetailPage($contract_id)
    {
        $user = Auth::user();
        $contract = Contract::find($contract_id);
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
        sleep(2);
        $USDTbalance = $modelUSDTbalance->getUserNetUSDTbalance($user->id);

        $validated = $request->validate([
            'contract_strategy' => 'required|numeric|digits:1|not_in:0',
            'deposit' => 'required|numeric'
        ]);


        $strategy = $validated['contract_strategy'];
        $deposit = $validated['deposit'];
        $newUSDTbalance = $USDTbalance - $deposit;

        if ($newUSDTbalance < 0) {
            Alert::error('Failed!', 'Insufficient USDT Balance!');
            return redirect()->back();
        }

        if ($strategy == 1 && $deposit < 100) {

            Alert::error('Failed!', 'Minimum 100 USDT needed for this strategy!');
            return redirect()->back();
        }

        if ($strategy == 2 && $deposit < 10) {

            Alert::error('Failed!', 'Minimum 10 USDT needed for this strategy!');
            return redirect()->back();
        }

        $expired_at = null;
        if ($strategy == 2) {
            $expired_at = date('Y-m-d 00:00:00', strtotime('+365 days'));
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

        // Dispatch a 48hrs delayed job to Activate the Contract
        ActivateContractJob::dispatch($contract->id)->onQueue('mail')->delay(now()->addHours(48));

        // Credit the Referrer
        $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

        return redirect()->route('finance.contracts');
    }

    public function postContractCompound($contract_id)
    {
        $user = Auth::user();
        $contract = Contract::find($contract_id);

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        // Get current available yield from Yield object
        $modelYield = new _Yield;
        $yield = $modelYield->getContractYield($contract_id);

        // Check if yield net enough for action
        if ($yield->net < 1) {
            Alert::error('Oops!', 'Insuffiecient Yield amount for this action!');
            return redirect()->back();
        }

        // Deduct the fee from Compounding Amount
        $fee = $yield->net * 4 / 100;
        $amount = round($yield->net - $fee, 2, PHP_ROUND_HALF_DOWN);
        $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

        // Deduct the Yield and increment Compounded column on Contract
        $compounding = $modelYield->compound($contract_id, $amount);

        if ($compounding) {
            // Send half the fee as referrer bonus
            $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

            Alert::success('Success!', 'Yield just compounded successfully!');
            return redirect()->back();
        } else {
            Alert::error('Oops!', 'Something is wrong, Compounding Failed!');
            return redirect()->back();
        }
    }

    public function postContractWithdraw($contract_id)
    {
        $user = Auth::user();
        $contract = Contract::find($contract_id);

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        // Get current available yield from Yield object
        $modelYield = new _Yield;
        $yield = $modelYield->getContractYield($contract_id);

        // Check if yield net enough for action
        if ($yield->net < 1) {
            Alert::error('Oops!', 'Insuffiecient Yield amount for this action!');
            return redirect()->back();
        }

        // Deduct the fee from Withdraw Amount
        $fee = $yield->net * 4 / 100;
        $amount = round($yield->net - $fee, 2, PHP_ROUND_HALF_DOWN);
        $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

        // Deduct the Yield and increment Compounded column on Contract
        $withdraw = $modelYield->withdraw($contract_id, $amount, $fee);

        if ($withdraw) {
            // Send half the fee as referrer bonus
            $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

            Alert::success('Success!', 'Yield just withdrawed successfully!');
            return redirect()->back();
        } else {
            Alert::error('Oops!', 'Something is wrong, Withdraw Failed!');
            return redirect()->back();
        }
    }

    public function postContractBreak($contract_id) // (TO DO LIST!)
    {
        $user = Auth::user();
        $contract = Contract::find($contract_id);

        // Check if the caller is the rightful owner of this contract
        if ($contract->user_id != $user->id) {
            Alert::error('DANGER!', 'You\'re not the rightful owner of this contract!');
            return redirect()->back();
        }

        // Get current available yield from Yield object
        $modelYield = new _Yield;
        $yield = $modelYield->getContractYield($contract_id);

        // Deduct the fee from Withdraw Amount
        $fee = $yield->net * 4 / 100;
        $amount = round($yield->net - $fee, 2, PHP_ROUND_HALF_DOWN);
        $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

        // Deduct the Yield and increment Compounded column on Contract
        $withdraw = $modelYield->withdraw($contract_id, $amount, $fee);

        if ($withdraw) {
            // Send half the fee as referrer bonus
            $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

            Alert::success('Success!', 'Yield just withdrawed successfully!');
            return redirect()->back();
        } else {
            Alert::error('Oops!', 'Something is wrong, withdraw Failed!');
            return redirect()->back();
        }
    }
}
