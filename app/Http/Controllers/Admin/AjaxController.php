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
    
    public function getCekTransactionById($id, $user_id){
        $modelSettingTrans = New Transaction;
        $getData = $modelSettingTrans->getDetailTransactionsAdmin($id, $user_id);
        return view('admin.ajax.transaction')
                ->with('headerTitle', 'Cek Transaksi')
                ->with('getData', $getData);
    }
    
    public function getBankPerusahaan(){
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $getPerusahaanBank = $modelBank->getBankPerusahaan();
        return view('admin.ajax.bank')
                ->with('headerTitle', 'Edit Bank Perusahaan')
                ->with('getData', $getPerusahaanBank)
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
    
    
      
    
    

}
