<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\Member\Product;
use Illuminate\Support\Facades\Cache;

class Sales extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo('App\Model\Member\Product', 'purchase_id', 'id');
    }

    public function masterSales()
    {
        return $this->belongsTo('App\Model\Member\MasterSales', 'master_sales_id', 'id');
    }

    public function getBestSellingProducts($user_id, $start_date, $end_date)
    {
        $physical = $this->where('stockist_id', $user_id)
            ->whereDate('sale_date', '>=', $start_date)
            ->whereDate('sale_date', '<', $end_date)
            ->selectRaw(' COUNT(id) AS sold, purchase_id ')
            ->groupBy('purchase_id')
            ->orderByDesc('sold')
            ->take(3)
            ->get();
        $digital = DB::table('ppob')->where('vendor_id', $user_id)
            ->whereDate('ppob_date', '>=', $start_date)
            ->whereDate('ppob_date', '<', $end_date)
            ->selectRaw(' COUNT(id) AS sold, buyer_code ')
            ->groupBy('buyer_code')
            ->orderByDesc('sold')
            ->take(3)
            ->get();
        $physical_result = collect();
        foreach ($physical as $row) {
            $product = Product::where('id', $row->purchase_id)->select('name', 'size')->first();
            $physical_result->push((object) [
                'product' => $product->name . ' ' . $product->size,
                'sold' => $row->sold
            ]); 
        }
        return (object) [
            'physical' => $physical_result,
            'digital' => $digital
        ];
    }
}
