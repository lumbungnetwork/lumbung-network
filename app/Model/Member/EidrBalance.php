<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EidrBalance extends Model
{
    use HasFactory;

    public function getUserNeteIDRBalance($user_id)
    {
        try {
            $balance = $this->selectRaw('
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
