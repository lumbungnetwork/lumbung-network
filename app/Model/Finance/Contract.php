<?php

namespace App\Model\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contract extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Finance', 'user_id');
    }

    public function yield()
    {
        return $this->hasMany('App\Model\Finance\Yield', 'contract_id', 'id');
    }

    public function getUserTotalLiquidity($id)
    {
        try {
            $liquidity = DB::table('contracts')->selectRaw('
                sum(case when status < 2 then principal else 0 end) as principal,
                sum(case when status = 1 then compounded else 0 end) as compounded
            ')->where('user_id', $id)
                ->first();
            $totalLiquidity = $liquidity->principal + $liquidity->compounded;
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $totalLiquidity = 0;
        }
        return $totalLiquidity;
    }
}
