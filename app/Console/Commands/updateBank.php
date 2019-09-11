<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transaction;


class updateBank extends Command {

    protected $signature = 'update_bank';
    protected $description = 'update bank 1';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelSettingTrans = New Transaction;
        $dataUpdate = array(
            'bank_perusahaan_id' => 1,
        );
        $modelSettingTrans->getUpdateTransaction('status', 2, $dataUpdate);
        dd('done');
    }
    
    
    
    
}
