<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Model\Admin;
use App\Model\Pinsetting;
use App\Model\Package;
use App\Model\Transaction;
use App\Model\Pin;
use App\Model\Bank;
use App\Model\Member;
use App\Model\Masterpin;
use App\Model\Pengiriman;
use App\Model\Bonussetting;
use App\Model\Transferwd;
use App\Model\Bonus;

class MasterAdminController extends Controller {

    public function __construct(){
        
    }
    
    public function getAddAdmin(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelAdmin = New Admin;
        $getAllAdmin = $modelAdmin->getAllUserAdmin($dataUser);
        return view('admin.user.create-user')
                ->with('headerTitle', 'Admin')
                ->with('getAllAdmin', $getAllAdmin)
                ->with('dataUser', $dataUser);
    }
    
    public function postAddAdmin(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($request->email == null || $request->password == null || $request->repassword == null || $request->f_name == null){
            return redirect()->route('addCrew')
                ->with('message', 'The field is required.')
                ->with('messageclass', 'danger');
        }
        if($request->password != $request->repassword){
            return redirect()->route('addCrew')
                ->with('message', 'Password didn\'t match')
                ->with('messageclass', 'danger');
        }
         $modelAdmin = New Admin;
         $cekUsername = $modelAdmin->getCekNewUsername($request->email);
         if($cekUsername != null){
            return redirect()->route('addCrew')
                ->with('message', 'use another email')
                ->with('messageclass', 'danger');
        }
        $dataInsert = array(
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->f_name,
            'is_active' => 1,
            'user_type' => $request->user_type,
            'id_type' => 0,
            'is_verification' => 1
        );
        $modelAdmin->getInsertUser($dataInsert);
        return redirect()->route('addCrew')
                ->with('message', 'Create New Admin Success')
                ->with('messageclass', 'success');
    }
    
    //Setting
    public function getAddPinSetting(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelSettingPin = New Pinsetting;
        $getPinSetting = $modelSettingPin->getActivePinSetting();
        return view('admin.pin.pin-setting')
                ->with('headerTitle', 'Pin')
                ->with('data', $getPinSetting)
                ->with('dataUser', $dataUser);
    }
    
