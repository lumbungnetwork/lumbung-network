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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class CreditController extends Controller
{
    public function postConvertFromUSDT(Request $request)
    {
        $user = Auth::user();
        $modelUSDT = new USDTbalance;
        $USDTbalance = $modelUSDT->getUserNetUSDTbalance($user->id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'amount' => "required|numeric|min:2|max:$USDTbalance"
        ]);

        if ($validator->fails()) {
            Alert::error('Failed', $validator->errors()->first());
            return redirect()->back();
        }

        $amount = $request->amount;

        // Assess to prevent overflow
        $newUSDTbalance = $USDTbalance - $amount;
        if ($newUSDTbalance < 0) {
            Alert::error('FAILED!', 'Insufficient USDT Balance!');
            return redirect()->back();
        }

        try {
            // Substract USDTbalance
            $balance = new USDTbalance;
            $balance->user_id = $user->id;
            $balance->amount = $amount;
            $balance->type = 0;
            $balance->status = 1;
            $balance->save();
        } catch (\Throwable $th) {
            Alert::error('FAILED!', 'Fail to convert!');
            return redirect()->back();
        }

        // Add Credit Balance
        $credit = new Credit;
        $credit->user_id = $user->id;
        $credit->amount = $amount;
        $credit->type = 1;
        $credit->source = 4;
        $credit->tx_id = $this->createCreditTxId($amount, 1, 4, 0);
        $credit->save();

        Alert::success('Done!', 'USDT successfully converted to Credit');
        return redirect()->route('finance.wallet');
    }

    public function postConvertToUSDT(Request $request)
    {
        $user = Auth::user();
        $modelCredit = new Credit;
        $creditBalance = $modelCredit->getUserNetCreditBalance($user->id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'amount' => "required|numeric|min:2|max:$creditBalance"
        ]);

        if ($validator->fails()) {
            Alert::error('Failed', $validator->errors()->first());
            return redirect()->back();
        }

        $amount = $request->amount;

        // Assess to prevent overflow
        $newCreditBalance = $creditBalance - $amount;
        if ($newCreditBalance < 0) {
            Alert::error('FAILED!', 'Insufficient Credit Balance!');
            return redirect()->back();
        }

        // Calculate Fee (1% from amount or minimum $1)
        $fee = 1;
        $checkFee = $amount * 1 / 100;
        if ($checkFee > $fee) {
            $fee = $checkFee;
        }
        $referralBonus = round($fee / 2, 2, PHP_ROUND_HALF_DOWN);

        // Substract Credit Balance
        try {
            $credit = new Credit;
            $credit->user_id = $user->id;
            $credit->amount = $amount;
            $credit->type = 0;
            $credit->source = 4;
            $credit->tx_id = $this->createCreditTxId($amount, 0, 4, 0);
            $credit->save();
        } catch (\Throwable $th) {
            Alert::error('FAILED!', 'Fail to convert!');
            return redirect()->back();
        }

        // Add USDT Balance minus fee
        $balance = new USDTbalance;
        $balance->user_id = $user->id;
        $balance->amount = $amount - $fee;
        $balance->type = 1;
        $balance->status = 1;
        $balance->hash = 'Converted from Credit';
        $balance->save();

        // Send half of fee to referrer
        $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

        Alert::success('Done!', 'Successfuly converted Credit to USDT');
        return redirect()->route('finance.wallet');
    }

    public function postCreditTransfer(Request $request)
    {
        $user = Auth::user();
        $modelCredit = new Credit;
        $creditBalance = $modelCredit->getUserNetCreditBalance($user->id);

        // Transfer Fee (flat $0.3)
        $fee = 0.3;
        $referralBonus = $fee / 2;
        $maxTransfer = $creditBalance - $fee;

        // Validate input
        $validator = Validator::make($request->all(), [
            'receiver' => 'required|string|exists:finances,username',
            'amount' => "required|numeric|min:0.7|max:$maxTransfer",
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            Alert::error('Failed', $validator->errors()->first());
            return redirect()->back();
        }

        // Check password
        $check = Hash::check($request->password, $user->password);
        if ($check == false) {
            Alert::error('Failed', 'Wrong Password!');
            return redirect()->back();
        }

        $amount = $request->amount;
        $debit = $amount + $fee;
        $receiver = Finance::where('username', $request->receiver)->select('id')->first();

        // Prevent transfer to same username
        if ($receiver->id == $user->id) {
            Alert::error('Failed', 'Can not transfer to same username!');
            return redirect()->back();
        }

        // Use Atomic Lock to prevent race conditions
        $lock = Cache::lock('credit' . $user->id, 20);

        if ($lock->get()) {

            // Assert to prevent overflow
            $newCreditBalance = $creditBalance - $debit;
            if ($newCreditBalance < 0) {
                Alert::error('FAILED!', 'Insufficient Credit Balance!');
                return redirect()->back();
            }

            // Substract Credit Balance
            try {
                $credit = new Credit;
                $credit->user_id = $user->id;
                $credit->amount = $debit;
                $credit->type = 0;
                $credit->source = 3;
                $credit->source_id = $receiver->id;
                $credit->tx_id = $this->createCreditTxId($debit, 0, 3, $receiver->id);
                $credit->save();
            } catch (\Throwable $th) {
                Alert::error('FAILED!', 'Fail to transfer!');
                return redirect()->back();
            }

            // Add Credit Balance ($amount) to receiver
            $balance = new Credit;
            $balance->user_id = $receiver->id;
            $balance->amount = $amount;
            $balance->type = 1;
            $balance->source = 3;
            $balance->source_id = $user->id;
            $balance->tx_id = $this->createCreditTxId($amount, 1, 3, $user->id);
            $balance->save();

            // Send half of fee to referrer
            $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

            $lock->release();
        }

        Alert::success('Done!', 'Transfered ' . $amount . ' Credit to ' . $request->receiver)->persistent(true);
        return redirect()->route('finance.wallet');
    }
}
