<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Member\MasterSales;
use App\Model\Member\LMBreward;
use App\User;
use App\Jobs\GenerateMarketplaceLMBRewardsJob;

class GenerateMarketplaceLMBRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lmb:monthly_rewards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate LMB monthly rewards';

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
        $users = User::all(['id', 'is_store']);

        foreach ($users as $user) {
            // check if Reward aldready created
            $check = LMBreward::where('user_id', $user->id)->where('date', date('m-Y', strtotime('last month')))->exists();
            if ($check) {
                continue;
            }

            // Dispatch the job to take care the iteration
            GenerateMarketplaceLMBRewardsJob::dispatch($user->id, $user->is_store)->onQueue('bonus');
        }

        return;
    }
}
