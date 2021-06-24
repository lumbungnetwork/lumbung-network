<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Jobs\FireDigiflazzTransactionJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\User;
use App\Model\Member\SellerProfile;
use App\Model\Member\Product;
use App\Model\Member\MasterSales;
use App\Model\Member\Sales;
use App\Model\Member\EidrBalance;
use App\Model\Member\Category;
use App\Model\Member\DigitalSale;
use Auth;
use Validator;
use GuzzleHttp\Client;
use Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ShoppingController extends Controller
{
    public function getShop($seller_id)
    {
        $user = Auth::user();

        $sellerData = User::select('alamat')->where('id', $seller_id)->first();
        $sellerProfile = SellerProfile::where('seller_id', $seller_id)->first();
        if (!$sellerProfile) {
            Alert::error('Failed', 'Seller not found');
            return redirect()->route('member.shopping');
        }
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

    public function postSearchProduct(Request $request) {
        $user = Auth::user();
        // validate input
        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string',
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', 'Ada yang salah dari kata kunci yang anda masukkan');
            return redirect()->back();
        }
        $filter = null;
        // Search products with keyword as wildcard and use filter if exist
        if ($request->filter) {
            $filter = $request->filter;
            if ($filter == 'provinsi' || $filter == 'kota') {
                $products = Product::where('products.name', 'LIKE', '%' . $request->keyword . '%')
                    ->where('products.is_active', 1)
                    ->get();
                $products = $products->filter( function ($product) use ($filter, $user) {
                    return $product->seller->$filter == $user->$filter;
                });
            } elseif ($filter == 'asc' || $filter == 'desc') {
                $products = Product::where('name', 'LIKE', '%' . $request->keyword . '%')
                    ->where('is_active', 1)
                    ->orderBy('price', $filter)
                    ->get();
            }
        } else {
            $products = Product::where('name', 'LIKE', '%' . $request->keyword . '%')
            ->where('is_active', 1)
            ->get();
        }
        
        
        return view('member.app.shopping.search_product')
            ->with('title', 'Cari Produk')
            ->with('keyword', $request->keyword)
            ->with(compact('user'))
            ->with(compact('filter'))
            ->with(compact('products'));
    }

    public function getShoppingPayment($masterSalesID)
    {
        $user = Auth::user();

        $masterSalesData = MasterSales::findOrFail($masterSalesID);
        // prevent unauthorized user
        if ($masterSalesData->user_id != $user->id) {
            Alert::error('Failed', 'Access Denied');
            return redirect()->back();
        }
        $salesData = Sales::where('master_sales_id', $masterSalesID)->with('product:id,name,size,image')->get();
        $sellerProfile = SellerProfile::select('shop_name')->where('seller_id', $masterSalesData->stockist_id)->first();
        $EidrBalance = new EidrBalance;
        $balance = $EidrBalance->getUserNeteIDRBalance($user->id);

        return view('member.app.shopping.shopping_payment')
            ->with('title', 'Pembayaran Belanja')
            ->with(compact('masterSalesData'))
            ->with(compact('salesData'))
            ->with(compact('balance'))
            ->with('shopName', $sellerProfile->shop_name);
    }

    // Digiflazz API
    public function getAllPrepaidPricelist()
    {
        return Cache::remember('prepaid_pricelist', 180, function () {
            // Digiflazz Credentials
            $username = config('services.digiflazz.user');
            $apiKey = config('services.digiflazz.key');
            $sign = md5($username . $apiKey . 'pricelist');
            // payload
            $json = json_encode([
                'cmd' => 'prepaid',
                'username' => $username,
                'sign' => $sign
            ]);
            // endpoint
            $url = 'https://api.digiflazz.com/v1/price-list';

            // use Guzzle Client
            $client = new Client;
            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body'    => $json
            ]);

            return json_decode($response->getBody(), true);
        });
    }

    public function getAllPostpaidPricelist()
    {
        return Cache::remember('postpaid_pricelist', 3600, function () {
            // Digiflazz Credentials
            $username = config('services.digiflazz.user');
            $apiKey = config('services.digiflazz.key');
            $sign = md5($username . $apiKey . 'pricelist');
            // payload
            $json = json_encode([
                'cmd' => 'pasca',
                'username' => $username,
                'sign' => $sign
            ]);
            // endpoint
            $url = 'https://api.digiflazz.com/v1/price-list';

            // use Guzzle Client
            $client = new Client;
            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body'    => $json
            ]);

            return json_decode($response->getBody(), true);
        });
    }

    public function getFilteredPrepaidProductArray($type, $buyer_sku_code)
    {
        // Get Product array from buyer_sku_code
        $pricelistArr = $this->getAllPrepaidPricelist();
        $key = array_search($buyer_sku_code, array_column($pricelistArr['data'], 'buyer_sku_code'));
        if (!$key) {
            return false;
        }
        $productArr = $pricelistArr['data'][$key];

        // Pulsa & Data
        if ($type < 3) {
            if ($productArr['price'] <= 40000) {
                $initPrice = $productArr['price'] + 50;
            }
            if ($productArr['price'] > 40000) {
                $initPrice = $productArr['price'] + 70;
            }
            // Here we add 4% (2% for Profit Sharing Conribution, 2% for Seller's profit)
            // We also round-up last 3 digits to 500 increments, all excess will be Seller's profit
            $addedPrice = $initPrice + ($initPrice * 4 / 100);
            $roundedPrice = round($addedPrice, -2);
            $last3DigitsCheck = substr($roundedPrice, -3);
            $check = 500 - $last3DigitsCheck;
            if ($check == 0) {
                $price = $roundedPrice;
            }
            if ($check > 0 && $check < 500) {
                $price = $roundedPrice + $check;
            }
            if ($check == 500) {
                $price = $roundedPrice;
            }
            if ($check < 0) {
                $price = $roundedPrice + (500 + $check);
            }
            $seller_price = $initPrice + ($price * 2 / 100);
        }

        // PLN Prepaid
        if ($type == 3) {
            $addedPrice = $productArr['price'] + 2000;
            $last3DigitsCheck = substr($addedPrice, -3);
            $check = 500 - $last3DigitsCheck;
            if ($check == 0) {
                $price = $addedPrice;
            }
            if ($check > 0 && $check < 500) {
                $price = $addedPrice + $check;
            }
            if ($check == 500) {
                $price = $addedPrice;
            }
            if ($check < 0) {
                $price = $addedPrice + (500 + $check);
            }
            $seller_price = $price - 1345;
        }

        // Emoneys
        if ($type >= 21) {
            $addedPrice = $productArr['price'] + 1000;
            $last3DigitsCheck = substr($addedPrice, -3);
            $check = 500 - $last3DigitsCheck;
            if ($check == 0) {
                $price = $addedPrice;
            }
            if ($check > 0 && $check < 500) {
                $price = $addedPrice + $check;
            }
            if ($check == 500) {
                $price = $addedPrice;
            }
            if ($check < 0) {
                $price = $addedPrice + (500 + $check);
            }
            $seller_price = $price - 500;
        }

        $DigitalSale = new DigitalSale;

        $product = (object) array(
            'buyer_sku_code' => $productArr['buyer_sku_code'],
            'desc' => $productArr['desc'],
            'seller_price' => $seller_price,
            'price' => $price,
            'product_name' => $productArr['product_name'],
            'ref_id' => $DigitalSale->getCodeRef($type)
        );

        return $product;
    }

    public function getFilteredPostpaidProductArray($type, $customer_no)
    {
        // Get data from cache
        $data = Cache::get($customer_no);

        if ($data == null) {
            return false;
        }

        $data_price = $data['data']['selling_price'];

        //BPJS
        if ($type == 4) {
            $price = $data_price;
            $seller_price = $data_price - 700;
        }

        //PLN
        if ($type == 5) {
            $price = $data_price + 500;
            $seller_price = $data_price - 700;
        }

        //HP & Telkom
        if ($type == 6 || $type == 7) {
            $price = $data_price + 1000;
            $seller_price = $data_price;
        }

        //PDAM
        if ($type == 8) {
            $price = $data_price + 800;
            $seller_price = $data_price + 100;
        }

        //PGN
        if ($type == 9) {
            $price = $data_price + 1000;
            $seller_price = $data_price;
        }

        //Multifinance
        if ($type == 10) {
            $price = $data_price + 2500;
            $seller_price = $data_price;
        }

        $product = (object) array(
            'buyer_sku_code' => $data['data']['buyer_sku_code'],
            'desc' => $data['data']['buyer_sku_code'],
            'seller_price' => $seller_price,
            'price' => $price,
            'ref_id' => $data['data']['ref_id']
        );

        return $product;
    }

    public function getPhoneOperatorList($type)
    {
        return view('member.app.shopping.phone_operator_list')
            ->with('title', 'Pilih Operator')
            ->with(compact('type'));
    }

    public function getEmoneyOperatorList()
    {
        return view('member.app.shopping.emoney_operator_list')
            ->with('title', 'Pilih Operator');
    }

    public function getPrepaidPhoneCreditPricelist($operator_id, $type_id)
    {
        // get user and check if is_store for quickbuy
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        // switch operators
        $operator = 'TELKOMSEL';
        switch ($operator_id) {
            case 2:
                $operator = 'INDOSAT';
                break;
            case 3:
                $operator = 'XL';
                break;
            case 4:
                $operator = 'AXIS';
                break;
            case 5:
                $operator = 'TRI';
                break;
            case 6:
                $operator = 'SMART';
                break;
        }

        // switch type
        $type = 'Pulsa';
        if ($type_id == 2) {
            $type = 'Data';
        }

        // empty array to be filled with selected operator pricelist data
        $priceArr = [];
        $priceCallArr = []; //Placeholder Paket Telepon & SMS
        // get all Prepaid Pricelist data from Cache if available or fresh fetch from API
        $prepaidPricelist = $this->getAllPrepaidPricelist();
        foreach ($prepaidPricelist['data'] as $row) {
            if ($row['category'] == $type) {
                if ($row['price'] <= 40000) {
                    $initPrice = $row['price'] + 50;
                }
                if ($row['price'] > 40000) {
                    $initPrice = $row['price'] + 70;
                }
                // Here we add 4% (2% for Profit Sharing Conribution, 2% for Seller's profit)
                // We also round-up last 3 digits to 500 increments, all excess will be Seller's profit
                $addedPrice = $initPrice + ($initPrice * 4 / 100);
                $roundedPrice = round($addedPrice, -2);
                $last3DigitsCheck = substr($roundedPrice, -3);
                $check = 500 - $last3DigitsCheck;
                if ($check == 0) {
                    $price = $roundedPrice;
                }
                if ($check > 0 && $check < 500) {
                    $price = $roundedPrice + $check;
                }
                if ($check == 500) {
                    $price = $roundedPrice;
                }
                if ($check < 0) {
                    $price = $roundedPrice + (500 + $check);
                }

                if ($row['brand'] == $operator) {
                    $priceArr[] = [
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'seller_price = ($price * 2 / 100)' => ($row['price'] + ($price * 2 / 100)),
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name'],
                        'seller_name' => $row['seller_name']
                    ];
                }
            }

            if ($type_id == 2) {
                // Get Paket Telepon & SMS
                if ($row['category'] == 'Paket SMS & Telpon') {
                    if ($row['price'] <= 40000) {
                        $initPrice = $row['price'] + 50;
                    }
                    if ($row['price'] > 40000) {
                        $initPrice = $row['price'] + 70;
                    }
                    // Here we add 4% (2% for Profit Sharing Conribution, 2% for Seller's profit)
                    // We also round-up last 3 digits to 500 increments, all excess will be Seller's profit
                    $addedPrice = $initPrice + ($initPrice * 4 / 100);
                    $roundedPrice = round($addedPrice, -2);
                    $last3DigitsCheck = substr($roundedPrice, -3);
                    $check = 500 - $last3DigitsCheck;
                    if ($check == 0) {
                        $price = $roundedPrice;
                    }
                    if ($check > 0 && $check < 500) {
                        $price = $roundedPrice + $check;
                    }
                    if ($check == 500) {
                        $price = $roundedPrice;
                    }
                    if ($check < 0) {
                        $price = $roundedPrice + (500 + $check);
                    }

                    if ($row['brand'] == $operator) {
                        $priceCallArr[] = [
                            'buyer_sku_code' => $row['buyer_sku_code'],
                            'desc' => $row['desc'],
                            'seller_price = ($price * 2 / 100)' => ($row['price'] + ($price * 2 / 100)),
                            'price' => $price,
                            'brand' => $row['brand'],
                            'product_name' => $row['product_name'],
                            'seller_name' => $row['seller_name']
                        ];
                    }
                }
            }
        }
        $pricelist = collect($priceArr)->sortBy('price')->toArray();
        $pricelistCall = collect($priceCallArr)->sortBy('price')->toArray();

        return view('member.app.shopping.prepaid_pricelist')
            ->with('title', 'Isi Pulsa/Data')
            ->with(compact('pricelist'))
            ->with(compact('pricelistCall'))
            ->with(compact('quickbuy'))
            ->with('type', $type_id);
    }

    public function getEmoneyPricelist($operator_id)
    {
        // get user and check if is_store for quickbuy
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        // switch operators
        $operator = 'GO PAY';
        switch ($operator_id) {
            case 22:
                $operator = 'MANDIRI E-TOLL';
                break;
            case 23:
                $operator = 'OVO';
                break;
            case 24:
                $operator = 'DANA';
                break;
            case 25:
                $operator = 'LinkAja';
                break;
            case 26:
                $operator = 'SHOPEE PAY';
                break;
            case 27:
                $operator = 'BRI BRIZZI';
                break;
            case 28:
                $operator = 'TAPCASH BNI';
                break;
        }

        // empty array to be filled with selected operator pricelist data
        $priceArr = [];
        // get all Prepaid Pricelist data from Cache if available or fresh fetch from API
        $prepaidPricelist = $this->getAllPrepaidPricelist();
        foreach ($prepaidPricelist['data'] as $row) {
            if ($row['category'] == 'E-Money') {
                $addedPrice = $row['price'] + 1000;
                $last3DigitsCheck = substr($addedPrice, -3);
                $check = 500 - $last3DigitsCheck;
                if ($check == 0) {
                    $price = $addedPrice;
                }
                if ($check > 0 && $check < 500) {
                    $price = $addedPrice + $check;
                }
                if ($check == 500) {
                    $price = $addedPrice;
                }
                if ($check < 0) {
                    $price = $addedPrice + (500 + $check);
                }

                if ($row['brand'] == $operator) {
                    $priceArr[] = [
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name'],
                        'seller_name' => $row['seller_name']
                    ];
                }
            }
        }
        $pricelist = collect($priceArr)->sortBy('price')->toArray();
        $pricelistCall = null;

        return view('member.app.shopping.prepaid_pricelist')
            ->with('title', 'Isi Emoney')
            ->with(compact('pricelist'))
            ->with(compact('pricelistCall'))
            ->with(compact('quickbuy'))
            ->with('type', $operator_id);
    }

    public function getPLNPrepaidPricelist()
    {
        // get user and check if is_store for quickbuy
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        $priceArr = [];
        // get all Prepaid Pricelist data from Cache if available or fresh fetch from API
        $prepaidPricelist = $this->getAllPrepaidPricelist();
        foreach ($prepaidPricelist['data'] as $row) {
            if ($row['category'] == 'PLN') {
                if ($row['price'] <= 40000) {
                    $initPrice = $row['price'] + 50;
                }
                if ($row['price'] > 40000) {
                    $initPrice = $row['price'] + 70;
                }
                // Here we add 4% (2% for Profit Sharing Conribution, 2% for Seller's profit)
                // We also round-up last 3 digits to 500 increments, all excess will be Seller's profit
                $addedPrice = $initPrice + 2000;
                $last3DigitsCheck = substr($addedPrice, -3);
                $check = 500 - $last3DigitsCheck;
                if ($check == 0) {
                    $price = $addedPrice;
                }
                if ($check > 0 && $check < 500) {
                    $price = $addedPrice + $check;
                }
                if ($check == 500) {
                    $price = $addedPrice;
                }
                if ($check < 0) {
                    $price = $addedPrice + (500 + $check);
                }

                if ($row['brand'] == 'PLN') {
                    $priceArr[] = [
                        'buyer_sku_code' => $row['buyer_sku_code'],
                        'desc' => $row['desc'],
                        'price' => $price,
                        'brand' => $row['brand'],
                        'product_name' => $row['product_name'],
                        'seller_name' => $row['seller_name']
                    ];
                }
            }
        }

        $pricelist = collect($priceArr)->sortBy('price')->toArray();
        $pricelistCall = null; //placeholder

        return view('member.app.shopping.prepaid_pricelist')
            ->with('title', 'Token PLN')
            ->with(compact('pricelist'))
            ->with(compact('pricelistCall'))
            ->with(compact('quickbuy'))
            ->with('type', 3);
    }

    public function postShoppingDigitalOrder(Request $request)
    {
        $user = Auth::user();

        // validate input
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'integer', 'min:1', 'max:40'],
            'buyer_sku_code' => ['required', 'string', 'min:1', 'max:30'],
            'customer_no' => ['required', 'numeric', 'digits_between:4,20'],
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', $validator->errors()->first());
            return redirect()->back();
        }

        $type = $request->type;

        // Get Product object from buyer_sku_code
        if ($type >= 1 && $type < 4 || $type >= 21 && $type < 29) {
            $product = $this->getFilteredPrepaidProductArray($request->type, $request->buyer_sku_code);
        } elseif ($type >= 4 && $type < 11) {
            if (Cache::has($request->customer_no)) {
                $product = $this->getFilteredPostpaidProductArray($type, $request->customer_no);
            } else {
                Alert::error('Error', 'Order Expired, silakan ulangi order anda');
                return redirect()->back();
            }
        }
        if (!$product) {
            Alert::error('Oops', 'Ada yang salah dengan order anda, sila ulangi kembali');
            return redirect()->route('member.home');
        }

        // Create new record for the order
        $sale = new DigitalSale;
        $sale->user_id = $user->id;
        $sale->vendor_id = 5;
        $sale->ppob_code = $product->ref_id;
        $sale->type = $request->type;
        $sale->ppob_price = $product->price;
        $sale->ppob_date = date('Y-m-d');
        $sale->buyer_code = $product->buyer_sku_code;
        $sale->product_name = $request->customer_no;
        $sale->message = $product->desc;
        $sale->harga_modal = $product->seller_price;
        $sale->save();

        // Redirect to payment page
        return redirect()->route('member.shopping.digitalPayment', ['sale_id' => $sale->id]);
    }

    public function postShoppingStoreQuickbuy(Request $request)
    {
        $user = Auth::user();
        if (!$user->is_store) {
            Alert::error('Error', 'Access Denied!');
            return redirect()->back();
        }

        // validate input
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'integer', 'min:1', 'max:40'],
            'buyer_sku_code' => ['required', 'string', 'min:1', 'max:30'],
            'customer_no' => ['required', 'numeric', 'digits_between:4,20'],
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', $validator->errors()->first());
            return redirect()->back();
        }

        $type = $request->type;

        // Get Product object from buyer_sku_code
        if ($type >= 1 && $type < 4 || $type >= 21 && $type < 29) {
            $product = $this->getFilteredPrepaidProductArray($request->type, $request->buyer_sku_code);
        } elseif ($type >= 4 && $type < 11) {
            if (Cache::has($request->customer_no)) {
                $product = $this->getFilteredPostpaidProductArray($request->type, $request->customer_no);
            } else {
                Alert::error('Error', 'Order Expired, silakan ulangi order anda');
                return redirect()->back();
            }
        }
        if (!$product) {
            Alert::error('Oops', 'Ada yang salah dengan order anda, sila ulangi kembali');
            return redirect()->route('member.home');
        }

        // Create new quickbuy record for the order
        $sale = new DigitalSale;
        $sale->user_id = $user->id;
        $sale->vendor_id = $user->id;
        $sale->ppob_code = $product->ref_id;
        $sale->status = 1;
        $sale->buy_metode = 1;
        $sale->type = $request->type;
        $sale->ppob_price = $product->price;
        $sale->ppob_date = date('Y-m-d');
        $sale->buyer_code = $product->buyer_sku_code;
        $sale->product_name = $request->customer_no;
        $sale->message = $product->desc;
        $sale->harga_modal = $product->seller_price;
        $sale->save();

        // Redirect to payment page
        return redirect()->route('member.store.confirmDigitalOrder', ['id' => $sale->id]);
    }

    public function getDigitalOrderPayment($sale_id)
    {
        $user = Auth::user();
        // Get data
        $data = DigitalSale::findOrFail($sale_id);
        if ($data->user_id != $user->id) {
            Alert::error('Failed', 'Access Denied');
            return redirect()->back();
        }
        // Get eIDR balance
        $EidrBalance = new EidrBalance;
        $balance = $EidrBalance->getUserNeteIDRBalance($user->id);
        if ($data->status == 0) {
            $sellerArr = $this->getRandomDigitalSellerBasedOnBalance();
        } else {
            $sellerArr = ['shop_name' => $data->seller->sellerProfile->shop_name ?? $data->seller->username];
        }


        return view('member.app.shopping.digital_payment')
            ->with('title', 'Pembayaran')
            ->with(compact('sellerArr'))
            ->with(compact('data'))
            ->with(compact('balance'));
    }

    public function getRandomDigitalSellerBasedOnBalance()
    {
        $user = Auth::user();
        $sellers = User::where('is_store', 1)->where('provinsi', $user->provinsi)->with('sellerProfile:id,seller_id,shop_name')->get();
        if (count($sellers) < 1) {
            $sellers = User::where('is_store', 1)->with('sellerProfile:id,seller_id,shop_name')->get();
        }

        $EidrBalance = new EidrBalance;
        $sellerArr = [];

        foreach ($sellers as $seller) {
            $balance = $EidrBalance->getUserNeteIDRBalance($seller->id);
            if ($balance >= 10000) {
                $sellerArr[] = [
                    'id' => $seller->id,
                    'shop_name' => $seller->sellerProfile->shop_name ?? $seller->username,
                    'balance' => $balance
                ];
            }
        }
        // return random selected array based on 5 highest balance
        return collect($sellerArr)->sortByDesc('balance')->take(5)->random(1)->first();
    }

    public function postShoppingConfirmDigitalOrderByEidr(Request $request)
    {
        $user = Auth::user();

        // validate input
        $validator = Validator::make($request->all(), [
            'salesID' => 'required|integer|exists:ppob,id',
            'seller_id' => 'required|integer|exists:seller_profiles,seller_id',
            'password' => 'required|numeric|digits_between:4,9'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        // Check 2FA
        $check = Hash::check($request->password, $user->{'2fa'});
        if (!$check) {
            Alert::error('Error', 'Pin 2FA yang anda masukkan salah!');
            return redirect()->back();
        }

        // Use Atomic Lock to prevent race conditions
        $lock = Cache::lock('shopping_digital_' . $user->id, 60);

        if ($lock->get()) {
            // Get Data and check auth
            $data = DigitalSale::findOrFail($request->salesID);
            if ($data->user_id != $user->id) {
                $lock->release();
                Alert::error('Error', 'Access Denied!');
                return redirect()->back();
            }

            // Get user's balance, check remaining and deduct
            $EidrBalance = new EidrBalance;
            $balance = $EidrBalance->getUserNeteIDRBalance($user->id);
            $remaining = $balance - $data->ppob_price;
            if ($remaining < 0) {
                $lock->release();
                Alert::error('Gagal', 'Saldo eIDR anda tidak cukup!');
                return redirect()->back();
            }

            // create new negative balance to deduct
            $newBalance = new EidrBalance;
            $newBalance->user_id = $user->id;
            $newBalance->amount = $data->ppob_price;
            $newBalance->type = 0;
            $newBalance->source = 6;
            $newBalance->tx_id = $data->id;
            $newBalance->note = 'Belanja: ' . $data->buyer_code . ' ' . $data->product_name;
            $newBalance->save();

            // Dispatch job
            FireDigiflazzTransactionJob::dispatch($data->id)->onQueue('digital');

            // Update Sales Data status to processing
            $data->status = 5;
            $data->buy_metode = 2;
            $data->tx_id = $newBalance->id;
            $data->vendor_id = $request->seller_id;
            $data->save();

            $lock->release();

            Alert::success('Pembayaran Berhasil', 'Pesanan anda segera diproses!');
            return redirect()->back();
        }
        Alert::error('Error', 'Access Denied!');
        return redirect()->back();
    }

    public function getShoppingReceipt($id)
    {
        $data = DigitalSale::findOrFail($id);

        return view('member.app.shopping.print_receipt')
            ->with(compact('data'));
    }

    public function getShoppingTransactions()
    {
        $user = Auth::user();

        // Physical goods
        $physical_tx = MasterSales::where('user_id', $user->id)
            ->where('status', '>', 0)
            ->where('buy_metode', '>', 0)
            ->with('seller')
            ->orderByDesc('created_at')
            ->paginate(15);

        // Digital goods
        $digital_tx = DigitalSale::where('user_id', $user->id)
            ->with('seller')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.app.shopping.transactions')
            ->with('title', 'Riwayat Transaksi')
            ->with(compact('physical_tx'))
            ->with(compact('digital_tx'));
    }

    public function getPostpaidList()
    {
        return view('member.app.shopping.postpaid_list')
            ->with('title', 'Pascabayar');
    }

    public function getHPPostpaidList()
    {
        return view('member.app.shopping.hp_postpaid_list')
            ->with('title', 'HP Pascabayar');
    }

    public function getHPPostpaidCheckCustomerNo($buyer_sku_code)
    {
        // get user and check if is_store for quickbuy
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        return view('member.app.shopping.postpaid_check_no')
            ->with('type', 6)
            ->with(compact('buyer_sku_code'))
            ->with(compact('quickbuy'))
            ->with('title', 'HP Pascabayar');
    }

    public function getPostpaidCheckCustomerNo($type)
    {
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        $buyer_sku_code = 'BPJS';
        switch ($type) {
            case 5:
                $buyer_sku_code = 'PLNPOST';
                break;
            case 7:
                $buyer_sku_code = 'TELKOM';
                break;
            case 9:
                $buyer_sku_code = 'PGN';
                break;
        }
        return view('member.app.shopping.postpaid_check_no')
            ->with(compact('type'))
            ->with(compact('quickbuy'))
            ->with(compact('buyer_sku_code'))
            ->with('title', 'Pascabayar');
    }

    public function getPDAMCheckCustomerNo()
    {
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        $postpaidPricelist = $this->getAllPostpaidPricelist();
        $areaArray = [];
        foreach ($postpaidPricelist['data'] as $row) {
            if ($row['brand'] == 'PDAM') {
                $areaArray[] = [
                    'product_name' => $row['product_name'],
                    'buyer_sku_code' => $row['buyer_sku_code']
                ];
            }
        }
        $areaArray = collect($areaArray)->sortBy('product_name')->toArray();

        return view('member.app.shopping.pdam_postpaid_check_no')
            ->with(compact('quickbuy'))
            ->with(compact('areaArray'))
            ->with('title', 'PDAM');
    }

    public function getMultifinanceCheckCustomerNo()
    {
        $user = Auth::user();
        $quickbuy = false;
        if ($user->is_store) {
            $quickbuy = true;
        }
        $postpaidPricelist = $this->getAllPostpaidPricelist();
        $priceArray = [];
        foreach ($postpaidPricelist['data'] as $row) {
            if ($row['brand'] == 'MULTIFINANCE') {
                $priceArray[] = [
                    'product_name' => $row['product_name'],
                    'buyer_sku_code' => $row['buyer_sku_code']
                ];
            }
        }
        $priceArray = collect($priceArray)->sortBy('product_name')->toArray();

        return view('member.app.shopping.multifinance_postpaid_check_no')
            ->with(compact('quickbuy'))
            ->with(compact('priceArray'))
            ->with('title', 'PDAM');
    }
}
