<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Admin;
use App\Model\Package;
use App\Model\Transaction;
use App\Model\Bank;
use App\Model\Pengiriman;
use App\Model\Transferwd;
use App\Model\Bonus;

class AjaxController extends Controller {

    public function __construct(){
        
    }
    
    public function getAdminById($type, $id){
        $dataUser = Auth::user();
        $getType = 0;
        $header = 'Empty';
        if($type == 1){
            $header = 'Edit';
            $getType = 1;
        }
        if($type == 2){
            $header = 'Delete';
            $getType = 2;
        }
        $modelAdmin = New Admin;
        $getData = null;
        if($id > 2){
            $getData = $modelAdmin->getAdminById($id);
        }
        return view('admin.ajax.admin')
                ->with('headerTitle', $header.' Admin')
                ->with('getData', $getData)
                ->with('type', $getType)
                ->with('dataUser', $dataUser);
    }
    
    public function getPackageById($id){
        $dataUser = Auth::user();
        $modelPackage = New Package;
        $getPackageId = $modelPackage->getPackageId($id);
        return view('admin.ajax.package')
                ->with('headerTitle', 'Edit Package')
                ->with('getData', $getPackageId)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekTransactionById($id, $user_id, $is_tron){
        $modelSettingTrans = New Transaction;
        $getData = $modelSettingTrans->getDetailTransactionsAdminNew($id, $user_id, $is_tron);
        return view('admin.ajax.transaction')
                ->with('headerTitle', 'Cek Transaksi')
                ->with('getData', $getData);
    }
    
    public function getRejectTransactionById($id, $user_id, $is_tron){
        $modelSettingTrans = New Transaction;
        $getData = $modelSettingTrans->getDetailTransactionsAdminNew($id, $user_id, $is_tron);
        return view('admin.ajax.reject-transaction')
                ->with('headerTitle', 'Reject Transaksi')
                ->with('getData', $getData);
    }
    
    public function getBankPerusahaan($id){
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $getPerusahaanBank = $modelBank->getBankPerusahaanID($id);
        return view('admin.ajax.bank')
                ->with('headerTitle', 'Edit Bank Perusahaan')
                ->with('getData', $getPerusahaanBank)
                ->with('dataUser', $dataUser);
    }
    
    public function getTronPerusahaan($id){
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $getPerusahaanTron = $modelBank->getTronPerusahaanID($id);
        return view('admin.ajax.tron')
                ->with('headerTitle', 'Edit Tron Perusahaan')
                ->with('getData', $getPerusahaanTron)
                ->with('dataUser', $dataUser);
    }
    
    public function getKirimPaket($id, $user_id){
        $dataUser = Auth::user();
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($id, $user_id);
        return view('admin.ajax.pengiriman')
                ->with('headerTitle', 'Confirm Pengiriman')
                ->with('getData', $getPengiriman)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekKirimPaket(Request $request){
        $dataUser = Auth::user();
        $id =$request->cekId;
        $user_id = $request->cekUserId;
        $kurir_name = $request->kurir_name;
        $no_resi = $request->no_resi;
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($id, $user_id);
        $data = (object) array(
            'id' => $id,
            'user_id' => $user_id,
            'kurir_name' => $kurir_name,
            'no_resi' => $no_resi
        );
        return view('admin.ajax.pengiriman')
                ->with('headerTitle', 'Confirm Pengiriman')
                ->with('getData', $getPengiriman)
                ->with('data', $data)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekRejectWD($id){
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWD($id);
        return view('admin.ajax.reject-wd')
                ->with('headerTitle', 'Reject Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekDetailWD($id){
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWD($id);
        return view('admin.ajax.detail-wd')
                ->with('headerTitle', 'Detail Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekRejectWDeIDR($id){
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDeIDR($id);
        return view('admin.ajax.reject-wd-eidr')
                ->with('headerTitle', 'Reject Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekDetailWDeIDR($id){
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDeIDR($id);
        return view('admin.ajax.detail-wd-eidr')
                ->with('headerTitle', 'Detail Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    
      
    
    

}
