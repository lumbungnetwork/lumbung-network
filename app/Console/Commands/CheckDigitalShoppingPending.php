<?php

namespace App\Console\Commands;

use App\Jobs\FireDigiflazzTransactionJob;
use Illuminate\Console\Command;
use App\Model\Member\DigitalSale;

class CheckDigitalShoppingPending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digital:check_pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A cronjob command to check if any pending tx need to be refired';

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
        $transactions = DigitalSale::where('status', 5)->select('id')->get();
        if (count($transactions) > 0) {
            foreach ($transactions as $tx) {
                FireDigiflazzTransactionJob::dispatch($tx->id)->onQueue('digital');
            }
        }

        return;
    }
}
