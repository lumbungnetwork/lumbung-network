<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    use HasFactory;

    public function getKabupatenKotaByProvinsi($provinsi)
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

    public function getKecamatanByKabupatenKota($kota)
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

    public function getKelurahanByKecamatan($kec)
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

    public function getProvinsiByID($id)
    {
        $sql = DB::table('provinsi')
            ->where('id_prov', '=', $id)
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
}
