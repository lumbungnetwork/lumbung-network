<?php

namespace App\Http\Controllers;

use App\Finance;
use App\Model\Finance\Credit;
use App\Model\Finance\Contract;
use Illuminate\Support\Facades\DB;
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

    /**
     * Check if a transaction ID (Hash) has used (exist in DB)
     *
     * @param string $hash txid
     * @param string $table name
     * @param string $column name
     * @return bool
     */
    public function checkUsedHashExist($hash, $table, $column)
    {
        return DB::table($table)
            ->where($column, $hash)
            ->exists();
    }

    /**
     * Instantiate Tron object with pre-defined $fullNode, $solidityNode, and $eventServer
     *
     * @return object
     * @throws TronException
     */
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

    /**
     * Generate Credit unique txid
     *
     * @param int|float $amount
     * @param int $type
     * @param int $source
     * @param int $source_id
     * @return string
     */
    public function createCreditTxId($amount, $type, $source, $source_id)
    {
        $count = Credit::where('created_at', '>', date('Y-m-d 00:00:00'))->count();
        $string = $amount . ' ' . $type . ' ' . $source . ' ' . $source_id . ' ' . floor($count + 1);
        $hex = bin2hex($string);
        $tx_id = sprintf("0x%034s", $hex);
        return $tx_id;
    }

    /**
     * Generate Referral Bonus to 4 levels above a transacting user
     *
     * @param int $user_id
     * @param int $sponsor_id
     * @param int|float $amount
     * @return void
     */
    public function creditReferralBonus($user_id, $sponsor_id, $amount)
    {
        $modelContract = new Contract;
        // Get 4 levels uplines
        $lv1 = Finance::where('id', $sponsor_id)->select('id', 'sponsor_id')->first();
        $lv2 = Finance::where('id', $lv1->sponsor_id)->select('id', 'sponsor_id')->first();
        $lv3 = Finance::where('id', $lv2->sponsor_id)->select('id', 'sponsor_id')->first();
        $lv4 = Finance::where('id', $lv3->sponsor_id)->select('id', 'sponsor_id')->first();

        $amount1 = round($amount / 2, 6, PHP_ROUND_HALF_DOWN); // 50%
        $amount2 = round($amount * (3 / 10), 6, PHP_ROUND_HALF_DOWN); // 30%
        $amount3 = round($amount * (1 / 10), 6, PHP_ROUND_HALF_DOWN); // 10%
        $amount4 = round($amount * (1 / 10), 6, PHP_ROUND_HALF_DOWN); // 10%

        // Check Lv.1
        $check1 = $modelContract->getUserTotalLiquidity($lv1->id);
        if ($check1 >= 100 && $amount1 > 0) {
            try {
                // Create tx_id
                $tx_id = $this->createCreditTxId($amount1, 1, 1, $user_id);

                // Credit the Referrer
                $credit = new Credit;
                $credit->user_id = $lv1->id;
                $credit->amount = $amount1;
                $credit->type = 1;
                $credit->source = 1;
                $credit->source_id = $user_id;
                $credit->tx_id = $tx_id;
                $credit->save();
            } catch (\Throwable $th) {
                //nope
            }
        }
        // Check Lv.2
        $check2 = $modelContract->getUserTotalLiquidity($lv2->id);
        if ($check2 >= 100 && $amount2 > 0) {
            try {
                // Create tx_id
                $tx_id = $this->createCreditTxId($amount2, 1, 1, $user_id);

                // Credit the Referrer
                $credit = new Credit;
                $credit->user_id = $lv2->id;
                $credit->amount = $amount2;
                $credit->type = 1;
                $credit->source = 1;
                $credit->source_id = $user_id;
                $credit->tx_id = $tx_id;
                $credit->save();
            } catch (\Throwable $th) {
                //nope
            }
        }
        // Check Lv.3
        $check3 = $modelContract->getUserTotalLiquidity($lv3->id);
        if ($check3 >= 100 && $amount3 > 0) {
            try {
                // Create tx_id
                $tx_id = $this->createCreditTxId($amount3, 1, 1, $user_id);

                // Credit the Referrer
                $credit = new Credit;
                $credit->user_id = $lv3->id;
                $credit->amount = $amount3;
                $credit->type = 1;
                $credit->source = 1;
                $credit->source_id = $user_id;
                $credit->tx_id = $tx_id;
                $credit->save();
            } catch (\Throwable $th) {
                //nope
            }
        }
        // Check Lv.4
        $check4 = $modelContract->getUserTotalLiquidity($lv4->id);
        if ($check4 >= 100 && $amount4 > 0) {
            try {
                // Create tx_id
                $tx_id = $this->createCreditTxId($amount4, 1, 1, $user_id);

                // Credit the Referrer
                $credit = new Credit;
                $credit->user_id = $lv4->id;
                $credit->amount = $amount4;
                $credit->type = 1;
                $credit->source = 1;
                $credit->source_id = $user_id;
                $credit->tx_id = $tx_id;
                $credit->save();
            } catch (\Throwable $th) {
                //nope
            }
        }
    }
}
