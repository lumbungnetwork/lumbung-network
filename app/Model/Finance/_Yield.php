<?php

namespace App\Model\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\Finance\Contract;
use App\Model\Finance\Credit;
use App\Http\Controllers\Controller;

class _Yield extends Model
{

    protected $table = 'yields';

    public function contract()
    {
        return $this->belongsTo('App\Model\Finance\Contract', 'user_id');
    }

    public function getContractYield($contract_id)
    {
        try {
            $balance = DB::table('yields')->selectRaw('
                sum(case when type = 0 then amount else 0 end) as debit,
                sum(case when type = 1 then amount else 0 end) as credit
            ')->where('contract_id', $contract_id)
                ->first();
            $net = round($balance->credit - $balance->debit, 2, PHP_ROUND_HALF_DOWN);
            $yield = (object) [
                'credit' => $balance->credit,
                'debit' => $balance->debit,
                'net' => $net
            ];
        } catch (\Throwable $ex) {
            $message = $ex->getMessage();
            $yield = (object) [
                'credit' => 0,
                'debit' => 0,
                'net' => 0
            ];
        }
        return $yield;
    }

    public function getUserTotalYields($user_id)
    {
        $contracts = Contract::where('user_id', $user_id)->get();

        if (count($contracts) == 0) {
            $yields = (object) [
                'earned' => 0,
                'available' => 0
            ];

            return $yields;
        }

        $availableYields = 0;
        $earnedYields = 0;

        foreach ($contracts as $contract) {
            $yield = $this->getContractYield($contract->id);
            $availableYields += $yield->net;
            $earnedYields += $yield->credit;
        }

        $yields = (object) [
            'earned' => $earnedYields,
            'available' => $availableYields
        ];

        return $yields;
    }

    public function compound($contract_id, $yield, $amount)
    {
        try {
            // Create new negative Yield
            $compounded = new _Yield;
            $compounded->contract_id = $contract_id;
            $compounded->amount = $yield;
            $compounded->type = 0;
            $compounded->action = 1; // 0 = withdraw, 1 = compound
            $compounded->save();

            // Increment compounded value to Contract
            $contract = Contract::find($contract_id);
            $contract->compounded += $amount;
            $contract->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function withdraw($contract_id, $amount, $fee)
    {
        $contract = Contract::find($contract_id);
        $controller = new Controller;
        try {
            // Create new negative Yield
            $withdraw = new _Yield;
            $withdraw->contract_id = $contract_id;
            $withdraw->amount = $amount + $fee;
            $withdraw->type = 0;
            $withdraw->action = 0; // 0 = withdraw, 1 = compound
            $withdraw->save();

            // Create new positive Credit
            $tx_id = $controller->createCreditTxId($amount, 1, 2, 0);
            $credit = new Credit;
            $credit->user_id = $contract->user_id;
            $credit->amount = $amount;
            $credit->type = 1;
            $credit->source = 2;
            $credit->source_id = $contract->id;
            $credit->tx_id = $tx_id;
            $credit->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
