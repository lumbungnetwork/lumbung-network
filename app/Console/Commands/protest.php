<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\Member\EidrBalance;

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
        $c = new Controller;
        // Get all old vendors
        $vendors = DB::table('users')->where('is_vendor', 1)->select('id')->get();

        $totalBalanceMigrated = 0;
        $totalVendorMigrated = 0;

        foreach ($vendors as $vendor) {
            // get old deposit
            $deposit = $c->getVendorAvailableDeposit($vendor->id);

            if ($deposit > 0) {
                $totalBalanceMigrated += $deposit;
                $totalVendorMigrated++;
                // create new internal eIDR balance
                $balance = new EidrBalance;
                $balance->user_id = $vendor->id;
                $balance->amount = $deposit;
                $balance->type = 1;
                $balance->source = 5;
                $balance->note = "Migrasi Saldo dari Deposit Vendor lama";
                $balance->save();

                // deduct old deposit
                DB::table('member_deposito')->insert([
                    'user_id' => $vendor->id,
                    'total_deposito' => $deposit,
                    'transaction_code' => 'Migrated to internal eIDR balance',
                    'deposito_status' => 1
                ]);
            }
        }

        echo "Total Balance Migrated: " . $totalBalanceMigrated . chr(10);
        echo "Total Vendor Migrated: " . $totalVendorMigrated . chr(10);

        return;
    }
}
