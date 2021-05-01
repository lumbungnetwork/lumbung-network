<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transaction;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Transferwd;


class updateBank extends Command
{

    protected $signature = 'update_bank';
    protected $description = 'update bank 1';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ini_set("memory_limit", -1);
        ini_set('max_execution_time', 1500);
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelMember = new Member;
        $getMember = $modelMember->getAllOldMemberByDate('2020-02-15');
        $dataKurang = array();
        foreach ($getMember as $row) {
            $totalBonus = $modelBonus->getTotalBonus($row);
            $totalWD = $modelWD->getTotalDiTransfer($row);
            $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($row);
            $totalTopUp = $modelBonus->getTotalSaldoUserId($row);
            //            $dataAll = (object) array(
            //                'total_bonus' => floor($totalBonus->total_bonus),
            //                'admin_fee' => 5000,
            //                'total_wd' => $totalWD->total_wd,
            //                'total_tunda' => $totalWD->total_tunda,
            //                'total_fee_admin' => $totalWD->total_fee_admin,
            //                'fee_tuntas' => $totalWD->fee_tuntas,
            //                'fee_tunda' => $totalWD->fee_tunda,
            //                'total_wd_eidr' => $totalWDeIDR->total_wd,
            //                'total_tunda_eidr' => $totalWDeIDR->total_tunda,
            //                'total_fee_admin_eidr' => $totalWDeIDR->total_fee_admin,
            //                'fee_tuntas_eidr' => $totalWDeIDR->fee_tuntas,
            //                'fee_tunda_eidr' => $totalWDeIDR->fee_tunda,
            //                'top_up' => $totalTopUp
            //            );
            $total_wd_eidr = $totalWDeIDR->total_wd + $totalWDeIDR->fee_tuntas;
            $saldo = $totalBonus->total_bonus - $totalWD->total_wd - $totalWD->total_tunda - $totalWD->total_fee_admin - ($totalWDeIDR->total_wd + $totalWDeIDR->fee_tuntas + $totalWDeIDR->total_tunda + $totalWDeIDR->fee_tunda);
            if ($saldo < 0) {
                //                if($totalTopUp > 0){
                $dataKurang[] = (object) array(
                    'username' => $row->username,
                    'id' => $row->id,
                    'saldo' => $saldo,
                    'top_up' => $totalTopUp,
                    'batas' => '======',
                    'total_bonus' => floor($totalBonus->total_bonus),
                    'total_wd' => $totalWD->total_wd,
                    'total_tunda' => $totalWD->total_tunda,
                    'total_fee_admin' => $totalWD->total_fee_admin,
                    'fee_tuntas' => $totalWD->fee_tuntas,
                    'fee_tunda' => $totalWD->fee_tunda,
                    'total_wd_eidr' => $totalWDeIDR->total_wd,
                    'total_tunda_eidr' => $totalWDeIDR->total_tunda,
                    'total_fee_admin_eidr' => $totalWDeIDR->total_fee_admin,
                    'fee_tuntas_eidr' => $totalWDeIDR->fee_tuntas,
                    'fee_tunda_eidr' => $totalWDeIDR->fee_tunda,
                    'total_bonus' => floor($totalBonus->total_bonus),
                );
                //                }
            }
        }
        dd($dataKurang);
    }
}
