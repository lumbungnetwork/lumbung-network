<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Package extends Model {
    
    public function getInsertPackage($data){
        try {
            DB::table('package')->insert($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getUpdatePackage($id, $data){
        try {
            DB::table('package')->where('id', '=', $id)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getPackageId($id){
        $sql = DB::table('package')
                    ->selectRaw('id, name, short_desc, pin, stock_wd, discount')
                    ->where('id', '=', $id)
                    ->whereNull('deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getAllPackage(){
        $sql = DB::table('package')
                    ->selectRaw('id, name, short_desc, pin, stock_wd, discount')
                    ->whereNull('deleted_at')
                    ->orderBy('pin')
                    ->get();
        return $sql;
    }
    
    public function getAllPackageUpgrade($data){
        $sql = DB::table('package')
                    ->selectRaw('id, name, short_desc, pin, stock_wd, discount')
                    ->where('id', '>', $data->package_id)
                    ->whereNull('deleted_at')
                    ->orderBy('pin')
                    ->get();
        return $sql;
    }
    
    public function getMyPackage($data){
        $sql = DB::table('package')
                    ->selectRaw('id, name, short_desc, pin, stock_wd, discount')
                    ->where('id', '=', $data->package_id)
                    ->first();
        return $sql;
    }
    
    public function getMyPackagePin($total_pin){
        $sql = DB::table('package')
                    ->selectRaw('id, name, short_desc, pin, stock_wd, discount')
                    ->where('pin', '=', $total_pin)
                    ->first();
        return $sql;
    }
    
    
}
