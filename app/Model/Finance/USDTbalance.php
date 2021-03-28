<?php

namespace App\Model\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class USDTbalance extends Model
{
    protected $fillable = ['user_id', 'amount', 'type', 'hash'];

    protected $table = 'usdt_balances';

    public function user()
    {
        return $this->belongsTo('App\Finance', 'user_id');
    }

    public function getUserNetUSDTbalance($id)
    {
        try {
            $balance = DB::table('usdt_balances')->selectRaw('
                sum(case when type = 0 then amount else 0 end) as debit,
                sum(case when type = 1 then amount else 0 end) as credit
            ')->where('user_id', $id)
                ->where('status', 1)
                ->first();
            $net = round($balance->credit - $balance->debit, 2, PHP_ROUND_HALF_DOWN);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $net = 0;
        }
        return $net;
    }
}
