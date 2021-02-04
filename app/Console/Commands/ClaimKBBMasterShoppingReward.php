<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\KBBMasterClaimShoppingRewardJob;

class ClaimKBBMasterShoppingReward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:shopreward {affiliate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Claiming KBB Account Shopping Reward Jobs, Args= 1 for Master, 2 for passive';

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
        $affiliate = $this->argument('affiliate');
        $accounts = DB::table('users')->select('users.id')
            ->where('users.affiliate', $affiliate)
            ->where('users.is_active', 1)
            ->get();

        foreach ($accounts as $account) {
            KBBMasterClaimShoppingRewardJob::dispatch($account->id)->onQueue('oneliner');
        }
    }
}
