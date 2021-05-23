<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\SellerProfile;
use App\Model\Member\Product;
use App\Model\Member\MasterSales;
use App\Model\Member\Sales;
use App\Model\Member\EidrBalance;
use App\Category;
use Auth;

class ShoppingController extends Controller
{
    public function getShop($seller_id)
    {
        $user = Auth::user();

        $sellerData = User::select('alamat')->where('id', $seller_id)->first();
        $sellerProfile = SellerProfile::where('seller_id', $seller_id)->first();
        $products = Product::where('seller_id', $seller_id)->where('is_active', 1)->get();
        $categories = Category::select('id', 'name')->get();

        return view('member.app.shop')
            ->with('title', 'Belanja')
            ->with(compact('user'))
            ->with(compact('seller_id'))
            ->with(compact('sellerData'))
            ->with(compact('sellerProfile'))
            ->with(compact('products'))
            ->with(compact('categories'));
    }

    public function postCheckout(Request $request)
    {
        $user = Auth::user();

        \Cart::session($user->id)->clear();

        return redirect()->route('member.shopping.payment', [$request->masterSalesID]);
    }

    public function getShoppingPayment($masterSalesID)
    {
        $user = Auth::user();

        $masterSalesData = MasterSales::find($masterSalesID);
        $salesData = Sales::where('master_sales_id', $masterSalesID)->with('product:id,name,size,image')->get();
        $sellerProfile = SellerProfile::select('shop_name')->where('seller_id', $masterSalesData->stockist_id)->first();
        $EidrBalance = new EidrBalance;
        $balance = $EidrBalance->getUserNeteIDRBalance($user->id);

        return view('member.shopping.shopping_payment')
            ->with('title', 'Pembayaran Belanja')
            ->with(compact('masterSalesData'))
            ->with(compact('salesData'))
            ->with(compact('balance'))
            ->with('shopName', $sellerProfile->shop_name);
    }
}
