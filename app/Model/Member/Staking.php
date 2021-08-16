<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staking extends Model
{
    use HasFactory;
    protected $table = 'staking';

    /**
     * Get Total Staked LMB on the platform (eligible for dividend)
     * @return int|float Staked amount
     */ 
    public function getStakedLMB()
    {
        $staked = $this->selectRaw('
                        sum(case when type = 1 then amount else - amount end) as net
                    ')->first();

        return round($staked->net, 2);
    }

    /**
     * Get a User's Staked LMB (eligible for dividend)
     *
     * @param integer $id
     */ 
    public function getUserStakedLMB($id)
    {   
        $staked = $this->selectRaw('
                        sum(case when type = 1 then amount else - amount end) as net
                    ')->where('user_id', $id)
                    ->first();

        return round($staked->net, 2);
    }

    /**
     * Get All Users with Staked LMB net amount
     *
     * @return collection
     */ 
    public function getAllStakers()
    {
        return $this->selectRaw('
                    user_id,
                    sum(case when type = 1 then amount else - amount end) as net
                ')->groupBy('user_id')->get();
    }

    /**
     * Generate Staking Leaderboard from All Staking Users ordered by Staked LMB amount (along with their username)
     *
     * @return collection
     */ 
    public function getAllStakersLeaderboard()
    {
        return $this->join('users', 'staking.user_id', '=', 'users.id')
                ->selectRaw('
                    sum(case when staking.type = 1 then staking.amount else - staking.amount end) as net,
                    users.username
                ')
                ->groupBy('staking.user_id')
                ->orderByDesc('net')
                ->paginate();
    }

    
    /**
     * Get User's Unstake progress (if any, returns null when no Unstaking in progress)
     *
     * @param int User's id
     */ 
    public function getUserUnstakeProgress($id)
    {
        $unstake = DB::table('unstake')->selectRaw('
		            sum(case when status = 0 then amount else 0 end) as processing
                ')
                ->where('user_id', $id)
                ->first();
        return $unstake->processing;
    }

}
