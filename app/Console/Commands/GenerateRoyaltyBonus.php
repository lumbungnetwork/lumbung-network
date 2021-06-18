<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessRoyaltiBonusJob;

class GenerateRoyaltyBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:royalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Monthly Royalty Bonus for Qualifying members';

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
        // Set 'last month' date range
        $startDate = date('Y-m-01 00:00:01', strtotime('last month'));
        $endDate = date('Y-m-t 23:59:01', strtotime('last month'));
        // Get Users with spending from last month, using updated_at as time constrain (when transaction
        // actually finalized)
        $users = DB::table('master_sales')
            ->selectRaw('user_id as id, sum(total_price) as spent')
            ->where('status', 2)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereNull('deleted_at')
            ->groupBy('user_id')
            ->get();
        // Set spending threshold (IDR)
        $threshold = 100000;
        foreach ($users as $user) {
            if ($user->spent > $threshold) {
                ProcessRoyaltiBonusJob::dispatch($user->id)->onQueue('oneliner');
            }
        }
    }
}
