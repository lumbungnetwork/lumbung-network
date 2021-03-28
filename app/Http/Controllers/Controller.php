<?php

namespace App\Http\Controllers;

use App\Model\Pin;
use App\Model\Transaction;
use App\Model\Bonus;
use App\Model\Transferwd;
use App\Model\Finance\Credit;
use App\Model\Finance\Contract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Exception\TronException;
use IEXBase\TronAPI\Provider\HttpProvider;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getTron()
    {
        $fullNode = new HttpProvider('https://api.tronstack.io');
        $solidityNode = new HttpProvider('https://api.tronstack.io');
        $eventServer = new HttpProvider('https://api.tronstack.io');

        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        return $tron;
    }

    public function getTronLocalWallet($pk)
    {
        $fullNode = new HttpProvider('https://api.tronstack.io');
        $solidityNode = new HttpProvider('https://api.tronstack.io');
        $eventServer = new HttpProvider('https://api.tronstack.io');

        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer, $signServer = null, $explorer = null, $pk);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        return $tron;
    }

    public function getVendorAvailableDeposit($vendor_id)
    {
        $modelTrans = new Transaction;
        $modelPin = new Pin;

        $getTotalDeposit = $modelPin->getVendorDepositBalance($vendor_id);
        $getTotalDepositWithdrawn = $modelTrans->getVendorTotalDepositWithdrawn($vendor_id);
        $getOnTheFlyDeposit = $modelPin->getPPOBFly($vendor_id);

        $total_credits = 0;
        $total_debits = 0;
        $total_withdrawn = 0;
        $total_onTheFly = 0;

        if ($getTotalDeposit->credits != null) {
            $total_credits = $getTotalDeposit->credits;
        }
        if ($getTotalDeposit->debits != null) {
            $total_debits = $getTotalDeposit->debits;
        }
        if ($getTotalDepositWithdrawn->total != null) {
            $total_withdrawn = $getTotalDepositWithdrawn->total;
        }
        if ($getOnTheFlyDeposit->deposit_out != null) {
            $total_onTheFly = $getOnTheFlyDeposit->deposit_out;
        }

        $available_balance = $total_credits - $total_debits - $total_withdrawn - $total_onTheFly;

        return $available_balance;
    }

    public function getMemberAvailableBonus($user_id)
    {
        $dataUser = (object) array(
            'id' => $user_id
        );

        $bonus = new Bonus;
        $transfered = new Transferwd;

        //daily bonus

        $getTotalBonus = $bonus->getTotalBonus($dataUser);
        $totalWD = $transfered->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $transfered->getTotalDiTransfereIDR($dataUser);

        $dailyWithdrawn = $totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin + $totalWDeIDR->total_wd + $totalWDeIDR->total_tunda + $totalWDeIDR->total_fee_admin;
        $totalAvailable = $getTotalBonus->total_bonus - $dailyWithdrawn;


        //royalti bonus
        $totalBonusRoyalti = $bonus->getTotalBonusRoyalti($dataUser);
        $totalWDroyalti = $transfered->getTotalDiTransferRoyalti($dataUser);
        $totalWDroyaltieIDR = $transfered->getTotalDiTransferRoyaltieIDR($dataUser);

        $royaltiWithdrawn = $totalWDroyalti->total_wd + $totalWDroyalti->total_tunda + $totalWDroyalti->total_fee_admin + $totalWDroyaltieIDR->total_wd + $totalWDroyaltieIDR->total_tunda + $totalWDroyaltieIDR->total_fee_admin;
        $totalAvailableRoyaltiBonus = $totalBonusRoyalti->total_bonus - $royaltiWithdrawn;

        return (object) array(
            'daily_bonus' => $totalAvailable,
            'daily_withdrawn' => $dailyWithdrawn,
            'royalti_bonus' => $totalAvailableRoyaltiBonus,
            'royalti_withdrawn' => $royaltiWithdrawn
        );
    }

    public function createCreditTxId($amount, $type, $source, $source_id)
    {
        $count = Credit::where('created_at', '>', date('Y-m-d 00:00:00'))->count();
        $string = $amount . ' ' . $type . ' ' . $source . ' ' . $source_id . ' ' . floor($count + 1);
        $hex = bin2hex($string);
        $tx_id = sprintf("0x%034s", $hex);
        return $tx_id;
    }

    public function creditReferralBonus($user_id, $sponsor_id, $amount)
    {
        // Check if sponsor have at least $100 active Liquidity
        $modelContract = new Contract;
        $check = $modelContract->getUserTotalLiquidity($sponsor_id);
        if ($check < 100) {
            return true; // Deny the Referral Bonus
        }

        try {
            // Create tx_id
            $tx_id = $this->createCreditTxId($amount, 1, 1, $user_id);

            // Credit the Referrer
            $credit = new Credit;
            $credit->user_id = $sponsor_id;
            $credit->amount = $amount;
            $credit->type = 1;
            $credit->source = 1;
            $credit->tx_id = $tx_id;
            $credit->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
