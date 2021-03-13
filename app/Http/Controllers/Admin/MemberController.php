<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Model\Member;
use App\Model\Pinsetting;
use App\Model\Masterpin;
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
use App\Product;
use App\Category;
use App\User;
use App\ProductImages;
use App\SellerProfile;
use App\LocalWallet;
use App\Ppob;
use App\Services\AbstractService;
use App\ValueObjects\Cart\ItemObject;
use Intervention\Image\ImageManager;
use GuzzleHttp\Client;
use RealRashid\SweetAlert\Facades\Alert;
use App\Jobs\SendRegistrationEmailJob;
use App\Jobs\PPOBAutoCancelJob;
use App\Jobs\ProcessRequestToDelegatesJob;
use Throwable;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Http;


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
                ->with('message', 'Data profil anda belum ada, silakan isi data profil anda')
                ->with('messageclass', 'warning');
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
        $getProvince = $modelMember->getProvinsiNew();
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
        $tron = null;
        $is_tron = 0;
        $is_stockist = 0;
        $stockist_at = null;
        if ($request->affiliate == 1) {
            $tron = 'TKrUoW4kfm2HVrAtpcW9sDBz4GmrbaJcBv';
            $is_tron = 1;
            $is_stockist = 1;
            $stockist_at = date('Y-m-d H:i:s');
        }
        if ($request->affiliate == 2) {
            $tron = 'TSirYAN5YC4XfSHHNif62reLABUZ5FCA7L';
            $is_tron = 1;
        }
        $dataInsertNewMember = array(
            'name' => $request->user_code,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'tron' => $tron,
            'is_tron' => $is_tron,
            'user_code' => $request->user_code,
            'affiliate' => $request->affiliate,
            'sponsor_id' => $sponsor_id,
            'is_stockist' => $is_stockist,
            'stockist_at' => $stockist_at
        );
        $modelMember->getInsertUsers($dataInsertNewMember);

        $dataEmail = array(
            'email' => $request->email,
            'password' => $request->password,
            'hp' => $request->hp,
            'user_code' => $request->user_code
        );
        $emailSend = $request->email;
        SendRegistrationEmailJob::dispatch($dataEmail, $emailSend)->onQueue('mail');
        Alert::success('Berhasil', 'Registrasi Member baru ' . $request->user_code . ' telah berhasil!')->persistent(true);
        return redirect()->back();
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
            return redirect()->route('mainDashboard');
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
            Alert::error('Gagal!', 'Tidak Cukup PIN untuk Mengaktivasi Akun Member Baru');
            return redirect()->route('m_detailOrderPackage', $getData->id);
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
            'expired_at' => date('Y-m-d H:i:s', strtotime('+ 365 days', strtotime('now'))),
            'member_type' => $getData->package_id
        );
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $getData->request_user_id, $dataUpdateIsActive);
        $total_sponsor = $dataUser->total_sponsor + 1;
        $dataUpdateSponsor = array(
            'total_sponsor' => $total_sponsor,
        );
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdateSponsor);
        Alert::success('Berhasil!', 'Akun Member telah Aktif, silakan melakukan Placement di Binary Tree')->persistent(true);
        return redirect()->route('m_addPlacement');
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

        $newUser = User::where('id', $getData->request_user_id)->where('is_active', 0)->first();
        if ($newUser != null) {
            $newUser->delete();
        }
        Alert::success('Berhasil', 'Aktivasi Akun Member Telah Ditolak');
        return redirect()->route('mainDashboard');
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
        $rand = rand(49, 99);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'transaction_code' => 'TR' . date('Ymd') . $dataUser->id . $code,
            'total_pin' => $request->total_pin,
            'price' => $harga,
            'unique_digit' => $rand,
        );
        $getIDTrans = $modelSettingTrans->getInsertTransaction($dataInsert);
        return redirect()->route('m_addTransaction', [$getIDTrans->lastID])
            ->with('message', 'Silakan pilih metode pembayaran anda')
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
            ->with('headerTitle', 'List Transaksi PIN')
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
            ->with('headerTitle', 'Konfirmasi Pembelian PIN')
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

    public function postAddTransactionTron(Request $request)
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
        $modelSettingPin = new Pinsetting;
        $modelPin = new Pin;
        $modelMasterPin = new Masterpin;
        $id_trans = $request->id_trans;
        $getTrans = $modelSettingTrans->getDetailTransactionsMember($id_trans, $dataUser);

        $hash = $request->hash;
        $sender = $request->sender;
        $amount = $getTrans->price + $getTrans->unique_digit;
        $timestamp = strtotime($getTrans->created_at);

        if (strlen($hash) != 64) {
            return redirect()->back()
                ->with('message', 'Hash Transaksi Salah atau Typo!')
                ->with('messageclass', 'danger');
        }

        $tron = $this->getTron();
        $i = 1;
        do {
            try {
                sleep(1);
                $response = $tron->getTransaction($hash);
            } catch (TronException $e) {
                $i++;
                continue;
            }
            break;
        } while ($i < 23);

        if ($i == 23) {
            Alert::error('Gagal', 'Hash Transaksi Bermasalah!');
            return redirect()->back();
        };

        $hashTime = $response['raw_data']['timestamp'];
        $hashSender = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['owner_address']);
        $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
        $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
        $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

        if ($hashTime > $timestamp) {
            if ($hashAmount / 100 == $amount) {
                if ($hashAsset == '1002652') {
                    if ($hashReceiver == 'TDtvo2jCoRftmRgzjkwMxekh8jqWLdDHNB') {
                        if ($hashSender == $sender) {

                            $getPinSetting = $modelSettingPin->getActivePinSetting();
                            $memberPin = array(
                                'user_id' => $getTrans->user_id,
                                'total_pin' => $getTrans->total_pin,
                                'setting_pin' => $getPinSetting->id,
                                'transaction_code' => $getTrans->transaction_code,
                                'pin_code' => 'P' . date('Ymd') . $getTrans->user_id
                            );
                            $modelPin->getInsertMemberPin($memberPin);

                            $dataInsertMasterPin = array(
                                'total_pin' => $getTrans->total_pin,
                                'type_pin' => 2,
                                'transaction_code' => $getTrans->transaction_code,
                                'reason' => "Member Buy (Autoconfirm)"
                            );
                            $modelMasterPin->getInsertMasterPin($dataInsertMasterPin);

                            $dataUpdate = array(
                                'status' => 2,
                                'tuntas_at' => date('Y-m-d H:i:s'),
                                'submit_by' => 1,
                                'submit_at' => date('Y-m-d H:i:s'),
                                'bank_perusahaan_id' => 9,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'is_tron' => 1
                            );
                            $modelSettingTrans->getUpdateTransaction('id', $id_trans, $dataUpdate);
                            return redirect()->route('m_listTransactions')
                                ->with('message', 'Pembelian PIN berhasil')
                                ->with('messageclass', 'success');
                        } else {
                            return redirect()->route('m_listTransactions')
                                ->with('message', 'Bukan Pengirim Yang Sebenarnya!')
                                ->with('messageclass', 'danger');
                        }
                    } else {
                        return redirect()->route('m_listTransactions')
                            ->with('message', 'Alamat Tujuan Transfer Salah!')
                            ->with('messageclass', 'danger');
                    }
                } else {
                    return redirect()->route('m_listTransactions')
                        ->with('message', 'Bukan Token eIDR yang benar!')
                        ->with('messageclass', 'danger');
                }
            } else {
                return redirect()->route('m_listTransactions')
                    ->with('message', 'Nominal Transfer Salah!')
                    ->with('messageclass', 'danger');
            }
        } else {
            return redirect()->route('m_listTransactions')
                ->with('message', 'Hash sudah terpakai!')
                ->with('messageclass', 'danger');
        }
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
            'expired_at' => date('Y-m-d H:i:s', strtotime('+ 365 days', strtotime('now'))),
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
            Alert::warning('Oops!', 'Alamat TRON anda masih kosong, silakan mengisi dan menautkan alamat TRON anda');
            return redirect()->route('m_newTron');
        }
        $modelMember = new Member;
        $delegates = $modelMember->getDelegates();
        $reset = $modelMember->getResetTronRequest('user_id', $dataUser->id);
        return view('member.profile.my-tron')
            ->with('headerTitle', 'Tron')
            ->with('delegates', $delegates)
            ->with('reset', $reset)
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
        Alert::success('Berhasil!', 'Alamat TRON telah ditautkan dengan akun ini');
        return redirect()->route('m_myTron');
    }

    public function postResetTron(Request $request)
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
            return redirect()->route('m_myTron');
        }
        if ($dataUser->affiliate == 2) {
            Alert::error('Gagal!', 'Akun KBB-Pasif tidak bisa melakukan Reset');
            return redirect()->back();
        }
        $modelMember = new Member;
        $data = [
            'user_id' => $dataUser->id,
            'username' => $dataUser->user_code,
            'delegate' => $request->delegate,
            'old_address' => $dataUser->tron
        ];

        $request = $modelMember->getInsertResetTron($data);
        ProcessRequestToDelegatesJob::dispatch(3, $request->lastID)->onQueue('tron');

        Alert::success('Berhasil', 'Pengajuan Reset alamat TRON telah diajukan ke delegasi. Silakan menghubungi Delegasi anda');
        return redirect()->route('m_myTron');
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
            return redirect()->route('m_myProfile')
                ->with('message', 'Silakan mengisi data profile anda terlebih dahulu')
                ->with('messageclass', 'warning');
        }
        if ($dataUser->is_stockist == 1 || $dataUser->is_vendor == 1) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Anda sudah menjadi stockist atau vendor')
                ->with('messageclass', 'danger');
        }
        $modelMember = new Member;
        $cekRequestStockist = $modelMember->getCekRequestSotckist($dataUser->id);
        if ($cekRequestStockist != null) {
            return redirect()->route('m_SearchStockist')
                ->with('message', 'Anda sudah pernah mengajukan menjadi stockist')
                ->with('messageclass', 'danger');
        }
        $delegates = $modelMember->getDelegates();
        return view('member.profile.add-stockist')
            ->with('headerTitle', 'Aplikasi Pengajuan Stockist')
            ->with('delegates', $delegates)
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
            return redirect()->route('m_myProfile')
                ->with('message', 'Silakan mengisi data profile anda terlebih dahulu')
                ->with('messageclass', 'warning');
        }
        $modelMember = new Member;
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'usernames' => $dataUser->user_code . ' ' . $request->username2 . ' ' . $request->username3,
            'delegate' => $request->delegate
        );
        $sendRequest = $modelMember->getInsertStockist($dataInsert);
        ProcessRequestToDelegatesJob::dispatch(1, $sendRequest->lastID)->onQueue('tron');
        Alert::success('Berhasil', 'Aplikasi Pengajuan Stockist telah diajukan ke Tim Delegasi');
        return redirect()->route('m_SearchStockist');
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

        if ($dataUser->kode_daerah == null) {
            Alert::warning('Oops', 'Anda perlu memperbarui data alamat anda sebelum berbelanja');
            return redirect()->route('m_myProfile');
        } else {
            if (strpos($dataUser->kode_daerah, ".") !== false) {
                Alert::warning('Oops', 'Anda perlu memperbarui data alamat anda sebelum berbelanja');
                return redirect()->route('m_editAddress');
            }
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
            ->with('message', 'Silakan pilih metode pembayaran anda, lalu Konfirmasi')
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
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $localAddress = null;
        $eIDRbalance = null;
        if ($localWallet != null) {
            if ($localWallet->is_active == 1) {
                $tron = $this->getTron();
                $localAddress = $localWallet->address;
                $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;
            }
        }
        $getDataItem = $modelSales->getMemberPembayaranSalesNew($id);
        return view('member.sales.stockist_confirm_payment')
            ->with('headerTitle', 'Konfirmasi Pembayaran Tunai')
            ->with('getDataSales', $getDataSales)
            ->with('getDataItem', $getDataItem)
            ->with('localAddress', $localAddress)
            ->with('eIDRbalance', $eIDRbalance)
            ->with('dataUser', $dataUser);
    }

    public function printShoppingReceipt($id)
    {
        $dataUser = Auth::user();
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
        }

        $type = 1;

        if ($dataUser->is_vendor == 1) {
            $type = 2;
        }

        $modelSales = new Sales;

        if ($type == 1) {
            $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id, $dataUser->id);
            $getDataItem = $modelSales->getMemberPembayaranSalesNew($id);
        } else {
            $getDataSales = $modelSales->getMemberReportSalesVendorDetail($id, $dataUser->id);
            $getDataItem = $modelSales->getMemberPembayaranVSalesNew($id);
        }

        $shopName = '';
        $sellerProfile = SellerProfile::where('seller_id', $dataUser->id)->select('shop_name')->first();
        $shopName .= $sellerProfile->shop_name;

        return view('member.digital.m_invoice_physical_products')
            ->with('dataSales', $getDataSales)
            ->with('shopName', $shopName)
            ->with('dataItem', $getDataItem);
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
        $getProvince = $modelMember->getProvinsiNew();
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

    //legacy (claim old royalti)
    public function postClaimOldRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        $type = 1;
        if ($dataUser->is_vendor == 1) {
            $type = 2;
        }

        $modelSales = new Sales;
        $clearStock = $modelSales->postDeleteOldStock($type, $dataUser->id);

        if ($clearStock) {
            $fuse = Config::get('services.telegram.test');
            $tron = $this->getTron();
            $tron->setPrivateKey($fuse);
            $amount = $request->amount * 100;
            $to = $dataUser->tron;
            $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
            $tokenID = '1002652';

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                Alert::success('Berhasil!', 'Sisa Royalti telah dikembalikan ke alamat TRON utama');
                return redirect()->route('m_SellerInventory');
            } else {
                Alert::error('Gagal!', 'Ada yang salah!');
                return redirect()->route('m_SellerInventory');
            }
        } else {
            Alert::error('Gagal!', 'Ada yang salah!');
            return redirect()->route('m_SellerInventory');
        }
    }

    //new shopping methods
    public function postSettlement(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }

        \Cart::session($dataUser->id)->clear();

        return redirect()->route('m_ShoppingPayment', [$request->masterSalesID, $request->sellerType]);
    }

    public function getShoppingPayment($masterSalesID, $sellerType)
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

        if ($sellerType == 1) {
            $getDataMaster = $modelSales->getMemberPembayaranMasterSales($masterSalesID);

            $getDataSales = $modelSales->getMemberPembayaranSalesNew($getDataMaster->id);
            $getStockist = $dataUser;
            if ($dataUser->id != $getDataMaster->stockist_id) {
                $getStockist = $modelMember->getUsers('id', $getDataMaster->stockist_id);
                if ($getStockist->is_stockist == null) {
                    return redirect()->route('mainDashboard');
                }
            }
            $sellerProfile = SellerProfile::where('seller_id', $getStockist->id)->first();

            return view('member.sales.shopping_payment')
                ->with('headerTitle', 'Pembayaran')
                ->with('getDataSales', $getDataSales)
                ->with('getDataMaster', $getDataMaster)
                ->with('getSeller', $getStockist)
                ->with('shopName', $sellerProfile->shop_name)
                ->with('dataUser', $dataUser);
        } else if ($sellerType == 2) {
            $getDataMaster = $modelSales->getMemberPembayaranVMasterSales($masterSalesID);

            $getDataSales = $modelSales->getMemberPembayaranVSalesNew($getDataMaster->id);
            $getVendor = $dataUser;
            if ($dataUser->id != $getDataMaster->vendor_id) {
                $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
                if ($getVendor->is_vendor == null) {
                    return redirect()->route('mainDashboard');
                }
            }
            $sellerProfile = SellerProfile::where('seller_id', $getVendor->id)->first();

            return view('member.sales.shopping_payment')
                ->with('headerTitle', 'Pembayaran')
                ->with('getDataSales', $getDataSales)
                ->with('getDataMaster', $getDataMaster)
                ->with('getSeller', $getVendor)
                ->with('shopName', $sellerProfile->shop_name)
                ->with('dataUser', $dataUser);
        }
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
        $modelMember = new Member;
        $getData = null;
        if ($dataUser->kode_daerah != null) {
            $provinsi = $modelMember->getProvinsiIdByName($dataUser->provinsi);
            if (!is_object($provinsi)) {
                return redirect()->route('m_editAddress')
                    ->with('message', 'Lumbung baru saja melakukan Update Database Daerah NKRI, silakan verifikasi kembali alamat anda sebelum Input Stock.')
                    ->with('messageclass', 'info');
            }
            $kabupaten = $modelMember->getKabupatenIdByName($dataUser->kota);
            $getData = $modelSales->getAllPurchaseByRegion($provinsi->id_prov, $kabupaten->id_kab, 1); //type 1 = Stockist, 2 = Vendor
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

    //seller Inventory
    public function getSellerInventory()
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
            Alert::error('Oops!', 'Data diri anda belum lengkap');
            return redirect()->route('m_newProfile');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchStockist');
        }

        //check if SellerProfile exist
        $checkSellerProfile = SellerProfile::where('seller_id', $dataUser->id)->first();
        if ($checkSellerProfile == null) {
            Alert::warning('Oops!', 'Silakan buat Profil Toko terlebih dulu');
            return redirect()->route('m_SellerProfile');
        }

        $type = 1;
        $getCategories = Category::select('id', 'name')->where('id', '<', 11)->get();

        if ($dataUser->is_vendor == 1) {
            $type = 2;
            $getCategories = Category::select('id', 'name')->where('id', '>', 10)->get();
        }

        $getProducts = Product::select('id', 'seller_id', 'name', 'size', 'price', 'desc', 'qty', 'category_id', 'image', 'is_active')
            ->where('seller_id', $dataUser->id)
            ->where('type', $type)
            ->with('category:id,name')
            ->get();

        return view('member.sales.inventory')
            ->with('products', $getProducts)
            ->with('categories', $getCategories)
            ->with('dataUser', $dataUser);
    }

    //Seller's Profile
    public function getSellerProfile()
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
            Alert::warning('Belum ada data', 'Silakan lengkapi terlebih dahulu Profile anda');
            return redirect()->route('m_newProfile');
        }
        if ($dataUser->is_vendor == 0 && $dataUser->is_stockist == 0) {
            return redirect()->route('mainDashboard');
        }

        $getProfile = SellerProfile::where('seller_id', $dataUser->id)->first();

        return view('member.sales.seller_profile')
            ->with('profile', $getProfile)
            ->with('dataUser', $dataUser);
    }

    public function postSellerAddProfile(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
        }

        $imageName = 'default.jpg';

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $request->validate([
                    'image' => 'required|mimes:jpeg,png|max:3000',
                ]);

                $name = $dataUser->user_code;
                $extension = 'jpg';
                $imageClass = new ImageManager;
                $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/sellers/' . $name . "." . $extension), 80, 'jpg');
                $imageName = $name . "." . $extension;
            } else {
                Alert::error('Gagal', 'Gagal upload gambar');
                return redirect()->back();
            }
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:25',
            'motto' => 'required|string|max:65',
            'tg_user' => 'nullable|string|max:60',
            'no_hp' => 'required|numeric|digits_between:9,16'
        ]);

        SellerProfile::create([
            'seller_id' => $dataUser->id,
            'shop_name' => $validated['shop_name'],
            'motto' => $validated['motto'],
            'no_hp' => $validated['no_hp'],
            'tg_user' => $validated['tg_user'],
            'image' => $imageName
        ]);

        Alert::success('Berhasil!', 'Data Profile telah dibuat');
        return redirect()->back();
    }

    public function postSellerEditProfile(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
        }

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $request->validate([
                    'image' => 'required|mimes:jpeg,png|max:3000',
                ]);

                $name = $dataUser->user_code;
                $extension = 'jpg';
                $imageClass = new ImageManager;
                $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/sellers/' . $name . "." . $extension), 80, 'jpg');
                $imageName = $name . "." . $extension;
                SellerProfile::where('seller_id', $dataUser->id)->update([
                    'image' => $imageName
                ]);
            } else {
                Alert::error('Gagal', 'Gagal upload gambar');
                return redirect()->back();
            }
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:25',
            'motto' => 'required|string|max:65',
            'tg_user' => 'nullable|string|max:60',
            'no_hp' => 'required|numeric|digits_between:9,16'
        ]);

        SellerProfile::where('seller_id', $dataUser->id)->update([
            'shop_name' => $validated['shop_name'],
            'motto' => $validated['motto'],
            'no_hp' => $validated['no_hp'],
            'tg_user' => $validated['tg_user']
        ]);

        Alert::success('Berhasil!', 'Data Profile telah diubah');
        return redirect()->back();
    }

    public function postCreateProduct(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchStockist');
        }

        $type = 1;
        if ($dataUser->is_vendor == 1) {
            $type = 2;
        }

        Product::create([
            'type' => $type,
            'seller_id' => $dataUser->id,
            'name' => $request->name,
            'size' => $request->size,
            'price' => $request->price,
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'image' => $request->image
        ]);

        Alert::success('Selesai', 'Produk berhasil dibuat.');
        return redirect()->back();
    }

    public function postEditProduct(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchStockist');
        }

        $type = 1;
        if ($dataUser->is_vendor == 1) {
            $type = 2;
        }

        Product::where('id', $request->product_id)
            ->where('type', $type)
            ->where('seller_id', $request->seller_id)
            ->update([
                'name' => $request->name,
                'size' => $request->size,
                'price' => $request->price,
                'desc' => $request->desc,
                'qty' => $request->qty,
                'is_active' => $request->is_active,
                'category_id' => $request->category_id,
                'image' => $request->image
            ]);

        Alert::success('Diubah', 'Produk berhasil diubah.');
        return redirect()->back();
    }

    public function postDeleteProduct(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchStockist');
        }

        $product = Product::find($request->product_id);
        if ($product->seller_id == $dataUser->id) {
            $product->delete();
        }

        Alert::success('Terhapus', 'Produk berhasil dihapus.');
        return redirect()->back();
    }

    public function postPaymentConfirmation(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchStockist');
        }

        $modelSales = new Sales;
        $hash = $request->hash;
        $sender = $request->userTron;

        if ($request->sellerType == 1) {
            $amount = $modelSales->getRoyaltiStockist($request->masterSalesID);
            $timestamp = $modelSales->getStockistMasterSalesTimestamp($request->masterSalesID);
        } elseif ($request->sellerType == 2) {
            $amount = $modelSales->getRoyaltiVendor($request->masterSalesID);
            $timestamp = $modelSales->getVendorMasterSalesTimestamp($request->masterSalesID);
        }

        $tron = $this->getTron();
        $i = 1;
        do {
            try {
                sleep(1);
                $response = $tron->getTransaction($hash);
            } catch (TronException $e) {
                $i++;
                continue;
            }
            break;
        } while ($i < 23);

        if ($i == 23) {
            Alert::error('Gagal', 'Hash Transaksi Bermasalah!');
            return redirect()->back();
        };

        $hashTime = $response['raw_data']['timestamp'];
        $hashSender = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['owner_address']);
        $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
        $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
        $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

        if ($hashTime > $timestamp) {
            if ($hashAmount == $amount * 100) {
                if ($hashAsset == '1002652') {
                    if ($hashReceiver == 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ') {
                        if ($hashSender == $sender) {

                            $dataUpdate = array(
                                'status' => 2,
                                'tron_transfer' => $hash
                            );
                            if ($request->sellerType == 1) {
                                $modelSales->getUpdateMasterSales('id', $request->masterSalesID, $dataUpdate);
                                Alert::success('Berhasil', 'Belanja member telah dikonfirmasi');
                                return redirect()->route('m_MemberStockistReport');
                            } elseif ($request->sellerType == 2) {
                                $modelSales->getUpdateVMasterSales('id', $request->masterSalesID, $dataUpdate);
                                Alert::success('Berhasil', 'Belanja member telah dikonfirmasi');
                                return redirect()->route('m_MemberVendorReport');
                            }
                        } else {
                            Alert::error('Gagal', 'Bukan pengirim yang sebenarnya');
                            return redirect()->back();
                        }
                    } else {
                        Alert::error('Gagal', 'Alamat Tujuan Transfer Salah!');
                        return redirect()->back();
                    }
                } else {
                    Alert::error('Gagal', 'Bukan Token eIDR yang benar!');
                    return redirect()->back();
                }
            } else {
                Alert::error('Gagal', 'Nominal Transfer Salah!');
                return redirect()->back();
            }
        } else {
            Alert::error('Gagal', 'Hash sudah terpakai!');
            return redirect()->back();
        }
    }

    public function postRejectShopping(Request $request)
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('m_SearchStockist');
        }

        $modelSales = new Sales;
        $dataUpdate = array(
            'status' => 10,
            'reason' => 'Dibatalkan oleh penjual'
        );
        $sellerType = 1;
        if ($dataUser->is_vendor == 1) {
            $sellerType = 2;
        }
        if ($sellerType == 1) {
            $modelSales->getUpdateMasterSales('id', $request->masterSalesID, $dataUpdate);
            $items = $modelSales->getMemberPembayaranSalesNew($request->masterSalesID);
        } else {
            $modelSales->getUpdateVMasterSales('id', $request->masterSalesID, $dataUpdate);
            $items = $modelSales->getMemberPembayaranVSalesNew($request->masterSalesID);
        }

        foreach ($items as $item) {
            $product = Product::find($item->purchase_id);
            $remaining = $product->qty + $item->amount;
            $product->update(['qty' => $remaining]);
        }

        Alert::success('Dibatalkan', 'Transaksi telah dibatalkan!');
        return redirect()->back();
    }

    //File and Image Upload
    public function getImageUpload()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->route('mainDashboard');
        }

        return view('member.image_upload');
    }

    public function postImageUpload(Request $request)
    {

        if ($request->hasFile('image')) {
            $dataUser = Auth::user();
            $onlyUser  = array(10);
            if (!in_array($dataUser->user_type, $onlyUser)) {
                return redirect()->route('mainDashboard');
            }
            if ($dataUser->package_id == null) {
                return redirect()->route('m_newPackage');
            }
            if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
                return redirect()->route('mainDashboard');
            }
            //  Let's do everything here
            if ($request->file('image')->isValid()) {
                //
                $validated = $request->validate([
                    'name' => 'required|string|alpha_dash|max:60',
                    'image' => 'required|mimes:jpeg|max:3000',
                ], [
                    'name.max' => 'Maksimum 60 karakter!',
                    'name.alpha_dash' => 'Hanya karakter huruf dan angka, tanpa Spasi, gunakan garis hubung',
                    'image.mimes' => 'Format file yang diterima hanya .jpeg atau .jpg saja',
                    'image.max' => 'Batas maksimum ukuran file adalah 1MB'
                ]);

                $name = strtolower($validated['name']);
                $extension = 'jpg';

                $nameCheck = ProductImages::where('name', $name . '.jpg')->first();
                if ($nameCheck != null) {
                    Alert::error('Oops!', 'Produk dengan nama yang sama sudah ada');
                    return redirect()->back();
                }
                // $request->image->storeAs('/public', $validated['name'] . "." . $extension);
                $imageClass = new ImageManager;
                $image = $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/products/' . $name . "." . $extension), 75);
                ProductImages::create([
                    'name' => $name . "." . $extension
                ]);
                Alert::image('Berhasil!', 'nama: ' . $name . "." . $extension, asset('/storage/products/') . "/" . $name . "." . $extension, '150', '150')->persistent(true);

                return redirect()->route('m_SellerInventory');
            }
            Alert::error('Gagal!', 'Gambar gagal di-upload');
            return redirect()->back();
        }
        Alert::error('Oops!', 'Gambar belum dipilih');
        return redirect()->back();
    }

    //Member Shopping

    public function getShopping($seller_id)
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
            Alert::error('Oops!', 'Silakan isi data profile anda terlebih dulu');
            return redirect()->route('m_newProfile');
        }
        $getSellerData = User::select('alamat', 'is_stockist', 'is_vendor')->where('id', $seller_id)->first();
        $getSellerProfile = SellerProfile::where('seller_id', $seller_id)->first();
        $getSellerProducts = Product::where('seller_id', $seller_id)->where('is_active', 1)->get();
        $getCategories = Category::select('id', 'name')->where('id', '<', 11)->get();

        if ($getSellerData->is_vendor == 1) {
            $getCategories = Category::select('id', 'name')->where('id', '>', 10)->get();
        }

        return view('member.sales.shopping')
            ->with('sellerProfile', $getSellerProfile)
            ->with('sellerAddress', $getSellerData->alamat)
            ->with('seller_id', $seller_id)
            ->with('categories', $getCategories)
            ->with('products', $getSellerProducts)
            ->with('user', $dataUser);
    }

    public function getPos()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->back();
        }
        if ($dataUser->is_profile == 0) {
            Alert::error('Oops!', 'Silakan isi data profile anda terlebih dulu');
            return redirect()->route('m_newProfile');
        }

        return view('member.sales.pos_input_buyer')
            ->with('user', null);
    }

    public function getPosShopping(Request $request)
    {
        $buyer = User::select('id', 'user_code', 'is_active', 'expired_at')->where('user_code', $request->username)->first();
        if ($buyer == null) {
            Alert::error('Oops!', 'Username tidak ditemukan, periksa kembali username yang anda masukkan');
            return redirect()->back()->with('username', $request->username);
        }

        if ($buyer->is_active == 0) {
            Alert::error('Oops!', 'Akun member ini belum diaktivasi.');
            return redirect()->back()->with('username', $request->username);
        }

        $expiration = strtotime($buyer->expired_at) - strtotime('now');
        if ($expiration < 0) {
            Alert::error('Oops!', 'Akun member ini sudah Expired.');
            return redirect()->back()->with('username', $request->username);
        }

        $dataUser = Auth::user();
        $onlyUser  = array(10);
        $seller_id = $dataUser->id;

        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        if ($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0) {
            return redirect()->back();
        }
        if ($dataUser->is_profile == 0) {
            Alert::error('Oops!', 'Silakan isi data profile anda terlebih dulu');
            return redirect()->route('m_newProfile');
        }
        $getSellerData = User::select('alamat', 'is_stockist', 'is_vendor')->where('id', $seller_id)->first();
        $getSellerProfile = SellerProfile::where('seller_id', $seller_id)->first();
        $getSellerProducts = Product::where('seller_id', $seller_id)->where('is_active', 1)->get();
        $getCategories = Category::select('id', 'name')->where('id', '<', 11)->get();

        if ($getSellerData->is_vendor == 1) {
            $getCategories = Category::select('id', 'name')->where('id', '>', 10)->get();
        }

        return view('member.sales.pos_shopping')
            ->with('sellerProfile', $getSellerProfile)
            ->with('sellerAddress', $getSellerData->alamat)
            ->with('seller_id', $seller_id)
            ->with('categories', $getCategories)
            ->with('products', $getSellerProducts)
            ->with('user', $buyer);
    }

    public function postAddToCart(Request $request)
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
            Alert::error('Oops!', 'Silakan isi data profile anda terlebih dulu');
            return redirect()->route('m_newProfile');
        }

        $Product = Product::find($request->product_id);

        \Cart::session($dataUser->id)->add(array(
            'id' => $Product->id,
            'name' => $Product->name,
            'price' => $Product->price,
            'quantity' => $request->quantity,
            'attributes' => array(),
            'associatedModel' => $Product
        ));

        Alert::success('OK!', 'Produk ditambahkan ke keranjang');
        return redirect()->back();
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

        $request = Request::create(\URL::to('/') . '/api/v1/statistic/overview', 'GET');
        $response = \Route::dispatch($request);

        $getData = $response->getOriginalContent();

        return view('member.explorer.statistic')
            ->with('data', $getData['data'])
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

    public function getEdit2FA()
    {
        $dataUser = Auth::user();
        $onlyUser  = array(10);
        if (!in_array($dataUser->user_type, $onlyUser)) {
            return redirect()->route('mainDashboard');
        }
        if ($dataUser->package_id == null) {
            return redirect()->route('m_newPackage');
        }
        return view('member.profile.my-2fa')
            ->with('headerTitle', '2FA PIN')
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

    public function postEdit2FA(Request $request)
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
            '2fa' => bcrypt($request->password),
        );
        $modelMember = new Member;
        $modelMember->getUpdateUsers('id', $dataUser->id, $dataUpdatePass);
        return redirect()->route('m_edit2FA')
            ->with('message', 'Berhasil edit Pin 2FA')
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
            return redirect()->route('m_myProfile')
                ->with('message', 'Silakan isi data profile anda terlebih dahulu')
                ->with('messageclass', 'warning');
        }
        if ($dataUser->is_stockist == 1 || $dataUser->is_vendor == 1) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Anda sudah menjadi member salah satu stockist atau vendor')
                ->with('messageclass', 'danger');
        }
        $timestamp = strtotime('now');
        $modelMember = new Member;
        $cekRequestStockist = $modelMember->getCekRequestVendor($dataUser->id);
        if ($cekRequestStockist != null) {
            return redirect()->route('m_SearchVendor')
                ->with('message', 'Anda sudah pernah mengajukan menjadi vendor')
                ->with('messageclass', 'danger');
        }
        $delegates = $modelMember->getDelegates();
        return view('member.profile.add-vendor')
            ->with('headerTitle', 'Aplikasi Pengajuan Vendor')
            ->with('timestamp', $timestamp)
            ->with('delegates', $delegates)
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
            'user_id' => $dataUser->id,
            'usernames' => $request->username1 . ' ' . $request->username2 . ' ' . $request->username3 . ' ' . $request->username4 . ' ' . $request->username5,
            'delegate' => $request->delegate,
            'hash' => $request->hash
        );
        $sendRequest = $modelMember->getInsertVendor($dataInsert);
        ProcessRequestToDelegatesJob::dispatch(2, $sendRequest->lastID)->onQueue('tron');

        Alert::success('Berhasil!', 'Pengajuan Vendor anda telah diteruskan ke Tim Delegasi');
        return redirect()->route('m_SearchVendor');
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
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $localAddress = null;
        $eIDRbalance = null;
        if ($localWallet != null) {
            if ($localWallet->is_active == 1) {
                $tron = $this->getTron();
                $localAddress = $localWallet->address;
                $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;
            }
        }
        $getDataItem = $modelSales->getMemberPembayaranVSalesNew($id);
        return view('member.sales.vendor_confirm_payment')
            ->with('headerTitle', 'Vendor Transfer')
            ->with('getDataSales', $getDataSales)
            ->with('getDataItem', $getDataItem)
            ->with('localAddress', $localAddress)
            ->with('eIDRbalance', $eIDRbalance)
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
        $rand = rand(50, 148);
        $dataInsert = array(
            'user_id' => $dataUser->id,
            'transaction_code' => 'DTR' . date('Ymd') . $dataUser->id . $code,
            'price' => $request->total_deposit,
            'unique_digit' => $rand,
        );
        $getIDTrans = $modelSettingTrans->getInsertDepositTransaction($dataInsert);
        return redirect()->route('m_addDepositTransaction', [$getIDTrans->lastID])
            ->with('message', 'Silakan pilih metode pembayaran anda')
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
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $localAddress = null;
        $eIDRbalance = null;
        if ($localWallet != null) {
            if ($localWallet->is_active == 1) {
                $tron = $this->getTron();
                $localAddress = $localWallet->address;
                $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;
            }
        }
        return view('member.digital.detail-deposit-trans')
            ->with('headerTitle', 'Deposit Transaction')
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('tronPerusahaan', $getPerusahaanTron)
            ->with('localAddress', $localAddress)
            ->with('eIDRbalance', $eIDRbalance)
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

        $modelSettingTrans = new Transaction;
        $modelPin = new Pin;
        $getTrans = $modelSettingTrans->getDetailDepositTransactionsMember($request->id_trans, $dataUser);
        $hash = $request->hash;
        $check = $modelPin->checkUsedHashExist($hash, 'deposit_transaction', 'tron_transfer');
        if ($check) {
            Alert::error('Gagal', 'Hash Transaksi sudah pernah digunakan pada pembayaran sebelumnya');
            return redirect()->back();
        }
        if (strlen($hash) != 64) {
            Alert::error('Gagal', 'Hash Transaksi terdapat kesalahan ketik atau Typo!');
            return redirect()->back();
        }

        $amount = $getTrans->price + $getTrans->unique_digit;
        $timestamp = strtotime($getTrans->created_at);
        $id_trans = $request->id_trans;
        $user_id = $dataUser->id;

        $tron = $this->getTron();
        $i = 1;
        do {
            try {
                sleep(1);
                $response = $tron->getTransaction($hash);
            } catch (TronException $e) {
                $i++;
                continue;
            }
            break;
        } while ($i < 23);

        if ($i == 23) {
            Alert::error('Gagal', 'Hash Transaksi Bermasalah!');
            return redirect()->back();
        };

        $hashTime = $response['raw_data']['timestamp'];
        $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
        $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
        $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

        if ($hashTime > $timestamp) {
            if ($hashAmount / 100 == $amount) {
                if ($hashAsset == '1002652') {
                    if ($hashReceiver == 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge') {
                        $dataUpdate = array(
                            'status' => 2,
                            'bank_perusahaan_id' => 9,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'is_tron' => 1,
                            'tron_transfer' => $hash
                        );
                        $modelSettingTrans->getUpdateDepositTransaction('id', $id_trans, $dataUpdate);

                        $memberDeposit = array(
                            'user_id' => $user_id,
                            'total_deposito' => $amount,
                            'transaction_code' => $getTrans->transaction_code,
                        );
                        $modelPin->getInsertMemberDeposit($memberDeposit);
                        Alert::success('Berhasil', 'Proses Isi Deposit Vendor Berhasil!');
                        return redirect()->route('mainMyAccount');
                    } else {
                        Alert::error('Gagal', 'Alamat Tujuan Transfer Salah!');
                        return redirect()->back();
                    }
                } else {
                    Alert::error('Gagal', 'Bukan Token eIDR yang benar!');
                    return redirect()->back();
                }
            } else {
                Alert::error('Gagal', 'Nominal Transfer Salah!');
                return redirect()->back();
            }
        } else {
            Alert::error('Gagal', 'Time Traveler!');
            return redirect()->back();
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
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataUser);
        $getTotalPPOBOut = $modelPin->getPPOBFly($dataUser->id);
        return view('member.digital.tarik-deposit')
            ->with('dataDeposit', $getTotalDeposit)
            ->with('dataTarik', $getTransTarik)
            ->with('onTheFly', $getTotalPPOBOut)
            ->with('dataUser', $dataUser);
    }

    public function postVendorWithdrawDeposit(Request $request)
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
            return redirect()->route('mainDashboard');
        }

        //double-checking actual available deposit
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataUser);
        $getTotalPPOBOut = $modelPin->getPPOBFly($dataUser->id);

        $sum_deposit_masuk = 0;
        $sum_deposit_keluar = 0;
        $sum_deposit_tarik = 0;
        $sum_on_the_fly = 0;
        if ($getTotalDeposit->sum_deposit_masuk != null) {
            $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
        }
        if ($getTotalDeposit->sum_deposit_keluar != null) {
            $sum_deposit_keluar = $getTotalDeposit->sum_deposit_keluar;
        }
        if ($getTransTarik->deposit_keluar != null) {
            $sum_deposit_tarik = $getTransTarik->deposit_keluar;
        }
        if ($getTotalPPOBOut->deposit_out != null) {
            $sum_on_the_fly = $getTotalPPOBOut->deposit_out;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_tarik -
            $sum_on_the_fly;

        $amount = $request->amount;
        $to = $dataUser->tron;

        if ($amount > $totalDeposit) {
            Alert::error('Gagal!', 'Saldo Deposit tidak cukup!');
            return redirect()->back();
        }
        if ($amount > 0 && $amount <= $totalDeposit) {
            $fuse = Config::get('services.telegram.test');
            $tron = $this->getTron();
            $tron->setPrivateKey($fuse);

            $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
            $tokenID = '1002652';

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount * 100, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                //logging the transaction
                $code = $modelTrans->getCodeDepositTransaction();
                $transaction_code = 'TTR' . date('Ymd') . $dataUser->id . $code;
                $dataInsert = array(
                    'type' => 2,
                    'user_id' => $dataUser->id,
                    'transaction_code' => $transaction_code,
                    'price' => $amount,
                    'unique_digit' => 0,
                    'user_bank' => null,
                    'is_tron' => 1,
                    'status' => 2,
                    'tuntas_at' => date('Y-m-d H:i:s'),
                    'tron_transfer' => $response['txid']
                );
                $modelTrans->getInsertDepositTransaction($dataInsert);

                //deducting vendor's deposit
                $vendorDeposit = array(
                    'user_id' => $dataUser->id,
                    'total_deposito' => $amount,
                    'transaction_code' => $transaction_code,
                    'deposito_status' => 1
                );
                $modelPin->getInsertMemberDeposit($vendorDeposit);

                Alert::success('Berhasil!', number_format($amount) . ' eIDR telah berhasil ditarik!');
                return redirect()->route('mainMyAccount');
            } else {
                Alert::error('Gagal!', 'Ada masalah pada proses transfer, silakan diulangi kembali');
                return redirect()->back();
            }
        }
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

    public function getListEmoney()
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
        return view('member.digital.list-emoney')
            ->with('headerTitle', 'List Emoney')
            ->with('dataUser', $dataUser);
    }

    public function getProductBySKUPrepaid($type, $buyer_sku_code)
    {
        $modelMember = new Member;
        $modelPin = new Pin;
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

        $code = $modelPin->getCodePPOBRef($type);

        foreach ($arrayData['data'] as $row) {
            if ($row['buyer_sku_code'] == $buyer_sku_code) {
                //Pulsa & Data
                if ($type < 3) {
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

                    $real_price = $row['price'] + ($price * 2 / 100);
                }

                //PLN Prepaid
                if ($type == 3) {
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
                }

                //Emoneys
                if ($type >= 21) {
                    $priceAwal = $row['price'];
                    $price3000 = $priceAwal + 1000;
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
                    $real_price = $price - 600;
                }

                $product = (object) array(
                    'buyer_sku_code' => $row['buyer_sku_code'],
                    'desc' => $row['desc'],
                    'real_price' => $real_price,
                    'price' => $price,
                    'brand' => $row['brand'],
                    'product_name' => $row['product_name'],
                    'ref_id' => $code
                );

                return $product;
            }
        }
    }

    public function getProductBySKUPostpaid($type, $buyer_sku_code, $customer_no)
    {
        $modelMember = new Member;
        $modelPin = new Pin;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $ref_id = $modelPin->getCodePPOBRef($type);
        $sign = md5($username . $apiKey . $ref_id);
        $array = array(
            'commands' => 'inq-pasca',
            'username' => $username,
            'buyer_sku_code' => $buyer_sku_code,
            'customer_no' => $customer_no,
            'ref_id' => $ref_id,
            'sign' => $sign,
        );
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $getData = json_decode($cek, true);
        if ($getData == null) {
            Alert::warning('Oops!', 'Periksa kembali nomor yang anda masukkan')->persistent(true);
            return redirect()->back();
        }

        $data_price = $getData['data']['selling_price'];

        //BPJS
        if ($type == 4) {
            $price = $data_price;
            $real_price = $data_price - 700;
        }

        //PLN
        if ($type == 5) {
            $price = $data_price + 500;
            $real_price = $data_price - 1000;
        }

        //HP & Telkom
        if ($type == 6 || $type == 7) {
            $price = $data_price + 1000;
            $real_price = $data_price - 100;
        }

        //PDAM
        if ($type == 8) {
            $price = $data_price + 800;
            $real_price = $data_price;
        }

        //PGN
        if ($type == 9) {
            $price = $data_price + 1000;
            $real_price = $data_price;
        }

        //Multifinance
        if ($type == 10) {
            $price = $data_price + 5000;
            $real_price = $data_price - 2600;
        }

        $product = (object) array(
            'buyer_sku_code' => $getData['data']['buyer_sku_code'],
            'desc' => $getData['data']['buyer_sku_code'],
            'real_price' => $real_price,
            'price' => $price,
            'ref_id' => $ref_id
        );

        return $product;
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
        $arrayHarga = null;
        $daftarHarga = null;
        if ($operator == 1) {
            $arrayHarga = $telkomsel;
        }
        if ($operator == 2) {
            $arrayHarga = $indosat;
        }
        if ($operator == 3) {
            $arrayHarga = $xl;
        }
        if ($operator == 4) {
            $arrayHarga = $axis;
        }
        if ($operator == 5) {
            $arrayHarga = $tri;
        }
        if ($operator == 6) {
            $arrayHarga = $smart;
        }
        $daftarHarga = collect($arrayHarga)->sortBy('price')->toArray();
        $daftarHargaCall = null; //Placeholder Paket Telepon & SMS
        return view('member.digital.daftar-harga-operator')
            ->with('headerTitle', 'Isi Pulsa')
            ->with('daftarHarga', $daftarHarga)
            ->with('type', 1)
            ->with('daftarHargaCall', $daftarHargaCall)
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
        // dd($arrayData['data']);
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

        $tcall = array();
        $icall = array();
        $xlcall = array();
        $tricall = array();

        //Paket Data
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

            //Paket SMS dan Telepon
            if ($row['category'] == 'Paket SMS & Telpon') {
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
                    $tcall[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'INDOSAT') {
                    $icall[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'XL') {
                    $xlcall[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'TRI') {
                    $tricall[] = array(
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
        $arrayHarga = null;
        $arrayHargaCall = null;
        $daftarHarga = null;
        $daftarHargaCall = null;

        if ($operator == 1) {
            $arrayHarga = $telkomsel;
            $arrayHargaCall = $tcall;
        }
        if ($operator == 2) {
            $arrayHarga = $indosat;
            $arrayHargaCall = $icall;
        }
        if ($operator == 3) {
            $arrayHarga = $xl;
            $arrayHargaCall = $xlcall;
        }
        if ($operator == 4) {
            $arrayHarga = $axis;
        }
        if ($operator == 5) {
            $arrayHarga = $tri;
            $arrayHargaCall = $tricall;
        }
        if ($operator == 6) {
            $arrayHarga = $smart;
        }
        if ($arrayHarga == null) {
            return redirect()->route('mainDashboard')
                ->with('message', 'Tidak ada data')
                ->with('messageclass', 'danger');
        }
        $daftarHarga = collect($arrayHarga)->sortBy('price')->toArray();
        $daftarHargaCall = collect($arrayHargaCall)->sortBy('price')->toArray();

        return view('member.digital.daftar-harga-operator')
            ->with('headerTitle', 'Isi Paket Data')
            ->with('daftarHarga', $daftarHarga)
            ->with('daftarHargaCall', $daftarHargaCall)
            ->with('type', 2)
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
        $orderedArrayData = collect($arrayData['data'])->sortBy('price')->toArray();

        $gopay = array();
        $etoll = array();
        $ovo = array();
        $dana = array();
        $linkaja = array();
        $shopee = array();
        $brizzi = array();
        $bnitc = array();
        foreach ($orderedArrayData as $row) {
            if ($row['category'] == 'E-Money') {
                $priceAwal = $row['price'];
                $price3000 = $priceAwal + 1000;
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
                $real_price = $price - 600;
                if ($row['brand'] == 'GO PAY') {
                    $gopay[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'type' => $row['type'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'MANDIRI E-TOLL') {
                    $etoll[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'OVO') {
                    $ovo[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'DANA') {
                    $dana[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'LinkAja') {
                    $linkaja[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'SHOPEE PAY') {
                    $shopee[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'BRI BRIZZI') {
                    $brizzi[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
                if ($row['brand'] == 'TAPCASH BNI') {
                    $bnitc[] = array(
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'real_price' => $real_price,
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name']
                    );
                }
            }
        }
        $daftarHarga = null;
        $type = null;
        if ($operator == 1) {
            $daftarHarga = collect($gopay)->sortBy('product_name')->toArray();
            $type = 21;
        }
        if ($operator == 2) {
            $daftarHarga = $etoll;
            $type = 22;
        }
        if ($operator == 3) {
            $daftarHarga = $ovo;
            $type = 23;
        }
        if ($operator == 4) {
            $daftarHarga = $dana;
            $type = 24;
        }
        if ($operator == 5) {
            $daftarHarga = $linkaja;
            $type = 25;
        }
        if ($operator == 6) {
            $daftarHarga = $shopee;
            $type = 26;
        }
        if ($operator == 7) {
            $daftarHarga = $brizzi;
            $type = 27;
        }
        if ($operator == 8) {
            $daftarHarga = $bnitc;
            $type = 28;
        }
        return view('member.digital.daftar-harga-operator')
            ->with('daftarHarga', $daftarHarga)
            ->with('headerTitle', 'Isi eMoney')
            ->with('daftarHargaCall', null)
            ->with('type', $type)
            ->with('dataUser', $dataUser);
    }

    public function getCekPLNPrepaid()
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
        return view('member.digital.cek-pln-prepaid')
            ->with('headerTitle', 'Cek Nomor PLN Prabayar');
    }

    public function getInquiryPLNPrepaid(Request $request)
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

        $array = array(
            'commands' => 'pln-subscribe',
            'customer_no' => $request->customer_no,
        );
        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $getData = json_decode($cek, true);
        if ($getData == null) {
            Alert::error('Gagal!', 'Data tidak ditemukan, cek kembali nomor yang anda masukkan');
            return redirect()->route('m_cekPLNPrepaid')
                ->with('customer_no', $request->customer_no);
        }
        $sign = md5($username . $apiKey . 'pricelist');
        $priceArray = array(
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        );
        $priceJson = json_encode($priceArray);
        $priceUrl = $getDataAPI->master_url . '/v1/price-list';
        $priceCek = $modelMember->getAPIurlCheck($priceUrl, $priceJson);
        $arrayData = json_decode($priceCek, true);
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
            ->with('type', 3)
            ->with('dataCustomer', $getData['data'])
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

        $type = $request->type;
        $buyer_sku_code = $request->buyer_sku_code;

        $modelPin = new Pin;

        if ($type >= 1 && $type < 4 || $type >= 21 && $type < 29) {
            $productData = $this->getProductBySKUPrepaid($type, $buyer_sku_code);
        } elseif ($type >= 4 && $type < 11) {
            $productData = $this->getProductBySKUPostpaid($type, $buyer_sku_code, $request->no_hp);
        }


        $dataInsert = array(
            'user_id' => $dataUser->id,
            'vendor_id' => $request->vendor_id,
            'ppob_code' => $productData->ref_id,
            'type' => $request->type,
            'buyer_code' => $productData->buyer_sku_code,
            'product_name' => $request->no_hp,
            'ppob_price' => $productData->price,
            'ppob_date' => date('Y-m-d'),
            'harga_modal' => $productData->real_price,
            'message' => $productData->desc
        );

        $newPPOB = $modelPin->getInsertPPOB($dataInsert);
        PPOBAutoCancelJob::dispatch($newPPOB->lastID)->delay(now()->addMinutes(70))->onQueue('tron');
        Alert::success('Berhasil!', 'Silakan Pilih Metode Pembayaran dan Konfirmasi');
        return redirect()->route('m_detailPPOBMemberTransaction', [$newPPOB->lastID]);
    }

    public function postQuickbuyPPOB(Request $request)
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
            return redirect()->route('mainDashboard');
        }

        $user_id = $request->user_id;
        if ($user_id == null) {
            $user_id = $dataUser->id;
        }

        $type = $request->type;
        $buyer_sku_code = $request->buyer_sku_code;

        $modelPin = new Pin;

        if ($type >= 1 && $type < 4 || $type >= 21 && $type < 29) {
            $productData = $this->getProductBySKUPrepaid($type, $buyer_sku_code);
        } elseif ($type >= 4 && $type < 11) {
            $productData = $this->getProductBySKUPostpaid($type, $buyer_sku_code, $request->no_hp);
        }

        $dataInsert = array(
            'user_id' => $user_id,
            'vendor_id' => $dataUser->id,
            'ppob_code' => $productData->ref_id,
            'type' => $request->type,
            'buyer_code' => $productData->buyer_sku_code,
            'product_name' => $request->no_hp,
            'ppob_price' => $productData->price,
            'ppob_date' => date('Y-m-d'),
            'confirm_at' => date('Y-m-d H:i:s'),
            'harga_modal' => $productData->real_price,
            'status' => 1,
            'buy_metode' => 1,
            'message' => $productData->desc
        );

        $newPPOB = $modelPin->getInsertPPOB($dataInsert);
        PPOBAutoCancelJob::dispatch($newPPOB->lastID)->delay(now()->addMinutes(70))->onQueue('tron');
        Alert::success('Berhasil!', 'Periksa kembali lalu Konfirmasi Pembelian ini');
        return redirect()->route('m_vendorDetailPPOB', [$newPPOB->lastID]);
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
            $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
            if ($getVendor->is_vendor == null) {
                return redirect()->route('mainDashboard');
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

        $getDataMaster = Ppob::find($id);
        $buyer = User::where('id', $getDataMaster->user_id)->select('user_code')->first();
        $seller = User::where('id', $getDataMaster->vendor_id)->select('user_code')->first();

        return view('member.digital.m_vpdf_ppob')
            ->with('getDataMaster', $getDataMaster)
            ->with('buyer', $buyer->user_code)
            ->with('seller', $seller->user_code);
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
            ->with('message', 'Konfirmasi pembelian berhasil, silakan hubungi vendor')
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
        $getDataMaster = $modelPin->getVendorPPOBDetail($id, $dataUser);
        return view('member.digital.m_detail_vppob')
            ->with('headerTitle', 'Transaksi Produk Digital')
            ->with('getDataMaster', $getDataMaster);
    }

    public function getDetailVendorPPOBnew($id)
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
        return view('member.digital.m_detail_vppob_new')
            ->with('headerTitle', 'Konfirmasi Transaksi')
            ->with('getDataMaster', $getDataMaster)
            ->with('getMember', $getMember)
            ->with('dataUser', $dataUser);
    }

    public function postVendorConfirmPPOBnew(Request $request)
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

        //2FA pin checking
        if (!Hash::check($request->password, $dataUser->{'2fa'})) {
            return redirect()->back()
                ->with('message', 'Kode Pin 2FA Salah!')
                ->with('messageclass', 'danger');
        }

        //deposit checking
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

        //pulsa, data, pln prepaid
        if ($getDataMaster->type >= 1 && $getDataMaster->type < 4) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        //pasca
        if ($getDataMaster->type >= 4 && $getDataMaster->type < 11) {
            $array = array(
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        //emoney
        if ($getDataMaster->type >= 21 && $getDataMaster->type < 30) {
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

        if ($arrayData == null) {
            return redirect()->back()
                ->with('message', 'Ada gangguan koneksi, periksa jaringan internet anda, lalu ulangi kembali.')
                ->with('messageclass', 'warning');
        }

        if ($arrayData['data']['status'] == 'Pending') {
            if ($getDataMaster->type >= 4 && $getDataMaster->type < 11) {
                $array = array(
                    'commands' => 'status-pasca',
                    'username' => $username,
                    'buyer_sku_code' => $getDataMaster->buyer_code,
                    'customer_no' => $getDataMaster->product_name,
                    'ref_id' => $ref_id,
                    'sign' => $sign,
                );
            } else {
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

            do {
                $i = 0;
                if ($i > 16) goto end;
                sleep(3); //give it a break, brotha ;D
                $cek = $modelMember->getAPIurlCheck($url, $json);
                $arrayData = json_decode($cek, true);
                $i++;

                if ($arrayData == null) {
                    return redirect()->back()
                        ->with('message', 'Ada gangguan koneksi, periksa jaringan internet anda, lalu ulangi kembali.')
                        ->with('messageclass', 'warning');
                }
            } while ($arrayData['data']['status'] == 'Pending');
        }

        //deliver the bad news first
        if ($arrayData['data']['status'] == 'Gagal') {
            $dataUpdate = array(
                'status' => 3,
                'deleted_at' => date('Y-m-d H:i:s'),
                'return_buy' => $cek,
                'vendor_approve' => 3,
                'vendor_cek' => $cek
            );
            $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
            if ($arrayData['data']['message'] != null) {
                return redirect()->back()
                    ->with('message', $arrayData['data']['message'])
                    ->with('messageclass', 'danger');
            }
            Alert::error('Gagal!', 'Maaf, transaksi gagal!');
            return redirect()->back();
        }

        //so the good news taste sweeter
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
                    'total_deposito' => $getDataMaster->harga_modal,
                    'transaction_code' => $getDataMaster->buyer_code . '-' . $ref_id,
                    'deposito_status' => 1
                );
                $modelPin->getInsertMemberDeposit($memberDeposit);
            }
            if ($arrayData['data']['buyer_last_saldo'] < 1000000) {
                $tgAk = Config::get('services.telegram.eidr');
                $client = new Client;
                $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                    'query' => [
                        'chat_id' => '365874331',
                        'text' => 'Saldo Digiflazz tinggal' . $arrayData['data']['buyer_last_saldo'],
                        'parse_mode' => 'markdown'
                    ]
                ]);
            }
            Alert::success('Berhasil!', 'Transaksi Berhasil!');
            return redirect()->back();
        }

        //get over it
        end:
        if ($arrayData['data']['status'] == 'Pending') {
            $dataUpdate = array(
                'vendor_cek' => $cek
            );
            $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
            Alert::warning('Pending', 'Transaksi Pending, silakan tekan Konfirmasi lagi.');
            return redirect()->back();
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
            'reason' => 'Dibatalkan oleh Vendor',
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelPin->getUpdatePPOB('id', $request->ppob_id, $dataUpdate);
        Alert::success('Berhasil', 'Transaksi Berhasil Dibatalkan');
        return redirect()->route('m_listVendotPPOBTransactions');
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
        if ($type > 9) {
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
        //1 BPJS. 2 PLN, 3 Hp Pasca, 4 TELKOM PSTN, 5 PDAM, 6 Gas Negara, 7 Multifinance

        if (!is_numeric($request->customer_no)) {
            return redirect()->back()
                ->with('message', 'Pergunakan hanya angka saja')
                ->with('messageclass', 'danger');
        }

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
        if ($request->type == 5) {
            $buyer_sku_code = $request->buyer_sku_code;
            $typePPOB = 8;
        }
        if ($request->type == 6) {
            $buyer_sku_code = 'PGN';
            $typePPOB = 9;
        }
        if ($request->type == 7) {
            $buyer_sku_code = $request->buyer_sku_code;
            $typePPOB = 10;
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
            Alert::warning('Oops!', 'Periksa kembali nomor yang anda masukkan')->persistent(true);
            return redirect()->back();
        }
        if ($getData['data']['rc'] != '00') {
            Alert::warning('Oops!', $getData['data']['message'])->persistent(true);
            return redirect()->back();
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

    public function getPPOBMultifinance()
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
            if ($row['brand'] == 'MULTIFINANCE') {
                $data[] = array(
                    'buyer_sku_code' => $row['buyer_sku_code'],
                    'admin' => $row['admin'],
                    'commission' => $row['commission'],
                    'brand' => $row['brand'],
                    'product_name' => $row['product_name']
                );
            }
        }
        return view('member.digital.daftar-multifinance')
            ->with('headerTitle', 'Daftar Multifinance')
            ->with('data', $data)
            ->with('dataUser', $dataUser);
    }

    public function getPDAMPascabayar()
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
            if ($row['brand'] == 'PDAM') {
                $data[] = array(
                    'buyer_sku_code' => $row['buyer_sku_code'],
                    'admin' => $row['admin'],
                    'commission' => $row['commission'],
                    'brand' => $row['brand'],
                    'product_name' => $row['product_name']
                );
            }
        }
        return view('member.digital.list-pdam')
            ->with('headerTitle', 'Daftar Pembayaran PDAM')
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

    public function getDetailPPOBMultifinance($sku, Request $request)
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
            ->with('type', 7)
            ->with('buyer_sku_code', $sku)
            ->with('product_name', $request->product_name)
            ->with('dataUser', $dataUser);
    }

    public function getDetailPDAMPascabayar($sku, Request $request)
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
            ->with('type', 5)
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