    public function postPinSetting(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $price = $request->price;
        $modelSettingPin = New Pinsetting;
        $remove = array(
            'is_active' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelSettingPin->getRemoveAllPinSetting($remove);
        $dataInsert = array(
            'price' => $price,
            'is_active' => 1,
            'created_by' => $dataUser->id,
            'active_at' => date('Y-m-d H:i:s')
        );
        $modelSettingPin->getInsertPinSetting($dataInsert);
        return redirect()->route('addSettingPin')
                ->with('message', 'Create Pin Setting Success')
                ->with('messageclass', 'success');
    }
    
    public function getAllPackage(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelSettingPin = New Pinsetting;
        $modelPackage = New Package;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $getAllPackage = $modelPackage->getAllPackage();
        return view('admin.package.package-list')
                ->with('headerTitle', 'Package')
                ->with('pinSetting', $getActivePinSetting)
                ->with('package', $getAllPackage)
                ->with('dataUser', $dataUser);
    }
    
    public function postUpdatePackage(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelPackage = New Package;
        $dataUpdate = array(
            'name' => $request->name,
            'short_desc' => $request->short_desc,
            'pin' => $request->pin,
            'stock_wd' => $request->stock_wd,
            'discount' => $request->discount
        );
        $modelPackage->getUpdatePackage($request->cekId, $dataUpdate);
        return redirect()->route('allPackage')
                ->with('message', 'Update Package Success')
                ->with('messageclass', 'success');
    }
    
    public function getListTransactions(Request $request){
        $status = $request->s;
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelSettingTrans = New Transaction;
        $getAllTransaction = $modelSettingTrans->getTransactionsByAdmin($status);
        return view('admin.pin.list-transaction')
                ->with('headerTitle', 'Transaksi')
                ->with('getData', $getAllTransaction)
                ->with('dataUser', $dataUser);
    }
    
    public function postConfirmTransaction(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $id = $request->cekId;
        $user_id = $request->cekMemberId;
        $modelSettingTrans = New Transaction;
        $getData = $modelSettingTrans->getDetailTransactionsAdmin($id, $user_id);
        if($getData == null){
            return redirect()->route('adm_listTransaction')
                ->with('message', 'Data tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        $modelSettingPin = New Pinsetting;
        $getPinSetting = $modelSettingPin->getActivePinSetting();
        $memberPin = array(
            'user_id' => $user_id,
            'total_pin' => $getData->total_pin,
            'setting_pin' => $getPinSetting->id,
            'transaction_code' => $getData->transaction_code,
            'pin_code' => 'P'.date('Ymd').$user_id
        );
        $modelPin = New Pin;
        $modelMasterPin = New Masterpin;
        $modelMember = New Member;
        $modePackage = New Package;
        $modelPin->getInsertMemberPin($memberPin);
        if($getData->type == 1){
            $memberStatus = 1;
            if($getData->total_pin >= 100){
                $memberStatus = 2;
            }
            $dataMemberUpdate = array(
                'member_status' => $memberStatus,
                'member_status_at' => date('Y-m-d H:i:s')
            );
            $modelMember->getUpdateUsers('id', $user_id, $dataMemberUpdate);
            $reason = 'Member buy pin';
        }
        if($getData->type == 10){
            $modelRO = New RepeatOrder;
            $getROPackage = $modePackage->getMyPackagePin($getData->total_pin);
            $dataRO = array(
                'user_id' => $user_id,
                'package_id' => $getROPackage->id
            );
            $modelRO->getInsertRO($dataRO);
            $reason = 'Member Repeat Order';
        }
        
        $dataUpdate = array(
            'status' => 2,
            'tuntas_at' => date('Y-m-d H:i:s')
        );
        $modelSettingTrans->getUpdateTransaction('id', $id, $dataUpdate);
        $dataInsertMasterPin = array(
            'total_pin' => $getData->total_pin,
            'type_pin' => 2,
            'transaction_code' => $getData->transaction_code,
            'reason' => $reason
        );
        $modelMasterPin->getInsertMasterPin($dataInsertMasterPin);
        return redirect()->route('adm_listTransaction')
                ->with('message', 'Berhasil konfirmasi transfer pin')
                ->with('messageclass', 'success');
        
    }
    
    public function getBankPerusahaan(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBank = new Bank;
        $getPerusahaanBank = $modelBank->getBankPerusahaan();
        return view('admin.bank.list-bank')
                ->with('headerTitle', 'Bank Perusahaan')
                ->with('getData', $getPerusahaanBank)
                ->with('dataUser', $dataUser);
    }
    
    public function postBankPerusahaan(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBank = new Bank;
        $getPerusahaanBank = $modelBank->getBankPerusahaanID($request->id);
        $dataUpdate = array(
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $request->account_name,
        );
        $modelBank->getUpdateBank('id', $request->id, $dataUpdate);
        return redirect()->route('adm_bankPerusahaan')
                ->with('message', 'Berhasil update bank perusahaan')
                ->with('messageclass', 'success');
    }
    
    public function getAddBankPerusahaan(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        return view('admin.bank.add-bank')
                ->with('headerTitle', 'Bank Perusahaan')
                ->with('dataUser', $dataUser);
    }
    
    public function postAddBankPerusahaan(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBank = new Bank;
        $dataInsert = array(
            'user_id' => 2,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $request->account_name,
            'bank_type' => 1,
            'active_at' => date('Y-m-d H:i:s')
        );
        $modelBank->getInsertBank($dataInsert);
        return redirect()->route('adm_bankPerusahaan')
                ->with('message', 'Berhasil tambah bank perusahaan')
                ->with('messageclass', 'success');
    }
    
    public function getListKirimPaket(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelPengiriman = new Pengiriman;
        $getAllPengiriman = $modelPengiriman->getAdmPengiriman();
        return view('admin.pin.kirim-paket')
                ->with('headerTitle', 'Kirim Paket')
                ->with('getData', $getAllPengiriman)
                ->with('dataUser', $dataUser);
    }
    
    public function getKirimPaketByID($id, $user_id){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($id, $user_id);
        return view('admin.pin.kirim-paket-detail')
                ->with('headerTitle', 'Konfirmasi Pengiriman')
                ->with('getData', $getPengiriman)
                ->with('dataUser', $dataUser);
    }
    
    public function postConfirmKirimPaket(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($request->cekId, $request->cekUserId);
        if($getPengiriman == null){
            return redirect()->route('adm_listKirimPaket')
                    ->with('message', 'Data tidak ditemukan')
                    ->with('messageclass', 'danger');
        }
        $dataUpdate = array(
            'status' => 1,
            'kirim_at' => date('Y-m-d H:i:s'),
            'kurir_name' => $request->kurir_name,
            'no_resi' => $request->no_resi
        );
        $modelPengiriman->getUpdatePengiriman($getPengiriman->id, $dataUpdate);
        return redirect()->route('adm_listKirimPaket')
                    ->with('message', 'Paket sudah dikirim')
                    ->with('messageclass', 'success');
    }
    
    public function getBonusStart(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $getBonusStart =$modelBonusSetting->getActiveBonusStart();
        return view('admin.setting.bonus-start')
                ->with('headerTitle', 'Setting Bonus Sponsor')
                ->with('getData', $getBonusStart)
                ->with('dataUser', $dataUser);
    }
    
    public function postBonusStart(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $dataUpdate = array(
            'is_active' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelBonusSetting->getUpdateBonusStart('is_active', 1, $dataUpdate);
        $dataInsert = array(
            'start_price' => $request->start_price,
            'created_by' => $dataUser->id
        );
        $modelBonusSetting->getInsertBonusStart($dataInsert);
        return redirect()->route('adm_bonusStart')
                    ->with('message', 'Edit Setting bonus start berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function getAllMember(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelMember = New Member;
        $getData = $modelMember->getAllMemberByAdmin();
        $getCountData = $modelMember->getAllMember();
        return view('admin.member.list-member')
                ->with('headerTitle', 'Member')
                ->with('getData', $getData)
                ->with('getTotal', $getCountData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAllBonusSponsor(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelMember = New Member;
        $modelBonus = New Bonus;
        $getData = $modelBonus->getBonusSponsorByAdmin();
        return view('admin.bonus.bonus-sponsor')
                ->with('headerTitle', 'Bonus Sponsor')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAllWD(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonus = New Bonus;
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllRequestWD();
        return view('admin.member.list-wd')
                ->with('headerTitle', 'Request Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function postCheckWD(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonus = New Bonus;
        $modelWD = new Transferwd;
        $getRowID = $request->id;
        foreach($getRowID as $getID){
            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s')
            );
            $modelWD->getUpdateWD('id', $getID, $dataUpdate);
        }
        return redirect()->route('adm_listWD')
                    ->with('message', 'Konfirmasi Transfer WD berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function postRejectWD(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelBonus = New Bonus;
        $modelWD = new Transferwd;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $getData = $modelWD->getIDRequestWD($getID);
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelWD->getUpdateWD('id', $getID, $dataUpdate);
        return redirect()->route('adm_listWD')
                    ->with('message', 'Data WD '.$getData->full_name.' senilai Rp. '.number_format($getData->wd_total + $getData->admin_fee, 0, ',', '.').' direject')
                    ->with('messageclass', 'success');
    }
    
    public function getAllHistoryWD(){
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllHistoryWD();
        return view('admin.member.history-wd')
                ->with('headerTitle', 'History Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    

}
