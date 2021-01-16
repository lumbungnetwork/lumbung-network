<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\User;
use Validator;

class Member extends Model
{

    public function getInsertUsers($data)
    {
        try {
            $lastInsertedID = DB::table('users')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateUsers($fieldName, $name, $data)
    {
        try {
            DB::table('users')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getAllMember()
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->count();
        return $sql;
    }

    public function getAllPinActivation()
    {
        $sql = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->count();
        return $sql;
    }

    public function getCekMemberExist($user_code)
    {
        $sql = DB::table('users')
            ->where('user_code', '=', $user_code)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->first();
        return $sql;
    }

    public function getKBBMember($user_code)
    {
        $sql = DB::table('users')
            ->where('user_code', '=', $user_code)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->whereIn('affiliate', [2, 3])
            ->first();
        return $sql;
    }

    public function getInviteCount($user_id)
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.user_code')
            ->where('invited_by', '=', $user_id)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->whereIn('affiliate', [2, 3])
            ->get();
        return $sql;
    }

    public function getCekMemberNotStockistOrVendor($id)
    {
        $sql = DB::table('users')
            ->where('id', '=', $id)
            ->where('is_stockist', '=', 0)
            ->where('is_vendor', '=', 0)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->first();
        return $sql;
    }

    public function getAllMemberByAdmin()
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.name, users.email, users.hp, users.is_active, users.active_at, u1.user_code as sp_name, '
                . 'users.user_code, users.is_tron, users.tron, users.pin_activate_at')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->where('users.is_active', '=', 1)
            ->where('users.user_type', '=', 10)
            ->orderBy('users.active_at', 'ASC')
            ->get();
        return $sql;
    }

