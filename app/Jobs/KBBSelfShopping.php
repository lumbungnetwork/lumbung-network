<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Model\Sales;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class KBBSelfShopping implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user_id;
    private $quantity;
    public function __construct($user_id, $quantity)
    {
        $this->user_id = $user_id;
        $this->quantity = $quantity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelSales = new Sales;
        $controller = new Controller;
        $invoice = $modelSales->getCodeMasterSales($this->user_id);
        $sale_date = date('Y-m-d');

        //double check
        $check = DB::table('master_sales')
            ->where('user_id', $this->user_id)
            ->where('sale_date', $sale_date)
            ->exists();

        if ($check) {
            $this->delete();
        }

        $product = Product::where('seller_id', $this->user_id)->first();
        if ($product == null) {
            $this->fail();
        }

        $total_price = $this->quantity * $product->price;
        $royalti = 4 / 100 * $total_price;

        $tron = $controller->getTron();
        $tron->setPrivateKey(Config::get('services.eidr.kbbmaster'));

        $to = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        $amount = $royalti * 100;

        $from = 'TKrUoW4kfm2HVrAtpcW9sDBz4GmrbaJcBv';
        $tokenID = '1002652';

        //send eIDR
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            die($e->getMessage());
        }

        if (!isset($response['result'])) {
            $this->fail();
        }

        //cleanup
        if ($response['result'] == true) {
            //fail check
            sleep(5);
            try {
                $tron->getTransaction($response['txid']);
            } catch (TronException $e) {
                $this->fail();
            }

            $dataInsertMasterSales = array(
                'user_id' => $this->user_id,
                'stockist_id' => $this->user_id,
                'invoice' => $invoice,
                'total_price' => $total_price,
                'sale_date' => $sale_date,
                'status' => 2,
                'tron_transfer' => $response['txid'],
                'buy_metode' => 1,
            );
            $insertMasterSales = $modelSales->getInsertMasterSales($dataInsertMasterSales);

            $dataInsert = array(
                'user_id' => $this->user_id,
                'stockist_id' => $this->user_id,
                'purchase_id' => $product->id,
                'invoice' => $invoice,
                'amount' => $this->quantity,
                'sale_price' => $total_price,
                'sale_date' => $sale_date,
                'master_sales_id' => $insertMasterSales->lastID
            );
            $insertSales = $modelSales->getInsertSales($dataInsert);

            return;
        } else {
            $this->fail();
        }
    }
}
