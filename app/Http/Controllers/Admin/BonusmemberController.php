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
            'total_bonus' => $totalBonus->total_bonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'total_fee_admin' => $totalWD->total_fee_admin
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
        return view('member.bonus.binary')
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
        $dataAll = (object) array(
            'total_bonus' => $totalBonus->total_bonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'admin_fee' => 6500,
            'total_fee_admin' => $totalWD->total_fee_admin
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
        return view('member.bonus.req-wd')
                ->with('dataUser', $dataUser);
    }
    
    
}

