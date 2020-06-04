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
use App\Model\Bonussetting;
use App\Model\Sales;
use App\Model\Pengiriman;
use App\Model\Transaction;
use App\Model\Membership;
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
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $modelPackage = New Package;
        $modelSales = new Sales;
        $sponsor_id = $dataUser->sponsor_id;
        $dataSponsor = $modelMember->getUsers('id', $sponsor_id);
        if($dataUser->is_active == 0){
            $getCheckNewOrder = $modelMemberPackage->getCountNewMemberPackageInactive($dataUser);
        }
        if($dataUser->is_active == 1){
            $getCheckNewOrder = $modelMemberPackage->getCountMemberPackageInactive($dataUser);
        }
//        $totalBonus = $modelBonus->getTotalBonus($dataUser);
//        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
//        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
//        $getMyPackage = $modelPackage->getPackageId($dataUser->package_id);
//        $stock_wd = 0;
//        if($getMyPackage != null){
//            $stock_wd = $getMyPackage->stock_wd;
//        }
        $kanan = 0;
//        if($dataUser->kanan_id != null){
//            $downlineKanan = $dataUser->upline_detail.',['.$dataUser->id.']'.',['.$dataUser->kanan_id.']';
//            if($dataUser->upline_detail == null){
//                $downlineKanan = '['.$dataUser->id.']'.',['.$dataUser->kanan_id.']';
//            }
//            $kanan = $modelMember->getCountMyDownline($downlineKanan) + 1;
//        }
        $kiri = 0;
//        if($dataUser->kiri_id != null){
//            $downlineKiri = $dataUser->upline_detail.',['.$dataUser->id.']'.',['.$dataUser->kiri_id.']';
//            if($dataUser->upline_detail == null){
//                $downlineKiri = '['.$dataUser->id.']'.',['.$dataUser->kiri_id.']';
//            }
//            $kiri = $modelMember->getCountMyDownline($downlineKiri) + 1;
//        }
        $downline_saya = '['.$dataUser->id.']';
        $total_tdkAktif = $modelMember->getCountMemberActivate($downline_saya, 0);
        $dataDashboard = (object) array(
//            'total_bonus' => floor($totalBonus->total_bonus),
//            'total_wd' => $totalWD->total_wd,
//            'total_tunda' => $totalWD->total_tunda,
//            'total_fee_admin' => $totalWD->total_fee_admin,
//            'fee_tuntas' => $totalWD->fee_tuntas,
//            'fee_tunda' => $totalWD->fee_tunda,
//            'total_wd_eidr' => $totalWDeIDR->total_wd,
//            'total_tunda_eidr' => $totalWDeIDR->total_tunda,
//            'total_fee_admin_eidr' => $totalWDeIDR->total_fee_admin,
//            'fee_tuntas_eidr' => $totalWDeIDR->fee_tuntas,
//            'fee_tunda_eidr' => $totalWDeIDR->fee_tunda,
//            'stock_wd' => $stock_wd,
            'kanan' => $kanan,
            'kiri' => $kiri,
            'member_tdk_aktif' => $total_tdkAktif,
//            'paket_name' => $getMyPackage->name
        );
        
        $getMyPeringkat = $modelBonusSetting->getPeringkatByType($dataUser->member_type);
