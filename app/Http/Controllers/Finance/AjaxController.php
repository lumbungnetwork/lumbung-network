<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TronController;
use Illuminate\Http\Request;
use App\Model\Finance\USDTbalance;
use App\Model\Finance\Credit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Config;

class AjaxController extends Controller
{
    public function postVerifyUSDTDeposit(Request $request)
    {
        $user = Auth::user();
        $tron = $this->getTron();
        $USDTcontractAddress = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';
        $hash = $request->hash;

        // Check if tx_id already used before
        $checkUsedHash = USDTbalance::select('hash')->where('hash', $hash)->exists();
        if ($checkUsedHash) {
            return response()->json(['success' => false, 'message' => 'Transaction already recorded!']);
        }

        // Record the deposit request
        USDTbalance::create([
            'user_id' => $user->id,
            'amount' => 0,
            'type' => 1,
            'hash' => $hash
        ]);

        sleep(10);
        $response = $tron->getTransaction($hash);
        $status = $response['ret'][0]['contractRet'];
        $contractAddress = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['contract_address']);
        $data = $response['raw_data']['contract'][0]['parameter']['value']['data'];

        if ($status == "SUCCESS") {
            if ($contractAddress == $USDTcontractAddress) {
                return response()->json(['success' => true, 'message' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'Wrong USDT token!']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Transaction Failed!']);
        }
    }

    public function postValidateUSDTDeposit(Request $request)
    {
        $user = Auth::user();
        $data = json_decode($request->data, true);
        $hash = $request->hash;
        $lumbungFinanceAddress = 'TKzyAPds4Au5oReKNM6FAbRecaq23vx4kd';
        $tron = $this->getTron();

        $method = $data['name'];
        $receiver = $tron->fromHex($data['params']['_to']);
        $amountHex = str_replace('0x', '', $data['params']['_value']['_hex']);
        $amount = base_convert($amountHex, 16, 10) / 1000000;

        if ($method == 'transfer') {
            if ($receiver == $lumbungFinanceAddress) {
                $deposit = USDTbalance::where('hash', $hash)->first();
                $deposit->amount = $amount;
                $deposit->status = 1;
                $deposit->save();

                $message_text = 'USDT Deposit (LF)' . chr(10);
                $message_text .= 'Username: ' . $user->username . chr(10);
                $message_text .= 'Amount: $' . number_format($amount) . chr(10);

                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => $message_text,
                    'parse_mode' => 'markdown'
                ]);
                return response()->json(['success' => true, 'message' => 'success']);
            } else {
                return response()->json(['success' => false, 'message' => 'Wrong recipient address!']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Wrong contract transaction method!']);
        }
    }

    // Convert Credits
    public function getConvertFromUSDT()
    {
        $user = Auth::user();
        $modelUSDT = new USDTbalance;
        $USDTbalance = $modelUSDT->getUserNetUSDTbalance($user->id);
        $formRoute = 'finance.credit.postConvertFromUSDT';

        return view('finance.ajax.getConvertCredit')
            ->with('type', 1)
            ->with('route', $formRoute)
            ->with('modalTitle', 'Convert USDT to Credit')
            ->with('balance', $USDTbalance);
    }

    public function getConvertToUSDT()
    {
        $user = Auth::user();
        $modelCredit = new Credit;
        $creditBalance = $modelCredit->getUserNetCreditBalance($user->id);
        $formRoute = 'finance.credit.postConvertToUSDT';

        return view('finance.ajax.getConvertCredit')
            ->with('type', 2)
            ->with('route', $formRoute)
            ->with('modalTitle', 'Convert Credit to USDT')
            ->with('balance', $creditBalance);
    }

    public function getCreditTransfer()
    {
        $user = Auth::user();
        $modelCredit = new Credit;
        $creditBalance = $modelCredit->getUserNetCreditBalance($user->id);
        $formRoute = 'finance.credit.postCreditTransfer';

        return view('finance.ajax.getCreditTransfer')
            ->with('route', $formRoute)
            ->with('modalTitle', 'Transfer Credit')
            ->with('balance', $creditBalance);
    }

    public function getUSDTWithdraw()
    {
        $user = Auth::user();
        $modelUSDT = new USDTbalance;
        $USDTbalance = $modelUSDT->getUserNetUSDTbalance($user->id);
        $formRoute = 'finance.ajax.postUSDTWithdraw';

        return view('finance.ajax.getUSDTWithdraw')
            ->with('route', $formRoute)
            ->with('modalTitle', 'Withdraw USDT')
            ->with('tron', $user->tron)
            ->with('balance', $USDTbalance);
    }

    // public function getAccountTelegram()
    // {
    //     $user = Auth::user();
    //     $chat_id = null;
    //     if ($user->chat_id != null) {
    //         $chat_id = $user->chat_id;
    //     }

    //     return 
    // }

    public function getPlatformLiquidity()
    {
        $platformLiquidity = Cache::remember('platformLiquidity', 900, function () {
            $tronController = new TronController;
            $ownerAddress = 'TCeqDdaXxvQr25TUjmA1rk3aZLj53DAqN5';
            $jBTCaddress = 'TLeEu311Cbw63BcmMHDgDLu7fnk9fqGcqT';
            $jTRXaddress = 'TE2RzoSV3wFK99w6J9UnnZ4vLfXYoxvRwP';
            $jETHaddress = 'TR7BUFRQeq1w5jAZf1FKx85SHuX6PfMqsV';
            $jUSDJaddress = 'TL5x9MtSnDy537FXKx53yAaHRRNdg9TkkA';

            // Get USD Prices
            $prices = $tronController->getPriceFeeds();

            $BTCprice = $prices['bitcoin']['usd'];
            $TRXprice = $prices['tron']['usd'];
            $ETHprice = $prices['ethereum']['usd'];
            $USDJprice = $prices['just-stablecoin']['usd'];

            // Get supplied balance from jTokens balances (divide by 100)
            $BTCbalance = $tronController->getTRC20Balance($ownerAddress, $jBTCaddress, 8) / 100;
            $TRXbalance = $tronController->getTRC20Balance($ownerAddress, $jTRXaddress, 8) / 100;
            $ETHbalance = $tronController->getTRC20Balance($ownerAddress, $jETHaddress, 8) / 100;
            $USDJbalance = $tronController->getTRC20Balance($ownerAddress, $jUSDJaddress, 8) / 100;

            $BTCvalue = round($BTCbalance * $BTCprice, 2);
            $TRXvalue = round($TRXbalance * $TRXprice, 2);
            $ETHvalue = round($ETHbalance * $ETHprice, 2);
            $USDJvalue = round($USDJbalance * $USDJprice, 2);

            $totalValue = $BTCvalue + $TRXvalue + $ETHvalue + $USDJvalue;

            return [
                'btc_balance' => $BTCbalance,
                'trx_balance' => $TRXbalance,
                'eth_balance' => $ETHbalance,
                'usdj_balance' => $USDJbalance,
                'btc_value' => $BTCvalue,
                'trx_value' => $TRXvalue,
                'eth_value' => $ETHvalue,
                'usdj_value' => $USDJvalue,
                'total_value' => $totalValue
            ];
        });

        return response()->json(['success' => true, 'data' => $platformLiquidity]);
    }
}
