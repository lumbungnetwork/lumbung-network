<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LocalWallet;
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
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{

    public function __construct()
    {
    }

    public function getDashboard()
    {
        $dataUser = Auth::user();
        if (in_array($dataUser->user_type, array(10))) {
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

    public function getMemberDashboard()
    {
        $dataUser = Auth::user();
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMemberPackage = new Memberpackage;
        $modelMember = new Member;
        $modelBonus = new Bonus;
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $modelPackage = new Package;
        $modelSales = new Sales;
        $sponsor_id = $dataUser->sponsor_id;
        $dataSponsor = $modelMember->getUsers('id', $sponsor_id);
        if ($dataUser->is_active == 0) {
            $getCheckNewOrder = $modelMemberPackage->getCountNewMemberPackageInactive($dataUser);
        }
        if ($dataUser->is_active == 1) {
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
        $downline_saya = '[' . $dataUser->id . ']';
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
        if ($getSales != null) {
            //            $mySales = $getSales->jml_price;
            $mySales = $getSales->month_sale_price;
        }
        $image = '';
        $name = 'Member Biasa';
        $canClaim = false;
        if ($getMyPeringkat != null) {
            $isCanClaim = $modelBonus->getMemberRewardByUser($dataUser, $getMyPeringkat->id);
            if ($isCanClaim == null) {
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
        $getDataVendorPPOBMemberBuy = $modelSales->getMemberVPPOB($dataUser->id);
        $getMonth = $modelSales->getThisMonth();
        $getData = $modelSales->getMemberMasterSalesHistory($dataUser->id, $getMonth);
        $getDataVSales = $modelSales->getMemberVMasterSalesHistory($dataUser->id, $getMonth);
        $getDataPPOBPulsaData = $modelSales->getMembePPOBSalesHistoryPulsaData($dataUser->id, $getMonth);
        $getDataPPOBSelainPulsaData = $modelSales->getMembePPOBSalesHistorySelainPulsaData($dataUser->id, $getMonth);
        $sum = 0;
        if ($getData != null) {
            foreach ($getData as $row) {
                if ($row->status == 2) {
                    $sum += $row->sale_price;
                }
            }
        }
        $vsum = 0;
        if ($getDataVSales != null) {
            foreach ($getDataVSales as $rowv) {
                if ($rowv->status == 2) {
                    $vsum += $rowv->sale_price;
                }
            }
        }
        $ppobSum = 0;
        if ($getDataPPOBPulsaData != null) {
            foreach ($getDataPPOBPulsaData as $rowppob) {
                if ($rowppob->status == 2) {
                    $ppobSum += $rowppob->sale_price;
                }
            }
        }
        $ppobSumSelainPulsaData = $getDataPPOBSelainPulsaData->total_ppob * 2500;
        $belanjaVendor = $vsum + $ppobSum + $ppobSumSelainPulsaData;

        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $userStakedLMB = $modelBonus->getUserStakedLMB($dataUser->id);

        return view('member.home.dashboard')
            ->with('headerTitle', 'Dashboard')
            ->with('dataOrder', $getCheckNewOrder)
            ->with('dataAll', $dataDashboard)
            ->with('dataSponsor', $dataSponsor)
            ->with('dataMy', $dataMy)
            ->with('getDataMemberBuy', $getDataMemberBuy)
            ->with('getDataVendorMemberBuy', $getDataVendorMemberBuy)
            ->with('getDataVendorPPOBMemberBuy', $getDataVendorPPOBMemberBuy)
            ->with('sum', $sum)
            ->with('vsum', $belanjaVendor)
            ->with(compact('LMBDividendPool'))
            ->with(compact('userStakedLMB'))
            ->with('dataUser', $dataUser);
    }

    public function getMemberNetworking()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelPin = new Pin;
        $modelMember = new Member;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $kanan = 0;
        if ($dataUser->kanan_id != null) {
            $downlineKanan = $dataUser->upline_detail . ',[' . $dataUser->id . ']' . ',[' . $dataUser->kanan_id . ']';
            if ($dataUser->upline_detail == null) {
                $downlineKanan = '[' . $dataUser->id . ']' . ',[' . $dataUser->kanan_id . ']';
            }
            $kanan = $modelMember->getCountMyDownline($downlineKanan) + 1;
        }
        $kiri = 0;
        if ($dataUser->kiri_id != null) {
            $downlineKiri = $dataUser->upline_detail . ',[' . $dataUser->id . ']' . ',[' . $dataUser->kiri_id . ']';
            if ($dataUser->upline_detail == null) {
                $downlineKiri = '[' . $dataUser->id . ']' . ',[' . $dataUser->kiri_id . ']';
            }
            $kiri = $modelMember->getCountMyDownline($downlineKiri) + 1;
        }
        $dataNetworking = (object) array(
            'kanan' => $kanan,
            'kiri' => $kiri,
        );
        return view('member.home.networking')
            ->with('dataPin', $getTotalPin)
            ->with('dataAll', $dataNetworking)
            ->with('dataUser', $dataUser);
    }

    public function getMemberWallet()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $active_at = $dataUser->active_at;
        if ($dataUser->pin_activate_at != null) {
            $active_at = $dataUser->pin_activate_at;
        }
        $future =  strtotime('+1 years', strtotime($active_at));
        $timefromdb = time();
        $timeleft = $future - $timefromdb;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        if ($daysleft <= 0) {
            return redirect()->route('m_newPin')
                ->with('message', 'Keanggotaan anda telah EXPIRED, silakan beli pin untuk Resubscribe')
                ->with('messageclass', 'danger');
        }

        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $trxBalance = null;
        $eIDRbalance = null;
        if ($localWallet != null) {
            if ($localWallet->is_active == 1) {
                $tron = $this->getTron();
                $tron->setAddress($localWallet->address);
                $trxBalance = $tron->getBalance(null, true);
                $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;
            }
        }

        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
        $totalBonusRoyalti = $modelBonus->getTotalBonusRoyalti($dataUser);
        $totalWDRoyalti = $modelWD->getTotalDiTransferRoyalti($dataUser);
        $dataAll = (object) array(
            'local_wallet' => $localWallet,
            'trx_balance' => $trxBalance,
            'eIDRbalance' => $eIDRbalance,
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

    public function getMemberExplorers()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        return view('member.home.explorer')
            ->with('headerTitle', 'Explorer')
            ->with('dataUser', $dataUser);
    }

    public function getMemberStaking()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        if ($dataUser->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            Alert::error('Oops!', 'Keanggotaan anda sudah EXPIRED!');
            return redirect()->route('mainDashboard');
        }

        $modelBonus = new Bonus;
        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $totalStakedLMB = $modelBonus->getStakedLMB();
        $userStakedLMB = $modelBonus->getUserStakedLMB($dataUser->id);
        $userDividend = $modelBonus->getUserDividend($dataUser->id);
        $userUnstaking = $modelBonus->getUserUnstakeProgress($dataUser->id);

        return view('member.home.staking')
            ->with('headerTitle', 'Staking')
            ->with(compact('LMBDividendPool'))
            ->with(compact('totalStakedLMB'))
            ->with(compact('userStakedLMB'))
            ->with(compact('userDividend'))
            ->with(compact('userUnstaking'))
            ->with('dataUser', $dataUser);
    }

    public function getMemberStakingLeaderboard()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        $modelBonus = new Bonus;
        $stakers = $modelBonus->getAllStakersLeaderboard();

        return view('member.home.staking-leaderboard')
            ->with('headerTitle', 'Staking Leaderboard')
            ->with(compact('stakers'));
    }

    public function getClaimedDividendHistory()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        $modelBonus = new Bonus;
        $data = $modelBonus->getUserClaimedDividend($dataUser->id);

        return view('member.home.history')
            ->with('headerTitle', 'Claimed Div History')
            ->with(compact('data'));
    }

    public function getDividendHistory()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        $modelBonus = new Bonus;
        $data = $modelBonus->getUserDividendHistory($dataUser->id);

        return view('member.home.dividend-history')
            ->with('headerTitle', 'Dividend History')
            ->with(compact('data'));
    }

    public function getStakingHistory()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        $modelBonus = new Bonus;
        $data = $modelBonus->getUserStakingHistory($dataUser->id);

        return view('member.home.history')
            ->with('headerTitle', 'Staking History')
            ->with(compact('data'));
    }

    public function getMemberMyAccount()
    {
        $dataUser = Auth::user();
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataUser);
        $getTotalPPOBOut = $modelPin->getPPOBFly($dataUser->id);

        return view('member.home.account')
            ->with('dataDeposit', $getTotalDeposit)
            ->with('dataTarik', $getTransTarik)
            ->with('onTheFly', $getTotalPPOBOut)
            ->with('telegram', $dataUser->chat_id)
            ->with('dataUser', $dataUser);
    }

    public function getMemberNotification()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMemberPackage = new Memberpackage;
        $modelMember = new Member;
        $modelBonus = new Bonus;
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $modelPackage = new Package;
        $modelSales = new Sales;
        $sponsor_id = $dataUser->sponsor_id;
        if ($dataUser->is_active == 0) {
            $getCheckNewOrder = $modelMemberPackage->getCountNewMemberPackageInactive($dataUser);
        }
        if ($dataUser->is_active == 1) {
            $getCheckNewOrder = $modelMemberPackage->getCountMemberPackageInactive($dataUser);
        }
        $getDataMemberBuy = $modelSales->getMemberSalesBuy($dataUser->id);
        $getDataVendorMemberBuy = $modelSales->getMemberVSalesBuy($dataUser->id);
        $getDataVendorPPOBMemberBuy = $modelSales->getMemberVPPOB($dataUser->id);
        return view('member.home.notification')
            ->with('dataOrder', $getCheckNewOrder)
            ->with('getDataMemberBuy', $getDataMemberBuy)
            ->with('getDataVendorMemberBuy', $getDataVendorMemberBuy)
            ->with('getDataVendorPPOBMemberBuy', $getDataVendorPPOBMemberBuy)
            ->with('dataUser', $dataUser);
    }
}
