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
        $data = (object) array(
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
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
        $sisaPin = $cekPin->sum_pin_masuk - $cekPin->sum_pin_keluar;
        if($sisaPin < $request->total_pin){
            $canInsert = (object) array('can' => false, 'pesan' => 'Pn anda idak tersedia untuk transfer pin');
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
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'total_wd_eidr' => $totalWDeIDR->total_wd,
            'total_tunda_eidr' => $totalWDeIDR->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin + $totalWDeIDR->total_wd + $totalWDeIDR->total_tunda + $totalWDeIDR->total_fee_admin)),
            'admin_fee' => 6500,
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
        $cekTron = $modelMember->getCheckTron($request->tron);
        if($cekTron != null){
            $canInsert = (object) array('can' => false, 'pesan' => 'Gunakan alamat TRON yang lain');
            return view('member.ajax.confirm_add_tron')
                        ->with('dataRequest', null)
                        ->with('check', $canInsert);
        }
        return view('member.ajax.confirm_add_tron')
                        ->with('dataRequest', $data)
                        ->with('check', $canInsert)
                        ->with('dataUser', $dataUser);
    }

    
    
    
    
    
}
