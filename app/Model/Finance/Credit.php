<?php

namespace App\Model\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Credit extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Finance', 'user_id');
    }

    public function getUserNetCreditBalance($user_id)
    {
        try {
            $balance = DB::table('credits')->selectRaw('
                sum(case when type = 0 then amount else 0 end) as debit,
                sum(case when type = 1 then amount else 0 end) as credit
            ')->where('user_id', $user_id)
                ->first();
            $net = round($balance->credit - $balance->debit, 2, PHP_ROUND_HALF_DOWN);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $net = 0;
        }
        return $net;
    }

    public function getUserCreditHistory($user_id)
    {
        $sql = DB::table('credits')
            ->leftJoin('finances', 'credits.source_id', '=', 'finances.id')
            ->selectRaw('credits.amount, credits.type, credits.source, credits.source_id, credits.created_at, finances.username as username')
            ->where('user_id', $user_id)
            ->orderBy('credits.created_at', 'desc')
            ->take(30)
            ->get();

        return $sql;
    }
}
