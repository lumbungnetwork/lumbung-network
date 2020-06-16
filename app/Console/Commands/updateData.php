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
        $modelMember = New Member;
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
            'email' => 'bernaandya@gmail.com',
            'password' => bcrypt('superadmin_2020'),
            'name' => 'bernaandya@gmail.com',
            'user_code' => 'bernaandya@gmail.com',
            'is_active' => 1,
            'user_type' => 1,
            'id_type' => 0,
        );
        DB::table('users')->insert($dataAndya);
        dd('done');
    }
    
}
