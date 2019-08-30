<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CronBonusLevel extends Command {

    protected $signature = 'bonus_level';
    protected $description = 'Cron Mingguan Bonus Level';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        dd('keluar juga cronnya');
    }
    
    
    
    
}
