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
use App\Model\Member;
use App\Model\Bonussetting;
use App\Model\Sales;

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
        $getData = $modelSettingTrans->getDetailRejectTransactionsAdmin($id, $user_id, $is_tron);
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
    
    public function getCekRejectWDRoyalti($id){
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDRoyalti($id);
        return view('admin.ajax.reject-wd-royalti')
                ->with('headerTitle', 'Reject Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekDetailWDRoyalti($id){
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDRoyalti($id);
        return view('admin.ajax.detail-wd-royalti')
                ->with('headerTitle', 'Detail Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekRequestMemberStockist($id){
        $modelMember = New Member;
        $getData = $modelMember->getCekMemberReqSotckist($id);
        return view('admin.ajax.cek_req_stockist')
                ->with('headerTitle', 'Cek Request Stockist')
                ->with('getData', $getData);
    }
    
    public function getCekRejectMemberStockist($id){
        $modelMember = New Member;
        $getData = $modelMember->getCekMemberReqSotckist($id);
        return view('admin.ajax.cek_reject_stockist')
                ->with('headerTitle', 'Reject Request Stockist')
                ->with('getData', $getData);
    }
    
    public function getCekRemoveMemberStockist($id){
        $modelMember = New Member;
        $getData = $modelMember->getCekMemberSotckistToRemove($id);
        return view('admin.ajax.cek_remove_stockist')
                ->with('headerTitle', 'Remove Member Stockist')
                ->with('getData', $getData);
    }
    
    public function getCekEditMemberStockist($id){
        $modelMember = New Member;
        $getData = $modelMember->getCekMemberSotckistToRemove($id);
        return view('admin.ajax.cek_edit_stockist')
                ->with('headerTitle', 'Edit Member Stockist')
                ->with('getData', $getData);
    }
    
    public function getEditBonusReward($id){
        $modelBonusSetting = new Bonussetting;
        $getData = $modelBonusSetting->getActiveBonusRewardByID($id);
        return view('admin.ajax.cek_reward')
                ->with('headerTitle', 'Edit Bonus Reward')
                ->with('getData', $getData);
    }
    
    public function getCekRejectClaimReward($id){
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminRewardByID($id);
        return view('admin.ajax.reject-claim-reward')
                ->with('headerTitle', 'Reject Claim Reward')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekDetailClaimReward($id){
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailRewardByID($id);
        return view('admin.ajax.detail-claim')
                ->with('headerTitle', 'Detail Withdrawal')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekRequestMemberInputStock($id, $user_id){
        $modelSales = New Sales;
        $getData = $modelSales->getMemberReqInputStockistItem($id);
        $getDataMaster = $modelSales->getMemberReqInputStockistID($id);
        return view('admin.ajax.cek_req_input_stock')
                ->with('headerTitle', 'Konfirmasi Input Stock & Royalti')
                ->with('getDataMaster', $getDataMaster)
                ->with('master_item_id', $id)
                ->with('user_id', $user_id)
                ->with('getData', $getData);
    }
    
    public function getCekRejectMemberInputStock($id, $user_id){
        $modelSales = New Sales;
        $getData = $modelSales->getMemberReqInputStockistItem($id);
        $getDataMaster = $modelSales->getMemberReqInputStockistID($id);
        return view('admin.ajax.cek_reject_input_stock')
                ->with('headerTitle', 'Reject Input Stock & Royalti')
                ->with('getDataMaster', $getDataMaster)
                ->with('master_item_id', $id)
                ->with('user_id', $user_id)
                ->with('getData', $getData);
    }
    
    public function getCekConfirmBelanjaStockist($id){
        $modelSales = New Sales;
        $modelMember = New Member;
        $getData = $modelSales->getAdminConfirmBelanjaID($id);
        return view('admin.ajax.cek_confirm_belanja')
                ->with('headerTitle', 'Detail Confirm Belanja')
                ->with('getData', $getData);
    }
    
    public function getCekVerificationRoyalti($id){
        $modelSales = New Sales;
        $getData = $modelSales->getAdminVerificationRoyaltiID($id);
        return view('admin.ajax.cek_confirm_royalti')
                ->with('headerTitle', 'Detail Verification Royalti')
                ->with('getData', $getData);
    }
    
    public function getCekRejectBelanjaReward($id){
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllBelanjaRewardByID($id);
        return view('admin.ajax.reject-belanja-reward')
                ->with('headerTitle', 'Reject Belanja Reward')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekDetailBelanjaReward($id){
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailBelanjaReward($id);
        return view('admin.ajax.detail-belanja')
                ->with('headerTitle', 'Detail Belanja Reward')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getRemovePurchaseId($id){
        $modelSales = New Sales;
        $getData = $modelSales->getDetailPurchase($id);
        return view('admin.ajax.cek_rm_product')
                ->with('headerTitle', 'Hapus Produk')
                ->with('getData', $getData);
    }
    
    public function getCekRejectPenjualanReward($id){
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllPenjualanRewardByID($id);
        return view('admin.ajax.reject-penjualan-reward')
                ->with('headerTitle', 'Reject Penjualan Reward')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getCekDetailPenjualanReward($id){
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailPenjualanReward($id);
        return view('admin.ajax.detail-penjualan')
                ->with('headerTitle', 'Detail Penjualan Reward')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAdminChangeDataMember($id){
        $dataUser = Auth::user();
        $modelMember = New Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-data')
                ->with('headerTitle', 'Change Data Member')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAdminChangePasswordMember($id){
        $dataUser = Auth::user();
        $modelMember = New Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-passwd')
                ->with('headerTitle', 'Change Password Member')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAdminChangeBlockMember($id){
        $dataUser = Auth::user();
        $modelMember = New Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-block')
                ->with('headerTitle', 'Blokir Data Member')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAdminChangeTronMember($id){
        $dataUser = Auth::user();
        $modelMember = New Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-tron')
                ->with('headerTitle', 'Change Tron Member')
                ->with('getData', $getData)
                ->with('dataUser', $dataUser);
    }
    
    public function getAdminGetCurrentPage(Request $request){
        $dataUser = Auth::user();
        $modelAdmin = New Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'GET '.$request->page
        );
        $modelAdmin->getInsertLogHistory($logHistory);
    }
    
    
      
    
    

}
