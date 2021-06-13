<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Model;

class BonusRoyalty extends Model
{
    protected $fillable = ['user_id', 'from_user_id', 'amount', 'level_id', 'bonus_date', 'status', 'hash'];

    /*
    * Check Matrix Limit on Bonus Royalty for a sponsor (user)
    * Base = 4
    * Matrix Limit = pow(base, level)
    * @return boolean
    */
    public function CheckBonusRoyaltyMatrixLimit($user_id, $level)
    {
        $return = true;
        $count = $this->where('user_id', '=', $user_id)
            ->where('bonus_date', '>=', date('Y-m-01 00:00:00', strtotime('this month')))
            ->where('bonus_date', '<=', date('Y-m-t 23:59:01', strtotime('this month')))
            ->where('level_id', $level)
            ->count();
        if ($count >= pow(4, $level)) {
            $return = false;
        }

        return $return;
    }
}
