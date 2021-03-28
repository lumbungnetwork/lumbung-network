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

        // Validate input
        $validator = Validator::make($request->all(), [
            'receiver' => 'required|string|exists:finances,username',
            'amount' => "required|numeric|min:2|max:$creditBalance",
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
        $receiver = Finance::where('username', $request->receiver)->select('id')->first();

        // Assess to prevent overflow
        $newCreditBalance = $creditBalance - $amount;
        if ($newCreditBalance < 0) {
            Alert::error('FAILED!', 'Insufficient Credit Balance!');
            return redirect()->back();
        }

        // Transfer Fee (flat $1)
        $fee = 1;
        $referralBonus = 0.5;

        // Substract Credit Balance
        try {
            $credit = new Credit;
            $credit->user_id = $user->id;
            $credit->amount = $amount;
            $credit->type = 0;
            $credit->source = 3;
            $credit->source_id = $receiver->id;
            $credit->tx_id = $this->createCreditTxId($amount, 0, 3, $receiver->id);
            $credit->save();
        } catch (\Throwable $th) {
            Alert::error('FAILED!', 'Fail to transfer!');
            return redirect()->back();
        }

        // Add Credit Balance minus fee to receiver
        $balance = new Credit;
        $balance->user_id = $receiver->id;
        $balance->amount = $amount - $fee;
        $balance->type = 1;
        $balance->source = 3;
        $balance->source_id = $user->id;
        $balance->tx_id = $this->createCreditTxId($amount, 1, 3, $user->id);
        $balance->save();

        // Send half of fee to referrer
        $this->creditReferralBonus($user->id, $user->sponsor_id, $referralBonus);

        Alert::success('Done!', 'Transfered ' . ($amount - $fee) . ' Credit to ' . $request->receiver);
        return redirect()->route('finance.wallet');
    }
}
