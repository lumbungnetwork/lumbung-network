<?php

namespace App\Http\Controllers;

use App\Model\Pin;
use App\Model\Transaction;
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
        $fullNode = new HttpProvider('https://super.guildchat.io');
        $solidityNode = new HttpProvider('https://solidity.guildchat.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');

        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        return $tron;
    }

    public function getTronLocalWallet($pk)
    {
        $fullNode = new HttpProvider('https://super.guildchat.io');
        $solidityNode = new HttpProvider('https://solidity.guildchat.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');

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
}
