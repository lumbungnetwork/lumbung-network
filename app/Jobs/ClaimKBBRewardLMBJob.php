<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\DB;

use App\User;

use App\Http\Controllers\Admin\BonusmemberController;

class ClaimKBBRewardLMBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get accounts for Silver III
        $accounts = DB::table('users')
            ->join('claim_reward', 'users.id', '=', 'claim_reward.user_id')
            ->selectRaw('users.id')
            ->where('users.member_type', 10)
            ->whereNull('claim_reward.reward_id', 1)
            ->get();
    }
}
