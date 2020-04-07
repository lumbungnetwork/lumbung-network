<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transaction;


class CronPushNotifPin extends Command {

    protected $signature = 'push_notif_pin';
    protected $description = 'Cron harian Notifikasi Pembelian PIN';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelSettingTrans = New Transaction;
        $getData = $modelSettingTrans->getAllRequestBeliPinYesterday();
        $dataEmail = array(
            'dataEmail' => $getData
        );
//        dd($dataEmail['dataEmail']);
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.push_notif_pin', $dataEmail, function($message) use($emailSend){
            $yesterday = date('d F Y',strtotime("-1 days"));
            $message->to($emailSend, 'Data Request Member Beli Pin '.$yesterday.' Lumbung Network')
                    ->subject('Data Request Member Beli Pin '.$yesterday.' Lumbung Network');
        });
    }
}
