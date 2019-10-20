<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

class BonusmemberController extends Controller {
    
    public function __construct(){
    }
    
    public function getMySummaryBonus(){
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
    
    public function getMySponsorBonus(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelBonus = New Bonus;
        $getData = $modelBonus->getBonusSponsor($dataUser);
        return view('member.bonus.sponsor')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getMyBinaryBonus(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelBonus = New Bonus;
        $getData = $modelBonus->getBonusBinary($dataUser);
        return view('member.bonus.binary')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getMyLevelBonus(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelBonus = New Bonus;
        $getData = $modelBonus->getBonusLevel($dataUser);
        return view('member.bonus.level')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getMyROBonus(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelBonus = New Bonus;
        $getData = $modelBonus->getBonusRO($dataUser);
        return view('member.bonus.ro')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getMySaldoBonus(){
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
    
    public function postRequestWithdraw(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
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
        return redirect()->route('m_myBonusSaldo')
                    ->with('message', 'request Withdraw berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function getHistoryWithdrawal(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllMemberWD($dataUser);
        return view('member.bonus.history-wd')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getRequestWithdrawal(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
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
    
    public function getRequestWithdrawalRoyalti(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
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
    
    public function postRequestWithdrawRoyalti(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
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
        return redirect()->route('m_myBonusSaldo')
                    ->with('message', 'request Withdraw berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function getRequestWithdrawaleIDR(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
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
            'fee_tunda_eidr' => $totalWDeIDR->fee_tunda
        );
        return view('member.bonus.req-wd-eidr')
                ->with('dataAll', $dataAll)
                ->with('dataUser', $dataUser);
    }
    
    public function postRequestWithdraweIDR(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
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
        return redirect()->route('m_requestWDeIDR')
                    ->with('message', 'request Konversi eIDR berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function getRequestClaimReward(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelBonusSetting = new Bonussetting;
        $modelMember = New Member;
        $getMyTeam = $modelMember->getSponsorPeringkat($dataUser);
        $getMyPeringkat = $modelBonusSetting->getPeringkatByType($dataUser->member_type);
        
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
            'image' => $image
        );
        return view('member.bonus.req-claim-reward')
                ->with('getMyTeam', $getMyTeam)
                ->with('dataMy', $dataMy)
                ->with('getMyPeringkat', $getMyPeringkat)
                ->with('canClaim', $canClaim)
                ->with('dataUser', $dataUser);
    }
    
    public function postRequestClaimReward(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
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
    
    public function getHistoryReward(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getMemberRewardHistory($dataUser);
        return view('member.bonus.history-reward')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    
}

