<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transaction;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Transferwd;

class updateData extends Command {
    
    protected $signature = 'update_data';
    protected $description = 'Command for Update Data';

    public function __construct() {
        parent::__construct();
    }
    
    public function handle() {
        //update lumbung.users set password = '$2y$10$QdU2j2MFloz3bmh8Co03UegnbeaSq5H3N8Ge8yM8QqEplce/pSlU6';
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 1500);
//        $dataUser = array(28, 831);
//        foreach($dataUser as $rowUser){
//            $getStockistReqRemove = $modelMember->getCekRequestSotckistBalikinData($rowUser);
//            $active_at = $getStockistReqRemove->active_at;
//            $dataUpdate = array(
//                'status' => 1,
//                'deleted_at' => null,
//                'submit_by' => 1,
//                'submit_at' => null,
//            );
//            $modelMember->getUpdateStockist('id', $rowUser, $dataUpdate);
//            $dataUpdateUser = array(
//                'is_stockist' => 1,
//                'stockist_at' => $active_at
//            );
//            $modelMember->getUpdateUsers('id', $rowUser, $dataUpdateUser);
//        }
        $dataAndya = array(
            'kode' => '12.71.22.0000',
            'nama' => 'MEDAN PETISAH',
            'propinsi' => 12,
            'kabupatenkota' => 71,
            'kecamatan' => 22,
            'kelurahan' => '0000'
        );
        DB::table('daerah')->insert($dataAndya);
        dd('done');
    }
    
}
