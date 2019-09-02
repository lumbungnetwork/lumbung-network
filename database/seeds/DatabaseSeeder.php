<?php

use Illuminate\Database\Seeder;
use App\Model\Bonussetting;

class DatabaseSeeder extends Seeder {

   public function run(){
       
       DB::table('master_pin')->delete();
       DB::table('pin_setting')->delete();
       DB::table('package')->delete();
       DB::table('users')->delete();
       DB::table('bonus_start')->delete();
       DB::table('bank')->delete();
       
       $pinMaster = array(
            array(
                'total_pin' => 3000,
                'reason' => 'Start Pin',
            )
        );
        foreach($pinMaster as $rowMasterPin){
            DB::table('master_pin')->insert($rowMasterPin);
        }
        
        $pinSetting = array(
            array(
                'price' => 100000,
                'created_by' => 1,
                'active_at' => date('Y-m-d H:i:s'),
            )
        );
        foreach($pinSetting as $rowPinSetting){
            DB::table('pin_setting')->insert($rowPinSetting);
        }
        
        $package = array(
            array(
                'name' => 'Member',
                'short_desc' => '1 Paket',
                'type' => 1,
                'pin' => 1,
                'stock_wd' => 0,
                'discount' => 0
            ),
        );
        foreach($package as $rowPackage){
            DB::table('package')->insert($rowPackage);
        }
        
        $users = array(
            array(
                'email' => 'superadmin@lumbungnetwork.com',
                'password' => bcrypt('superadmin_2019'),
                'name' => 'Super Admin',
                'user_code' => 'superadmin@lumbungnetwork.com',
                'is_active' => 1,
                'user_type' => 1,
                'id_type' => 0,
            ),
            array(
                'email' => 'masteradmin@lumbungnetwork.com',
                'password' => bcrypt('masteradmin_2019'),
                'name' => 'Master Admin',
                'user_code' => 'masteradmin@lumbungnetwork.com',
                'is_active' => 1,
                'user_type' => 2,
                'id_type' => 0,
            ),
            array(
                'email' => 'admin@lumbungnetwork.com',
                'password' => bcrypt('admin_2019'),
                'name' => 'Admin 1',
                'user_code' => 'admin@lumbungnetwork.com',
                'is_active' => 1,
                'user_type' => 3,
                'id_type' => 0,
            ),
            array(
                'email' => 'top001@lumbungnetwork.com',
                'password' => bcrypt('lumbungnetwork_2019'),
                'name' => 'lumbungnetwork 001',
                'user_code' => 'top001',
                'hp' => '08111111111',
                'is_active' => 1,
                'user_type' => 10,
                'active_at' => date('Y-m-d H:i:s')
            ),
        );
        foreach($users as $row){
            DB::table('users')->insert($row);
        }
        
        $getUser001 = DB::table('users')->where('email', '=', 'top001@lumbungnetwork.com')->where('user_code', '=', 'top001')->first();
        $getPinsetting = DB::table('pin_setting')->selectRaw('id, price')->where('is_active', '=', 1)->first();
        $package001 = DB::table('package')->where('type', '=', 1)->first();
        $total_pin = 10;
        $price = $total_pin * $getPinsetting->price;
        $getTransCount = DB::table('transaction')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount+1;
        $code = sprintf("%04s", $tmp);
        $transaction = array(
            array(
                'user_id' => $getUser001->id,
                'transaction_code' => 'TR'.date('Ymd').$getUser001->id.$code,
                'type' => 1,
                'total_pin' => $total_pin,
                'price' => $price,
                'unique_digit' => 169,
                'status' => 2,
                'tuntas_at' => date('Y-m-d H:i:s')
            ),
        );
        foreach($transaction as $transaction001){
            DB::table('transaction')->insert($transaction001);
        }
        $getTrans = DB::table('transaction')->where('user_id', '=', $getUser001->id)->where('status', '=', 2)->first();
        $memberPin = array(
            'user_id' => $getUser001->id,
            'total_pin' => $total_pin,
            'setting_pin' => $getPinsetting->id,
            'transaction_code' => $getTrans->transaction_code,
            'pin_code' => 'PIN'.date('Ymd').$getUser001->id
        );
        DB::table('member_pin')->insert($memberPin);
        
        $dateNow = date('Y-m-d H:i:s');
        $updateData = array(
            'package_id' => $package001->id,
            'package_id_at' => $dateNow,
            'member_type' => 1,
            'member_status' => 1,
            'member_status_at' => $dateNow,
            'full_name' => 'Top 001 2019',
            'alamat' => 'Jl. Surabaya 45',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'kode_pos' => '1945',
            'gender' => 1,
            'ktp' => '10302018',
            'is_profile' => 1,
            'profile_created_at' => $dateNow
        );
        DB::table('users')->where('id', '=', $getUser001->id)->update($updateData);
        
        $pinMaster001 = array(
            array(
                'total_pin' => $total_pin,
                'type_pin' => 2,
                'transaction_code' => $getTrans->transaction_code,
                'reason' => 'Top 001 Buy Pin',
            )
        );
        foreach($pinMaster001 as $rowMasterPin001){
            DB::table('master_pin')->insert($rowMasterPin001);
        }
        $bonus_start = array(
            'start_price' => 20000,
            'created_by' => 2
        );
        DB::table('bonus_start')->insert($bonus_start);
        $bankPerusahaan = array(
            'user_id' => 2,
            'bank_name' => 'BCA',
            'account_no' => '10302018',
            'account_name' => 'Top 001 2019',
            'bank_type' => 1,
            'active_at' => date('Y-m-d H:i:s')
        );
        DB::table('bank')->insert($bankPerusahaan);
        
        $dataPlacement = array(
            array(
                'email' => 'top002@lumbungnetwork.com',
                'password' => bcrypt('lumbungnetwork_2019'),
                'name' => 'Top 002',
                'user_code' => 'top002',
                'hp' => '081122222222',
                'user_type' => 10,
                'sponsor_id' => 4
            ),
            array(
                'email' => 'top003@lumbungnetwork.com',
                'password' => bcrypt('lumbungnetwork_2019'),
                'name' => 'Top 003',
                'user_code' => 'top003',
                'hp' => '081233333333',
                'user_type' => 10,
                'sponsor_id' => 4
            ),
        );
//        foreach($dataPlacement as $rowPlacement){
//            $lastInsertedID = DB::table('users')->insertGetId($rowPlacement);
//            $dataInsert = array(
//                'user_id' => $getUser001->id,
//                'request_user_id' => $lastInsertedID,
//                'package_id' => 1,
//                'name' => 'Master Stockist',
//                'short_desc' => '10 Box',
//                'total_pin' => 1,
//            );
//            $paketId = DB::table('member_package')->insertGetId($dataInsert);
//            $dataUpdatePackageId = array(
//                'package_id' => 1,
//                'package_id_at' => date('Y-m-d H:i:s')
//            );
//            DB::table('users')->where('id', '=', $lastInsertedID)->update($dataUpdatePackageId);
//            $memberPin1 = array(
//                'user_id' => $getUser001->id,
//                'total_pin' => 1,
//                'setting_pin' => 1,
//                'pin_code' => 'PIN201908154001'.$lastInsertedID,
//                'is_used' => 1,
//                'used_at' => date('Y-m-d H:i:s'),
//                'used_user_id' => $lastInsertedID,
//                 'pin_status' => 1,
//            );
//            DB::table('member_pin')->insertGetId($memberPin1);
//            $dataUpdate = array(
//                'status' => 1
//            );
//            DB::table('member_package')->where('id', '=', $paketId)->update($dataUpdate);
//            $modelBonusSetting = new Bonussetting;
//            $getBonusStart =$modelBonusSetting->getActiveBonusStart();
//            $bonus_price = $getBonusStart->start_price * 10;
//            $dataInsertBonus = array(
//                'user_id' => $getUser001->id,
//                'from_user_id' => $lastInsertedID,
//                'type' => 1,
//                'bonus_price' => $bonus_price,
//                'bonus_date' => date('Y-m-d')
//            );
//            DB::table('bonus_member')->insertGetId($dataInsertBonus);
//            $dataUpdateIsActive = array(
//                'is_active' => 1,
//                'active_at' => date('Y-m-d H:i:s'),
//                'member_type' => 1,
//                 'full_name' => $rowPlacement['name'].'2019',
//                'alamat' => 'Jl. Simpang '.($lastInsertedID + 7),
//                'provinsi' => 'Jawa Tengah',
//                'kota' => 'Semarang',
//                'kode_pos' => '18'.($lastInsertedID + 19),
//                'gender' => 1,
//                'ktp' => '105020'.($lastInsertedID + 56),
//                'is_profile' => 1,
//                'profile_created_at' =>  date('Y-m-d'),
//                'upline_id' => $getUser001->id,
//                'upline_detail' => '['.$getUser001->id.']'
//            );
//            DB::table('users')->where('id', '=', $lastInsertedID)->update($dataUpdateIsActive);
//        }
//        $dataUpdateSponsor = array(
//            'total_sponsor' => 2,
//            'kiri_id' => 5,
//            'kanan_id' => 6
//        );
//        DB::table('users')->where('id', '=', $getUser001->id)->update($dataUpdateSponsor);
        
        dd('done All seed');
    }
}
