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
use App\Model\Pin;
use App\TronModel;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;
use GuzzleHttp\Client;

class AjaxController extends Controller
{
    private $tron;

    public function __construct()
    {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        $fuse = Config::get('services.telegram.test');


        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer, $signServer = null, $explorer = null, $fuse);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        $this->tron = $tron;
    }

    public function getAdminById($type, $id)
    {
        $dataUser = Auth::user();
        $getType = 0;
        $header = 'Empty';
        if ($type == 1) {
            $header = 'Edit';
            $getType = 1;
        }
        if ($type == 2) {
            $header = 'Delete';
            $getType = 2;
        }
        $modelAdmin = new Admin;
        $getData = $modelAdmin->getAdminById($id);
        return view('admin.ajax.admin')
            ->with('headerTitle', $header . ' Admin')
            ->with('getData', $getData)
            ->with('type', $getType)
            ->with('dataUser', $dataUser);
    }

    public function getPackageById($id)
    {
        $dataUser = Auth::user();
        $modelPackage = new Package;
        $getPackageId = $modelPackage->getPackageId($id);
        return view('admin.ajax.package')
            ->with('headerTitle', 'Edit Package')
            ->with('getData', $getPackageId)
            ->with('dataUser', $dataUser);
    }

    public function getCekTransactionById($id, $user_id, $is_tron)
    {
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailTransactionsAdminNew($id, $user_id, $is_tron);
        return view('admin.ajax.transaction')
            ->with('headerTitle', 'Cek Transaksi')
            ->with('getData', $getData);
    }

    public function getRejectTransactionById($id, $user_id, $is_tron)
    {
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailRejectTransactionsAdmin($id, $user_id, $is_tron);
        return view('admin.ajax.reject-transaction')
            ->with('headerTitle', 'Reject Transaksi')
            ->with('getData', $getData);
    }

    public function getBankPerusahaan($id)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $getPerusahaanBank = $modelBank->getBankPerusahaanID($id);
        return view('admin.ajax.bank')
            ->with('headerTitle', 'Edit Bank Perusahaan')
            ->with('getData', $getPerusahaanBank)
            ->with('dataUser', $dataUser);
    }

    public function getTronPerusahaan($id)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $getPerusahaanTron = $modelBank->getTronPerusahaanID($id);
        return view('admin.ajax.tron')
            ->with('headerTitle', 'Edit Tron Perusahaan')
            ->with('getData', $getPerusahaanTron)
            ->with('dataUser', $dataUser);
    }

    public function getKirimPaket($id, $user_id)
    {
        $dataUser = Auth::user();
        $modelPengiriman = new Pengiriman;
        $getPengiriman = $modelPengiriman->getAdmPengirimanByID($id, $user_id);
        return view('admin.ajax.pengiriman')
            ->with('headerTitle', 'Confirm Pengiriman')
            ->with('getData', $getPengiriman)
            ->with('dataUser', $dataUser);
    }

    public function getCekKirimPaket(Request $request)
    {
        $dataUser = Auth::user();
        $id = $request->cekId;
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

    public function getCekRejectWD($id)
    {
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWD($id);
        return view('admin.ajax.reject-wd')
            ->with('headerTitle', 'Reject Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailWD($id)
    {
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWD($id);
        return view('admin.ajax.detail-wd')
            ->with('headerTitle', 'Detail Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekRejectWDeIDR($id)
    {
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDeIDR($id);
        return view('admin.ajax.reject-wd-eidr')
            ->with('headerTitle', 'Reject Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailWDeIDR($id)
    {
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDeIDR($id);
        return view('admin.ajax.detail-wd-eidr')
            ->with('headerTitle', 'Detail Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekRejectWDRoyalti($id)
    {
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDRoyalti($id);
        return view('admin.ajax.reject-wd-royalti')
            ->with('headerTitle', 'Reject Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailWDRoyalti($id)
    {
        $dataUser = Auth::user();
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDRequestWDRoyalti($id);
        return view('admin.ajax.detail-wd-royalti')
            ->with('headerTitle', 'Detail Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekRequestMemberStockist($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberReqSotckist($id);
        return view('admin.ajax.cek_req_stockist')
            ->with('headerTitle', 'Cek Request Stockist')
            ->with('getData', $getData);
    }

    public function getCekRejectMemberStockist($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberReqSotckist($id);
        return view('admin.ajax.cek_reject_stockist')
            ->with('headerTitle', 'Reject Request Stockist')
            ->with('getData', $getData);
    }

    public function getCekRemoveMemberStockist($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberSotckistToRemove($id);
        return view('admin.ajax.cek_remove_stockist')
            ->with('headerTitle', 'Remove Member Stockist')
            ->with('getData', $getData);
    }

    public function getCekEditMemberStockist($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberSotckistToRemove($id);
        return view('admin.ajax.cek_edit_stockist')
            ->with('headerTitle', 'Edit Member Stockist')
            ->with('getData', $getData);
    }

    public function getEditBonusReward($id)
    {
        $modelBonusSetting = new Bonussetting;
        $getData = $modelBonusSetting->getActiveBonusRewardByID($id);
        return view('admin.ajax.cek_reward')
            ->with('headerTitle', 'Edit Bonus Reward')
            ->with('getData', $getData);
    }

    public function getCekRejectClaimReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminRewardByID($id);
        return view('admin.ajax.reject-claim-reward')
            ->with('headerTitle', 'Reject Claim Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailClaimReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailRewardByID($id);
        return view('admin.ajax.detail-claim')
            ->with('headerTitle', 'Detail Withdrawal')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekRequestMemberInputStock($id, $user_id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputStockistItem($id);
        $getDataMaster = $modelSales->getMemberReqInputStockistID($id);
        return view('admin.ajax.cek_req_input_stock')
            ->with('headerTitle', 'Konfirmasi Input Stock & Royalti')
            ->with('getDataMaster', $getDataMaster)
            ->with('master_item_id', $id)
            ->with('user_id', $user_id)
            ->with('getData', $getData);
    }

    public function getCekRejectMemberInputStock($id, $user_id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputStockistItem($id);
        $getDataMaster = $modelSales->getMemberReqInputStockistID($id);
        return view('admin.ajax.cek_reject_input_stock')
            ->with('headerTitle', 'Reject Input Stock & Royalti')
            ->with('getDataMaster', $getDataMaster)
            ->with('master_item_id', $id)
            ->with('user_id', $user_id)
            ->with('getData', $getData);
    }

    public function getCekConfirmBelanjaStockist($id)
    {
        $modelSales = new Sales;
        $modelMember = new Member;
        $getData = $modelSales->getAdminConfirmBelanjaID($id);
        return view('admin.ajax.cek_confirm_belanja')
            ->with('headerTitle', 'Detail Confirm Belanja')
            ->with('getData', $getData);
    }

    public function getCekVerificationRoyalti($id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getAdminVerificationRoyaltiID($id);
        return view('admin.ajax.cek_confirm_royalti')
            ->with('headerTitle', 'Detail Verification Royalti')
            ->with('getData', $getData);
    }

    public function getCekRejectBelanjaReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllBelanjaRewardByID($id);
        return view('admin.ajax.reject-belanja-reward')
            ->with('headerTitle', 'Reject Belanja Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekRejectVBelanjaReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllVBelanjaRewardByID($id);
        return view('admin.ajax.reject-vbelanja-reward')
            ->with('headerTitle', 'Reject Vendor Belanja Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailBelanjaReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailBelanjaReward($id);
        return view('admin.ajax.detail-belanja')
            ->with('headerTitle', 'Detail Belanja Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailVBelanjaReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailVBelanjaReward($id);
        return view('admin.ajax.detail-vbelanja')
            ->with('headerTitle', 'Detail Vendor Belanja Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getRemovePurchaseId($id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getDetailPurchase($id);
        return view('admin.ajax.cek_rm_product')
            ->with('headerTitle', 'Hapus Produk')
            ->with('getData', $getData);
    }

    public function getCekRejectPenjualanReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllPenjualanRewardByID($id);
        return view('admin.ajax.reject-penjualan-reward')
            ->with('headerTitle', 'Reject Penjualan Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekRejectVPenjualanReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllVendorPenjualanRewardByID($id);
        return view('admin.ajax.reject-vpenjualan-reward')
            ->with('headerTitle', 'Reject Penjualan Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailPenjualanReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailPenjualanReward($id);
        return view('admin.ajax.detail-penjualan')
            ->with('headerTitle', 'Detail Penjualan Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getCekDetailVPenjualanReward($id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminDetailVPenjualanReward($id);
        return view('admin.ajax.detail-penjualan')
            ->with('headerTitle', 'Detail Penjualan Reward')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAdminChangeDataMember($id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-data')
            ->with('headerTitle', 'Change Data Member')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAdminChangePasswordMember($id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-passwd')
            ->with('headerTitle', 'Change Password Member')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAdminChange2faMember($id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-2fa')
            ->with('headerTitle', 'Change 2FA Member')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAdminChangeBlockMember($id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-block')
            ->with('headerTitle', 'Blokir Data Member')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAdminChangeTronMember($id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $getData = $modelMember->getUsers('id', $id);
        return view('admin.ajax.change-tron')
            ->with('headerTitle', 'Change Tron Member')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getAdminGetCurrentPage(Request $request)
    {
        $dataUser = Auth::user();
        $modelAdmin = new Admin;
        $logHistory = array(
            'user_id' => $dataUser->id,
            'detail_log' => 'GET ' . $request->page
        );
        $modelAdmin->getInsertLogHistory($logHistory);
    }

    public function getAdminEditStock($stockist_id, $purchase_id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $modelSales = new Sales;
        $getDataUser = $modelMember->getExplorerByID($stockist_id);
        $data = $modelSales->getStockByPurchaseIdStockist($getDataUser->id, $purchase_id);
        $getData = null;
        if ($data != null) {
            $jml_keluar = $modelSales->getSumStock($getDataUser->id, $data->id);
            $total_sisa = $data->total_qty - $jml_keluar;
            if ($total_sisa < 0) {
                $total_sisa = 0;
            }
            $hapus = 0;
            if ($total_sisa == 0) {
                if ($data->deleted_at != null) {
                    $hapus = 1;
                }
            }
            $getData = (object) array(
                'total_qty' => $data->total_qty,
                'name' => $data->name,
                'code' => $data->code,
                'ukuran' => $data->ukuran,
                'image' => $data->image,
                'member_price' => $data->member_price,
                'stockist_price' => $data->stockist_price,
                'id' => $data->id,
                'jml_keluar' => $jml_keluar,
                'total_sisa' => $total_sisa,
                'hapus' => $hapus,
                'purchase_id' => $data->purchase_id,
            );
        }
        return view('admin.ajax.edit-stock')
            ->with('headerTitle', 'Edit Stock')
            ->with('getData', $getData)
            ->with('getDataUser', $getDataUser)
            ->with('dataUser', $dataUser);
    }

    public function getAdminRemoveStock($stockist_id, $purchase_id)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $modelSales = new Sales;
        $getDataUser = $modelMember->getExplorerByID($stockist_id);
        $data = $modelSales->getStockByPurchaseIdStockist($getDataUser->id, $purchase_id);
        $getData = null;
        if ($data != null) {
            $jml_keluar = $modelSales->getSumStock($getDataUser->id, $data->id);
            $total_sisa = $data->total_qty - $jml_keluar;
            if ($total_sisa < 0) {
                $total_sisa = 0;
            }
            $hapus = 0;
            if ($total_sisa == 0) {
                if ($data->deleted_at != null) {
                    $hapus = 1;
                }
            }
            $getData = (object) array(
                'total_qty' => $data->total_qty,
                'name' => $data->name,
                'code' => $data->code,
                'ukuran' => $data->ukuran,
                'image' => $data->image,
                'member_price' => $data->member_price,
                'stockist_price' => $data->stockist_price,
                'id' => $data->id,
                'jml_keluar' => $jml_keluar,
                'total_sisa' => $total_sisa,
                'hapus' => $hapus,
                'purchase_id' => $data->purchase_id,
            );
        }
        return view('admin.ajax.rm-stock')
            ->with('headerTitle', 'Remove Stock')
            ->with('getData', $getData)
            ->with('getDataUser', $getDataUser)
            ->with('dataUser', $dataUser);
    }

    public function getCekRejectTopup($id, $user_id)
    {
        $dataUser = Auth::user();
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminTopUpSaldoIDUserId($id, $user_id);
        return view('admin.ajax.reject-topup')
            ->with('headerTitle', 'Reject Top Up')
            ->with('getData', $getData)
            ->with('dataUser', $dataUser);
    }

    public function getRemoveVendorPurchaseId($id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getDetailPurchaseVendor($id);
        return view('admin.ajax.cek_rm_vproduct')
            ->with('headerTitle', 'Hapus Produk Vendor')
            ->with('getData', $getData);
    }

    public function getCekRequestMemberVendor($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberReqVendor($id);
        return view('admin.ajax.cek_req_vendor')
            ->with('headerTitle', 'Cek Request Vendor')
            ->with('getData', $getData);
    }

    public function getCekRejectMemberVendor($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberReqVendor($id);
        return view('admin.ajax.cek_reject_vendor')
            ->with('headerTitle', 'Reject Request Vendor')
            ->with('getData', $getData);
    }

    public function getCekRemoveMemberVendor($id)
    {
        $modelMember = new Member;
        $getData = $modelMember->getCekMemberVendorToRemove($id);
        return view('admin.ajax.cek_remove_vendor')
            ->with('headerTitle', 'Remove Member Vendor')
            ->with('getData', $getData);
    }

    public function getCekRequestMemberInputVStock($id, $user_id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputVStockistItem($id);
        $getDataMaster = $modelSales->getMemberReqInputVStockistID($id);
        return view('admin.ajax.cek_req_input_vstock')
            ->with('headerTitle', 'Konfirmasi Vendor Input Stock & Royalti')
            ->with('getDataMaster', $getDataMaster)
            ->with('master_item_id', $id)
            ->with('user_id', $user_id)
            ->with('getData', $getData);
    }

    public function getCekRejectMemberInputVStock($id, $user_id)
    {
        $modelSales = new Sales;
        $getData = $modelSales->getMemberReqInputVStockistItem($id);
        $getDataMaster = $modelSales->getMemberReqInputVStockistID($id);
        return view('admin.ajax.cek_reject_input_vstock')
            ->with('headerTitle', 'Reject Vendor Input Stock & Royalti')
            ->with('getDataMaster', $getDataMaster)
            ->with('master_item_id', $id)
            ->with('user_id', $user_id)
            ->with('getData', $getData);
    }

    public function getCekIsiDepositTransactionById($id, $user_id, $is_tron)
    {
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailDepositTransactionsMemberNew($id, $user_id, $is_tron);
        return view('admin.ajax.transaction-isideposit')
            ->with('headerTitle', 'Cek Transaksi')
            ->with('getData', $getData);
    }

    public function getRejectIsiDepositTransactionById($id, $user_id, $is_tron)
    {
        $modelSettingTrans = new Transaction;
        $getData = $modelSettingTrans->getDetailRejectDepositTransactionsAdmin($id, $user_id, $is_tron);
        return view('admin.ajax.reject-transaction-isideposit')
            ->with('headerTitle', 'Reject Transaksi')
            ->with('getData', $getData);
    }

    public function getCekPPOBTransactionById($id, $type)
    {
        $modelPin = new Pin;
        $getData = $modelPin->getAdminDetailTransactionPPOBEiDR($id);
        return view('admin.ajax.cek-transaksi-ppob')
            ->with('headerTitle', 'Detail Transaksi')
            ->with('type', $type)
            ->with('getData', $getData);
    }

    //TRON-API testing


    public function getCekTestHash(Request $request)
    {
        $tron = new TronModel;
        $detail = $tron->checkTransaction($request->hash);

        return view('admin.ajax.cek-test-hash')
            ->with('headerTitle', 'Detail Transaksi')
            ->with('timestamp', $detail['timestamp'])
            ->with('sender', $detail['sender'])
            ->with('receiver', $detail['receiver'])
            ->with('asset', $detail['asset'])
            ->with('amount', $detail['amount']);
    }

    public function getCekTestSend(Request $request)
    {
        $to = $request->toAddress;
        $amount = $request->amount;
        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';
        try {
            $transaction = $this->tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $this->tron->signTransaction($transaction);
            $response = $this->tron->sendRawTransaction($signedTransaction);
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            die($e->getMessage());
        }
        dd($response);

        // $hash = $this->tron->toHex($request->hash);
        $detail = $this->tron->getTransaction($request->hash);
        $timestamp = $detail['raw_data']['timestamp'];
        $sender = $this->tron->fromHex($detail['raw_data']['contract'][0]['parameter']['value']['owner_address']);
        $receiver = $this->tron->fromHex($detail['raw_data']['contract'][0]['parameter']['value']['to_address']);
        $asset = $this->tron->fromHex($detail['raw_data']['contract'][0]['parameter']['value']['asset_name']);
        $amount = $detail['raw_data']['contract'][0]['parameter']['value']['amount'];

        return view('admin.ajax.cek-test-hash')
            ->with('headerTitle', 'Detail Transaksi')
            ->with('timestamp', $timestamp)
            ->with('sender', $sender)
            ->with('receiver', $receiver)
            ->with('asset', $asset)
            ->with('amount', $amount);
    }

    public function getCekTestCheckMutation(Request $request)
    {
        $mootaToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJucWllNHN3OGxsdyIsImp0aSI6ImY0NGE5YjZmNjI1NTllYTVlNjYzODcxMTA0OWM2M2YwYTcxYTU0ZTQ5YTA0YjU1ODQ2NTcxZDc0ZTAyZmM5MTc4ZTZlZDQ3YTQ5NGZkMDY1IiwiaWF0IjoxNjA2MTU1Mjk4LCJuYmYiOjE2MDYxNTUyOTgsImV4cCI6MTYzNzY5MTI5OCwic3ViIjoiMTU2NTIiLCJzY29wZXMiOlsiYXBpIl19.DHQ77NIKrStWYPue21LfAlhWzJUjCHtmHhyKa-yqBPGpgtcywywxcQBqk4odBDXHhPkkj1sE0h_nRxoeRY0cg692XuDBrE5Z0mupTaM8-tlgr8lzBhFrjrleCTwqPxPwkQhoGmM3iNAs8JbwpGZ5LgCJNtfDFW_vHYBA9r-2M9Dug30Ckz1TJgyGxNKrEFVV9ZOzuAie6FjHxp_LSV0bCQvacocNEoYWSqMCxomjvtkGZ9iIPC93WZVsLu7v4Up1Xdz5ZIYk7ZNltN_NCBwgUB6KstTRqcloWBk-ISJW-favXIrlKa-aDiZrngzRgKsCv69bf7PhxmaEMKm1edova5tey1qyIQ9mpYP1TIOU3AeSJj6wPFH20rF6KIBpx-vQg3GnnRj0vmY17bnpzv4bIKImyAUg5S94nuNVqx664mfcggEa1oVwdW9kjhHsp2tAET5g2sASrHbx2yASuPJYYEbTSL1OnyhT0IAIIE1o8jIDsvH69jN7GuDppKwbY4iTQBE4Ctm-y0ds3FGdRevv1yXoRUdJayj2mhrWTb--H01qvDyN542rO1Gk6LA-vTro2PKQvJ3zU2_H_Dc_Rle_01cr8FaS76mz_BmwyQm7-WC9eRnYJKTLRXJ1u2QYUSlRk_zQS-VqYif8j6mhD2fyVjWZo65tBYh8AvRfu_ktD5E';

        $headers = [
            'Authorization' => 'Bearer ' . $mootaToken,
            'Accept'        => 'application/json',
        ];
        $client = new Client;
        $date = $request->date;
        $expectedTransfer = $request->nominal;

        $mutationCheck = $client->request('GET', 'https://app.moota.co/api/v2/mutation', [
            'headers' => $headers,
            'query' => [
                'type' => 'CR',
                'bank' => 'dE6jRawozNQ',
                'amount' => $expectedTransfer,
                'date' => $date,
            ]
        ]);
        dd(json_decode($mutationCheck->getBody()->getContents(), true));
        $mutationCheckArray = json_decode($mutationCheck, true);
        dd($mutationCheckArray);

        return view('admin.ajax.cek-test-hash');
    }
    public function getCekTestCheckBalance(Request $request)
    {

        $address = $request->checkAddress;
        $tokenID = $request->tokenId;

        $tokenBalance = $this->tron->getTokenBalance($tokenID, $address, $fromTron = false);
        dd($tokenBalance);

        return view('admin.ajax.cek-test-hash');
    }
}
