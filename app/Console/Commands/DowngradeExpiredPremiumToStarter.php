<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class DowngradeExpiredPremiumToStarter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:check_downgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check expired premium, downgrade to Starter';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('user_type', 10)->where('expired_at', '<=', date('Y-m-d 00:00:00', strtotime('today')))->select('id')->get();
        if (count($users) > 0) {
            foreach ($users as $user) {
                User::where('id', $user->id)->update([
                    'user_type' => 9
                ]);
            }
        }

        echo 'Total users downgraded: ' . count($users) . chr(10);

        return;
    }
}
