<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Admin extends Model {
    
    public function getInsertUser($data){
        try {
            DB::table('users')->insert($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getUpdateMember($fieldName, $name, $data){
        try {
            DB::table('users')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getAllUserAdmin($dataUser){
        if($dataUser->user_type == 1){
            $sql = DB::table('users')
                    ->selectRaw('id, email, name, user_type')
                    ->whereIn('user_type', array(2, 3))
                    ->get();
        } else {
            $sql = DB::table('users')
                    ->selectRaw('id, email, name, user_type')
                    ->where('user_type', '=', 3)
                    ->get();
        }
        return $sql;
    }
    
    public function getCekNewUsername($username){
        $sql = DB::table('users')
                    ->selectRaw('id')
                    ->where('users.email', '=', $username)
                    ->first();
        return $sql;
    }
    
    public function getAdminById($id){
        $sql = DB::table('users')
                    ->selectRaw('id, email, name, user_type')
                    ->where('id', '=', $id)
                    ->first();
        return $sql;
    }
    
    public function getInsertDaerah($data){
        try {
            DB::table('daerah')->insert($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
}

