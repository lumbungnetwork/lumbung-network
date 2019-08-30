<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Pengiriman extends Model {
    
    public function getInsertPengiriman($data){
        try {
            DB::table('pengiriman_paket')->insert($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getUpdatePengiriman($id, $data){
        try {
            DB::table('pengiriman_paket')->where('id', '=', $id)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getMyPengiriman($data){
        $sql = DB::table('pengiriman_paket')
                    ->selectRaw('id, total_pin, alamat_kirim, status, kurir_name, no_resi')
                    ->where('user_id', '=', $data->id)
                    ->get();
        $returnData = null;
        if(count($sql) > 0){
            $returnData = $sql;
        }
        return $returnData;
    }
    
    public function getCekPinPengiriman($data){
        $sql = DB::table('pengiriman_paket')
                    ->selectRaw('sum(total_pin) as total_pin_terkirim')
                    ->where('user_id', '=', $data->id)
                    ->where('status', '!=', 2)
                    ->first();
        $totalTerkirim = 0;
        if($sql->total_pin_terkirim != null){
            $totalTerkirim = $sql->total_pin_terkirim;
        }
        return $totalTerkirim;
    }
    
    public function getAdmPengiriman(){
        $sql = DB::table('pengiriman_paket')
                        ->join('users', 'pengiriman_paket.user_id', '=', 'users.id')
                        ->selectRaw('users.name, users.hp, '
                                . 'pengiriman_paket.id, pengiriman_paket.total_pin, pengiriman_paket.alamat_kirim, pengiriman_paket.status, '
                                . 'pengiriman_paket.user_id, pengiriman_paket.kurir_name, pengiriman_paket.no_resi')
                        ->get();
        $returnData = null;
        if(count($sql) > 0){
            $returnData = $sql;
        }
        return $returnData;
    }
    
    public function getAdmPengirimanByID($id, $user_id){
        $sql = DB::table('pengiriman_paket')
                        ->join('users', 'pengiriman_paket.user_id', '=', 'users.id')
                        ->selectRaw('users.name, users.hp, '
                                . 'pengiriman_paket.id, pengiriman_paket.total_pin, pengiriman_paket.alamat_kirim, pengiriman_paket.status,'
                                . 'pengiriman_paket.user_id, pengiriman_paket.kurir_name, pengiriman_paket.no_resi')
                        ->where('pengiriman_paket.id', '=', $id)
                        ->where('pengiriman_paket.user_id', '=', $user_id)
                        ->first();
        return $sql;
    }
    
    public function getCekPinTuntasTerkirim($data){
        $sql = DB::table('pengiriman_paket')
                    ->selectRaw('sum(total_pin) as total_pin_terkirim')
                    ->where('user_id', '=', $data->id)
                    ->where('status', '=', 1)
                    ->first();
        $totalTerkirim = 0;
        if($sql->total_pin_terkirim != null){
            $totalTerkirim = $sql->total_pin_terkirim;
        }
        return $totalTerkirim;
    }
    
    
    
}
