<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Bonussetting;
use App\Model\Binaryhistory;
use App\Model\Historyindex;

class CronBonusPasang extends Command {
    
    protected $signature = 'bonus_pasang';
    protected $description = 'Cron Bonus Pasang active member data kemarin';

    public function __construct() {
        parent::__construct();
    }
    
    public function handle() {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 1500);
        $modelMember = New Member;
        $modelBinaryHistory = new Binaryhistory;
        $modelBonusSetting = new Bonussetting;
        $modelBonus = new Bonus;
        $modelHistoryIndex = New Historyindex;
        $date = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        $cekHistoryIndex = $modelHistoryIndex->getHistoryIndex($date);
        if($cekHistoryIndex != null){
            dd('index stop here');
        }
        $totalActivateDate = $modelMember->getCountMemberByDate($date);
        if($totalActivateDate == 0){
            dd('activated stop here');
        }
        $totalPasang = 0;
        $cekAwal = array();
        $getMemberDate = $modelMember->getAllMemberByDate($date);
        foreach($getMemberDate as $row){
            $kiri = 0;
            if($row->kiri_id != null){
                $downlineKiri = $row->upline_detail.',['.$row->id.']'.',['.$row->kiri_id.']';
                if($row->upline_detail == null){
                    $downlineKiri = '['.$row->id.']'.',['.$row->kiri_id.']';
                }
                $kiriOuter = $modelMember->getNewCountOuterDownlineByDate($downlineKiri, $date);
                $kiriInner = $modelMember->getNewCountInnerDownlineByDate($row->kiri_id, $date);
                $kiri = $kiriOuter + $kiriInner;
            }
            $kanan = 0;
            if($row->kanan_id != null){
                $downlineKanan = $row->upline_detail.',['.$row->id.']'.',['.$row->kanan_id.']';
                if($row->upline_detail == null){
                    $downlineKanan = '['.$row->id.']'.',['.$row->kanan_id.']';
                }
                $kananOuter = $modelMember->getNewCountOuterDownlineByDate($downlineKanan, $date);
                $kananInner = $modelMember->getNewCountInnerDownlineByDate($row->kanan_id, $date);
                $kanan = $kananOuter + $kananInner;
            }
            $getHistoryBinary = $modelBinaryHistory->getBinaryHistory($row->id);
            $kiriCheck = $kiri - $getHistoryBinary->sum_total_kiri;
            $kananCheck = $kanan - $getHistoryBinary->sum_total_kanan;
            if($kiriCheck > 0 && $kananCheck > 0){
                $pasangan = $kiriCheck;
                if($kiriCheck > $kananCheck){
                    $pasangan = $kananCheck;
                }
                if($kiriCheck < $kananCheck){
                    $pasangan = $kiriCheck;
                }
                $cekAwal[] = (object) array(
                    'user_id' => $row->id,
                    'total_pasang' => $pasangan
                );
                $totalPasang += $pasangan;
            }
        }
        $getBonusStart =$modelBonusSetting->getActiveBonusStart();
        if($totalPasang == 0){
            dd('done here');
        }
        $indexBonus = $totalActivateDate * $getBonusStart->start_price / $totalPasang;
        if($indexBonus > 20000){
            $indexBonus = 20000;
        }
        $dataInsertIndex = array(
            'total_binary' => $totalPasang,
            'total_activated' => $totalActivateDate,
            'bonus_index' => $indexBonus,
            'index_date' => $date,
            'type_setting' => $getBonusStart->id,
            'bonus_pasangan_setting' => $getBonusStart->start_price
        );
        foreach($cekAwal as $rowAkhir){
            $bonus_price = $rowAkhir->total_pasang * $indexBonus;
            $dataInsertBonus = array(
                'user_id' => $rowAkhir->user_id,
                'type' => 2,
                'bonus_price' => $bonus_price,
                'bonus_date' => $date,
                'poin_type' => 1,
                'total_binary' => $rowAkhir->total_pasang,
                'total_activated' => $totalActivateDate,
                'total_all_binary' => $totalPasang,
                'bonus_index' => $indexBonus,
                'index_date' => $date,
                'bonus_setting' => $getBonusStart->start_price
            );
            $modelBonus->getInsertBonusMember($dataInsertBonus);
            $dataInsertHistoryBinary = array(
                'user_id' => $rowAkhir->user_id,
                'total_left' => $rowAkhir->total_pasang,
                'total_right' => $rowAkhir->total_pasang,
                'binary_date' => $date
            );
            $modelBinaryHistory->getInsertBinaryHistory($dataInsertHistoryBinary);
        }
        $modelHistoryIndex->getInsertHistoryIndex($dataInsertIndex);
        dd('done bonus tgl '.$date);
    }
    
}
