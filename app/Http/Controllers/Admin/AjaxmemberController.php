<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Pinsetting;
use App\Model\Package;
use App\Model\Member;
use App\Model\Validation;
use App\Model\Bank;
use App\Model\Pengiriman;
use App\Model\Pin;
use App\Model\Memberpackage;
use App\Model\Transferwd;
use App\Model\Bonus;
use App\Model\Transaction;
use App\Model\Sales;
use App\Model\Bonussetting;

class AjaxmemberController extends Controller {

    public function __construct(){
    }
    
    public function postCekAddSponsor(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $canInsert = $modelValidasi->getCheckNewSponsor($request);
        $modelMember = New Member;
        $getCheck = $modelMember->getCheckUsercode($request->user_code);
        if($getCheck->cekCode == 1){
            $canInsert = (object) array('can' => false,  'pesan' => 'Username sudah terpakai');
        }
        $data = (object) array(
            'name' => $request->name,
            'email' => $request->email,
            'hp' => $request->hp,
            'username' => $request->user_code,
            'password' => $request->password
        );
        return view('member.ajax.confirm_add_sponsor')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function postCekAddProfile(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelMember = New Member;
        $canInsert = $modelValidasi->getCheckNewProfile($request);
        $provinsi = $modelMember->getProvinsiByID($request->provinsi);
        if($provinsi == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Provinsi harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kota = $modelMember->getNamaByKode($request->kota);
        if($kota == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kabupaten/Kota harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kode = $kota->kode;
        $kecamatan = $modelMember->getNamaByKode($request->kecamatan);
        if($kecamatan == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kecamatan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kode = $kecamatan->kode;
        $kelurahan = $modelMember->getNamaByKode($request->kelurahan);
        if($kelurahan == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kelurahan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kode = $kelurahan->kode;
        $data = (object) array(
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'provinsi' => $provinsi->nama,
            'kode_pos' => $request->kode_pos,
            'kota' => $kota->nama,
            'kecamatan' => $kecamatan->nama,
            'kelurahan' => $kelurahan->nama,
            'kode_daerah' => $kode,
        );
        return view('member.ajax.confirm_add_profile')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekAddPackage($id_paket, $setuju){
        $modePackage = New Package;
        $modelSettingPin = New Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $getDetailPackage = $modePackage->getPackageId($id_paket);
        return view('member.ajax.confirm_add_package')
                        ->with('setuju', $setuju)
                        ->with('getData', $getDetailPackage)
                        ->with('pinSetting', $getActivePinSetting);
    }
    
    public function postCekAddPin(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $canInsert = $modelValidasi->getCheckAddPin($request, $dataUser);
        $disc = 0;
//        if($request->total_pin >= 100){
//            $disc = 0;
//        }
        $modelSettingPin = New Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $hargaAwal = $getActivePinSetting->price * $request->total_pin;
        $discAwal = $hargaAwal * $disc / 100;
        $harga = $hargaAwal - $discAwal;
        $data = (object) array(
            'total_pin' => $request->total_pin,
            'harga' => $harga,
            'disc' => $disc
        );
        return view('member.ajax.confirm_add_pin')
                        ->with('check', $canInsert)
                        ->with('disc', $disc)
                        ->with('data', $data);
    }
    
    public function postCekAddTransaction(Request $request){
        $dataUser = Auth::user();
        $modelBank = New Bank;
        $modelTrans = New Transaction;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if(count($separate) == 2){
            $cekType = $separate[0];
            $bankId = $separate[1];
            if($cekType == 0){
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($bankId);
            }
        }
        $getTrans = $modelTrans->getDetailTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_add_transaction')
                        ->with('bankPerusahaan', $getPerusahaanBank)
                        ->with('getTrans', $getTrans)
                        ->with('cekType', $cekType)
                        ->with('data', $data);
    }
    
    public function postCekRejectTransaction(Request $request){
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_reject_transaction')
                        ->with('data', $data);
    }
    
    public function getCekConfirmOrderPackage(Request $request){
        $dataUser = Auth::user();
        $data = (object) array('id_paket' => $request->id_paket);
        $modelPin = new Pin;
        $modelMemberPackage = New Memberpackage;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($request->id_paket, $dataUser);
        $sisaPin = $modelPin->getTotalPinMember($dataUser);
        $sum_pin_masuk = 0;
        $sum_pin_keluar = 0;
        if($sisaPin->sum_pin_masuk != null){
            $sum_pin_masuk = $sisaPin->sum_pin_masuk;
        }
        if($sisaPin->sum_pin_keluar != null){
            $sum_pin_keluar = $sisaPin->sum_pin_keluar;
        }
        $total = $sum_pin_masuk - $sum_pin_keluar;
        $totalPinOrder = $getData->total_pin;
        $lanjut = false;
        if($total >= $totalPinOrder){
            $lanjut = true;
        }
        return view('member.ajax.confirm_order')
                        ->with('lanjut', $lanjut)
                        ->with('data', $data);
    }
    
    public function getCekRejectOrderPackage(Request $request){
        $dataUser = Auth::user();
        $data = (object) array('id_paket' => $request->id_paket);
        $modelPin = new Pin;
        $modelMemberPackage = New Memberpackage;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($request->id_paket, $dataUser);
        $sisaPin = $modelPin->getTotalPinMember($dataUser);
        $sum_pin_masuk = 0;
        $sum_pin_keluar = 0;
        if($sisaPin->sum_pin_masuk != null){
            $sum_pin_masuk = $sisaPin->sum_pin_masuk;
        }
        if($sisaPin->sum_pin_keluar != null){
            $sum_pin_keluar = $sisaPin->sum_pin_keluar;
        }
        $total = $sum_pin_masuk - $sum_pin_keluar;
        $totalPinOrder = $getData->total_pin;
        $lanjut = false;
        if($total >= $totalPinOrder){
            $lanjut = true;
        }
        return view('member.ajax.reject_order')
                        ->with('lanjut', $lanjut)
                        ->with('data', $data);
    }
    
    public function getCekAddBank(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBank = New Bank;
        $canInsert = $modelValidasi->getCheckAddBank($request);
//        $getCek = $modelBank->getCheckNoRek($request->account_no, $request->bank_name);
//        if($getCek > 0){
//            $canInsert = (object) array('can' => false,  'pesan' => 'Identitas rekening bank sudah terpakai');
//        }
        $data = (object) array(
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $dataUser->full_name
        );
        return view('member.ajax.confirm_add_bank')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function getActivateBank($id){
        $dataUser = Auth::user();
        $modelBank = New Bank;
        $getCek = $modelBank->getBankMemberID($id, $dataUser);
        return view('member.ajax.confirm_activate_bank')
                        ->with('getData', $getCek)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekTransferPin(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelPin = new Pin;
        $modelMember = new Member;
        $canInsert = $modelValidasi->getCheckPengiriman($request);
        $cekPin =$modelPin->getTotalPinMember($dataUser);
        if($request->total_pin == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak mengisi jumlah pin');
            return view('member.ajax.confirm_transfer_pin')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
        }
        if($request->to_id == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak mengisi data penerima Pin');
            return view('member.ajax.confirm_transfer_pin')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
        }
        $sisaPin = $cekPin->sum_pin_masuk - $cekPin->sum_pin_keluar;
        if($sisaPin < $request->total_pin){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin anda idak tersedia untuk transfer pin');
        }
        $cekMember = $modelMember->getUsers('id', $request->to_id);
        $data = (object) array(
            'total_pin' => $request->total_pin,
            'id' => $cekMember->id,
            'name' => $cekMember->name,
            'user_code' => $cekMember->user_code,
            'email' => $cekMember->email,
            'hp' => $cekMember->hp
        );
        return view('member.ajax.confirm_transfer_pin')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekUpgrade($id_paket){
        $dataUser = Auth::user();
        $modePackage = New Package;
        $modelPin = New Pin;
        $getDetailPackage = $modePackage->getPackageId($id_paket);
        $getMyPackage = $modePackage->getMyPackage($dataUser);
        $total_sisa_pin = $getDetailPackage->pin - $getMyPackage->pin;
        $cekPin =$modelPin->getTotalPinMember($dataUser);
        $sisaPin = $cekPin->sum_pin_masuk - $cekPin->sum_pin_keluar;
        $dataCek = (object) array(
            'total_sisa_pin' => $total_sisa_pin,
            'sisa_pin' => $sisaPin
        );
        $modelValidasi = New Validation;
        $canInsert = $modelValidasi->getCekPinForUpgrade($dataCek);
        return view('member.ajax.confirm_upgrade_package')
                        ->with('canInsert', $canInsert)
                        ->with('total_pin', $total_sisa_pin)
                        ->with('dataPackage', $getDetailPackage)
                        ->with('dataMyPackage', $getMyPackage);
    }
    
    public function getCekPlacementKiriKanan($id, $type){
        $posisi = 'kanan_id';
        if($type == 1){
            $posisi = 'kiri_id';
        }
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $dataUser = Auth::user();
        $modelMember = New Member;
        $getUplineId = $dataUser;
        if($id != $dataUser->id){
            $getUplineId = $modelMember->getUsers('id', $id);
        }
        if($getUplineId->$posisi != null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Posisi placement yang anda pilih telah terisi, pilih posisi yang lain');
        }
        $getDataDataCalon = $modelMember->getAllMemberToPlacement($dataUser);
        $jml = count($getDataDataCalon);
        if($jml == 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Tidak ada data member yang akan di placement');
        }
        return view('member.ajax.confirm_add_placement')
                        ->with('dataCalon', $getDataDataCalon)
                        ->with('check', $canInsert)
                        ->with('upline_id', $getUplineId->id)
                        ->with('type', $type)
                        ->with('dataUser', $dataUser);
    }
    
    public function getSearchUserCode(Request $request){
        $dataUser=  Auth::user();
        $modelMember = New Member;
        $downline = $dataUser->upline_detail.',['.$dataUser->id.']';
        if($dataUser->upline_detail == null){
            $downline = '['.$dataUser->id.']';
        }
        $getDownlineUsername = null;
        if($request->name != null){
            $getDownlineUsername = $modelMember->getMyDownlineUsername($downline, $request->name);
        }
//        dd($getDownlineUsername);
        return view('member.ajax.get_name_autocomplete')
                        ->with('getData', $getDownlineUsername)
                        ->with('dataUser', $dataUser);
    }
    
    public function getExplorerMemberByUserCode(Request $request){
        $dataUser=  Auth::user();
        $modelMember = New Member;
        $getUsername = null;
        if($request->name != null){
            if(strlen($request->name) >= 3){
                $getUsername = $modelMember->getExplorerUsername($dataUser->id, $request->name);
            }
        }
        return view('member.ajax.get_explore_user')
                        ->with('getData', $getUsername)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekRO(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelPin = new Pin;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $canInsert = $modelValidasi->getCheckRO($request, $getTotalPin, $dataUser);
        $data = (object) array(
            'total_pin' => $request->total_pin
        );
        return view('member.ajax.confirm_add_ro')
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function getCekConfirmWD(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelBank = New Bank;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $getMyActiveBank = $modelBank->getBankMemberActive($dataUser);
        $id_bank = null;
        if($getMyActiveBank != null){
            $id_bank = $getMyActiveBank->id;
        }
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin)),
            'admin_fee' => 6500,
            'bank' => $id_bank
        );
        $canInsert = $modelValidasi->getCheckWD($dataAll);
         return view('member.ajax.confirm_add_wd')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function getCekConfirmWDRoyalti(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelBank = New Bank;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonusRoyalti($dataUser);
        $totalWD = $modelWD->getTotalDiTransferRoyalti($dataUser);
        $getMyActiveBank = $modelBank->getBankMemberActive($dataUser);
        $id_bank = null;
        if($getMyActiveBank != null){
            $id_bank = $getMyActiveBank->id;
        }
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin)),
            'admin_fee' => 6500,
            'bank' => $id_bank
        );
        $canInsert = $modelValidasi->getCheckWD($dataAll);
         return view('member.ajax.confirm_add_wd_royalti')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function getCekConfirmWDeIDR(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
        $totalTopUp = $modelBonus->getTotalSaldoUserId($dataUser);
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'total_wd_eidr' => $totalWDeIDR->total_wd,
            'total_tunda_eidr' => $totalWDeIDR->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin + $totalWDeIDR->total_wd + $totalWDeIDR->total_tunda + $totalWDeIDR->total_fee_admin)),
            'admin_fee' => 5000,
            'tron' => $dataUser->tron
        );
        $canInsert = $modelValidasi->getCheckWDeIDR($dataAll);
         return view('member.ajax.confirm_add_wdeidr')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function getCekAddTron(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $modelMember = New Member;
        $data = (object) array(
            'tron' => $request->tron
        );
        if($request->tron == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat TRON harus diisi');
            return view('member.ajax.confirm_add_tron')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        if(strpos($request->tron, ' ') !== false){
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat TRON tidak boleh ada spasi');
            return view('member.ajax.confirm_add_tron')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        if(strlen($request->tron) != 34){
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat TRON harus 34 karakter');
            return view('member.ajax.confirm_add_tron')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        return view('member.ajax.confirm_add_tron')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function getSearchByType($type, Request $request){
        $modelMember = New Member;
        $getData = null;
        if($type == 'kota'){
            if($request->provinsi != 0){
                $getData = $modelMember->getKabupatenKotaByPropinsi($request->provinsi);
            }
        }
        if($type == 'kecamatan'){
            if($request->kota != 0){
                $dataKec = explode('.', $request->kota);
                $getData = $modelMember->getKecamatanByKabupatenKota($dataKec[0], $dataKec[1]);
            }
        }
        if($type == 'kelurahan'){
            if($request->kecamatan != 0){
                $dataKel = explode('.', $request->kecamatan);
                $getData = $modelMember->getKelurahanByKecamatan($dataKel[0], $dataKel[1], $dataKel[2]);
            }
        }
        return view('member.ajax.searchDaerah')
                        ->with('type', $type)
                        ->with('getData', $getData);
    }
    
    public function getCekRequestMemberStockist(Request $request){
        $dataUser = Auth::user();
        $modelMember = New Member;
        $modelValidasi = New Validation;
        $cekHU1 = null;
        if($request->hu1 != null){
            $getHU1 = $modelMember->getCekHakUsaha($dataUser, $request->hu1);
            if($getHU1 != null){
                $cekHU1 = $getHU1->id;
            }
        }
        $cekHU2 = null;
        if($request->hu2 != null){
            $getHU2 = $modelMember->getCekHakUsaha($dataUser, $request->hu2);
            if($getHU2 != null){
                $cekHU2 = $getHU2->id;
            }
        }
        $cekHU3 = null;
        if($request->hu3 != null){
            $getHU3 = $modelMember->getCekHakUsaha($dataUser, $request->hu3);
            if($getHU3 != null){
                $cekHU3 = $getHU3->id;
            }
        }
        $dataAll = (object) array(
            'syarat1' => $request->syarat1,
            'syarat2' => $request->syarat2,
            'syarat3' => $request->syarat3,
            'syarat4' => $request->syarat4,
            'hu1' => $cekHU1,
            'hu2' => $cekHU2,
            'hu3' => $cekHU3,
            'total_sp' => $dataUser->total_sponsor,
            'alamat' => $dataUser
        );
        $canInsert = $modelValidasi->getCheckRequestStockist($dataAll);
        return view('member.ajax.confirm_request_stockistr')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function getStockistCekSoping(Request $request){
        $dataUser = Auth::user();
        $idPurchase = $request->id_barang;
        $quantity = $request->total_buy;
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getDetailPurchase($idPurchase);
        return view('member.ajax.confirm_buy_barang')
                        ->with('getData', $getData)
                        ->with('qty', $quantity);
    }
    
    public function getCekSoping(Request $request){
        $dataUser = Auth::user();
        $idPurchase = $request->id_barang;
        $quantity = $request->total_buy;
        $stokist_id = $request->stokist_id;
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getDetailPurchase($idPurchase);
        return view('member.ajax.confirm_m_buy_barang')
                        ->with('getData', $getData)
                        ->with('stokist_id', $stokist_id)
                        ->with('qty', $quantity);
    }
    
    public function getCekEditAddress(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelMember = New Member;
        $canInsert = $modelValidasi->getCheckEditAddress($request);
        $provinsi = $modelMember->getProvinsiByID($request->provinsi);
        if($provinsi == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Provinsi harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kota = $modelMember->getNamaByKode($request->kota);
        if($kota == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kabupaten/Kota harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kode = $kota->kode;
        $kecamatan = $modelMember->getNamaByKode($request->kecamatan);
        if($kecamatan == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kecamatan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kode = $kecamatan->kode;
        $kelurahan = $modelMember->getNamaByKode($request->kelurahan);
        if($kelurahan == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Kelurahan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $kode = $kelurahan->kode;
        $data = (object) array(
            'alamat' => $request->alamat,
            'provinsi' => $provinsi->nama,
            'kode_pos' => $request->kode_pos,
            'kota' => $kota->nama,
            'kecamatan' => $kecamatan->nama,
            'kelurahan' => $kelurahan->nama,
            'kode_daerah' => $kode,
        );
        return view('member.ajax.confirm_edit_address')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekConfirmClaimReward(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $getData = $modelBonusSetting->getActiveBonusRewardByID($request->reward_id);
        $dataAll = (object) array(
            'reward_detail' => $getData->reward_detail,
            'reward_id' => $getData->id
        );
         return view('member.ajax.confirm_reward_detail')
                        ->with('data', $dataAll);
    }
    
    public function getCekMemberPembayaran(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $modelMember = New Member;
        $modelBank = New Bank;
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($request->sale_id);
        $getStockist = $modelMember->getUsers('id', $getDataMaster->stockist_id);
        if($request->buy_metode == 1){
            $buy_metode = 1;
        }
        if($request->buy_metode == 2){
            $buy_metode = 2;
            $getStockistBank = $modelBank->getBankMemberActive($getStockist);
            $bank_name = $getStockistBank->bank_name;
            $account_no = $getStockistBank->account_no;
            $account_name = $getStockistBank->account_name;
        }
        if($request->buy_metode == 3){
            $buy_metode = 3;
            $tron = $getStockist->tron;
        }
        $dataAll = (object) array(
            'buy_metode' => $buy_metode,
            'getDataMaster' => $getDataMaster,
            'getStockist' => $getStockist,
            'tron' => $tron,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
        );
        return view('member.ajax.confirm_member_pembayaran')
                        ->with('data', $dataAll);
        
    }
    
    public function postCekAddRequestStock(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert =  (object) array('can' => true, 'pesan' => '');
        if($request->metode == 'undefined'){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih metode pembayaran');
            return view('member.ajax.confirm_add_stock')
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        if($request->metode == 2){
            $bank_name = 'BRI';
            $account_no = '033601001795562';
            $account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if($request->metode == 3){
            $tron = 'TWJtGQHBS88PfZTXvWAYhQEMrx36eX2F9Pc';
        }
        $data = (object) array(
            'id_master' => $request->id_master,
            'royalti' => $request->royalti,
            'buy_metode' => $request->metode,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
            'tron' => $tron
        );
        return view('member.ajax.confirm_add_stock')
                            ->with('check', $canInsert)
                            ->with('data', $data);
    }
    
    public function postCekRejectRequestStock(Request $request){
        $data = (object) array('id_master' => $request->id_master);
        return view('member.ajax.confirm_reject_stock')
                        ->with('data', $data);
    }
    
    public function postCekAddRoyalti(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $royalti_metode = $request->metode;
        if($royalti_metode == 'undefined'){
            $canInsert = (object) array('can' => false, 'pesan' => 'Metode transfer royalti belum diipih');
            return view('member.ajax.confirm_add_royalti')
                            ->with('dataRequest', null)
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id_master, $dataUser->id);
        $royalti_tron = null;
        $royalti_bank_name = null;
        $royalti_account_no = null;
        $royalti_account_name = null;
        if($royalti_metode == 1){
            $royalti_bank_name = 'BRI';
            $royalti_account_no = '033601001795562';
            $royalti_account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if($royalti_metode == 2){
            $royalti_tron = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        }
        $data = (object) array(
            'id_master' => $id_master,
            'royalti_metode' => $royalti_metode,
            'royalti_bank_name' => $royalti_bank_name,
            'royalti_account_no' => $royalti_account_no,
            'royalti_account_name' => $royalti_account_name,
            'royalti_tron' => $royalti_tron
        );
        return view('member.ajax.confirm_add_royalti')
                        ->with('getDataSales', $getDataSales)
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function postCekConfirmPembelian(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.confirm_confirm_pembelian')
                        ->with('getDataSales', $getDataSales)
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function postCekRejectPembelian(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.reject_pembelian')
                        ->with('getDataSales', $getDataSales)
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function getCekConfirmBelanjaReward(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($dataUser->is_tron == 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_belanja')
                        ->with('data', null)
                        ->with('check', $canInsert);
        }
        $modelSales = New Sales;
        $getData = $modelSales->getMemberMasterSalesMonthYear($dataUser->id, $request->m, $request->y);
         return view('member.ajax.confirm_reward_belanja')
                        ->with('data', $getData)
                        ->with('check', $canInsert);
    }
    
    public function getCekConfirmPenjualanReward(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($dataUser->is_tron == 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_penjualan')
                        ->with('data', null)
                        ->with('check', $canInsert);
        }
        $modelSales = New Sales;
        $getData = $modelSales->getStockistPenjualanMonthYear($dataUser->id, $request->m, $request->y);
         return view('member.ajax.confirm_reward_penjualan')
                        ->with('data', $getData)
                        ->with('check', $canInsert);
    }
    
    public function getSearchUserCodeStockist(Request $request){
        $dataUser=  Auth::user();
        $modelMember = New Member;
        $getDownlineUsername = null;
        if($request->name != null){
            $getDownlineUsername = $modelMember->getMyDownlineUsernameStockist($request->name);
        }
        return view('member.ajax.get_name_autocomplete')
                        ->with('getData', $getDownlineUsername)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekConfirmTopUp(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalTopUp = $request->input_jml_topup;
        
        $dataAll = (object) array(
            'req_topup' => (int) $totalTopUp,
            'tron' => $dataUser->tron
        );
        $canInsert = $modelValidasi->getCheckTopUp($dataAll);
         return view('member.ajax.confirm_add_topup')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function getCekTopupTransaction(Request $request){
        $dataUser = Auth::user();
        $modelBank = New Bank;
        $modelBonus = New Bonus;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if(count($separate) == 2){
            $cekType = $separate[0];
            $bankId = $separate[1];
            $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
        }
        $getTrans = $modelBonus->getTopUpSaldoID($request->id_topup, $dataUser);
        $data = (object) array('id_topup' => $request->id_topup);
        return view('member.ajax.confirm_topup_transaction')
                        ->with('bankPerusahaan', $getPerusahaanBank)
                        ->with('getTrans', $getTrans)
                        ->with('cekType', $cekType)
                        ->with('data', $data);
    }
    
    public function getCekRejectTopup(Request $request){
        $data = (object) array('id_topup' => $request->id_topup);
        return view('member.ajax.confirm_reject_topup')
                        ->with('data', $data);
    }
    
    public function getCekEditPassword(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($request->password == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Password harus diisii');
            return view('member.ajax.confirm_edit_password')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        if(strpos($request->repassword, ' ') !== false){
            $canInsert = (object) array('can' => false, 'pesan' => 'Ketik ulang password harus diisi');
            return view('member.ajax.confirm_edit_password')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        if($request->password != $request->repassword){
            $canInsert = (object) array('can' => false, 'pesan' => 'Password tidak sama');
            return view('member.ajax.confirm_edit_password')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        if(strlen($request->password) < 6){
            $canInsert = (object) array('can' => false, 'pesan' => 'Password terlalu pendek, minimal 6 karakter');
            return view('member.ajax.confirm_edit_password')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        $data = (object) array(
            'password' => $request->password
        );
        return view('member.ajax.confirm_edit_password')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }
    
    public function getCekRequestMemberVendor(Request $request){
        $dataUser = Auth::user();
        $modelMember = New Member;
        $modelValidasi = New Validation;
        $cekHU1 = null;
        if($request->hu1 != null){
            $getHU1 = $modelMember->getCekHakUsaha($dataUser, $request->hu1);
            if($getHU1 != null){
                $cekHU1 = $getHU1->id;
            }
        }
        $cekHU2 = null;
        if($request->hu2 != null){
            $getHU2 = $modelMember->getCekHakUsaha($dataUser, $request->hu2);
            if($getHU2 != null){
                $cekHU2 = $getHU2->id;
            }
        }
        $cekHU3 = null;
        if($request->hu3 != null){
            $getHU3 = $modelMember->getCekHakUsaha($dataUser, $request->hu3);
            if($getHU3 != null){
                $cekHU3 = $getHU3->id;
            }
        }
        $cekHU4 = null;
        if($request->hu4 != null){
            $getHU4 = $modelMember->getCekHakUsaha($dataUser, $request->hu4);
            if($getHU4 != null){
                $cekHU4 = $getHU4->id;
            }
        }
        $cekHU5 = null;
        if($request->hu5 != null){
            $getHU5 = $modelMember->getCekHakUsaha($dataUser, $request->hu5);
            if($getHU5 != null){
                $cekHU5 = $getHU5->id;
            }
        }
        $dataAll = (object) array(
            'syarat1' => $request->syarat1,
            'syarat2' => $request->syarat2,
            'syarat3' => $request->syarat3,
            'syarat4' => $request->syarat4,
            'hu1' => $cekHU1,
            'hu2' => $cekHU2,
            'hu3' => $cekHU3,
            'hu4' => $cekHU4,
            'hu5' => $cekHU5,
            'total_sp' => $dataUser->total_sponsor,
            'alamat' => $dataUser
        );
        $canInsert = $modelValidasi->getCheckRequestVendor($dataAll);
        return view('member.ajax.confirm_request_vendor')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function getSearchUserCodeVendor(Request $request){
        $dataUser=  Auth::user();
        $modelMember = New Member;
        $getDownlineUsername = null;
        if($request->name != null){
            $getDownlineUsername = $modelMember->getMyDownlineUsernameVendor($request->name);
        }
        return view('member.ajax.get_name_autocomplete')
                        ->with('getData', $getDownlineUsername)
                        ->with('dataUser', $dataUser);
    }
    
    public function postCekAddRequestVStock(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert =  (object) array('can' => true, 'pesan' => '');
        if($request->metode == 'undefined'){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih metode pembayaran');
            return view('member.ajax.confirm_add_vstock')
                            ->with('check', $canInsert)
                            ->with('dataUser', $dataUser);
        }
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        if($request->metode == 2){
            $bank_name = 'BRI';
            $account_no = '033601001795562';
            $account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if($request->metode == 3){
            $tron = 'TWJtGQHBS88PfZTXvWAYhQEMrx36eX2F9Pc';
        }
        $data = (object) array(
            'id_master' => $request->id_master,
            'royalti' => $request->royalti,
            'buy_metode' => $request->metode,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
            'tron' => $tron
        );
        return view('member.ajax.confirm_add_vstock')
                            ->with('check', $canInsert)
                            ->with('data', $data);
    }
    
    public function postCekRejectRequestVStock(Request $request){
        $data = (object) array('id_master' => $request->id_master);
        return view('member.ajax.confirm_reject_vstock')
                        ->with('data', $data);
    }
    
    public function getCekMemberVPembayaran(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $modelMember = New Member;
        $modelBank = New Bank;
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranVMasterSales($request->sale_id);
        $getStockist = $modelMember->getUsers('id', $getDataMaster->vendor_id);
        if($request->buy_metode == 1){
            $buy_metode = 1;
        }
        if($request->buy_metode == 2){
            $buy_metode = 2;
            $getStockistBank = $modelBank->getBankMemberActive($getStockist);
            $bank_name = $getStockistBank->bank_name;
            $account_no = $getStockistBank->account_no;
            $account_name = $getStockistBank->account_name;
        }
        if($request->buy_metode == 3){
            $buy_metode = 3;
            $tron = $getStockist->tron;
        }
        $dataAll = (object) array(
            'buy_metode' => $buy_metode,
            'getDataMaster' => $getDataMaster,
            'getStockist' => $getStockist,
            'tron' => $tron,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
        );
        return view('member.ajax.confirm_member_vpembayaran')
                        ->with('data', $dataAll);
        
    }
    
    public function postCekConfirmVPembelian(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesVendorDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.confirm_confirm_vpembelian')
                        ->with('getDataSales', $getDataSales)
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function postCekRejectVPembelian(Request $request){
        $dataUser = Auth::user();
        $modelSales = New Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesVendorDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.reject_vpembelian')
                        ->with('getDataSales', $getDataSales)
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function getCekConfirmVPenjualanReward(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($dataUser->is_tron == 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_vpenjualan')
                        ->with('data', null)
                        ->with('check', $canInsert);
        }
        $modelSales = New Sales;
        $getData = $modelSales->getVendorPenjualanMonthYear($dataUser->id, $request->m, $request->y);
         return view('member.ajax.confirm_reward_vpenjualan')
                        ->with('data', $getData)
                        ->with('check', $canInsert);
    }
    
    public function getCekConfirmVBelanjaReward(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($dataUser->is_tron == 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_vbelanja')
                        ->with('data', null)
                        ->with('check', $canInsert);
        }
        $modelSales = New Sales;
        $getData = $modelSales->getMemberVMasterSalesMonthYear($dataUser->id, $request->m, $request->y);
         return view('member.ajax.confirm_reward_vbelanja')
                        ->with('data', $getData)
                        ->with('check', $canInsert);
    }
    
    public function postCekAddDeposit(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $canInsert = $modelValidasi->getCheckAddDeposit($request, $dataUser);
        $data = (object) array(
            'total_deposit' => $request->total_deposit
        );
        return view('member.ajax.confirm_add_deposit')
                        ->with('check', $canInsert)
                        ->with('data', $data);
    }
    
    public function postCekAddDepositTransaction(Request $request){
        $dataUser = Auth::user();
        $modelBank = New Bank;
        $modelTrans = New Transaction;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if(count($separate) == 2){
            $cekType = $separate[0];
            $bankId = $separate[1];
            if($cekType == 0){
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($bankId);
            }
        }
        $getTrans = $modelTrans->getDetailDepositTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_add_deposit_transaction')
                        ->with('bankPerusahaan', $getPerusahaanBank)
                        ->with('getTrans', $getTrans)
                        ->with('cekType', $cekType)
                        ->with('data', $data);
    }
    
    public function postCekRejectDepositTransaction(Request $request){
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_reject_deposit_transaction')
                        ->with('data', $data);
    }
    
    public function postCekTarikDeposit(Request $request){
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $separate = explode('_', $request->id_bank);
        if(count($separate) != 2){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pilih metode penarikan');
            return view('member.ajax.confirm_tarik_deposit')
                        ->with('check', $canInsert)
                        ->with('data', null);
        }
        if(!is_numeric($request->total_deposit)){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nominal harus dalam angka');
            return view('member.ajax.confirm_tarik_deposit')
                        ->with('check', $canInsert)
                        ->with('data', null);
        }
        if($request->total_deposit <= 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Nominal harus diatas 0');
            return view('member.ajax.confirm_tarik_deposit')
                        ->with('check', $canInsert)
                        ->with('data', null);
        }
        $modelBank = New Bank;
        $cekType = $separate[0];
        $bankId = $separate[1];
        if($cekType == 0){
            $getUserBank = $modelBank->getBankMemberID($bankId, $dataUser);
        } else {
            $getUserBank = $dataUser;
        }
        $modelPin = new Pin;
        $modelTrans = New Transaction;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $dataDeposit = $modelPin->getTotalDepositMember($dataUser);
        $sum_deposit_masuk = 0;
        $sum_deposit_keluar = 0;
        if($dataDeposit->sum_deposit_masuk != null){
            $sum_deposit_masuk = $dataDeposit->sum_deposit_masuk;
        }
        if($getTransTarik->deposit_keluar != null){
            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar;
        if($totalDeposit <  $request->total_deposit){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak bisa tarik deposit. Saldo deposit kosong atau kurang');
            return view('member.ajax.confirm_tarik_deposit')
                        ->with('check', $canInsert)
                        ->with('data', null);
        }
        $data = (object) array(
            'total_deposit' => $request->total_deposit
        );
        return view('member.ajax.confirm_tarik_deposit')
                        ->with('check', $canInsert)
                        ->with('metode', $cekType)
                        ->with('getUserBank', $getUserBank)
                        ->with('data', $data);
    }
    
    public function postCekAddTarikTransaction(Request $request){
        $dataUser = Auth::user();
        $modelBank = New Bank;
        $modelTrans = New Transaction;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if(count($separate) == 2){
            $cekType = $separate[0];
            $bankId = $separate[1];
            if($cekType == 0){
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($bankId);
            }
        }
        $getTrans = $modelTrans->getDetailDepositTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_add_tarik_transaction')
                        ->with('bankPerusahaan', $getPerusahaanBank)
                        ->with('getTrans', $getTrans)
                        ->with('cekType', $cekType)
                        ->with('data', $data);
    }
    
    public function postCekRejectTarikTransaction(Request $request){
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_reject_tarik_transaction')
                        ->with('data', $data);
    }
    
    public function getCekPOBX(Request $request){
        $dataUser = Auth::user();
        $modelValidasi = New Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelMember = New Member;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if($request->jaringan == null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih jaringan');
            return view('member.ajax.confirm_add_pulsa')
                        ->with('check', $canInsert)
                        ->with('data', null);
        }
        $jaringan = $request->jaringan;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username.$apiKey.'pl');
        $json = '{
                    "commands" : "pricelist",
                    "username" : "'.$username.'",
                    "sign"     : "'.$sign.'", 
                    "status" : "active"
            }';
        $url = $getDataAPI->master_url.'/v1/legacy/index/pulsa/'.$jaringan;
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        $totalBonusAll = $modelBonus->getTotalBonusSponsor($dataUser);
        if($totalBonusAll == 0){
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak memiliki bonus Sponsor');
            return view('member.ajax.confirm_add_pulsa')
                        ->with('check', $canInsert)
                        ->with('data', null);
        }
        $totalWD = $modelWD->getTotalDiTransferByType($dataUser, 1);
        $dataAll = (object) array(
            'total_bonus_get' => $totalBonusAll,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'saldo' => (int) ($totalBonusAll - ($totalWD->total_wd + $totalWD->total_tunda )),
            'wd_fee' => $totalWD->total_wd_admin_fee,
            'wd_tunda_fee' => $totalWD->total_tunda_admin_fee,
            'jaringan' => $jaringan, 
            //'data_jaringan' => $arrayData['data']
            'data_jaringan' => array(
                array(
                    "pulsa_code" => "htelkomsel1000",
                    "pulsa_op" => "Telkomsel",
                    "pulsa_nominal" => "1000",
                    "pulsa_price" => 1900,
                    "pulsa_type" => "pulsa",
                    "masaaktif" => "3",
                    "status" => "active"
                ),
                array(
                    "pulsa_code" => "htelkomsel10000",
                    "pulsa_op" => "Telkomsel",
                    "pulsa_nominal" => "10000",
                    "pulsa_price" => 10900,
                    "pulsa_type" => "pulsa",
                    "masaaktif" => "15",
                    "status" => "active",
                ),
                array(
                    "pulsa_code" => "htelkomsel20000",
                    "pulsa_op" => "Telkomsel",
                    "pulsa_nominal" => "20000",
                    "pulsa_price" => 20500,
                    "pulsa_type" => "pulsa",
                    "masaaktif" => "30",
                    "status" => "active",
                )
            )
        );
         return view('member.ajax.confirm_add_pulsa')
                        ->with('check', $canInsert)
                        ->with('data', $dataAll);
    }
    
    public function postCekBuyPPOBHP(Request $request){
        //cek validasi lg
        //cek vendor punya deposit ga
        $dataUser = Auth::user();
        $no_hp = $request->no_hp;
        if($no_hp == null){
            return view('member.ajax.confirm_cek_ppob')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memasukan nomor')
                        ->with('dataVendor', null);
        }
        $vendor_id = $request->vendor_id;
        $dataVendor = (object) array(
            'id' => $vendor_id
        );
        if($vendor_id == null){
            return view('member.ajax.confirm_cek_ppob')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memilih Vendor')
                        ->with('dataVendor', null);
        }
        if($request->harga == 'undefined'){
            return view('member.ajax.confirm_cek_ppob')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memilih harga')
                        ->with('dataVendor', null);
        }
        $buy_method = $request->type_pay;
        if($buy_method == 'undefined'){
            return view('member.ajax.confirm_cek_ppob')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memilih jenis pembayaran')
                        ->with('dataVendor', null);
        }
        
        $modelPin = new Pin;
        $modelTrans = New Transaction;
        //cek $no_hp ga boleh dalam 10 menit
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if($cekHP != null){
            return view('member.ajax.confirm_cek_ppob')
                        ->with('data', null)
                        ->with('message', 'Nomor HP ini masih dalam rentang 10 menit.')
                        ->with('dataVendor', null);
        }
        $separate = explode('__', $request->harga);
        $buyer_sku_code = $separate[0];
        $price = $separate[1];
        $brand = $separate[2];
        $desc = $separate[3];
        $real_price = $separate[4];
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataVendor);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataVendor);
        $getTotalPPOBOut = $modelPin->getPPOBFly($vendor_id);
        $sum_deposit_masuk = 0;
        $sum_deposit_keluar1 = 0;
        $sum_deposit_keluar = 0;
        $sum_ppob_keluar = 0;
        if($getTotalDeposit->sum_deposit_masuk != null){
            $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
        }
        if($getTotalDeposit->sum_deposit_keluar != null){
            $sum_deposit_keluar1 = $getTotalDeposit->sum_deposit_keluar;
        }
        if($getTransTarik->deposit_keluar != null){
            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        }
        if($getTotalPPOBOut->deposit_out != null){
            $sum_ppob_keluar = $getTotalPPOBOut->deposit_out;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1 - $sum_ppob_keluar - $real_price;
        if($totalDeposit < 0){
            return view('member.ajax.confirm_cek_ppob')
                        ->with('data', null)
                        ->with('message', 'tidak dapat dilanjutkan, saldo vendor kurang')
                        ->with('dataVendor', null);
        }
        
        $modelMember = New Member;
        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'price' => $price,
            'brand' => $brand,
            'no_hp' => $no_hp,
            'vendor_id' => $vendor_id,
            'buy_method' => $buy_method,
            'harga_modal' => $real_price,
            'message' => $desc
        );
        $getVendor = $modelMember->getUsers('id', $vendor_id);
        $type = $request->type;
        return view('member.ajax.confirm_cek_ppob')
                        ->with('data', $getData)
                        ->with('type', $type)
                        ->with('dataVendor', $getVendor);
    }
    
    public function postCekBuyPPOBPasca(Request $request){
        //cek validasi lg
        //cek vendor punya deposit ga
        $dataUser = Auth::user();
        $no_hp = $request->no_hp;
        if($no_hp == null){
            return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memasukan nomor')
                        ->with('dataVendor', null);
        }
        $vendor_id = $request->vendor_id;
        $dataVendor = (object) array(
            'id' => $vendor_id
        );
        if($vendor_id == null){
            return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memilih Vendor')
                        ->with('dataVendor', null);
        }
        if($request->harga == 'undefined'){
            return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memilih harga')
                        ->with('dataVendor', null);
        }
        $buy_method = $request->type_pay;
        if($buy_method == 'undefined'){
            return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', null)
                        ->with('message', 'Anda tidak memilih jenis pembayaran')
                        ->with('dataVendor', null);
        }
        
        $modelPin = new Pin;
        $modelTrans = New Transaction;
        //cek $no_hp ga boleh dalam 10 menit
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if($cekHP != null){
            return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', null)
                        ->with('message', 'Nomor HP ini masih dalam rentang 10 menit.')
                        ->with('dataVendor', null);
        }
        $buyer_sku_code = $request->buyer_sku_code;
        $price = $request->harga;
        $real_price = $request->price;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataVendor);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataVendor);
        $getTotalPPOBOut = $modelPin->getPPOBFly($vendor_id);
        $sum_deposit_masuk = 0;
        $sum_deposit_keluar1 = 0;
        $sum_deposit_keluar = 0;
        $sum_ppob_keluar = 0;
        if($getTotalDeposit->sum_deposit_masuk != null){
            $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
        }
        if($getTotalDeposit->sum_deposit_keluar != null){
            $sum_deposit_keluar1 = $getTotalDeposit->sum_deposit_keluar;
        }
        if($getTransTarik->deposit_keluar != null){
            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        }
        if($getTotalPPOBOut->deposit_out != null){
            $sum_ppob_keluar = $getTotalPPOBOut->deposit_out;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1 - $sum_ppob_keluar - $real_price;
        if($totalDeposit < 0){
            return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', null)
                        ->with('message', 'tidak dapat dilanjutkan, saldo vendor kurang')
                        ->with('dataVendor', null);
        }
        $type = $request->type;
        $modelMember = New Member;
        $typeName = 'Pembayaran BPJS';
        if($type == 5){
            $typeName = 'Pembayaran PLN Pascabayar';
        }
        if($type == 6){
            $typeName = 'Pembayaran HP Pascabayar';
        }
        if($type == 7){
            $typeName = 'Pembayaran TELKOM PTSN';
        }
        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'price' => $price,
            'no_hp' => $no_hp,
            'vendor_id' => $vendor_id,
            'buy_method' => $buy_method,
            'harga_modal' => $real_price,
            'message' => $typeName,
            'ref_id' => $request->ref_id
        );
        $getVendor = $modelMember->getUsers('id', $vendor_id);
        return view('member.ajax.confirm_cek_ppob_pasca')
                        ->with('data', $getData)
                        ->with('type', $type)
                        ->with('dataVendor', $getVendor);
    }
    
    public function postMemberBuyPPOBHP(Request $request){
        $dataUser = Auth::user();
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($request->ppob_id, $dataUser);
        $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
        return view('member.ajax.confirm_pembayaran_ppob')
                        ->with('data', $getDataMaster)
                        ->with('dataVendor', $getVendor);
    }
    
    public function postRejectBuyPPOBHP(Request $request){
        $dataUser = Auth::user();
        $id_ppob = $request->id_ppob;
        return view('member.ajax.reject_pembayaran_ppob')
                        ->with('id_ppob', $id_ppob)
                        ->with('dataUser', $dataUser);
    }

    
    
    
    
    
}
