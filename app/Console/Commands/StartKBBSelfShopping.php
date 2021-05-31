<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Model\Member\Product;
use App\Jobs\KBBSelfShopping;

class StartKBBSelfShopping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:shopping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start KBB Self Shopping Job';

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
            ->leftJoin('products', 'products.seller_id', '=', 'users.id')
            ->where('users.affiliate', 1)
            ->where('users.is_active', 1)
            ->where('products.type', '=', 1)
            ->get();

        $stockistIDs = [4088, 4092, 4094, 4095, 4098];

        $id = 0; //for stockist iteration

        $quantity = 1; //buying quantity, 100K+ IDR each

        $i = 0; //for accounts iteration

        foreach ($accounts as $account) {
            if ($i == 200) {
                $id++;
                $i = 0;
            }

            $stockistID = $stockistIDs[$id];

            $i++;

            KBBSelfShopping::dispatch($account->id, $quantity, $stockistID)->onQueue('oneliner');
        }

        return;
    }
}
