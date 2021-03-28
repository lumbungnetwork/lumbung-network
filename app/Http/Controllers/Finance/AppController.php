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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Model\Finance\Credit;
use App\Model\Finance\_Yield;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Config;

class AppController extends Controller
{
    public function getFinanceDashboard()
    {
        $user = Auth::user();
        $modelYield = new _Yield;
        $modelContract = new Contract;
        $contracts = null;
        $userContracts = Contract::where('user_id', $user->id)->get();
        $referrals = Finance::where('sponsor_id', $user->id)->select('id')->count();
        if (count($userContracts) > 0) {
            $contracts = $userContracts;
        }
        $yields = $modelYield->getUserTotalYields($user->id);
        $totalLiquidity = $modelContract->getUserTotalLiquidity($user->id);
        return view('finance.dashboard')
            ->with('title', 'Dashboard')
            ->with(compact('contracts'))
            ->with(compact('referrals'))
            ->with(compact('yields'))
            ->with(compact('totalLiquidity'))
            ->with(compact('user'));
    }

    // Wallet
    public function getWallet()
    {
        $user = Auth::user();

        // Check if TRON address set
        if ($user->tron == null) {
            Alert::warning('Oops', 'You need to bind a TRON address first!');
            return redirect()->route('finance.account.addresses');
        }

        $modelUSDTbalance = new USDTbalance;
        $modelCredit = new Credit;
        $USDTbalance = $modelUSDTbalance->getUserNetUSDTbalance($user->id);
        $creditBalance = $modelCredit->getUserNetCreditBalance($user->id);
        if ($user->loginSecurity == null) {
            Alert::info('About Security', "It's strongly recommended to activate 2FA to protect your account and fund!");
        }
        return view('finance.wallet')
            ->with('title', 'Wallet')
            ->with(compact('USDTbalance'))
            ->with(compact('creditBalance'))
            ->with(compact('user'));
    }

    public function getWalletDepositUSDT()
    {
        $user = Auth::user();
        $modelUSDTbalance = new USDTbalance;
        $USDTbalance = $modelUSDTbalance->getUserNetUSDTbalance($user->id);

        return view('finance.wallet.deposit-usdt')
            ->with('title', 'Deposit USDT')
            ->with(compact('USDTbalance'))
            ->with(compact('user'));
    }

    public function getTest()
    {
        $user = Auth::user();

        return view('finance.wallet.test-decode')
            ->with('title', 'Deposit TEST')
            ->with(compact('user'));
    }

    // Contracts
    // moved to ContractController

    // Account
    public function getAccountPage()
    {
        $user = Auth::user();

        return view('finance.account')
            ->with('title', 'Account')
            ->with(compact('user'));
    }

    public function getAccountCredentialsPage()
    {
        $user = Auth::user();

        return view('finance.account.credentials')
            ->with('title', 'Credentials')
            ->with(compact('user'));
    }

    public function getAccountAddressesPage()
    {
        $user = Auth::user();

        return view('finance.account.addresses')
            ->with('title', 'Addresses')
            ->with(compact('user'));
    }

    public function postAccountSetTron(Request $request)
    {

        $validated = $request->validate([
            'tron' => 'string|size:34|required'
        ]);
        $user = Auth::user();
        $user = Finance::find($user->id);
        if ($user->tron == null) {
            $user->tron = $validated['tron'];
            $user->save();
            Alert::success('Success!', 'Tron Address successfully set!');
        } else {
            Alert::error('Oops!', 'Tron Address already set!');
        }

        return redirect()->route('finance.account.addresses')
            ->with('title', 'Addresses')
            ->with(compact('user'));
    }

    public function postAccountResetTron(Request $request)
    {
        $user = Auth::user();
        $user = Finance::find($user->id);
        if ($user->tron == null) {
            Alert::warning('Reset!', 'Tron Address is already reset!');
        } else {
            $user->tron = null;
            $user->save();
            Alert::success('Reset!', 'Tron Address has been reset!');
        }

        return redirect()->route('finance.account.addresses')
            ->with('title', 'Addresses')
            ->with(compact('user'));
    }

    public function postUSDTWithdraw(Request $request)
    {
        $user = Auth::user();
        $modelUSDT = new USDTbalance;
        $USDTbalance = $modelUSDT->getUserNetUSDTbalance($user->id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'amount' => "required|numeric|min:2|max:$USDTbalance",
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

        // Assess to prevent overflow
        $newUSDTBalance = $USDTbalance - $amount;
        if ($newUSDTBalance < 0) {
            Alert::error('FAILED!', 'Insufficient USDT Balance!');
            return redirect()->back();
        }

        // Transfer Fee (flat $1)
        $fee = 1;

        try {
            // Substract USDTbalance
            $balance = new USDTbalance;
            $balance->user_id = $user->id;
            $balance->amount = $amount;
            $balance->type = 0;
            $balance->status = 1;
            $balance->save();
        } catch (\Throwable $th) {
            Alert::error('FAILED!', 'Fail to withdraw!');
            return redirect()->back();
        }

        // Second assert
        $afterUSDTbalance = $modelUSDT->getUserNetUSDTbalance($user->id);
        if ($afterUSDTbalance != $newUSDTBalance) {
            Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => 'Anomaly in USDT Withdraw UserID: ' . $user->id,
                'parse_mode' => 'markdown'
            ]);
        }

        $net = $amount - $fee;

        $text = 'USDT Withdraw Request' . chr(10);
        $text .= 'Username: ' . $user->username . chr(10);
        $text .= 'TRON Addr: ' . $user->tron . chr(10);
        $text .= 'Amount: ' . $net . chr(10);

        Telegram::sendMessage([
            'chat_id' => Config::get('services.telegram.overlord'),
            'text' => $text,
            'parse_mode' => 'markdown'
        ]);

        Alert::success('Done!', 'Withdrawal requested, will be processed within an hour.');
        return redirect()->back();
    }
}