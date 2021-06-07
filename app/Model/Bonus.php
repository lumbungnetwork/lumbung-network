<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Bonus extends Model
{
    public function checkUsedHashExist($hash, $table, $column)
    {
        $sql = DB::table($table)
            ->where($column, $hash)
            ->exists();
        return $sql;
    }

    public function insertLMBDividend($data)
    {
        try {
            DB::table('lmb_dividend')->insertOrIgnore($data);
            $result = (object) array('status' => true);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getLMBDividendPool()
    {
        $pool = Cache::remember('lmb_div_pool', 900, function () {
            try {
                $div = DB::table('lmb_dividend')->selectRaw('
		sum(case when status = 0 then amount else 0 end) as debits,
		sum(case when status = 1 then amount else 0 end) as credits
                    ')
                    ->first();
                $result = $div->credits - $div->debits;
            } catch (Throwable $ex) {
                $result = 0;
            }
            return $result;
        });

        return $pool;
    }

    public function insertUserDividend($data)
    {
        try {
            $lastInsertedID = DB::table('users_dividend')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function updateUserDividend($fieldName, $name, $data)
    {
        try {
            DB::table('users_dividend')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getUserDividend($id)
    {
        try {
            $div = DB::table('users_dividend')->selectRaw('
                sum(case when type = 0 then amount else 0 end) as claimed,
                sum(case when type = 1 then amount else 0 end) as rewarded
            ')->where('user_id', $id)
                ->first();
            $net = round($div->rewarded - $div->claimed, 2, PHP_ROUND_HALF_DOWN);
            $result = (object) array(
                'claimed' => (float) $div->claimed,
                'rewarded' => (float) $div->rewarded,
                'net' => $net
            );
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array(
                'claimed' => 0,
                'rewarded' => 0,
                'net' => 0
            );
        }
        return $result;
    }

    public function getUserClaimedDividend($id)
    {
        return DB::table('users_dividend')
            ->select('date', 'amount', 'hash')
            ->where('user_id', $id)
            ->where('type', 0)
            ->paginate(15);
    }

    public function getUserDividendHistory($id)
    {
        return DB::table('users_dividend')
            ->select('date', 'amount', 'type')
            ->where('user_id', $id)
            ->orderByDesc('date')
            ->paginate(10);
    }

    public function insertUserStake($data)
    {
        try {
            $lastID = DB::table('staking')->insertGetId($data);
            $result = (object) array('status' => true, 'lastID' => $lastID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function updateUserStake($fieldName, $name, $data)
    {
        try {
            DB::table('staking')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getStakedLMB()
    {
        try {
            $lmb = DB::table('staking')->selectRaw('
                sum(case when type = 1 then amount else 0 end) as staked,
                sum(case when type = 2 then amount else 0 end) as unstaked
            ')->first();
            $result = $lmb->staked - $lmb->unstaked;
            if ($result < 0) {
                $result = 0;
            }
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = 0;
        }
        return $result;
    }

    public function getUserStakedLMB($id)
    {
        try {
            $lmb = DB::table('staking')->selectRaw('
		sum(case when type = 1 then amount else 0 end) as staked,
		sum(case when type = 2 then amount else 0 end) as unstaked
                    ')
                ->where('user_id', $id)
                ->first();
            $result = $lmb->staked - $lmb->unstaked;
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = 0;
        }
        return $result;
    }

    public function getUserStakingHistory($id)
    {
        return DB::table('staking')
            ->selectRaw('DATE_FORMAT(created_at, "%M - %Y") as date, amount, type, hash')
            ->where('user_id', $id)
            ->paginate(10);
    }

    public function getUserUnstakeProgress($id)
    {
        try {
            $lmb = DB::table('unstake')->selectRaw('
		        sum(case when status = 0 then amount else 0 end) as processing
            ')
                ->where('user_id', $id)
                ->first();
            $result = $lmb->processing;
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = 0;
        }
        return $result;
    }

    public function insertUnstakingData($data)
    {
        try {
            $lastInsertedID = DB::table('unstake')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function updateUnstakingData($fieldName, $name, $data)
    {
        try {
            DB::table('unstake')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getAllStakers()
    {
        return DB::table('staking')->selectRaw('
            sum(case when type = 1 then amount else 0 end) as staked,
            sum(case when type = 2 then amount else 0 end) as unstaked, 
            user_id,
            sum(case when type = 1 then amount else - amount end) as net
        ')->groupBy('user_id')->get();
    }

    public function getAllStakersLeaderboard()
    {
        return DB::table('staking')
            ->join('users', 'staking.user_id', '=', 'users.id')
            ->selectRaw('
                sum(case when staking.type = 1 then staking.amount else - staking.amount end) as net,
                users.username
            ')
            ->groupBy('staking.user_id')
            ->orderBy('net', 'DESC')
            ->paginate();
    }

    public function getInsertBonusMember($data)
    {
        try {
            $lastInsertedID = DB::table('bonus_member')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getTotalBonus($data)
    {
        $sql = DB::table('bonus_member')
            ->selectRaw('sum(bonus_price) as total_bonus')
            ->where('user_id', '=', $data->id)
            ->whereIn('type', array(1, 2))
            ->where('poin_type', '=', 1)
            ->first();
        $total_bonus = 0;
        if ($sql->total_bonus != null) {
            $total_bonus = $sql->total_bonus;
        }
        $return = (object) array(
            'total_bonus' => $total_bonus
        );
        return $return;
    }
    public function getOldTotalBonusRoyalti($user_id)
    {
        $sql = DB::table('bonus_member')
            ->selectRaw('sum(bonus_price) as total_bonus')
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 3)
            ->where('poin_type', '=', 1)
            ->first();
        $total_bonus = 0;
        if ($sql->total_bonus != null) {
            $total_bonus = $sql->total_bonus;
        }
        return $total_bonus;
    }

    public function getTotalBonusRoyalti($user_id)
    {
        try {
            $sql = DB::table('bonus_royalties')
                ->selectRaw('
                sum(case when status = 0 then amount else 0 end) as credited,
                sum(case when status = 1 then amount else 0 end) as claimed
            ')
                ->where('user_id', '=', $user_id)
                ->first();
            $return = (object) [
                'credited' => $sql->credited,
                'claimed' => $sql->claimed,
                'net' => $sql->credited - $sql->claimed
            ];
        } catch (\Throwable $th) {
            $return = (object) [
                'credited' => 0,
                'claimed' => 0,
                'net' => 0
            ];
        }

        return $return;
    }

    public function getCekNewBonusRoyaltiMax($id, $level, $maxBonus, $date)
    {
        $return = true;
        if ($id > 11) {
            $sql = DB::table('bonus_royalties')
                ->selectRaw('count(id) as total_max')
                ->where('user_id', '=', $id)
                ->whereDate('bonus_date', '>=', $date->startDay)
                ->whereDate('bonus_date', '<=', $date->endDay)
                ->where('level_id', '=', $level)
                ->first();
            if ($sql->total_max != null) {
                if ($sql->total_max >= pow($maxBonus, $level)) {
                    $return = false;
                }
            }
        }
        return $return;
    }

    public function getBonusSponsor($data)
    {
        $sql = DB::table('bonus_member')
            ->join('users', 'bonus_member.from_user_id', '=', 'users.id')
            ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, users.name, users.username')
            ->where('bonus_member.user_id', '=', $data->id)
            ->where('bonus_member.type', '=', 1)
            ->where('bonus_member.poin_type', '=', 1)
            ->orderBy('bonus_member.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getBonusLevel($data)
    {
        $sql = DB::table('bonus_member')
            ->join('users', 'bonus_member.from_user_id', '=', 'users.id')
            ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, users.name, users.username, bonus_member.level_id')
            ->where('bonus_member.user_id', '=', $data->id)
            ->where('bonus_member.type', '=', 4)
            ->where('bonus_member.poin_type', '=', 1)
            ->orderBy('bonus_member.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getBonusRoyaltiCalculation($user_id, $date)
    {
        $sql = DB::table('bonus_royalties')
            ->join('users', 'bonus_royalties.from_user_id', '=', 'users.id')
            ->selectRaw('users.username, bonus_royalties.level_id')
            ->where('bonus_royalties.user_id', '=', $user_id)
            ->where('bonus_royalties.bonus_date', '=', $date . '-01')
            ->orderBy('bonus_royalties.level_id', 'ASC')
            ->groupBy('bonus_royalties.id')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getBonusRO($data)
    {
        $sql = DB::table('bonus_member')
            ->join('users', 'bonus_member.from_user_id', '=', 'users.id')
            ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, users.name, users.username')
            ->where('bonus_member.user_id', '=', $data->id)
            ->where('bonus_member.type', '=', 4)
            ->where('bonus_member.poin_type', '=', 1)
            ->orderBy('bonus_member.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getBonusSponsorByAdmin()
    {
        $sql = DB::table('bonus_member')
            ->join('users', 'bonus_member.user_id', '=', 'users.id')
            ->join('bank', 'users.id', '=', 'bank.user_id')
            ->selectRaw('sum(bonus_member.bonus_price) as total_bonus, '
                . 'users.name, users.username, users.total_sponsor, users.hp, '
                . 'bank.bank_name, bank.account_no,'
                . 'users.full_name')
            ->where('bonus_member.type', '=', 1)
            ->where('bonus_member.poin_type', '=', 1)
            ->where('bank.is_active', '=', 1)
            ->groupBy('users.name')
            ->groupBy('users.full_name')
            ->groupBy('users.username')
            ->groupBy('users.total_sponsor')
            ->groupBy('bank.bank_name')
            ->groupBy('bank.account_no')
            ->groupBy('users.hp')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getBonusBinary($data)
    {
        $sql = DB::table('bonus_member')
            ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, bonus_member.total_binary,'
                . 'bonus_member.total_activated, bonus_member.bonus_index, bonus_member.total_all_binary, '
                . 'bonus_member.index_date, bonus_member.bonus_setting')
            ->where('bonus_member.user_id', '=', $data->id)
            ->where('bonus_member.type', '=', 2)
            ->where('bonus_member.poin_type', '=', 1)
            ->orderBy('bonus_member.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getInsertClaimReward($data)
    {
        try {
            $lastInsertedID = DB::table('claim_reward')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateClaimReward($fieldName, $name, $data)
    {
        try {
            DB::table('claim_reward')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getMemberRewardByUser($data, $reward_id)
    {
        $sql = DB::table('claim_reward')
            ->selectRaw('claim_reward.id')
            ->where('claim_reward.user_id', '=', $data->id)
            ->where('claim_reward.reward_id', '=', $reward_id)
            ->where('claim_reward.status', '!=', 2)
            ->first();
        return $sql;
    }

    public function getMemberRewardHistory($data)
    {
        $sql = DB::table('claim_reward')
            ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
            ->selectRaw('bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason')
            ->where('claim_reward.user_id', '=', $data->id)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllReward()
    {
        $sql = DB::table('claim_reward')
            ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
            ->join('users', 'claim_reward.user_id', '=', 'users.id')
            ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                . 'users.username, users.tron')
            ->where('claim_reward.status', '=', 0)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminRewardByID($id)
    {
        $sql = DB::table('claim_reward')
            ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
            ->join('users', 'claim_reward.user_id', '=', 'users.id')
            ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                . 'users.username')
            ->where('claim_reward.id', '=', $id)
            ->where('claim_reward.status', '=', 0)
            ->first();
        return $sql;
    }

    public function getAdminDetailRewardByID($id)
    {
        $sql = DB::table('claim_reward')
            ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
            ->join('users', 'claim_reward.user_id', '=', 'users.id')
            ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                . 'users.username')
            ->where('claim_reward.id', '=', $id)
            ->first();
        return $sql;
    }

    public function getAdminHistoryReward()
    {
        $sql = DB::table('claim_reward')
            ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
            ->join('users', 'claim_reward.user_id', '=', 'users.id')
            ->join('users as u', 'claim_reward.submit_by', '=', 'u.id')
            ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                . 'users.username, claim_reward.submit_by, u.name')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getInsertBelanjaReward($data)
    {
        try {
            $lastInsertedID = DB::table('belanja_reward')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateBelanjaReward($fieldName, $name, $data)
    {
        try {
            DB::table('belanja_reward')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getTotalBelanjaReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->where('belanja_reward.user_id', '=', $id)
            ->where('belanja_reward.status', '=', 1)
            ->where('belanja_reward.type', '=', 1)
            ->sum('belanja_reward.reward');
        return $sql;
    }

    public function getTotalClaimedLMBfromMarketplace($id)
    {
        $sql = DB::table('belanja_reward')
            ->where('belanja_reward.user_id', '=', $id)
            ->where('belanja_reward.status', '=', 1)
            ->sum('belanja_reward.reward');
        return $sql;
    }

    public function getBelanjaRewardByMonthYear($id, $month, $year)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->where('month', '=', $month)
            ->where('year', '=', $year)
            ->where('status', '!=', 2)
            ->where('type', '=', 1)
            ->first();
        return $sql;
    }

    public function getTotalVBelanjaReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->where('belanja_reward.user_id', '=', $id)
            ->where('belanja_reward.status', '=', 1)
            ->where('belanja_reward.type', '=', 3)
            ->sum('belanja_reward.reward');
        return $sql;
    }

    public function getVBelanjaRewardByMonthYear($id, $month, $year)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->where('month', '=', $month)
            ->where('year', '=', $year)
            ->where('status', '!=', 2)
            ->where('type', '=', 3)
            ->first();
        return $sql;
    }

    public function getAdminAllBelanjaReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, users.is_tron')
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 1)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllBelanjaRewardByID($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 1)
            ->first();
        return $sql;
    }

    public function getAdminAllVBelanjaRewardByID($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 3)
            ->first();
        return $sql;
    }

    public function getAdminHistoryBelanjaReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->join('users as u', 'belanja_reward.submit_by', '=', 'u.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.submit_by, u.name')
            ->where('belanja_reward.type', '=', 1)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminDetailBelanjaReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.reason')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.type', '=', 1)
            ->first();
        return $sql;
    }

    public function getJobDetailBelanjaReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.type, belanja_reward.user_id, '
                . 'DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, users.tron, users.username')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.status', '=', 0)
            ->first();
        return $sql;
    }

    public function getAdminDetailVBelanjaReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.reason')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.type', '=', 3)
            ->first();
        return $sql;
    }

    public function getTotalPenjualanReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->where('belanja_reward.user_id', '=', $id)
            ->where('belanja_reward.status', '=', 1)
            ->where('belanja_reward.type', '=', 2)
            ->sum('belanja_reward.reward');
        return $sql;
    }

    public function getPenjualanRewardByMonthYear($id, $month, $year)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->where('month', '=', $month)
            ->where('year', '=', $year)
            ->where('status', '!=', 2)
            ->where('type', '=', 2)
            ->first();
        return $sql;
    }

    public function getTotalVPenjualanReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->where('belanja_reward.user_id', '=', $id)
            ->where('belanja_reward.status', '=', 1)
            ->where('belanja_reward.type', '=', 4)
            ->sum('belanja_reward.reward');
        return $sql;
    }

    public function getVPenjualanRewardByMonthYear($id, $month, $year)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->where('month', '=', $month)
            ->where('year', '=', $year)
            ->where('status', '!=', 2)
            ->where('type', '=', 4)
            ->first();
        return $sql;
    }

    public function getAdminAllPenjualanReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, users.is_tron')
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 2)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllVendorPenjualanReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, users.is_tron')
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 4)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllVendorBelanjaReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, users.is_tron')
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 3)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllPenjualanRewardByID($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 2)
            ->first();
        return $sql;
    }

    public function getAdminAllVendorPenjualanRewardByID($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 4)
            ->first();
        return $sql;
    }

    public function getAdminDetailPenjualanReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.reason')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.type', '=', 2)
            ->first();
        return $sql;
    }

    public function getAdminDetailVPenjualanReward($id)
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.reason')
            ->where('belanja_reward.id', '=', $id)
            ->where('belanja_reward.type', '=', 4)
            ->first();
        return $sql;
    }

    public function getAdminHistoryPenjualanReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->join('users as u', 'belanja_reward.submit_by', '=', 'u.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.submit_by, u.name')
            ->where('belanja_reward.type', '=', 2)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminHistoryVPenjualanReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->join('users as u', 'belanja_reward.submit_by', '=', 'u.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.submit_by, u.name')
            ->where('belanja_reward.type', '=', 4)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminHistoryVBelanjaReward()
    {
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->join('users as u', 'belanja_reward.submit_by', '=', 'u.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.tron, belanja_reward.status, belanja_reward.submit_by, u.name')
            ->where('belanja_reward.type', '=', 3)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllBelanjaRewardYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.hp, users.tron, users.is_tron')
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 1)
            ->whereDate('belanja_reward.belanja_date', '=', $yesterday)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllPenjualanRewardYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('belanja_reward')
            ->join('users', 'belanja_reward.user_id', '=', 'users.id')
            ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                . 'belanja_reward.created_at, users.username, users.hp, users.tron, users.is_tron')
            ->where('belanja_reward.status', '=', 0)
            ->where('belanja_reward.type', '=', 2)
            ->whereDate('belanja_reward.belanja_date', '=', $yesterday)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminAllRewardYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('claim_reward')
            ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
            ->join('users', 'claim_reward.user_id', '=', 'users.id')
            ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                . 'users.username, users.hp, users.tron')
            ->where('claim_reward.status', '=', 0)
            ->whereDate('claim_reward.claim_date', '=', $yesterday)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllClaimLMB()
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('sum(belanja_reward.reward) as total_claim_shop')
            ->where('belanja_reward.status', '=', 1)
            ->first();
        return $sql;
    }

    public function getAllClaimRewardLMB()
    {
        $sql = DB::table('claim_reward')
            ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as tot_reward_1, '
                . 'sum(case when reward_id = 2 then 200 else 0 end) as tot_reward_2,'
                . 'sum(case when reward_id = 3 then 500 else 0 end) as tot_reward_3,'
                . 'sum(case when reward_id = 4 then 2000 else 0 end) as tot_reward_4')
            ->where('status', '=', 1)
            ->whereIn('reward_id', array(1, 2, 3, 4))
            ->first();
        return $sql;
    }

    public function getClaimRewardLMBbyId($reward_id)
    {
        $sql = DB::table('claim_reward')
            ->join('users', 'claim_reward.user_id', '=', 'users.id')
            ->selectRaw('claim_reward.id, claim_reward.user_id, users.username, users.tron, claim_reward.reward_id')
            ->where('claim_reward.status', '=', 0)
            ->where('claim_reward.id', '=', $reward_id)
            ->whereNull('claim_reward.reason')
            ->first();
        return $sql;
    }

    public function getAllClaimLMBByIDUserCode($data)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('sum(belanja_reward.reward) as total_claim_shop')
            ->where('belanja_reward.user_id', '=', $data->id)
            ->where('belanja_reward.status', '=', 1)
            ->first();
        return $sql;
    }

    public function getAllClaimRewardLMBByIDUserCode($data)
    {
        $sql = DB::table('claim_reward')
            ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as tot_reward_1, '
                . 'sum(case when reward_id = 2 then 200 else 0 end) as tot_reward_2,'
                . 'sum(case when reward_id = 3 then 500 else 0 end) as tot_reward_3,'
                . 'sum(case when reward_id = 4 then 2000 else 0 end) as tot_reward_4')
            ->where('claim_reward.user_id', '=', $data->id)
            ->where('status', '=', 1)
            ->whereIn('reward_id', array(1, 2, 3, 4))
            ->first();
        return $sql;
    }

    public function getAllClaimLMBLastMonth($date)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('sum(belanja_reward.reward) as total_claim_shop')
            ->where('belanja_reward.status', '=', 1)
            ->whereDate('belanja_reward.belanja_date', '>=', $date->start_day)
            ->whereDate('belanja_reward.belanja_date', '<=', $date->end_day)
            ->first();
        return $sql;
    }

    public function getAllClaimRewardLMBLastMonth($date)
    {
        $sql = DB::table('claim_reward')
            ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as tot_reward_1, '
                . 'sum(case when reward_id = 2 then 200 else 0 end) as tot_reward_2,'
                . 'sum(case when reward_id = 3 then 500 else 0 end) as tot_reward_3,'
                . 'sum(case when reward_id = 4 then 2000 else 0 end) as tot_reward_4')
            ->where('status', '=', 1)
            ->whereIn('reward_id', array(1, 2, 3, 4))
            ->whereDate('claim_date', '>=', $date->start_day)
            ->whereDate('claim_date', '<=', $date->end_day)
            ->first();
        return $sql;
    }

    public function getInsertTopUp($data)
    {
        try {
            $lastInsertedID = DB::table('top_up')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateTopUp($fieldName, $name, $data)
    {
        try {
            DB::table('top_up')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getAllTopUpSaldo($data)
    {
        $sql = DB::table('top_up')
            ->where('user_id', '=', $data->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getTopUpSaldoID($id)
    {
        $sql = DB::table('top_up')
            ->where('id', '=', $id)
            ->first();
        return $sql;
    }

    public function getTopUpSaldoIDUserId($id, $data)
    {
        $sql = DB::table('top_up')
            ->where('id', '=', $id)
            ->where('user_id', '=', $data->id)
            ->first();
        return $sql;
    }

    public function getTotalSaldoUserId($data)
    {
        $sql = DB::table('top_up')
            ->selectRaw('sum(nominal) as total_topup')
            ->where('user_id', '=', $data->id)
            ->where('status', '=', 2)
            ->first();
        $total = 0;
        if ($sql->total_topup != null) {
            $total = $sql->total_topup;
        }
        return $total;
    }

    public function getAllRequestTopup()
    {
        $sql = DB::table('top_up')
            ->join('users', 'top_up.user_id', '=', 'users.id')
            ->join('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
            ->selectRaw('users.name, users.hp, users.username, users.tron, '
                . 'top_up.status, top_up.updated_at, top_up.id,'
                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                . 'bank.bank_name, bank.account_name, bank.account_no')
            ->where('top_up.status', '=', 1)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminTopUpSaldoIDUserId($id, $user_id)
    {
        $sql = DB::table('top_up')
            ->join('users', 'top_up.user_id', '=', 'users.id')
            ->join('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
            ->selectRaw('users.name, users.hp, users.username, '
                . 'top_up.status, top_up.updated_at, top_up.id,'
                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                . 'bank.bank_name, bank.account_name, bank.account_no')
            ->where('top_up.id', '=', $id)
            ->where('top_up.user_id', '=', $user_id)
            ->first();
        return $sql;
    }

    public function getJobTopUpSaldoIDUserId($id, $user_id)
    {
        $sql = DB::table('top_up')
            ->join('users', 'top_up.user_id', '=', 'users.id')
            ->selectRaw('users.name, users.tron, users.username, '
                . 'top_up.status, top_up.updated_at, top_up.id, top_up.bank_perusahaan_id,'
                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal')
            ->where('top_up.id', '=', $id)
            ->where('top_up.status', '=', 1)
            ->where('top_up.user_id', '=', $user_id)
            ->first();
        return $sql;
    }

    public function getUserIdfromTopUpId($topup_id)
    {
        $sql = DB::table('top_up')
            ->selectRaw('top_up.user_id')
            ->where('top_up.id', '=', $topup_id)
            ->where('top_up.status', '=', 1)
            ->first();
        return $sql;
    }

    public function getAllHistoryTopup()
    {
        $sql = DB::table('top_up')
            ->join('users', 'top_up.user_id', '=', 'users.id')
            ->leftJoin('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
            ->selectRaw('users.name, users.hp, users.username, '
                . 'top_up.status, top_up.updated_at, top_up.id,'
                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                . 'bank.bank_name, bank.account_name, bank.account_no')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllRequestTopupYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('top_up')
            ->join('users', 'top_up.user_id', '=', 'users.id')
            ->join('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
            ->selectRaw('users.name, users.hp, users.username, users.tron, '
                . 'top_up.status, top_up.updated_at, top_up.id,'
                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                . 'bank.bank_name, bank.account_name, bank.account_no')
            ->where('top_up.status', '=', 1)
            ->whereDate('top_up.created_at', '=', $yesterday)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }
}
