<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Auth;
use App\Model\Member\LMBreward;
use Illuminate\Http\Request;
use App\Jobs\Member\SendLMBRewardMarketplaceJob;
use App\User;
use Hash;
use App\Model\Member\Product;
use App\Model\Member\Sales;
use App\Model\Member\MasterSales;
use App\Model\Member\SellerProfile;
use App\Jobs\UserClaimDividendJob;
use Illuminate\Support\Facades\Cache;
use App\Notifications\StockistNotification;
use App\Notifications\VendorNotification;
use App\Jobs\ForwardShoppingPaymentJob;
use App\Model\Member\DigitalSale;
use App\Model\Member\EidrBalance;
use App\Model\Member\Region;
use App\Http\Controllers\Member\DigiflazzController;
use App\Jobs\SendLMBClaimRoyaltyJob;
use App\Model\Member\BonusRoyalty;
use App\Model\Member\EidrBalanceTransaction;
use App\Model\Member\LMBdividend;
use App\Model\Member\Staking;
use App\Model\Member\UsersDividend;
use DB;
use Validator;
use IEXBase\TronAPI\Exception\TronException;

class AjaxController extends Controller
{
    // Resubscribe
    public function postResubscribeConfirm(Request $request)
    {
        $user = Auth::user();

        // Check (only premiumed account allowed)
        if ($user->member_type == 0) {
            return response()->json(['success' => false, 'message' => 'Anda belum berhak untuk Resubscribe!']);
        }

        // Check the txid hash
        $hash = $request->hash;
        $check = $this->checkUsedHashExist($hash, 'resubscribe', 'hash');
        $check2 = $this->checkUsedHashExist($hash, 'stockist_request', 'hash');
        if ($check || $check2) {
            return response()->json(['success' => false, 'message' => 'Hash Transaksi sudah pernah digunakan pada transaksi sebelumnya']);
        }
        $receiver = config('services.tron.address.burn');
        $amount = 100000000; // 100 LMB

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('resubscribe_' . $user->id, 20);

        if ($lock->get()) {
            $tron = $this->getTron();
            $i = 1;
            do {
                try {
                    sleep(1);
                    $response = $tron->getTransaction($hash);
                } catch (TronException $e) {
                    $i++;
                    continue;
                }
                break;
            } while (
                $i < 23
            );

            if ($i == 23) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Hash Transaksi Bermasalah!']);
            };

            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            // Check receiver address, token id and amount
            if ($hashReceiver != $receiver) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Alamat Tujuan Transfer Salah!']);
            }
            if ($hashAsset != '1002640') {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Bukan token LMB yang benar!']);
            }
            if ($hashAmount != $amount) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Jumlah transfer salah!']);
            }

            // Modify User's model and save
            $user->user_type = 10;
            $user->expired_at = date('Y-m-d 00:00:00', strtotime('+365 days'));
            $user->save();

            $lock->release();
            return response()->json(['success' => true]);
        }
    }

    //LMB Rewards
    public function postClaimShoppingReward()
    {
        $user = Auth::user();

        if ($user->tron == null) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengatur alamat TRON anda']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('claim_' . $user->id, 20);

        if ($lock->get()) {
            // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
            $LMBreward = new LMBreward;
            $netLMBReward = $LMBreward->getUserNetLMBReward($user->id);

            if ($netLMBReward > 0) {
                // Create negative LMBreward model record
                $reward = new LMBreward;
                $reward->user_id = $user->id;
                $reward->amount = $netLMBReward;
                $reward->type = 0;
                $reward->is_store = 0;
                $reward->save();

                // Dispatch job
                SendLMBRewardMarketplaceJob::dispatch($reward->id)->onQueue('tron');

                $lock->release();

                return response()->json(['success' => true, 'message' => 'Reward Claimed']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something is wrong, try again later']);
    }

    public function postClaimRoyalty()
    {
        $user = Auth::user();

        if ($user->tron == null) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengatur alamat TRON anda']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('claim_' . $user->id, 20);

        if ($lock->get()) {
            // Get bonus data
            $BonusRoyalty = new BonusRoyalty;
            $bonus = $BonusRoyalty->getTotalBonusRoyalti($user->id);

            if ($bonus->net > 0) {
                $claim = new BonusRoyalty;
                $claim->user_id = $user->id;
                $claim->amount = $bonus->net;
                $claim->status = 1;
                $claim->bonus_date = date('Y-m-d H:i:s');
                $claim->save();

                // Dispatch job
                SendLMBClaimRoyaltyJob::dispatch($claim->id)->onQueue('tron');

                $lock->release();

                return response()->json(['success' => true, 'message' => 'Reward Claimed']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something is wrong, try again later']);
    }

    // Claim Shopping Reward directly into Staked amount
    public function postStakeShoppingReward()
    {
        $user = Auth::user();

        if ($user->tron == null) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengatur alamat TRON anda']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
            $LMBreward = new LMBreward;
            $netLMBReward = $LMBreward->getUserNetLMBReward($user->id);

            if ($netLMBReward > 0) {
                // Create negative LMBreward model record
                $reward = new LMBreward;
                $reward->user_id = $user->id;
                $reward->amount = $netLMBReward;
                $reward->type = 0;
                $reward->is_store = 0;
                $reward->hash = 'Staked';
                $reward->save();

                // Add Stake
                $stake = new Staking;
                $stake->user_id = $user->id;
                $stake->type = 1;
                $stake->amount = $netLMBReward;
                $stake->hash = 'Stake dari Reward Jual-Beli ' . date('M y');
                $stake->save();

                $lock->release();

                return response()->json(['success' => true, 'message' => 'Reward Staked']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something is wrong, try again later']);
    }

    public function postStakeRoyalty()
    {
        $user = Auth::user();

        if ($user->tron == null) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengatur alamat TRON anda']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            // Get bonus data
            $BonusRoyalty = new BonusRoyalty;
            $bonus = $BonusRoyalty->getTotalBonusRoyalti($user->id);

            if ($bonus->net > 0) {
                $claim = new BonusRoyalty;
                $claim->user_id = $user->id;
                $claim->amount = $bonus->net;
                $claim->status = 1;
                $claim->bonus_date = date('Y-m-d H:i:s');
                $claim->save();

                // Add Stake
                $stake = new Staking;
                $stake->user_id = $user->id;
                $stake->type = 1;
                $stake->amount = $bonus->net;
                $stake->hash = 'Stake dari Bonus Royalty ' . date('d M Y');
                $stake->save();

                $lock->release();

                return response()->json(['success' => true, 'message' => 'Bonus Staked']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Something is wrong, try again later']);
    }

    public function getStakeAdd(Request $request)
    {
        return view('member.app.ajax.get_stake_add')
            ->with('max', $request->max);
    }

    public function getStakeSubstract(Request $request)
    {
        return view('member.app.ajax.get_stake_substract')
            ->with('max', $request->max);
    }

    public function postStakeConfirm(Request $request)
    {
        $user = Auth::user();

        // Check the hash
        $hash = $request->hash;
        $check = $this->checkUsedHashExist($hash, 'staking', 'hash');
        if ($check) {
            return response()->json(['success' => false, 'message' => 'Hash Transaksi sudah pernah digunakan pada transaksi sebelumnya']);
        }
        $receiver = config('services.tron.address.lmb_staking');
        $amount = $request->amount;

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            $tron = $this->getTron();
            $i = 1;
            do {
                try {
                    sleep(1);
                    $response = $tron->getTransaction($hash);
                } catch (TronException $e) {
                    $i++;
                    continue;
                }
                break;
            } while ($i < 23);

            if ($i == 23) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Hash Transaksi Bermasalah!']);
            };

            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            // Check amount (with decimals precision), token_id, and receiver's address
            if ($hashAmount == $amount * config('services.tron.decimals.lmb')) {
                if ($hashAsset == config('services.tron.token_id.lmb')) {
                    if ($hashReceiver == $receiver) {
                        // Add Stake
                        $stake = new Staking;
                        $stake->user_id = $user->id;
                        $stake->type = 1;
                        $stake->amount = $amount;
                        $stake->hash = $hash;
                        $stake->save();

                        $lock->release();
                        return response()->json(['success' => true]);
                    } else {
                        $lock->release();
                        return response()->json(['success' => false, 'message' => 'Alamat Tujuan Transfer Salah!']);
                    }
                } else {
                    $lock->release();
                    return response()->json(['success' => false, 'message' => 'Bukan token LMB yang benar!']);
                }
            } else {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Nominal Transfer Salah!']);
            }
        }
    }

    public function postUnstake(Request $request)
    {
        $user = Auth::user();
        if ($user->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            return response()->json(['success' => false, 'message' => 'Membership Expired!']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            $amount = $request->amount;

            // check negative overdraft
            if ($amount <= 0) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Amount cannot be zero or less!']);
            }

            $Staking = new Staking;
            $userStakedLMB = $Staking->getUserStakedLMB($user->id);
            // Double check to preven negative overdraft
            $newStakedLMB = $userStakedLMB - $amount;
            if ($newStakedLMB < 0) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Amount exceeds staked balance!']);
            } else {
                $due_date = date('Y-m-d', strtotime('+7 days'));
                try {
                    // Insert record to update staking balance
                    $unstake_id = DB::table('staking')->insertGetId([
                        'user_id' => $user->id,
                        'type' => 2,
                        'amount' => $amount,
                        'hash' => 'Unstaking Process (' . $due_date . ') ' . $user->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    // Insert unstake schedule
                    DB::table('unstake')->insertGetId([
                        'staking_id' => $unstake_id,
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'due_date' => $due_date,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $lock->release();
                    return response()->json(['success' => true]);
                } catch (\Throwable $th) {
                    $lock->release();
                    return response()->json(['success' => false, 'message' => 'Error updating Staking data!']);
                }
            }
        }
    }

    public function postClaimStakingDividend()
    {
        $user = Auth::user();
        if ($user->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            return response()->json(['success' => false, 'message' => 'Membership Expired!']);
        }
        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('stake_' . $user->id, 20);

        if ($lock->get()) {
            // Double check dividend amount
            $UsersDividend = new UsersDividend;
            $userDividend = $UsersDividend->getUserDividend($user->id);

            if ($userDividend->net >= 1000) {
                // Create new negative record in user's dividend table
                $dividend = new UsersDividend;
                $dividend->user_id = $user->id;
                $dividend->type = 0;
                $dividend->amount = $userDividend->net;
                $dividend->date = date('Y-m-d');
                $dividend->save();
                // Dispatch the job
                UserClaimDividendJob::dispatch($user->id, $dividend->id)->onQueue('bonus');
                sleep(3);
                $lock->release();
                return response()->json(['success' => true]);
            } else {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Not enough available dividend!']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Access Denied!']);
        }
    }

    // Shopping AJAXes
    public function getShopName(Request $request)
    {
        $shopNames = null;
        if ($request->name != null) {
            $shopNames = SellerProfile::where('shop_name', 'LIKE', '%' . $request->name . '%')
                ->select('id', 'shop_name', 'seller_id')
                ->orderBy('shop_name', 'ASC')
                ->get();
        }
        return view('member.app.ajax.get_shop_name_autocomplete')
            ->with('getData', $shopNames);
    }

    public function getUserName(Request $request)
    {
        $usernames = null;
        if ($request->name != null) {
            $usernames = User::where('username', 'LIKE', '%' . $request->name . '%')
                ->select('id', 'username')
                ->orderBy('username', 'ASC')
                ->get();
        }
        return view('member.app.ajax.get_username_autocomplete')
            ->with(compact('usernames'));
    }

    public function getDownlineUsername(Request $request)
    {
        $user = Auth::user();
        $usernames = null;
        $uplines = $user->upline_detail . ',[' . $user->id . ']';
        if (!$user->upline_detail) {
            $uplines = '[' . $user->id . ']';
        }
        if ($request->name) {
            $usernames = User::where('username', 'LIKE', '%' . $request->name . '%')
                ->where('upline_detail', 'LIKE', $uplines . '%')
                ->select('id', 'username')
                ->orderBy('username', 'ASC')
                ->get();
        }
        return view('member.app.ajax.get_username_autocomplete')
            ->with(compact('usernames'));
    }

    public function postChangeBuyerQuickbuy(Request $request)
    {
        $user = Auth::user();
        $data = DigitalSale::findOrFail($request->salesID);
        if ($data->vendor_id != $user->id) {
            return response()->json(['success' => false]);
        }
        $data->user_id = $request->user_id;
        $data->save();
        return response()->json(['success' => true], 201);
    }

    public function getProductByCategory(Request $request)
    {
        $getSellerProducts = Product::where('seller_id', $request->seller_id)
            ->where('category_id', $request->category_id)
            ->where('is_active', 1)
            ->get();

        if ($request->category_id == 0) {
            $getSellerProducts = Product::where('seller_id', $request->seller_id)
                ->where('is_active', 1)
                ->get();
        }

        return view('member.app.ajax.product-by-category')
            ->with('products', $getSellerProducts);
    }

    public function getProductById(Request $request)
    {
        $getProduct = Product::findOrFail($request->product_id);
        $search = false;
        if ($request->search) {
            $search = true;
        }

        return view('member.app.ajax.product-by-id')
            ->with(compact('search'))
            ->with('product', $getProduct);
    }

    public function postAddToCart(Request $request)
    {
        $user = Auth::user();
        $Product = Product::find($request->product_id);

        $remaining = $Product->qty - $request->quantity;
        if (
            $remaining < 0
        ) {
            return response()->json(['success' => false, 'message' => 'Stock Produk tidak cukup!']);
        }

        if (isset($request->buyer_id)) {
            \Cart::session($request->buyer_id)->add(array(
                'id' => $Product->id,
                'name' => $Product->name,
                'price' => $Product->price,
                'quantity' => $request->quantity,
                'attributes' => array(),
                'associatedModel' => $Product
            ));
        } else {
            \Cart::session($user->id)->add(array(
                'id' => $Product->id,
                'name' => $Product->name,
                'price' => $Product->price,
                'quantity' => $request->quantity,
                'attributes' => array(),
                'associatedModel' => $Product
            ));
        }

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang!']);
    }

    public function getCartContents(Request $request)
    {
        \Cart::session($request->user_id);
        $items = \Cart::getContent();

        return view('member.app.ajax.get-cart-contents')
            ->with('products', $items);
    }

    public function getDeleteCartItem(Request $request)
    {
        \Cart::session($request->user_id)->remove($request->product_id);
        $items = \Cart::getContent();

        return view('member.app.ajax.get-cart-contents')
            ->with('products', $items);
    }

    public function getCartTotal(Request $request)
    {
        $getTotal = \Cart::session($request->user_id)->getSubTotal();
        $total = number_format($getTotal);

        return $total;
    }

    public function getCartCheckout(Request $request)
    {
        $user = Auth::user();
        $buyer_id = $user->id;
        $seller = User::find($request->seller_id);
        $status = false;
        $message = '';
        if (!$seller || !$seller->is_store) {
            $message = 'Data Toko tidak valid!';
        }
        $route = route('member.shopping.postCheckout');

        // Check if Request buyer_id exist (POS), then Seller = auth User
        if ($request->buyer_id) {
            $buyer_id = $request->buyer_id;
            $seller = $user;
            $route = route('member.store.postCheckoutPOS');
        }

        // Get data from Shopping Cart
        $getTotal = \Cart::session($buyer_id)->getSubTotal();
        $cartItems = \Cart::session($buyer_id)->getContent();
        $itemsArray = $cartItems->toArray();

        //check available stock
        foreach ($itemsArray as $item) {
            $stock = Product::find($item['id'])->qty;
            $remaining = $stock - $item['quantity'];
            if ($remaining < 0) {
                $status = false;
                $message = 'Insufficient Stock';
                return view('member.app.ajax.checkout')
                    ->with('status', $status)
                    ->with('name', $item['name'])
                    ->with('stock', $item['associatedModel']['qty']);
            }
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('checkout_' . $buyer_id, 10);

        if ($lock->get()) {
            $modelMasterSales = new MasterSales;
            $invoice = $modelMasterSales->getCodeMasterSales($buyer_id);
            $sale_date = date('Y-m-d');

            // Insert Master Sales record
            $masterSales = new MasterSales;
            $masterSales->user_id = $buyer_id;
            $masterSales->stockist_id = $seller->id;
            $masterSales->invoice = $invoice;
            $masterSales->total_price = $getTotal;
            $masterSales->sale_date = $sale_date;
            // If POS
            if ($request->buyer_id) {
                $masterSales->status = 1;
                $masterSales->buy_metode = 1;
            }
            $masterSales->save();

            // Insert Sales Record (Sold products details)
            foreach ($itemsArray as $item) {
                $sales = new Sales;
                $sales->user_id = $buyer_id;
                $sales->stockist_id = $seller->id;
                $sales->purchase_id = $item['id'];
                $sales->invoice = $invoice;
                $sales->amount = $item['quantity'];
                $sales->sale_price = $item['quantity'] * $item['price'];
                $sales->sale_date = $sale_date;
                $sales->master_sales_id = $masterSales->id;
                $sales->save();
            }

            $status = true;

            $lock->release();
        }

        return view('member.app.ajax.checkout')
            ->with(compact('status'))
            ->with(compact('message'))
            ->with(compact('route'))
            ->with('masterSalesID', $masterSales->id);
    }

    public function postCancelShoppingPaymentBuyer(Request $request)
    {
        $user = Auth::user();
        try {
            $data = MasterSales::find($request->masterSalesID);
            // check auth
            if ($data->user_id != $user->id) {
                return response()->json(['success' => false]);
            }
            $data->delete();
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }
    public function postCancelDigitalShoppingPaymentBuyer(Request $request)
    {
        $user = Auth::user();
        try {
            $data = DigitalSale::find($request->salesID);
            // check auth
            if ($data->user_id != $user->id) {
                return response()->json(['success' => false]);
            }
            $data->delete();
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }

    public function postShoppingPaymentCash(Request $request)
    {
        $user = Auth::user();

        try {
            // Update MasterSales data
            $data = MasterSales::find($request->masterSalesID);
            $data->status = 1;
            $data->buy_metode = 1;
            $data->save();

            // Notify seller
            $seller = User::where('id', $data->stockist_id)->select('id', 'chat_id')->first();
            if ($seller->chat_id != null) {
                $seller->notify(new StockistNotification([
                    'buyer' => $user->username,
                    'price' => 'Rp' . number_format($data->total_price),
                    'payment' => 'TUNAI'
                ]));
            }

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    public function postDigitalShoppingPaymentCash(Request $request)
    {
        $user = Auth::user();

        // validate input
        $validator = Validator::make($request->all(), [
            'salesID' => 'required|integer|exists:ppob,id',
            'seller_id' => 'required|integer|exists:seller_profiles,seller_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false]);
        }

        try {
            // Update Sale data
            $data = DigitalSale::findOrFail($request->salesID);
            $data->status = 1;
            $data->vendor_id = $request->seller_id;
            $data->buy_metode = 1;
            $data->save();

            // Notify seller
            $seller = User::where('id', $data->vendor_id)->select('id', 'chat_id')->first();
            if ($seller->chat_id != null) {
                $seller->notify(new VendorNotification([
                    'buyer' => $user->username,
                    'product' => $data->message,
                    'price' => 'Rp' . number_format($data->ppob_price),
                    'payment' => 'TUNAI'
                ]));
            }

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    public function postShoppingPaymentInternaleIDR(Request $request)
    {
        $user = Auth::user();

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('shopping_payment_' . $user->id, 50);

        if ($lock->get()) {
            // Get internal eIDR balance
            $EidrBalance = new EidrBalance;
            $balance = $EidrBalance->getUserNeteIDRBalance($user->id);
            // get MasterSales data
            $data = MasterSales::find($request->masterSalesID);
            $salesAmount = $data->total_price;
            $royalty = $salesAmount * (2 / 100);
            $shop = SellerProfile::where('seller_id', $data->stockist_id)->select('shop_name')->first();

            // second check
            $remaining = $balance - $salesAmount;
            if ($remaining < 0) {
                $lock->release();
                return response()->json(['success' => false]);
            }

            // deduct user balance
            $uBalance = new EidrBalance;
            $uBalance->user_id = $user->id;
            $uBalance->amount = $salesAmount;
            $uBalance->type = 0;
            $uBalance->source = 6;
            $uBalance->tx_id = $data->id;
            $uBalance->note = "Pembayaran Belanja di " . $shop->shop_name;
            $uBalance->save();

            // add seller balance minus royalty
            $sBalance = new EidrBalance;
            $sBalance->user_id = $data->stockist_id;
            $sBalance->amount = round($salesAmount - $royalty, 2, PHP_ROUND_HALF_DOWN);
            $sBalance->type = 1;
            $sBalance->source = 6;
            $sBalance->tx_id = $data->id;
            $sBalance->note = "Pembayaran Belanja dari " . $user->username;
            $sBalance->save();

            // update MasterSales data
            $data->buy_metode = 2;
            $data->status = 2;
            $data->save();

            // Create LMBdividend (1% from sales)
            $dividend = new LMBdividend;
            $dividend->amount = $royalty / 2;
            $dividend->type = 1;
            $dividend->status = 1;
            $dividend->source_id = $data->id;
            $dividend->save();

            $lock->release();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function postShoppingPaymentExternaleIDR(Request $request)
    {
        $user = Auth::user();
        $hash = $request->hash;
        $receiver = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';

        // check if hash used
        $check = MasterSales::where('tron_transfer', $hash)->exists();
        if ($check) {
            return response()->json(['success' => false, 'message' => 'Hash Transaksi Sudah terpakai!']);
        }

        // Use Atomic lock to prevent race condition
        $lock = Cache::lock('shopping_payment_' . $user->id, 50);

        if ($lock->get()) {
            // get MasterSales data
            $data = MasterSales::find($request->masterSalesID);

            // get Tron instance and check hash to blockchain
            $tron = $this->getTron();
            $i = 1;
            do {
                try {
                    sleep(1);
                    $response = $tron->getTransaction($hash);
                } catch (TronException $e) {
                    $i++;
                    continue;
                }
                break;
            } while ($i < 23);

            if ($i == 23) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Hash Transaksi Bermasalah!']);
            };

            // parse the response
            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            // check
            if ($hashReceiver !== $receiver) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Tujuan transfer salah!']);
            }

            if ($hashAsset !== '1002652') {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Bukan token eIDR yang benar!']);
            }

            if ($hashAmount != ($data->total_price * 100)) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Jumlah transfer salah!']);
            }

            // update MasterSales record
            $data->status = 2;
            $data->buy_metode = 3;
            $data->tron_transfer = $hash;
            $data->save();

            // Get products and update stock amount
            $products = Sales::where('master_sales_id', $request->masterSalesID)
                ->select(['id', 'purchase_id', 'amount'])
                ->get();

            foreach ($products as $item) {
                $product = Product::find($item->purchase_id);
                $remaining = $product->qty - $item->amount;
                $product->update(['qty' => $remaining]);
                $product->save();
            }

            // Notify seller
            if ($user->chat_id != null) {
                User::find($user->id)->notify(new StockistNotification([
                    'buyer' => $user->username,
                    'price' => 'Rp' . number_format($data->total_price),
                    'payment' => 'eIDR Eksternal (Tuntas Otomatis)'
                ]));
            }

            // Dispatch job to forward payment to Seller
            ForwardShoppingPaymentJob::dispatch($request->masterSalesID)->onQueue('tron');

            $lock->release();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Transaksi duplikat']);
    }

    // Profile and Address Utility
    public function getSearchAddressRegionByType($type, Request $request)
    {
        $Region = new Region;
        $data = null;
        if ($type == 'kota') {
            if ($request->provinsi != 0) {
                $data = $Region->getKabupatenKotaByProvinsi($request->provinsi);
            }
        }
        if ($type == 'kecamatan') {
            if ($request->kota != 0) {
                $data = $Region->getKecamatanByKabupatenKota($request->kota);
            }
        }
        if ($type == 'kelurahan') {
            if ($request->kecamatan != 0) {
                $data = $Region->getKelurahanByKecamatan($request->kecamatan);
            }
        }
        return view('member.app.ajax.searchRegion')
            ->with(compact('type'))
            ->with(compact('data'));
    }

    public function postAddUserProfile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|regex:/^[\pL\s\-]+$/u',
            'provinsi' => 'required|integer',
            'kota' => 'required|integer',
            'kecamatan' => 'required|integer',
            'kelurahan' => 'required|integer',
            'alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        if ($user->is_profile == 0) {
            // Parse the region ID
            $Region = new Region;
            $provinsi = $Region->getProvinsiByID($request->provinsi);
            $kota = $Region->getKabByID($request->kota);
            $kecamatan = $Region->getKecByID($request->kecamatan);
            $kelurahan = $Region->getKelByID($request->kelurahan);
            if (!$provinsi || !$kota || !$kecamatan || !$kelurahan) {
                return response()->json(['success' => false, 'message' => 'Alamat tidak valid']);
            }

            // record to User model
            $user->full_name = $request->full_name;
            $user->provinsi = $provinsi->nama;
            $user->kota = $kota->nama;
            $user->kecamatan = $kecamatan->nama;
            $user->kelurahan = $kelurahan->nama;
            $user->alamat = $request->alamat;
            $user->is_profile = 1;
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    public function postEditUserProfile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'provinsi' => 'required|integer',
            'kota' => 'required|integer',
            'kecamatan' => 'required|integer',
            'kelurahan' => 'required|integer',
            'alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        if ($user->is_profile == 1) {
            // Parse the region ID
            $Region = new Region;
            $provinsi = $Region->getProvinsiByID($request->provinsi);
            $kota = $Region->getKabByID($request->kota);
            $kecamatan = $Region->getKecByID($request->kecamatan);
            $kelurahan = $Region->getKelByID($request->kelurahan);
            if (!$provinsi || !$kota || !$kecamatan || !$kelurahan) {
                return response()->json(['success' => false, 'message' => 'Alamat tidak valid']);
            }

            // record to User model
            $user->provinsi = $provinsi->nama;
            $user->kota = $kota->nama;
            $user->kecamatan = $kecamatan->nama;
            $user->kelurahan = $kelurahan->nama;
            $user->alamat = $request->alamat;
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    // Telegram
    public function getCreateTelegramLink()
    {
        $user = Auth::user();
        $length = 10;
        $linkCode = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
        Cache::put($linkCode, $user->id, 600);
        return response()->json(['success' => true, 'message' => $linkCode], 201);
    }

    public function getRemoveTelegramLink()
    {
        $user = Auth::user();
        $user->chat_id = null;
        $user->save();
        return response()->json(['success' => true], 201);
    }

    // Store
    public function getSearchProductImage(Request $request)
    {
        $getProductImage = null;
        if ($request->name != null) {
            $getProductImage = Product::where('image', 'LIKE', '%' . $request->name . '%')
                ->select('image')
                ->groupBy('image')
                ->orderBy('image', 'ASC')
                ->get();
        }
        return view('member.app.ajax.get_image_autocomplete')
            ->with('productImages', $getProductImage);
    }

    public function postStoreCancelPhysicalOrder(Request $request)
    {
        $user = Auth::user();
        $data = MasterSales::findOrFail($request->masterSalesID);
        // check store authority
        if ($data->stockist_id != $user->id || $data->status != 1) {
            return response()->json(['success' => false]);
        }

        $data->status = 10;
        $data->reason = "Dibatalkan oleh Penjual";
        $data->save();

        return response()->json(['success' => true], 201);
    }

    public function postStoreCancelDigitalOrder(Request $request)
    {
        $user = Auth::user();
        $data = DigitalSale::findOrFail($request->salesID);
        // check store authority
        if ($data->vendor_id != $user->id || $data->status != 1) {
            return response()->json(['success' => false]);
        }

        $data->status = 3;
        $data->reason = "Dibatalkan oleh Penjual";
        $data->save();

        return response()->json(['success' => true], 201);
    }

    public function postStoreConfirmPhysicalOrder(Request $request)
    {
        $user = Auth::user();
        $data = MasterSales::findOrFail($request->masterSalesID);
        // check store authority
        if ($data->stockist_id != $user->id || $data->status != 1) {
            return response()->json(['success' => false, 'message' => 'Access Denied']);
        }

        // Check 2FA
        $check = Hash::check($request->password, $user->{'2fa'});
        if (!$check) {
            return response()->json(['success' => false, 'message' => 'Pin 2FA salah']);
        }

        // Use Atomic Lock to prevent race condition
        $lock = Cache::lock('store_payment_' . $user->id, 60);

        if ($lock->get()) {
            // Check internal eIDR balance
            $EidrBalance = new EidrBalance;
            $balance = $EidrBalance->getUserNeteIDRBalance($user->id);
            $royalty = round($data->total_price * (2 / 100));
            $remaining = $balance - $royalty;
            if ($remaining < 0) {
                $lock->release();
                return response()->json(['success' => false, 'message' => 'Saldo eIDR tidak mencukupi']);
            }

            // Create negative balance record to Deduct seller's eIDR balance
            $newBalance = new EidrBalance;
            $newBalance->user_id = $user->id;
            $newBalance->amount = $royalty;
            $newBalance->type = 0;
            $newBalance->source = 6;
            $newBalance->tx_id = $data->id;
            $newBalance->note = "Kontribusi Bagi Hasil Penjualan";
            $newBalance->save();

            // Create LMBdividend (1% from sales)
            $dividend = new LMBdividend;
            $dividend->amount = $royalty / 2;
            $dividend->type = 1;
            $dividend->status = 1;
            $dividend->source_id = $data->id;
            $dividend->save();

            // update masterSales data
            $data->status = 2;
            $data->save();

            // update stock
            $products = Sales::where(
                'master_sales_id',
                $data->id
            )
                ->select(['id', 'purchase_id', 'amount'])
                ->get();

            foreach ($products as $item) {
                $product = Product::find($item->purchase_id);
                $remaining = $product->qty - $item->amount;
                $product->update(['qty' => $remaining]);
                $product->save();
            }

            $lock->release();
            return response()->json(['success' => true]);
        }
    }

    public function getCheckDigitalOrderStatus(Request $request)
    {
        try {
            $data = DigitalSale::findOrFail($request->salesID);
            if ($data->status != 5) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    public function getCheckPostpaidCustomerNo(Request $request)
    {
        $DFcontroller = new DigiflazzController;
        $status = true;
        $message = '';
        $response = $DFcontroller->postpaidInquiry($request->buyer_sku_code, $request->customer_no, $request->type);
        if ($response == null) {
            $message = 'Periksa kembali nomor pelanggan yang anda masukkan';
            $status = false;
        }
        if ($response['data']['rc'] != '00') {
            $message = $response['data']['message'];
            $status = false;
        }
        if ($status) {
            Cache::put($request->customer_no, $response, now()->addMinutes(80));
        }

        return view('member.app.ajax.postpaid_inquiry')
            ->with(compact('status'))
            ->with('type', $request->type)
            ->with('data', $response['data'])
            ->with(compact('message'));
    }

    public function getCheckPrepaidPLNCustomerNo(Request $request)
    {
        $DFcontroller = new DigiflazzController;
        $response = $DFcontroller->prepaidPLNInquiry($request->customer_no);
        if ($response == null) {
            return response()->json(['success' => false]);
        } else {
            return response()->json(['success' => true, 'name' => $response['data']['name'], 'segment_power' => $response['data']['segment_power']]);
        }
    }

    public function postCancelDepositTransaction(Request $request)
    {
        $user = Auth::user();
        $data = EidrBalanceTransaction::findOrFail($request->transaction_id);
        if ($data->user_id != $user->id) {
            return response()->json(['success' => false]);
        }
        $data->delete();
        return response()->json(['success' => true]);
    }

    // Network AJAXes
    /*
    * Check Placing Request on an empty node
    * @var Request upline_id = user id, position = 0| 1 bool
    * position is linked to User's column name: left_id and right_id
    */
    public function getCheckPlacing(Request $request)
    {
        $user = Auth::user();
        $status = true;
        $upline = User::find($request->upline_id);
        if (!$upline || !$upline->member_type) {
            $status = false;
        }
        // Get position and check
        $position = 'left_id';
        if ($request->position) {
            $position = 'right_id';
        }
        if ($upline->$position) {
            $status = false;
        }
        // Get direct downlines yet to place
        $downlines = User::where('sponsor_id', $user->id)
            ->where('member_type', '>', 0)
            ->whereNull('upline_id')
            ->select('id', 'username')
            ->get();

        return view('member.app.ajax.get_placing_detail')
            ->with(compact('status'))
            ->with('upline_id', $upline->id)
            ->with(compact('position'))
            ->with(compact('downlines'));
    }
}
