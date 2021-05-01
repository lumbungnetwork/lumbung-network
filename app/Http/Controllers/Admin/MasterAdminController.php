<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
use App\Model\Sales;
use App\Jobs\ManualTopUpeIDRjob;
use Illuminate\Support\Facades\Mail;

class MasterAdminController extends Controller
{

    public function __construct()
    {
    }

    public function getAddAdmin()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelAdmin = new Admin;
        $getAllAdmin = $modelAdmin->getAllUserAdmin($dataUser);
        return view('admin.user.create-user')
            ->with('headerTitle', 'Admin')
            ->with('getAllAdmin', $getAllAdmin)
            ->with('dataUser', $dataUser);
    }

    public function postAddAdmin(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($request->email == null || $request->password == null || $request->repassword == null || $request->f_name == null || $request->role == null) {
            return redirect()->route('addCrew')
                ->with('message', 'The field is required.')
                ->with('messageclass', 'danger');
        }
        if ($request->password != $request->repassword) {
            return redirect()->route('addCrew')
                ->with('message', 'Password didn\'t match')
                ->with('messageclass', 'danger');
        }
        $permission = implode(",", $request->role);
        $modelAdmin = new Admin;
        $cekUsername = $modelAdmin->getCekNewUsername($request->email);
        if ($cekUsername != null) {
            return redirect()->route('addCrew')
                ->with('message', 'use another email')
                ->with('messageclass', 'danger');
        }
        $dataInsert = array(
            'username' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->f_name,
            'email' => $request->email,
            'is_active' => 1,
            'user_type' => 3,
            'id_type' => 0,
            'permission' => $permission
        );
        $modelAdmin->getInsertUser($dataInsert);
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/new-admin username = ' . $request->email
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('addCrew')
            ->with('message', 'Create New Admin Success')
            ->with('messageclass', 'success');
    }

    public function postEditAdmin(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($request->email == null || $request->password == null || $request->repassword == null || $request->name == null) {
            return redirect()->route('addCrew')
                ->with('message', 'Data tidak boleh kosong.')
                ->with('messageclass', 'danger');
        }
        if ($request->password != $request->repassword) {
            return redirect()->route('addCrew')
                ->with('message', 'Password harus sama dengan ketik ulang password')
                ->with('messageclass', 'danger');
        }
        $modelAdmin = new Admin;
        $cekUsername = $modelAdmin->getCekNewUsernameEdit($request->email, $request->cekId);
        if ($cekUsername != null) {
            return redirect()->route('addCrew')
                ->with('message', 'gunakan username/email yang lain')
                ->with('messageclass', 'danger');
        }
        $dataUpdate = array(
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'email' => $request->email,
        );
        $modelAdmin->getUpdateMember('id', $request->cekId, $dataUpdate);
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/admin username = ' . $request->email
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('addCrew')
            ->with('message', 'Berhasil edit admin')
            ->with('messageclass', 'success');
    }

    //Setting
    public function getAddPinSetting()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSettingPin = new Pinsetting;
        $getPinSetting = $modelSettingPin->getActivePinSetting();
        $modelAdmin = new Admin;
        return view('admin.pin.pin-setting')
            ->with('headerTitle', 'Pin')
            ->with('data', $getPinSetting)
            ->with('dataUser', $dataUser);
    }

    public function postPinSetting(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $price = $request->price;
        $modelSettingPin = new Pinsetting;
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
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/add/pin-setting'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('addSettingPin')
            ->with('message', 'Create Pin Setting Success')
            ->with('messageclass', 'success');
    }

    public function getAllPackage()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSettingPin = new Pinsetting;
        $modelPackage = new Package;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $getAllPackage = $modelPackage->getAllPackage();
        $modelAdmin = new Admin;
        return view('admin.package.package-list')
            ->with('headerTitle', 'Package')
            ->with('pinSetting', $getActivePinSetting)
            ->with('package', $getAllPackage)
            ->with('dataUser', $dataUser);
    }

    public function postUpdatePackage(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPackage = new Package;
        $dataUpdate = array(
            'name' => $request->name,
            'short_desc' => $request->short_desc,
            'pin' => $request->pin,
            'stock_wd' => $request->stock_wd,
            'discount' => $request->discount
        );
        $modelPackage->getUpdatePackage($request->cekId, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('allPackage')
            ->with('message', 'Update Package Success')
            ->with('messageclass', 'success');
    }

    public function getListTransactions(Request $request)
    {
        $status = $request->s;
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSettingTrans = new Transaction;
        $getAllTransaction = $modelSettingTrans->getTransactionsByAdmin($status);
        return view('admin.pin.list-transaction')
            ->with('headerTitle', 'Transaksi')
            ->with('getData', $getAllTransaction)
            ->with('dataUser', $dataUser);
    }

    public function postConfirmTransaction(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $id = $request->cekId;
        $user_id = $request->cekMemberId;
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailTransactionsAdmin($id, $user_id);
        if ($getData == null) {
            return redirect()->route('adm_listTransaction')
                ->with('message', 'Data tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        $modelSettingPin = new Pinsetting;
        $getPinSetting = $modelSettingPin->getActivePinSetting();
        $memberPin = array(
            'user_id' => $user_id,
            'total_pin' => $getData->total_pin,
            'setting_pin' => $getPinSetting->id,
            'transaction_code' => $getData->transaction_code,
            'pin_code' => 'P' . date('Ymd') . $user_id
        );
        $modelPin = new Pin;
        $modelMasterPin = new Masterpin;
        $modelMember = new Member;
        $modelPin->getInsertMemberPin($memberPin);
        if ($getData->type == 1) {
            $memberStatus = 1;
            if ($getData->total_pin >= 100) {
                $memberStatus = 2;
            }
            $dataMemberUpdate = array(
                'member_status' => $memberStatus,
                'member_status_at' => date('Y-m-d H:i:s')
            );
            $modelMember->getUpdateUsers('id', $user_id, $dataMemberUpdate);
            $reason = 'Member buy pin';
        }

        $dataUpdate = array(
            'status' => 2,
            'tuntas_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelSettingTrans->getUpdateTransaction('id', $id, $dataUpdate);
        $dataInsertMasterPin = array(
            'total_pin' => $getData->total_pin,
            'type_pin' => 2,
            'transaction_code' => $getData->transaction_code,
            'reason' => $reason
        );
        $modelMasterPin->getInsertMasterPin($dataInsertMasterPin);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listTransaction')
            ->with('message', 'Berhasil konfirmasi transfer pin')
            ->with('messageclass', 'success');
    }

    public function postRejectTransaction(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $id = $request->cekId;
        $user_id = $request->cekMemberId;
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailRejectTransactionsAdminByID($id, $user_id);
        if ($getData == null) {
            return redirect()->route('adm_listTransaction')
                ->with('message', 'Data tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        $dataUpdate = array(
            'status' => 3,
            'deleted_at' => date('Y-m-d H:i:s'),
            'reason' => $request->reason,
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelSettingTrans->getUpdateTransaction('id', $id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listTransaction')
            ->with('message', 'Transaksi dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getListHistoryTransactions()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSettingTrans = new Transaction;
        $getAllTransaction = $modelSettingTrans->getAdminHistoryTransactions();
        return view('admin.pin.history-transaction')
            ->with('headerTitle', 'History Transaksi')
            ->with('getData', $getAllTransaction)
            ->with('dataUser', $dataUser);
    }

    public function getBankPerusahaan()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBank = new Bank;
        $getPerusahaanBank = $modelBank->getBankPerusahaan();
        $getPerusahaanTron = $modelBank->getTronPerusahaan();
        $modelAdmin = new Admin;
        return view('admin.bank.list-bank')
            ->with('headerTitle', 'Bank Perusahaan')
            ->with('getData', $getPerusahaanBank)
            ->with('getDataTron', $getPerusahaanTron)
            ->with('dataUser', $dataUser);
    }

    public function postBankPerusahaan(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
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
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/bank'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_bankPerusahaan')
            ->with('message', 'Berhasil update bank perusahaan')
            ->with('messageclass', 'success');
    }

    public function getAddBankPerusahaan()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        return view('admin.bank.add-bank')
            ->with('headerTitle', 'Bank Perusahaan')
            ->with('dataUser', $dataUser);
    }

    public function postAddBankPerusahaan(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
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

    public function postTronPerusahaan(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBank = new Bank;
        //        $getPerusahaanTron = $modelBank->getTronPerusahaanID($request->id);
        $dataUpdate = array(
            'tron' => $request->tron,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $modelBank->getUpdateTron('id', $request->id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_bankPerusahaan')
            ->with('message', 'Berhasil update tron perusahaan')
            ->with('messageclass', 'success');
    }

    public function getAddTronPerusahaan()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        return view('admin.bank.add-tron')
            ->with('headerTitle', 'Tron Perusahaan')
            ->with('dataUser', $dataUser);
    }

    public function postAddTronPerusahaan(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBank = new Bank;
        $dataInsert = array(
            'user_id' => 2,
            'tron_name' => 'eIDR',
            'tron' => $request->tron,
            'tron_type' => 1,
            'active_at' => date('Y-m-d H:i:s')
        );
        $modelBank->getInsertTron($dataInsert);
        return redirect()->route('adm_bankPerusahaan')
            ->with('message', 'Berhasil tambah tron perusahaan')
            ->with('messageclass', 'success');
    }

    public function getListKirimPaket()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPengiriman = new Pengiriman;
        $getAllPengiriman = $modelPengiriman->getAdmPengiriman();
        return view('admin.pin.kirim-paket')
            ->with('headerTitle', 'Kirim Paket')
            ->with('getData', $getAllPengiriman)
            ->with('dataUser', $dataUser);
    }

    public function getKirimPaketByID($id, $user_id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($id, $user_id);
        return view('admin.pin.kirim-paket-detail')
            ->with('headerTitle', 'Konfirmasi Pengiriman')
            ->with('getData', $getPengiriman)
            ->with('dataUser', $dataUser);
    }

    public function postConfirmKirimPaket(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($request->cekId, $request->cekUserId);
        if ($getPengiriman == null) {
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

    public function getBonusStart()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $getBonusStart = $modelBonusSetting->getActiveBonusStart();
        $modelAdmin = new Admin;
        return view('admin.setting.bonus-start')
            ->with('headerTitle', 'Setting Bonus Sponsor')
            ->with('getData', $getBonusStart)
            ->with('dataUser', $dataUser);
    }

    public function postBonusStart(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
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
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/bonus-start'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_bonusStart')
            ->with('message', 'Edit Setting bonus start berhasil')
            ->with('messageclass', 'success');
    }

    public function getAllMember()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        return view('admin.member.list-member')
            ->with('headerTitle', 'Member')
            ->with('getData', null)
            ->with('dataUser', $dataUser);
    }

    public function getAllBonusSponsor()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $modelBonus = new Bonus;
        $getData = $modelBonus->getBonusSponsorByAdmin();
        $modelAdmin = new Admin;
        return view('admin.bonus.bonus-sponsor')
            ->with('headerTitle', 'Bonus Sponsor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllWD()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllRequestWD();
        return view('admin.member.list-wd')
            ->with('headerTitle', 'Request Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllWDeIDR()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllRequestWDeIDR();
        return view('admin.member.list-wd-eidr')
            ->with('headerTitle', 'Request Withdrawal Konversi eIDR')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckWD(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $getRowID = $request->id;
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelWD->getUpdateWD('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listWD')
            ->with('message', 'Konfirmasi Transfer WD berhasil')
            ->with('messageclass', 'success');
    }

    public function postCheckWDeIDR(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $getRowID = $request->id;
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelWD->getUpdateWD('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listWDeIDR')
            ->with('message', 'Konfirmasi Transfer WD berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectWD(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $getData = $modelWD->getIDRequestWDReject($getID);
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelWD->getUpdateWD('id', $getID, $dataUpdate);

        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->back()
            ->with('message', 'Data Request ' . $getData->full_name . ' senilai Rp. ' . number_format($getData->wd_total + $getData->admin_fee, 0, ',', '.') . ' direject')
            ->with('messageclass', 'success');
    }

    public function getAllHistoryWD()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllHistoryWD();
        return view('admin.member.history-wd')
            ->with('headerTitle', 'History Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllHistoryWDeIDR()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllHistoryWDeIDR();
        return view('admin.member.history-wd-eidr')
            ->with('headerTitle', 'History Konversi eIDR')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllDaerah()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        ini_set("memory_limit", -1);
        ini_set('max_execution_time', 1500);
        $modelAdmin = new Admin;
        //        $getData = $modelAdmin->getDaerah();
        //        $jsonDaerah = json_encode($getData);
        //        $file = 'daerah.json';
        //        $destinationPath = storage_path()."/app/public/";
        //        File::put($destinationPath.$file, $jsonDaerah);
        //        return response()->download($destinationPath.$file);
        $jsonFile = public_path() . '/image/daerah_indonesia.json';
        $fileData = file_get_contents($jsonFile);
        $dataArray = json_decode($fileData, true);
        //        $dataArray = array(
        //            "daerahID" => 1,
        //            "kode" => "11.00.00.0000",
        //            "nama" => "Nanggroe Aceh Darussalaam",
        //            "propinsi" => 11,
        //            "kabupatenkota" => 0,
        //            "kecamatan" => 0,
        //            "kelurahan" => 0,
        //        );
        //        dd($dataArray[0]);
        foreach ($dataArray as $row) {
            $modelAdmin->getInsertDaerah($row);
        }
        dd('done');
    }

    public function getAllRequestMemberStockist()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getData = $modelMember->getAllMemberReqSotckist();
        return view('admin.member.req-stockist')
            ->with('headerTitle', 'Request Stockist')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getHistoryRequestMemberStockist()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getData = $modelMember->getHistoryAllMemberReqSotckist();
        return view('admin.member.history-req_stockist')
            ->with('headerTitle', 'History Request Stockist')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllMemberStockists()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getData = $modelMember->getAdminAllStockist();
        return view('admin.member.all-stockists')
            ->with('headerTitle', 'List Stockist')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postRequestMemberStockist(Request $request)
    { //disini
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 1,
            'active_at' => $date,
            'submit_by' => $dataUser->id,
            'submit_at' => $date,
        );
        $modelMember->getUpdateStockist('id', $request->id, $dataUpdate);
        $dataUpdateUser = array(
            'is_stockist' => 1,
            'stockist_at' => $date
        );
        $modelMember->getUpdateUsers('id', $request->id_user, $dataUpdateUser);
        return redirect()->route('adm_listReqStockist')
            ->with('message', 'Member berhasil menjadi stockist')
            ->with('messageclass', 'success');
    }

    public function postRejectMemberStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 2,
            'deleted_at' => $date,
            'submit_by' => $dataUser->id,
            'submit_at' => $date,
        );
        $modelMember->getUpdateStockist('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listReqStockist')
            ->with('message', 'Member request stockist direject')
            ->with('messageclass', 'success');
    }

    public function postRequestMemberVendor(Request $request)
    { //disini
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 1,
            'active_at' => $date,
            'submit_by' => $dataUser->id,
            'submit_at' => $date,
        );
        $modelMember->getUpdateVendor('id', $request->id, $dataUpdate);
        $dataUpdateUser = array(
            'is_vendor' => 1,
            'vendor_at' => $date
        );
        $modelMember->getUpdateUsers('id', $request->id_user, $dataUpdateUser);
        return redirect()->route('adm_listReqVendor')
            ->with('message', 'Member berhasil menjadi vendor')
            ->with('messageclass', 'success');
    }

    public function postRejectMemberVendor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 2,
            'deleted_at' => $date,
            'submit_by' => $dataUser->id,
            'submit_at' => $date,
        );
        $modelMember->getUpdateVendor('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listReqVendor')
            ->with('message', 'Member request vendor direject')
            ->with('messageclass', 'success');
    }

    public function getAllMemberVendor()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getData = $modelMember->getAdminAllVendor();
        return view('admin.member.all-vendor')
            ->with('headerTitle', 'List Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postRemoveMemberStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 10,
            'deleted_at' => $date,
            'submit_by' => $dataUser->id,
            'submit_at' => $date,
        );
        $modelMember->getUpdateStockist('id', $request->id, $dataUpdate);
        $dataUpdateUser = array(
            'is_stockist' => 0,
            'stockist_at' => null
        );
        $modelMember->getUpdateUsers('id', $request->id_user, $dataUpdateUser);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'hapus member stockist'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMemberStockist')
            ->with('message', 'stockist ' . $request->username . ' dihapus kememberannya')
            ->with('messageclass', 'success');
    }

    public function postEditMemberStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        //        $dataUpdate = array(
        //            'status' => 10,
        //            'deleted_at' => $date,
        //            'submit_by' => $dataUser->id,
        //            'submit_at' => $date,
        //        );
        //        $modelMember->getUpdateStockist('id', $request->id, $dataUpdate);
        $dataUpdateUser = array(
            'hp' => $request->hp,
        );
        $modelMember->getUpdateUsers('id', $request->id_user, $dataUpdateUser);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'change hp'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMemberStockist')
            ->with('message', 'stockist ' . $request->username . ' dihapus kememberannya')
            ->with('messageclass', 'success');
    }

    public function getAllPurchase()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getAllPurchase();
        return view('admin.sales.all_purchase')
            ->with('headerTitle', 'All Products')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAddPurchase()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $getProvince = $modelMember->getProvinsiNew();
        return view('admin.sales.add_purchase')
            ->with('headerTitle', 'Create Products')
            ->with('provinsi', $getProvince)
            ->with('dataUser', $dataUser);
    }

    public function postAddPurchase(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        if ($request->provinsi == 0) {
            return redirect()->route('adm_addPurchase')
                ->with('message', 'Anda Tidak memilih propinsi')
                ->with('messageclass', 'danger');
        }
        $provinsiSearch = $modelMember->getProvinsiByID($request->provinsi);
        $kotaSearch = $modelMember->getKabByID($request->kota);
        $provinsiName = $provinsiSearch->nama;
        $kotaName = $kotaSearch->nama;
        $qty = 200000;
        $dataInsert = array(
            'name' => $request->name,
            'ukuran' => $request->ukuran,
            'stockist_price' => $request->member_price,
            'member_price' => $request->member_price,
            'code' => $request->code,
            'image' => $request->image,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => 0,
            'kelurahan' => 0,
            'qty' => $qty,
            'area' => $provinsiName . ' ' . $kotaName
        );
        $getInsertPurchase = $modelSales->getInsertPurchase($dataInsert);
        //insert stock
        $dataInsertStock = array(
            'purchase_id' => $getInsertPurchase->lastID,
            'user_id' => $dataUser->id,
            'type' => 1,
            'amount' => $qty
        );
        $modelSales->getInsertStock($dataInsertStock);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listPurchases')
            ->with('message', 'Produk berhasil ditambahkan')
            ->with('messageclass', 'success');
    }

    public function getNewBonusReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $getData = $modelBonusSetting->getActiveBonusReward();
        return view('admin.setting.add-bonus-reward')
            ->with('headerTitle', 'New Bonus Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postNewBonusReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $dataInsert = array(
            'name' => $request->name,
            'reward_detail' => $request->reward_detail,
            'image' => $request->image,
            'qualified' => $request->qualified,
            'member_type' => $request->member_type,
            'type' => $request->type
        );
        $modelBonusSetting->getInsertReward($dataInsert);
        return redirect()->route('adm_newReward')
            ->with('message', 'Reward berhasil ditambahkan')
            ->with('messageclass', 'success');
    }

    public function getBonusReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $getData = $modelBonusSetting->getActiveBonusReward();
        $modelAdmin = new Admin;
        return view('admin.setting.bonus-reward')
            ->with('headerTitle', 'Bonus Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postBonusReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonusSetting = new Bonussetting;
        $dataUpdate = array(
            'name' => $request->name,
            'reward_detail' => $request->reward_detail
        );
        $modelBonusSetting->getUpdateReward('id', $request->cekId, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/bonus-reward'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_Rewards')
            ->with('message', 'Edit Setting bonus reward berhasil')
            ->with('messageclass', 'success');
    }

    public function getTesting()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        return view('admin.setting.testing')
            ->with('headerTitle', 'Testing')
            ->with('dataUser', $dataUser);
    }

    public function getAllClaimReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllReward();
        return view('admin.member.list-reward')
            ->with('headerTitle', 'Claim Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckClaimReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getRowID = $request->id;
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateClaimReward('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listClaimReward')
            ->with('message', 'Konfirmasi Reward berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectClaimReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $getData = $modelBonus->getAdminRewardByID($getID);
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateClaimReward('id', $getID, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listClaimReward')
            ->with('message', 'Data Claim Reject berhasil')
            ->with('messageclass', 'success');
    }

    public function getHistoryClaimReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminHistoryReward();
        return view('admin.member.history-reward')
            ->with('headerTitle', 'History Claim Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllRequestMemberInputStock()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputStockist();
        return view('admin.member.req-input-stock')
            ->with('headerTitle', 'Input Stock & Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postRequestMemberInputStock(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'status' => 2,
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelSales->getUpdateItemPurchaseMaster('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listReqInputStock')
            ->with('message', 'Konfirmasi Member request input stock & royalti berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectMemberInputStock(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'status' => 10,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
            'reason' => $request->reason
        );
        $modelSales->getUpdateItemPurchaseMaster('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listReqInputStock')
            ->with('message', 'Reject Member request input stock & royalti berhasil')
            ->with('messageclass', 'success');
    }

    public function getAllConfirmBelanjaStockist()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getAdminConfirmBelanja();
        return view('admin.member.confirm-belanja')
            ->with('headerTitle', 'Confirm Belanja')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postConfirmBelanjaStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'status' => 3
        );
        $modelSales->getUpdateMasterSales('id', $request->id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listConfirmBelanjaStockist')
            ->with('message', 'Konfirmasi belanja stockist berhasil')
            ->with('messageclass', 'success');
    }

    public function getAllVerificationRoyalti()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getAdminVerificationRoyalti();
        return view('admin.member.confirm-royalti')
            ->with('headerTitle', 'Verification Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postVerificationRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;

        $getAllSales = $modelSales->getAdminRoyaltiSales($request->id);
        foreach ($getAllSales as $row) {
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
        $dataUpdate = array(
            'status' => 5
        );
        $modelSales->getUpdateMasterSales('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listVerificationRoyalti')
            ->with('message', 'Verifikasi royalti berhasil')
            ->with('messageclass', 'success');
    }

    public function getAllBelanjaReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllBelanjaReward();
        return view('admin.member.belanja-reward')
            ->with('headerTitle', 'Claim Reward Belanja')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getRowID = $request->id;
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'tuntas_at' => date('Y-m-d H:i:s')
            );
            $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        }
        return redirect()->route('adm_listBelanjaReward')
            ->with('message', 'Konfirmasi Reward Belanja berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listBelanjaReward')
            ->with('message', 'Data Reward Belanja berhasil direject')
            ->with('messageclass', 'success');
    }

    public function getHistoryBelanjaReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminHistoryBelanjaReward();
        return view('admin.member.history-reward-belanja')
            ->with('headerTitle', 'History Reward Belanja')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getEditPurchase($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $getData = $modelSales->getDetailPurchase($id);
        $getProvince = $modelMember->getProvinsi();
        $geCodeProvince = $getData->provinsi . '.00.00.0000';
        $cekLenght = strlen($getData->kota);
        $kota = $getData->kota;
        if ($cekLenght == 1) {
            $kota = '0' . $getData->kota;
        }
        $getCodeKota = $getData->provinsi . '.' . $kota . '.00.0000';
        $getDetailProvinsi = $modelMember->getNamaByKode($geCodeProvince);
        $getDetailKota = $modelMember->getNamaByKode($getCodeKota);
        $getAllKotaFromProvince = $modelMember->getKabupatenKotaByPropinsi($getData->provinsi);
        return view('admin.sales.edit_purchase')
            ->with('headerTitle', 'Edit Products')
            ->with('provinsi', $getProvince)
            ->with('getData', $getData)
            ->with('detailProvinsi', $getDetailProvinsi)
            ->with('detailKota', $getDetailKota)
            ->with('allKota', $getAllKotaFromProvince)
            ->with('dataUser', $dataUser);
    }

    public function postEditPurchase(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        if ($request->provinsi == 0) {
            return redirect()->route('adm_editPurchase', [$request->id])
                ->with('message', 'Provinsi harus dipilih')
                ->with('messageclass', 'danger');
        }
        $provinsiSearch = $modelMember->getProvinsiByID($request->provinsi);
        $provinsiName = $provinsiSearch->nama;
        $kota = 0;
        $kotaName = '';
        if ($request->kota != null) {
            if ($request->kota != 0) {
                $kotaSearch = $modelMember->getNamaByKode($request->kota);
                $kota = $kotaSearch->kabupatenkota;
                $kotaName = ' - ' . $kotaSearch->nama;
            }
        }
        $kecamatan = 0;
        $kecamatanName = '';
        if ($request->kecamatan != null) {
            if ($request->kecamatan != 0) {
                $kecamatanSearch = $modelMember->getNamaByKode($request->kecamatan);
                $kecamatan = $kecamatanSearch->kecamatan;
                $kecamatanName = ' - ' . $kecamatanSearch->nama;
            }
        }
        $kelurahan = 0;
        $kelurahanName = '';
        if ($request->kelurahan != null) {
            if ($request->kelurahan != 0) {
                $kelurahanSearch = $modelMember->getNamaByKode($request->kelurahan);
                $kelurahan = $kelurahanSearch->kelurahan;
                $kelurahanName = ' - ' . $kelurahanSearch->nama;
            }
        }
        $dataUpdate = array(
            'name' => $request->name,
            'ukuran' => $request->ukuran,
            'stockist_price' => $request->member_price,
            'member_price' => $request->member_price,
            'code' => $request->code,
            'image' => $request->image,
            'provinsi' => $request->provinsi,
            'kota' => $kota,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'area' => $provinsiName . ' ' . $kotaName . ' ' . $kecamatanName . ' ' . $kelurahanName
        );
        $modelSales->getUpdatePurchase('id', $request->id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listPurchases')
            ->with('message', 'Produk berhasil diedit')
            ->with('messageclass', 'success');
    }

    public function postRemovePurchase(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelSales->getUpdatePurchase('id', $request->id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listPurchases')
            ->with('message', 'Produk berhasil dihapus')
            ->with('messageclass', 'success');
    }

    public function getAllPenjualanReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllPenjualanReward();
        return view('admin.member.penjualan-reward')
            ->with('headerTitle', 'Claim Reward Penjualan')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getRowID = $request->id;
        if ($getRowID == null) {
            return redirect()->route('adm_listPenjualanReward')
                ->with('message', 'Gagal, tidak ada yang di centang')
                ->with('messageclass', 'danger');
        }
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listPenjualanReward')
            ->with('message', 'Konfirmasi Reward Penjualan berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listPenjualanReward')
            ->with('message', 'Data Reward Penjualan berhasil direject')
            ->with('messageclass', 'success');
    }

    public function getHistoryPenjualanReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminHistoryPenjualanReward();
        return view('admin.member.history-reward-penjualan')
            ->with('headerTitle', 'History Reward Penjualan')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postAdminChangeDataMember(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getCheck = $modelMember->getCheckUsercodeNotHim($request->username, $request->cekId);
        if ($getCheck->cekCode == 1) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Username sudah terpakai')
                ->with('messageclass', 'danger');
        }
        $getData = $modelMember->getUsers('id', $request->cekId);
        $full_name = null;
        $username = $request->username;
        $tron = $getData->tron;
        $is_tron = $getData->is_tron;
        if ($getData->full_name != null) {
            $full_name = $request->full_name;
        }
        if ($request->affiliate == 1) {
            $username = $modelMember->getCountNewKBBUserCode();
            $tron = 'TKrUoW4kfm2HVrAtpcW9sDBz4GmrbaJcBv';
            $is_tron = 1;
        }
        if ($request->affiliate == 2) {
            $tron = 'TSirYAN5YC4XfSHHNif62reLABUZ5FCA7L';
            $is_tron = 1;
        }
        $dataUpdate = array(
            'username' => $username,
            'email' => $request->email,
            'hp' => $request->hp,
            'tron' => $tron,
            'is_tron' => $is_tron,
            'affiliate' => $request->affiliate,
            'full_name' => $full_name
        );
        $modelMember->getUpdateUsers('id', $request->cekId, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/change/data/member user_id ' . $request->cekId
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMember')
            ->with('message', 'Berhasil')
            ->with('messageclass', 'success');
    }

    public function postAdminChangePasswordMember(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($request->password != $request->repassword) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Password dn ktik ulang password tidak sama')
                ->with('messageclass', 'danger');
        }
        if (strlen($request->password) < 6) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Password terlalu pendek, minimal 6 karakter')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $dataUpdatePass = array(
            'password' => bcrypt($request->password),
        );
        $modelMember->getUpdateUsers('id', $request->cekId, $dataUpdatePass);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/change/passwd/member user_id ' . $request->cekId
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMember')
            ->with('message', 'Password username ' . $request->username . ' berhasil diubah')
            ->with('messageclass', 'success');
    }

    public function postAdminChange2faMember(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($request->password != $request->repassword) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Pin dn ktik ulang password tidak sama')
                ->with('messageclass', 'danger');
        }
        if (strlen($request->password) < 4) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Pin terlalu pendek, minimal 4 karakter')
                ->with('messageclass', 'danger');
        }
        if (!is_numeric($request->password)) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Pin harus angka')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $dataUpdatePass = array(
            '2fa' => bcrypt($request->password),
        );
        $modelMember->getUpdateUsers('id', $request->cekId, $dataUpdatePass);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/change/2fa/member user_id ' . $request->cekId
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMember')
            ->with('message', 'Kode Pin 2FA dari ' . $request->username . ' berhasil diubah')
            ->with('messageclass', 'success');
    }

    public function postAdminChangeBlockMember(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $dataUpdate = array(
            'is_login' => 0,
        );
        $modelMember->getUpdateUsers('id', $request->cekId, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/change/block/member user_id ' . $request->cekId
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMember')
            ->with('message', 'Berhasil Blokir Member')
            ->with('messageclass', 'success');
    }

    public function postAdminChangeTronMember(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $dataUpdate = array(
            'tron' => $request->tron,
        );
        $modelMember->getUpdateUsers('id', $request->cekId, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'POST /adm/change/tron/member user_id ' . $request->cekId
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMember')
            ->with('message', 'Berhasil ubah tron Member')
            ->with('messageclass', 'success');
    }

    public function postSearchMember(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }

        $cekLenght = strlen($request->input);
        if ($cekLenght < 3) {
            return redirect()->route('adm_listMember')
                ->with('message', 'Minimal pencarian harus 3 karakter (huruf).')
                ->with('messageclass', 'danger');
        }
        if ($request->selector == 1) {
            $modelMember = new Member;
            $data = $modelMember->getSearchAllMemberByAdmin($request->input);
        }
        if ($request->selector == 2) {
            $modelMember = new Member;
            $data = $modelMember->getSearchAllMemberByTron($request->input);
        }

        $getData = $data->data;
        $getCountData = $data->total;
        return view('admin.member.list-member')
            ->with('headerTitle', 'Search Member')
            ->with('getData', $getData)
            ->with('getTotal', $getCountData)
            ->with('dataUser', $dataUser);
    }

    public function postSearchMemberByMonth(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
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
        $modelMember = new Member;
        $data = $modelMember->getSearchMemberByMonthByAdmin($getMonth);
        $getData = $data->data;
        $getCountData = $data->total;
        return view('admin.member.list-member')
            ->with('headerTitle', 'Search Member')
            ->with('getData', $getData)
            ->with('getTotal', $getCountData)
            ->with('dataUser', $dataUser);
    }

    public function postSearchMemberStockist(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $cekLenght = strlen($request->name);
        if ($cekLenght < 3) {
            return redirect()->route('adm_listMemberStockist')
                ->with('message', 'Minimal pencarian harus 3 karakter (huruf).')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $data = $modelMember->getSearchAllMemberStockistByAdmin($request->name);
        $getData = $data->data;
        $getCountData = $data->total;
        return view('admin.member.all-stockists')
            ->with('headerTitle', 'Search Member Stockist')
            ->with('getData', $getData)
            ->with('getTotal', $getCountData)
            ->with('dataUser', $dataUser);
    }

    public function postSearchMemberVendor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $cekLenght = strlen($request->name);
        if ($cekLenght < 3) {
            return redirect()->route('adm_listMemberStockist')
                ->with('message', 'Minimal pencarian harus 3 karakter (huruf).')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $data = $modelMember->getSearchAllMemberVendorByAdmin($request->name);
        $getData = $data->data;
        $getCountData = $data->total;
        return view('admin.member.all-vendor')
            ->with('headerTitle', 'Search Member Vendor')
            ->with('getData', $getData)
            ->with('getTotal', $getCountData)
            ->with('dataUser', $dataUser);
    }

    public function getAllWDRoyalti()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllRequestWDRoyalti();
        return view('admin.member.list-wd-royalti')
            ->with('headerTitle', 'Request Withdrawal Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckWDRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        if ($request->id == null) {
            return redirect()->route('adm_listWDRoyalti')
                ->with('message', 'tidak ada data yang dipilih')
                ->with('messageclass', 'danger');
        }
        $getRowID = $request->id;
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelWD->getUpdateWD('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listWDRoyalti')
            ->with('message', 'Konfirmasi Transfer WD Royalti berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectWDRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $getData = $modelWD->getIDRequestWDReject($getID);
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelWD->getUpdateWD('id', $getID, $dataUpdate);
        $redirect = 'adm_listWDRoyalti';
        $wd = 'WD Royalti';
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route($redirect)
            ->with('message', 'Data ' . $wd . ' ' . $getData->full_name . ' senilai Rp. ' . number_format($getData->wd_total + $getData->admin_fee, 0, ',', '.') . ' direject')
            ->with('messageclass', 'success');
    }

    public function getAllHistoryWDRoyalti()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllHistoryWDRoyalti();
        return view('admin.member.history-wd-royalti')
            ->with('headerTitle', 'History Withdrawal Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getHistoryRequestInputStock()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputStockistHistory();
        return view('admin.member.history-input-stock')
            ->with('headerTitle', 'History Stock & Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getMemberStockistStock($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getDataUser = $modelMember->getExplorerByID($id);
        $modelSales = new Sales;
        $data = $modelSales->getMemberPurchaseShoping($getDataUser->id);
        $getData = array();
        if ($data != null) {
            foreach ($data as $row) {
                $jml_keluar = $modelSales->getSumStock($getDataUser->id, $row->id);
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
                    'hapus' => $hapus,
                    'purchase_id' => $row->purchase_id,
                );
            }
        }
        return view('admin.member.stock-product')
            ->with('headerTitle', 'Stock Product')
            ->with('getData', $getData)
            ->with('getStockist', $getDataUser)
            ->with('dataUser', $dataUser);
    }

    public function postEditStock(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($request->jml_stock == null) {
            return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                ->with('message', 'Jumlah Stock harus diisi')
                ->with('messageclass', 'danger');
        }
        if ($request->jml_stock < 0) {
            return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                ->with('message', 'Jumlah Stock tidak boleh kurang dari 0')
                ->with('messageclass', 'danger');
        }
        $metode = $request->metode;
        $purchase_id = $request->purchase_id;
        $stockist_id = $request->stockist_id;
        $modelAdmin = new Admin;
        $modelSales = new Sales;
        $data = $modelSales->getStockByPurchaseIdStockist($stockist_id, $purchase_id);
        $jml_keluar = $modelSales->getSumStock($stockist_id, $data->id);
        // klo nambah maka ubah qty di table item_purchase dan table stock yg sales_id, stockist_id is null
        // klo kurang insert di stock isi stockist_id
        $total_sisa = $data->total_qty - $jml_keluar;
        $getLastItemPurchase = $modelSales->getLastItemPurchase($purchase_id, $stockist_id);
        if ($getLastItemPurchase == null) {
            return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                ->with('message', 'tidak ada data')
                ->with('messageclass', 'danger');
        }
        if ($metode == 1) {
            $idLastPurchase = $getLastItemPurchase->id;
            $tambahStock = $getLastItemPurchase->qty + $request->jml_stock;
            $dataUpdateItemPurchase = array(
                'qty' => $tambahStock
            );
            $modelSales->getUpdateItemPurchase('id', $idLastPurchase, $dataUpdateItemPurchase);
            $getLastStock = $modelSales->getLastStockID($purchase_id, $stockist_id);
            $dataUpdateStock = array(
                'amount' => $tambahStock
            );
            $modelSales->getUpdateStock('id', $getLastStock->id, $dataUpdateStock);
            $logHistory = array(
                'user_id' => $dataUser->id,
                'detail_log' => 'POST adm/edit-stock Tambah Stock'
            );
            $modelAdmin->getInsertLogHistory($logHistory);
            return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                ->with('message', 'berhasil tambah stock')
                ->with('messageclass', 'success');
        }
        if ($metode == 2) {
            if (($total_sisa - $request->jml_stock) < 0) {
                return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                    ->with('message', 'stock kurang dari 0')
                    ->with('messageclass', 'danger');
            }
            $dataInsertStock = array(
                'purchase_id' => $purchase_id,
                'user_id' => $stockist_id,
                'type' => 2,
                'amount' => $request->jml_stock,
                'stockist_id' => $stockist_id,
            );
            $modelSales->getInsertStock($dataInsertStock);
            $logHistory = array(
                'user_id' => $dataUser->id,
                'detail_log' => 'POST adm/edit-stock Kurang Stock'
            );
            $modelAdmin->getInsertLogHistory($logHistory);
            return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                ->with('message', 'berhasil kurang stock')
                ->with('messageclass', 'success');
        }
    }

    public function postRemoveStock(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $purchase_id = $request->purchase_id;
        $stockist_id = $request->stockist_id;
        $modelAdmin = new Admin;
        $modelSales = new Sales;
        $getAllItem = $modelSales->getAllItemPurchaseStockistPurchase($purchase_id, $stockist_id);
        if ($getAllItem != null) {
            foreach ($getAllItem as $row) {
                $dataUpdateItemPurchase = array(
                    'deleted_at' => date('Y-m-d H:i:s')
                );
                $modelSales->getUpdateItemPurchase('id', $row->id, $dataUpdateItemPurchase);
            }
            $logHistory = array(
                'user_id' => $dataUser->id,
                'detail_log' => 'POST adm/rm-stock Hapus Stock, Purchase ID ' . $purchase_id . ', Stockist ID ' . $stockist_id
            );
            $modelAdmin->getInsertLogHistory($logHistory);
            return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
                ->with('message', 'berhasil hapus stock')
                ->with('messageclass', 'success');
        }
        return redirect()->route('adm_memberStockistStock', [$request->stockist_id])
            ->with('message', 'tidak ada data')
            ->with('messageclass', 'danger');
    }

    public function getAllTopup()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAllRequestTopup();
        return view('admin.member.list-topup')
            ->with('headerTitle', 'Request Top Up Saldo')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckTopup(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($request->id == null) {
            return redirect()->route('adm_listTopup')
                ->with('message', 'tidak ada data yang dipilih')
                ->with('messageclass', 'danger');
        }
        $getRowID = $request->id;
        foreach ($getRowID as $getID) {
            ManualTopUpeIDRjob::dispatchNow(1, $getID);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listTopup')
            ->with('message', 'Konfirmasi Transfer Top Up berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectTopup(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $getData = $modelBonus->getTopUpSaldoID($getID);
        $dataUpdate = array(
            'status' => 3,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateTopUp('id', $getID, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listTopup')
            ->with('message', 'Data Top Up senilai Rp. ' . number_format($getData->nominal + $getData->unique_digit, 0, ',', '.') . ' direject')
            ->with('messageclass', 'success');
    }

    public function getAllHistoryTopup()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAllHistoryTopup();
        return view('admin.member.history-topup')
            ->with('headerTitle', 'History Top Up Saldo')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllVendorPurchase()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getAllPurchaseVendor();
        return view('admin.sales.all_purchase_vendor')
            ->with('headerTitle', 'All Product Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAddVendorPurchase()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $getProvince = $modelMember->getProvinsiNew();
        return view('admin.sales.add_vpurchase')
            ->with('headerTitle', 'Create Product Vendor')
            ->with('provinsi', $getProvince)
            ->with('dataUser', $dataUser);
    }

    public function postAddVendorPurchase(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        if ($request->provinsi == 0) {
            return redirect()->route('adm_addVendorPurchase')
                ->with('message', 'Anda Tidak memilih propinsi')
                ->with('messageclass', 'danger');
        }
        $provinsiSearch = $modelMember->getProvinsiByID($request->provinsi);
        $provinsiName = $provinsiSearch->nama;
        $kotaSearch = $modelMember->getKabByID($request->kota);
        $kotaName = $kotaSearch->nama;
        $qty = 200000;
        $dataInsert = array(
            'name' => $request->name,
            'ukuran' => $request->ukuran,
            'stockist_price' => $request->member_price,
            'member_price' => $request->member_price,
            'code' => $request->code,
            'image' => $request->image,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => 0,
            'kelurahan' => 0,
            'qty' => $qty,
            'area' => $provinsiName . ' ' . $kotaName,
            'type' => 2,
        );
        $getInsertPurchase = $modelSales->getInsertPurchase($dataInsert);
        //insert stock
        $dataInsertStock = array(
            'purchase_id' => $getInsertPurchase->lastID,
            'user_id' => $dataUser->id,
            'type' => 1,
            'amount' => $qty
        );
        $modelSales->getInsertVStock($dataInsertStock);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVendorPurchases')
            ->with('message', 'Produk Vendor berhasil ditambahkan')
            ->with('messageclass', 'success');
    }

    public function getEditVendorPurchase($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        $getData = $modelSales->getDetailPurchaseVendor($id);
        $getProvince = $modelMember->getProvinsi();
        $geCodeProvince = $getData->provinsi . '.00.00.0000';
        $cekLenght = strlen($getData->kota);
        $kota = $getData->kota;
        if ($cekLenght == 1) {
            $kota = '0' . $getData->kota;
        }
        $getCodeKota = $getData->provinsi . '.' . $kota . '.00.0000';
        $getDetailProvinsi = $modelMember->getNamaByKode($geCodeProvince);
        $getDetailKota = $modelMember->getNamaByKode($getCodeKota);
        $getAllKotaFromProvince = $modelMember->getKabupatenKotaByPropinsi($getData->provinsi);
        return view('admin.sales.edit_vpurchase')
            ->with('headerTitle', 'Edit Products Vendor')
            ->with('provinsi', $getProvince)
            ->with('getData', $getData)
            ->with('detailProvinsi', $getDetailProvinsi)
            ->with('detailKota', $getDetailKota)
            ->with('allKota', $getAllKotaFromProvince)
            ->with('dataUser', $dataUser);
    }

    public function postEditVendorPurchase(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $modelMember = new Member;
        if ($request->provinsi == 0) {
            return redirect()->route('adm_editVendorPurchase', [$request->id])
                ->with('message', 'Provinsi harus dipilih')
                ->with('messageclass', 'danger');
        }
        $provinsiSearch = $modelMember->getProvinsiByID($request->provinsi);
        $provinsiName = $provinsiSearch->nama;
        $kota = 0;
        $kotaName = '';
        if ($request->kota != null) {
            if ($request->kota != 0) {
                $kotaSearch = $modelMember->getNamaByKode($request->kota);
                $kota = $kotaSearch->kabupatenkota;
                $kotaName = ' - ' . $kotaSearch->nama;
            }
        }
        $kecamatan = 0;
        $kecamatanName = '';
        if ($request->kecamatan != null) {
            if ($request->kecamatan != 0) {
                $kecamatanSearch = $modelMember->getNamaByKode($request->kecamatan);
                $kecamatan = $kecamatanSearch->kecamatan;
                $kecamatanName = ' - ' . $kecamatanSearch->nama;
            }
        }
        $kelurahan = 0;
        $kelurahanName = '';
        if ($request->kelurahan != null) {
            if ($request->kelurahan != 0) {
                $kelurahanSearch = $modelMember->getNamaByKode($request->kelurahan);
                $kelurahan = $kelurahanSearch->kelurahan;
                $kelurahanName = ' - ' . $kelurahanSearch->nama;
            }
        }
        $dataUpdate = array(
            'name' => $request->name,
            'ukuran' => $request->ukuran,
            'stockist_price' => $request->member_price,
            'member_price' => $request->member_price,
            'code' => $request->code,
            'image' => $request->image,
            'provinsi' => $request->provinsi,
            'kota' => $kota,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'area' => $provinsiName . ' ' . $kotaName . ' ' . $kecamatanName . ' ' . $kelurahanName
        );
        $modelSales->getUpdatePurchase('id', $request->id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVendorPurchases')
            ->with('message', 'Produk Vendor berhasil diedit')
            ->with('messageclass', 'success');
    }

    public function postRemoveVendorPurchase(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelSales->getUpdatePurchase('id', $request->id, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVendorPurchases')
            ->with('message', 'Produk Vendor berhasil dihapus')
            ->with('messageclass', 'success');
    }

    public function getAllRequestMemberVendor()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getData = $modelMember->getAllMemberReqVendor();
        return view('admin.member.req-vendor')
            ->with('headerTitle', 'Request Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getHistoryRequestMemberVendor()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getData = $modelMember->getHistoryAllMemberReqVendor();
        return view('admin.member.history-req_vendor')
            ->with('headerTitle', 'History Request Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getHistoryRequestInputVStock()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputVStockistHistory();
        return view('admin.member.history-input-vstock')
            ->with('headerTitle', 'History Vendor Stock & Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllRequestMemberInputVStock()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputVendor();
        return view('admin.member.req-input-vstock')
            ->with('headerTitle', 'Input Vendor Stock & Royalti')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postRequestMemberInputVStock(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'status' => 2,
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelSales->getUpdateVendorItemPurchaseMaster('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listReqInputVStock')
            ->with('message', 'Konfirmasi Member request vendor input stock & royalti berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectMemberInputVStock(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSales = new Sales;
        $dataUpdate = array(
            'status' => 10,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
            'reason' => $request->reason
        );
        $modelSales->getUpdateVendorItemPurchaseMaster('id', $request->id, $dataUpdate);
        return redirect()->route('adm_listReqInputVStock')
            ->with('message', 'Reject Member request input stock & royalti berhasil')
            ->with('messageclass', 'success');
    }

    public function getMemberVendorStock($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getDataUser = $modelMember->getExplorerByID($id);
        $modelSales = new Sales;
        $data = $modelSales->getMemberPurchaseVendorShoping($getDataUser->id);
        $getData = array();
        if ($data != null) {
            foreach ($data as $row) {
                $jml_keluar = $modelSales->getSumStockVendor($getDataUser->id, $row->id);
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
                    'hapus' => $hapus,
                    'purchase_id' => $row->purchase_id,
                );
            }
        }
        return view('admin.member.stock-vproduct')
            ->with('headerTitle', 'Stock Vendor Product')
            ->with('getData', $getData)
            ->with('getStockist', $getDataUser)
            ->with('dataUser', $dataUser);
    }

    public function postRemoveMemberVendor(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $date =  date('Y-m-d H:i:s');
        $dataUpdate = array(
            'status' => 10,
            'deleted_at' => $date,
            'submit_by' => $dataUser->id,
            'submit_at' => $date,
        );
        $modelMember->getUpdateVendor('id', $request->id, $dataUpdate);
        $dataUpdateUser = array(
            'is_vendor' => 0,
        );
        $modelMember->getUpdateUsers('id', $request->id_user, $dataUpdateUser);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'hapus member vendor'
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listMemberVendor')
            ->with('message', 'vendor ' . $request->username . ' dihapus kememberannya')
            ->with('messageclass', 'success');
    }

    public function getAllVPenjualanReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllVendorPenjualanReward();
        return view('admin.member.vpenjualan-reward')
            ->with('headerTitle', 'Claim Reward Penjualan Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckVPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getRowID = $request->id;
        if ($getRowID == null) {
            return redirect()->route('adm_listPenjualanReward')
                ->with('message', 'Gagal, tidak ada yang di centang')
                ->with('messageclass', 'danger');
        }
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVPenjualanReward')
            ->with('message', 'Konfirmasi Reward Penjualan Vendor berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectVPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVPenjualanReward')
            ->with('message', 'Data Reward Penjualan Vendor berhasil direject')
            ->with('messageclass', 'success');
    }

    public function getHistoryVPenjualanReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminHistoryVPenjualanReward();
        return view('admin.member.history-reward-vpenjualan')
            ->with('headerTitle', 'History Reward Penjualan Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllVBelanjaReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllVendorBelanjaReward();
        return view('admin.member.vbelanja-reward')
            ->with('headerTitle', 'Claim Reward Belanja Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postCheckVBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getRowID = $request->id;
        if ($getRowID == null) {
            return redirect()->route('adm_listVBelanjaReward')
                ->with('message', 'Gagal, tidak ada yang di centang')
                ->with('messageclass', 'danger');
        }
        foreach ($getRowID as $getID) {
            $dataUpdate = array(
                'status' => 1,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        }
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVBelanjaReward')
            ->with('message', 'Konfirmasi Reward Belanja Vendor berhasil')
            ->with('messageclass', 'success');
    }

    public function postRejectVBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getID = $request->cekId;
        $alesan = $request->reason;
        $dataUpdate = array(
            'status' => 2,
            'reason' => $alesan,
            'deleted_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelBonus->getUpdateBelanjaReward('id', $getID, $dataUpdate);
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => $request->method() . ' ' . $request->path()
        );
        $modelAdmin->getInsertLogHistory($logHistory);
        return redirect()->route('adm_listVBelanjaReward')
            ->with('message', 'Data Reward Belanja Vendor berhasil direject')
            ->with('messageclass', 'success');
    }

    public function getHistoryVBelanjaReward()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminHistoryVBelanjaReward();
        return view('admin.member.history-reward-vbelanja')
            ->with('headerTitle', 'History Reward Belanja Vendor')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAllRequestIsiDeposit()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        //get Saldo Digiflazz
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username . $apiKey . 'depo');
        $array = array(
            'cmd' => 'deposit',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/cek-saldo';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayResult = json_decode($cek, true);
        $saldoDigiflazz = $arrayResult['data']['deposit'];

        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getTotalDeposit = $modelPin->getTotalDepositAll();
        $getAllTransaction = $modelTrans->getTransactionsIsiDepositByAdmin();
        $saldoFly = $modelPin->getPPOBFlyAll();
        $requestTarik = 0;
        if ($modelTrans->getAllRequestTarikDeposit() != null) {
            $cekRequestTarik = $modelTrans->getAllRequestTarikDeposit();
            $requestTarik = $cekRequestTarik->total_request_tarik;
        };
        return view('admin.digital.list-req-deposit')
            ->with('headerTitle', 'Transaksi Isi Deposit')
            ->with('getData', $getAllTransaction)
            ->with('requestTarik', $requestTarik)
            ->with('localDeposit', $getTotalDeposit)
            ->with('saldoGantung', $saldoFly->deposit_out)
            ->with('saldoDigiflazz', $saldoDigiflazz)
            ->with('dataUser', $dataUser);
    }

    public function postConfirmTransactionIsiDeposit(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $id = $request->cekId;
        $user_id = $request->cekMemberId;
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailDepositTransactionsAdmin($id, $user_id);
        if ($getData == null) {
            return redirect()->route('adm_listIsiDeposit')
                ->with('message', 'Data tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        $memberDeposit = array(
            'user_id' => $user_id,
            'total_deposito' => $getData->price,
            'transaction_code' => $getData->transaction_code,
        );
        $modelPin = new Pin;
        $modelPin->getInsertMemberDeposit($memberDeposit);
        $dataUpdate = array(
            'status' => 2,
            'tuntas_at' => date('Y-m-d H:i:s'),
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelSettingTrans->getUpdateDepositTransaction('id', $id, $dataUpdate);
        return redirect()->route('adm_listIsiDeposit')
            ->with('message', 'Berhasil konfirmasi isi deposit')
            ->with('messageclass', 'success');
    }

    public function postRejectTransactionIsiDeposit(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $id = $request->cekId;
        $user_id = $request->cekMemberId;
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailRejectDepositTransactionsAdminByID($id, $user_id);
        if ($getData == null) {
            return redirect()->route('adm_listIsiDeposit')
                ->with('message', 'Data tidak ditemukan')
                ->with('messageclass', 'danger');
        }
        $dataUpdate = array(
            'status' => 3,
            'deleted_at' => date('Y-m-d H:i:s'),
            'reason' => $request->reason,
            'submit_by' => $dataUser->id,
            'submit_at' => date('Y-m-d H:i:s'),
        );
        $modelSettingTrans->getUpdateDepositTransaction('id', $id, $dataUpdate);
        return redirect()->route('adm_listIsiDeposit')
            ->with('message', 'Transaksi dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getAllRequestTarikDeposit()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelSettingTrans = new Transaction;
        $getAllTransaction = $modelSettingTrans->getTransactionsTarikDepositByAdmin();
        return view('admin.digital.list-tarik-deposit')
            ->with('headerTitle', 'Transaksi Tarik Deposit')
            ->with('getData', $getAllTransaction)
            ->with('dataUser', $dataUser);
    }

    public function postConfirmTransactionTarikDeposit(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $getRowID = $request->id;
        $modelSettingTrans = new Transaction;
        foreach ($getRowID as $getID) {
            $getData = $modelSettingTrans->getTransactionsTarikDepositByAdminByID($getID);
            $memberDeposit = array(
                'user_id' => $getData->user_id,
                'total_deposito' => $getData->price,
                'transaction_code' => $getData->transaction_code,
                'deposito_status' => 1
            );
            $modelPin = new Pin;
            $modelPin->getInsertMemberDeposit($memberDeposit);
            $dataUpdate = array(
                'status' => 2,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'submit_by' => $dataUser->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelSettingTrans->getUpdateDepositTransaction('id', $getID, $dataUpdate);
        }
        return redirect()->route('adm_listTarikDeposit')
            ->with('message', 'Berhasil konfirmasi tarik deposit')
            ->with('messageclass', 'success');
    }

    public function getTransferSystemDeposit()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        return view('admin.digital.transfer-deposit')
            ->with('headerTitle', 'Transfer System Deposit')
            ->with('dataUser', $dataUser);
    }

    public function postTransferSystemDeposit(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $getCode = $modelPin->getCodeTransactionSystem();
        $dataInsert = array(
            'total_deposit' => $request->total_deposit,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $request->account_name,
            'type_deposit' => 1,
            'code' => $getCode
        );
        $modelPin->getInsertMasterDeposit($dataInsert);
        //kirim email
        $dataEmail = array(
            'tgl_order' => date('d F Y'),
            'total_deposit' => 'Rp ' . number_format($request->total_deposit, 0, ',', '.'),
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $request->account_name,
            'code' => $getCode,
            'dataLink' => 'approve/deposit/' . $getCode,
        );
        $emailSend = 'bernaandya@gmail.com';
        Mail::send('member.email.transfer_deposit', $dataEmail, function ($message) use ($emailSend) {
            $message->to($emailSend, 'Konfirmasi Transfer Deposit Lumbung Network')
                ->subject('Konfirmasi Transfer Deposit Lumbung Network');
        });

        return redirect()->route('adm_listIsiDeposit')
            ->with('message', 'Berhasil konfirmasi transfer system deposit')
            ->with('messageclass', 'success');
    }

    public function getAllTransactionDeposit()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelTrans = new Transaction;
        $getAllTransaction = $modelTrans->getHistoryTransactionsDepositByAdmin();
        //        dd($getAllTransaction);
        return view('admin.digital.history-transaksi-deposit')
            ->with('headerTitle', 'History Transaksi Deposit')
            ->with('getAllTransaction', $getAllTransaction)
            ->with('dataUser', $dataUser);
    }

    public function getListVendorPPOBTransactionsEiDR()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $getData = $modelPin->getAdminTransactionPPOBEiDR();
        //        dd($getData);
        return view('admin.digital.transaksi-eidr')
            ->with('headerTitle', 'Transaksi eIDR')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function geDetailVendorPPOBTransactionsEiDR($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $getData = $modelPin->getAdminDetailTransactionPPOBEiDR($id);
        //        dd($getData);
        return view('admin.digital.detail-transaksi-eidr')
            ->with('headerTitle', 'Detail Transaksi eIDR')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function postApprovePPOBTransactionsEiDR(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $modelMember = new Member;
        //cek deposit cukup ga
        $modelTrans = new Transaction;
        $getDataMaster = $modelPin->getAdminDetailTransactionPPOBEiDR($request->ppob_id);
        //        $dataVendor =  $modelMember->getUsers('id', $getDataMaster->vendor_id);
        //        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataVendor);
        //        $getTotalDeposit = $modelPin->getTotalDepositMember($dataVendor);
        //        $sum_deposit_masuk = 0;
        //        $sum_deposit_keluar1 = 0;
        //        $sum_deposit_keluar = 0;
        //        if($getTotalDeposit->sum_deposit_masuk != null){
        //            $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
        //        }
        //        if($getTotalDeposit->sum_deposit_keluar != null){
        //            $sum_deposit_keluar1 = $getTotalDeposit->sum_deposit_keluar;
        //        }
        //        if($getTransTarik->deposit_keluar != null){
        //            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        //        }
        //        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1 - $getDataMaster->harga_modal;
        //        if($totalDeposit < 0){
        //            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
        //                    ->with('message', 'tidak dapat dilanjutkan, deposit kurang')
        //                    ->with('messageclass', 'danger');
        //        }
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
            //            $cekDuaKali = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code.'-'.$ref_id);
            //            if($cekDuaKali == null){
            //                $memberDeposit = array(
            //                    'user_id' => $dataVendor->id,
            //                    'total_deposito' => $getDataMaster->harga_modal, //tambahin 2%
            //                    'transaction_code' => $getDataMaster->buyer_code.'-'.$ref_id,
            //                    'deposito_status' => 1
            //                );
            //                $modelPin->getInsertMemberDeposit($memberDeposit);
            //            }
            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
                ->with('message', 'transaksi berhasil')
                ->with('messageclass', 'success');
        }

        if ($arrayData['data']['status'] == 'Pending') {
            $dataUpdate = array(
                'vendor_cek' => $cek
            );
            $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
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
            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
                ->with('message', 'transaksi gagal')
                ->with('messageclass', 'danger');
        }

        return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
            ->with('message', 'tidak ada data')
            ->with('messageclass', 'danger');
    }

    public function postRejectPPOBTransactionsEiDR(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $dataUpdate = array(
            'status' => 3,
            'reason' => $request->reason,
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
        return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
            ->with('message', 'Transaksi  dibatalkan')
            ->with('messageclass', 'success');
    }

    public function getPPOBTransactionsAPI($id)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1, 2, 3);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $getData = $modelPin->getAdminDetailTransactionPPOBEiDR($id);
        $modelTrans = new Transaction;
        $modelMember = new Member;
        $dataVendor =  $modelMember->getUsers('id', $getData->vendor_id);
        $getDataMaster = $modelPin->getVendorPPOBDetail($id, $dataVendor);
        if ($getDataMaster == null) {
            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
                ->with('message', 'Tidak ada data')
                ->with('messageclass', 'danger');
        }
        if ($getDataMaster->status != 2) {
            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
                ->with('message', 'transaksi vendor belum tuntas')
                ->with('messageclass', 'danger');
        }
        if ($getDataMaster->vendor_approve != 0) {
            return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
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
                        'user_id' => $dataVendor->id,
                        'total_deposito' => $getDataMaster->harga_modal,
                        'transaction_code' => $getDataMaster->buyer_code . '-' . $getDataMaster->ppob_code,
                        'deposito_status' => 1
                    );
                    $modelPin->getInsertMemberDeposit($memberDeposit);
                }
                return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
                    ->with('message', 'transaksi berhasil')
                    ->with('messageclass', 'success');
            }

            if ($arrayData['data']['status'] == 'Pending') {
                return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
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
                return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
                    ->with('message', 'terjadi kesalahan pada transaksi, saldo dikembalikan')
                    ->with('messageclass', 'success');
            }
        }
        return redirect()->route('adm_listVendotPPOBTransactionsEiDR')
            ->with('message', 'tidak ada data transaksi, kesalahan pada api')
            ->with('messageclass', 'danger');
    }





    //PPOB

    public function getMemberTestingCheckSaldo()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username . $apiKey . 'depo');
        $array = array(
            'cmd' => 'deposit',
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/cek-saldo';
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        dd($arrayData);
    }

    public function getMemberTestingCheckDaftarPulsa($cmd)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;

        $sign = md5($username . $apiKey . 'pricelist');
        $array = array(
            'cmd' => $cmd,
            'username' => $username,
            'sign' => $sign
        );
        $json = json_encode($array);
        $url = $getDataAPI->master_url . '/v1/price-list';
        // category => pulsa
        // brand => TELKOMSEL
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        dd($arrayData);
    }

    public function getMemberTestingCheckTopupPulsa($buyer, $hp)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $modelPin = new Pin;
        $rand = rand(11, 99);
        $ref_id = uniqid(); //$rand.$modelPin->getCodePPOBRef(1);
        $sign = md5($username . $apiKey . $ref_id);
        $array = array(
            'username' => $username,
            'buyer_sku_code' => $buyer,
            'customer_no' => $hp,
            'ref_id' => $ref_id,
            'sign' => $sign,
            //            'testing' => true,
            //            'msg' => 'testing dengan admin'
        );
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);

        if ($arrayData['data']['rc'] == '00') {
            $dataUpdate = array(
                'status' => 2,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'return_buy' => $cek,
                'vendor_approve' => 2,
                'potong_saldo' => 1,
                'digiflazz_status' => 'Tuntas',
                'buyer_sku_code' => $buyer,
                'customer_no' => $hp,
            );
            dd($dataUpdate);
        }

        if ($arrayData['data']['rc'] == '03') {
            $dataUpdate = array(
                'vendor_approve' => 1,
                'vendor_cek' => $cek,
                'potong_saldo' => 0,
                'digiflazz_status' => 'Pending',
                'buyer_sku_code' => $buyer,
                'customer_no' => $hp,
            );
            dd($dataUpdate);
        }

        $dataUpdate = array(
            'vendor_approve' => 3,
            'vendor_cek' => $cek,
            'potong_saldo' => 0,
            'digiflazz_status' => 'Gagal',
            'buyer_sku_code' => $buyer,
            'customer_no' => $hp,
        );
        dd($dataUpdate);
    }

    public function getMemberTestingCheckStatus($id, $cek)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $getData = $modelPin->getStatusPPOBDetail($id);
        $ref_id = uniqid(); // $getData->ppob_code;
        $sign = md5($username . $apiKey . $ref_id);
        $array = array(
            'commands' => 'inq-pasca',
            'username' => $username,
            'buyer_sku_code' => 'BPJS', //$getData->buyer_code,
            'customer_no' => '0001772358478', //$getData->product_name,
            'ref_id' => $ref_id,
            'sign' => $sign,
            //            'testing' => true,
            //            'msg' => 'testing dengan admin'
        );
        if ($cek == 1) {
            dd($array);
        }
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);

        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        dd($arrayData);
    }

    public function getMemberNewTestingCheckStatus(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getDataMaster = $modelPin->getStatusPPOBDetail($request->id);
        if ($getDataMaster == null) {
            dd('empty');
        }
        if ($getDataMaster->status != 2) {
            dd('stop here');
        }
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username . $apiKey . $getDataMaster->ppob_code);
        $array = array(
            'username' => $username,
            'buyer_sku_code' => $getDataMaster->buyer_code,
            'customer_no' => $getDataMaster->product_name,
            'ref_id' => $getDataMaster->ppob_code,
            'sign' => $sign,
        );
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        dd($arrayData);
    }

    public function getUpdateTransaction($id, $lihat)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(1);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        $modelMember = new Member;
        $modelPin = new Pin;
        $getDataMaster = $modelPin->getStatusPPOBDetail($id);
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
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => 'BPJS',
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 5) {
            $array = array(
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 6) {
            $array = array(
                'commands' => 'status-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        if ($getDataMaster->type == 7) {
            $array = array(
                'commands' => 'status-pasca',
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
        if ($lihat == 1) {
            dd($arrayData);
        }
        if ($arrayData['data']['status'] == 'Sukses') {
            $dataUpdate1 = array(
                'status' => 2,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'return_buy' => $cek,
                'vendor_approve' => 2
            );
            $modelPin->getUpdatePPOB('id', $getDataMaster->id, $dataUpdate1);
            $cekDuaKali = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code . '-' . $ref_id);
            if ($cekDuaKali == null) {
                $memberDeposit = array(
                    'user_id' => $getDataMaster->vendor_id,
                    'total_deposito' => $getDataMaster->harga_modal,
                    'transaction_code' => $getDataMaster->buyer_code . '-' . $ref_id,
                    'deposito_status' => 1
                );
                $modelPin->getInsertMemberDeposit($memberDeposit);
            }
            dd('done. Transaksi sukses, update berhasil');
        }

        if ($arrayData['data']['status'] == 'Pending') {
            $dataUpdate = array(
                'status' => 1,
                'tuntas_at' => null,
                'deleted_at' => null,
                'return_buy' => null,
                'vendor_approve' => 0,
                'vendor_cek' => $cek,
            );
            $modelPin->getUpdatePPOB('id', $getDataMaster->id, $dataUpdate);
            dd('done. Transaksi pending, update berhasil');
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
            $cekDepositGagal = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code . '-' . $ref_id);
            if ($cekDepositGagal != null) {
                $modelPin->getDeleteMemberDeposit($cekDepositGagal->id);
            }
            dd('done. Transaksi gagal, update berhasil');
        }
    }
}
