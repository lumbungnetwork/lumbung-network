<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Validation extends Model
{

    public function getCheckNewSponsor($request)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->email == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Email tidak boleh kosong');
            return $canInsert;
        }
        if ($request->password == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password tidak boleh kosong');
            return $canInsert;
        }
        if ($request->name == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nama tidak boleh kosong');
            return $canInsert;
        }
        if ($request->user_code == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username tidak boleh kosong');
            return $canInsert;
        }
        if ($request->hp == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'No. Handphone tidak boleh kosong');
            return $canInsert;
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Format email salah');
            return $canInsert;
        }
        if ($request->password != $request->repassword) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password tidak sama');
            return $canInsert;
        }
        if (strlen($request->password) < 6) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password terlalu pendek, minimal 6 karakter');
            return $canInsert;
        }
        if (!is_numeric($request->hp)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor HP menggunakan harus menggunakan angka');
            return $canInsert;
        }
        $cekHP = substr($request->hp, 0, 2);
        if ($cekHP != '08') {
            $canInsert = (object) array('can' => false, 'pesan' => 'Awalan Nomor HP menggunakan harus menggunakan angka 08');
            return $canInsert;
        }
        if (strlen($request->hp) < 9) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor HP terlalu pendek minimal 8 digit');
            return $canInsert;
        }
        if (strlen($request->hp) > 13) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor HP terlalu panjang, maksimal 13 angka');
            return $canInsert;
        }
        if (strpos($request->user_code, ' ') !== false) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username tidak boleh ada spasi');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckNewProfile($request)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->full_name == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nama lengkap harus diisi, sesuai dengan nama pada rekening Bank');
            return $canInsert;
        }
        if ($request->alamat == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat harus diisi');
            return $canInsert;
        }
        if ($request->provinsi == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pilih Provinsi');
            return $canInsert;
        }
        if ($request->kota == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kota harus diisi');
            return $canInsert;
        }
        if ($request->kecamatan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kecamatan harus diisi');
            return $canInsert;
        }
        if ($request->kelurahan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kelurahan harus diisi');
            return $canInsert;
        }
        if ($request->kode_pos == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode pos harus diisi');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckEditAddress($request)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->alamat == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat harus diisi');
            return $canInsert;
        }
        if ($request->provinsi == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pilih Provinsi');
            return $canInsert;
        }
        if ($request->kota == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kota harus diisi');
            return $canInsert;
        }
        if ($request->kecamatan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kecamatan harus diisi');
            return $canInsert;
        }
        if ($request->kelurahan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kelurahan harus diisi');
            return $canInsert;
        }
        if ($request->kode_pos == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode pos harus diisi');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckAddPin($request, $data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if (!is_numeric($request->total_pin)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus dalam angka');
            return $canInsert;
        }
        if ($request->total_pin <= 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus diatas 0');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckAddBank($request)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->account_no == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih nomor rekening');
            return $canInsert;
        }
        if ($request->bank_name == 'none') {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih bank');
            return $canInsert;
        }
        if (!is_numeric($request->account_no)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nomor Rekening harus dalam angka');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckPengiriman($request)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->total_pin == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Total Pin harus diisi');
            return $canInsert;
        }
        if (!is_numeric($request->total_pin)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Total Pin harus dalam angka');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCekPinForUpgrade($data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($data->total_sisa_pin <= 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Total Pin harus lebis besar dari 0');
            return $canInsert;
        }
        if ($data->sisa_pin < $data->total_sisa_pin) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin anda tidak cukup untuk melakukan upgrade');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckRO($request, $getTotalPin, $data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if (!is_numeric($request->total_pin)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus dalam angka');
            return $canInsert;
        }
        if ($request->total_pin <= 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin harus diatas 0');
            return $canInsert;
        }
        $sum_pin_masuk = 0;
        $sum_pin_keluar = 0;
        if ($getTotalPin->sum_pin_masuk != null) {
            $sum_pin_masuk = $getTotalPin->sum_pin_masuk;
        }
        if ($getTotalPin->sum_pin_keluar != null) {
            $sum_pin_keluar = $getTotalPin->sum_pin_keluar;
        }
        $total = $sum_pin_masuk - $sum_pin_keluar;
        if ($total < $request->total_pin) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin yang anda masukan tidak cukup untuk melakukan Resubscribe. Silakan beli Pin');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckWD($data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($data->bank == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data profil dan data bank');
            return $canInsert;
        }
        if ($data->req_wd < 20000) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Batas minimum withdraw adalah Rp. 20.000');
            return $canInsert;
        }
        if (($data->req_wd - $data->saldo) > 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pengajuan withdrawal anda kurang dari sisa saldo');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckWDeIDR($data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($data->tron == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat TRON');
            return $canInsert;
        }
        if ($data->req_wd < 10000) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Batas minimum Penarikan ke eIDR adalah Rp10.000,-');
            return $canInsert;
        }
        if (($data->req_wd - $data->saldo) > 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Saldo tidak mencukupi!');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckRequestStockist($data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($data->alamat->provinsi == null || $data->alamat->kota == null || $data->alamat->kecamatan == null || $data->alamat->kelurahan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Data Alamat Profil belum lengkap.');
            return $canInsert;
        }
        if ($data->syarat1 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum menyetujui telah memiliki 3 Hak Usaha');
            return $canInsert;
        }
        if ($data->syarat2 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum menyanggupi modal belanja awal senilai minimal Rp2.000.000,00');
            return $canInsert;
        }
        if ($data->syarat3 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum menyatakan bahwa di RW/Lingkungan tempat tinggal saya BELUM ADA Stokis Lumbung Network.');
            return $canInsert;
        }
        if ($data->syarat4 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum Saya telah membaca dan menyetujui Peraturan dan Kode Etik Stokis Lumbung Network.');
            return $canInsert;
        }
        //        if($data->total_sp < 3){
        //            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memenuhi jumlah Hak Usaha sebanyak 3, atas nama sendiri');
        //            return $canInsert;
        //        }
        if ($data->hu1 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 1 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu2 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 3 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu3 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 3 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu1 == $data->hu2 || $data->hu1 == $data->hu3 || $data->hu2 == $data->hu3) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha tidak boleh ada yang sama');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckRequestVendor($data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($data->alamat->provinsi == null || $data->alamat->kota == null || $data->alamat->kecamatan == null || $data->alamat->kelurahan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Data Alamat Profil belum lengkap.');
            return $canInsert;
        }
        if ($data->syarat1 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum menyetujui telah memiliki 5 Hak Usaha');
            return $canInsert;
        }
        if ($data->syarat2 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum menyanggupi modal belanja awal senilai minimal Rp2.000.000,00');
            return $canInsert;
        }
        if ($data->syarat3 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum menyatakan bahwa di RW/Lingkungan tempat tinggal saya BELUM ADA Stokis Lumbung Network.');
            return $canInsert;
        }
        if ($data->syarat4 == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum Saya telah membaca dan menyetujui Peraturan dan Kode Etik Stokis Lumbung Network.');
            return $canInsert;
        }
        //        if($data->total_sp < 3){
        //            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memenuhi jumlah Hak Usaha sebanyak 3, atas nama sendiri');
        //            return $canInsert;
        //        }
        if ($data->hu1 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 1 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu2 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 2 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu3 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 3 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu4 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 4 Anda tidak ada');
            return $canInsert;
        }
        if ($data->hu5 == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha 5 Anda tidak ada');
            return $canInsert;
        }
        if (
            $data->hu1 == $data->hu2 || $data->hu1 == $data->hu3 || $data->hu1 == $data->hu4 || $data->hu1 == $data->hu5
            || $data->hu2 == $data->hu3 || $data->hu2 == $data->hu4 || $data->hu2 == $data->hu5
            || $data->hu3 == $data->hu4 || $data->hu3 == $data->hu5
            || $data->hu4 == $data->hu5
        ) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Username pada Hak Usaha tidak boleh ada yang sama');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckTopUp($data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($data->tron == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return $canInsert;
        }
        if ($data->req_topup < 20000) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Batas minimum Top Up Saldo ke eIDR adalah Rp. 20.000');
            return $canInsert;
        }
        return $canInsert;
    }

    public function getCheckAddDeposit($request, $data)
    {
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if (!is_numeric($request->total_deposit)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nominal harus dalam angka');
            return $canInsert;
        }
        if ($request->total_deposit <= 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nominal harus diatas 0');
            return $canInsert;
        }
        return $canInsert;
    }
}
