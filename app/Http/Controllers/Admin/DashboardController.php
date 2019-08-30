<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Pin;
use App\Model\Member;
use App\Model\Memberpackage;
use App\Model\Bonus;
use App\Model\Transferwd;
use App\Model\Package;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {

    public function __construct(){
        
    }
    
    public function getDashboard(){
        $dataUser = Auth::user();
        if(in_array($dataUser->user_type, array(10))){
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $modelMember = new Member;
        $getTotalPin = $modelPin->getTotalPinAdmin();
        $getAllmember = $modelMember->getAllMember();
        return view('admin.home.admin-dashboard')
                    ->with('headerTitle', 'Dashboard')
                    ->with('dataPin', $getTotalPin)
                    ->with('totalMember', $getAllmember)
                    ->with('dataUser', $dataUser);
        
    }
    
    public function getMemberDashboard(){
        $dataUser = Auth::user();
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelMemberPackage = New Memberpackage;
        $modelMember = new Member;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelPackage = New Package;
        $sponsor_id = $dataUser->sponsor_id;
        $dataSponsor = $modelMember->getUsers('id', $sponsor_id);
        if($dataUser->is_active == 0){
            $getCheckNewOrder = $modelMemberPackage->getCountNewMemberPackageInactive($dataUser);
        }
        if($dataUser->is_active == 1){
            $getCheckNewOrder = $modelMemberPackage->getCountMemberPackageInactive($dataUser);
        }
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $getMyPackage = $modelPackage->getPackageId($dataUser->package_id);
        $stock_wd = 0;
        if($getMyPackage != null){
            $stock_wd = $getMyPackage->stock_wd;
        }
        $kanan = 0;
        if($dataUser->kanan_id != null){
            $downlineKanan = $dataUser->upline_detail.',['.$dataUser->id.']'.',['.$dataUser->kanan_id.']';
            if($dataUser->upline_detail == null){
                $downlineKanan = '['.$dataUser->id.']'.',['.$dataUser->kanan_id.']';
            }
            $kanan = $modelMember->getCountMyDownline($downlineKanan) + 1;
        }
        $kiri = 0;
        if($dataUser->kiri_id != null){
            $downlineKiri = $dataUser->upline_detail.',['.$dataUser->id.']'.',['.$dataUser->kiri_id.']';
            if($dataUser->upline_detail == null){
                $downlineKiri = '['.$dataUser->id.']'.',['.$dataUser->kiri_id.']';
            }
            $kiri = $modelMember->getCountMyDownline($downlineKiri) + 1;
        }
        $downline_saya = '['.$dataUser->id.']';
        $total_tdkAktif = $modelMember->getCountMemberActivate($downline_saya, 0);
        $dataDashboard = (object) array(
            'total_bonus' => $totalBonus->total_bonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'stock_wd' => $stock_wd,
            'kanan' => $kanan,
            'kiri' => $kiri,
            'member_tdk_aktif' => $total_tdkAktif,
            'paket_name' => $getMyPackage->name
        );
        //cek upgrade 30 hari dari aktifasi, klo MS jg jgn munculin
        return view('member.home.dashboard')
                    ->with('headerTitle', 'Dashboard')
                    ->with('dataOrder', $getCheckNewOrder)
                    ->with('dataAll', $dataDashboard)
                    ->with('dataSponsor', $dataSponsor)
                    ->with('dataUser', $dataUser);
    }
    

}
