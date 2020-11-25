<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\TopUpeIDRjob;
use Illuminate\Support\Facades\Auth;
use App\Model\Member;
use App\Model\Pinsetting;
use App\Model\Package;
use App\Model\Memberpackage;
use App\Model\Transaction;
use App\Model\Pin;
use App\Model\Bonussetting;
use App\Model\Transferwd;
use App\Model\Bonus;
use App\Model\Sales;
use App\Model\Bank;


class BonusmemberController extends Controller
{

    public function __construct()
    {
    }

    public function getMySummaryBonus()
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
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $dataAll = (object) array(
            'total_bonus' => floor($totalBonus->total_bonus),
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'total_fee_admin' => $totalWD->total_fee_admin,
            'fee_tuntas' => $totalWD->fee_tuntas,
            'fee_tunda' => $totalWD->fee_tunda
        );
        return view('member.bonus.summary')
            ->with('dataAll', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function getMySponsorBonus()
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
        $getData = $modelBonus->getBonusSponsor($dataUser);
        return view('member.bonus.sponsor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMyBinaryBonus()
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
        $getData = $modelBonus->getBonusBinary($dataUser);
        return view('member.bonus.binary')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMyLevelBonus()
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
        $getData = $modelBonus->getBonusLevel($dataUser);
        return view('member.bonus.level')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMyROBonus()
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
        $getData = $modelBonus->getBonusRO($dataUser);
        return view('member.bonus.ro')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMySaldoBonus()
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
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
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
            'fee_tunda_eidr' => $totalWDeIDR->fee_tunda
        );
        return view('member.bonus.saldo')
            ->with('dataAll', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function postRequestWithdraw(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getCode = $modelWD->getCodeWD($dataUser);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'user_bank' => $request->user_bank,
            'wd_code' => $getCode,
            'wd_total' => $request->saldo_wd,
            'wd_date' => date('Y-m-d'),
            'admin_fee' => $request->admin_fee
        );
        $modelWD->getInsertWD($dataInsert);
        return redirect()->route('mainWallet')
            ->with('message', 'request Withdraw berhasil')
            ->with('messageclass', 'success');
    }

    public function getHistoryWithdrawal()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllMemberWD($dataUser);
        return view('member.bonus.history-wd')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getRequestWithdrawal()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
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
            'fee_tunda_eidr' => $totalWDeIDR->fee_tunda
        );
        return view('member.bonus.req-wd')
            ->with('dataAll', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function getRequestWithdrawalRoyalti()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
                ->with('message', 'masa Keanggotaan anda telah kadaluarsa, silakan beli pin untuk Resubscribe')
                ->with('messageclass', 'danger');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonusRoyalti($dataUser);
        $totalWD = $modelWD->getTotalDiTransferRoyalti($dataUser);
        $dataAll = (object) array(
            'total_bonus' => floor($totalBonus->total_bonus),
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'admin_fee' => 6500,
            'total_fee_admin' => $totalWD->total_fee_admin,
            'fee_tuntas' => $totalWD->fee_tuntas,
            'fee_tunda' => $totalWD->fee_tunda
        );
        return view('member.bonus.req-wd-royalti')
            ->with('dataAll', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function postRequestWithdrawRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getCode = $modelWD->getCodeWD($dataUser);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'user_bank' => $request->user_bank,
            'wd_code' => $getCode,
            'type' => 3,
            'wd_total' => $request->saldo_wd,
            'wd_date' => date('Y-m-d'),
            'admin_fee' => $request->admin_fee
        );
        $modelWD->getInsertWD($dataInsert);
        return redirect()->route('mainWallet')
            ->with('message', 'request Withdraw berhasil')
            ->with('messageclass', 'success');
    }

