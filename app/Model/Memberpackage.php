<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Memberpackage extends Model {
    
    public function getInsertMemberPackage($data){
        try {
            $lastInsertedID = DB::table('member_package')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateMemberPackage($fieldName, $name, $data){
        try {
            DB::table('member_package')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getCheckMemberPackage($where, $data){
        $sql = DB::table('member_package')
                    ->selectRaw('id')
                    ->where($where, '=', $data)
                    ->first();
        return $sql;
    }
    
    public function getCountMemberPackageInactive($data){
        $sql = DB::table('member_package')
                    ->selectRaw('id')
                    ->where('user_id', '=', $data->id)
                    ->where('status', '=', 0)
                    ->count();
        return $sql;
    }
    
    public function getCountNewMemberPackageInactive($data){
        $sql = DB::table('member_package')
                    ->selectRaw('id')
                    ->where('request_user_id', '=', $data->id)
                    ->where('status', '=', 0)
                    ->count();
        return $sql;
    }
    
    public function getMemberPackageInactive($data){
        $sql = DB::table('member_package')
                    ->join('users', 'member_package.request_user_id', '=', 'users.id')
                    ->selectRaw('member_package.id, member_package.request_user_id, '
                            . 'member_package.package_id, member_package.name, member_package.short_desc, member_package.total_pin, '
                            . 'users.email, users.hp, users.name as name_user')
                    ->where('member_package.user_id', '=', $data->id)
                    ->where('member_package.status', '=', 0)
                    ->get();
        return $sql;
    }
    
    public function getDetailMemberPackageInactive($id, $data){
        $sql = DB::table('member_package')
                    ->join('users', 'member_package.request_user_id', '=', 'users.id')
                    ->selectRaw('member_package.id, member_package.request_user_id, '
                            . 'member_package.package_id, member_package.name, member_package.short_desc, member_package.total_pin, '
                            . 'users.email, users.hp, users.name as name_user, member_package.created_at')
                    ->where('member_package.id', '=', $id)
                    ->where('member_package.user_id', '=', $data->id)
                    ->where('member_package.status', '=', 0)
                    ->first();
        return $sql;
    }
    
    
}

