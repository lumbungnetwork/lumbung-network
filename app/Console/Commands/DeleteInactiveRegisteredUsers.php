<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;

class DeleteInactiveRegisteredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Inactive Registered Users';

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
        $deletedUsers = User::where('is_active', 0)
            ->where('user_type', 10)
            ->where('active_at', null)
            ->delete();

        var_dump($deletedUsers);
        return;
    }
}