    public function getSearchAllMemberByAdmin($search)
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.name, users.email, users.hp, users.is_active, users.active_at, u1.user_code as sp_name, '
                . 'users.user_code, users.is_tron, users.tron, users.pin_activate_at')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->where('users.is_active', '=', 1)
            ->where('users.user_type', '=', 10)
            ->where('users.user_code', 'LIKE', '%' . $search . '%')
            ->orderBy('users.active_at', 'DESC')
            ->get();
        $return = (object) array(
            'total' => 0,
            'data' => null
        );
        if (count($sql) > 0) {
            $return = (object) array(
                'total' => count($sql),
                'data' => $sql
            );
        }
        return $return;
    }

    public function getSearchAllMemberByTron($search)
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.name, users.email, users.hp, users.is_active, users.active_at, u1.user_code as sp_name, '
                . 'users.user_code, users.is_tron, users.tron, users.pin_activate_at')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->where('users.is_active', '=', 1)
            ->where('users.user_type', '=', 10)
            ->where('users.tron', '=', $search)
            ->orderBy('users.active_at', 'ASC')
            ->get();
        $return = (object) array(
            'total' => 0,
            'data' => null
        );
        if (count($sql) > 0) {
            $return = (object) array(
                'total' => count($sql),
                'data' => $sql
            );
        }
        return $return;
    }

    public function getSearchMemberByMonthByAdmin($date)
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.name, users.email, users.hp, users.is_active, users.active_at, u1.user_code as sp_name, '
                . 'users.user_code, users.is_tron, users.tron, users.pin_activate_at')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->where('users.is_active', '=', 1)
            ->where('users.user_type', '=', 10)
            ->where('users.active_at', '>=', $date->startDay)
            ->where('users.active_at', '<=', $date->endDay)
            ->orderBy('users.active_at', 'ASC')
            ->get();
        $return = (object) array(
            'total' => 0,
            'data' => null
        );
        if (count($sql) > 0) {
            $return = (object) array(
                'total' => count($sql),
                'data' => $sql
            );
        }
        return $return;
    }

    public function getSearchAllMemberStockistByAdmin($search)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->where('users.user_code', 'LIKE', '%' . $search . '%')
            ->orderBy('stockist_at', 'DESC')
            ->get();
        $return = (object) array(
            'total' => 0,
            'data' => null
        );
        if (count($sql) > 0) {
            $return = (object) array(
                'total' => count($sql),
                'data' => $sql
            );
        }
        return $return;
    }

    public function getSearchAllMemberVendorByAdmin($search)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_vendor', '=', 1)
            ->where('users.user_code', 'LIKE', '%' . $search . '%')
            ->orderBy('vendor_at', 'DESC')
            ->get();
        $return = (object) array(
            'total' => 0,
            'data' => null
        );
        if (count($sql) > 0) {
            $return = (object) array(
                'total' => count($sql),
                'data' => $sql
            );
        }
        return $return;
    }

    public function getUsers($where, $data)
    {
        $sql = DB::table('users')->where($where, '=', $data)->first();
        return $sql;
    }

    public function getCheckEmailPhoneUsercode($mail, $phone, $usercode)
    {
        $sqlEmail = DB::table('users')->selectRaw('id')->where('email', '=', $mail)->where('user_type', '=', 10)->count();
        $sqlHP = DB::table('users')->selectRaw('id')->where('hp', '=', $phone)->where('user_type', '=', 10)->count();
        $sqlCode = DB::table('users')->selectRaw('id')->where('user_code', '=', $usercode)->where('user_type', '=', 10)->count();
        $data = (object) array(
            'cekEmail' => $sqlEmail, 'cekHP' => $sqlHP, 'cekCode' => $sqlCode
        );
        return $data;
    }

    public function getCheckUsercode($usercode)
    {
        $sqlCode = DB::table('users')->selectRaw('id')->where('user_code', '=', $usercode)->count();
        $data = (object) array(
            'cekCode' => $sqlCode
        );
        return $data;
    }

    public function getCheckUsercodeNotHim($usercode, $id)
    {
        $sqlCode = DB::table('users')
            ->selectRaw('id')
            ->where('user_code', '=', $usercode)
            ->where('id', '!=', $id)
            ->where('user_type', '=', 10)
            ->count();
        $data = (object) array(
            'cekCode' => $sqlCode
        );
        return $data;
    }

    public function getCountLastMember()
    {
        $getCount = DB::table('users')
            ->selectRaw('id')
            ->where('user_type', '=', 10)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();
        $tmp = $getCount + 13;
        $getCode = 'LN' . date('ymd') . sprintf("%04s", $tmp);
        return $getCode;
    }

    public function getCountNewKBBUserCode()
    {
        $getCount = DB::table('users')
            ->selectRaw('id')
            ->where('user_code', 'LIKE', 'KBB-%')
            ->where('is_active', '=', 1)
            ->where('affiliate', '=', 1)
            ->count();
        $tmp = $getCount + 1;
        $getCode = 'KBB-' . sprintf("%05s", $tmp);
        return $getCode;
    }

    public function getCheckKTP($ktp)
    {
        $sql = DB::table('users')->selectRaw('id')->where('ktp', '=', $ktp)->where('user_type', '=', 10)->count();
        return $sql;
    }

    public function getAllDownlineSponsor($data)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('sponsor_id', '=', $data->id)
            ->get();
        $getData = null;
        if (count($sql) > 0) {
            $getData = $sql;
        }
        return $getData;
    }

    public function getAllMemberToPlacement($data)
    {
        $sql = DB::table('users')
            ->where('sponsor_id', '=', $data->id)
            ->whereNull('upline_id')
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->get();
        return $sql;
    }

    public function getCekMemberToPlacement($id, $data)
    {
        $sql = DB::table('users')
            ->where('id', '=', $id)
            ->where('sponsor_id', '=', $data->id)
            ->whereNull('upline_id')
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->first();
        return $sql;
    }

    public function getCekKananKiriFreeKakiKecil($id, $data)
    {
        $sql = DB::table('users')
            ->where('id', '=', $id)
            ->where('upline_id', '=', $data->id)
            ->where('user_type', '=', 10)
            ->where(function ($query) {
                $query->whereNull('kiri_id')
                    ->orWhereNull('kanan_id');
            })
            ->first();
        return $sql;
    }

    public function getCekKananKiriFreeKakiPanjang($uplineDetail, $id)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            //                    ->where('upline_id', '=', $id)
            ->where('upline_detail', 'LIKE', $uplineDetail . '%')
            ->where(function ($query) {
                $query->whereNull('kiri_id')
                    ->orWhereNull('kanan_id');
            })
            ->orderBy('id', 'ASC')
            ->first();
        return $sql;
    }

    //binary ver 1.00
    public function getBinary($data)
    {
        $sql1 = DB::table('users')
            ->where('id', '=', $data->id)
            ->where('user_type', '=', 10)
            ->first();
        $sql2 = $sql3 = $sql4 = $sql5 = $sql6 = $sql7 = null;
        if ($sql1->kiri_id != null) {
            $sql2 = DB::table('users')
                ->where('id', '=', $sql1->kiri_id)
                ->where('user_type', '=', 10)
                ->first();
        }
        if ($sql1->kanan_id != null) {
            $sql3 = DB::table('users')
                ->where('id', '=', $sql1->kanan_id)
                ->where('user_type', '=', 10)
                ->first();
        }

        if ($sql2 != null) {
            if ($sql2->kiri_id != null) {
                $sql4 = DB::table('users')
                    ->where('id', '=', $sql2->kiri_id)
                    ->where('user_type', '=', 10)
                    ->first();
            }
            if ($sql2->kanan_id != null) {
                $sql5 = DB::table('users')
                    ->where('id', '=', $sql2->kanan_id)
                    ->where('user_type', '=', 10)
                    ->first();
            }
        }

        if ($sql3 != null) {
            if ($sql3->kiri_id != null) {
                $sql6 = DB::table('users')
                    ->where('id', '=', $sql3->kiri_id)
                    ->where('user_type', '=', 10)
                    ->first();
            }
            if ($sql3->kanan_id != null) {
                $sql7 = DB::table('users')
                    ->where('id', '=', $sql3->kanan_id)
                    ->where('user_type', '=', 10)
                    ->first();
            }
        }
        $dataReturn = array($sql1, $sql2, $sql3, $sql4, $sql5, $sql6, $sql7);
        return $dataReturn;
    }

    public function getMyDownline($downline)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('upline_detail', 'LIKE', $downline . '%')
            ->orderBy('id', 'ASC')
            ->get();
        return $sql;
    }

    public function getCountMyDownline($downline)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('user_type', '=', 10)
            ->where('upline_detail', 'LIKE', $downline . '%')
            ->count();
        return $sql;
    }

    public function getCountMemberActivate($downline, $status)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', $status)
            ->where('upline_detail', 'LIKE', $downline . '%')
            ->count();
        return $sql;
    }

    public function getMyDownlineAllStatus($downline, $id)
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.name, users.email, users.hp, users.user_code, users.active_at, users.is_active, '
                . 'users.package_id, package.name as paket_name')
            ->leftJoin('package', 'package.id', '=', 'users.package_id')
            ->where('users.user_type', '=', 10)
            ->where('users.sponsor_id', '=', $id)
            ->orWhere('users.upline_detail', 'LIKE', $downline . '%')
            ->orderBy('users.id', 'ASC')
            ->get();
        return $sql;
    }

    public function getMyDownlineUsername($downline, $username)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('upline_detail', 'LIKE', $downline . '%')
            ->where('user_code', 'LIKE', '%' . $username . '%')
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getCekIdDownline($id, $downline)
    {
        $sql = DB::table('users')
            ->where('id', '=', $id)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('upline_detail', 'LIKE', $downline . '%')
            ->orderBy('id', 'ASC')
            ->first();
        return $sql;
    }

    public function getUsersCodeEmail($user_code, $email)
    {
        $sql = DB::table('users')->where('user_code', '=', $user_code)->where('email', '=', $email)->first();
        return $sql;
    }

    public function getAllOldMemberByDate($date)
    {
        $sql = DB::table('users')
            //                    ->whereDate('created_at', '<=', $date)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->orderBy('id', 'ASC')
            ->get();
        return $sql;
    }

    public function getCountOldMemberByDate($date)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->whereDate('active_at', '=', $date)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->orderBy('id', 'ASC')
            ->count();
        return $sql;
    }

    public function getCountOuterDownlineByDate($downline, $date)
    {
        $sql = DB::table('users')
            ->selectRaw('count(users.id) as total_downline')
            ->where('users.user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->whereDate('active_at', '<=', $date)
            ->where('users.upline_detail', 'LIKE', $downline . '%')
            ->first();
        $return = 0;
        if ($sql->total_downline != null) {
            $return = $sql->total_downline;
        }
        return $return;
    }

    public function getCountInnerDownlineByDate($id, $date)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('users.id', '=', $id)
            ->where('users.user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->whereDate('active_at', '<=', $date)
            ->count();
        return $sql;
    }

    public function getCheckOuterDownlineByDate($downline, $date)
    {
        $sql = DB::table('users')
            ->where('users.user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->whereDate('active_at', $date)
            ->where('users.upline_detail', 'LIKE', $downline . '%')
            ->get();
        $return = 0;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllMemberByDate($date)
    {
        $sql = DB::table('users')
            ->whereDate('active_at', '<=', $date)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->orderBy('id', 'ASC')
            ->get();
        return $sql;
    }

    public function getCountMemberByDate($date)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->whereDate('placement_at', '=', $date)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->orderBy('id', 'ASC')
            ->count();
        return $sql;
    }

    public function getCountMemberResubscribeByDate($date)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->whereDate('pin_activate_at', '=', $date)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->orderBy('id', 'ASC')
            ->count();
        return $sql;
    }

    public function getNewCountOuterDownlineByDate($downline, $date)
    {
        $sql = DB::table('users')
            ->selectRaw('sum(users.pin_activate) as total_downline')
            ->where('users.user_type', '=', 10)
            ->where('is_active', '=', 1)
            //                    ->whereDate('placement_at', $date)
            ->whereDate('active_at', '<=', $date)
            ->where('users.upline_detail', 'LIKE', $downline . '%')
            ->first();
        $return = 0;
        if ($sql->total_downline != null) {
            $return = $sql->total_downline;
        }
        return $return;
    }

    public function getNewCountInnerDownlineByDate($id, $date)
    {
        $sql = DB::table('users')
            ->selectRaw('sum(users.pin_activate) as total_downline')
            ->where('users.id', '=', $id)
            ->where('users.user_type', '=', 10)
            ->where('is_active', '=', 1)
            //                    ->whereDate('placement_at', '=', $date)
            ->whereDate('active_at', '<=', $date)
            ->first();
        $return = 0;
        if ($sql->total_downline != null) {
            $return = $sql->total_downline;
        }
        return $return;
    }

    public function getLevelSponsoring($id)
    {
        $sql = DB::table('users')
            ->selectRaw('users.id, users.user_code, '
                . 'u1.id as id_lvl1, u1.user_code as user_code_lvl1, '
                . 'u2.id as id_lvl2, u2.user_code as user_code_lvl2, '
                . 'u3.id as id_lvl3, u3.user_code as user_code_lvl3, '
                . 'u4.id as id_lvl4, u4.user_code as user_code_lvl4, '
                . 'u5.id as id_lvl5, u5.user_code as user_code_lvl5, '
                . 'u6.id as id_lvl6, u6.user_code as user_code_lvl6, '
                . 'u7.id as id_lvl7, u7.user_code as user_code_lvl7')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->leftJoin('users as u2', 'u1.sponsor_id', '=', 'u2.id')
            ->leftJoin('users as u3', 'u2.sponsor_id', '=', 'u3.id')
            ->leftJoin('users as u4', 'u3.sponsor_id', '=', 'u4.id')
            ->leftJoin('users as u5', 'u4.sponsor_id', '=', 'u5.id')
            ->leftJoin('users as u6', 'u5.sponsor_id', '=', 'u6.id')
            ->leftJoin('users as u7', 'u6.sponsor_id', '=', 'u7.id')
            ->where('users.id', '=', $id)
            ->where('users.user_type', '=', 10)
            ->where('users.is_active', '=', 1)
            ->first();
        return $sql;
    }

    public function getCekIdDownlineSponsor($id, $sp_id)
    {
        $sql = DB::table('users')
            ->where('id', '=', $id)
            ->where('sponsor_id', '=', $sp_id)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->orderBy('id', 'ASC')
            ->first();
        return $sql;
    }

    public function getStructureSponsor($data)
    {
        $sql = DB::table('users')
            ->where('sponsor_id', '=', $data->id)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSponsorPeringkat($data)
    {
        $sql = DB::table('users')
            ->leftJoin('bonus_reward2', 'users.member_type', '=', 'bonus_reward2.type')
            ->selectRaw('users.user_code, users.member_type, bonus_reward2.name, users.total_sponsor, bonus_reward2.image')
            ->where('users.sponsor_id', '=', $data->id)
            ->where('users.user_type', '=', 10)
            ->where('users.is_active', '=', 1)
            ->orderBy('users.id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getCheckTron($tron)
    {
        $sql = DB::table('users')->selectRaw('id')->where('tron', '=', $tron)->where('user_type', '=', 10)->first();
        return $sql;
    }

    public function getSearchUserStockist($data)
    {
        $sql = DB::table('users')
            ->where('user_code', '=', $data)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchUserVendor($data)
    {
        $sql = DB::table('users')
            ->where('user_code', '=', $data)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_vendor', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getMyDownlineUsernameStockist($username)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('user_code', 'LIKE', '%' . $username . '%')
            ->where('is_stockist', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getMyDownlineUsernameVendor($username)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('user_code', 'LIKE', '%' . $username . '%')
            ->where('is_vendor', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchUserByLocation($data)
    {
        $sql = DB::table('users')
            ->where('kode_daerah', 'LIKE', $data . '%')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->orderBy('kelurahan', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getCekHakUsaha($data, $user_code)
    {
        $sql = DB::table('users')
            ->where('sponsor_id', '=', $data->id)
            ->where('user_code', '=', $user_code)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->first();
        return $sql;
    }

    public function getProvinsi()
    {
        $sql = DB::table('daerah')
            ->where('kabupatenkota', '=', 0)
            ->where('kecamatan', '=', 0)
            ->where('kelurahan', '=', 0)
            ->orderBy('daerahID', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getProvinsiNew()
    {
        $sql = DB::table('provinsi')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getNamaByKode($kode)
    {
        $sql = DB::table('daerah')
            ->where('kode', '=', $kode)
            ->first();
        return $sql;
    }

    public function getProvinsiByID($id)
    {
        $sql = DB::table('provinsi')
            ->where('id_prov', '=', $id)
            ->first();
        return $sql;
    }

    public function getProvinsiIdByName($name)
    {
        $sql = DB::table('provinsi')
            ->where('nama', '=', $name)
            ->first();
        return $sql;
    }

    public function getKabupatenIdByName($name)
    {
        $sql = DB::table('kabupaten')
            ->where('nama', '=', $name)
            ->first();
        return $sql;
    }

    public function getKabByID($id)
    {
        $sql = DB::table('kabupaten')
            ->where('id_kab', '=', $id)
            ->first();
        return $sql;
    }
    public function getKecByID($id)
    {
        $sql = DB::table('kecamatan')
            ->where('id_kec', '=', $id)
            ->first();
        return $sql;
    }
    public function getKelByID($id)
    {
        $sql = DB::table('kelurahan')
            ->where('id_kel', '=', $id)
            ->first();
        return $sql;
    }

    public function getKabupatenKotaByPropinsi($provinsi)
    {
        $sql = DB::table('daerah')
            ->where('propinsi', '=', $provinsi)
            ->where('kabupatenkota', '>', 0)
            ->where('kecamatan', '=', 0)
            ->where('kelurahan', '=', 0)
            ->orderBy('daerahID', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getKabupatenKotaByPropinsiNew($provinsi)
    {
        $sql = DB::table('kabupaten')
            ->where('id_prov', '=', $provinsi)
            ->orderBy('id_kab', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getKecamatanByKabupatenKota($provinsi, $kota)
    {
        $sql = DB::table('daerah')
            ->where('propinsi', '=', $provinsi)
            ->where('kabupatenkota', '=', $kota)
            ->where('kecamatan', '>', 0)
            ->where('kelurahan', '=', 0)
            ->orderBy('nama', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getKecamatanByKabupatenKotaNew($kota)
    {
        $sql = DB::table('kecamatan')
            ->where('id_kab', '=', $kota)
            ->orderBy('id_kec', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getKelurahanByKecamatan($provinsi, $kota, $kec)
    {
        $sql = DB::table('daerah')
            ->where('propinsi', '=', $provinsi)
            ->where('kabupatenkota', '=', $kota)
            ->where('kecamatan', '=', $kec)
            ->where('kelurahan', '>', 0)
            ->orderBy('nama', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getKelurahanByKecamatanNew($kec)
    {
        $sql = DB::table('kelurahan')
            ->where('id_kec', '=', $kec)
            ->orderBy('id_kel', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getDelegates()
    {
        return DB::table('delegates')
            ->selectRaw('name')
            ->get();
    }

    public function getInsertStockist($data)
    {
        try {
            $lastInsertedID = DB::table('stockist_request')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateStockist($fieldName, $name, $data)
    {
        try {
            DB::table('stockist_request')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function deleteRequestStockist($request_id)
    {
        return DB::table('stockist_request')
            ->where('stockist_request.id', '=', $request_id)
            ->where('stockist_request.status', '=', 0)
            ->delete();
    }

    public function deleteRequestVendor($request_id)
    {
        return DB::table('vendor_request')
            ->where('vendor_request.id', '=', $request_id)
            ->where('vendor_request.status', '=', 0)
            ->delete();
    }

    public function getRequestStockist($request_id)
    {
        $sql = DB::table('stockist_request')
            ->selectRaw('stockist_request.id, stockist_request.usernames, stockist_request.delegate')
            ->where('stockist_request.id', '=', $request_id)
            ->where('stockist_request.status', '=', 0)
            ->first();
        return $sql;
    }

    public function getRequestVendor($request_id)
    {
        $sql = DB::table('vendor_request')
            ->selectRaw('vendor_request.id, vendor_request.usernames, vendor_request.delegate')
            ->where('vendor_request.id', '=', $request_id)
            ->where('vendor_request.status', '=', 0)
            ->first();
        return $sql;
    }

    public function getCekMemberReqSotckist($id)
    {
        $sql = DB::table('stockist_request')
            ->join('users', 'users.id', '=', 'stockist_request.user_id')
            ->selectRaw('stockist_request.id, users.user_code, users.total_sponsor, users.id as id_user')
            ->where('stockist_request.id', '=', $id)
            ->where('stockist_request.status', '=', 0)
            ->first();
        return $sql;
    }

    public function getCekMemberReqVendor($id)
    {
        $sql = DB::table('vendor_request')
            ->join('users', 'users.id', '=', 'vendor_request.user_id')
            ->selectRaw('vendor_request.id, users.user_code, users.total_sponsor, users.id as id_user')
            ->where('vendor_request.id', '=', $id)
            ->where('vendor_request.status', '=', 0)
            ->first();
        return $sql;
    }

    public function getCekMemberSotckistToRemove($id)
    {
        $sql = DB::table('stockist_request')
            ->join('users', 'users.id', '=', 'stockist_request.user_id')
            ->selectRaw('stockist_request.id, users.user_code, users.total_sponsor, users.id as id_user, users.hp')
            ->where('users.id', '=', $id)
            ->where('users.user_type', '=', 10)
            ->where('users.is_active', '=', 1)
            ->where('users.is_stockist', '=', 1)
            ->first();
        return $sql;
    }

    public function getCekMemberVendorToRemove($id)
    {
        $sql = DB::table('vendor_request')
            ->join('users', 'users.id', '=', 'vendor_request.user_id')
            ->selectRaw('vendor_request.id, users.user_code, users.total_sponsor, users.id as id_user, users.hp')
            ->where('users.id', '=', $id)
            ->where('users.user_type', '=', 10)
            ->where('users.is_active', '=', 1)
            ->where('users.is_vendor', '=', 1)
            ->first();
        return $sql;
    }

    public function getAllMemberReqSotckist()
    {
        $sql = DB::table('stockist_request')
            ->join('users', 'users.id', '=', 'stockist_request.user_id')
            ->selectRaw('stockist_request.id, users.user_code, users.total_sponsor, stockist_request.created_at')
            ->where('stockist_request.status', '=', 0)
            ->get();
        return $sql;
    }

    public function getAllMemberReqVendor()
    {
        $sql = DB::table('vendor_request')
            ->join('users', 'users.id', '=', 'vendor_request.user_id')
            ->selectRaw('vendor_request.id, users.user_code, users.total_sponsor, vendor_request.created_at')
            ->where('vendor_request.status', '=', 0)
            ->get();
        return $sql;
    }

    public function getHistoryAllMemberReqSotckist()
    {
        $sql = DB::table('stockist_request')
            ->join('users', 'users.id', '=', 'stockist_request.user_id')
            ->join('users as u', 'stockist_request.submit_by', '=', 'u.id')
            ->selectRaw('stockist_request.id, users.user_code, users.total_sponsor, stockist_request.created_at, u.name as submit_name, '
                . 'stockist_request.active_at, stockist_request.submit_at, stockist_request.status, stockist_request.submit_by')
            ->orderBy('stockist_request.status', 'ASC')
            ->get();
        return $sql;
    }

    public function getHistoryAllMemberReqVendor()
    {
        $sql = DB::table('vendor_request')
            ->join('users', 'users.id', '=', 'vendor_request.user_id')
            ->join('users as u', 'vendor_request.submit_by', '=', 'u.id')
            ->selectRaw('vendor_request.id, users.user_code, users.total_sponsor, vendor_request.created_at, u.name as submit_name, '
                . 'vendor_request.active_at, vendor_request.submit_at, vendor_request.status, vendor_request.submit_by')
            ->orderBy('vendor_request.status', 'ASC')
            ->get();
        return $sql;
    }

    public function getCekRequestSotckist($id)
    {
        $sql = DB::table('stockist_request')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->first();
        return $sql;
    }

    public function getCekRequestVendorExist($id)
    {
        $sql = DB::table('vendor_request')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->first();
        return $sql;
    }

    public function getAdminAllStockist()
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->orderBy('stockist_at', 'DESC')
            ->get();
        return $sql;
    }

    public function getAdminAllVendor()
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_vendor', '=', 1)
            ->orderBy('vendor_at', 'DESC')
            ->get();
        return $sql;
    }

    public function getInsertVendor($data)
    {
        try {
            $lastInsertedID = DB::table('vendor_request')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateVendor($fieldName, $name, $data)
    {
        try {
            DB::table('vendor_request')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getCekRequestVendor($id)
    {
        $sql = DB::table('vendor_request')
            ->selectRaw('id')
            ->where('user_id', '=', $id)
            ->first();
        return $sql;
    }

    public function getAllMemberHasMoreSponsor($type, $totSp)
    {
        $sql = DB::table('users')
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->where('total_sponsor', '>=', $totSp)
            ->where('member_type', '=', $type)
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getCountMemberHasSponsorMemberType($sponsor_id, $type)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('sponsor_id', '=', $sponsor_id)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->where('member_type', '>=', $type)
            ->orderBy('id', 'ASC')
            ->count();
        return $sql;
    }

    public function getCountMemberHasSponsorFirst($sponsor_id)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('sponsor_id', '=', $sponsor_id)
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->orderBy('id', 'ASC')
            ->count();
        return $sql;
    }

    public function getInsertHistoryMembership($data)
    {
        try {
            $lastInsertedID = DB::table('history_membership')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getSearchUserByKelurahan($data, $kecamatan)
    {
        $sql = User::where('kelurahan', '=', $data)
            ->where('kecamatan', '=', $kecamatan)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->whereHas('sellerProfile')
            ->with('sellerProfile')
            ->orderBy('user_code', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchUserByKecamatan($kecamatan, $kelurahan)
    {
        $sql = User::where('kecamatan', '=', $kecamatan)
            ->where('kelurahan', '!=', $kelurahan)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->whereHas('sellerProfile')
            ->with('sellerProfile')
            ->orderBy('user_code', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchUserByKota($data, $kecamatan, $kelurahan)
    {
        $sql = User::where('kota', '=', $data)
            ->where('kecamatan', '!=', $kecamatan)
            ->where('kelurahan', '!=', $kelurahan)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_stockist', '=', 1)
            ->whereHas('sellerProfile')
            ->with('sellerProfile')
            ->orderBy('user_code', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchVendorUserByKelurahan($data, $kecamatan)
    {
        $sql = User::where('kelurahan', '=', $data)
            ->where('kecamatan', '=', $kecamatan)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_vendor', '=', 1)
            ->whereHas('sellerProfile')
            ->with('sellerProfile')
            ->orderBy('user_code', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchVendorUserByKecamatan($kecamatan, $kelurahan)
    {
        $sql = User::where('kecamatan', '=', $kecamatan)
            ->where('kelurahan', '!=', $kelurahan)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_vendor', '=', 1)
            ->whereHas('sellerProfile')
            ->with('sellerProfile')
            ->orderBy('user_code', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getSearchUserVendorByKota($data, $kecamatan, $kelurahan)
    {
        $sql = User::where('kota', '=', $data)
            ->where('kecamatan', '!=', $kecamatan)
            ->where('kelurahan', '!=', $kelurahan)
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('is_vendor', '=', 1)
            ->whereHas('sellerProfile')
            ->with('sellerProfile')
            ->orderBy('user_code', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllMemberLastMonth($date)
    {
        $sql = DB::table('users')
            ->selectRaw('id')
            ->where('is_active', '=', 1)
            ->where('user_type', '=', 10)
            ->whereDate('active_at', '>=', $date->start_day)
            ->whereDate('active_at', '<=', $date->end_day)
            ->count();
        return $sql;
    }

    public function getAllActivationLastMonth($date)
    {
        $sql = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->whereDate('used_at', '>=', $date->start_day)
            ->whereDate('used_at', '<=', $date->end_day)
            ->count();
        return $sql;
    }

    public function getExplorerUsername($id, $username)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('id', '!=', $id)
            ->where('id', '>', 11)
            ->where('user_code', 'LIKE', '%' . $username . '%')
            ->orderBy('id', 'ASC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getExplorerByID($id)
    {
        $sql = DB::table('users')
            ->where('user_type', '=', 10)
            ->where('is_active', '=', 1)
            ->where('id', '=', $id)
            ->first();
        return $sql;
    }

    public function getCekRequestSotckistBalikinData($id)
    {
        $sql = DB::table('stockist_request')
            ->where('user_id', '=', $id)
            ->first();
        return $sql;
    }

    public function getAPIurlCheck($url, $json)
    {
        $userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        //        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $json_response = curl_exec($ch);
        curl_close($ch);

        if (empty($json_response)) {
            return null;
        } else {
            return $json_response;
        }
    }
    public function getAPIurlCheckSimple($url, $json)
    {
        $userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $json_response = curl_exec($ch);
        // curl_close($ch);

        if (empty($json_response)) {
            return null;
        } else {
            return $json_response;
        }
    }

    public function getDataAPIMobilePulsa()
    {

        $data = (object) array(
            'username' => Config::get('services.digiflazz.user'),
            'api_key' => Config::get('services.digiflazz.key'),
            'master_url' => 'https://api.digiflazz.com'
        );
        return $data;
    }

    public function getUserTronAddress($user_id)
    {
        $sql = DB::table('users')
            ->where('id', $user_id)
            ->select('tron')
            ->first();

        return $sql->tron;
    }
}
