<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Validation extends Model {
    
    public function getCheckNewSponsor($request){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($request->email == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Email tidak boleh kosong');
            return $canInsert;
        }
        if($request->password == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Password tidak boleh kosong');
            return $canInsert;
        }
        if($request->name == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nama tidak boleh kosong');
            return $canInsert;
        }
        if($request->user_code == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Username tidak boleh kosong');
            return $canInsert;
        }
        if($request->hp == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'No. Handphone tidak boleh kosong');
            return $canInsert;
        }
        if($request->password != $request->repassword){
            $canInsert = (object) array('can' => false, 'pesan' => 'Password tidak sama');
            return $canInsert;
        }
        if(strlen($request->password) < 6){
            $canInsert = (object) array('can' => false, 'pesan' => 'Password terlalu pendek, minimal 6 karakter');
            return $canInsert;
        }
        if(!is_numeric($request->hp)){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor HP menggunakan harus menggunakan angka');
            return $canInsert;
        }
        $cekHP = substr($request->hp, 0, 2);
        if($cekHP != '08'){
            $canInsert = (object) array('can' => false, 'pesan' => 'Awalan Nomor HP menggunakan harus menggunakan angka 08');
            return $canInsert;
        }
        if(strlen($request->hp) < 9){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor HP terlalu pendek minimal 8 digit');
            return $canInsert;
        }
        if(strlen($request->hp) > 13){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor HP terlalu panjang, maksimal 13 angka');
            return $canInsert;
        }
        if(strpos($request->user_code, ' ') !== false){
            $canInsert = (object) array('can' => false, 'pesan' => 'Username tidak boleh ada spasi');
            return $canInsert;
        }
        return $canInsert;
    }
    
    public function getCheckNewProfile($request){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($request->full_name == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nama lengkap harus diisi, sesuai dengan nama pada rekening Bank');
            return $canInsert;
        }
        if($request->ktp == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor KTP harus diisi');
            return $canInsert;
        }
        if($request->alamat == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat harus diisi');
            return $canInsert;
        }
        if($request->provinsi == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pilih Provinsi');
            return $canInsert;
        }
        if($request->kota == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kota harus diisi');
            return $canInsert;
        }
        if($request->kode_pos == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode pos harus diisi');
            return $canInsert;
        }
        return $canInsert;
    }
    
    public function getCheckAddPin($request, $data){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if(!is_numeric($request->total_pin)){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus dalam angka');
            return $canInsert;
        }
        if($request->total_pin <= 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus diatas 0');
            return $canInsert;
        }
        if($data->member_status == 2){
            if($request->total_pin < 100){
                $canInsert = (object) array('can' => false, 'pesan' => 'Anda Director Stockist, maka anda harus membeli pin minimal 100');
                return $canInsert;
            }
        }
        return $canInsert;
    }
    
    public function getCheckAddBank($request){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($request->account_no == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih nomor rekening');
            return $canInsert;
        }
        if($request->bank_name == 'none'){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih bank');
            return $canInsert;
        }
        if(!is_numeric($request->account_no)){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor Rekening harus dalam angka');
            return $canInsert;
        }
        return $canInsert;
    }
    
    public function getCheckPengiriman($request){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($request->total_pin == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Total Pin harus diisi');
            return $canInsert;
        }
        if(!is_numeric($request->total_pin)){
            $canInsert = (object) array('can' => false, 'pesan' => 'Total Pin harus dalam angka');
            return $canInsert;
        }
        return $canInsert;
    }
    
    public function getCekPinForUpgrade($data){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($data->total_sisa_pin <= 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Total Pin harus lebis besar dari 0');
            return $canInsert;
        }
        if($data->sisa_pin < $data->total_sisa_pin){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin anda tidak cukup untuk melakukan upgrade');
            return $canInsert;
        }
        return $canInsert;
    }
    
    public function getCheckRO($request, $getTotalPin, $data){
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if(!is_numeric($request->total_pin)){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus dalam angka');
            return $canInsert;
        }
        if($request->total_pin <= 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus diatas 0');
            return $canInsert;
        }
        $sum_pin_masuk = 0;
        $sum_pin_keluar = 0;
        if($getTotalPin->sum_pin_masuk != null){
            $sum_pin_masuk = $getTotalPin->sum_pin_masuk;
        }
        if($getTotalPin->sum_pin_keluar != null){
            $sum_pin_keluar = $getTotalPin->sum_pin_keluar;
        }
        $total = $sum_pin_masuk - $sum_pin_keluar;
        if($total < $request->total_pin){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin yang anda masukan tidak cukup untuk melakukan repeat order');
            return $canInsert;
        }
        return $canInsert;
    }
    
    public function getCheckWD($data){
        if($data->bank == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data profil dan data bank');
            return $canInsert;
        }
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($data->saldo < 20000){
            $canInsert = (object) array('can' => false, 'pesan' => 'Saldo yang tersedia tidak mencukupi untuk withdraw. batas minimum withdraw adalah Rp. 20.000');
            return $canInsert;
        }
        if(($data->saldo -  $data->admin_fee) < 20000){
            $canInsert = (object) array('can' => false, 'pesan' => 'Saldo yang tersedia tidak mencukupi untuk withdraw. batas minimum withdraw adalah Rp. 20.000 dengan biaya admin (fee) Rp. 6.500');
            return $canInsert;
        }
        return $canInsert;
    }
    
}