//        $getSales = $modelSales->getMemberSales($dataUser->id);
        $getSales = $modelSales->getMemberMasterSalesMonthYear($dataUser->id, date('m'), date('Y'));
        $mySales = 0;
        if($getSales != null){
//            $mySales = $getSales->jml_price;
            $mySales = $getSales->month_sale_price;
        }
        $image = '';
        $name = 'Member Biasa';
        $canClaim = false;
        if($getMyPeringkat != null){
            $isCanClaim = $modelBonus->getMemberRewardByUser($dataUser, $getMyPeringkat->id);
            if($isCanClaim == null){
                $canClaim = true;
            }
            $image = $getMyPeringkat->image;
            $name = $getMyPeringkat->name;
        }
        $dataMy = (object) array(
            'name' => $name,
            'image' => $image,
            'sales' => $mySales
        );
        $getDataMemberBuy = $modelSales->getMemberSalesBuy($dataUser->id);
        $getDataVendorMemberBuy = $modelSales->getMemberVSalesBuy($dataUser->id);
        $getMonth = $modelSales->getThisMonth();
        $getData = $modelSales->getMemberMasterSalesHistory($dataUser->id, $getMonth);
        $getDataVSales = $modelSales->getMemberVMasterSalesHistory($dataUser->id, $getMonth);
        $sum = 0;
        if($getData != null){
            foreach($getData as $row){
                if($row->status == 2){
                    $sum += $row->sale_price;
                }
            }
        }
        $vsum = 0;
        if($getDataVSales != null){
            foreach($getDataVSales as $rowv){
                if($rowv->status == 2){
                    $vsum += $rowv->sale_price;
                }
            }
        }
        return view('member.home.dashboard')
                    ->with('headerTitle', 'Dashboard')
                    ->with('dataOrder', $getCheckNewOrder)
                    ->with('dataAll', $dataDashboard)
                    ->with('dataSponsor', $dataSponsor)
                    ->with('dataMy', $dataMy)
                    ->with('getDataMemberBuy', $getDataMemberBuy)
                    ->with('getDataVendorMemberBuy', $getDataVendorMemberBuy)
                    ->with('sum', $sum)
                    ->with('vsum', $vsum)
                    ->with('dataUser', $dataUser);
    }
    
    public function getMemberNetworking(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelPin = new Pin;
        $modelPengiriman = new Pengiriman;
        $modelMember = new Member;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $getTotalPinTerkirim = $modelPengiriman->getCekPinTuntasTerkirim($dataUser);
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
        $dataNetworking = (object) array(
            'kanan' => $kanan,
            'kiri' => $kiri,
        );
        return view('member.home.networking')
                    ->with('dataPin', $getTotalPin)
                    ->with('dataTerkirim', $getTotalPinTerkirim)
                    ->with('dataAll', $dataNetworking)
                    ->with('dataUser', $dataUser);
    }
    
    public function getMemberWallet(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
        $totalBonusRoyalti = $modelBonus->getTotalBonusRoyalti($dataUser);
        $totalWDRoyalti = $modelWD->getTotalDiTransferRoyalti($dataUser);
        $dataAll = (object) array(
            'total_bonus' => floor($totalBonus->total_bonus),
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'admin_fee' => 6500,
            'total_fee_admin' => $totalWD->total_fee_admin,
            'fee_tuntas' => $totalWD->fee_tuntas,
            'fee_tunda' => $totalWD->fee_tunda,
            'total_wd_eidr' => $totalWDeIDR->total_wd,
            'total_tunda_eidr' => $totalWDeIDR->total_tunda,
            'total_fee_admin_eidr' => $totalWDeIDR->total_fee_admin,
            'fee_tuntas_eidr' => $totalWDeIDR->fee_tuntas,
            'fee_tunda_eidr' => $totalWDeIDR->fee_tunda,
            'total_bonus_ro' => floor($totalBonusRoyalti->total_bonus),
            'total_wd_ro' => $totalWDRoyalti->total_wd,
            'total_tunda_ro' => $totalWDRoyalti->total_tunda,
            'total_fee_admin_ro' => $totalWDRoyalti->total_fee_admin,
        );
        return view('member.home.wallet')
                    ->with('dataAll', $dataAll)
                    ->with('dataUser', $dataUser);
    }
    
    public function getMemberExplorers(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        return view('member.home.explorer')
                    ->with('headerTitle', 'Explorer')
                    ->with('dataUser', $dataUser);
    }
    
    public function getMemberMyAccount(){
        $dataUser = Auth::user();
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelPin = new Pin;
        $modelTrans = New Transaction;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataUser);
        return view('member.home.account')
                    ->with('dataDeposit', $getTotalDeposit)
                    ->with('dataTarik', $getTransTarik)
                    ->with('dataUser', $dataUser);
    }
    
    public function getMemberNotification(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelMemberPackage = New Memberpackage;
        $modelMember = new Member;
        $modelBonus = new Bonus;
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $modelPackage = New Package;
        $modelSales = new Sales;
        $sponsor_id = $dataUser->sponsor_id;
        if($dataUser->is_active == 0){
            $getCheckNewOrder = $modelMemberPackage->getCountNewMemberPackageInactive($dataUser);
        }
        if($dataUser->is_active == 1){
            $getCheckNewOrder = $modelMemberPackage->getCountMemberPackageInactive($dataUser);
        }
        $getDataMemberBuy = $modelSales->getMemberSalesBuy($dataUser->id);
        $getDataVendorMemberBuy = $modelSales->getMemberVSalesBuy($dataUser->id);
        return view('member.home.notification')
                    ->with('dataOrder', $getCheckNewOrder)
                    ->with('getDataMemberBuy', $getDataMemberBuy)
                    ->with('getDataVendorMemberBuy', $getDataVendorMemberBuy)
                    ->with('dataUser', $dataUser);
    }
    

}
