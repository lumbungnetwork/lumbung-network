<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class protest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versatile:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A versatile command';

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
        $users = DB::table('users')->select('id', 'active_at', 'pin_activate_at')->where('is_active', 1)->get();

        foreach ($users as $user) {
            if ($user->pin_activate_at == null) {
                $expired = date('Y-m-d', strtotime('+ 365 days', strtotime($user->active_at)));
                DB::table('users')->where('id', $user->id)->update(['expired_at' => $expired]);
            } else {
                $expired = date('Y-m-d', strtotime('+ 365 days', strtotime($user->pin_activate_at)));
                DB::table('users')->where('id', $user->id)->update(['expired_at' => $expired]);
            }
        }
        return;
    }
}
