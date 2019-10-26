<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Member;
use App\Model\Pinsetting;
use App\Model\Package;
use App\Model\Memberpackage;
use App\Model\Transaction;
use App\Model\Pin;
use App\Model\Bonussetting;
use App\Model\Bonus;
use App\Model\Bank;
use App\Model\Pengiriman;
use App\Model\Membership;
use App\Model\Sales;

class MemberController extends Controller {
    
    public function __construct(){

    }
    
    public function getMyProfile(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_newProfile')
                    ->with('message', 'Profil data disri belum ada, silakan isi data profil anda')
                    ->with('messageclass', 'danger');
        }
        return view('member.profile.my-profile')
                    ->with('headerTitle', 'Profil Saya')
                    ->with('dataUser', $dataUser);
    }
    
    public function getAddMyProfile(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_profile == 1){
            return redirect()->route('m_myProfile')
                    ->with('message', 'Tidak bisa buat profil')
                    ->with('messageclass', 'danger');
        }
        $modelMember = New Member;
        $getProvince = $modelMember->getProvinsi();
        return view('member.profile.add-profile')
                ->with('headerTitle', 'Profile')
                ->with('provinsi', $getProvince)
                ->with('dataUser', $dataUser);
    }
    
    public function postAddMyProfile(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $dataUpdate = array(
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'is_profile' => 1,
            'profile_created_at' => date('Y-m-d H:i:s'),
            'kode_daerah' => $request->kode_daerah,
        );
        $modelMember = New Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdate);
        return redirect()->route('m_myProfile')
                    ->with('message', 'Profil data diri berhasil dibuat')
                    ->with('messageclass', 'success');
    }
    
    public function getAddSponsor(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        return view('member.sponsor.add-sponsor')
                ->with('headerTitle', 'Sponsor')
                ->with('dataUser', $dataUser);
    }
    
    public function postAddSponsor(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelMember = New Member;
        $sponsor_id = $dataUser->id;
        $dataInsertNewMember = array(
            'name' => $request->user_code,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'user_code' => $request->user_code,
            'sponsor_id' => $sponsor_id
        );
        $modelMember->getInsertUsers($dataInsertNewMember);
//        $total_sponsor = $dataUser->total_sponsor + 1;
//        $dataUpdateSponsor = array(
//            'total_sponsor' => $total_sponsor,
//        );
//        $modelMember->getUpdateUsers('id', $sponsor_id, $dataUpdateSponsor);
        $dataEmail = array(
            'email' => $request->email,
            'password' => $request->password,
            'hp' => $request->hp,
            'user_code' => $request->user_code
        );
        $emailSend = $request->email;
        Mail::send('member.email.email', $dataEmail, function($message) use($emailSend){
            $message->to($emailSend, 'Lumbung Network Registration')
                    ->subject('Welcome to Lumbung Network');
        });
        return redirect()->route('m_newSponsor')
                ->with('message', 'Registrasi member baru berhasil')
                ->with('messageclass', 'success');
    }
    
    public function getStatusSponsor(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $modelMember = New Member;
        $getAllSponsor = $modelMember->getAllDownlineSponsor($dataUser);
        return view('member.sponsor.status-sponsor')
                ->with('getData', $getAllSponsor)
                ->with('dataUser', $dataUser);
    }
    
    public function getAddPackage(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id != null){
            return redirect()->route('mainDashboard');
        }
        $modePackage = New Package;
        $modelSettingPin = New Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $getAllPackage = $modePackage->getAllPackage();
        return view('member.package.add-package')
                ->with('headerTitle', 'Package')
                ->with('allPackage', $getAllPackage)
                ->with('pinSetting', $getActivePinSetting)
                ->with('dataUser', $dataUser);
    }
    
    public function postAddPackage(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id != null){
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = New Memberpackage;
        $modelPackage = New Package;
        $getDetailPackage = $modelPackage->getPackageId($request->id_paket);
        $dataInsert = array(
            'user_id' => $dataUser->sponsor_id,
            'request_user_id' => $dataUser->id,
            'package_id' => $request->id_paket,
            'name' => $getDetailPackage->name,
            'short_desc' => $getDetailPackage->short_desc,
            'total_pin' => $getDetailPackage->pin,
        );
        $modelMemberPackage->getInsertMemberPackage($dataInsert);
        $dataUpdatePackageId = array(
            'package_id' => $getDetailPackage->id,
            'package_id_at' => date('Y-m-d H:i:s')
        );
        $modelMember = New Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdatePackageId);
        return redirect()->route('mainDashboard')
                    ->with('message', 'Order Package success, please wait your sponsor activate')
                    ->with('messageclass', 'success');
    }
    
    public function getListOrderPackage(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = New Memberpackage;
        $getCheckNewOrder = $modelMemberPackage->getMemberPackageInactive($dataUser);
        if(count($getCheckNewOrder) == 0){
            return redirect()->route('mainDashboard')
                    ->with('message', 'No one member order package')
                    ->with('messageclass', 'danger');
        }
        return view('member.package.order-list-package')
                ->with('headerTitle', 'Package')
                ->with('allPackage', $getCheckNewOrder)
                ->with('dataUser', $dataUser);
    }
    
    public function getDetailOrderPackage($paket_id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = New Memberpackage;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($paket_id, $dataUser);
        if($getData == null){
            return redirect()->route('mainDashboard');
        }
        $modelSettingPin = New Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        return view('member.package.order-detail-package')
                ->with('headerTitle', 'Package')
                ->with('getData', $getData)
                ->with('pinSetting', $getActivePinSetting)
                ->with('dataUser', $dataUser);
    }
    
    public function postActivatePackage(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = New Memberpackage;
        $modelPin = new Pin;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($request->id_paket, $dataUser);
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $getMylastPin = $modelPin->getMyLastPin($dataUser);
        $sisaPin = $getTotalPin->sum_pin_masuk - $getTotalPin->sum_pin_keluar;
        if($sisaPin < $getData->total_pin){
            return redirect()->route('m_detailOrderPackage', $getData->id)
                        ->with('message', 'Paket tidak cukup untuk mengaktifasi sponsor baru, silakan beli pin')
                        ->with('messageclass', 'danger');
        }
        $code = sprintf("%03s", $getData->total_pin);
        $memberPin = array(
            'user_id' => $dataUser->id,
            'total_pin' => $getData->total_pin,
            'setting_pin' => $getMylastPin->setting_pin,
            'pin_code' => $getMylastPin->pin_code.$code.$getData->request_user_id,
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s'),
            'used_user_id' => $getData->request_user_id,
             'pin_status' => 1,
        );
        $modelPin->getInsertMemberPin($memberPin);
        $dataUpdate = array(
            'status' => 1
        );
        $modelMemberPackage->getUpdateMemberPackage('id', $getData->id, $dataUpdate);
        $modelBonusSetting = new Bonussetting;
        $modelBonus = new Bonus;
        $getBonusStart =$modelBonusSetting->getActiveBonusStart();
        $bonus_price = $getBonusStart->start_price * $getData->total_pin;
        $dataInsertBonus = array(
            'user_id' => $dataUser->id,
            'from_user_id' => $getData->request_user_id,
            'type' => 1,
            'bonus_price' => $bonus_price,
            'bonus_date' => date('Y-m-d'),
            'poin_type' => 1,
            'total_pin' => $getData->total_pin
        );
        $modelBonus->getInsertBonusMember($dataInsertBonus);
        $dataUpdateIsActive = array(
            'is_active' => 1,
            'active_at' => date('Y-m-d H:i:s'),
            'member_type' => $getData->package_id
        );
        $modelMember = New Member;
        $modelMember->getUpdateUsers('id', $getData->request_user_id, $dataUpdateIsActive);
        $total_sponsor = $dataUser->total_sponsor + 1;
        $dataUpdateSponsor = array(
            'total_sponsor' => $total_sponsor,
        );
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdateSponsor);
        return redirect()->route('mainDashboard')
                        ->with('message', 'Berhasil mengaktifasi sponsor baru')
                        ->with('messageclass', 'success');
    }
    
    public function getAddPin(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        return view('member.pin.order-pin')
                ->with('headerTitle', 'Pin')
                ->with('dataUser', $dataUser);
    }
    
    public function postAddPin(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $disc = 0;
//        if($request->total_pin >= 100){
//            $disc = 3;
//        }
        $modelSettingPin = New Pinsetting;
        $modelSettingTrans = New Transaction;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $hargaAwal = $getActivePinSetting->price * $request->total_pin;
        $discAwal = $hargaAwal * $disc / 100;
        $harga = $hargaAwal - $discAwal;
        $code =$modelSettingTrans->getCodeTransaction();
        $rand = rand(101, 249);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'transaction_code' => 'TR'.date('Ymd').$dataUser->id.$code,
            'total_pin' => $request->total_pin,
            'price' => $harga,
            'unique_digit' => $rand,
        );
        $getIDTrans = $modelSettingTrans->getInsertTransaction($dataInsert);
        return redirect()->route('m_addTransaction', [$getIDTrans->lastID])
                    ->with('message', 'Order Pin berhasil, silakan lakukan proses transfer')
                    ->with('messageclass', 'success');
    }
    
    public function getListTransactions(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $modelSettingTrans = New Transaction;
        $getAllTransaction = $modelSettingTrans->getTransactionsMember($dataUser);
        return view('member.pin.list-transaction')
                ->with('headerTitle', 'Transaction')
                ->with('getData', $getAllTransaction)
                ->with('dataUser', $dataUser);
    }
    
    public function getAddTransaction($id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $modelTrans = New Transaction;
        $modelBank = New Bank;
        $getTrans = $modelTrans->getDetailTransactionsMember($id, $dataUser);
        if($getTrans == null){
            return redirect()->route('mainDashboard');
        }
        $getPerusahaanTron = null;
        if($getTrans->bank_perusahaan_id != null){
            if($getTrans->is_tron == 0){
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($getTrans->bank_perusahaan_id);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($getTrans->bank_perusahaan_id);
            }
        } else {
            $getPerusahaanBank = $modelBank->getBankPerusahaan();
            $getPerusahaanTron = $modelBank->getTronPerusahaan();
        }
        return view('member.pin.order-detail-transaction')
                ->with('headerTitle', 'Transaction')
                ->with('bankPerusahaan', $getPerusahaanBank)
                ->with('tronPerusahaan', $getPerusahaanTron)
                ->with('getData', $getTrans)
                ->with('dataUser', $dataUser);
    }
    
    public function postAddTransaction(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $modelSettingTrans = New Transaction;
        $id_trans = $request->id_trans;
        $dataUpdate = array(
            'status' => 1,
            'bank_perusahaan_id' => $request->bank_perusahaan_id,
            'updated_at' => date('Y-m-d H:i:s'),
            'is_tron' => $request->is_tron
        );
        $modelSettingTrans->getUpdateTransaction('id', $id_trans, $dataUpdate);
        $getTrans = $modelSettingTrans->getDetailTransactionsMemberNew($id_trans, $dataUser->id, $request->is_tron);
        $metodePembayaran = 'Transfer Antar Bank';
        $alamat = $getTrans->to_name.' a/n '.$getTrans->account_name.' no rek. '.$getTrans->account;
        if($request->is_tron == 1){
            $metodePembayaran = 'Transfer eIDR';
            $alamat = $getTrans->to_name.' a/n '.$getTrans->account;
        }
        $dataEmail = array(
            'tgl_order' => date('d F Y'),
            'nama' => $dataUser->user_code,
            'hp' => $dataUser->hp,
            'transaction_code' => $getTrans->transaction_code,
            'total_pin' => $getTrans->total_pin,
            'price' => 'Rp '.number_format($getTrans->price, 0, ',', '.'),
            'unique_digit' => $getTrans->unique_digit,
            'metode' => $metodePembayaran,
            'alamat' => $alamat
        );
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.pin_confirm', $dataEmail, function($message) use($emailSend){
            $message->to($emailSend, 'Konfirmasi Data Pembelian PIN Member Lumbung Network')
                    ->subject('Konfirmasi Data Pembelian PIN Member Lumbung Network');
        });
        return redirect()->route('m_listTransactions')
                    ->with('message', 'Konfirmasi transfer berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function postRejectTransaction(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($request->reason == null){
            return redirect()->route('m_addTransaction', [$request->id_trans])
                        ->with('message', 'Alasan harus diisi')
                        ->with('messageclass', 'danger');
        }
        $modelSettingTrans = New Transaction;
        $id_trans = $request->id_trans;
        $dataUpdate = array(
            'status' => 3,
            'deleted_at' => date('Y-m-d H:i:s'),
            'reason' => $request->reason
        );
        $modelSettingTrans->getUpdateTransaction('id', $id_trans, $dataUpdate);
        return redirect()->route('m_listTransactions')
                    ->with('message', 'Transaksi dibatalkan')
                    ->with('messageclass', 'success');
    }
    
    public function getMyPinStock(){
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
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $getTotalPinTerkirim = $modelPengiriman->getCekPinTuntasTerkirim($dataUser);
        return view('member.pin.pin-stock')
                        ->with('dataPin', $getTotalPin)
                        ->with('dataTerkirim', $getTotalPinTerkirim)
                        ->with('dataUser', $dataUser);
    }
    
    public function getMyPinHistory(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelPin = new Pin;
        $getHistoryPin = $modelPin->getMyHistoryPin($dataUser);
        return view('member.pin.pin-history')
                        ->with('getData', $getHistoryPin)
                        ->with('dataUser', $dataUser);
    }
    
    public function getAddPlacement(Request $request){
        $dataUser = Auth::user();
        $myUserSession = $dataUser;
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = New Member;
        $back = false;
        if($request->get_id != null){
            $back = true;
            $dataUser = $modelMember->getUsers('id', $request->get_id);
        }
        $getBinary = $modelMember->getBinary($dataUser);
        $downline = $myUserSession->upline_detail.',['.$myUserSession->id.']';
        if($myUserSession->upline_detail == null){
            $downline = '['.$myUserSession->id.']';
        }
        $getAllDownline = $modelMember->getMyDownline($downline);
        return view('member.sponsor.placement')
                        ->with('getAllDownline', $getAllDownline)
                        ->with('back', $back)
                        ->with('getData', $getBinary)
                        ->with('dataUser', $dataUser);
    }
        
    public function postAddPlacement(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $posisi = 'kanan_id';
        if($request->type == 1){
            $posisi = 'kiri_id';
        }
        $modelMember = New Member;
        $getUplineId = $dataUser;
        if($request->upline_id != $dataUser->id){
            $getUplineId = $modelMember->getUsers('id', $request->upline_id);
        }
        if($getUplineId->$posisi != null){
            return redirect()->route('m_addPlacement')
                        ->with('message', 'Posisi placement yang anda pilih telah terisi, pilih posisi yang lain')
                        ->with('messageclass', 'danger');
        }
        $getNewMember = $modelMember->getCekMemberToPlacement($request->id_calon, $dataUser);
        if($getNewMember == null){
            return redirect()->route('m_addPlacement')
                        ->with('message', 'data member yang akan di placement tidak ditemukan')
                        ->with('messageclass', 'danger');
        }
        $newMemberUpline = $getUplineId->upline_detail.',['.$getUplineId->id.']';
        if($getUplineId->upline_detail == null){
            $newMemberUpline = '['.$getUplineId->id.']';
        }
        $dataUpdateDownline = array(
            'upline_id' => $getUplineId->id,
            'upline_detail' => $newMemberUpline,
            'placement_at' => date('Y-m-d H:i:s')
        );
        $modelMember->getUpdateUsers('id', $getNewMember->id, $dataUpdateDownline);
        $dataUpdateUpline = array(
            $posisi => $getNewMember->id
        );
        $modelMember->getUpdateUsers('id', $getUplineId->id, $dataUpdateUpline);
        return redirect()->route('m_addPlacement')
                    ->with('message', 'Placement berhasil')
                    ->with('messageclass', 'success');
        
    }
    
    public function getMyBinary(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = New Member;
        $back = false;
        $downline = $dataUser->upline_detail.',['.$dataUser->id.']';
        if($dataUser->upline_detail == null){
            $downline = '['.$dataUser->id.']';
        }
        if($request->get_id != null){
            if($request->get_id != $dataUser->id){
                $back = true;
                $dataUser = $modelMember->getCekIdDownline($request->get_id, $downline);
            }
        }
        if($dataUser == null){
            return redirect()->route('mainDashboard');
        }
        $getBinary = $modelMember->getBinary($dataUser);
        return view('member.networking.binary')
                        ->with('getData', $getBinary)
                        ->with('back', $back)
                        ->with('dataUser', $dataUser);
    }
    
    //Bank
    public function getMyBank(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_newProfile')
                    ->with('message', 'Profil data diri belum ada, Tidak bisa mengisi data bank')
                    ->with('messageclass', 'danger');
        }
        $modelBank = new Bank;
        $getAllMyBank = $modelBank->getBankMember($dataUser);
        return view('member.profile.bank')
                        ->with('getData', $getAllMyBank)
                        ->with('dataUser', $dataUser);
    }
    
    public function postAddBank(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_newProfile')
                    ->with('message', 'Profil data diri belum ada, Tidak bisa mengisi data bank')
                    ->with('messageclass', 'danger');
        }
        $modelBank = New Bank;
        $date = date('Y-m-d H:i:s');
        $dateUpdate = array(
            'is_active' => 0,
            'deleted_at' => $date
        );
        $modelBank->getUpdateBank('user_id', $dataUser->id, $dateUpdate);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $dataUser->full_name,
            'active_at' => $date
        );
        $modelBank->getInsertBank($dataInsert);
        return redirect()->route('m_myBank')
                    ->with('message', 'Bank berhasil ditambahkan')
                    ->with('messageclass', 'success');
    }
    
    public function postActivateBank(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_newProfile')
                    ->with('message', 'Profil data diri belum ada, Tidak bisa mengisi data bank')
                    ->with('messageclass', 'danger');
        }
        $modelBank = New Bank;
        $date = date('Y-m-d H:i:s');
        $dateUpdate = array(
            'is_active' => 0,
            'deleted_at' => $date
        );
        $modelBank->getUpdateBank('user_id', $dataUser->id, $dateUpdate);
        $dataActivateBank = array(
            'is_active' => 1,
        );
        $modelBank->getUpdateBank('id', $request->id_bank, $dataActivateBank);
        return redirect()->route('m_myBank')
                    ->with('message', 'salah satu bank berhasil di aktifkan')
                    ->with('messageclass', 'success');
    }
    
    public function getTransferPin(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = New Member;
        $downline = $dataUser->upline_detail.',['.$dataUser->id.']';
        if($dataUser->upline_detail == null){
            $downline = '['.$dataUser->id.']';
        }
        $getMyStructure = $modelMember->getMyDownline($downline);
        return view('member.pin.transfer-pin')
                        ->with('getData', $getMyStructure)
                        ->with('dataUser', $dataUser);
    }
    
    public function postAddTransferPin(Request $request){
        $dataUser = Auth::user();
        if (Hash::check($request->confirm_password, $dataUser->password)) {
            $to_id = $request->to_id;
            $total_pin = $request->total_pin;
            $modelPin = new Pin;
            $modelMember = new Member;
            $cekMember = $modelMember->getUsers('id', $to_id);
            $myLastPin = $modelPin->getMyLastPin($dataUser);
            $code = 'PT'.date('Ymd').sprintf("%03s", $total_pin);
            $my_memberPin = array(
                'user_id' => $dataUser->id,
                'total_pin' => $total_pin,
                'setting_pin' => $myLastPin->setting_pin,
                'pin_code' => $code,
                'is_used' => 1,
                'used_at' => date('Y-m-d H:i:s'),
                 'pin_status' => 2,
                'transfer_user_id' => $to_id
            );
            $modelPin->getInsertMemberPin($my_memberPin);
            $to_memberPin = array(
                'user_id' => $to_id,
                'total_pin' => $total_pin,
                'setting_pin' => $myLastPin->setting_pin,
                'pin_code' => $code,
                'transfer_from_user_id' => $dataUser->id
            );
            $modelPin->getInsertMemberPin($to_memberPin);
            return redirect()->route('m_addTransferPin')
                    ->with('message', 'Pin berhasil di-transfer ke '.$cekMember->name.' ('.$cekMember->user_code.') sejumlah '.$total_pin)
                    ->with('messageclass', 'success');
        }
        return redirect()->route('m_addTransferPin')
                    ->with('message', 'Password salah, pastikan password adalah yang anda pakai untuk login aplikasi')
                    ->with('messageclass', 'danger');
    }
    
    public function getStatusMember(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $modelMember = New Member;
        $downline = $dataUser->upline_detail.',['.$dataUser->id.']';
        if($dataUser->upline_detail == null){
            $downline = '['.$dataUser->id.']';
        }
        $getMyStructure = $modelMember->getMyDownlineAllStatus($downline, $dataUser->id);
        $modelMemberPackage = New Memberpackage;
        $getCheckNewOrder = $modelMemberPackage->getCountMemberPackageInactive($dataUser);
        return view('member.sponsor.status-member')
                ->with('getData', $getMyStructure)
                ->with('dataOrder', $getCheckNewOrder)
                ->with('dataUser', $dataUser);
    }
    
    public function getAddUpgrade(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->member_type == 4){
            return redirect()->route('mainDashboard')
                    ->with('message', 'Paket anda sudah yang tertinggi')
                    ->with('messageclass', 'danger');
        }
        $date30 =  strtotime(date('Y-m-d', strtotime('+30 days', strtotime($dataUser->active_at))));
        $dateNow = strtotime(date('Y-m-d 23:59:59'));
        if($date30 < $dateNow){
            return redirect()->route('mainDashboard')
                        ->with('message', 'Masa berlaku melakukan upgrade telah habis')
                        ->with('messageclass', 'danger');
        }
        $modePackage = New Package;
        $getPackageUpgrade = $modePackage->getAllPackageUpgrade($dataUser);
        return view('member.package.upgrade-package')
                ->with('headerTitle', 'Upgrade Package')
                ->with('packageUpgrade', $getPackageUpgrade)
                ->with('dataUser', $dataUser);
    }
    
    public function postAddUpgrade(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        $modelPackage = New Package;
        $modelMembership = New Membership;
        $modelMember = New Member;
        $modelPin = New Pin;
        $userPackage = $modelPackage->getMyPackage($dataUser);
        $total_pin_all = $userPackage->pin + $request->total_pin;
        $getNewPackage = $modelPackage->getMyPackagePin($total_pin_all);
        $dataUpgrade = array(
            'user_id' => $dataUser->id,
            'member_type_old' => $dataUser->member_type,
            'member_type_new' => $getNewPackage->id,
            'type' => 1
        );
        $modelMembership->getInsertMembership($dataUpgrade);
        $dataMemberUpdate = array(
            'package_id' => $getNewPackage->id,
            'member_type' => $getNewPackage->id,
            'upgrade_at' => date('Y-m-d H:i:s')
        );
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataMemberUpdate);
        $getMylastPin = $modelPin->getMyLastPin($dataUser);
        $code = sprintf("%03s", $request->total_pin);
        $memberPin = array(
            'user_id' => $dataUser->id,
            'total_pin' => $request->total_pin,
            'setting_pin' => $getMylastPin->setting_pin,
            'pin_code' => $getMylastPin->pin_code.$code.$dataUser->id,
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s'),
            'used_user_id' => $dataUser->id,
            'pin_status' => 1,
            'is_upgrade' => 1
        );
        $modelPin->getInsertMemberPin($memberPin);
        $modelBonusSetting = new Bonussetting;
        $modelBonus = new Bonus;
        $getBonusStart =$modelBonusSetting->getActiveBonusStart();
        $bonus_price = $getBonusStart->start_price * $request->total_pin;
        $dataInsertBonus = array(
            'user_id' => $dataUser->sponsor_id,
            'from_user_id' => $dataUser->id,
            'type' => 1,
            'bonus_price' => $bonus_price,
            'bonus_date' => date('Y-m-d'),
            'poin_type' => 1,
            'total_pin' => $request->total_pin
        );
        $modelBonus->getInsertBonusMember($dataInsertBonus);
        return redirect()->route('mainDashboard')
                    ->with('message', 'Upgrade member berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function getAddRO(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $modelPin = new Pin;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        return view('member.pin.ro')
                ->with('dataPin', $getTotalPin)
                ->with('dataUser', $dataUser);
    }
    
    public function postAddRO(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $modelPin = New Pin;
        $modelMember = New Member;
        $modelBonusSetting = new Bonussetting;
        $modelBonus = new Bonus;
        $getMylastPin = $modelPin->getMyLastPin($dataUser);
        $code = sprintf("%03s", $request->total_pin);
        $memberPin = array(
            'user_id' => $dataUser->id,
            'total_pin' => $request->total_pin,
            'setting_pin' => $getMylastPin->setting_pin,
            'pin_code' => $getMylastPin->pin_code.$code.$dataUser->id,
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s'),
            'used_user_id' => $dataUser->id,
            'pin_status' => 1,
            'is_ro' => 1
        );
        $modelPin->getInsertMemberPin($memberPin);
        $getDay1_thisMonth = date('Y-m-01', strtotime(date('Y-m-d')));
        $getDay1_nextMonth = date('Y-m-01', strtotime('+1 Month'));
        $getMaxPin = $modelPin->getCheckMaxPinROByDate($dataUser->sponsor_id, $getDay1_thisMonth, $getDay1_nextMonth);
        if($getMaxPin >= 4){
            return redirect()->route('mainDashboard')
                        ->with('message', 'Repeat Order member berhasil')
                        ->with('messageclass', 'success');
        }
        $getLevelSp = $modelMember->getLevelSponsoring($dataUser->id);
        $modelSettingPin = New Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $price_pin = $getActivePinSetting->price * $request->total_pin;
        $royalti_statik = 1;
        $bonus_royalti = $royalti_statik/100 * $price_pin;
        if($getLevelSp->id_lvl1 != null){
            $dataInsertBonusLvl1 = array(
                'user_id' => $getLevelSp->id_lvl1,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 1,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl1);
        }
        if($getLevelSp->id_lvl2 != null){
            $dataInsertBonusLvl2 = array(
                'user_id' => $getLevelSp->id_lvl2,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 2,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl2);
        }
        if($getLevelSp->id_lvl3 != null){
            $dataInsertBonusLvl3 = array(
                'user_id' => $getLevelSp->id_lvl3,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 3,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl3);
        }
        if($getLevelSp->id_lvl4 != null){
            $dataInsertBonusLvl4 = array(
                'user_id' => $getLevelSp->id_lvl4,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 4,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl4);
        }
        if($getLevelSp->id_lvl5 != null){
            $dataInsertBonusLvl5 = array(
                'user_id' => $getLevelSp->id_lvl5,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 5,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl5);
        }
        if($getLevelSp->id_lvl6 != null){
            $dataInsertBonusLvl6 = array(
                'user_id' => $getLevelSp->id_lvl6,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 6,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl6);
        }
        if($getLevelSp->id_lvl7 != null){
            $dataInsertBonusLvl7 = array(
                'user_id' => $getLevelSp->id_lvl7,
                'from_user_id' => $dataUser->id,
                'type' => 3,
                'bonus_price' => $bonus_royalti,
                'bonus_date' => date('Y-m-d'),
                'poin_type' => 1,
                'level_id' => 7,
                'total_pin' => $request->total_pin
            );
            $modelBonus->getInsertBonusMember($dataInsertBonusLvl7);
        }
        return redirect()->route('mainDashboard')
                    ->with('message', 'Repeat Order member berhasil')
                    ->with('messageclass', 'success');
    }
    
    public function getMySponsorTree(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_active == 0){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->upline_id == null){
            if($dataUser->id > 4){
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = New Member;
        $back = false;
        $downline = $dataUser->upline_detail.',['.$dataUser->id.']';
        if($dataUser->upline_detail == null){
            $downline = '['.$dataUser->id.']';
        }
        if($request->get_id != null){
            if($request->get_id != $dataUser->id){
                $back = true;
                $dataUser = $modelMember->getCekIdDownlineSponsor($request->get_id, $dataUser->id);
            }
        }
        if($dataUser == null){
            return redirect()->route('mainDashboard');
        }
        $getBinary = $modelMember->getStructureSponsor($dataUser);
        return view('member.networking.sponsor-tree')
                        ->with('getData', $getBinary)
                        ->with('back', $back)
                        ->with('dataUser', $dataUser);
    }
    
    public function getMyTron(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_tron == 0){
            return redirect()->route('m_newTron')
                    ->with('message', 'data Tron belum ada, silakan isi data tron anda')
                    ->with('messageclass', 'danger');
        }
        return view('member.profile.my-tron')
                    ->with('headerTitle', 'Tron')
                    ->with('dataUser', $dataUser);
    }
    
    public function getAddMyTron(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_tron == 1){
            return redirect()->route('m_myTron');
        }
        return view('member.profile.add-tron')
                ->with('headerTitle', 'TRON')
                ->with('dataUser', $dataUser);
    }
    
    public function postAddMyTron(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_tron == 1){
            return redirect()->route('m_myTron');
        }
        $dataUpdate = array(
            'is_tron' => 1,
            'tron' => $request->tron,
            'tron_at' => date('Y-m-d H:i:s')
        );
        $modelMember = New Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdate);
        return redirect()->route('m_myTron')
                    ->with('message', 'Data Tron berhasil dibuat')
                    ->with('messageclass', 'success');
    }
    
    public function getRequestMemberStockist(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        return view('member.profile.add-stockist')
                ->with('headerTitle', 'Aplikasi Pengajuan Stockist')
                ->with('dataUser', $dataUser);
    }
    
    public function postRequestMemberStockist(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        $modelMember = New Member;
        $dataInsert = array(
            'user_id' => $dataUser->id
        );
        $modelMember->getInsertStockist($dataInsert);
        return redirect()->route('m_myProfile')
                    ->with('message', 'Aplikasi Pengajuan Stockist berhasil dibuat')
                    ->with('messageclass', 'success');
    }
    
    public function getSearchStockist(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('mainDashboard'); //lari ke menu stokist
        }
        $modelMember = New Member;
        $getData = null;
        if($dataUser->kode_daerah != null){
            $dataDaerah = explode('.', $dataUser->kode_daerah);
            $provKota = $dataDaerah[0].'.'.$dataDaerah[1];
            $getData = $modelMember->getSearchUserByLocation($provKota);
        }
        return view('member.profile.m_shop')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function postSearchStockist(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('mainDashboard'); 
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        $modelMember = New Member;
        $getData = $modelMember->getSearchUserStockist($request->user_name);
        return view('member.profile.m_shop')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getMemberShoping($stokist_id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('m_MemberStockistShoping'); 
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getAllPurchase();
        return view('member.sales.m_shoping')
                ->with('getData', $getData)
                ->with('id', $stokist_id)
                ->with('dataUser', $dataUser);
    }
    
    public function getDetailPurchase($stokist_id, $id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('m_MemberStockistShoping'); 
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getDetailPurchase($id);
        return view('member.sales.m_purchase_view')
                ->with('getData', $getData)
                ->with('stokist_id', $stokist_id)
                ->with('dataUser', $dataUser);
    }
    
    public function postMemberShoping(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('m_MemberStockistShoping');
        }
        $modelSales = New Sales;
        $arrayLog = json_decode($request->cart_list, true);
        $modelMember = New Member;
        $user_id = $dataUser->id;
        $stockist_id = $request->stockist_id;
        $is_stockist = 0;
        $invoice = $modelSales->getCodeMasterSales($user_id);
        $sale_date = date('Y-m-d');
        $total_price = 0;
        foreach($arrayLog as $rowTotPrice){
            $total_price += $rowTotPrice['product_quantity'] * $rowTotPrice['product_price'];
        }
        $dataInsertMasterSales = array(
            'user_id' => $user_id,
            'stockist_id' => $stockist_id,
            'is_stockist' => $is_stockist,
            'invoice' => $invoice,
            'total_price' => $total_price,
            'sale_date' => $sale_date,
        );
        $insertMasterSales = $modelSales->getInsertMasterSales($dataInsertMasterSales);
        foreach($arrayLog as $row){
            $dataInsert = array(
                'user_id' => $user_id,
                'stockist_id' => $stockist_id,
                'is_stockist' => $is_stockist,
                'purchase_id' => $row['product_id'],
                'invoice' => $invoice,
                'amount' => $row['product_quantity'],
                'sale_price' => $row['product_quantity'] * $row['product_price'],
                'sale_date' => $sale_date,
                'master_sales_id' => $insertMasterSales->lastID
            );
            $modelSales->getInsertSales($dataInsert);
        }
//        $dataInsertStock = array(
//            'purchase_id' => $request->id_barang,
//            'user_id' => $dataUser->id,
//            'type' => 2,
//            'amount' => $request->qty,
//            'sales_id' => $insertSales->lastID
//        );
//        $modelSales->getInsertStock($dataInsertStock);
        return redirect()->route('m_historyShoping')
                    ->with('message', 'Berhasil member belanja')
                    ->with('messageclass', 'success');
    }
    
    public function getMemberStockistShoping(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 0){
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getAllPurchase();
        return view('member.sales.m_stockist_shoping')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getStockistDetailPurchase($id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 0){
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getDetailPurchase($id);
        return view('member.sales.m_stockist_purchase_view')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function postMemberStockistShoping(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 0){
            return redirect()->route('m_SearchStockist');
        }
        $arrayLog = json_decode($request->cart_list, true);
//        dd($arrayLog);
        $modelSales = New Sales;
        $modelMember = New Member;
        $user_id = $dataUser->id;
        $stockist_id = $dataUser->id;
        $is_stockist = $dataUser->is_stockist;
        $invoice = $modelSales->getCodeMasterSales($user_id);
        $sale_date = date('Y-m-d');
        $total_price = 0;
        foreach($arrayLog as $rowTotPrice){
            $total_price += $rowTotPrice['product_quantity'] * $rowTotPrice['product_price'];
        }
        $dataInsertMasterSales = array(
            'user_id' => $user_id,
            'stockist_id' => $stockist_id,
            'is_stockist' => $is_stockist,
            'invoice' => $invoice,
            'total_price' => $total_price,
            'sale_date' => $sale_date,
        );
//        dd($dataInsertMasterSales);
        $insertMasterSales = $modelSales->getInsertMasterSales($dataInsertMasterSales);
        foreach($arrayLog as $row){
            $dataInsert = array(
                'user_id' => $user_id,
                'stockist_id' => $stockist_id,
                'is_stockist' => $is_stockist,
                'purchase_id' => $row['product_id'],
                'invoice' => $invoice,
                'amount' => $row['product_quantity'],
                'sale_price' => $row['product_quantity'] * $row['product_price'],
                'sale_date' => $sale_date,
                'master_sales_id' => $insertMasterSales->lastID
            );
            $modelSales->getInsertSales($dataInsert);
        }
        
//        $dataInsertStock = array(
//            'purchase_id' => $request->id_barang,
//            'user_id' => $dataUser->id,
//            'type' => 2,
//            'amount' => $request->qty,
//            'sales_id' => $insertSales->lastID
//        );
//        $modelSales->getInsertStock($dataInsertStock);
        return redirect()->route('m_historyShoping')
                    ->with('message', 'Berhasil member stockist belanja')
                    ->with('messageclass', 'success');
        
    }
    
    public function getMemberStockistReport(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        return redirect()->route('mainDashboard');
    }
    
    public function getEditAddress(){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelMember = New Member;
        $getProvince = $modelMember->getProvinsi();
        return view('member.profile.edit-address')
                ->with('headerTitle', 'Alamat')
                ->with('provinsi', $getProvince)
                ->with('dataUser', $dataUser);
    }
    
    public function postEditAddress(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        $dataUpdate = array(
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'kode_daerah' => $request->kode_daerah,
        );
        $modelMember = New Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdate);
        return redirect()->route('m_myProfile')
                    ->with('message', 'Alamat profile berhasil ubah')
                    ->with('messageclass', 'success');
    }
    
    public function getHistoryShoping(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $getMonth = $modelSales->getThisMonth();
        if($request->month != null && $request->year != null) {
            $start_day = date('Y-m-01', strtotime($request->year.'-'.$request->month));
            $end_day = date('Y-m-t', strtotime($request->year.'-'.$request->month));
            $text_month = date('F Y', strtotime($request->year.'-'.$request->month));
            $getMonth = (object) array(
                'startDay' => $start_day,
                'endDay' => $end_day,
                'textMonth' => $text_month
            );
        }
        $getData = $modelSales->getMemberMasterSalesHistory($dataUser->id, $getMonth);
        $sum = 0;
        if($getData != null){
            foreach($getData as $row){
                $sum += $row->sale_price;
            }
        }
        return view('member.sales.history_sales')
                ->with('headerTitle', 'History Belanja')
                ->with('getData', $getData)
                ->with('sum', $sum)
                ->with('getDate', $getMonth)
                ->with('dataUser', $dataUser);
    }
    
    public function getMemberPembayaran($id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('m_MemberStockistShoping'); 
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $modelBank = New Bank;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($id);
        //klo kosong
        $getDataSales = $modelSales->getMemberPembayaranSales($getDataMaster->id);
        $getStockist = $modelMember->getUsers('id', $getDataMaster->stockist_id);
        $getStockistBank = $modelBank->getBankMemberActive($getStockist);
        return view('member.sales.m_pembayaran')
                    ->with('headerTitle', 'Pembayaran')
                    ->with('getDataSales', $getDataSales)
                    ->with('getDataMaster', $getDataMaster)
                    ->with('getStockist', $getStockist)
                    ->with('getStockistBank', $getStockistBank)
                    ->with('dataUser', $dataUser);
    }
    
    public function postMemberPembayaran(Request $request){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 1){
            return redirect()->route('m_MemberStockistShoping'); 
        }
        if($dataUser->is_profile == 0){
            return redirect()->route('m_SearchStockist')
                    ->with('message', 'Data profil anda belum lengkap')
                    ->with('messageclass', 'danger');
        }
        $modelSales = New Sales;
        $tron = null;
        $tron_transfer = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($request->master_sale_id);
        if($request->buy_metode == 1){
            $buy_metode = 1;
        }
        if($request->buy_metode == 2){
            $buy_metode = 2;
            $bank_name = $request->bank_name;
            $account_no = $request->account_no;
            $account_name = $request->account_name;
        }
        if($request->buy_metode == 3){
            $buy_metode = 3;
            $tron = $request->tron;
            if($request->tron_tranfer == null){
                return redirect()->route('m_MemberPembayaran', [$request->master_sale_id])
                        ->with('message', 'Hash transaksi transfer dari Blockchain TRON belum diisi')
                        ->with('messageclass', 'danger');
            }
            $tron_transfer = $request->tron_tranfer;
        }
        $dataUpdate = array(
            'status' => 1,
            'buy_metode' => $buy_metode,
            'tron' => $tron,
            'tron_transfer' => $tron_transfer,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
        );
        $modelSales->getUpdateMasterSales('id', $request->master_sale_id, $dataUpdate);
        return redirect()->route('m_historyShoping')
                        ->with('message', 'Berhsil konfirmasi pembayaran')
                        ->with('messageclass', 'success');
    }
    
    public function getMemberStockistPembayaran($id){
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if(!in_array($dataUser->user_type, $onlyUser)){
            return redirect()->route('mainDashboard');
        }
        if($dataUser->package_id == null){
            return redirect()->route('m_newPackage');
        }
        if($dataUser->is_stockist == 0){
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = New Sales;
        $modelMember = New Member;
        $modelBank = New Bank;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($id);
        //klo kosong
        $getDataSales = $modelSales->getMemberPembayaranSales($getDataMaster->id);
        $getStockistBank = $modelBank->getBankMemberActive($dataUser);
        return view('member.sales.m_stockist_pembayaran')
                    ->with('headerTitle', 'Pembayaran')
                    ->with('getDataSales', $getDataSales)
                    ->with('getDataMaster', $getDataMaster)
                    ->with('getStockist', $dataUser)
                    ->with('getStockistBank', $getStockistBank)
                    ->with('dataUser', $dataUser);
    }
    
    
    
    
    
    
    
    
    
    
    
    
}