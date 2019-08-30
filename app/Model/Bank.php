<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Bank extends Model {
    
    //Bonus Start (Sponsor) 
    public function getInsertBank($data){
        try {
            $lastInsertedID = DB::table('bank')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateBank($fieldName, $name, $data){
        try {
            DB::table('bank')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getBankPerusahaan(){
        $sql = DB::table('bank')
                    ->selectRaw('id, bank_name, account_no, account_name')
                    ->where('bank_type', '=', 1)
                    ->where('is_active', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getBankMember($data){
        $sql = DB::table('bank')
                    ->selectRaw('id, bank_name, account_no, account_name, is_active, active_at')
                    ->where('user_id', '=', $data->id)
                    ->where('bank_type', '=', 10)
//                    ->where('is_active', '=', 1)
                    ->orderBy('bank_name', 'ASC')
                    ->get();
        $dataReturn = null;
        if(count($sql) > 0){
            $dataReturn = $sql;
        }
        return $dataReturn;
    }
    
    public function getCheckNoRek($norek, $bankname){
        $sql = DB::table('bank')
                ->selectRaw('id')
                ->where('account_no', '=', $norek)
                ->where('bank_name', '=', $bankname)
                ->count();
        return $sql;
    }
    
    public function getBankMemberID($id, $data){
        $sql = DB::table('bank')
                    ->selectRaw('id, bank_name, account_no, account_name, is_active, active_at')
                    ->where('id', '=', $id)
                    ->where('user_id', '=', $data->id)
                    ->where('bank_type', '=', 10)
                    ->first();
        return $sql;
    }
    
    public function getMutasiBank(){
//        API Key :
//        3566a92a9975bb88df1f0abba7da0a16
//        API Signature :
//        4088decaf715e573248d7fa655cdd0912c8075df
        $data = array(
            "search"  => array(
                    "date"  => array(
                            "from"    => date("Y-m-d")." 00:00:00",
                            "to"        => date("Y-m-d")." 23:59:59"
                    ),
                    "service_code"    => "bri",
                    "account_number"  => "1234567890",
                    "amount"          => "50123.00"
            )
        );

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL             => "https://api.cekmutasi.co.id/v1/bank/search",
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => http_build_query($data),
            CURLOPT_HTTPHEADER      => ["API-KEY: (APIKEY-ANDA)"], // tanpa tanda kurung
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;
    }
    
    public function getCallBackbank(){
        $config = array(
            "cekmutasi_api_signature" => "4088decaf715e573248d7fa655cdd0912c8075df"
        );

        $incomingApiSignature = isset($_SERVER['HTTP_API_SIGNATURE']) ? $_SERVER['HTTP_API_SIGNATURE'] : '';

        // validasi API Signature
        if( !hash_equals($config['cekmutasi_api_signature'], $incomingApiSignature) ) {
            exit("Invalid Signature");
        }

        $post = file_get_contents("php://input");
        $json = json_decode($post);

        if( json_last_error() !== JSON_ERROR_NONE ) {
            exit("Invalid JSON");
        }

        if( $json->action == "payment_report" )
        {
            foreach( $json->content->data as $data )
            {
                # Waktu transaksi dalam format unix timestamp
                $time = $data->unix_timestamp;

                # Tipe transaksi : credit / debit
                $type = $data->type;

                # Jumlah (2 desimal) : 50000.00
                $amount = $data->amount;

                # Berita transfer
                $description = $data->description;

                # Saldo rekening (2 desimal) : 1500000.00
                $balance = $data->balance;

                if( $type == "credit" ) // dana masuk
                {
                    $sql = "SELECT * FROM tabel_invoice WHERE jumlah_tagihan = '".$conn->real_escape_string($amount)."' AND status = 'UNPAID' ORDER BY id ASC LIMIT 1";
                    if( ($exec = $conn->query($sql)) )
                    {
                        while( $invoice = $conn->fetch_object($exec) )
                        {
                            // Invoice dengan nominal ini ditemukan, lanjutkan proses

                            // contoh proses update status pembayaran invoice UNPAID -> PAID
                            $update = "UPDATE tabel_invoice SET status = 'PAID' WHERE id = {$invoice['id']}";
                            $conn->query($update) or die($conn->error);
                        }
                    }
                }
            }
        }
    }
    
    
}
