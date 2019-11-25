<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transferwd;
use App\Model\Sales;


class CronPushNotifInputStock extends Command {

    protected $signature = 'push_notif_input_stock';
    protected $description = 'Cron harian Notifikasi Input Stock from Member';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelSales = New Sales;
        $getData = $modelSales->getMemberReqInputStockistYesterday();
        $dataEmail = array(
            'dataEmail' => $getData
        );
//        dd($dataEmail['dataEmail']);
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.push_notif_input_stock', $dataEmail, function($message) use($emailSend){
            $yesterday = date('d F Y',strtotime("-1 days"));
            $message->to($emailSend, 'Data Request Input Stock Member '.$yesterday.' Lumbung Network')
                    ->subject('Data Request Input Stock Member '.$yesterday.' Lumbung Network');
        });
    }
    
    
    
    
}
