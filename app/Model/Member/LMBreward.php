<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LMBreward extends Model
{
    use HasFactory;

    protected $table = 'lmb_rewards';

    protected $fillable = ['user_id', 'amount', 'sales', 'type', 'date'];

    /*
    |--------------------------------------------------------------------------
    | Get User Net LMB Reward
    |--------------------------------------------------------------------------
    | @param $user_id
    | @param $is_store (0 = Shopping Reward, 1 = Selling Reward)
    */
    public function getUserNetLMBReward($user_id)
    {
        try {
            $balance = DB::table('lmb_rewards')->selectRaw('
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
}
