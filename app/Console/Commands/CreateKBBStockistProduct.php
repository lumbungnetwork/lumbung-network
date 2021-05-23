<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Model\Member\Product;
use Illuminate\Support\Facades\DB;

class CreateKBBStockistProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            ->where('products.seller_id', null)
            ->get();

        if (count($accounts) > 0) {
            foreach ($accounts as $account) {
                $rand = rand(95, 198);
                Product::create([
                    'type' => 1,
                    'seller_id' => $account->id,
                    'name' => 'Beras',
                    'size' => '10Kg',
                    'price' => 100000 + $rand,
                    'qty' => 20,
                    'desc' => null,
                    'category_id' => 1,
                    'image' => 'beras-prosper.jpg '
                ]);
            }
        }

        return;
    }
}
