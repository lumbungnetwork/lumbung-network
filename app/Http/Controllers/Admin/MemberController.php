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
use App\Model\Transferwd;
use GuzzleHttp\Client;

//1.  Kartu Halo 1500
//2.  PLN 500
//3.  Telkom 1000
//4.  Finance (Cicilan) 1000

class MemberController extends Controller
{

    public function __construct()
    {
    }

    public function getMyProfile()
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
        return view('member.profile.my-profile')
            ->with('headerTitle', 'Profil Saya')
            ->with('dataUser', $dataUser);
    }

    public function getAddMyProfile()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 1) {
            return redirect()->route('m_myProfile')
                ->with('message', 'Tidak bisa buat profil')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $getProvince = $modelMember->getProvinsi();
        return view('member.profile.add-profile')
            ->with('headerTitle', 'Profile')
            ->with('provinsi', $getProvince)
            ->with('dataUser', $dataUser);
    }

    public function postAddMyProfile(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $dataUpdate = array(
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'is_profile' => 1,
            'profile_created_at' => date('Y-m-d H:i:s'),
            'kode_daerah' => $request->kode_daerah,
        );
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdate);
        return redirect()->route('m_myProfile')
            ->with('message', 'Profil data diri berhasil dibuat')
            ->with('messageclass', 'success');
    }

    public function getAddSponsor()
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        return view('member.sponsor.add-sponsor')
            ->with('headerTitle', 'Sponsor')
            ->with('dataUser', $dataUser);
    }

    public function postAddSponsor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
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
        $dataEmail = array(
            'email' => $request->email,
            'password' => $request->password,
            'hp' => $request->hp,
            'user_code' => $request->user_code
        );
        $emailSend = $request->email;
        Mail::send('member.email.email', $dataEmail, function ($message) use ($emailSend) {
            $message->to($emailSend, 'Lumbung Network Registration')
                ->subject('Welcome to Lumbung Network');
        });
        return redirect()->route('m_newSponsor')
            ->with('message', 'Registrasi member baru berhasil')
            ->with('messageclass', 'success');
    }

    public function getStatusSponsor()
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
        $modelMember = new Member;
        $getAllSponsor = $modelMember->getAllDownlineSponsor($dataUser);
        return view('member.sponsor.status-sponsor')
            ->with('getData', $getAllSponsor)
            ->with('dataUser', $dataUser);
    }

    public function getAddPackage()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id != null) {
            return redirect()->route('mainDashboard');
        }
        $modePackage = new Package;
        $modelSettingPin = new Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $getAllPackage = $modePackage->getAllPackage();
        return view('member.package.add-package')
            ->with('headerTitle', 'Package')
            ->with('allPackage', $getAllPackage)
            ->with('pinSetting', $getActivePinSetting)
            ->with('dataUser', $dataUser);
    }

    public function postAddPackage(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id != null) {
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = new Memberpackage;
        $modelPackage = new Package;
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
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdatePackageId);
        return redirect()->route('mainDashboard')
            ->with('message', 'Order Package success, please wait your sponsor activate')
            ->with('messageclass', 'success');
    }

    public function getListOrderPackage()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = new Memberpackage;
        $getCheckNewOrder = $modelMemberPackage->getMemberPackageInactive($dataUser);
        if (count($getCheckNewOrder) == 0) {
            return redirect()->route('mainDashboard')
                ->with('message', 'No one member order package')
                ->with('messageclass', 'danger');
        }
        return view('member.package.order-list-package')
            ->with('headerTitle', 'Package')
            ->with('allPackage', $getCheckNewOrder)
            ->with('dataUser', $dataUser);
    }

    public function getDetailOrderPackage($paket_id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = new Memberpackage;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($paket_id, $dataUser);
        if ($getData == null) {
            return redirect()->route('mainDashboard');
        }
        $modelSettingPin = new Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        return view('member.package.order-detail-package')
            ->with('headerTitle', 'Package')
            ->with('getData', $getData)
            ->with('pinSetting', $getActivePinSetting)
            ->with('dataUser', $dataUser);
    }

    public function postActivatePackage(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = new Memberpackage;
        $modelPin = new Pin;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($request->id_paket, $dataUser);
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $getMylastPin = $modelPin->getMyLastPin($dataUser);
        $sisaPin = $getTotalPin->sum_pin_masuk - $getTotalPin->sum_pin_keluar;
        if ($sisaPin < $getData->total_pin) {
            return redirect()->route('m_detailOrderPackage', $getData->id)
                ->with('message', 'Paket tidak cukup untuk mengaktifasi sponsor baru, silakan beli pin')
                ->with('messageclass', 'danger');
        }
        $code = sprintf("%03s", $getData->total_pin);
        $memberPin = array(
            'user_id' => $dataUser->id,
            'total_pin' => $getData->total_pin,
            'setting_pin' => $getMylastPin->setting_pin,
            'pin_code' => $getMylastPin->pin_code . $code . $getData->request_user_id,
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
        $getBonusStart = $modelBonusSetting->getActiveBonusStart();
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
        $modelMember = new Member;
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

    public function postRejectPackage(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMemberPackage = new Memberpackage;
        $modelMember = new Member;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($request->id_paket, $dataUser);
        $dataUpdate = array(
            'status' => 10
        );
        $modelMemberPackage->getUpdateMemberPackage('id', $getData->id, $dataUpdate);
        $dataUpdateIsActive = array(
            'is_login' => 0,
            'sponsor_id' => null,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelMember->getUpdateUsers('id', $getData->request_user_id, $dataUpdateIsActive);
        return redirect()->route('mainDashboard')
            ->with('message', 'member baru di reject')
            ->with('messageclass', 'success');
    }

    public function getAddPin()
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
        return view('member.pin.order-pin')
            ->with('headerTitle', 'Pin')
            ->with('dataUser', $dataUser);
    }

    public function postAddPin(Request $request)
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
        $disc = 0;
        $modelSettingPin = new Pinsetting;
        $modelSettingTrans = new Transaction;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $hargaAwal = $getActivePinSetting->price * $request->total_pin;
        $discAwal = $hargaAwal * $disc / 100;
        $harga = $hargaAwal - $discAwal;
        $code = $modelSettingTrans->getCodeTransaction();
        $rand = rand(101, 249);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'transaction_code' => 'TR' . date('Ymd') . $dataUser->id . $code,
            'total_pin' => $request->total_pin,
            'price' => $harga,
            'unique_digit' => $rand,
        );
        $getIDTrans = $modelSettingTrans->getInsertTransaction($dataInsert);
        return redirect()->route('m_addTransaction', [$getIDTrans->lastID])
            ->with('message', 'Order Pin berhasil, silakan lakukan proses transfer')
            ->with('messageclass', 'success');
    }

    public function getListTransactions()
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
        $modelSettingTrans = new Transaction;
        $getAllTransaction = $modelSettingTrans->getTransactionsMember($dataUser);
        return view('member.pin.list-transaction')
            ->with('headerTitle', 'Transaction')
            ->with('getData', $getAllTransaction)
            ->with('dataUser', $dataUser);
    }

    public function getAddTransaction($id)
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
        $modelTrans = new Transaction;
        $modelBank = new Bank;
        $getTrans = $modelTrans->getDetailTransactionsMember($id, $dataUser);
        if ($getTrans == null) {
            return redirect()->route('mainDashboard');
        }
        $getPerusahaanTron = null;
        if ($getTrans->bank_perusahaan_id != null) {
            if ($getTrans->is_tron == 0) {
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

    public function postAddTransaction(Request $request)
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
        $modelSettingTrans = new Transaction;
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
        $alamat = $getTrans->to_name . ' a/n ' . $getTrans->account_name . ' no rek. ' . $getTrans->account;
        if ($request->is_tron == 1) {
            $metodePembayaran = 'Transfer eIDR';
            $alamat = $getTrans->to_name . ' a/n ' . $getTrans->account;
        }
        $dataEmail = array(
            'tgl_order' => date('d F Y'),
            'nama' => $dataUser->user_code,
            'hp' => $dataUser->hp,
            'transaction_code' => $getTrans->transaction_code,
            'total_pin' => $getTrans->total_pin,
            'price' => 'Rp ' . number_format($getTrans->price, 0, ',', '.'),
            'unique_digit' => $getTrans->unique_digit,
            'metode' => $metodePembayaran,
            'alamat' => $alamat
        );
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.pin_confirm', $dataEmail, function ($message) use ($emailSend) {
            $message->to($emailSend, 'Konfirmasi Data Pembelian PIN Member Lumbung Network')
                ->subject('Konfirmasi Data Pembelian PIN Member Lumbung Network');
        });
        return redirect()->route('m_listTransactions')
            ->with('message', 'Konfirmasi transfer berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectTransaction(Request $request)
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
        if ($request->reason == null) {
            return redirect()->route('m_addTransaction', [$request->id_trans])
                ->with('message', 'Alasan harus diisi')
                ->with('messageclass', 'danger');
        }
        $modelSettingTrans = new Transaction;
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

    public function getMyPinStock()
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
        $modelPengiriman = new Pengiriman;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $getTotalPinTerkirim = $modelPengiriman->getCekPinTuntasTerkirim($dataUser);
        return view('member.pin.pin-stock')
            ->with('dataPin', $getTotalPin)
            ->with('dataTerkirim', $getTotalPinTerkirim)
            ->with('dataUser', $dataUser);
    }

    public function getMyPinHistory()
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
        $getHistoryPin = $modelPin->getMyHistoryPin($dataUser);
        return view('member.pin.pin-history')
            ->with('getData', $getHistoryPin)
            ->with('dataUser', $dataUser);
    }

    public function getAddPlacement(Request $request)
    {
        $dataUser = Auth::user();
        $myUserSession = $dataUser;
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = new Member;
        $back = false;
        if ($request->get_id != null) {
            $back = true;
            $dataUser = $modelMember->getUsers('id', $request->get_id);
        }
        $getBinary = $modelMember->getBinary($dataUser);
        $downline = $myUserSession->upline_detail . ',[' . $myUserSession->id . ']';
        if ($myUserSession->upline_detail == null) {
            $downline = '[' . $myUserSession->id . ']';
        }
        $getAllDownline = $modelMember->getMyDownline($downline);
        return view('member.sponsor.placement')
            ->with('getAllDownline', $getAllDownline)
            ->with('back', $back)
            ->with('getData', $getBinary)
            ->with('dataUser', $dataUser);
    }

    public function postAddPlacement(Request $request)
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $posisi = 'kanan_id';
        if ($request->type == 1) {
            $posisi = 'kiri_id';
        }
        $modelMember = new Member;
        $getUplineId = $dataUser;
        if ($request->upline_id != $dataUser->id) {
            $getUplineId = $modelMember->getUsers('id', $request->upline_id);
        }
        if ($getUplineId->$posisi != null) {
            return redirect()->route('m_addPlacement')
                ->with('message', 'Posisi placement yang anda pilih telah terisi, pilih posisi yang lain')
                ->with('messageclass', 'danger');
        }
        $getNewMember = $modelMember->getCekMemberToPlacement($request->id_calon, $dataUser);
        if ($getNewMember == null) {
            return redirect()->route('m_addPlacement')
                ->with('message', 'data member yang akan di placement tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        $newMemberUpline = $getUplineId->upline_detail . ',[' . $getUplineId->id . ']';
        if ($getUplineId->upline_detail == null) {
            $newMemberUpline = '[' . $getUplineId->id . ']';
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

    public function getMyBinary(Request $request)
    {
        $dataUser = Auth::user();
        $sessionUser = Auth::user();
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = new Member;
        $back = false;
        $downline = $dataUser->upline_detail . ',[' . $dataUser->id . ']';
        if ($dataUser->upline_detail == null) {
            $downline = '[' . $dataUser->id . ']';
        }
        if ($request->get_id != null) {
            if ($request->get_id != $dataUser->id) {
                $back = true;
                $dataUser = $modelMember->getCekIdDownline($request->get_id, $downline);
            }
        }
        if ($dataUser == null) {
            return redirect()->route('mainDashboard');
        }
        $getBinary = $modelMember->getBinary($dataUser);
        return view('member.networking.binary')
            ->with('getData', $getBinary)
            ->with('back', $back)
            ->with('dataUser', $dataUser)
            ->with('sessionUser', $sessionUser);
    }

    //Bank
    public function getMyBank()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
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

    public function postAddBank(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_newProfile')
                ->with('message', 'Profil data diri belum ada, Tidak bisa mengisi data bank')
                ->with('messageclass', 'danger');
        }
        $modelBank = new Bank;
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

    public function postActivateBank(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_newProfile')
                ->with('message', 'Profil data diri belum ada, Tidak bisa mengisi data bank')
                ->with('messageclass', 'danger');
        }
        $modelBank = new Bank;
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

    public function getTransferPin()
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = new Member;
        $downline = $dataUser->upline_detail . ',[' . $dataUser->id . ']';
        if ($dataUser->upline_detail == null) {
            $downline = '[' . $dataUser->id . ']';
        }
        $getMyStructure = $modelMember->getMyDownline($downline);
        return view('member.pin.transfer-pin')
            ->with('getData', $getMyStructure)
            ->with('dataUser', $dataUser);
    }

    public function postAddTransferPin(Request $request)
    {
        $dataUser = Auth::user();
        if (Hash::check($request->confirm_password, $dataUser->password)) {
            $to_id = $request->to_id;
            $total_pin = $request->total_pin;
            $modelPin = new Pin;
            $modelMember = new Member;
            $cekMember = $modelMember->getUsers('id', $to_id);
            $myLastPin = $modelPin->getMyLastPin($dataUser);
            $code = 'PT' . date('Ymd') . sprintf("%03s", $total_pin);
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
                ->with('message', 'Pin berhasil di-transfer ke ' . $cekMember->name . ' (' . $cekMember->user_code . ') sejumlah ' . $total_pin)
                ->with('messageclass', 'success');
        }
        return redirect()->route('m_addTransferPin')
            ->with('message', 'Password salah, pastikan password adalah yang anda pakai untuk login aplikasi')
            ->with('messageclass', 'danger');
    }

    public function getStatusMember()
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
        $modelMember = new Member;
        $downline = $dataUser->upline_detail . ',[' . $dataUser->id . ']';
        if ($dataUser->upline_detail == null) {
            $downline = '[' . $dataUser->id . ']';
        }
        $getMyStructure = $modelMember->getMyDownlineAllStatus($downline, $dataUser->id);
        $modelMemberPackage = new Memberpackage;
        $getCheckNewOrder = $modelMemberPackage->getCountMemberPackageInactive($dataUser);
        return view('member.sponsor.status-member')
            ->with('getData', $getMyStructure)
            ->with('dataOrder', $getCheckNewOrder)
            ->with('dataUser', $dataUser);
    }

    public function getAddUpgrade()
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
        if ($dataUser->member_type == 4) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Paket anda sudah yang tertinggi')
                ->with('messageclass', 'danger');
        }
        $date30 =  strtotime(date('Y-m-d', strtotime('+30 days', strtotime($dataUser->active_at))));
        $dateNow = strtotime(date('Y-m-d 23:59:59'));
        if ($date30 < $dateNow) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Masa berlaku melakukan upgrade telah habis')
                ->with('messageclass', 'danger');
        }
        $modePackage = new Package;
        $getPackageUpgrade = $modePackage->getAllPackageUpgrade($dataUser);
        return view('member.package.upgrade-package')
            ->with('headerTitle', 'Upgrade Package')
            ->with('packageUpgrade', $getPackageUpgrade)
            ->with('dataUser', $dataUser);
    }

    public function postAddUpgrade(Request $request)
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
        $modelPackage = new Package;
        $modelMembership = new Membership;
        $modelMember = new Member;
        $modelPin = new Pin;
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
            'pin_code' => $getMylastPin->pin_code . $code . $dataUser->id,
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s'),
            'used_user_id' => $dataUser->id,
            'pin_status' => 1,
            'is_upgrade' => 1
        );
        $modelPin->getInsertMemberPin($memberPin);
        $modelBonusSetting = new Bonussetting;
        $modelBonus = new Bonus;
        $getBonusStart = $modelBonusSetting->getActiveBonusStart();
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

    public function getAddRO()
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $modelPin = new Pin;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        return view('member.pin.ro')
            ->with('dataPin', $getTotalPin)
            ->with('dataUser', $dataUser);
    }

    public function postAddRO(Request $request)
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $modelPin = new Pin;
        $modelMember = new Member;
        $modelBonusSetting = new Bonussetting;
        $modelBonus = new Bonus;
        $getMylastPin = $modelPin->getMyLastPin($dataUser);
        $code = sprintf("%03s", $request->total_pin);
        $memberPin = array(
            'user_id' => $dataUser->id,
            'total_pin' => $request->total_pin,
            'setting_pin' => $getMylastPin->setting_pin,
            'pin_code' => $getMylastPin->pin_code . $code . $dataUser->id,
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s'),
            'used_user_id' => $dataUser->id,
            'pin_status' => 1,
            'is_ro' => 1
        );
        $modelPin->getInsertMemberPin($memberPin);
        $getDay1_thisMonth = date('Y-m-01', strtotime(date('Y-m-d')));
        $getDay1_nextMonth = date('Y-m-01', strtotime('+1 Month'));
        //update package id (sistemnya nambah)
        $new_total_pin = $dataUser->pin_activate + $request->total_pin;
        $dataUpdatePackageId = array(
            'pin_activate' => $new_total_pin,
            'pin_activate_at' => date('Y-m-d H:i:s'),
        );
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdatePackageId);

        //bonus sponsor disini
        $getBonusStart = $modelBonusSetting->getActiveBonusStart();
        $bonus_price = $getBonusStart->start_price * $request->total_pin;
        $dataInsertBonusSponsor = array(
            'user_id' => $dataUser->sponsor_id,
            'from_user_id' => $dataUser->id,
            'type' => 1,
            'bonus_price' => $bonus_price,
            'bonus_date' => date('Y-m-d'),
            'poin_type' => 1,
            'total_pin' => $request->total_pin
        );
        $modelBonus->getInsertBonusMember($dataInsertBonusSponsor);

        $getMaxPin = $modelPin->getCheckMaxPinROByDate($dataUser->sponsor_id, $getDay1_thisMonth, $getDay1_nextMonth);
        if ($getMaxPin >= 4) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Resubscribe member berhasil')
                ->with('messageclass', 'success');
        }
        //        $getLevelSp = $modelMember->getLevelSponsoring($dataUser->id);
        $modelSettingPin = new Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $price_pin = $getActivePinSetting->price * $request->total_pin;
        $royalti_statik = 1;
        $bonus_royalti = ($royalti_statik / 100 * $price_pin) / 2; //500
        //        if($getLevelSp->id_lvl1 != null){
        //            $dataInsertBonusLvl1 = array(
        //                'user_id' => $getLevelSp->id_lvl1,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 1,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl1);
        //        }
        //        if($getLevelSp->id_lvl2 != null){
        //            $dataInsertBonusLvl2 = array(
        //                'user_id' => $getLevelSp->id_lvl2,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 2,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl2);
        //        }
        //        if($getLevelSp->id_lvl3 != null){
        //            $dataInsertBonusLvl3 = array(
        //                'user_id' => $getLevelSp->id_lvl3,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 3,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl3);
        //        }
        //        if($getLevelSp->id_lvl4 != null){
        //            $dataInsertBonusLvl4 = array(
        //                'user_id' => $getLevelSp->id_lvl4,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 4,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl4);
        //        }
        //        if($getLevelSp->id_lvl5 != null){
        //            $dataInsertBonusLvl5 = array(
        //                'user_id' => $getLevelSp->id_lvl5,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 5,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl5);
        //        }
        //        if($getLevelSp->id_lvl6 != null){
        //            $dataInsertBonusLvl6 = array(
        //                'user_id' => $getLevelSp->id_lvl6,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 6,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl6);
        //        }
        //        if($getLevelSp->id_lvl7 != null){
        //            $dataInsertBonusLvl7 = array(
        //                'user_id' => $getLevelSp->id_lvl7,
        //                'from_user_id' => $dataUser->id,
        //                'type' => 3,
        //                'bonus_price' => $bonus_royalti,
        //                'bonus_date' => date('Y-m-d'),
        //                'poin_type' => 1,
        //                'level_id' => 7,
        //                'total_pin' => $request->total_pin
        //            );
        //            $modelBonus->getInsertBonusMember($dataInsertBonusLvl7);
        //        }
        return redirect()->route('mainDashboard')
            ->with('message', 'Resubscribe member berhasil')
            ->with('messageclass', 'success');
    }

    public function getMySponsorTree(Request $request)
    {
        $dataUser = Auth::user();
        $sessionUser = Auth::user();
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
        if ($dataUser->upline_id == null) {
            if ($dataUser->id > 4) {
                return redirect()->route('mainDashboard');
            }
        }
        $modelMember = new Member;
        $back = false;
        $downline = $dataUser->upline_detail . ',[' . $dataUser->id . ']';
        if ($dataUser->upline_detail == null) {
            $downline = '[' . $dataUser->id . ']';
        }
        if ($request->get_id != null) {
            if ($request->get_id < $sessionUser->id) {
                return redirect()->route('mainDashboard');
            }
            if ($request->get_id != $dataUser->id) {
                $back = true;
                $dataUser = $modelMember->getUsers('id', $request->get_id);
            }
        }
        if ($dataUser == null) {
            return redirect()->route('m_mySponsorTree');
        }
        $getBinary = $modelMember->getStructureSponsor($dataUser);
        return view('member.networking.sponsor-tree')
            ->with('getData', $getBinary)
            ->with('back', $back)
            ->with('dataUser', $dataUser)
            ->with('sessionUser', $sessionUser);
    }

    public function getMyTron()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_tron == 0) {
            return redirect()->route('m_newTron')
                ->with('message', 'data Tron belum ada, silakan isi data tron anda')
                ->with('messageclass', 'danger');
        }
        return view('member.profile.my-tron')
            ->with('headerTitle', 'Tron')
            ->with('dataUser', $dataUser);
    }

    public function getAddMyTron()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_tron == 1) {
            return redirect()->route('m_myTron');
        }
        return view('member.profile.add-tron')
            ->with('headerTitle', 'TRON')
            ->with('dataUser', $dataUser);
    }

    public function postAddMyTron(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_tron == 1) {
            return redirect()->route('m_myTron');
        }
        $dataUpdate = array(
            'is_tron' => 1,
            'tron' => $request->tron,
            'tron_at' => date('Y-m-d H:i:s')
        );
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdate);
        return redirect()->route('m_myTron')
            ->with('message', 'Data Tron berhasil dibuat')
            ->with('messageclass', 'success');
    }

    public function getRequestMemberStockist()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        if ($dataUser->is_stockist == 1 && $dataUser->is_vendor == 1) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Anda sudah menjadi member salah satu stockist atau vendor')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $cekRequestStockist = $modelMember->getCekRequestSotckist($dataUser->id);
        if ($cekRequestStockist != null) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Anda sudah pernah mengajukan menjadi stockist')
                ->with('messageclass', 'danger');
        }
        return view('member.profile.add-stockist')
            ->with('headerTitle', 'Aplikasi Pengajuan Stockist')
            ->with('dataUser', $dataUser);
    }

    public function postRequestMemberStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $dataInsert = array(
            'user_id' => $dataUser->id
        );
        $modelMember->getInsertStockist($dataInsert);
        return redirect()->route('m_SearchStockist')
            ->with('message', 'Aplikasi Pengajuan Stockist berhasil dibuat')
            ->with('messageclass', 'success');
    }

    public function getSearchStockist()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMember = new Member;
        $getDataKelurahan = null;
        $getDataKecamatan = null;
        $getDataKota = null;
        $getData = null;
        if ($dataUser->kode_daerah != null) {
            $dataDaerah = explode('.', $dataUser->kode_daerah);
            $provKota = $dataDaerah[0] . '.' . $dataDaerah[1];
            $kelurahan = $dataUser->kelurahan;
            $kecamatan = $dataUser->kecamatan;
            $kota = $dataUser->kota;
            $getDataKelurahan = $modelMember->getSearchUserByKelurahan($kelurahan, $kecamatan);
            $getDataKecamatan = $modelMember->getSearchUserByKecamatan($kecamatan, $kelurahan);
            $getDataKota = $modelMember->getSearchUserByKota($kota, $kecamatan, $kelurahan);
        }
        $cekRequestStockist = $modelMember->getCekRequestSotckist($dataUser->id);
        return view('member.profile.m_shop')
            ->with('getDataKelurahan', $getDataKelurahan)
            ->with('getDataKecamatan', $getDataKecamatan)
            ->with('getDataKota', $getDataKota)
            ->with('cekRequest', $cekRequestStockist)
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postSearchStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $getDataKelurahan = null;
        $getDataKecamatan = null;
        $getDataKota = null;
        $getData = $modelMember->getSearchUserStockist($request->user_name);
        $cekRequestStockist = $modelMember->getCekRequestSotckist($dataUser->id);
        return view('member.profile.m_shop')
            ->with('getData', $getData)
            ->with('getDataKelurahan', $getDataKelurahan)
            ->with('getDataKecamatan', $getDataKecamatan)
            ->with('getDataKota', $getDataKota)
            ->with('cekRequest', $cekRequestStockist)
            ->with('dataUser', $dataUser);
    }

    public function getMemberShoping($stokist_id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        //cek stockistnya
        $modelSales = new Sales;
        $modelMember = new Member;
        $getDataStockist = $dataUser;
        if ($dataUser->id != $stokist_id) {
            $getDataStockist = $modelMember->getUsers('id', $stokist_id);
            if ($getDataStockist->is_stockist == null) {
                return redirect()->route('mainDashboard');
            }
        }
        $data = $modelSales->getMemberPurchaseShoping($stokist_id);
        $getData = array();
        if ($data != null) {
            foreach ($data as $row) {
                $jml_keluar = $modelSales->getSumStock($stokist_id, $row->id);
                $total_sisa = $row->total_qty - $jml_keluar;
                if ($total_sisa < 0) {
                    $total_sisa = 0;
                }
                $hapus = 0;
                if ($total_sisa == 0) {
                    if ($row->deleted_at != null) {
                        $hapus = 1;
                    }
                }
                $getData[] = (object) array(
                    'total_qty' => $row->total_qty,
                    'name' => $row->name,
                    'code' => $row->code,
                    'ukuran' => $row->ukuran,
                    'image' => $row->image,
                    'member_price' => $row->member_price,
                    'stockist_price' => $row->stockist_price,
                    'id' => $row->id,
                    'jml_keluar' => $jml_keluar,
                    'total_sisa' => $total_sisa,
                    'hapus' => $hapus
                );
            }
        }
        return view('member.sales.m_shoping')
            ->with('getData', $getData)
            ->with('id', $stokist_id)
            ->with('dataUser', $dataUser);
    }

    public function getDetailPurchase($stokist_id, $id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 1) {
            return redirect()->route('m_MemberStockistShoping');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $getData = $modelSales->getDetailPurchase($id);
        return view('member.sales.m_purchase_view')
            ->with('getData', $getData)
            ->with('stokist_id', $stokist_id)
            ->with('dataUser', $dataUser);
    }

    public function postMemberShoping(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $arrayLog = json_decode($request->cart_list, true);
        //        dd($arrayLog);
        $user_id = $dataUser->id;
        $stockist_id = $request->stockist_id;
        $is_stockist = 0;
        $invoice = $modelSales->getCodeMasterSales($user_id);
        $sale_date = date('Y-m-d');
        $total_price = 0;
        //cek takutnya kelebihan qty
        foreach ($arrayLog as $rowCekQuantity) {
            if ($rowCekQuantity['product_quantity'] > $rowCekQuantity['max_qty']) {
                return redirect()->route('m_MemberShoping', [$stockist_id])
                    ->with('message', 'total keranjang ' . $rowCekQuantity['nama_produk'] . ' melebihi dari stok yang tersedia')
                    ->with('messageclass', 'danger');
            }
            $cekQuota = $modelSales->getStockByPurchaseIdStockist($stockist_id, $rowCekQuantity['product_id']);
            $jml_keluar = $modelSales->getSumStock($stockist_id, $rowCekQuantity['product_id']);
            $total_sisa = $cekQuota->total_qty - $jml_keluar;
            if ($rowCekQuantity['product_quantity'] > $total_sisa) {
                return redirect()->route('m_MemberShoping', [$stockist_id])
                    ->with('message', 'total keranjang ' . $rowCekQuantity['nama_produk'] . ' melebihi dari stok yang tersedia')
                    ->with('messageclass', 'danger');
            }
        }
        foreach ($arrayLog as $rowTotPrice) {
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
        foreach ($arrayLog as $row) {
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
            $insertSales = $modelSales->getInsertSales($dataInsert);
            $dataInsertStock = array(
                'purchase_id' => $row['product_id'],
                'user_id' => $user_id,
                'type' => 2,
                'amount' => $row['product_quantity'],
                'sales_id' => $insertSales->lastID,
                'stockist_id' => $stockist_id,
            );
            $modelSales->getInsertStock($dataInsertStock);
        }

        return redirect()->route('m_MemberPembayaran', [$insertMasterSales->lastID])
            ->with('message', 'Berhasil member belanja')
            ->with('messageclass', 'success');
    }

    public function getMemberStockistReport()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReportSalesStockist($dataUser->id);
        return view('member.sales.report_stockist')
            ->with('headerTitle', 'Report Belanja')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMemberDetailStockistReport($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id, $dataUser->id);
        if ($getDataSales == null) {
            return redirect()->route('mainDashboard');
        }
        $getDataItem = $modelSales->getMemberPembayaranSales($id);
        return view('member.sales.m_stockist_transfer')
            ->with('headerTitle', 'Stockist Transfer')
            ->with('getDataSales', $getDataSales)
            ->with('getDataItem', $getDataItem)
            ->with('dataUser', $dataUser);
    }

    public function getEditAddress()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMember = new Member;
        $getProvince = $modelMember->getProvinsi();
        return view('member.profile.edit-address')
            ->with('headerTitle', 'Alamat')
            ->with('provinsi', $getProvince)
            ->with('dataUser', $dataUser);
    }

    public function postEditAddress(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $dataUpdate = array(
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'kode_daerah' => $request->kode_daerah,
        );
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdate);
        return redirect()->route('m_myProfile')
            ->with('message', 'Alamat profile berhasil ubah')
            ->with('messageclass', 'success');
    }

    public function getHistoryShoping(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $getMonth = $modelSales->getThisMonth();
        if ($request->month != null && $request->year != null) {
            $start_day = date('Y-m-01', strtotime($request->year . '-' . $request->month));
            $end_day = date('Y-m-t', strtotime($request->year . '-' . $request->month));
            $text_month = date('F Y', strtotime($request->year . '-' . $request->month));
            $getMonth = (object) array(
                'startDay' => $start_day,
                'endDay' => $end_day,
                'textMonth' => $text_month
            );
        }
        $getData = $modelSales->getMemberMasterSalesHistory($dataUser->id, $getMonth);
        $sum = 0;
        if ($getData != null) {
            foreach ($getData as $row) {
                if ($row->status == 2) {
                    $sum += $row->sale_price;
                }
            }
        }
        return view('member.sales.history_sales')
            ->with('headerTitle', 'History Belanja')
            ->with('getData', $getData)
            ->with('sum', $sum)
            ->with('getDate', $getMonth)
            ->with('dataUser', $dataUser);
    }

    public function getMemberPembayaran($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $modelBank = new Bank;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($id);
        //klo kosong
        $getDataSales = $modelSales->getMemberPembayaranSales($getDataMaster->id);
        $getStockist = $dataUser;
        if ($dataUser->id != $getDataMaster->stockist_id) {
            $getStockist = $modelMember->getUsers('id', $getDataMaster->stockist_id);
            if ($getStockist->is_stockist == null) {
                return redirect()->route('mainDashboard');
            }
        }
        $getStockistBank = $modelBank->getBankMemberActive($getStockist);
        return view('member.sales.m_pembayaran')
            ->with('headerTitle', 'Pembayaran')
            ->with('getDataSales', $getDataSales)
            ->with('getDataMaster', $getDataMaster)
            ->with('getStockist', $getStockist)
            ->with('getStockistBank', $getStockistBank)
            ->with('dataUser', $dataUser);
    }

    public function postMemberPembayaran(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $tron = null;
        $tron_transfer = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($request->master_sale_id);
        if ($request->buy_metode == 1) {
            $buy_metode = 1;
        }
        if ($request->buy_metode == 2) {
            $buy_metode = 2;
            $bank_name = $request->bank_name;
            $account_no = $request->account_no;
            $account_name = $request->account_name;
        }
        if ($request->buy_metode == 3) {
            $buy_metode = 3;
            $tron = $request->tron;
            if ($request->tron_transfer == null) {
                return redirect()->route('m_MemberPembayaran', [$request->master_sale_id])
                    ->with('message', 'Hash transaksi transfer dari Blockchain TRON belum diisi')
                    ->with('messageclass', 'danger');
            }
            $tron_transfer = $request->tron_transfer;
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
            ->with('message', 'Konfirmasi pembayaran berhasil.')
            ->with('messageclass', 'success');
    }


    public function getStockistInputPurchase()
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $getData = null;
        if ($dataUser->kode_daerah != null) {
            $dataDaerah = explode('.', $dataUser->kode_daerah);
            $getData = $modelSales->getAllPurchaseByRegion($dataDaerah[0], $dataDaerah[1]);
        }
        return view('member.sales.stockist_input_stock')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postStockistInputPurchase(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $arrayLog = json_decode($request->cart_list, true);
        $total_price = 0;
        foreach ($arrayLog as $rowTotPrice) {
            $total_price += $rowTotPrice['product_quantity'] * $rowTotPrice['product_price'];
        }
        $dataInsertMasterStock = array(
            'stockist_id' => $dataUser->id,
            'price' => $total_price
        );
        $masterStock = $modelSales->getInsertItemPurchaseMaster($dataInsertMasterStock);
        foreach ($arrayLog as $row) {
            $dataInsertItem = array(
                'purchase_id' => $row['product_id'],
                'master_item_id' => $masterStock->lastID,
                'stockist_id' => $dataUser->id,
                'qty' => $row['product_quantity'],
                'sisa' => $row['product_quantity'],
                'price' => $row['product_price']
            );
            $modelSales->getInsertItemPurchase($dataInsertItem);
            $dataInsertStock = array(
                'purchase_id' => $row['product_id'],
                'user_id' => $dataUser->id,
                'type' => 1,
                'amount' => $row['product_quantity'],
            );
            $modelSales->getInsertStock($dataInsertStock);
        }
        return redirect()->route('m_StockistDetailPruchase', [$masterStock->lastID])
            ->with('message', 'Silakan pilih Metode Pembayaran anda, lalu Konfirmasi')
            ->with('messageclass', 'success');
    }

    public function getStockistListPurchase()
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberMasterPurchaseStockist($dataUser->id);
        $dataAll = array();
        if ($getData != null) {
            foreach ($getData as $row) {
                $detailAll = $modelSales->getMemberItemPurchaseStockist($row->id, $dataUser->id);
                $dataAll[] = (object) array(
                    'status' => $row->status,
                    'created_at' => $row->created_at,
                    'price' => $row->price,
                    'id' => $row->id,
                    'detail_all' => $detailAll
                );
            }
        }
        return view('member.sales.stockist_purchase')
            ->with('getData', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function getStockistDetailRequestStock($id)
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $getDataMaster = $modelSales->getMemberMasterPurchaseStockistByID($id, $dataUser->id);
        $getDataItem = $modelSales->getMemberItemPurchaseStockist($getDataMaster->id, $dataUser->id);
        return view('member.sales.stockist_detail_purchase')
            ->with('getDataMaster', $getDataMaster)
            ->with('getDataItem', $getDataItem)
            ->with('dataUser', $dataUser);
    }

    public function postAddRequestStock(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $tron = null;
        $tron_transfer = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $buy_metode = 0;
        if ($request->metode == 2) {
            $buy_metode = 2;
            $bank_name = $request->bank_name;
            $account_no = $request->account_no;
            $account_name = $request->account_name;
        }
        if ($request->metode == 3) {
            $buy_metode = 3;
            $tron = $request->tron;
            if ($request->transfer == null) {
                return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                    ->with('message', 'Hash transaksi transfer dari Blockchain TRON belum diisi')
                    ->with('messageclass', 'danger');
            }
            $tron_transfer = $request->transfer;
        }
        $dataUpdate = array(
            'status' => 1,
            'buy_metode' => $buy_metode,
            'tron' => $tron,
            'tron_transfer' => $tron_transfer,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
            'metode_at' => date('Y-m-d H:i:s')
        );
        $modelSales->getUpdateItemPurchaseMaster('id', $request->id_master, $dataUpdate);
        return redirect()->route('m_StockistListPruchase')
            ->with('message', 'Konfirmasi request Input Stock berhasil')
            ->with('messageclass', 'success');
    }

    public function postAddRequestStockTron(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }

        $modelSales = new Sales;
        $bank_name = 'TronWeb';
        $account_no = null;
        $account_name = null;
        $buy_metode = 0;
        $hash = $request->transfer;
        $sender = $request->sender;
        $amount = $request->royalti;
        $timestamp = $modelSales->getItemPurchaseMasterTimestamp($request->id_master);

        $client = new Client();
        sleep(3);
        $response = $client->request('GET', 'https://apilist.tronscan.org/api/transaction-info', [
            'query' => ['hash' => $hash]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                ->with('message', 'Ada Gangguan Koneksi API, Lapor ke Admin!')
                ->with('messageclass', 'danger');
        }

        if (empty(json_decode($response->getBody(), true))) {
            return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                ->with('message', 'Hash Transaksi Bermasalah!')
                ->with('messageclass', 'danger');
        };

        $jsonres = json_decode($response->getBody());
        $hashTime = $jsonres->timestamp / 1000;
        $txdata = $jsonres->contractData;
        if ($hashTime > $timestamp) {
            if ($txdata->amount == $amount * 100) {
                if ($txdata->asset_name == '1002652') {
                    if ($txdata->to_address == 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ') {
                        if ($txdata->owner_address == $sender) {
                            $buy_metode = 4;
                            $tron = $request->tron;
                            $dataUpdate = array(
                                'status' => 2,
                                'buy_metode' => $buy_metode,
                                'tron' => $tron,
                                'tron_transfer' => $hash,
                                'bank_name' => $bank_name,
                                'account_no' => $account_no,
                                'account_name' => $account_name,
                                'metode_at' => date('Y-m-d H:i:s')
                            );
                            $modelSales->getUpdateItemPurchaseMaster('id', $request->id_master, $dataUpdate);
                            return redirect()->route('m_StockistListPruchase')
                                ->with('message', 'Konfirmasi request Input Stock berhasil')
                                ->with('messageclass', 'success');
                        } else {
                            return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                                ->with('message', 'Bukan Pengirim Yang Sebenarnya!')
                                ->with('messageclass', 'danger');
                        }
                    } else {
                        return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                            ->with('message', 'Alamat Tujuan Transfer Salah!')
                            ->with('messageclass', 'danger');
                    }
                } else {
                    return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                        ->with('message', 'Bukan Token eIDR yang benar!')
                        ->with('messageclass', 'danger');
                }
            } else {
                return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                    ->with('message', 'Nominal Transfer Salah!')
                    ->with('messageclass', 'danger');
            }
        } else {
            return redirect()->route('m_StockistDetailPruchase', [$request->id_master])
                ->with('message', 'Hash sudah terpakai!')
                ->with('messageclass', 'danger');
        }
    }

    public function postRejectRequestStock(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $id_master = $request->id_master;
        $date = date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 10,
            'deleted_at' => $date,
        );
        $modelSales->getUpdateItemPurchaseMaster('id', $id_master, $dataUpdate);
        $dataUpdateItem = array(
            'deleted_at' => $date,
        );
        $modelSales->getUpdateItemPurchase('master_item_id', $id_master, $dataUpdateItem);
        return redirect()->route('m_StockistListPruchase')
            ->with('message', 'Request Input Stock dibatalkan')
            ->with('messageclass', 'success');
    }

    public function postAddTransferRoyalti(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }
        if ($request->metode == 2) {
            if ($request->royalti_tron_transfer == null) {
                return redirect()->route('m_MemberDetailStockistReport', array($request->master_id))
                    ->with('message', 'Anda belum memasukan Hash# transaksi transfer dari Blockchain TRON')
                    ->with('messageclass', 'danger');
            }
        }
        $modelSales = new Sales;
        $id_master = $request->master_id;
        $royalti_metode = $request->metode;
        $royalti_tron = null;
        $royalti_tron_transfer = null;
        $royalti_bank_name = null;
        $royalti_account_no = null;
        $royalti_account_name = null;
        if ($royalti_metode == 1) {
            $royalti_bank_name = 'BRI';
            $royalti_account_no = '033601001795562';
            $royalti_account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if ($royalti_metode == 2) {
            $royalti_tron = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
            $royalti_tron_transfer = $request->royalti_tron_transfer;
        }
        $dataUpdate = array(
            'status' => 4,
            'royalti_metode' => $royalti_metode,
            'royalti_tron' => $royalti_tron,
            'royalti_tron_transfer' => $royalti_tron_transfer,
            'royalti_bank_name' => $royalti_bank_name,
            'royalti_account_no' => $royalti_account_no,
            'royalti_account_name' => $royalti_account_name
        );
        $modelSales->getUpdateMasterSales('id', $id_master, $dataUpdate);
        return redirect()->route('m_MemberStockistReport')
            ->with('message', 'Berhasil konfirmasi transfer royalti')
            ->with('messageclass', 'success');
    }

    public function getStockistMyStockPurchaseSisa()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        if ($dataUser->is_stockist == 0) {
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $data = $modelSales->getMemberPurchaseShoping($dataUser->id);
        $getData = array();
        if ($data != null) {
            foreach ($data as $row) {
                $jml_keluar = $modelSales->getSumStock($dataUser->id, $row->id);
                $total_sisa = $row->total_qty - $jml_keluar;
                if ($total_sisa < 0) {
                    $total_sisa = 0;
                }
                $hapus = 0;
                if ($total_sisa == 0) {
                    if ($row->deleted_at != null) {
                        $hapus = 1;
                    }
                }
                $getData[] = (object) array(
                    'total_qty' => $row->total_qty,
                    'name' => $row->name,
                    'code' => $row->code,
                    'ukuran' => $row->ukuran,
                    'image' => $row->image,
                    'member_price' => $row->member_price,
                    'stockist_price' => $row->stockist_price,
                    'id' => $row->id,
                    'jml_keluar' => $jml_keluar,
                    'total_sisa' => $total_sisa,
                    'hapus' => $hapus
                );
            }
        }
        return view('member.sales.stockist_my_stock_sisa')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postAddConfirmPembelian(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $id_master = $request->master_id;
        $getSales = $modelSales->getMemberPembayaranSales($id_master);
        if ($getSales != null) {
            foreach ($getSales as $row) {
                $cekAda = $modelSales->getLastStockIDCekExist($row->purchase_id, $row->user_id, $row->stockist_id, $row->id);
                if ($cekAda == null) {
                    $dataInsertStock = array(
                        'purchase_id' => $row->purchase_id,
                        'user_id' => $row->user_id,
                        'type' => 2,
                        'amount' => $row->amount,
                        'sales_id' => $row->id,
                        'stockist_id' => $row->stockist_id,
                    );
                    $modelSales->getInsertStock($dataInsertStock);
                }
            }
        }

        $dataUpdate = array(
            'status' => 2
        );
        $modelSales->getUpdateMasterSales('id', $id_master, $dataUpdate);
        return redirect()->route('m_MemberStockistReport')
            ->with('message', 'Berhasil konfirmasi pembayaran member')
            ->with('messageclass', 'success');
    }

    public function postAddRejectPembelian(Request $request)
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
            return redirect()->route('m_SearchStockist');
        }
        $modelSales = new Sales;
        $id_master = $request->master_id;
        $dataUpdate = array(
            'status' => 10,
            'reason' => $request->reason
        );
        $modelSales->getUpdateMasterSales('id', $id_master, $dataUpdate);
        $getSales = $modelSales->getMemberPembayaranSales($id_master);
        if ($getSales != null) {
            foreach ($getSales as $row) {
                $cekAda = $modelSales->getLastStockIDCekExist($row->purchase_id, $row->user_id, $row->stockist_id, $row->id);
                if ($cekAda != null) {
                    $modelSales->getDeleteStock($row->purchase_id, $row->id, $row->stockist_id, $row->user_id);
                }
            }
        }
        return redirect()->route('m_MemberStockistReport')
            ->with('message', 'Berhasil reject pembayaran member')
            ->with('messageclass', 'success');
    }

    public function getExplorerStatistic()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMember = new Member;
        $modelWD = new Transferwd;
        $modelSales = new Sales;
        $modelBonus = new Bonus;

        //all time
        $total_aktifasi = $modelMember->getAllMember();
        $totalWD = $modelWD->getTotalDiTransferAll();
        $getSales = $modelSales->getSalesAllHistory();
        $getVSales = $modelSales->getVSalesAllHistory();
        $getPPOB = $modelSales->getPPOBAllHistory();
        $getAllShopLMB = $modelBonus->getAllClaimLMB();
        $getAllClaimLMB = $modelBonus->getAllClaimRewardLMB();
        $sum = 0;
        if ($getAllClaimLMB != null) {
            $sum = $getAllClaimLMB->tot_reward_1 + $getAllClaimLMB->tot_reward_2 + $getAllClaimLMB->tot_reward_3 + $getAllClaimLMB->tot_reward_4;
        }
        $lmb_claim = $sum + $getAllShopLMB->total_claim_shop;
        $dataAll = (object) array(
            'total_aktifasi' => $total_aktifasi,
            'total_wd' => $totalWD->total_wd,
            'fee_tuntas' => $totalWD->fee_tuntas,
            'total_sales' => $getSales->total_sales,
            'total_vsales' => $getVSales->total_sales,
            'total_ppob' => $getPPOB->total_sales,
            'lmb_claim' => $lmb_claim
        );

        //last month
        $last_month = (object) array(
            'start_day' => date("Y-m-d", strtotime("first day of previous month")),
            'end_day' => date("Y-m-d", strtotime("last day of previous month"))
        );
        $total_aktifasi_date = $modelMember->getAllMemberLastMonth($last_month);
        $totalWD_date = $modelWD->getTotalDiTransferAllLastMonth($last_month);
        $getSales_date = $modelSales->getSalesAllHistoryLastMonth($last_month);
        $getVSales_date = $modelSales->getVSalesAllHistoryLastMonth($last_month);
        $getPPOB_date = $modelSales->getPPOBAllHistoryLastMonth($last_month);
        $getAllShopLMB_date = $modelBonus->getAllClaimLMBLastMonth($last_month);
        $getAllClaimLMB_date = $modelBonus->getAllClaimRewardLMBLastMonth($last_month);
        $sum_date = 0;
        if ($getAllClaimLMB_date != null) {
            $sum_date = $getAllClaimLMB_date->tot_reward_1 + $getAllClaimLMB_date->tot_reward_2 + $getAllClaimLMB_date->tot_reward_3 + $getAllClaimLMB_date->tot_reward_4;
        }
        $lmb_claim_date = $sum_date + $getAllShopLMB_date->total_claim_shop;
        $dataAll_lastmonth = (object) array(
            'total_aktifasi' => $total_aktifasi_date,
            'total_wd' => $totalWD_date->total_wd,
            'fee_tuntas' => $totalWD_date->fee_tuntas,
            'total_sales' => $getSales_date->total_sales,
            'total_vsales' => $getVSales_date->total_sales,
            'total_ppob' => $getPPOB_date->total_sales,
            'lmb_claim' => $lmb_claim_date
        );
        return view('member.explorer.statistic')
            ->with('dataAll', $dataAll)
            ->with('dataAll_month', $dataAll_lastmonth)
            ->with('dataUser', $dataUser);
    }

    public function getExplorerUser(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMember = new Member;
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $modelPin = new Pin;
        $modelPengiriman = new Pengiriman;
        $dataExplore = null;
        if ($request->get_id != null) {
            $user = $modelMember->getExplorerByID($request->get_id);
            $sponsor = $modelMember->getExplorerByID($user->sponsor_id);
            $getMyPeringkat = $modelBonusSetting->getPeringkatByType($user->member_type);
            $namePeringkat = 'Member Biasa';
            $image = '';
            if ($getMyPeringkat != null) {
                $namePeringkat = $getMyPeringkat->name;
                $image = $getMyPeringkat->image;
            }
            $getTotalPin = $modelPin->getTotalPinMember($user);
            $sum_pin_masuk = 0;
            $sum_pin_keluar = 0;
            if ($getTotalPin->sum_pin_masuk != null) {
                $sum_pin_masuk = $getTotalPin->sum_pin_masuk;
            }
            if ($getTotalPin->sum_pin_keluar != null) {
                $sum_pin_keluar = $getTotalPin->sum_pin_keluar;
            }
            $total = $sum_pin_masuk - $sum_pin_keluar;

            $totalWD = $modelWD->getTotalDiTransfer($user);
            $getSales = $modelSales->getSalesAllHistoryByID($user);
            $getAllShopLMB = $modelBonus->getAllClaimLMBByIDUserCode($user);
            $getAllClaimLMB = $modelBonus->getAllClaimRewardLMBByIDUserCode($user);
            $totalBonus = $modelBonus->getTotalBonus($user);
            $sum = 0;
            if ($getAllClaimLMB != null) {
                $sum = $getAllClaimLMB->tot_reward_1 + $getAllClaimLMB->tot_reward_2 + $getAllClaimLMB->tot_reward_3 + $getAllClaimLMB->tot_reward_4;
            }
            $lmb_claim = $sum + $getAllShopLMB->total_claim_shop;

            $dataExplore = (object) array(
                'user' => $user,
                'sponsor' => $sponsor,
                'peringkat' => $namePeringkat,
                'image_peringkat' => $image,
                'pin_tersedia' => $total,
                'pin_terpakai' => $sum_pin_keluar,
                'total_wd' => $totalWD->total_wd,
                'fee_tuntas' => $totalWD->fee_tuntas,
                'total_bonus' => floor($totalBonus->total_bonus),
                'total_sales' => $getSales->total_sales,
                'lmb_claim' => $lmb_claim
            );
        }
        return view('member.explorer.user')
            ->with('dataExplore', $dataExplore)
            ->with('dataUser', $dataUser);
    }

    public function getEditPassword()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        return view('member.profile.my-password')
            ->with('headerTitle', 'Password')
            ->with('dataUser', $dataUser);
    }

    public function postEditPassword(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $dataUpdatePass = array(
            'password' => bcrypt($request->password),
        );
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdatePass);
        return redirect()->route('m_editPassword')
            ->with('message', 'Berhasil edit passowrd')
            ->with('messageclass', 'success');
    }

    public function getRequestMemberVendor()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        if ($dataUser->is_stockist == 1 || $dataUser->is_vendor == 1) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Anda sudah menjadi member salah satu stockist atau vendor')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $cekRequestStockist = $modelMember->getCekRequestVendor($dataUser->id);
        if ($cekRequestStockist != null) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Anda sudah pernah mengajukan menjadi vendor')
                ->with('messageclass', 'danger');
        }
        return view('member.profile.add-vendor')
            ->with('headerTitle', 'Aplikasi Pengajuan Vendor')
            ->with('dataUser', $dataUser);
    }

    public function postRequestMemberVendor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $dataInsert = array(
            'user_id' => $dataUser->id
        );
        $modelMember->getInsertVendor($dataInsert);
        return redirect()->route('m_SearchVendor')
            ->with('message', 'Aplikasi Pengajuan Vendor berhasil dibuat')
            ->with('messageclass', 'success');
    }

    public function getSearchVendor()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelMember = new Member;
        $getDataKelurahan = null;
        $getDataKecamatan = null;
        $getDataKota = null;
        $getData = null;
        if ($dataUser->kode_daerah != null) {
            $dataDaerah = explode('.', $dataUser->kode_daerah);
            $kelurahan = $dataUser->kelurahan;
            $kecamatan = $dataUser->kecamatan;
            $kota = $dataUser->kota;
            $getDataKelurahan = $modelMember->getSearchVendorUserByKelurahan($kelurahan, $kecamatan);
            $getDataKecamatan = $modelMember->getSearchVendorUserByKecamatan($kecamatan, $kelurahan);
            $getDataKota = $modelMember->getSearchUserVendorByKota($kota, $kecamatan, $kelurahan);
        }
        $cekRequestStockist = $modelMember->getCekRequestVendorExist($dataUser->id);
        return view('member.profile.m_vshop')
            ->with('getDataKelurahan', $getDataKelurahan)
            ->with('getDataKecamatan', $getDataKecamatan)
            ->with('getDataKota', $getDataKota)
            ->with('cekRequest', $cekRequestStockist)
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postSearchVendor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $getDataKelurahan = null;
        $getDataKecamatan = null;
        $getDataKota = null;
        $getData = $modelMember->getSearchUserVendor($request->user_name);
        $cekRequestStockist = $modelMember->getCekRequestVendorExist($dataUser->id);
        return view('member.profile.m_vshop')
            ->with('getData', $getData)
            ->with('getDataKelurahan', $getDataKelurahan)
            ->with('getDataKecamatan', $getDataKecamatan)
            ->with('getDataKota', $getDataKota)
            ->with('cekRequest', $cekRequestStockist)
            ->with('dataUser', $dataUser);
    }

    public function getMemberShopingVendor($vendor_id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        //cek stockistnya
        $modelSales = new Sales;
        $modelMember = new Member;
        $getDataVendor = $dataUser;
        if ($dataUser->id != $vendor_id) {
            $getDataVendor = $modelMember->getUsers('id', $vendor_id);
            if ($getDataVendor->is_vendor == null) {
                return redirect()->route('mainDashboard');
            }
        }
        $data = $modelSales->getMemberPurchaseVendorShoping($vendor_id);
        $getData = array();
        if ($data != null) {
            foreach ($data as $row) {
                $jml_keluar = $modelSales->getSumStockVendor($vendor_id, $row->id);
                $total_sisa = $row->total_qty - $jml_keluar;
                if ($total_sisa < 0) {
                    $total_sisa = 0;
                }
                $hapus = 0;
                if ($total_sisa == 0) {
                    if ($row->deleted_at != null) {
                        $hapus = 1;
                    }
                }
                $getData[] = (object) array(
                    'total_qty' => $row->total_qty,
                    'name' => $row->name,
                    'code' => $row->code,
                    'ukuran' => $row->ukuran,
                    'image' => $row->image,
                    'member_price' => $row->member_price,
                    'vendor_price' => $row->vendor_price,
                    'id' => $row->id,
                    'jml_keluar' => $jml_keluar,
                    'total_sisa' => $total_sisa,
                    'hapus' => $hapus
                );
            }
        }
        return view('member.sales.m_vshoping')
            ->with('getData', $getData)
            ->with('id', $vendor_id)
            ->with('dataUser', $dataUser);
    }

    public function getVendorInputPurchase()
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $getData = null;
        if ($dataUser->kode_daerah != null) {
            $dataDaerah = explode('.', $dataUser->kode_daerah);
            $getData = $modelSales->getAllPurchaseVendorByRegion($dataDaerah[0], $dataDaerah[1]);
        }
        return view('member.sales.vendor_input_stock')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postVendorInputPurchase(Request $request)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $arrayLog = json_decode($request->cart_list, true);
        $total_price = 0;
        foreach ($arrayLog as $rowTotPrice) {
            $total_price += $rowTotPrice['product_quantity'] * $rowTotPrice['product_price'];
        }
        $dataInsertMasterStock = array(
            'vendor_id' => $dataUser->id,
            'price' => $total_price
        );
        $masterStock = $modelSales->getInsertVendorItemPurchaseMaster($dataInsertMasterStock);
        foreach ($arrayLog as $row) {
            $dataInsertItem = array(
                'purchase_id' => $row['product_id'],
                'vmaster_item_id' => $masterStock->lastID,
                'vendor_id' => $dataUser->id,
                'qty' => $row['product_quantity'],
                'sisa' => $row['product_quantity'],
                'price' => $row['product_price']
            );
            $modelSales->getInsertVItemPurchase($dataInsertItem);
            $dataInsertStock = array(
                'purchase_id' => $row['product_id'],
                'user_id' => $dataUser->id,
                'type' => 1,
                'amount' => $row['product_quantity'],
            );
            $modelSales->getInsertVStock($dataInsertStock);
        }
        return redirect()->route('m_VendorDetailPruchase', [$masterStock->lastID])
            ->with('message', 'Silakan pilih Metode Pembayaran anda, lalu Konfirmasi')
            ->with('messageclass', 'success');
    }

    public function getVendorDetailRequestStock($id)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $getDataMaster = $modelSales->getMemberMasterPurchaseVendorByID($id, $dataUser->id);
        $getDataItem = $modelSales->getMemberItemPurchaseVendor($getDataMaster->id, $dataUser->id);
        return view('member.sales.vendor_detail_purchase')
            ->with('getDataMaster', $getDataMaster)
            ->with('getDataItem', $getDataItem)
            ->with('dataUser', $dataUser);
    }

    public function getVendorListPurchase()
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberMasterPurchaseVendor($dataUser->id);
        $dataAll = array();
        if ($getData != null) {
            foreach ($getData as $row) {
                $detailAll = $modelSales->getMemberItemPurchaseVendor($row->id, $dataUser->id);
                $dataAll[] = (object) array(
                    'status' => $row->status,
                    'created_at' => $row->created_at,
                    'price' => $row->price,
                    'id' => $row->id,
                    'detail_all' => $detailAll
                );
            }
        }
        return view('member.sales.vendor_purchase')
            ->with('getData', $dataAll)
            ->with('dataUser', $dataUser);
    }

    public function postAddRequestVStock(Request $request)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $tron = null;
        $tron_transfer = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $buy_metode = 0;
        if ($request->metode == 2) {
            $buy_metode = 2;
            $bank_name = $request->bank_name;
            $account_no = $request->account_no;
            $account_name = $request->account_name;
        }
        if ($request->metode == 3) {
            $buy_metode = 3;
            $tron = $request->tron;
            if ($request->transfer == null) {
                return redirect()->route('m_VendorDetailPruchase', [$request->id_master])
                    ->with('message', 'Hash transaksi transfer dari Blockchain TRON belum diisi')
                    ->with('messageclass', 'danger');
            }
            $tron_transfer = $request->transfer;
        }
        $dataUpdate = array(
            'status' => 1,
            'buy_metode' => $buy_metode,
            'tron' => $tron,
            'tron_transfer' => $tron_transfer,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
            'metode_at' => date('Y-m-d H:i:s')
        );
        $modelSales->getUpdateVendorItemPurchaseMaster('id', $request->id_master, $dataUpdate);
        return redirect()->route('m_VendorListPruchase')
            ->with('message', 'Konfirmasi request Vendor Input Stock berhasil')
            ->with('messageclass', 'success');
    }

    public function postAddRequestVStockTron(Request $request)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $bank_name = 'TronWeb';
        $account_no = null;
        $account_name = null;
        $buy_metode = 0;
        $hash = $request->transfer;
        $sender = $request->sender;
        $amount = $request->royalti;
        $timestamp = $modelSales->getVendorItemPurchaseMasterTimestamp($request->id_master);

        $client = new Client();
        sleep(3);
        $response = $client->request('GET', 'https://apilist.tronscan.org/api/transaction-info', [
            'query' => ['hash' => $hash]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect()->route('m_VendorListPruchase', [$request->id_master])
                ->with('message', 'Ada Gangguan Koneksi API, Lapor ke Admin!')
                ->with('messageclass', 'danger');
        }

        if (empty(json_decode($response->getBody(), true))) {
            return redirect()->route('m_VendorListPruchase', [$request->id_master])
                ->with('message', 'Hash Transaksi Bermasalah!')
                ->with('messageclass', 'danger');
        };

        $jsonres = json_decode($response->getBody());
        $hashTime = $jsonres->timestamp / 1000;
        $txdata = $jsonres->contractData;
        if ($hashTime > $timestamp) {
            if ($txdata->amount == $amount * 100) {
                if ($txdata->asset_name == '1002652') {
                    if ($txdata->to_address == 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ') {
                        if ($txdata->owner_address == $sender) {
                            $buy_metode = 4;
                            $tron = $request->tron;
                            $dataUpdate = array(
                                'status' => 2,
                                'buy_metode' => $buy_metode,
                                'tron' => $tron,
                                'tron_transfer' => $hash,
                                'bank_name' => $bank_name,
                                'account_no' => $account_no,
                                'account_name' => $account_name,
                                'metode_at' => date('Y-m-d H:i:s')
                            );
                            $modelSales->getUpdateVendorItemPurchaseMaster('id', $request->id_master, $dataUpdate);
                            return redirect()->route('m_VendorListPruchase')
                                ->with('message', 'Proses Input Stock Vendor berhasil')
                                ->with('messageclass', 'success');
                        } else {
                            return redirect()->route('m_VendorListPruchase', [$request->id_master])
                                ->with('message', 'Bukan Pengirim Yang Sebenarnya!')
                                ->with('messageclass', 'danger');
                        }
                    } else {
                        return redirect()->route('m_VendorListPruchase', [$request->id_master])
                            ->with('message', 'Alamat Tujuan Transfer Salah!')
                            ->with('messageclass', 'danger');
                    }
                } else {
                    return redirect()->route('m_VendorListPruchase', [$request->id_master])
                        ->with('message', 'Bukan Token eIDR yang benar!')
                        ->with('messageclass', 'danger');
                }
            } else {
                return redirect()->route('m_VendorListPruchase', [$request->id_master])
                    ->with('message', 'Nominal Transfer Salah!')
                    ->with('messageclass', 'danger');
            }
        } else {
            return redirect()->route('m_VendorListPruchase', [$request->id_master])
                ->with('message', 'Hash sudah terpakai!')
                ->with('messageclass', 'danger');
        }
    }

    public function postRejectRequestVStock(Request $request)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $id_master = $request->id_master;
        $date = date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 10,
            'deleted_at' => $date,
        );
        $modelSales->getUpdateVendorItemPurchaseMaster('id', $id_master, $dataUpdate);
        $dataUpdateItem = array(
            'deleted_at' => $date,
        );
        $modelSales->getUpdateVItemPurchase('vmaster_item_id', $id_master, $dataUpdateItem);
        return redirect()->route('m_StockistListPruchase')
            ->with('message', 'Request Input Stock dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getVendorMyStockPurchaseSisa()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_profile == 0) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Data profil anda belum lengkap')
                ->with('messageclass', 'danger');
        }
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $data = $modelSales->getMemberPurchaseVendorShoping($dataUser->id);
        $getData = array();
        if ($data != null) {
            foreach ($data as $row) {
                $jml_keluar = $modelSales->getSumStockVendor($dataUser->id, $row->id);
                $total_sisa = $row->total_qty - $jml_keluar;
                if ($total_sisa < 0) {
                    $total_sisa = 0;
                }
                $hapus = 0;
                if ($total_sisa == 0) {
                    if ($row->deleted_at != null) {
                        $hapus = 1;
                    }
                }
                $getData[] = (object) array(
                    'total_qty' => $row->total_qty,
                    'name' => $row->name,
                    'code' => $row->code,
                    'ukuran' => $row->ukuran,
                    'image' => $row->image,
                    'member_price' => $row->member_price,
                    'vendor_price' => $row->vendor_price,
                    'id' => $row->id,
                    'jml_keluar' => $jml_keluar,
                    'total_sisa' => $total_sisa,
                    'hapus' => $hapus
                );
            }
        }
        return view('member.sales.vendor_my_stock_sisa')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMemberVendorReport()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReportSalesVendor($dataUser->id);
        return view('member.sales.report_vendor')
            ->with('headerTitle', 'Report Belanja')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postMemberShopingVendor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $arrayLog = json_decode($request->cart_list, true);
        $user_id = $dataUser->id;
        $vendor_id = $request->vendor_id;
        $is_vendor = 0;
        $invoice = $modelSales->getCodeMasterSales($user_id);
        $sale_date = date('Y-m-d');
        $total_price = 0;
        //cek takutnya kelebihan qty
        foreach ($arrayLog as $rowCekQuantity) {
            if ($rowCekQuantity['product_quantity'] > $rowCekQuantity['max_qty']) {
                return redirect()->route('m_MemberShopingVendor', [$vendor_id])
                    ->with('message', 'total keranjang ' . $rowCekQuantity['nama_produk'] . ' melebihi dari stok yang tersedia')
                    ->with('messageclass', 'danger');
            }
            $cekQuota = $modelSales->getStockByPurchaseIdVendor($vendor_id, $rowCekQuantity['product_id']);
            $jml_keluar = $modelSales->getSumStockVendor($vendor_id, $rowCekQuantity['product_id']);
            $total_sisa = $cekQuota->total_qty - $jml_keluar;
            if ($rowCekQuantity['product_quantity'] > $total_sisa) {
                return redirect()->route('m_MemberShopingVendor', [$vendor_id])
                    ->with('message', 'total keranjang ' . $rowCekQuantity['nama_produk'] . ' melebihi dari stok yang tersedia')
                    ->with('messageclass', 'danger');
            }
        }
        foreach ($arrayLog as $rowTotPrice) {
            $total_price += $rowTotPrice['product_quantity'] * $rowTotPrice['product_price'];
        }
        $dataInsertMasterSales = array(
            'user_id' => $user_id,
            'vendor_id' => $vendor_id,
            'is_vendor' => $is_vendor,
            'invoice' => $invoice,
            'total_price' => $total_price,
            'sale_date' => $sale_date,
        );
        $insertMasterSales = $modelSales->getInsertVMasterSales($dataInsertMasterSales);
        foreach ($arrayLog as $row) {
            $dataInsert = array(
                'user_id' => $user_id,
                'vendor_id' => $vendor_id,
                'is_vendor' => $is_vendor,
                'purchase_id' => $row['product_id'],
                'invoice' => $invoice,
                'amount' => $row['product_quantity'],
                'sale_price' => $row['product_quantity'] * $row['product_price'],
                'sale_date' => $sale_date,
                'vmaster_sales_id' => $insertMasterSales->lastID
            );
            $insertSales = $modelSales->getInsertVSales($dataInsert);
            $dataInsertStock = array(
                'purchase_id' => $row['product_id'],
                'user_id' => $user_id,
                'type' => 2,
                'amount' => $row['product_quantity'],
                'vsales_id' => $insertSales->lastID,
                'vendor_id' => $vendor_id,
            );
            $modelSales->getInsertVStock($dataInsertStock);
        }

        return redirect()->route('m_MemberVPembayaran', [$insertMasterSales->lastID])
            ->with('message', 'Berhasil member belanja')
            ->with('messageclass', 'success');
    }

    public function getMemberVPembayaran($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $modelBank = new Bank;
        $getDataMaster = $modelSales->getMemberPembayaranVMasterSales($id);
        //klo kosong
        $getDataSales = $modelSales->getMemberPembayaranVSales($getDataMaster->id);
        $getVendor = $dataUser;
        if ($dataUser->id != $getDataMaster->vendor_id) {
            $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
            if ($getVendor->is_vendor == null) {
                return redirect()->route('mainDashboard');
            }
        }
        $getStockistBank = $modelBank->getBankMemberActive($getVendor);
        return view('member.sales.m_vpembayaran')
            ->with('headerTitle', 'Pembayaran')
            ->with('getDataSales', $getDataSales)
            ->with('getDataMaster', $getDataMaster)
            ->with('getStockist', $getVendor)
            ->with('getStockistBank', $getStockistBank)
            ->with('dataUser', $dataUser);
    }

    public function postMemberVPembayaran(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $tron = null;
        $tron_transfer = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranVMasterSales($request->master_sale_id);
        if ($request->buy_metode == 1) {
            $buy_metode = 1;
        }
        if ($request->buy_metode == 2) {
            $buy_metode = 2;
            $bank_name = $request->bank_name;
            $account_no = $request->account_no;
            $account_name = $request->account_name;
        }
        if ($request->buy_metode == 3) {
            $buy_metode = 3;
            $tron = $request->tron;
            if ($request->tron_transfer == null) {
                return redirect()->route('m_MemberVPembayaran', [$request->master_sale_id])
                    ->with('message', 'Hash transaksi transfer dari Blockchain TRON belum diisi')
                    ->with('messageclass', 'danger');
            }
            $tron_transfer = $request->tron_transfer;
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
        $modelSales->getUpdateVMasterSales('id', $request->master_sale_id, $dataUpdate);
        return redirect()->route('m_historyVShoping')
            ->with('message', 'Konfirmasi pembayaran berhasil.')
            ->with('messageclass', 'success');
    }

    public function getHistoryVShoping(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $getMonth = $modelSales->getThisMonth();
        if ($request->month != null && $request->year != null) {
            $start_day = date('Y-m-01', strtotime($request->year . '-' . $request->month));
            $end_day = date('Y-m-t', strtotime($request->year . '-' . $request->month));
            $text_month = date('F Y', strtotime($request->year . '-' . $request->month));
            $getMonth = (object) array(
                'startDay' => $start_day,
                'endDay' => $end_day,
                'textMonth' => $text_month
            );
        }
        $getData = $modelSales->getMemberVMasterSalesHistory($dataUser->id, $getMonth);
        return view('member.sales.history_vsales')
            ->with('headerTitle', 'History Belanja Vendor')
            ->with('getData', $getData)
            ->with('getDate', $getMonth)
            ->with('dataUser', $dataUser);
    }

    public function getMemberDetailVendorReport($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $getDataSales = $modelSales->getMemberReportSalesVendorDetail($id, $dataUser->id);
        if ($getDataSales == null) {
            return redirect()->route('mainDashboard');
        }
        $getDataItem = $modelSales->getMemberPembayaranVSales($id);
        return view('member.sales.m_vendor_transfer')
            ->with('headerTitle', 'Vendor Transfer')
            ->with('getDataSales', $getDataSales)
            ->with('getDataItem', $getDataItem)
            ->with('dataUser', $dataUser);
    }

    public function postAddConfirmVPembelian(Request $request)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $id_master = $request->master_id;
        $getSales = $modelSales->getMemberPembayaranVSales($id_master);
        if ($getSales != null) {
            foreach ($getSales as $row) {
                $cekAda = $modelSales->getLastVStockIDCekExist($row->purchase_id, $row->user_id, $row->vendor_id, $row->id);
                if ($cekAda == null) {
                    $dataInsertStock = array(
                        'purchase_id' => $row->purchase_id,
                        'user_id' => $row->user_id,
                        'type' => 2,
                        'amount' => $row->amount,
                        'vsales_id' => $row->id,
                        'vendor_id' => $row->vendor_id,
                    );
                    $modelSales->getInsertVStock($dataInsertStock);
                }
            }
        }

        $dataUpdate = array(
            'status' => 2
        );
        $modelSales->getUpdateVMasterSales('id', $id_master, $dataUpdate);
        return redirect()->route('m_MemberVendorReport')
            ->with('message', 'Berhasil konfirmasi pembayaran member')
            ->with('messageclass', 'success');
    }

    public function postAddRejectVPembelian(Request $request)
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
            return redirect()->route('m_SearchVendor');
        }
        $modelSales = new Sales;
        $id_master = $request->master_id;
        $dataUpdate = array(
            'status' => 10,
            'reason' => $request->reason
        );
        $modelSales->getUpdateVMasterSales('id', $id_master, $dataUpdate);
        $getSales = $modelSales->getMemberPembayaranVSales($id_master);
        if ($getSales != null) {
            foreach ($getSales as $row) {
                $cekAda = $modelSales->getLastVStockIDCekExist($row->purchase_id, $row->user_id, $row->vendor_id, $row->id);
                if ($cekAda != null) {
                    $modelSales->getDeleteVStock($row->purchase_id, $row->id, $row->vendor_id, $row->user_id);
                }
            }
        }
        return redirect()->route('m_MemberVendorReport')
            ->with('message', 'Berhasil reject pembayaran member')
            ->with('messageclass', 'success');
    }

    public function getAddDeposit()
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        return view('member.digital.add-deposit')
            ->with('headerTitle', 'Isi Deposit')
            ->with('dataUser', $dataUser);
    }

    public function postAddDeposit(Request $request)
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $modelSettingTrans = new Transaction;
        $code = $modelSettingTrans->getCodeDepositTransaction();
        $rand = rand(101, 249);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'transaction_code' => 'DTR' . date('Ymd') . $dataUser->id . $code,
            'price' => $request->total_deposit,
            'unique_digit' => $rand,
        );
        $getIDTrans = $modelSettingTrans->getInsertDepositTransaction($dataInsert);
        return redirect()->route('m_addDepositTransaction', [$getIDTrans->lastID])
            ->with('message', 'Pengajuan Deposit berhasil, silakan lakukan proses transfer')
            ->with('messageclass', 'success');
    }

    public function getListDepositTransactions()
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $modelSettingTrans = new Transaction;
        $getAllTransaction = $modelSettingTrans->getDepositTransactionsMember($dataUser);
        return view('member.digital.list-deposit')
            ->with('getData', $getAllTransaction)
            ->with('dataUser', $dataUser);
    }

    public function getAddDepositTransaction($id)
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $modelTrans = new Transaction;
        $modelBank = new Bank;
        $getTrans = $modelTrans->getDetailDepositTransactionsMember($id, $dataUser);
        if ($getTrans == null) {
            return redirect()->route('mainDashboard');
        }
        $getPerusahaanTron = null;
        if ($getTrans->bank_perusahaan_id != null) {
            if ($getTrans->is_tron == 0) {
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($getTrans->bank_perusahaan_id);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($getTrans->bank_perusahaan_id);
            }
        } else {
            $getPerusahaanBank = $modelBank->getBankPerusahaan();
            $getPerusahaanTron = $modelBank->getTronPerusahaan();
        }
        return view('member.digital.detail-deposit-trans')
            ->with('headerTitle', 'Deposit Transaction')
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('tronPerusahaan', $getPerusahaanTron)
            ->with('getData', $getTrans)
            ->with('dataUser', $dataUser);
    }

    public function postAddDepositTransaction(Request $request)
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $tron_transfer = null;
        if ($request->is_tron == 1) {
            if ($request->tron_transfer == null) {
                return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                    ->with('message', 'Transaksi tron harus memasukkan Transaksi Hash')
                    ->with('messageclass', 'danger');
            }
            $tron_transfer = $request->tron_transfer;
        }

        $modelSettingTrans = new Transaction;
        $id_trans = $request->id_trans;
        $dataUpdate = array(
            'status' => 1,
            'bank_perusahaan_id' => $request->bank_perusahaan_id,
            'updated_at' => date('Y-m-d H:i:s'),
            'is_tron' => $request->is_tron,
            'tron_transfer' => $request->tron_transfer
        );
        $modelSettingTrans->getUpdateDepositTransaction('id', $id_trans, $dataUpdate);
        return redirect()->route('m_listDepositTransactions')
            ->with('message', 'Konfirmasi transfer berhasil')
            ->with('messageclass', 'success');
    }

    public function postAddDepositTransactionTron(Request $request)
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }

        $hash = $request->tron_transfer;
        $sender = $request->sender;
        $amount = $request->amount;
        $timestamp = $request->timestamp;

        $client = new Client();
        sleep(3);
        $response = $client->request('GET', 'https://apilist.tronscan.org/api/transaction-info', [
            'query' => ['hash' => $hash]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                ->with('message', 'Ada Gangguan Koneksi API, Lapor ke Admin!')
                ->with('messageclass', 'danger');
        }

        if (empty(json_decode($response->getBody(), true))) {
            return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                ->with('message', 'Hash Transaksi Bermasalah!')
                ->with('messageclass', 'danger');
        };

        $jsonres = json_decode($response->getBody());
        $txdata = $jsonres->contractData;
        if ($jsonres->timestamp > $timestamp) {
            if ($txdata->amount == $amount * 100) {
                if ($txdata->asset_name == '1002652') {
                    if ($txdata->to_address == 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge') {
                        if ($txdata->owner_address == $sender) {
                            $modelSettingTrans = new Transaction;
                            $id_trans = $request->id_trans;
                            $dataUpdate = array(
                                'status' => 2,
                                'bank_perusahaan_id' => $request->bank_perusahaan_id,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'is_tron' => $request->is_tron,
                                'tron_transfer' => $request->tron_transfer
                            );
                            $modelSettingTrans->getUpdateDepositTransaction('id', $id_trans, $dataUpdate);
                            return redirect()->route('m_listDepositTransactions')
                                ->with('message', 'Proses Isi Deposit Vendor berhasil')
                                ->with('messageclass', 'success');
                        } else {
                            return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                                ->with('message', 'Bukan Pengirim Yang Sebenarnya!')
                                ->with('messageclass', 'danger');
                        }
                    } else {
                        return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                            ->with('message', 'Alamat Tujuan Transfer Salah!')
                            ->with('messageclass', 'danger');
                    }
                } else {
                    return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                        ->with('message', 'Bukan Token eIDR yang benar!')
                        ->with('messageclass', 'danger');
                }
            } else {
                return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                    ->with('message', 'Nominal Transfer Salah!')
                    ->with('messageclass', 'danger');
            }
        } else {
            return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                ->with('message', 'Hash sudah terpakai!')
                ->with('messageclass', 'danger');
        }
    }

    public function postRejectDepositTransaction(Request $request)
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        if ($request->reason == null) {
            return redirect()->route('m_addDepositTransaction', [$request->id_trans])
                ->with('message', 'Alasan harus diisi')
                ->with('messageclass', 'danger');
        }
        $modelSettingTrans = new Transaction;
        $id_trans = $request->id_trans;
        $dataUpdate = array(
            'status' => 3,
            'deleted_at' => date('Y-m-d H:i:s'),
            'reason' => $request->reason
        );
        $modelSettingTrans->getUpdateDepositTransaction('id', $id_trans, $dataUpdate);
        return redirect()->route('m_listDepositTransactions')
            ->with('message', 'Transaksi isi Deposit dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getMyDepositHistory()
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $modelPin = new Pin;
        $getHistoryDeposit = $modelPin->getMyHistoryDeposit($dataUser);
        //        $getTotalDeposit = $modelPin->getTotalDepositMember($dataUser);
        return view('member.digital.deposit-history')
            ->with('getData', $getHistoryDeposit)
            ->with('dataUser', $dataUser);
    }

    public function getTarikDeposit()
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        $modelBank = new Bank;
        $getAllMyBank = $modelBank->getBankMember($dataUser);
        return view('member.digital.tarik-deposit')
            ->with('headerTitle', 'Tarik Deposit')
            ->with('getAllMyBank', $getAllMyBank)
            ->with('dataUser', $dataUser);
    }

    public function postTarikDeposit(Request $request)
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
        if ($dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchVendor');
        }
        //cek depositnya ada berapa jika kurang maka tidak bisa
        $modelSettingTrans = new Transaction;
        $code = $modelSettingTrans->getCodeDepositTransaction();
        $dataInsert = array(
            'type' => 2,
            'user_id' => $dataUser->id,
            'transaction_code' => 'TTR' . date('Ymd') . $dataUser->id . $code,
            'price' => $request->total_deposit,
            'unique_digit' => 0,
            'user_bank' => $request->user_bank,
            'is_tron' => $request->is_tron,
            'status' => 1
        );
        $modelSettingTrans->getInsertDepositTransaction($dataInsert);
        return redirect()->route('m_listDepositTransactions')
            ->with('message', 'Pengajuan Tark Deposit berhasil, tunggu konfirmasi admin')
            ->with('messageclass', 'success');
    }

    public function getListOperator($type)
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
        return view('member.digital.list-operator')
            ->with('headerTitle', 'List Operator')
            ->with('type', $type)
            ->with('dataUser', $dataUser);
    }

    public function getDaftarHargaOperator($operator)
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
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;

        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        //        dd($arrayData['data']);
        //category => pulsa
        //brand =>
        //1 => TELKOMSEL
        //2 => INDOSAT
        //3 => XL
        //4 => AXIS
        //5 => TRI
        //6 => SMART
        $telkomsel = array();
        $indosat = array();
        $xl = array();
        $axis = array();
        $tri = array();
        $smart = array();
        foreach ($arrayData['data'] as $row) {
            if ($row['category'] == 'Pulsa') {
                if ($row['price'] <= 40000) {
                    $priceAwal = $row['price'] + 50;
                }
                if ($row['price'] > 40000) {
                    $priceAwal = $row['price'] + 70;
                }
                $pricePersen = $priceAwal + ($priceAwal * 4 / 100);
                $priceRound = round($pricePersen, -2);
                $cek3digit = substr($priceRound, -3);
                $cek = 500 - $cek3digit;
                if ($cek == 0) {
                    $price = $priceRound;
                }
                if ($cek > 0 && $cek < 500) {
                    $price = $priceRound + $cek;
                }
                if ($cek == 500) {
                    $price = $priceRound;
                }
                if ($cek < 0) {
                    $price = $priceRound + (500 + $cek);
                }
                if ($row['brand'] == 'TELKOMSEL') {
                    $telkomsel[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'INDOSAT') {
                    $indosat[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'XL') {
                    $xl[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'AXIS') {
                    $axis[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'TRI') {
                    $tri[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'SMART') {
                    $smart[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
            }
        }
        $daftarHarga = null;
        if ($operator == 1) {
            $daftarHarga = $telkomsel;
        }
        if ($operator == 2) {
            $daftarHarga = $indosat;
        }
        if ($operator == 3) {
            $daftarHarga = $xl;
        }
        if ($operator == 4) {
            $daftarHarga = $axis;
        }
        if ($operator == 5) {
            $daftarHarga = $tri;
        }
        if ($operator == 6) {
            $daftarHarga = $smart;
        }
        return view('member.digital.daftar-harga-operator')
            ->with('headerTitle', 'Daftar Harga Operator')
            ->with('daftarHarga', $daftarHarga)
            ->with('dataUser', $dataUser);
    }

    public function getDaftarHargaDataOperator($operator)
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
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;

        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        //        dd($arrayData['data']);
        //category => pulsa
        //brand =>
        //1 => TELKOMSEL
        //2 => INDOSAT
        //3 => XL
        //4 => AXIS
        //5 => TRI
        //6 => SMART
        $telkomsel = array();
        $indosat = array();
        $xl = array();
        $axis = array();
        $tri = array();
        $smart = array();
        foreach ($arrayData['data'] as $row) {
            if ($row['category'] == 'Data') {
                if ($row['price'] <= 40000) {
                    $priceAwal = $row['price'] + 50;
                }
                if ($row['price'] > 40000) {
                    $priceAwal = $row['price'] + 70;
                }
                $pricePersen = $priceAwal + ($priceAwal * 4 / 100); //jadi 2 dari 4
                $priceRound = round($pricePersen, -2);
                $cek3digit = substr($priceRound, -3);
                $cek = 500 - $cek3digit;
                if ($cek == 0) {
                    $price = $priceRound;
                }
                if ($cek > 0 && $cek < 500) {
                    $price = $priceRound + $cek;
                }
                if ($cek == 500) {
                    $price = $priceRound;
                }
                if ($cek < 0) {
                    $price = $priceRound + (500 + $cek);
                }
                if ($row['brand'] == 'TELKOMSEL') {
                    $telkomsel[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'INDOSAT') {
                    $indosat[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'XL') {
                    $xl[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'AXIS') {
                    $axis[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'TRI') {
                    $tri[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'SMART') {
                    $smart[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
            }
        }
        $daftarHarga = null;
        if ($operator == 1) {
            if (!empty($telkomsel)) {
                $daftarHarga = $telkomsel;
            }
        }
        if ($operator == 2) {
            if (!empty($indosat)) {
                $daftarHarga = $indosat;
            }
        }
        if ($operator == 3) {
            if (!empty($xl)) {
                $daftarHarga = $xl;
            }
        }
        if ($operator == 4) {
            if (!empty($axis)) {
                $daftarHarga = $axis;
            }
        }
        if ($operator == 5) {
            if (!empty($tri)) {
                $daftarHarga = $tri;
            }
        }
        if ($operator == 6) {
            if (!empty($smart)) {
                $daftarHarga = $smart;
            }
        }
        if ($daftarHarga == null) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Tidak ada data')
                ->with('messageclass', 'danger');
        }
        return view('member.digital.daftar-hargadata-operator')
            ->with('headerTitle', 'Daftar Harga Data Operator')
            ->with('daftarHarga', $daftarHarga)
            ->with('dataUser', $dataUser);
    }

    public function getEmoneyByOperator($operator)
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
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;

        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        //        dd($arrayData['data']);
        //category => E-Money
        //brand => 1 => OVO
        $ovo = array();
        foreach ($arrayData['data'] as $row) {
            if ($row['category'] == 'E-Money') {
                $priceAwal = $row['price'];
                $pricePersen = $priceAwal + ($priceAwal * 4 / 100);
                $priceRound = round($pricePersen, -2);
                $cek3digit = substr($priceRound, -3);
                $cek = 500 - $cek3digit;
                if ($cek == 0) {
                    $price = $priceRound;
                }
                if ($cek > 0 && $cek < 500) {
                    $price = $priceRound + $cek;
                }
                if ($cek == 500) {
                    $price = $priceRound;
                }
                if ($cek < 0) {
                    $price = $priceRound + (500 + $cek);
                }
                if ($row['brand'] == 'OVO') {
                    $ovo[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
            }
        }
        $daftarHarga = null;
        $operatorName = null;
        $tipe = null;
        if ($operator == 1) {
            $daftarHarga = $ovo;
            $operatorName = 'OVO';
            $tipe = 8;
        }
        return view('member.digital.harga-emoney')
            ->with('daftarHarga', $daftarHarga)
            ->with('operatorName', $operatorName)
            ->with('tipe', $tipe)
            ->with('dataUser', $dataUser);
    }

    public function getDaftarHargaPLNPrepaid()
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
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;

        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        $daftarHargaPLN = array();
        foreach ($arrayData['data'] as $row) {
            if ($row['category'] == 'PLN') {
                $priceAwal = $row['price'];
                $price3000 = $priceAwal + 2000;
                $cek3digit = substr($price3000, -3);
                $cek = 500 - $cek3digit;
                if ($cek == 0) {
                    $price = $price3000;
                }
                if ($cek > 0 && $cek < 500) {
                    $price = $price3000 + $cek;
                }
                if ($cek == 500) {
                    $price = $price3000;
                }
                if ($cek < 0) {
                    $price = $price3000 + (500 + $cek);
                }
                $real_price = $price - 1445;
                if ($row['brand'] == 'PLN') {
                    $daftarHargaPLN[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price, //$row['price'],
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
            }
        }
        return view('member.digital.daftar-hargapln')
            ->with('headerTitle', 'Daftar Harga PLN')
            ->with('daftarHarga', $daftarHargaPLN)
            ->with('dataUser', $dataUser);
    }

    public function getPreparingBuyPPOB(Request $request)
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
        $no_hp = $request->no_hp;
        $separate = explode('__', $request->harga);
        $buyer_sku_code = $separate[0];
        $price = $separate[1];
        $buy_method = $request->type_pay;
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;

        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        $getData = array();
        foreach ($arrayData['data'] as $row) {
            if ($row['buyer_sku_code'] == $buyer_sku_code) {
                $getData[] = array(
                    'buyer_sku_code' => $row['buyer_sku_code'],
                    'desc' => $row['desc'],
                    'real_price' => $row['price'],
                    'price' => $price,
                    'brand' => $row['brand'],
                    'product_name' => $row['product_name']
                );
            }
        }
        //        dd($getData[0]);
        $getDataKelurahan = null;
        $getDataKecamatan = null;
        $getDataKota = null;
        if ($dataUser->kode_daerah != null) {
            $dataDaerah = explode('.', $dataUser->kode_daerah);
            $kelurahan = $dataUser->kelurahan;
            $kecamatan = $dataUser->kecamatan;
            $kota = $dataUser->kota;
            $getDataKelurahan = $modelMember->getSearchVendorUserByKelurahan($kelurahan, $kecamatan);
            $getDataKecamatan = $modelMember->getSearchVendorUserByKecamatan($kecamatan, $kelurahan);
            $getDataKota = $modelMember->getSearchUserVendorByKota($kota, $kecamatan, $kelurahan);
        }
        $cekRequestStockist = $modelMember->getCekRequestVendorExist($dataUser->id);
        //        dd($buy_method);
        return view('member.digital.pilih-vendor')
            ->with('headerTitle', 'Pilih Vendor')
            ->with('daftarVendor', $getData[0])
            ->with('getDataKelurahan', $getDataKelurahan)
            ->with('getDataKecamatan', $getDataKecamatan)
            ->with('getDataKota', $getDataKota)
            ->with('cekRequest', $cekRequestStockist)
            ->with('no_hp', $no_hp)
            ->with('buy_method', $buy_method)
            ->with('dataUser', $dataUser);
    }

    public function postBuyPPOB(Request $request)
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
        $modelPin = new Pin;
        if ($request->type == 1) {
            //cek saldo vendor
            $code = $modelPin->getCodePPOBRef($request->type);
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $code,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => $request->harga_modal,
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }
        if ($request->type == 2) {
            //cek saldo vendor
            $code = $modelPin->getCodePPOBRef($request->type);
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $code,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => $request->harga_modal,
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        if ($request->type == 3) {
            //cek saldo vendor
            $code = $modelPin->getCodePPOBRef($request->type);
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $code,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => $request->harga_modal,
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        if ($request->type == 4) {
            //cek saldo vendor
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $request->ref_id,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => $request->harga_modal,
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        if ($request->type == 5) {
            //cek saldo vendor
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $request->ref_id,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => ($request->harga_modal  - 1500),
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        if ($request->type == 6) {
            //cek saldo vendor
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $request->ref_id,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => $request->harga_modal,
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        if ($request->type == 7) {
            //cek saldo vendor
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $request->ref_id,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => ($request->harga_modal - 700),
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        if ($request->type == 8) {
            //cek saldo vendor
            $dataInsert = array(
                'buy_metode' => $request->buy_method,
                'user_id' => $dataUser->id,
                'vendor_id' => $request->vendor_id,
                'ppob_code' => $request->ref_id,
                'type' => $request->type,
                'buyer_code' => $request->buyer_sku_code,
                'product_name' => $request->no_hp,
                'ppob_price' => $request->price,
                'ppob_date' => date('Y-m-d'),
                'harga_modal' => $request->harga_modal,
                'message' => $request->message
            );
            $newPPOB = $modelPin->getInsertPPOB($dataInsert);
            return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID])
                ->with('message', 'Periksa kembali Order anda di bawah ini, lalu Konfirmasi')
                ->with('messageclass', 'success');
        }

        return redirect()->route('mainDashboard')
            ->with('message', 'Proses gagal, terjadi kesalahan pada sistem')
            ->with('messageclass', 'danger');
    }

    public function getListBuyPPOB(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        $modelSales = new Sales;
        $modelPin = new Pin;
        $getMonth = $modelSales->getThisMonth();
        if ($request->month != null && $request->year != null) {
            $start_day = date('Y-m-01', strtotime($request->year . '-' . $request->month));
            $end_day = date('Y-m-t', strtotime($request->year . '-' . $request->month));
            $text_month = date('F Y', strtotime($request->year . '-' . $request->month));
            $getMonth = (object) array(
                'startDay' => $start_day,
                'endDay' => $end_day,
                'textMonth' => $text_month
            );
        }
        $getData = $modelPin->getMemberHistoryPPOB($dataUser->id, $getMonth);
        $sum = 0;
        //        if($getData != null){
        //            foreach($getData as $row){
        //                if($row->status == 2){
        //                    $sum += $row->sale_price;
        //                }
        //            }
        //        }
        return view('member.digital.list_transaction')
            ->with('headerTitle', 'List Transaksi')
            ->with('getData', $getData)
            ->with('sum', $sum)
            ->with('getDate', $getMonth)
            ->with('dataUser', $dataUser);
    }

    //    array:1 [
    //        "data" => array:9 [
    //          "ref_id" => "ref_1_001_20200628"
    //          "customer_no" => "081282477195"
    //          "buyer_sku_code" => "T1"
    //          "message" => "Transaksi Pending"
    //          "status" => "Pending"
    //          "rc" => "03"
    //          "buyer_last_saldo" => 683545
    //          "sn" => ""
    //          "price" => 1355
    //        ]
    //    ]

    public function getDetailBuyPPOB($id)
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
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($id, $dataUser);
        $getVendor = $dataUser;
        if ($dataUser->id != $getDataMaster->vendor_id) {
            $getVendor = null;
            if ($getDataMaster->buy_metode == 1) {
                $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
                if ($getVendor->is_vendor == null) {
                    return redirect()->route('mainDashboard');
                }
            }
        }
        return view('member.digital.m_buy_ppob')
            ->with('headerTitle', 'Pembayaran')
            ->with('getDataMaster', $getDataMaster)
            ->with('getVendor', $getVendor)
            ->with('dataUser', $dataUser);
    }

    public function getDetailInvoicePPOB($id)
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
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($id, $dataUser);
        $getVendor = $dataUser;
        if ($dataUser->id != $getDataMaster->vendor_id) {
            $getVendor = null;
            if ($getDataMaster->buy_metode == 1) {
                $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
                if ($getVendor->is_vendor == null) {
                    return redirect()->route('mainDashboard');
                }
            }
        }
        //        dd($getDataMaster);
        return view('member.digital.m_pdf_ppob')
            ->with('getDataMaster', $getDataMaster)
            ->with('getVendor', $getVendor)
            ->with('dataUser', $dataUser);
    }

    public function getDetailVendorInvoicePPOB($id)
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
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getVendorPPOBDetail($id, $dataUser);
        $getMember = $modelMember->getUsers('id', $getDataMaster->user_id);
        //        dd($getDataMaster);
        return view('member.digital.m_vpdf_ppob')
            ->with('getDataMaster', $getDataMaster)
            ->with('getMember', $getMember)
            ->with('dataUser', $dataUser);
    }

    public function getUpdateStatusPPOB($id)
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
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $getData = $modelPin->getStatusPPOBDetail($id);
        $ref_id = $getData->ppob_code;
        $sign = md5($username . $apiKey . $ref_id);
        $array = array(
            'username' => $username,
            'buyer_sku_code' => $getData->buyer_code,
            'customer_no' => $getData->product_name,
            'ref_id' => $ref_id,
            'sign' => $sign,
        );
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        if ($arrayData['data']['status'] == 'Sukses') {
            $dataUpdate = array(
                'return_buy' => $cek
            );
            $modelPin->getUpdatePPOB('id', $id, $dataUpdate);
            return redirect()->route('m_detailPPOBMemberTransaction', [$id])
                ->with('message', 'update status berhasil')
                ->with('messageclass', 'success');
        }
        return redirect()->route('m_detailPPOBMemberTransaction', [$id])
            ->with('message', 'update status gagal')
            ->with('messageclass', 'danger');
    }

    public function getVendorDetailBuyPPOB($id)
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
        $modelPin = new Pin;
    }

    public function postConfirmBuyPPOB(Request $request)
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
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($request->id, $dataUser);
        if ($getDataMaster == null) {
            return redirect()->route('mainDashboard');
        }
        $tron = null;
        $tron_transfer = null;
        if ($request->tron_transfer != null) {
            $tron = $request->tron;
            $tron_transfer = $request->tron_transfer;
        }
        //        $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
        $dataUpdate = array(
            'status' => 1,
            'tron' => $tron,
            'tron_transfer' => $tron_transfer,
            'confirm_at' => date('Y-m-d H:i:s')
        );
        $modelPin->getUpdatePPOB('id', $request->id, $dataUpdate);
        return redirect()->route('m_listPPOBTransaction')
            ->with('message', 'konfirm pembelian berhasil, silakan hubungi vendor')
            ->with('messageclass', 'success');
    }

    public function getListVendorPPOBTransactions()
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
        $getData = $modelPin->getVendorTransactionPPOB($dataUser->id);
        return view('member.digital.list_vendor_transaction')
            ->with('headerTitle', 'List Transaksi')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getDetailVendorPPOB($id)
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
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getVendorPPOBDetail($id, $dataUser);
        $getMember = $modelMember->getUsers('id', $getDataMaster->user_id);
        return view('member.digital.m_detail_vppob')
            ->with('headerTitle', 'Konfirmasi Transaksi')
            ->with('getDataMaster', $getDataMaster)
            ->with('getMember', $getMember)
            ->with('dataUser', $dataUser);
    }

    public function postVendorConfirmPPOB(Request $request)
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
        //cek deposit cukup ga
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getDataMaster = $modelPin->getVendorPPOBDetail($request->ppob_id, $dataUser);
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataUser);
        $sum_deposit_masuk = 0;
        $sum_deposit_keluar1 = 0;
        $sum_deposit_keluar = 0;
        if ($getTotalDeposit->sum_deposit_masuk != null) {
            $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
        }
        if ($getTotalDeposit->sum_deposit_keluar != null) {
            $sum_deposit_keluar1 = $getTotalDeposit->sum_deposit_keluar;
        }
        if ($getTransTarik->deposit_keluar != null) {
            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1 - $getDataMaster->harga_modal;
        if ($totalDeposit < 0) {
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'tidak dapat dilanjutkan, deposit kurang')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $ref_id = $getDataMaster->ppob_code;
        $sign = md5($username . $apiKey . $ref_id);

        if ($getDataMaster->type == 1) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 2) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 3) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 4) {
            $array = array(
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => 'BPJS',
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 5) {
            $array = array(
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 6) {
            $array = array(
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 7) {
            $array = array(
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 8) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }

        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        //        $modelSettingTrans = New Transaction;
        //        $code =$modelSettingTrans->getCodeDepositTransaction();

        if ($arrayData['data']['status'] == 'Sukses') {
            $dataUpdate = array(
                'status' => 2,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'return_buy' => $cek,
                'vendor_approve' => 2
            );
            $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
            $cekDuaKali = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code . '-' . $ref_id);
            if ($cekDuaKali == null) {
                $memberDeposit = array(
                    'user_id' => $dataUser->id,
                    'total_deposito' => $request->harga_modal, //tambahin 2%
                    'transaction_code' => $getDataMaster->buyer_code . '-' . $ref_id,
                    'deposito_status' => 1
                );
                $modelPin->getInsertMemberDeposit($memberDeposit);
            }
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'transaksi berhasil')
                ->with('messageclass', 'success');
        }

        if ($arrayData['data']['status'] == 'Pending') {
            $dataUpdate = array(
                'vendor_cek' => $cek
            );
            $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'transaksi sedang pending, tunggu beberapa saat. Kemudian cek status di halaman transaksi digital')
                ->with('messageclass', 'warning');
        }

        if ($arrayData['data']['status'] == 'Gagal') {
            $dataUpdate = array(
                'status' => 3,
                'deleted_at' => date('Y-m-d H:i:s'),
                'return_buy' => $cek,
                'vendor_approve' => 3,
                'vendor_cek' => $cek
            );
            $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'transaksi gagal')
                ->with('messageclass', 'danger');
        }

        return redirect()->route('m_listVendotPPOBTransactions')
            ->with('message', 'tidak ada data')
            ->with('messageclass', 'danger');
    }

    public function postVendorRejectPPOB(Request $request)
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
        $modelPin = new Pin;
        $dataUpdate = array(
            'status' => 3,
            'reason' => $request->reason,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
        return redirect()->route('m_listVendotPPOBTransactions')
            ->with('message', 'Transaksi berhasil dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getCekStatusTransaksiApi($id)
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
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getVendorPPOBDetail($id, $dataUser);
        if ($getDataMaster == null) {
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'Tidak ada data')
                ->with('messageclass', 'danger');
        }
        if ($getDataMaster->status != 2) {
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'transaksi vendor belum tuntas')
                ->with('messageclass', 'danger');
        }
        if ($getDataMaster->vendor_approve != 0) {
            return redirect()->route('m_listVendotPPOBTransactions')
                ->with('message', 'Tidak ada data')
                ->with('messageclass', 'danger');
        }
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username . $apiKey . $getDataMaster->ppob_code);

        if ($getDataMaster->type == 1) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 2) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 3) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 4) {
            $array = array(
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 5) {
            $array = array(
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 6) {
            $array = array(
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 7) {
            $array = array(
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 8) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $getDataMaster->ppob_code,
                'sign' => $sign,
            );
        }

        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);

        if ($arrayData != null) {
            if ($arrayData['data']['status'] == 'Sukses') {
                $dataUpdate = array(
                    'status' => 2,
                    'tuntas_at' => date('Y-m-d H:i:s'),
                    'return_buy' => $cek,
                    'vendor_approve' => 2
                );
                $modelPin->getUpdatePPOB('id', $getDataMaster->id, $dataUpdate);
                $cekDuaKali = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code . '-' . $getDataMaster->ppob_code);
                if ($cekDuaKali == null) {
                    $memberDeposit = array(
                        'user_id' => $dataUser->id,
                        'total_deposito' => $getDataMaster->harga_modal,
                        'transaction_code' => $getDataMaster->buyer_code . '-' . $getDataMaster->ppob_code,
                        'deposito_status' => 1
                    );
                    $modelPin->getInsertMemberDeposit($memberDeposit);
                }
                return redirect()->route('m_listVendotPPOBTransactions')
                    ->with('message', 'transaksi berhasil')
                    ->with('messageclass', 'success');
            }

            if ($arrayData['data']['status'] == 'Pending') {
                return redirect()->route('m_listVendotPPOBTransactions')
                    ->with('message', 'transaksi sedang pending, tunggu beberapa saat. Kemudian cek status di halaman transaksi digital')
                    ->with('messageclass', 'warning');
            }

            if ($arrayData['data']['status'] == 'Gagal') {
                $dataUpdate = array(
                    'status' => 3,
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'return_buy' => $cek,
                    'vendor_approve' => 3,
                    'vendor_cek' => $cek
                );
                $modelPin->getUpdatePPOB('id', $getDataMaster->id, $dataUpdate);
                $cekDepositGagal = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code . '-' . $getDataMaster->ppob_code);
                if ($cekDepositGagal != null) {
                    $modelPin->getDeleteMemberDeposit($cekDepositGagal->id);
                }
                return redirect()->route('m_listVendotPPOBTransactions')
                    ->with('message', 'terjadi kesalahan pada transaksi, saldo dikembalikan')
                    ->with('messageclass', 'success');
            }
        }
        return redirect()->route('m_listVendotPPOBTransactions')
            ->with('message', 'tidak ada data transaksi, kesalahan pada api')
            ->with('messageclass', 'danger');
    }

    public function getPPOBPascabayar($type)
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
        if ($type > 4) {
            return redirect()->route('mainDashboard');
        }
        return view('member.digital.pasca-input_no')
            ->with('headerTitle', 'Cek Tagihan')
            ->with('type', $type)
            ->with('dataUser', $dataUser);
    }

    public function getPPOBPascabayarCekTagihan(Request $request)
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
        //1 BPJS. 2 PLN, 3 Hp Pasca, 4 TELKOM PSTN

        if ($request->type == 1) {
            $buyer_sku_code = 'BPJS';
            $typePPOB = 4;
        }
        if ($request->type == 2) {
            $buyer_sku_code = 'PLNPOST';
            $typePPOB = 5;
        }
        if ($request->type == 3) {
            $buyer_sku_code = $request->buyer_sku_code;
            $typePPOB = 6;
        }
        if ($request->type == 4) {
            $buyer_sku_code = 'TELKOM';
            $typePPOB = 7;
        }
        $modelMember = new Member;
        $modelPin = new Pin;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $ref_id = $modelPin->getCodePPOBRef($typePPOB);
        $sign = md5($username . $apiKey . $ref_id);
        $array = array(
            'commands' => 'inq-pasca',
            'username' => $username,
            'buyer_sku_code' => $buyer_sku_code,
            'customer_no' => $request->customer_no,
            'ref_id' => $ref_id,
            'sign' => $sign,
        );
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $getData = json_decode($cek, true);
        if ($getData == null) {
            return redirect()->route('mainDashboard')
                ->with('message', 'data tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        return view('member.digital.pasca-cek_tagihan')
            ->with('getData', $getData['data'])
            ->with('buyer_sku_code', $buyer_sku_code)
            ->with('type', $typePPOB)
            ->with('dataUser', $dataUser);
    }

    public function getPPOBHPPascabayar()
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
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => 'pasca',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        $data = array();
        foreach ($arrayData['data'] as $row) {
            if ($row['brand'] == 'HP PASCABAYAR') {
                $data[] = array(
                    'buyer_sku_code' => $row['buyer_sku_code'],
                    'admin' => $row['admin'],
                    'commission' => $row['commission'],
                    'brand' => $row['brand'],
                    'product_name' => $row['product_name']
                );
            }
        }
        return view('member.digital.daftar-hp_pascabayar')
            ->with('headerTitle', 'Daftar HP Pascabayar')
            ->with('data', $data)
            ->with('dataUser', $dataUser);
    }

    public function getDetailPPOBHpPascabayar($sku, Request $request)
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
        return view('member.digital.hp-pasca-input_no')
            ->with('headerTitle', 'Cek Tagihan')
            ->with('type', 3)
            ->with('buyer_sku_code', $sku)
            ->with('product_name', $request->product_name)
            ->with('dataUser', $dataUser);
    }

    public function getListTagihanPascabayar()
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
        return view('member.digital.list-tagihan_pascabayar')
            ->with('headerTitle', 'List Pascabayar')
            ->with('dataUser', $dataUser);
    }















    //    public function getMyRequestPOBX($paket){
    //        $dataUser = Auth::user();
    //        $onlyUser  = array(10);
    //        if(!in_array($dataUser->user_type, $onlyUser)){
    //            return redirect()->route('mainDashboard');
    //        }
    //        if($dataUser->package_id == null){
    //            return redirect()->route('m_newPackage');
    //        }
    //        return view('member.bonus.pobx')
    //                ->with('headerTitle', 'Pin')
    //                ->with('dataUser', $dataUser);
    //    }


    //    array:1 [▼
    //        "data" => array:11 [▼
    //          0 => array:10 [▼
    //            "product_name" => "Pln Pascabayar"
    //            "category" => "Pascabayar"
    //            "brand" => "PLN PASCABAYAR"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 2750
    //            "commission" => 2500
    //            "buyer_sku_code" => "PLNPOST"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          1 => array:10 [▼
    //            "product_name" => "Halo Postpaid"
    //            "category" => "Pascabayar"
    //            "brand" => "HP PASCABAYAR"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 0
    //            "commission" => 800
    //            "buyer_sku_code" => "HALO"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          2 => array:10 [▼
    //            "product_name" => "XL Postpaid"
    //            "category" => "Pascabayar"
    //            "brand" => "HP PASCABAYAR"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 0
    //            "commission" => 1500
    //            "buyer_sku_code" => "XLPASCA"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          3 => array:10 [▼
    //            "product_name" => "Tirtanadi Medan"
    //            "category" => "Pascabayar"
    //            "brand" => "PDAM"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 2500
    //            "commission" => 800
    //            "buyer_sku_code" => "TIRTANADI"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "Provinsi Sumatera Utara"
    //          ]
    //          4 => array:10 [▼
    //            "product_name" => "TELKOMPSTN"
    //            "category" => "Pascabayar"
    //            "brand" => "INTERNET PASCABAYAR"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 2500
    //            "commission" => 1200
    //            "buyer_sku_code" => "TELKOM"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          5 => array:10 [▼
    //            "product_name" => "Bpjs Kesehatan"
    //            "category" => "Pascabayar"
    //            "brand" => "BPJS KESEHATAN"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 2500
    //            "commission" => 1150
    //            "buyer_sku_code" => "BPJS"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          6 => array:10 [▼
    //            "product_name" => "Bussan Auto Finance"
    //            "category" => "Pascabayar"
    //            "brand" => "MULTIFINANCE"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 6000
    //            "commission" => 1100
    //            "buyer_sku_code" => "BAF"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          7 => array:10 [▼
    //            "product_name" => "Mega Auto Finance"
    //            "category" => "Pascabayar"
    //            "brand" => "MULTIFINANCE"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 0
    //            "commission" => 500
    //            "buyer_sku_code" => "MAF"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          8 => array:10 [▼
    //            "product_name" => "Mega Central Finance"
    //            "category" => "Pascabayar"
    //            "brand" => "MULTIFINANCE"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 0
    //            "commission" => 500
    //            "buyer_sku_code" => "MCF"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          9 => array:10 [▼
    //            "product_name" => "Wom Finance"
    //            "category" => "Pascabayar"
    //            "brand" => "MULTIFINANCE"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 0
    //            "commission" => 500
    //            "buyer_sku_code" => "WOM"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //          10 => array:10 [▼
    //            "product_name" => "Columbia Finance"
    //            "category" => "Pascabayar"
    //            "brand" => "MULTIFINANCE"
    //            "seller_name" => "Lucky 7 Cell"
    //            "admin" => 0
    //            "commission" => 1100
    //            "buyer_sku_code" => "COF"
    //            "buyer_product_status" => true
    //            "seller_product_status" => true
    //            "desc" => "-"
    //          ]
    //        ]
    //      ]










}