    public function postRequestWithdrawRoyaltieIDR(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getCode = $modelWD->getCodeWDeIDR($dataUser);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'user_bank' => 1,
            'is_tron' => 1,
            'wd_code' => $getCode,
            'type' => 3,
            'wd_total' => $request->saldo_wd,
            'wd_date' => date('Y-m-d'),
            'admin_fee' => $request->admin_fee
        );
        $modelWD->getInsertWD($dataInsert);
        return redirect()->route('mainWallet')
            ->with('message', 'Request Withdraw Royalti via eIDR Berhasil')
            ->with('messageclass', 'success');
    }

    public function getRequestWithdrawaleIDR()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
                ->with('message', 'masa Keanggotaan anda telah kadaluarsa, silakan beli pin untuk Resubscribe')
                ->with('messageclass', 'danger');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
        $totalTopUp = $modelBonus->getTotalSaldoUserId($dataUser);
        $dataAll = (object) array(
            'total_bonus' => floor($totalBonus->total_bonus),
            'admin_fee' => 5000,
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
            'top_up' => $totalTopUp
        );
        return view('member.bonus.req-wd-eidr')
            ->with('dataAll', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function postRequestWithdraweIDR(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getCode = $modelWD->getCodeWDeIDR($dataUser);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'user_bank' => $request->user_bank,
            'type' => 5,
            'wd_code' => $getCode,
            'wd_total' => $request->saldo_wd,
            'wd_date' => date('Y-m-d'),
            'admin_fee' => $request->admin_fee,
            'is_tron' => 1
        );
        $modelWD->getInsertWD($dataInsert);
        return redirect()->route('mainWallet')
            ->with('message', 'request Konversi eIDR berhasil')
            ->with('messageclass', 'success');
    }

    public function getRequestClaimReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_newProfile')
                ->with('message', 'Profil data disri belum ada, silakan isi data profil anda')
                ->with('messageclass', 'danger');
        }
        if ($dataUser->is_tron == 0) {
            return redirect()->route('m_newTron')
                ->with('message', 'Anda harus melengkapi alamat Tron anda untuk bisa menerima Reward')
                ->with('messageclass', 'danger');
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
                ->with('message', 'masa Keanggotaan anda telah EXPIRED, silakan beli pin untuk Resubscribe')
                ->with('messageclass', 'danger');
        }
        $modelBonus = new Bonus;
        $modelBonusSetting = new Bonussetting;
        $modelMember = new Member;
        $getMyTeam = $modelMember->getSponsorPeringkat($dataUser);
        $getMyPeringkat = $modelBonusSetting->getPeringkatByType($dataUser->member_type);

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
            'image' => $image
        );
        return view('member.bonus.req-claim-reward')
            ->with('getMyTeam', $getMyTeam)
            ->with('dataMy', $dataMy)
            ->with('getMyPeringkat', $getMyPeringkat)
            ->with('canClaim', $canClaim)
            ->with('dataUser', $dataUser);
    }

    public function postRequestClaimReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'reward_id' => $request->cekID,
            'claim_date' => date('Y-m-d')
        );
        $modelBonus->getInsertClaimReward($dataInsert);
        return redirect()->route('m_requestClaimReward')
            ->with('message', 'Claim Reward berhasil')
            ->with('messageclass', 'success');
    }

    public function getHistoryReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getMemberRewardHistory($dataUser);
        return view('member.bonus.history-reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getBelanjaReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        //        $getData = $modelSales->getMemberMasterSalesMonthlyTerbaru($dataUser->id);
        $getData = $modelSales->getMemberMasterSalesMonthly($dataUser->id);
        $getTotalBonus = $modelBonus->getTotalBelanjaReward($dataUser->id);
        $dataClaim = array();
        $month = date('m');
        $year = date('Y');
        if ($getData != null) {
            foreach ($getData as $row) {
                $can = 1;
                if ($month == $row->month && $year == $row->year) {
                    $can = 0;
                } else {
                    $cekCanClaim = $modelBonus->getBelanjaRewardByMonthYear($dataUser->id, $row->month, $row->year);
                    if ($cekCanClaim != null) {
                        $can = 0;
                    }
                    if ($row->month_sale_price < 100000) { //100000
                        $can = 0;
                    }
                }
                $dataClaim[] = (object) array(
                    'month_sale_price' => $row->month_sale_price,
                    'monthly' => $row->monthly,
                    'month' => $row->month,
                    'year' => $row->year,
                    'canClaim' => $can
                );
            }
        }
        return view('member.bonus.req-belanja-reward')
            ->with('getData', $dataClaim)
            ->with('getTotal', $getTotalBonus)
            ->with('dataUser', $dataUser);
    }

    public function postRequestBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $getData = $modelSales->getMemberMasterSalesMonthYear($dataUser->id, $request->month, $request->year);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'reward' => $request->reward,
            'month' => $request->month,
            'year' => $request->year,
            'belanja_date' => $request->year . '-' . $request->month . '-01',
            'total_belanja' => $getData->month_sale_price
        );
        $modelBonus->getInsertBelanjaReward($dataInsert);
        return redirect()->route('m_BelanjaReward')
            ->with('message', 'Claim Reward Belanja berhasil')
            ->with('messageclass', 'success');
    }

    public function getPenjualanReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        //        $getData = $modelSales->getStockistPenjualanMonthlyTerbaru($dataUser->id);
        $getData = $modelSales->getStockistPenjualanMonthly($dataUser->id);
        $getTotalBonus = $modelBonus->getTotalPenjualanReward($dataUser->id);
        $dataClaim = array();
        $month = date('m');
        $year = date('Y');
        if ($getData != null) {
            foreach ($getData as $row) {
                $can = 1;
                if ($month == $row->month && $year == $row->year) {
                    $can = 0;
                } else {
                    $cekCanClaim = $modelBonus->getPenjualanRewardByMonthYear($dataUser->id, $row->month, $row->year);
                    if ($cekCanClaim != null) {
                        $can = 0;
                    }
                }
                $dataClaim[] = (object) array(
                    'month_sale_price' => $row->month_sale_price,
                    'monthly' => $row->monthly,
                    'month' => $row->month,
                    'year' => $row->year,
                    'canClaim' => $can
                );
            }
        }
        return view('member.bonus.req-penjualan-reward')
            ->with('getData', $dataClaim)
            ->with('getTotal', $getTotalBonus)
            ->with('dataUser', $dataUser);
    }

    public function postRequestPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $getData = $modelSales->getStockistPenjualanMonthYear($dataUser->id, $request->month, $request->year);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'reward' => $request->reward,
            'month' => $request->month,
            'year' => $request->year,
            'belanja_date' => $request->year . '-' . $request->month . '-01',
            'total_belanja' => $getData->month_sale_price,
            'type' => 2
        );
        $modelBonus->getInsertBelanjaReward($dataInsert);
        return redirect()->route('m_PenjualanReward')
            ->with('message', 'Claim Reward Penjualan berhasil')
            ->with('messageclass', 'success');
    }

    public function postRequestTopupSaldo(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $rand = rand(49, 99);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'nominal' => $request->req_topup,
            'unique_digit' => $rand
        );
        $getIDTrans = $modelBonus->getInsertTopUp($dataInsert);
        return redirect()->route('m_MemberTopupPembayaran', [$getIDTrans->lastID])
            ->with('message', 'Silakan pilih Metode Pembayaran anda')
            ->with('messageclass', 'success');
    }

    public function getHistoryTopupSaldo()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAllTopUpSaldo($dataUser);
        return view('member.bonus.topup-saldo')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMemberTopupPembayaran($id)
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
        $modelBank = new Bank;
        $getData = $modelBonus->getTopUpSaldoID($id);
        $getPerusahaanBank = null;
        if ($getData->bank_perusahaan_id != null) {
            $getPerusahaanBank = $modelBank->getBankPerusahaanID($getData->bank_perusahaan_id);
        } else {
            $getPerusahaanBank = $modelBank->getBankPerusahaan();
        }
        return view('member.bonus.detail-topup-eidr')
            ->with('headerTitle', 'Pembayaran')
            ->with('getData', $getData)
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('dataUser', $dataUser);
    }

    public function postTopUpeIDRCheckByJob($topup_id, $user_id)
    {
        TopUpeIDRjob::dispatch($topup_id, $user_id);
    }

    public function postMemberTopupPembayaran(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_active == 0) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelBank = new Bank;
        $id_topup = $request->id_topup;
        //        $getData = $modelBonus->getTopUpSaldoID($id_topup);
        $dataUpdate = array(
            'status' => 1,
            'bank_perusahaan_id' => $request->bank_perusahaan_id,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateTopUp('id', $id_topup, $dataUpdate);
        // $this->dispatch(new TopUpeIDRjob($id_topup, $dataUser->id));
        TopUpeIDRjob::dispatch($id_topup, $dataUser->id);
        return redirect()->back();
    }

    public function postRejectTopup(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_active == 0) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $id_topup = $request->id_topup;
        $dataUpdate = array(
            'status' => 3,
            'deleted_at' => date('Y-m-d H:i:s'),
            'reason' => null,
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateTopUp('id', $id_topup, $dataUpdate);
        return redirect()->route('m_historyTopupSaldo')
            ->with('message', 'Transaksi Top Up dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getHistoryWithdrawaleIDR()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllMemberWDeIDR($dataUser);
        return view('member.bonus.history-wd-eidr')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getVBelanjaReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
                ->with('message', 'masa Keanggotaan anda telah kadaluarsa, silakan beli pin untuk Resubscribe')
                ->with('messageclass', 'danger');
        }
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $getData = $modelSales->getMemberVendorMasterSalesMonthly($dataUser->id);
        $getTotalBonus = $modelBonus->getTotalVBelanjaReward($dataUser->id);
        $dataClaim = array();
        $month = date('m');
        $year = date('Y');
        if ($getData != null) {
            foreach ($getData as $row) {
                $can = 1;
                if ($month == $row->month && $year == $row->year) {
                    $can = 0;
                } else {
                    $cekCanClaim = $modelBonus->getVBelanjaRewardByMonthYear($dataUser->id, $row->month, $row->year);
                    if ($cekCanClaim != null) {
                        $can = 0;
                    }
                    if ($row->month_sale_price < 1000) { //100000
                        $can = 0;
                    }
                }
                $dataClaim[] = (object) array(
                    'month_sale_price' => $row->month_sale_price,
                    'monthly' => $row->monthly,
                    'month' => $row->month,
                    'year' => $row->year,
                    'canClaim' => $can
                );
            }
        }
        return view('member.bonus.req-vbelanja-reward')
            ->with('getData', $dataClaim)
            ->with('getTotal', $getTotalBonus)
            ->with('dataUser', $dataUser);
    }

    public function postRequestVBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
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
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $getData = $modelSales->getMemberVMasterSalesMonthYear($dataUser->id, $request->month, $request->year);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'reward' => $request->reward,
            'month' => $request->month,
            'year' => $request->year,
            'belanja_date' => $request->year . '-' . $request->month . '-01',
            'total_belanja' => $getData[0]->month_sale_price,
            'type' => 3
        );
        $modelBonus->getInsertBelanjaReward($dataInsert);
        return redirect()->route('m_VBelanjaReward')
            ->with('message', 'Claim Reward Belanja berhasil')
            ->with('messageclass', 'success');
    }

    public function getVendorPenjualanReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
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
                ->with('message', 'masa Keanggotaan anda telah kadaluarsa, silakan beli pin untuk Resubscribe')
                ->with('messageclass', 'danger');
        }
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $getData = $modelSales->getVendorPenjualanMonthly($dataUser->id);
        $getTotalBonus = $modelBonus->getTotalVPenjualanReward($dataUser->id);
        $dataClaim = array();
        $month = date('m');
        $year = date('Y');
        if ($getData != null) {
            foreach ($getData as $row) {
                $can = 1;
                if ($month == $row->month && $year == $row->year) {
                    $can = 0;
                } else {
                    $cekCanClaim = $modelBonus->getVPenjualanRewardByMonthYear($dataUser->id, $row->month, $row->year);
                    if ($cekCanClaim != null) {
                        $can = 0;
                    }
                    if ($row->month_sale_price < 1000) { //100000
                        $can = 0;
                    }
                }
                $dataClaim[] = (object) array(
                    'month_sale_price' => $row->month_sale_price,
                    'monthly' => $row->monthly,
                    'month' => $row->month,
                    'year' => $row->year,
                    'canClaim' => $can
                );
            }
        }
        return view('member.bonus.req-vpenjualan-reward')
            ->with('getData', $dataClaim)
            ->with('getTotal', $getTotalBonus)
            ->with('dataUser', $dataUser);
    }

    public function postVendorPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $getData = $modelSales->getVendorPenjualanMonthYear($dataUser->id, $request->month, $request->year);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'reward' => $request->reward,
            'month' => $request->month,
            'year' => $request->year,
            'belanja_date' => $request->year . '-' . $request->month . '-01',
            'total_belanja' => $getData[0]->month_sale_price,
            'type' => 4
        );
        $modelBonus->getInsertBelanjaReward($dataInsert);
        return redirect()->route('m_VPenjualanReward')
            ->with('message', 'Claim Reward Penjualan berhasil')
            ->with('messageclass', 'success');
    }
}
