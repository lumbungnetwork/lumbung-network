<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;

class LMBdividend extends Model
{
    use HasFactory;
    protected $table = 'lmb_dividend';

    /*
    * Get LMB Dividend Pool with Cache function to reduce queries in adjacent time
    */
    public function getLMBDividendPool()
    {
        return Cache::remember('lmb_div_pool', 900, function () {
            $credits = $this->where('status', 1)->sum('amount');
            $debits = $this->where('status', 0)->sum('amount');

            return $credits - $debits;
        });
    }
}
