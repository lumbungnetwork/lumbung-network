<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Member extends Model {
    
    public function getInsertUsers($data){
        try {
            $lastInsertedID = DB::table('users')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateUsers($fieldName, $name, $data){
        try {
            DB::table('users')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getAllMember(){
        $sql = DB::table('users')
                    ->selectRaw('id')
                    ->where('is_active', '=', 1)
                    ->where('user_type', '=', 10)
                    ->count();
        return $sql;
    }
    
    public function getAllMemberByAdmin(){
        $sql = DB::table('users')
                    ->selectRaw('users.id, users.name, users.email, users.hp, users.is_active, users.active_at, u1.user_code as sp_name, '
                            . 'users.user_code')
                    ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
                    ->where('users.is_active', '=', 1)
                    ->where('users.user_type', '=', 10)
                    ->get();
        return $sql;
    }
    
    public function getUsers($where, $data){
        $sql = DB::table('users')->where($where, '=', $data)->first();
        return $sql;
    }
    
    public function getCheckEmailPhoneUsercode($mail, $phone, $usercode){
        $sqlEmail = DB::table('users')->selectRaw('id')->where('email', '=', $mail)->where('user_type', '=', 10)->count();
        $sqlHP = DB::table('users')->selectRaw('id')->where('hp', '=', $phone)->where('user_type', '=', 10)->count();
        $sqlCode = DB::table('users')->selectRaw('id')->where('user_code', '=', $usercode)->where('user_type', '=', 10)->count();
        $data = (object) array(
            'cekEmail' => $sqlEmail, 'cekHP' => $sqlHP, 'cekCode' => $sqlCode
        );
        return $data;
    }
    
    public function getCheckUsercode($usercode){
        $sqlCode = DB::table('users')->selectRaw('id')->where('user_code', '=', $usercode)->where('user_type', '=', 10)->count();
        $data = (object) array(
            'cekCode' => $sqlCode
        );
        return $data;
    }
    
    public function getCountLastMember(){
        $getCount = DB::table('users')
                    ->selectRaw('id')
                    ->where('user_type', '=', 10)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count();
        $tmp = $getCount+1;
         $getCode = 'U'.date('Ymd').sprintf("%04s", $tmp);
        return $getCode;
    }
    
    public function getCheckKTP($ktp){
        $sql = DB::table('users')->selectRaw('id')->where('ktp', '=', $ktp)->where('user_type', '=', 10)->count();
        return $sql;
    }
    
    public function getAllDownlineSponsor($data){
        $sql = DB::table('users')
                    ->where('user_type', '=', 10)
                    ->where('sponsor_id', '=', $data->id)
                    ->get();
        $getData = null;
        if(count($sql) > 0){
            $getData = $sql;
        }
        return $getData;
    }
    
    public function getAllMemberToPlacement($data){
        $sql = DB::table('users')
                    ->where('sponsor_id', '=', $data->id)
                    ->whereNull('upline_id')
                    ->where('is_active', '=', 1)
                    ->where('user_type', '=', 10)
                    ->get();
        return $sql;
    }
    
    public function getCekMemberToPlacement($id, $data){
        $sql = DB::table('users')
                    ->where('id', '=', $id)
                    ->where('sponsor_id', '=', $data->id)
                    ->whereNull('upline_id')
                    ->where('is_active', '=', 1)
                    ->where('user_type', '=', 10)
                    ->first();
        return $sql;
    }
    
    public function getCekKananKiriFreeKakiKecil($id, $data){
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
    
    public function getCekKananKiriFreeKakiPanjang($uplineDetail, $id){
        $sql = DB::table('users')
                    ->where('user_type', '=', 10)
//                    ->where('upline_id', '=', $id)
                    ->where('upline_detail', 'LIKE', $uplineDetail.'%')
                    ->where(function ($query) {
                        $query->whereNull('kiri_id')
                                    ->orWhereNull('kanan_id');
                    })
                    ->orderBy('id', 'ASC')
                    ->first();
        return $sql;
    }
    
    //binary ver 1.00
    public function getBinary($data){
        $sql1 = DB::table('users')
                    ->where('id', '=', $data->id)
                    ->where('user_type', '=', 10)
                    ->first();
        $sql2 = $sql3 = $sql4 = $sql5 = $sql6 = $sql7 = null;
        if($sql1->kiri_id != null){
            $sql2 = DB::table('users')
                    ->where('id', '=', $sql1->kiri_id)
                    ->where('user_type', '=', 10)
                    ->first();
        }
        if($sql1->kanan_id != null){
            $sql3 = DB::table('users')
                    ->where('id', '=', $sql1->kanan_id)
                    ->where('user_type', '=', 10)
                    ->first();
        }
        
        if($sql2 != null){
            if($sql2->kiri_id != null){
                $sql4 = DB::table('users')
                        ->where('id', '=', $sql2->kiri_id)
                        ->where('user_type', '=', 10)
                        ->first();
            }
            if($sql2->kanan_id != null){
                $sql5 = DB::table('users')
                        ->where('id', '=', $sql2->kanan_id)
                        ->where('user_type', '=', 10)
                        ->first();
            }
        }
        
        if($sql3 != null){
            if($sql3->kiri_id != null){
                $sql6 = DB::table('users')
                        ->where('id', '=', $sql3->kiri_id)
                        ->where('user_type', '=', 10)
                        ->first();
            }
            if($sql3->kanan_id != null){
                $sql7 = DB::table('users')
                        ->where('id', '=', $sql3->kanan_id)
                        ->where('user_type', '=', 10)
                        ->first();
            }
        }
        $dataReturn = array($sql1, $sql2, $sql3, $sql4, $sql5, $sql6, $sql7);
        return $dataReturn;
    }
    
    public function getMyDownline($downline){
        $sql = DB::table('users')
                    ->where('user_type', '=', 10)
                    ->where('is_active', '=', 1)
                    ->where('upline_detail', 'LIKE', $downline.'%')
                    ->orderBy('id', 'ASC')
                    ->get();
        return $sql;
    }
    
    public function getCountMyDownline($downline){
        $sql = DB::table('users')
                    ->selectRaw('id')
                    ->where('user_type', '=', 10)
                    ->where('upline_detail', 'LIKE', $downline.'%')
                    ->count();
        return $sql;
    }
    
    public function getCountMemberActivate($downline, $status){
        $sql = DB::table('users')
                    ->selectRaw('id')
                    ->where('user_type', '=', 10)
                    ->where('is_active', '=', $status)
                    ->where('upline_detail', 'LIKE', $downline.'%')
                    ->count();
        return $sql;
    }
    
    public function getMyDownlineAllStatus($downline, $id){
        $sql = DB::table('users')
                    ->selectRaw('users.id, users.name, users.email, users.hp, users.user_code, users.active_at, users.is_active, '
                            . 'users.package_id, package.name as paket_name')
                    ->leftJoin('package', 'package.id', '=', 'users.package_id')
                    ->where('users.user_type', '=', 10)
                    ->where('users.sponsor_id', '=', $id)
                    ->orWhere('users.upline_detail', 'LIKE', $downline.'%')
                    ->orderBy('users.id', 'ASC')
                    ->get();
        return $sql;
    }
    
    public function getMyDownlineUsername($downline, $username){
        $sql = DB::table('users')
                    ->where('user_type', '=', 10)
                    ->where('is_active', '=', 1)
                    ->where('upline_detail', 'LIKE', $downline.'%')
                    ->where('user_code', 'LIKE', '%'.$username.'%')
                    ->orderBy('id', 'ASC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getCekIdDownline($id, $downline){
        $sql = DB::table('users')
                    ->where('id', '=', $id)
                    ->where('user_type', '=', 10)
                    ->where('is_active', '=', 1)
                    ->where('upline_detail', 'LIKE', $downline.'%')
                    ->orderBy('id', 'ASC')
                    ->first();
        return $sql;
    }
    
}

