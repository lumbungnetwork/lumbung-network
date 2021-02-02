<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\KBBMemberBonusJob;

class ClaimKBBMemberBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:memberbonus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Claim all KBB Member Bonus';

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
        $accounts = DB::table('users')->select('users.id')
            ->whereIn('users.affiliate', [2, 3])
            ->where('users.is_active', 1)
            ->get();

        foreach ($accounts as $account) {
            KBBMemberBonusJob::dispatch($account->id)->onQueue('oneliner');
        }
    }
}
