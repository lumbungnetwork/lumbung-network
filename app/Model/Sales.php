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
    
    public function getMemberPembayaranSales($id){
        $sql = DB::table('sales')
                    ->join('purchase', 'purchase.id', '=', 'sales.purchase_id')
                    ->selectRaw('sales.sale_price, sales.amount, sales.invoice, sales.sale_date, '
                            . 'purchase.name, purchase.ukuran, purchase.code')
                    ->where('sales.master_sales_id', '=', $id)
                    ->whereNull('sales.deleted_at')
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
    
    public function getMemberMasterPurchaseStockist($id){
        $sql = DB::table('item_purchase_master')
                    ->where('stockist_id', '=', $id)
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
    
    public function getMemberReqInputStockist(){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->selectRaw('users.user_code, users.hp,  item_purchase_master.stockist_id, '
                            . 'item_purchase_master.id, item_purchase_master.price, item_purchase_master.created_at')
                    ->where('item_purchase_master.status', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
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
    
    public function getMemberPurchaseShoping($stockist_id){
        $sql = DB::table('item_purchase_master')
                    ->join('users', 'item_purchase_master.stockist_id', '=', 'users.id')
                    ->join('item_purchase', 'item_purchase_master.id', '=', 'item_purchase.master_item_id')
                    ->join('purchase', 'purchase.id', '=', 'item_purchase.purchase_id')
                    ->selectRaw('sum(item_purchase.qty) as total_qty, '
                            . 'sum(item_purchase.sisa) as total_sisa, '
                            . 'purchase.name, purchase.code, purchase.ukuran, purchase.image, purchase.member_price,'
                            . 'purchase.stockist_price, purchase.id')
                    ->where('item_purchase_master.status', '=', 2)
                    ->where('item_purchase_master.stockist_id', '=', $stockist_id)
                    ->groupBy('purchase.name')
                    ->groupBy('purchase.code')
                    ->groupBy('purchase.ukuran')
                    ->groupBy('purchase.image')
                    ->groupBy('purchase.member_price')
                    ->groupBy('purchase.stockist_price')
                    ->groupBy('purchase.id')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getSumStock($stockist_id, $purchase_id){
        $sql = DB::table('stock')
                    ->selectRaw('sum(amount) as jml_keluar')
                    ->where('type', '=', 2)
                    ->where('stockist_id', '=', $stockist_id)
                    ->where('purchase_id', '=', $purchase_id)
                    ->first();
        $return = 0;
        if($sql->jml_keluar != null){
            $return = $sql->jml_keluar;
        }
        return $return;
    }
    
    public function getMemberReportSalesStockist($id){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.user_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode')
                    ->where('master_sales.stockist_id', '=', $id)
                    ->where('master_sales.status', '>=', 1)
                    ->whereNull('master_sales.deleted_at')
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
                            . 'master_sales.royalti_bank_name, master_sales.royalti_account_no, master_sales.royalti_account_name')
                    ->where('master_sales.id', '=', $id)
                    ->where('master_sales.stockist_id', '=', $stockist_id)
                    ->where('master_sales.status', '=', 2)
                    ->whereNull('master_sales.deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getAdminConfirmBelanja(){
        $sql = DB::table('master_sales')
                    ->join('users', 'master_sales.stockist_id', '=', 'users.id')
                    ->selectRaw('master_sales.sale_date, users.user_code, master_sales.total_price as sale_price, '
                            . 'master_sales.id, master_sales.status, master_sales.buy_metode,'
                            . 'master_sales.royalti_metode')
                    ->where('master_sales.status', '=', 1)
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
                    ->where('master_sales.status', '=', 3)
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
                    ->where('master_sales.status', '=', 1)
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
                    ->where('master_sales.status', '=', 3)
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
    
}

