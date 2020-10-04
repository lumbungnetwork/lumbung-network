<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Sales extends Model {
    
    public function getInsertPurchase($data){
        try {
            $lastInsertedID = DB::table('purchase')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdatePurchase($fieldName, $name, $data){
        try {
            DB::table('purchase')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getAllPurchase(){
        $sql = DB::table('purchase')
                    ->whereNull('deleted_at')
                    ->where('type', '=', 1)
                    ->orderBy('id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return =$sql;
        }
        return $return;
    }
    
    public function getAllPurchaseByRegion($prov, $kota){
        $sql = DB::table('purchase')
                    ->where('provinsi', '=', $prov)
                    ->where('kota', '=', $kota)
                    ->whereNull('deleted_at')
                    ->where('type', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return =$sql;
        }
        return $return;
    }
    
    
    public function getDetailPurchase($id){
        $sql = DB::table('purchase')
                    ->where('id', '=', $id)
                    ->whereNull('deleted_at')
                    ->where('type', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getAllPurchaseVendor(){
        $sql = DB::table('purchase')
                    ->whereNull('deleted_at')
                    ->where('type', '=', 2)
                    ->orderBy('id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return =$sql;
        }
        return $return;
    }
    
    public function getAllPurchaseVendorByRegion($prov, $kota){
        $sql = DB::table('purchase')
                    ->where('provinsi', '=', $prov)
                    ->where('kota', '=', $kota)
                    ->whereNull('deleted_at')
                    ->where('type', '=', 2)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return =$sql;
        }
        return $return;
    }
    
    public function getDetailPurchaseVendor($id){
        $sql = DB::table('purchase')
                    ->where('id', '=', $id)
                    ->whereNull('deleted_at')
                    ->where('type', '=', 2)
                    ->first();
        return $sql;
    }
    
    public function getInsertStock($data){
        try {
            $lastInsertedID = DB::table('stock')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateStock($fieldName, $name, $data){
        try {
            DB::table('stock')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
        
    public function getDeleteStock($purchase_id, $sale_id, $stockist_id, $user_id){
        try {
            DB::table('stock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('sales_id', '=', $sale_id)
                    ->where('stockist_id', '=', $stockist_id)
                    ->where('user_id', '=', $user_id)
                    ->delete();
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getStockID($purchase_id){
        $sql = DB::table('stock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('type', '=', 1)
                    ->orderBy('id', 'DESC')
                    ->first();
        return $sql;
    }
    
    public function getLastStockID($purchase_id, $user_id){
        $sql = DB::table('stock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', 1)
                    ->orderBy('id', 'DESC')
                    ->first();
        return $sql;
    }
    
    public function getLastStockIDCekExist($purchase_id, $user_id, $stockist_id, $sales_id){
        $sql = DB::table('stock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('user_id', '=', $user_id)
                    ->where('stockist_id', '=', $stockist_id)
                    ->where('sales_id', '=', $sales_id)
                    ->first();
        return $sql;
    }
    
    public function getLastVStockIDCekExist($purchase_id, $user_id, $vendor_id, $sales_id){
        $sql = DB::table('vstock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('user_id', '=', $user_id)
                    ->where('vendor_id', '=', $vendor_id)
                    ->where('vsales_id', '=', $sales_id)
                    ->first();
        return $sql;
    }
    
    public function getInsertVStock($data){
        try {
            $lastInsertedID = DB::table('vstock')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateVStock($fieldName, $name, $data){
        try {
            DB::table('vstock')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getDeleteVStock($purchase_id, $sale_id, $stockist_id, $user_id){
        try {
            DB::table('vstock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('vsales_id', '=', $sale_id)
                    ->where('vendor_id', '=', $stockist_id)
                    ->where('user_id', '=', $user_id)
                    ->delete();
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getVStockID($purchase_id){
        $sql = DB::table('vstock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('type', '=', 1)
                    ->orderBy('id', 'DESC')
                    ->first();
        return $sql;
    }
    
    public function getLastVStockID($purchase_id, $user_id){
        $sql = DB::table('vstock')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', 1)
                    ->orderBy('id', 'DESC')
                    ->first();
        return $sql;
    }
    
    public function getInsertSales($data){
        try {
            $lastInsertedID = DB::table('sales')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getInsertVSales($data){
        try {
            $lastInsertedID = DB::table('vsales')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getMemberSales($id){
        $start_day = date("Y-m-01");
        $end_day = date("Y-m-t");
        $sql = DB::table('sales')
                    ->selectRaw('sum(sale_price) as jml_price')
                    ->where('user_id', '=', $id)
                    ->whereDate('sales.sale_date', '>=', $start_day)
                    ->whereDate('sales.sale_date', '<=', $end_day)
                    ->whereNull('deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getThisMonth(){
        $start_day = date('Y-m-01');
        $end_day = date('Y-m-t');
        $text_month = date('F Y');
        $data = (object) array(
            'startDay' => $start_day,
            'endDay' => $end_day,
            'textMonth' => $text_month
        );
        return $data;
    }
    
    public function getMemberSalesHistory($id, $date){
        $sql = DB::table('sales')
                    ->join('users', 'sales.stockist_id', '=', 'users.id')
                    ->selectRaw('sales.sale_date, users.user_code, sales.sale_price')
                    ->where('sales.user_id', '=', $id)
                    ->whereDate('sales.sale_date', '>=', $date->startDay)
                    ->whereDate('sales.sale_date', '<=', $date->endDay)
                    ->whereNull('sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getInsertMasterSales($data){
        try {
            $lastInsertedID = DB::table('master_sales')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateMasterSales($fieldName, $name, $data){
        try {
            DB::table('master_sales')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getInsertVMasterSales($data){
        try {
            $lastInsertedID = DB::table('vmaster_sales')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateVMasterSales($fieldName, $name, $data){
        try {
            DB::table('vmaster_sales')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getCodeMasterSales($id){
        $getTransCount = DB::table('master_sales')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount+1;
        $code = 'Cart_'.date('Ymds').$id.sprintf("%04s", $tmp);
        return $code;
    }
    
    public function getMemberPembayaranMasterSales($id){
        $sql = DB::table('master_sales')
                    ->where('master_sales.id', '=', $id)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getMemberPembayaranVMasterSales($id){
        $sql = DB::table('vmaster_sales')
                    ->where('vmaster_sales.id', '=', $id)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getMemberPembayaranSales($id){
        $sql = DB::table('sales')
                    ->join('purchase', 'purchase.id', '=', 'sales.purchase_id')
                    ->selectRaw('sales.sale_price, sales.amount, sales.invoice, sales.sale_date, '
                            . 'purchase.name, purchase.ukuran, purchase.code, sales.purchase_id, sales.stockist_id, sales.user_id,'
                            . 'sales.id')
                    ->where('sales.master_sales_id', '=', $id)
                    ->whereNull('sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberPembayaranVSales($id){
        $sql = DB::table('vsales')
                    ->join('purchase', 'purchase.id', '=', 'vsales.purchase_id')
                    ->selectRaw('vsales.sale_price, vsales.amount, vsales.invoice, vsales.sale_date, '
                            . 'purchase.name, purchase.ukuran, purchase.code, vsales.purchase_id, vsales.vendor_id, vsales.user_id,'
                            . 'vsales.id')
                    ->where('vsales.vmaster_sales_id', '=', $id)
                    ->whereNull('vsales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberMasterSalesHistory($id, $date){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.stockist_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode')
                    ->where('master_sales.user_id', '=', $id)
                    ->whereDate('master_sales.sale_date', '>=', $date->startDay)
                    ->whereDate('master_sales.sale_date', '<=', $date->endDay)
                    ->whereNull('master_sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberVMasterSalesHistory($id, $date){
        $sql = DB::table('vmaster_sales')
                    ->join('users', 'vmaster_sales.vendor_id', '=', 'users.id')
                    ->selectRaw('vmaster_sales.sale_date, users.user_code, vmaster_sales.total_price as sale_price, '
                            . 'vmaster_sales.id, vmaster_sales.status, vmaster_sales.buy_metode')
                    ->where('vmaster_sales.user_id', '=', $id)
                    ->whereDate('vmaster_sales.sale_date', '>=', $date->startDay)
                    ->whereDate('vmaster_sales.sale_date', '<=', $date->endDay)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMembePPOBSalesHistory($id, $date){
        $sql = DB::table('ppob')
                    ->join('users', 'ppob.vendor_id', '=', 'users.id')
                    ->selectRaw('ppob.ppob_date, users.user_code, ppob.ppob_price as sale_price, '
                            . 'ppob.id, ppob.status, ppob.buy_metode')
                    ->where('ppob.user_id', '=', $id)
                    ->whereDate('ppob.ppob_date', '>=', $date->startDay)
                    ->whereDate('ppob.ppob_date', '<=', $date->endDay)
                    ->whereNull('ppob.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMembePPOBSalesHistoryPulsaData($id, $date){
        $sql = DB::table('ppob')
                    ->join('users', 'ppob.vendor_id', '=', 'users.id')
                    ->selectRaw('ppob.ppob_date, users.user_code, ppob.ppob_price as sale_price, '
                            . 'ppob.id, ppob.status, ppob.buy_metode')
                    ->where('ppob.user_id', '=', $id)
                    ->where('ppob.type', '<=', 2)
                    ->whereDate('ppob.ppob_date', '>=', $date->startDay)
                    ->whereDate('ppob.ppob_date', '<=', $date->endDay)
                    ->whereNull('ppob.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMembePPOBSalesHistorySelainPulsaData($id, $date){
        $sql = DB::table('ppob')
                    ->join('users', 'ppob.vendor_id', '=', 'users.id')
                    ->selectRaw('count(ppob.id) as total_ppob')
                    ->where('ppob.user_id', '=', $id)
                    ->where('ppob.type', '>', 2)
                    ->where('ppob.status', '=', 2)
                    ->whereDate('ppob.ppob_date', '>=', $date->startDay)
                    ->whereDate('ppob.ppob_date', '<=', $date->endDay)
                    ->whereNull('ppob.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getCronrSalesHistoryMonth($date){
        $sql = DB::table('master_sales')
                    ->selectRaw('master_sales.user_id as id, sum(master_sales.total_price) as month_sale_price')
                    ->where('master_sales.status', '>', 0)
                    ->whereDate('master_sales.sale_date', '>=', $date->startDay)
                    ->whereDate('master_sales.sale_date', '<=', $date->endDay)
                    ->whereNull('master_sales.deleted_at')
                    ->groupBy('master_sales.user_id')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getCekSalesHistoryMemberMonth($id, $date, $min_belanja){
        $return = true;
        if($id > 11){
            $sql = DB::table('master_sales')
                        ->selectRaw('sum(master_sales.total_price) as cek_month_belanja')
                        ->where('master_sales.status', '=', 2)
                        ->where('master_sales.user_id', '=', $id)
                        ->whereDate('master_sales.sale_date', '>=', $date->startDay)
                        ->whereDate('master_sales.sale_date', '<=', $date->endDay)
                        ->whereNull('master_sales.deleted_at')
                        ->first();
            if($sql->cek_month_belanja == null){
                $return = false;
            }
            if($sql->cek_month_belanja != null){
                if($sql->cek_month_belanja < $min_belanja){
                    $return = false;
                }
            }
        }
        return $return;
    }
    
    public function getInsertItemPurchase($data){
        try {
            $lastInsertedID = DB::table('item_purchase')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateItemPurchase($fieldName, $name, $data){
        try {
            DB::table('item_purchase')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getInsertItemPurchaseMaster($data){
        try {
            $lastInsertedID = DB::table('item_purchase_master')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateItemPurchaseMaster($fieldName, $name, $data){
        try {
            DB::table('item_purchase_master')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getInsertVItemPurchase($data){
        try {
            $lastInsertedID = DB::table('vitem_purchase')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateVItemPurchase($fieldName, $name, $data){
        try {
            DB::table('vitem_purchase')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getInsertVendorItemPurchaseMaster($data){
        try {
            $lastInsertedID = DB::table('vitem_purchase_master')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateVendorItemPurchaseMaster($fieldName, $name, $data){
        try {
            DB::table('vitem_purchase_master')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getMemberMasterPurchaseStockist($id){
        $sql = DB::table('item_purchase_master')
                    ->where('stockist_id', '=', $id)
                    ->orderBy('id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberMasterPurchaseVendor($id){
        $sql = DB::table('vitem_purchase_master')
                    ->where('vendor_id', '=', $id)
                    ->orderBy('id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberMasterPurchaseStockistByID($id, $user_id){
        $sql = DB::table('item_purchase_master')
                    ->where('id', '=', $id)
                    ->where('stockist_id', '=', $user_id)
                    ->first();
        return $sql;
    }
    
    public function getMemberMasterPurchaseVendorByID($id, $user_id){
        $sql = DB::table('vitem_purchase_master')
                    ->where('id', '=', $id)
                    ->where('vendor_id', '=', $user_id)
                    ->first();
        return $sql;
    }
    
    public function getMemberItemPurchaseStockist($master_id, $id){
        $sql = DB::table('item_purchase')
                    ->join('purchase', 'purchase.id', '=', 'item_purchase.purchase_id')
                    ->selectRaw('item_purchase.qty, item_purchase.price,  '
                            . 'purchase.name, purchase.ukuran, purchase.member_price, purchase.stockist_price')
                    ->where('item_purchase.master_item_id', '=', $master_id)
                    ->where('item_purchase.stockist_id', '=', $id)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberItemPurchaseVendor($master_id, $id){
        $sql = DB::table('vitem_purchase')
                    ->join('purchase', 'purchase.id', '=', 'vitem_purchase.purchase_id')
                    ->selectRaw('vitem_purchase.qty, vitem_purchase.price,  '
                            . 'purchase.name, purchase.ukuran, purchase.member_price, purchase.stockist_price as vendor_price')
                    ->where('vitem_purchase.vmaster_item_id', '=', $master_id)
                    ->where('vitem_purchase.vendor_id', '=', $id)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReqInputStockist(){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->selectRaw('users.user_code, users.hp,  item_purchase_master.stockist_id, '
                            . 'item_purchase_master.id, item_purchase_master.price, item_purchase_master.created_at,'
                            . 'item_purchase_master.buy_metode, item_purchase_master.tron, item_purchase_master.tron_transfer,'
                            . 'item_purchase_master.bank_name, item_purchase_master.account_no, item_purchase_master.account_name')
                    ->where('item_purchase_master.status', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReqInputVendor(){
        $sql = DB::table('vitem_purchase_master')
                    ->join('users', 'vitem_purchase_master.vendor_id', '=', 'users.id')
                    ->selectRaw('users.user_code, users.hp,  vitem_purchase_master.vendor_id, '
                            . 'vitem_purchase_master.id, vitem_purchase_master.price, vitem_purchase_master.created_at,'
                            . 'vitem_purchase_master.buy_metode, vitem_purchase_master.tron, vitem_purchase_master.tron_transfer,'
                            . 'vitem_purchase_master.bank_name, vitem_purchase_master.account_no, vitem_purchase_master.account_name')
                    ->where('vitem_purchase_master.status', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReqInputStockistID($id){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->selectRaw('users.user_code, users.hp,  item_purchase_master.stockist_id, '
                            . 'item_purchase_master.id, item_purchase_master.price, item_purchase_master.created_at,'
                            . 'item_purchase_master.buy_metode, item_purchase_master.tron, item_purchase_master.tron_transfer,'
                            . 'item_purchase_master.bank_name, item_purchase_master.account_no, item_purchase_master.account_name')
                    ->where('item_purchase_master.id', '=', $id)
                    ->where('item_purchase_master.status', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getMemberReqInputVStockistID($id){
        $sql = DB::table('vitem_purchase_master')
                    ->join('users', 'vitem_purchase_master.vendor_id', '=', 'users.id')
                    ->selectRaw('users.user_code, users.hp,  vitem_purchase_master.vendor_id, '
                            . 'vitem_purchase_master.id, vitem_purchase_master.price, vitem_purchase_master.created_at,'
                            . 'vitem_purchase_master.buy_metode, vitem_purchase_master.tron, vitem_purchase_master.tron_transfer,'
                            . 'vitem_purchase_master.bank_name, vitem_purchase_master.account_no, vitem_purchase_master.account_name')
                    ->where('vitem_purchase_master.id', '=', $id)
                    ->where('vitem_purchase_master.status', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getMemberReqInputStockistItem($id){
        $sql = DB::table('item_purchase')
                    ->join('purchase', 'purchase.id', '=', 'item_purchase.purchase_id')
                    ->selectRaw('item_purchase.qty, item_purchase.price,  '
                            . 'purchase.name, purchase.ukuran, purchase.member_price, purchase.stockist_price')
                    ->where('item_purchase.master_item_id', '=', $id)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReqInputVStockistItem($id){
        $sql = DB::table('vitem_purchase')
                    ->join('purchase', 'purchase.id', '=', 'vitem_purchase.purchase_id')
                    ->selectRaw('vitem_purchase.qty, vitem_purchase.price,  '
                            . 'purchase.name, purchase.ukuran, purchase.member_price, purchase.stockist_price as vendor_price')
                    ->where('vitem_purchase.vmaster_item_id', '=', $id)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberPurchaseShoping($stockist_id){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->join('item_purchase', 'item_purchase_master.id', '=', 'item_purchase.master_item_id')
                    ->join('purchase', 'purchase.id', '=', 'item_purchase.purchase_id')
                    ->selectRaw('sum(item_purchase.qty) as total_qty, '
                            . 'sum(item_purchase.sisa) as total_sisa, '
                            . 'purchase.name, purchase.code, purchase.ukuran, purchase.image, purchase.member_price,'
                            . 'purchase.stockist_price, purchase.id, purchase.deleted_at, purchase.id as purchase_id')
                    ->where('item_purchase_master.status', '=', 2)
                    ->where('item_purchase_master.stockist_id', '=', $stockist_id)
                    ->whereNull('item_purchase.deleted_at')
                    ->groupBy('purchase.name')
                    ->groupBy('purchase.code')
                    ->groupBy('purchase.ukuran')
                    ->groupBy('purchase.image')
                    ->groupBy('purchase.member_price')
                    ->groupBy('purchase.stockist_price')
                    ->groupBy('purchase.id')
                    ->groupBy('purchase.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getSumStock($stockist_id, $purchase_id){
//        $sql = DB::table('stock')
//                    ->selectRaw('sum(amount) as jml_keluar')
//                    ->where('type', '=', 2)
//                    ->where('stockist_id', '=', $stockist_id)
//                    ->where('purchase_id', '=', $purchase_id)
//                    ->first();
        $query = " 
            SELECT sum(data_stock.amount) as jml_keluar
            FROM (
                SELECT 
                        amount
                FROM stock
                WHERE stockist_id = $stockist_id
                AND purchase_id = $purchase_id
                GROUP BY sales_id, amount
            ) as data_stock ";
        $sql = DB::select($query);
        $return = 0;
        if($sql[0]->jml_keluar != null){
            $return = $sql[0]->jml_keluar;
        }
        return $return;
    }
    
    public function getMemberPurchaseVendorShoping($vendor_id){
        $sql = DB::table('vitem_purchase_master')
                    ->join('users', 'vitem_purchase_master.vendor_id', '=', 'users.id')
                    ->join('vitem_purchase', 'vitem_purchase_master.id', '=', 'vitem_purchase.vmaster_item_id')
                    ->join('purchase', 'purchase.id', '=', 'vitem_purchase.purchase_id')
                    ->selectRaw('sum(vitem_purchase.qty) as total_qty, '
                            . 'sum(vitem_purchase.sisa) as total_sisa, '
                            . 'purchase.name, purchase.code, purchase.ukuran, purchase.image, purchase.member_price,'
                            . 'purchase.stockist_price as vendor_price, purchase.id, purchase.deleted_at, purchase.id as purchase_id')
                    ->where('vitem_purchase_master.status', '=', 2)
                    ->where('vitem_purchase_master.vendor_id', '=', $vendor_id)
                    ->whereNull('vitem_purchase.deleted_at')
                    ->groupBy('purchase.name')
                    ->groupBy('purchase.code')
                    ->groupBy('purchase.ukuran')
                    ->groupBy('purchase.image')
                    ->groupBy('purchase.member_price')
                    ->groupBy('purchase.stockist_price')
                    ->groupBy('purchase.id')
                    ->groupBy('purchase.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getSumStockVendor($vendor_id, $purchase_id){
        $query = " 
            SELECT sum(data_stock.amount) as jml_keluar
            FROM (
                SELECT 
                        amount
                FROM vstock
                WHERE vendor_id = $vendor_id
                AND purchase_id = $purchase_id
                GROUP BY vsales_id, amount
            ) as data_stock ";
        $sql = DB::select($query);
        $return = 0;
        if($sql[0]->jml_keluar != null){
            $return = $sql[0]->jml_keluar;
        }
        return $return;
    }
    
    public function getStockByPurchaseIdStockist($stockist_id, $purchase_id){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->join('item_purchase', 'item_purchase_master.id', '=', 'item_purchase.master_item_id')
                    ->join('purchase', 'purchase.id', '=', 'item_purchase.purchase_id')
                    ->selectRaw('sum(item_purchase.qty) as total_qty, '
                            . 'sum(item_purchase.sisa) as total_sisa, '
                            . 'purchase.name, purchase.code, purchase.ukuran, purchase.image, purchase.member_price,'
                            . 'purchase.stockist_price, purchase.id, purchase.deleted_at, purchase.id as purchase_id')
                    ->where('item_purchase_master.status', '=', 2)
                    ->where('item_purchase_master.stockist_id', '=', $stockist_id)
                    ->where('purchase.id', '=', $purchase_id)
                    ->groupBy('purchase.name')
                    ->groupBy('purchase.code')
                    ->groupBy('purchase.ukuran')
                    ->groupBy('purchase.image')
                    ->groupBy('purchase.member_price')
                    ->groupBy('purchase.stockist_price')
                    ->groupBy('purchase.id')
                    ->groupBy('purchase.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getStockByPurchaseIdVendor($vendor_id, $purchase_id){
        $sql = DB::table('vitem_purchase_master')
                    ->join('users', 'vitem_purchase_master.vendor_id', '=', 'users.id')
                    ->join('vitem_purchase', 'vitem_purchase_master.id', '=', 'vitem_purchase.vmaster_item_id')
                    ->join('purchase', 'purchase.id', '=', 'vitem_purchase.purchase_id')
                    ->selectRaw('sum(vitem_purchase.qty) as total_qty, '
                            . 'sum(vitem_purchase.sisa) as total_sisa, '
                            . 'purchase.name, purchase.code, purchase.ukuran, purchase.image, purchase.member_price,'
                            . 'purchase.stockist_price, purchase.id, purchase.deleted_at, purchase.id as purchase_id')
                    ->where('vitem_purchase_master.status', '=', 2)
                    ->where('vitem_purchase_master.vendor_id', '=', $vendor_id)
                    ->where('purchase.id', '=', $purchase_id)
                    ->groupBy('purchase.name')
                    ->groupBy('purchase.code')
                    ->groupBy('purchase.ukuran')
                    ->groupBy('purchase.image')
                    ->groupBy('purchase.member_price')
                    ->groupBy('purchase.stockist_price')
                    ->groupBy('purchase.id')
                    ->groupBy('purchase.deleted_at')
                    ->first();
        return $sql;
    }
    
    
    public function getMemberReportSalesStockist($id){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.user_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode')
                    ->where('master_sales.stockist_id', '=', $id)
                    ->whereNull('master_sales.deleted_at')
                    ->orderBy('master_sales.sale_date', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReportSalesVendor($id){
        $sql = DB::table('vmaster_sales')
                    ->join('users', 'vmaster_sales.user_id', '=', 'users.id')
                    ->selectRaw('vmaster_sales.sale_date, users.user_code, vmaster_sales.total_price as sale_price, '
                            . 'vmaster_sales.id, vmaster_sales.status, vmaster_sales.buy_metode,'
                            . 'vmaster_sales.royalti_metode')
                    ->where('vmaster_sales.vendor_id', '=', $id)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->orderBy('vmaster_sales.sale_date', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReportSalesStockistDetail($id, $stockist_id){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.user_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode, '
                            . 'master_sales.royalti_metode, master_sales.royalti_tron, master_sales.royalti_tron_transfer,'
                            . 'master_sales.tron, master_sales.tron_transfer, master_sales.bank_name, master_sales.account_name, master_sales.account_no, '
                            . 'master_sales.royalti_bank_name, master_sales.royalti_account_no, master_sales.royalti_account_name')
                    ->where('master_sales.id', '=', $id)
                    ->where('master_sales.stockist_id', '=', $stockist_id)
//                    ->where('master_sales.status', '=', 1)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getMemberReportSalesVendorDetail($id, $vendor_id){
        $sql = DB::table('vmaster_sales')
                    ->join('users', 'vmaster_sales.user_id', '=', 'users.id')
                    ->selectRaw('vmaster_sales.sale_date, users.user_code, vmaster_sales.total_price as sale_price, '
                            . 'vmaster_sales.id, vmaster_sales.status, vmaster_sales.buy_metode, '
                            . 'vmaster_sales.royalti_metode, vmaster_sales.royalti_tron, vmaster_sales.royalti_tron_transfer,'
                            . 'vmaster_sales.tron, vmaster_sales.tron_transfer, vmaster_sales.bank_name, vmaster_sales.account_name, vmaster_sales.account_no, '
                            . 'vmaster_sales.royalti_bank_name, vmaster_sales.royalti_account_no, vmaster_sales.royalti_account_name')
                    ->where('vmaster_sales.id', '=', $id)
                    ->where('vmaster_sales.vendor_id', '=', $vendor_id)
//                    ->where('master_sales.status', '=', 1)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getAdminConfirmBelanja(){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.stockist_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode')
                    ->where('master_sales.status', '=', 2)
                    ->whereNull('master_sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminVerificationRoyalti(){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.stockist_id', '=', 'users.id')
                    ->selectRaw('users.user_code, master_sales.total_price as sale_price, master_sales.sale_date, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode, master_sales.royalti_tron, master_sales.royalti_tron_transfer,'
                            . 'master_sales.royalti_bank_name, master_sales.royalti_account_no, master_sales.royalti_account_name')
                    ->where('master_sales.status', '=', 4)
                    ->whereNull('master_sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminConfirmBelanjaID($id){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.stockist_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode')
                    ->where('master_sales.id', '=', $id)
                    ->where('master_sales.status', '=', 2)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getAdminVerificationRoyaltiID($id){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.stockist_id', '=', 'users.id')
                    ->selectRaw('users.user_code, master_sales.total_price as sale_price, master_sales.sale_date, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode, master_sales.royalti_tron, master_sales.royalti_tron_transfer,'
                            . 'master_sales.royalti_bank_name, master_sales.royalti_account_no, master_sales.royalti_account_name')
                    ->where('master_sales.status', '=', 4)
                    ->where('master_sales.id', '=', $id)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getAdminRoyaltiSales($id){
        $sql = DB::table('sales')
                    ->join('purchase', 'purchase.id', '=', 'sales.purchase_id')
                    ->selectRaw('sales.sale_price, sales.amount, sales.invoice, sales.sale_date, '
                            . 'purchase.name, purchase.ukuran, purchase.code, sales.purchase_id, sales.id, sales.user_id, sales.stockist_id')
                    ->where('sales.master_sales_id', '=', $id)
                    ->whereNull('sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberSalesBuy($id){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.user_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode')
                    ->where('master_sales.stockist_id', '=', $id)
                    ->where('master_sales.status', '=', 1)
                    ->whereNull('master_sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberVSalesBuy($id){
        $sql = DB::table('vmaster_sales')
                    ->join('users', 'vmaster_sales.user_id', '=', 'users.id')
                    ->selectRaw('vmaster_sales.sale_date, users.user_code, vmaster_sales.total_price as sale_price, '
                            . 'vmaster_sales.id, vmaster_sales.status, vmaster_sales.buy_metode,'
                            . 'vmaster_sales.royalti_metode')
                    ->where('vmaster_sales.vendor_id', '=', $id)
                    ->where('vmaster_sales.status', '=', 1)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberVPPOB($id){
        $sql = DB::table('ppob')
                    ->join('users', 'ppob.user_id', '=', 'users.id')
                    ->selectRaw('ppob.ppob_date, users.user_code, ppob.ppob_price as sale_price, '
                            . 'ppob.id, ppob.status, ppob.buy_metode')
                    ->where('ppob.vendor_id', '=', $id)
                    ->where('ppob.status', '=', 1)
                    ->whereNull('ppob.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberMasterSalesMonthly($id){
        $start_day = date("Y-m-01");
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as month_sale_price, '
                            . 'DATE_FORMAT(master_sales.sale_date, "%M-%Y") as monthly, YEAR(master_sales.sale_date) as year, '
                            . 'MONTH(master_sales.sale_date) as month')
                    ->where('master_sales.user_id', '=', $id)
                    ->where('master_sales.status', '=', 2)
                    ->whereDate('master_sales.sale_date', '<', $start_day)
                    ->whereNull('master_sales.deleted_at')
                    ->groupBy('year', 'month')
                    ->groupBy('monthly')
                    ->orderBy('year', 'DESC')
                    ->orderBy('month', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberVendorMasterSalesMonthly($id){
        $start_day = date("Y-m-01");
//        $sql = DB::table('vmaster_sales')
//                    ->selectRaw('sum(vmaster_sales.total_price) as month_sale_price, '
//                            . 'DATE_FORMAT(vmaster_sales.sale_date, "%M-%Y") as monthly, YEAR(vmaster_sales.sale_date) as year, '
//                            . 'MONTH(vmaster_sales.sale_date) as month')
//                    ->where('vmaster_sales.user_id', '=', $id)
//                    ->where('vmaster_sales.status', '=', 2)
//                    ->whereDate('vmaster_sales.sale_date', '<', $start_day)
//                    ->whereNull('vmaster_sales.deleted_at')
//                    ->groupBy('year', 'month')
//                    ->groupBy('monthly')
//                    ->orderBy('year', 'ASC')
//                    ->orderBy('month', 'ASC')
//                    ->get();
        $sql = "select sum(sales.sale_price) as month_sale_price, DATE_FORMAT(sales.sale_date, '%M-%Y') as monthly, YEAR(sales.sale_date) as year, "
                . "MONTH(sales.sale_date) as month "
                . "from "
                . "(select vmaster_sales.total_price as sale_price, vmaster_sales.sale_date "
                . "from vmaster_sales "
                . "where vmaster_sales.user_id = $id "
                . "and vmaster_sales.status = 2 "
                . "and vmaster_sales.sale_date < '$start_day' "
                . "and vmaster_sales.deleted_at is null "
                . "union "
                ."select ppob.ppob_price as sale_price, ppob.ppob_date as sale_date "
                . "from ppob "
                . "where ppob.user_id = $id "
                . "and ppob.status = 2 "
                . "and ppob.ppob_date < '$start_day' and ppob.type <= 2 "
                . "and ppob.deleted_at is null "
                . "union "
                ."select '2500' as sale_price, aa.ppob_date as sale_date "
                . "from ppob as aa "
                . "where aa.vendor_id = $id "
                . "and aa.status = 2 "
                . "and aa.ppob_date < '$start_day' and aa.type > 2 "
                . "and aa.deleted_at is null) as sales "
                . "GROUP BY year, month, monthly "
                . "ORDER BY year ASC, month ASC ";
        $result = DB::select($sql);
        if(count($result) <= 0){
            return null;
        }
        return $result;
    }
    
    public function getMemberVendorPPOBMonthly($id){
        $start_day = date("Y-m-01");
        $sql = DB::table('ppob')
                    ->selectRaw('sum(ppob.ppob_price) as month_sale_price, '
                            . 'DATE_FORMAT(ppob.ppob_date, "%M-%Y") as monthly, YEAR(ppob.ppob_date) as year, '
                            . 'MONTH(ppob.ppob_date) as month')
                    ->where('ppob.user_id', '=', $id)
                    ->where('ppob.status', '=', 2)
                    ->whereDate('ppob.ppob_date', '<', $start_day)
                    ->whereNull('ppob.deleted_at')
                    ->groupBy('year', 'month')
                    ->groupBy('monthly')
                    ->orderBy('year', 'ASC')
                    ->orderBy('month', 'ASC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberMasterSalesMonthlyTerbaru($id){
        $start_day = date("Y-m-01");
        $query = " 
            SELECT data_monthly.user_id, data_monthly.month_sale_price, data_monthly.monthly,
		data_monthly.year_nya, data_monthly.month_nya,
		belanja_reward.id, belanja_reward.status, belanja_reward.type
            FROM (
            SELECT 
                    master_sales.user_id, sum(master_sales.total_price) as month_sale_price,
                    DATE_FORMAT(master_sales.sale_date, '%M-%Y') as monthly, YEAR(master_sales.sale_date) as year_nya, 
            MONTH(master_sales.sale_date) as month_nya
            FROM lumbung.master_sales
            Where master_sales.user_id = $id
            AND master_sales.status = 2 
            AND master_sales.sale_date < '$start_day'
            AND master_sales.deleted_at IS NULL
            GROUP BY year_nya, month_nya, monthly, master_sales.user_id
            ORDER BY month_nya DESC, year_nya DESC
            ) as data_monthly
            LEFT JOIN lumbung.belanja_reward ON belanja_reward.user_id = data_monthly.user_id 
                    AND belanja_reward.month = data_monthly.month_nya 
                    AND belanja_reward.year = data_monthly.year_nya ";
        $sql = DB::select($query);
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberMasterSalesMonthYear($id, $month, $year){
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as month_sale_price, '
                            . 'DATE_FORMAT(master_sales.sale_date, "%M-%Y") as monthly, YEAR(master_sales.sale_date) as year, '
                            . 'MONTH(master_sales.sale_date) as month')
                    ->where('master_sales.user_id', '=', $id)
                    ->where('master_sales.status', '=', 2)
                    ->whereMonth('master_sales.sale_date', '=', $month)
                    ->whereYear('master_sales.sale_date', '=', $year)
                    ->whereNull('master_sales.deleted_at')
                    ->groupBy('year', 'month')
                    ->groupBy('monthly')
                    ->first();
        return $sql;
    }
    
    public function getMemberVMasterSalesMonthYear($id, $month, $year){
//        $sql = DB::table('vmaster_sales')
//                    ->selectRaw('sum(vmaster_sales.total_price) as month_sale_price, '
//                            . 'DATE_FORMAT(vmaster_sales.sale_date, "%M-%Y") as monthly, YEAR(vmaster_sales.sale_date) as year, '
//                            . 'MONTH(vmaster_sales.sale_date) as month')
//                    ->where('vmaster_sales.user_id', '=', $id)
//                    ->where('vmaster_sales.status', '=', 2)
//                    ->whereMonth('vmaster_sales.sale_date', '=', $month)
//                    ->whereYear('vmaster_sales.sale_date', '=', $year)
//                    ->whereNull('vmaster_sales.deleted_at')
//                    ->groupBy('year', 'month')
//                    ->groupBy('monthly')
//                    ->orderBy('year', 'ASC')
//                    ->orderBy('month', 'ASC')
//                    ->first();
//        return $sql;
        
        $sql = "select sum(sales.sale_price) as month_sale_price, DATE_FORMAT(sales.sale_date, '%M-%Y') as monthly, YEAR(sales.sale_date) as year, "
                . "MONTH(sales.sale_date) as month "
                . "from "
                . "(select vmaster_sales.total_price as sale_price, vmaster_sales.sale_date "
                . "from vmaster_sales "
                . "where vmaster_sales.user_id = $id "
                . "and vmaster_sales.status = 2 "
                . "and month(vmaster_sales.sale_date) = '$month' "
                . "and year(vmaster_sales.sale_date) = '$year' "
                . "and vmaster_sales.deleted_at is null "
                . "union "
                ."select ppob.ppob_price as sale_price, ppob.ppob_date as sale_date "
                . "from ppob "
                . "where ppob.user_id = $id "
                . "and ppob.status = 2 "
                . "and month(ppob.ppob_date) = '$month' "
                . "and year(ppob.ppob_date) = '$year' "
                . " and ppob.type <= 2 "
                . "and ppob.deleted_at is null "
                . "union "
                ."select '2500' as sale_price, aa.ppob_date as sale_date "
                . "from ppob as aa "
                . "where aa.vendor_id = $id "
                . "and aa.status = 2 "
                . "and month(aa.ppob_date) = '$month' "
                . "and year(aa.ppob_date) = '$year' "
                . "and aa.type > 2 "
                . "and aa.deleted_at is null) as sales "
                . "GROUP BY year, month, monthly "
                . "ORDER BY year ASC, month ASC ";
        $result = DB::select($sql);
        if(count($result) <= 0){
            return null;
        }
        return $result;
        
    }
    
    public function getStockistPenjualanMonthly($id){
        $start_day = date("Y-m-01");
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as month_sale_price, '
                            . 'DATE_FORMAT(master_sales.sale_date, "%M-%Y") as monthly, YEAR(master_sales.sale_date) as year, '
                            . 'MONTH(master_sales.sale_date) as month')
                    ->where('master_sales.stockist_id', '=', $id)
                    ->where('master_sales.status', '=', 2)
                    ->whereDate('master_sales.sale_date', '<', $start_day)
                    ->whereNull('master_sales.deleted_at')
                    ->groupBy('year', 'month')
                    ->groupBy('monthly')
                    ->orderBy('month', 'DESC')
                    ->orderBy('year', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getVendorPenjualanMonthly($id){
        $start_day = date("Y-m-01");
//        $sql = DB::table('vmaster_sales')
//                    ->selectRaw('sum(vmaster_sales.total_price) as month_sale_price, '
//                            . 'DATE_FORMAT(vmaster_sales.sale_date, "%M-%Y") as monthly, YEAR(vmaster_sales.sale_date) as year, '
//                            . 'MONTH(vmaster_sales.sale_date) as month')
//                    ->where('vmaster_sales.vendor_id', '=', $id)
//                    ->where('vmaster_sales.status', '=', 2)
//                    ->whereDate('vmaster_sales.sale_date', '<', $start_day)
//                    ->whereNull('vmaster_sales.deleted_at')
//                    ->groupBy('year', 'month')
//                    ->groupBy('monthly')
//                    ->orderBy('year', 'ASC')
//                    ->orderBy('month', 'ASC')
//                    ->get();
//        $return = null;
//        if(count($sql) > 0){
//            $return = $sql;
//        }
//        return $return;
        
        
        $sql = "select sum(sales.sale_price) as month_sale_price, "
                . "DATE_FORMAT(sales.sale_date, '%M-%Y') as monthly, "
                . "YEAR(sales.sale_date) as year, "
                . "MONTH(sales.sale_date) as month "
                . "from "
                . "(select vmaster_sales.total_price as sale_price, vmaster_sales.sale_date "
                . "from vmaster_sales "
                . "where vmaster_sales.vendor_id = $id "
                . "and vmaster_sales.status = 2 "
                . "and vmaster_sales.sale_date < '$start_day' "
                . "and vmaster_sales.deleted_at is null "
                . "union "
                ."select ppob.ppob_price as sale_price, ppob.ppob_date as sale_date "
                . "from ppob "
                . "where ppob.vendor_id = $id "
                . "and ppob.status = 2 "
                . "and ppob.ppob_date < '$start_day' and ppob.type <= 2 "
                . "and ppob.deleted_at is null "
                . "union "
                ."select '2500' as sale_price, aa.ppob_date as sale_date "
                . "from ppob as aa "
                . "where aa.vendor_id = $id "
                . "and aa.status = 2 "
                . "and aa.ppob_date < '$start_day' and aa.type > 2 "
                . "and aa.deleted_at is null) as sales "
                . "GROUP BY year, month, monthly "
                . "ORDER BY year ASC, month ASC ";
        $result = DB::select($sql);
        if(count($result) <= 0){
            return null;
        }
        return $result;
    }
    
    public function getStockistPenjualanMonthlyTerbaru($id){
        $start_day = date("Y-m-01");
        $query = " 
            SELECT data_monthly.stockist_id, data_monthly.month_sale_price, data_monthly.monthly,
		data_monthly.year_nya, data_monthly.month_nya,
		belanja_reward.id, belanja_reward.status, belanja_reward.type
            FROM (
            SELECT 
                    master_sales.stockist_id, sum(master_sales.total_price) as month_sale_price,
                    DATE_FORMAT(master_sales.sale_date, '%M-%Y') as monthly, YEAR(master_sales.sale_date) as year_nya, 
            MONTH(master_sales.sale_date) as month_nya
            FROM lumbung.master_sales
            Where master_sales.stockist_id = $id
            AND master_sales.status = 2 
            AND master_sales.sale_date < '$start_day'
            AND master_sales.deleted_at IS NULL
            GROUP BY year_nya, month_nya, monthly, master_sales.stockist_id
            ORDER BY month_nya DESC, year_nya DESC
            ) as data_monthly
            LEFT JOIN lumbung.belanja_reward ON belanja_reward.user_id = data_monthly.stockist_id 
                    AND belanja_reward.month = data_monthly.month_nya 
                    AND belanja_reward.year = data_monthly.year_nya ";
        $sql = DB::select($query);
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getStockistPenjualanMonthYear($id, $month, $year){
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as month_sale_price, '
                            . 'DATE_FORMAT(master_sales.sale_date, "%M-%Y") as monthly, YEAR(master_sales.sale_date) as year, '
                            . 'MONTH(master_sales.sale_date) as month')
                    ->where('master_sales.stockist_id', '=', $id)
                    ->where('master_sales.status', '=', 2)
                    ->whereMonth('master_sales.sale_date', '=', $month)
                    ->whereYear('master_sales.sale_date', '=', $year)
                    ->whereNull('master_sales.deleted_at')
                    ->groupBy('year', 'month')
                    ->groupBy('monthly')
                    ->first();
        return $sql;
    }
    
    public function getVendorPenjualanMonthYear($id, $month, $year){
//        $sql = DB::table('vmaster_sales')
//                    ->selectRaw('sum(vmaster_sales.total_price) as month_sale_price, '
//                            . 'DATE_FORMAT(vmaster_sales.sale_date, "%M-%Y") as monthly, YEAR(vmaster_sales.sale_date) as year, '
//                            . 'MONTH(vmaster_sales.sale_date) as month')
//                    ->where('vmaster_sales.vendor_id', '=', $id)
//                    ->where('vmaster_sales.status', '=', 2)
//                    ->whereMonth('vmaster_sales.sale_date', '=', $month)
//                    ->whereYear('vmaster_sales.sale_date', '=', $year)
//                    ->whereNull('vmaster_sales.deleted_at')
//                    ->groupBy('year', 'month')
//                    ->groupBy('monthly')
//                    ->first();
//        return $sql;
        
        $sql = "select sum(sales.sale_price) as month_sale_price, DATE_FORMAT(sales.sale_date, '%M-%Y') as monthly, YEAR(sales.sale_date) as year, "
                . "MONTH(sales.sale_date) as month "
                . "from "
                . "(select vmaster_sales.total_price as sale_price, vmaster_sales.sale_date "
                . "from vmaster_sales "
                . "where vmaster_sales.vendor_id = $id "
                . "and vmaster_sales.status = 2 "
                . "and month(vmaster_sales.sale_date) = '$month' "
                . "and year(vmaster_sales.sale_date) = '$year' "
                . "and vmaster_sales.deleted_at is null "
                . "union "
                ."select ppob.ppob_price as sale_price, ppob.ppob_date as sale_date "
                . "from ppob "
                . "where ppob.vendor_id = $id "
                . "and ppob.status = 2 "
                . "and month(ppob.ppob_date) = '$month' "
                . "and year(ppob.ppob_date) = '$year' "
                . "and ppob.type <= 2 "
                . "and ppob.deleted_at is null "
                . "union "
                ."select '2500' as sale_price, aa.ppob_date as sale_date "
                . "from ppob as aa "
                . "where aa.vendor_id = $id "
                . "and aa.status = 2 "
                . "and month(aa.ppob_date) = '$month' and year(aa.ppob_date) = '$year' and aa.type > 2 "
                . "and aa.deleted_at is null) as sales "
                . "GROUP BY year, month, monthly "
                . "ORDER BY year ASC, month ASC ";
        $result = DB::select($sql);
        if(count($result) <= 0){
            return null;
        }
        return $result;
    }
    
    public function getMemberReqInputStockistYesterday(){
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->selectRaw('users.user_code, users.hp,  item_purchase_master.stockist_id, '
                            . 'item_purchase_master.id, item_purchase_master.price, item_purchase_master.created_at,'
                            . 'item_purchase_master.buy_metode, item_purchase_master.tron, item_purchase_master.tron_transfer,'
                            . 'item_purchase_master.bank_name, item_purchase_master.account_no, item_purchase_master.account_name')
                    ->where('item_purchase_master.status', '=', 1)
                    ->whereDate('item_purchase_master.created_at', '=', $yesterday)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getSalesAllHistory(){
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as total_sales')
                    ->where('master_sales.status', '=', 2)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getVSalesAllHistory(){
        $sql = DB::table('vmaster_sales')
                    ->selectRaw('sum(vmaster_sales.total_price) as total_sales')
                    ->where('vmaster_sales.status', '=', 2)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getPPOBAllHistory(){
        $sql = DB::table('ppob')
                    ->selectRaw('sum(ppob.ppob_price) as total_sales')
                    ->where('ppob.status', '=', 2)
                    ->whereNull('ppob.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getSalesAllHistoryByID($data){
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as total_sales')
                    ->where('master_sales.user_id', '=', $data->id)
                    ->where('master_sales.status', '=', 2)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getMemberReqInputStockistHistory(){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->join('users as u', 'item_purchase_master.submit_by', '=', 'u.id')
                    ->selectRaw('users.user_code, users.hp,  item_purchase_master.stockist_id, '
                            . 'item_purchase_master.id, item_purchase_master.price, item_purchase_master.created_at,'
                            . 'item_purchase_master.buy_metode, item_purchase_master.tron, item_purchase_master.tron_transfer,'
                            . 'item_purchase_master.bank_name, item_purchase_master.account_no, item_purchase_master.account_name, item_purchase_master.status,'
                            . 'item_purchase_master.submit_by, u.name as submit_name')
                    ->where('item_purchase_master.status', '>', 0)
                    ->orderBy('item_purchase_master.created_at', 'DESC')
                    ->orderBy('item_purchase_master.status', 'ASC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getMemberReqInputVStockistHistory(){
        $sql = DB::table('vitem_purchase_master')
                    ->join('users', 'vitem_purchase_master.vendor_id', '=', 'users.id')
                    ->join('users as u', 'vitem_purchase_master.submit_by', '=', 'u.id')
                    ->selectRaw('users.user_code, users.hp,  vitem_purchase_master.vendor_id, '
                            . 'vitem_purchase_master.id, vitem_purchase_master.price, vitem_purchase_master.created_at,'
                            . 'vitem_purchase_master.buy_metode, vitem_purchase_master.tron, vitem_purchase_master.tron_transfer,'
                            . 'vitem_purchase_master.bank_name, vitem_purchase_master.account_no, vitem_purchase_master.account_name, vitem_purchase_master.status,'
                            . 'vitem_purchase_master.submit_by, u.name as submit_name')
                    ->where('vitem_purchase_master.status', '>', 0)
                    ->orderBy('vitem_purchase_master.created_at', 'DESC')
                    ->orderBy('vitem_purchase_master.status', 'ASC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getSalesAllHistoryLastMonth($date){
        $sql = DB::table('master_sales')
                    ->selectRaw('sum(master_sales.total_price) as total_sales')
                    ->where('master_sales.status', '=', 2)
                    ->whereDate('master_sales.sale_date', '>=', $date->start_day)
                    ->whereDate('master_sales.sale_date', '<=', $date->end_day)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getVSalesAllHistoryLastMonth($date){
        $sql = DB::table('vmaster_sales')
                    ->selectRaw('sum(vmaster_sales.total_price) as total_sales')
                    ->where('vmaster_sales.status', '=', 2)
                    ->whereDate('vmaster_sales.sale_date', '>=', $date->start_day)
                    ->whereDate('vmaster_sales.sale_date', '<=', $date->end_day)
                    ->whereNull('vmaster_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getPPOBAllHistoryLastMonth($date){
        $sql = DB::table('ppob')
                    ->selectRaw('sum(ppob.ppob_price) as total_sales')
                    ->where('ppob.status', '=', 2)
                    ->whereDate('ppob.ppob_date', '>=', $date->start_day)
                    ->whereDate('ppob.ppob_date', '<=', $date->end_day)
                    ->whereNull('ppob.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getLastItemPurchase($purchase_id, $user_id){
        $sql = DB::table('item_purchase')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('stockist_id', '=', $user_id)
                    ->orderBy('id', 'DESC')
                    ->first();
        return $sql;
    }
    
    public function getAllItemPurchaseStockistPurchase($purchase_id, $user_id){ 
        $sql = DB::table('item_purchase')
                    ->where('purchase_id', '=', $purchase_id)
                    ->where('stockist_id', '=', $user_id)
                    ->orderBy('id', 'ASC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
}

