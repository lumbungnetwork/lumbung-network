<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Model\Member\DigitalSale;
use App\Model\Member\MasterSales;
use App\Model\Member\Sales;
use Illuminate\Http\Request;

class PublicTransactionsController extends Controller
{
    public function getTransaction($type, $transaction_id)
    {
        $masterSalesData = null;
        $salesData = [];
        $digitalSaleData = null;
        if ($type == 'physical') {
            $masterSalesData = MasterSales::where('status', 2)->where('id', $transaction_id)->first();
            if ($masterSalesData) {
                $salesData = Sales::where('master_sales_id', $masterSalesData->id)->get();
            }
        } elseif ($type == 'digital') {
            $digitalSaleData = DigitalSale::where('status', 2)->where('id', $transaction_id)->first();
        }

        return view('market.transaction')
            ->with(compact('type'))
            ->with(compact('masterSalesData'))
            ->with(compact('salesData'))
            ->with(compact('digitalSaleData'))
            ->with('title', 'Transaction Detail');
    }
}
