<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Model\Pinsetting;
use App\Model\Package;
use App\Model\Member;
use App\Model\Validation;
use App\Model\Bank;
use App\Model\Pin;
use App\Model\Memberpackage;
use App\Model\Transferwd;
use App\Model\Bonus;
use App\Model\Transaction;
use App\Model\Sales;
use App\Model\Bonussetting;
use App\Product;
use App\Category;
use App\User;
use App\SellerProfile;
use App\Services\AbstractService;
use App\ValueObjects\Cart\ItemObject;
use App\Jobs\ForwardShoppingPaymentJob;
use App\Jobs\PPOBexecuteJob;
use Illuminate\Support\Facades\Crypt;
use App\LocalWallet;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Cache;

class AjaxmemberController extends Controller
{
    public function __construct()
    {
    }

    //new AJAX
    //Shopping
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

        return view('member.ajax.product-by-category')
            ->with('products', $getSellerProducts);
    }

    public function getProductById(Request $request)
    {
        $getProduct = Product::find($request->product_id);

        return view('member.ajax.product-by-id')
            ->with('product', $getProduct);
    }

    public function postAddToCart(Request $request)
    {
        $dataUser = Auth::user();
        $Product = Product::find($request->product_id);

        $remaining = $Product->qty - $request->quantity;
        if ($remaining < 0) {
            return response()->json(['success' => false, 'message' => 'Stock Produk tidak cukup!']);
        }

        \Cart::session($dataUser->id)->add(array(
            'id' => $Product->id,
            'name' => $Product->name,
            'price' => $Product->price,
            'quantity' => $request->quantity,
            'attributes' => array(),
            'associatedModel' => $Product
        ));

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang!']);
    }

    public function getCartContents(Request $request)
    {
        \Cart::session($request->user_id);
        $items = \Cart::getContent();

        return view('member.ajax.get-cart-contents')
            ->with('products', $items);
    }

    public function getDeleteCartItem(Request $request)
    {
        \Cart::session($request->user_id)->remove($request->product_id);
        $items = \Cart::getContent();

        return view('member.ajax.get-cart-contents')
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
        $seller = User::find($request->seller_id);
        $sellerType = 1; // 1 = Stockist, 2 = Vendor
        if ($seller->is_vendor == 1) {
            $sellerType = 2;
        }
        $getTotal = \Cart::session($user->id)->getSubTotal();
        $cartItems = \Cart::session($user->id)->getContent();
        $itemsArray = $cartItems->toArray();

        //check available stock

        foreach ($itemsArray as $item) {
            $stock = Product::find($item['id'])->qty;
            $remaining = $stock - $item['quantity'];
            if ($remaining < 0) {
                $status = false;
                return view('member.ajax.checkout')
                    ->with('status', $status)
                    ->with('name', $item['name'])
                    ->with('stock', $item['associatedModel']['qty']);
            }
        }

        $modelSales = new Sales;
        $invoice = $modelSales->getCodeMasterSales($user->id);
        $sale_date = date('Y-m-d');

        if ($sellerType == 1) {

            $dataInsertMasterSales = array(
                'user_id' => $user->id,
                'stockist_id' => $seller->id,
                'invoice' => $invoice,
                'total_price' => $getTotal,
                'sale_date' => $sale_date,
            );
            $insertMasterSales = $modelSales->getInsertMasterSales($dataInsertMasterSales);

            foreach ($itemsArray as $item) {
                $dataInsert = array(
                    'user_id' => $user->id,
                    'stockist_id' => $seller->id,
                    'purchase_id' => $item['id'],
                    'invoice' => $invoice,
                    'amount' => $item['quantity'],
                    'sale_price' => $item['quantity'] * $item['price'],
                    'sale_date' => $sale_date,
                    'master_sales_id' => $insertMasterSales->lastID
                );
                $insertSales = $modelSales->getInsertSales($dataInsert);
            }
        } else if ($sellerType == 2) {

            $dataInsertMasterSales = array(
                'user_id' => $user->id,
                'vendor_id' => $seller->id,
                'invoice' => $invoice,
                'total_price' => $getTotal,
                'sale_date' => $sale_date,
            );
            $insertMasterSales = $modelSales->getInsertVMasterSales($dataInsertMasterSales);

            foreach ($itemsArray as $item) {
                $dataInsert = array(
                    'user_id' => $user->id,
                    'vendor_id' => $seller->id,
                    'purchase_id' => $item['id'],
                    'invoice' => $invoice,
                    'amount' => $item['quantity'],
                    'sale_price' => $item['quantity'] * $item['price'],
                    'sale_date' => $sale_date,
                    'vmaster_sales_id' => $insertMasterSales->lastID
                );
                $insertSales = $modelSales->getInsertVSales($dataInsert);
            }
        }
        $status = true;
        return view('member.ajax.checkout')
            ->with('status', $status)
            ->with('sellerType', $sellerType)
            ->with('masterSalesID', $insertMasterSales->lastID);
    }

    public function postCreateLocalWallet(Request $request)
    {
        $tron = $this->getTron();

        $local_wallet = $tron->generateAddress();
        LocalWallet::create([
            'user_id' => $request->user_id,
            'address' => $local_wallet->getAddress(true),
            'private_key' => Crypt::encryptString($local_wallet->getPrivateKey())
        ]);

        return response()->json(['success' => true]);
    }

    public function postWithdrawLocalWallet(Request $request)
    {
        $mainWallet = User::find($request->user_id)->tron;
        $localWallet = LocalWallet::where('user_id', $request->user_id)->first();
        $tron = $this->getTronLocalWallet(Crypt::decryptString($localWallet->private_key));
        $trxBalance = $tron->getBalance($localWallet->address, true);
        $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;

        $to = $mainWallet;

        if ($request->asset == 1) {
            if ($request->amount > $eIDRbalance) {
                return response()->json(['success' => false, 'message' => 'Saldo aset tidak cukup!']);
            } else {

                $amount = $request->amount * 100;
                $tokenID = '1002652';
                $from = $localWallet->address;
                try {
                    $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                    $signedTransaction = $tron->signTransaction($transaction);
                    $response = $tron->sendRawTransaction($signedTransaction);
                } catch (TronException $e) {
                    die($e->getMessage());
                }

                if ($response['result'] == true) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Ada yang salah!']);
                }
            }
        } elseif ($request->asset == 2) {
            $amount = (float) $request->amount;
            if ($amount > $trxBalance) {
                return response()->json(['success' => false, 'message' => 'Saldo aset tidak cukup!']);
            } else {
                $from = $localWallet->address;
                try {
                    $transaction = $tron->getTransactionBuilder()->sendTrx($to, $amount, $from);
                    $signedTransaction = $tron->signTransaction($transaction);
                    $response = $tron->sendRawTransaction($signedTransaction);
                } catch (TronException $e) {
                    die($e->getMessage());
                }

                if ($response['result'] == true) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Ada yang salah!']);
                }
            }
        }
    }

    public function postDepositLocalWalletPay(Request $request)
    {
        $dataUser = Auth::user();
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $tron = $this->getTronLocalWallet(Crypt::decryptString($localWallet->private_key));
        $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;

        $modelTrans = new Transaction;
        $data = (object) array('id' => $dataUser->id);
        $transactionData = $modelTrans->getDetailDepositTransactionsMember($request->id_trans, $data);
        $deposit = $transactionData->price + $transactionData->unique_digit;

        $to = 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge';

        if ($deposit > $eIDRbalance) {
            return response()->json(['success' => false, 'message' => 'Saldo eIDR tidak cukup!']);
        } else {

            $amount = $deposit * 100;
            $tokenID = '1002652';
            $from = $localWallet->address;
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                return response()->json(['success' => true, 'message' => $response['txid']]);
            } else {
                return response()->json(['success' => false, 'message' => 'Ada yang salah!']);
            }
        }
    }

    public function postPayByLocalWallet(Request $request)
    {
        $dataUser = Auth::user();
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $tron = $this->getTronLocalWallet(Crypt::decryptString($localWallet->private_key));
        $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;

        $modelSales = new Sales;
        $royalti = $modelSales->getRoyaltiStockist($request->masterSalesID);

        $to = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';

        if ($royalti > $eIDRbalance) {
            return response()->json(['success' => false, 'message' => 'Saldo eIDR tidak cukup!']);
        } else {

            $amount = $royalti * 100;
            $tokenID = '1002652';
            $from = $localWallet->address;
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                $dataUpdate = array(
                    'status' => 2,
                    'tron_transfer' => $response['txid']
                );
                $modelSales->getUpdateMasterSales('id', $request->masterSalesID, $dataUpdate);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Ada yang salah!']);
            }
        }
    }

    public function postPayByLocalWalletVendor(Request $request)
    {
        $dataUser = Auth::user();
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        $tron = $this->getTronLocalWallet(Crypt::decryptString($localWallet->private_key));
        $eIDRbalance = $tron->getTokenBalance(1002652, $localWallet->address, false) / 100;

        $modelSales = new Sales;
        $royalti = $modelSales->getRoyaltiVendor($request->masterSalesID);

        $to = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';

        if ($royalti > $eIDRbalance) {
            return response()->json(['success' => false, 'message' => 'Saldo eIDR tidak cukup!']);
        } else {

            $amount = $royalti * 100;
            $tokenID = '1002652';
            $from = $localWallet->address;
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                $dataUpdate = array(
                    'status' => 2,
                    'tron_transfer' => $response['txid']
                );
                $modelSales->getUpdateVMasterSales('id', $request->masterSalesID, $dataUpdate);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Ada yang salah!']);
            }
        }
    }

    public function postToggleLocalWallet(Request $request)
    {
        $dataUser = Auth::user();
        $localWallet = LocalWallet::where('user_id', $dataUser->id)->first();
        if ($request->is_active == 1) {
            $localWallet->is_active = 1;
            $localWallet->save();
            return response()->json(['success' => true, 'message' => 'Dompet Lokal berhasil diaktifkan']);
        } elseif ($request->is_active == 0) {
            $localWallet->is_active = 0;
            $localWallet->save();
            return response()->json(['success' => true, 'message' => 'Dompet Lokal berhasil dinon-aktifkan']);
        } else {
            return response()->json(['success' => false, 'message' => 'Ada yang salah!']);
        }
    }

    public function getShopName(Request $request)
    {

        $shopNames = null;
        if ($request->name != null) {
            $shopNames = SellerProfile::where('shop_name', 'LIKE', '%' . $request->name . '%')
                ->select('id', 'shop_name', 'seller_id')
                ->orderBy('shop_name', 'ASC')
                ->get();
        }
        return view('member.ajax.get_shop_name_autocomplete')
            ->with('getData', $shopNames);
    }



    public function postCancelShoppingPaymentBuyer(Request $request)
    {
        $modelSales = new Sales;

        $dataUpdate = array(
            'status' => 10,
            'reason' => 'Dibatalkan oleh pembeli'
        );
        if ($request->sellerType == 1) {
            $modelSales->getUpdateMasterSales('id', $request->masterSalesID, $dataUpdate);
        } else {
            $modelSales->getUpdateVMasterSales('id', $request->masterSalesID, $dataUpdate);
        }

        return response()->json(['success' => true]);
    }

    public function postShoppingPayment(Request $request)
    {
        $modelSales = new Sales;
        $items = null;
        if ($request->buy_method == 1) {
            $dataUpdate = array(
                'status' => 1,
                'buy_metode' => $request->buy_method
            );
            if ($request->sellerType == 1) {
                $modelSales->getUpdateMasterSales('id', $request->masterSalesID, $dataUpdate);
                $items = $modelSales->getMemberPembayaranSalesNew($request->masterSalesID);
            } else {
                $modelSales->getUpdateVMasterSales('id', $request->masterSalesID, $dataUpdate);
                $items = $modelSales->getMemberPembayaranVSalesNew($request->masterSalesID);
            }

            foreach ($items as $item) {
                $product = Product::find($item->purchase_id);
                $remaining = $product->qty - $item->amount;
                $product->update(['qty' => $remaining]);
            }

            return response()->json(['success' => true]);
        } else if ($request->buy_method == 3) {
            $hash = $request->tron_transfer;
            $receiver = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
            if ($request->sellerType == 1) {
                $amount = $modelSales->getMasterTotalPriceStockist($request->masterSalesID);
                $timestamp = $modelSales->getStockistMasterSalesTimestamp($request->masterSalesID);
            } else {
                $amount = $modelSales->getMasterTotalPriceVendor($request->masterSalesID);
                $timestamp = $modelSales->getVendorMasterSalesTimestamp($request->masterSalesID);
            }

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
                return response()->json(['success' => false, 'message' => 'Hash Transaksi Bermasalah!']);
            };

            $hashTime = $response['raw_data']['timestamp'];
            $hashSender = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['owner_address']);
            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            if ($hashTime > $timestamp) {
                if ($hashAmount == $amount * 100) {
                    if ($hashAsset == '1002652') {
                        if ($hashReceiver == $receiver) {
                            $dataUpdate = array(
                                'status' => 2,
                                'buy_metode' => 3,
                                'tron_transfer' => $hash
                            );
                            if ($request->sellerType == 1) {
                                $modelSales->getUpdateMasterSales('id', $request->masterSalesID, $dataUpdate);
                                $items = $modelSales->getMemberPembayaranSalesNew($request->masterSalesID);
                            } else {
                                $modelSales->getUpdateVMasterSales('id', $request->masterSalesID, $dataUpdate);
                                $items = $modelSales->getMemberPembayaranVSalesNew($request->masterSalesID);
                            }

                            foreach ($items as $item) {
                                $product = Product::find($item->purchase_id);
                                $remaining = $product->qty - $item->amount;
                                $product->update(['qty' => $remaining]);
                            }
                            ForwardShoppingPaymentJob::dispatch($request->masterSalesID, $request->sellerType)->onQueue('tron');
                            return response()->json(['success' => true]);
                        } else {
                            return response()->json(['success' => false, 'message' => 'Alamat Tujuan Transfer Salah!']);
                        }
                    } else {
                        return response()->json(['success' => false, 'message' => 'Bukan token eIDR yang benar!']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Nominal Transfer Salah!']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Hash sudah terpakai!']);
            }
        }
    }

    public function postCekAddSponsor(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $canInsert = $modelValidasi->getCheckNewSponsor($request);
        $modelMember = new Member;
        $getCheck = $modelMember->getCheckUsercode($request->user_code);
        if ($getCheck->cekCode == 1) {
            $canInsert = (object) array('can' => false,  'pesan' => 'Username sudah terpakai');
        }
        $user_code = $request->user_code;
        if ($request->affiliate == 1) {
            $user_code = $modelMember->getCountNewKBBUserCode();
        }
        $data = (object) array(
            'email' => $request->email,
            'hp' => $request->hp,
            'username' => $user_code,
            'password' => $request->password,
            'affiliate' => $request->affiliate
        );
        return view('member.ajax.confirm_add_sponsor')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function postCekAddProfile(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelMember = new Member;
        $canInsert = $modelValidasi->getCheckNewProfile($request);
        $provinsi = $modelMember->getProvinsiByID($request->provinsi);
        if ($provinsi == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Provinsi harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $kota = $modelMember->getKabByID($request->kota);
        if ($kota == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kabupaten/Kota harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $kecamatan = $modelMember->getKecByID($request->kecamatan);
        if ($kecamatan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kecamatan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $kelurahan = $modelMember->getKelByID($request->kelurahan);
        if ($kelurahan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kelurahan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $data = (object) array(
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'provinsi' => $provinsi->nama,
            'kode_pos' => $request->kode_pos,
            'kota' => $kota->nama,
            'kecamatan' => $kecamatan->nama,
            'kelurahan' => $kelurahan->nama,
            'kode_daerah' => $request->kelurahan,
        );
        return view('member.ajax.confirm_add_profile')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getCekAddPackage($id_paket, $setuju)
    {
        $modePackage = new Package;
        $modelSettingPin = new Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $getDetailPackage = $modePackage->getPackageId($id_paket);
        return view('member.ajax.confirm_add_package')
            ->with('setuju', $setuju)
            ->with('getData', $getDetailPackage)
            ->with('pinSetting', $getActivePinSetting);
    }

    public function postCekAddPin(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $canInsert = $modelValidasi->getCheckAddPin($request, $dataUser);
        $disc = 0;
        //        if($request->total_pin >= 100){
        //            $disc = 0;
        //        }
        $modelSettingPin = new Pinsetting;
        $getActivePinSetting = $modelSettingPin->getActivePinSetting();
        $hargaAwal = $getActivePinSetting->price * $request->total_pin;
        $discAwal = $hargaAwal * $disc / 100;
        $harga = $hargaAwal - $discAwal;
        $data = (object) array(
            'total_pin' => $request->total_pin,
            'harga' => $harga,
            'disc' => $disc
        );
        return view('member.ajax.confirm_add_pin')
            ->with('check', $canInsert)
            ->with('disc', $disc)
            ->with('data', $data);
    }

    public function postCekAddTransaction(Request $request)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $modelTrans = new Transaction;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if (count($separate) == 2) {
            $cekType = $separate[0];
            $bankId = $separate[1];
            if ($cekType == 0) {
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($bankId);
            }
        }
        $getTrans = $modelTrans->getDetailTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans, 'sender' => $dataUser->tron);
        return view('member.ajax.confirm_add_transaction')
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('getTrans', $getTrans)
            ->with('cekType', $cekType)
            ->with('data', $data);
    }

    public function postCekAddTransactionTron(Request $request)
    {
        $dataUser = Auth::user();
        $modelTrans = new Transaction;
        $getTrans = $modelTrans->getDetailTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans, 'sender' => $request->sender);
        return view('member.ajax.confirm_add_transaction_tron')
            ->with('getTrans', $getTrans)
            ->with('data', $data);
    }

    public function postCekRejectTransaction(Request $request)
    {
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_reject_transaction')
            ->with('data', $data);
    }

    public function getCekConfirmOrderPackage(Request $request)
    {
        $dataUser = Auth::user();
        $data = (object) array('id_paket' => $request->id_paket);
        $modelPin = new Pin;
        $modelMemberPackage = new Memberpackage;
        $getData = $modelMemberPackage->getDetailMemberPackageInactive($request->id_paket, $dataUser);
        $sisaPin = $modelPin->getTotalPinMember($dataUser);
        $sum_pin_masuk = 0;
        $sum_pin_keluar = 0;
        if ($sisaPin->sum_pin_masuk != null) {
            $sum_pin_masuk = $sisaPin->sum_pin_masuk;
        }
        if ($sisaPin->sum_pin_keluar != null) {
            $sum_pin_keluar = $sisaPin->sum_pin_keluar;
        }
        $total = $sum_pin_masuk - $sum_pin_keluar;
        $totalPinOrder = $getData->total_pin;
        $lanjut = false;
        if ($total >= $totalPinOrder) {
            $lanjut = true;
        }
        return view('member.ajax.confirm_order')
            ->with('lanjut', $lanjut)
            ->with('data', $data);
    }

    public function getCekRejectOrderPackage(Request $request)
    {
        $data = (object) array('id_paket' => $request->id_paket);
        return view('member.ajax.reject_order')
            ->with('data', $data);
    }

    public function getCekAddBank(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBank = new Bank;
        $canInsert = $modelValidasi->getCheckAddBank($request);
        //        $getCek = $modelBank->getCheckNoRek($request->account_no, $request->bank_name);
        //        if($getCek > 0){
        //            $canInsert = (object) array('can' => false,  'pesan' => 'Identitas rekening bank sudah terpakai');
        //        }
        $data = (object) array(
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'account_name' => $dataUser->full_name
        );
        return view('member.ajax.confirm_add_bank')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getActivateBank($id)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $getCek = $modelBank->getBankMemberID($id, $dataUser);
        return view('member.ajax.confirm_activate_bank')
            ->with('getData', $getCek)
            ->with('dataUser', $dataUser);
    }

    public function getCekTransferPin(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelPin = new Pin;
        $modelMember = new Member;
        $canInsert = $modelValidasi->getCheckPengiriman($request);
        $cekPin = $modelPin->getTotalPinMember($dataUser);
        if ($request->total_pin == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak mengisi jumlah pin');
            return view('member.ajax.confirm_transfer_pin')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        if ($request->to_id == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak mengisi data penerima Pin');
            return view('member.ajax.confirm_transfer_pin')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $sisaPin = $cekPin->sum_pin_masuk - $cekPin->sum_pin_keluar;
        if ($sisaPin < $request->total_pin) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin anda tidak cukup');
        }
        if (($sisaPin - $request->total_pin) < 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pin anda tidak cukup');
        }
        $cekMember = $modelMember->getUsers('id', $request->to_id);
        $data = (object) array(
            'total_pin' => $request->total_pin,
            'id' => $cekMember->id,
            'name' => $cekMember->name,
            'user_code' => $cekMember->user_code,
            'email' => $cekMember->email,
            'hp' => $cekMember->hp
        );
        return view('member.ajax.confirm_transfer_pin')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getCekUpgrade($id_paket)
    {
        $dataUser = Auth::user();
        $modePackage = new Package;
        $modelPin = new Pin;
        $getDetailPackage = $modePackage->getPackageId($id_paket);
        $getMyPackage = $modePackage->getMyPackage($dataUser);
        $total_sisa_pin = $getDetailPackage->pin - $getMyPackage->pin;
        $cekPin = $modelPin->getTotalPinMember($dataUser);
        $sisaPin = $cekPin->sum_pin_masuk - $cekPin->sum_pin_keluar;
        $dataCek = (object) array(
            'total_sisa_pin' => $total_sisa_pin,
            'sisa_pin' => $sisaPin
        );
        $modelValidasi = new Validation;
        $canInsert = $modelValidasi->getCekPinForUpgrade($dataCek);
        return view('member.ajax.confirm_upgrade_package')
            ->with('canInsert', $canInsert)
            ->with('total_pin', $total_sisa_pin)
            ->with('dataPackage', $getDetailPackage)
            ->with('dataMyPackage', $getMyPackage);
    }

    public function getCekPlacementKiriKanan($id, $type)
    {
        $posisi = 'kanan_id';
        if ($type == 1) {
            $posisi = 'kiri_id';
        }
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $dataUser = Auth::user();
        $modelMember = new Member;
        $getUplineId = $dataUser;
        if ($id != $dataUser->id) {
            $getUplineId = $modelMember->getUsers('id', $id);
        }
        if ($getUplineId->$posisi != null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Posisi placement yang anda pilih telah terisi, pilih posisi yang lain');
        }
        $getDataDataCalon = $modelMember->getAllMemberToPlacement($dataUser);
        $jml = count($getDataDataCalon);
        if ($jml == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Tidak ada data member yang akan di placement');
        }
        return view('member.ajax.confirm_add_placement')
            ->with('dataCalon', $getDataDataCalon)
            ->with('check', $canInsert)
            ->with('upline_id', $getUplineId->id)
            ->with('type', $type)
            ->with('dataUser', $dataUser);
    }

    public function getSearchUserCode(Request $request)
    {
        $dataUser =  Auth::user();
        $modelMember = new Member;
        $downline = $dataUser->upline_detail . ',[' . $dataUser->id . ']';
        if ($dataUser->upline_detail == null) {
            $downline = '[' . $dataUser->id . ']';
        }
        $getDownlineUsername = null;
        if ($request->name != null) {
            $getDownlineUsername = $modelMember->getMyDownlineUsername($downline, $request->name);
        }
        //        dd($getDownlineUsername);
        return view('member.ajax.get_name_autocomplete')
            ->with('getData', $getDownlineUsername)
            ->with('dataUser', $dataUser);
    }

    public function getExplorerMemberByUserCode(Request $request)
    {
        $dataUser =  Auth::user();
        $modelMember = new Member;
        $getUsername = null;
        if ($request->name != null) {
            if (strlen($request->name) >= 3) {
                $getUsername = $modelMember->getExplorerUsername($dataUser->id, $request->name);
            }
        }
        return view('member.ajax.get_explore_user')
            ->with('getData', $getUsername)
            ->with('dataUser', $dataUser);
    }

    public function getCekRO(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelPin = new Pin;
        $getTotalPin = $modelPin->getTotalPinMember($dataUser);
        $canInsert = $modelValidasi->getCheckRO($request, $getTotalPin, $dataUser);
        $data = (object) array(
            'total_pin' => $request->total_pin
        );
        return view('member.ajax.confirm_add_ro')
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function getCekConfirmWD(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelBank = new Bank;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
        $getMyActiveBank = $modelBank->getBankMemberActive($dataUser);
        $id_bank = null;
        if ($getMyActiveBank != null) {
            $id_bank = $getMyActiveBank->id;
        }
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin + $totalWDeIDR->total_wd + $totalWDeIDR->total_tunda + $totalWDeIDR->total_fee_admin)),
            'admin_fee' => 6500,
            'bank' => $id_bank
        );
        $canInsert = $modelValidasi->getCheckWD($dataAll);
        return view('member.ajax.confirm_add_wd')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getCekConfirmWDRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelBank = new Bank;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonusRoyalti($dataUser);
        $totalWD = $modelWD->getTotalDiTransferRoyalti($dataUser);
        $getMyActiveBank = $modelBank->getBankMemberActive($dataUser);
        $id_bank = null;
        if ($getMyActiveBank != null) {
            $id_bank = $getMyActiveBank->id;
        }
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin)),
            'admin_fee' => 6500,
            'bank' => $id_bank
        );
        $canInsert = $modelValidasi->getCheckWD($dataAll);
        return view('member.ajax.confirm_add_wd_royalti')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getCekConfirmWDeIDR(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonus($dataUser);
        $totalWD = $modelWD->getTotalDiTransfer($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'total_wd_eidr' => $totalWDeIDR->total_wd,
            'total_tunda_eidr' => $totalWDeIDR->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin + $totalWDeIDR->total_wd + $totalWDeIDR->total_tunda + $totalWDeIDR->total_fee_admin)),
            'admin_fee' => 3000,
            'tron' => $dataUser->tron
        );
        $canInsert = $modelValidasi->getCheckWDeIDR($dataAll);
        return view('member.ajax.confirm_add_wdeidr')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getCekConfirmWDRoyaltieIDR(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $totalBonus = $request->input_jml_wd; //$modelBonus->getTotalBonus($dataUser);
        $totalBonusAll = $modelBonus->getTotalBonusRoyalti($dataUser);
        $totalWD = $modelWD->getTotalDiTransferRoyalti($dataUser);
        $totalWDeIDR = $modelWD->getTotalDiTransferRoyaltieIDR($dataUser);
        $dataAll = (object) array(
            'req_wd' => (int) $totalBonus,
            'total_bonus' => $totalBonus,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'total_wd_eidr' => $totalWDeIDR->total_wd,
            'total_tunda_eidr' => $totalWDeIDR->total_tunda,
            'saldo' => (int) ($totalBonusAll->total_bonus - ($totalWD->total_wd + $totalWD->total_tunda + $totalWD->total_fee_admin + $totalWDeIDR->total_wd + $totalWDeIDR->total_tunda + $totalWDeIDR->total_fee_admin)),
            'admin_fee' => 3000,
            'tron' => $dataUser->tron
        );
        $canInsert = $modelValidasi->getCheckWDeIDR($dataAll);
        return view('member.ajax.confirm_add_wd_royalti_eidr')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getCekAddTron(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $modelMember = new Member;
        $data = (object) array(
            'tron' => $request->tron
        );
        if ($request->tron == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat TRON harus diisi');
            return view('member.ajax.confirm_add_tron')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (strpos($request->tron, ' ') !== false) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat TRON tidak boleh ada spasi');
            return view('member.ajax.confirm_add_tron')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (strlen($request->tron) != 34) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat TRON harus 34 karakter');
            return view('member.ajax.confirm_add_tron')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        return view('member.ajax.confirm_add_tron')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getSearchByType($type, Request $request)
    {
        $modelMember = new Member;
        $getData = null;
        if ($type == 'kota') {
            if ($request->provinsi != 0) {
                $getData = $modelMember->getKabupatenKotaByPropinsi($request->provinsi);
            }
        }
        if ($type == 'kecamatan') {
            if ($request->kota != 0) {
                $dataKec = explode('.', $request->kota);
                $getData = $modelMember->getKecamatanByKabupatenKota($dataKec[0], $dataKec[1]);
            }
        }
        if ($type == 'kelurahan') {
            if ($request->kecamatan != 0) {
                $dataKel = explode('.', $request->kecamatan);
                $getData = $modelMember->getKelurahanByKecamatan($dataKel[0], $dataKel[1], $dataKel[2]);
            }
        }
        return view('member.ajax.searchDaerah')
            ->with('type', $type)
            ->with('getData', $getData);
    }

    public function getSearchByTypeNew($type, Request $request)
    {
        $modelMember = new Member;
        $getData = null;
        if ($type == 'kota') {
            if ($request->provinsi != 0) {
                $getData = $modelMember->getKabupatenKotaByPropinsiNew($request->provinsi);
            }
        }
        if ($type == 'kecamatan') {
            if ($request->kota != 0) {
                $getData = $modelMember->getKecamatanByKabupatenKotaNew($request->kota);
            }
        }
        if ($type == 'kelurahan') {
            if ($request->kecamatan != 0) {
                $getData = $modelMember->getKelurahanByKecamatanNew($request->kecamatan);
            }
        }
        return view('member.ajax.searchDaerah')
            ->with('type', $type)
            ->with('getData', $getData);
    }

    public function getCekRequestMemberStockist(Request $request)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $modelValidasi = new Validation;

        $cekHU2 = null;
        if ($request->hu2 != null) {
            $getHU2 = $modelMember->getCekHakUsaha($dataUser, $request->hu2);
            if ($getHU2 != null) {
                $cekHU2 = $getHU2->id;
            }
        }
        $cekHU3 = null;
        if ($request->hu3 != null) {
            $getHU3 = $modelMember->getCekHakUsaha($dataUser, $request->hu3);
            if ($getHU3 != null) {
                $cekHU3 = $getHU3->id;
            }
        }
        $dataAll = (object) array(
            'syarat1' => $request->syarat1,
            'syarat3' => $request->syarat3,
            'syarat4' => $request->syarat4,
            'hu2' => $cekHU2,
            'hu3' => $cekHU3,
            'total_sp' => $dataUser->total_sponsor,
            'alamat' => $dataUser
        );
        $canInsert = $modelValidasi->getCheckRequestStockist($dataAll);
        return view('member.ajax.confirm_request_stockistr')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getStockistCekSoping(Request $request)
    {
        $dataUser = Auth::user();
        $idPurchase = $request->id_barang;
        $quantity = $request->total_buy;
        $modelSales = new Sales;
        $modelMember = new Member;
        $getData = $modelSales->getDetailPurchase($idPurchase);
        return view('member.ajax.confirm_buy_barang')
            ->with('getData', $getData)
            ->with('qty', $quantity);
    }

    public function getCekSoping(Request $request)
    {
        $dataUser = Auth::user();
        $idPurchase = $request->id_barang;
        $quantity = $request->total_buy;
        $stokist_id = $request->stokist_id;
        $modelSales = new Sales;
        $modelMember = new Member;
        $getData = $modelSales->getDetailPurchase($idPurchase);
        return view('member.ajax.confirm_m_buy_barang')
            ->with('getData', $getData)
            ->with('stokist_id', $stokist_id)
            ->with('qty', $quantity);
    }

    public function getCekEditAddress(Request $request)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->alamat == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Alamat harus diisi');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $provinsi = $modelMember->getProvinsiByID($request->provinsi);
        if ($provinsi == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Provinsi harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $kota = $modelMember->getKabByID($request->kota);
        if ($kota == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kabupaten/Kota harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $kecamatan = $modelMember->getKecByID($request->kecamatan);
        if ($kecamatan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kecamatan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $kelurahan = $modelMember->getKelByID($request->kelurahan);
        if ($kelurahan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kelurahan harus dipilih');
            return view('member.ajax.confirm_add_profile')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $data = (object) array(
            'alamat' => $request->alamat,
            'provinsi' => $provinsi->nama,
            'kota' => $kota->nama,
            'kecamatan' => $kecamatan->nama,
            'kelurahan' => $kelurahan->nama,
            'kode_daerah' => $request->kelurahan,
        );
        return view('member.ajax.confirm_edit_address')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getCekConfirmClaimReward(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBonusSetting = new Bonussetting;
        $modelWD = new Transferwd;
        $getData = $modelBonusSetting->getActiveBonusRewardByID($request->reward_id);
        $dataAll = (object) array(
            'reward_detail' => $getData->reward_detail,
            'reward_id' => $getData->id
        );
        return view('member.ajax.confirm_reward_detail')
            ->with('data', $dataAll);
    }

    public function getCekMemberPembayaran(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $modelMember = new Member;
        $modelBank = new Bank;
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranMasterSales($request->sale_id);
        $getStockist = $modelMember->getUsers('id', $getDataMaster->stockist_id);
        if ($request->buy_metode == 1) {
            $buy_metode = 1;
        }
        if ($request->buy_metode == 2) {
            $buy_metode = 2;
            $getStockistBank = $modelBank->getBankMemberActive($getStockist);
            $bank_name = $getStockistBank->bank_name;
            $account_no = $getStockistBank->account_no;
            $account_name = $getStockistBank->account_name;
        }
        if ($request->buy_metode == 3) {
            $buy_metode = 3;
            $tron = $getStockist->tron;
        }
        $dataAll = (object) array(
            'buy_metode' => $buy_metode,
            'getDataMaster' => $getDataMaster,
            'getStockist' => $getStockist,
            'tron' => $tron,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
        );
        return view('member.ajax.confirm_member_pembayaran')
            ->with('data', $dataAll);
    }

    public function postCekAddRequestStock(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert =  (object) array('can' => true, 'pesan' => '');
        if ($request->metode == 'undefined') {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih metode pembayaran');
            return view('member.ajax.confirm_add_stock')
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        if ($request->metode == 2) {
            $bank_name = 'BRI';
            $account_no = '033601001795562';
            $account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if ($request->metode == 3) {
            $tron = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        }
        $data = (object) array(
            'id_master' => $request->id_master,
            'royalti' => $request->royalti,
            'buy_metode' => $request->metode,
            'sender' => $dataUser->tron, //user default tron address
            'timestamp' => $request->timestamp,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
            'tron' => $tron
        );
        if ($request->metode == 4) {
            $tron = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
            $data = (object) array(
                'id_master' => $request->id_master,
                'royalti' => $request->royalti,
                'buy_metode' => $request->metode,
                'sender' => $request->sender,
                'timestamp' => $request->timestamp,
                'bank_name' => $bank_name,
                'account_no' => $account_no,
                'account_name' => $account_name,
                'tron' => $tron
            );
            return view('member.ajax.confirm_add_stock_tron')
                ->with('check', $canInsert)
                ->with('data', $data);
        }

        return view('member.ajax.confirm_add_stock')
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function postCekRejectRequestStock(Request $request)
    {
        $data = (object) array('id_master' => $request->id_master);
        return view('member.ajax.confirm_reject_stock')
            ->with('data', $data);
    }

    public function postCekAddRoyalti(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $royalti_metode = $request->metode;
        if ($royalti_metode == 'undefined') {
            $canInsert = (object) array('can' => false, 'pesan' => 'Metode transfer royalti belum diipih');
            return view('member.ajax.confirm_add_royalti')
                ->with('dataRequest', null)
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id_master, $dataUser->id);
        $royalti_tron = null;
        $royalti_bank_name = null;
        $royalti_account_no = null;
        $royalti_account_name = null;
        if ($royalti_metode == 1) {
            $royalti_bank_name = 'BRI';
            $royalti_account_no = '033601001795562';
            $royalti_account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if ($royalti_metode == 2) {
            $royalti_tron = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        }
        $data = (object) array(
            'id_master' => $id_master,
            'royalti_metode' => $royalti_metode,
            'royalti_bank_name' => $royalti_bank_name,
            'royalti_account_no' => $royalti_account_no,
            'royalti_account_name' => $royalti_account_name,
            'royalti_tron' => $royalti_tron
        );
        return view('member.ajax.confirm_add_royalti')
            ->with('getDataSales', $getDataSales)
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function postCekConfirmPembelian(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.confirm_confirm_pembelian')
            ->with('getDataSales', $getDataSales)
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function postCekRejectPembelian(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesStockistDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.reject_pembelian')
            ->with('getDataSales', $getDataSales)
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function getCekConfirmBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($dataUser->is_tron == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_belanja')
                ->with('data', null)
                ->with('check', $canInsert);
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberMasterSalesMonthYear($dataUser->id, $request->m, $request->y);
        return view('member.ajax.confirm_reward_belanja')
            ->with('data', $getData)
            ->with('dataUser', $dataUser)
            ->with('check', $canInsert);
    }

    public function getCekConfirmPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($dataUser->is_tron == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_penjualan')
                ->with('data', null)
                ->with('check', $canInsert);
        }
        $modelSales = new Sales;
        $getData = $modelSales->getStockistPenjualanMonthYear($dataUser->id, $request->m, $request->y);
        return view('member.ajax.confirm_reward_penjualan')
            ->with('data', $getData)
            ->with('dataUser', $dataUser)
            ->with('check', $canInsert);
    }

    public function getSearchUserCodeStockist(Request $request)
    {
        $dataUser =  Auth::user();
        $modelMember = new Member;
        $getDownlineUsername = null;
        if ($request->name != null) {
            $getDownlineUsername = $modelMember->getMyDownlineUsernameStockist($request->name);
        }
        return view('member.ajax.get_name_autocomplete')
            ->with('getData', $getDownlineUsername)
            ->with('dataUser', $dataUser);
    }

    public function getSearchProductImage(Request $request)
    {
        $modelProduct = new Product;
        $getProductImage = null;
        if ($request->name != null) {
            $getProductImage = $modelProduct->getProductImage($request->name);
        }
        return view('member.ajax.get_image_autocomplete')
            ->with('productImages', $getProductImage);
    }
    public function getSearchProductImageEdit(Request $request)
    {
        $modelProduct = new Product;
        $getProductImage = null;
        if ($request->name != null) {
            $getProductImage = $modelProduct->getProductImage($request->name);
        }
        return view('member.ajax.get_image_autocomplete_edit')
            ->with('productImages', $getProductImage);
    }

    public function getEditProduct($product_id)
    {
        $dataUser = Auth::user();
        $type = 1;
        if ($dataUser->is_vendor == 1) {
            $type = 2;
        }
        $getEditProduct = null;
        if ($product_id != null) {
            $getEditProduct = Product::select('id', 'seller_id', 'name', 'size', 'price', 'desc', 'qty', 'category_id', 'image', 'is_active')
                ->where('seller_id', $dataUser->id)
                ->where('type', $type)
                ->where('id', $product_id)
                ->with('category:id,name')
                ->first();
        }
        $getCategories = Category::select('id', 'name')->where('id', '<', 21)->get();
        return view('member.ajax.get_edit_product')
            ->with('categories', $getCategories)
            ->with('product', $getEditProduct);
    }

    public function getCekConfirmTopUp(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $totalTopUp = $request->input_jml_topup;

        $dataAll = (object) array(
            'req_topup' => (int) $totalTopUp,
            'tron' => $dataUser->tron
        );
        $canInsert = $modelValidasi->getCheckTopUp($dataAll);
        return view('member.ajax.confirm_add_topup')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getCekTopUpStatus(Request $request)
    {

        $modelBonus = new Bonus;

        $dataTopup = $modelBonus->getTopUpSaldoID($request->id_topup);

        return $dataTopup->status;
    }

    public function getCheckPPOBStatus(Request $request)
    {
        $dataUser = Auth::user();
        $modelPin = new Pin;

        $getDataMaster = $modelPin->getMemberPembayaranPPOB($request->masterSalesID, $dataUser);

        return $getDataMaster->status;
    }

    public function getCekTopupTransaction(Request $request)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $modelBonus = new Bonus;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if (count($separate) == 2) {
            $cekType = $separate[0];
            $bankId = $separate[1];
            $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
        }
        $getTrans = $modelBonus->getTopUpSaldoID($request->id_topup, $dataUser);
        $data = (object) array('id_topup' => $request->id_topup);
        return view('member.ajax.confirm_topup_transaction')
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('getTrans', $getTrans)
            ->with('cekType', $cekType)
            ->with('data', $data);
    }

    public function getCekRejectTopup(Request $request)
    {
        $data = (object) array('id_topup' => $request->id_topup);
        return view('member.ajax.confirm_reject_topup')
            ->with('data', $data);
    }

    public function getCekEditPassword(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->old_password == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password Lama harus diisii');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        $cekOld = false;
        if (Hash::check($request->old_password, $dataUser->password)) {
            $cekOld = true;
        }
        if ($cekOld == false) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password lama tidak sama');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        $ucl = preg_match('/[A-Z]/', $request->password); // Uppercase Letter
        $lcl = preg_match('/[a-z]/', $request->password); // Lowercase Letter
        $dig = preg_match('/\d/', $request->password); // Numeral
        if ($request->password == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password harus diisii');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (strpos($request->repassword, ' ') !== false) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Ketik ulang password harus diisi');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if ($request->password != $request->repassword) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password tidak sama');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (strlen($request->password) < 6) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password terlalu pendek, minimal 6 karakter');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (!$ucl || !$lcl || !$dig) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Password harus mengandung minimal 1 Huruf Besar, 1 Huruf Kecil dan 1 Angka. (Contoh: iniP4sswoRdku777)');
            return view('member.ajax.confirm_edit_password')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        $data = (object) array(
            'password' => $request->password
        );
        return view('member.ajax.confirm_edit_password')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getCek2FA(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->type == 1) {
            //Change pin
            if ($request->old_password == null) {
                $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin Lama harus diisi');
                return view('member.ajax.confirm_edit_2fa')
                    ->with('dataRequest', null)
                    ->with('check', $canInsert);
            }

            $cekOld = false;
            if (Hash::check($request->old_password, $dataUser->{'2fa'})) {
                $cekOld = true;
            }
            if ($cekOld == false) {
                $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin lama tidak benar');
                return view('member.ajax.confirm_edit_2fa')
                    ->with('dataRequest', null)
                    ->with('check', $canInsert);
            }
        }

        if ($request->password == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin harus diisii');
            return view('member.ajax.confirm_edit_2fa')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (strpos($request->repassword, ' ') !== false) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Ketik ulang Kode Pin harus diisi');
            return view('member.ajax.confirm_edit_2fa')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if ($request->password != $request->repassword) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin tidak sama');
            return view('member.ajax.confirm_edit_2fa')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (strlen($request->password) < 4) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin terlalu pendek, minimal 4 digit');
            return view('member.ajax.confirm_edit_2fa')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        if (!is_numeric($request->password)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin hanya menggunakan Angka saja');
            return view('member.ajax.confirm_edit_2fa')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }

        $shame = '1111 1234 12345 123456 1234567 123123 1212 654321 4321 7777 8888 9999 0000';
        if (strpos($shame, $request->password) !== false) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Kode Pin yang anda buat TERLALU MUDAH DITEBAK');
            return view('member.ajax.confirm_edit_2fa')
                ->with('dataRequest', null)
                ->with('check', $canInsert);
        }
        $data = (object) array(
            'password' => $request->password
        );
        return view('member.ajax.confirm_edit_2fa')
            ->with('dataRequest', $data)
            ->with('check', $canInsert)
            ->with('dataUser', $dataUser);
    }

    public function getCekRequestMemberVendor(Request $request)
    {
        $dataUser = Auth::user();
        $modelMember = new Member;
        $modelValidasi = new Validation;

        $cekHU1 = null;
        if ($request->hu1 != null) {
            $getHU1 = $modelMember->getCekMemberExist($request->hu1);
            if ($getHU1 != null) {
                $cekHU1 = $getHU1->id;
            }
        }
        $cekHU2 = null;
        if ($request->hu2 != null) {
            $getHU2 = $modelMember->getCekMemberExist($request->hu2);
            if ($getHU2 != null) {
                $cekHU2 = $getHU2->id;
            }
        }
        $cekHU3 = null;
        if ($request->hu3 != null) {
            $getHU3 = $modelMember->getCekMemberExist($request->hu3);
            if ($getHU3 != null) {
                $cekHU3 = $getHU3->id;
            }
        }
        $cekHU4 = null;
        if ($request->hu4 != null) {
            $getHU4 = $modelMember->getCekMemberExist($request->hu4);
            if ($getHU4 != null) {
                $cekHU4 = $getHU4->id;
            }
        }
        $cekHU5 = null;
        if ($request->hu5 != null) {
            $getHU5 = $modelMember->getCekMemberExist($request->hu5);
            if ($getHU5 != null) {
                $cekHU5 = $getHU5->id;
            }
        }
        $hash = 0;
        if ($request->hash != null) {
            $hash = $request->hash;
        }
        $dataAll = (object) array(
            'syarat1' => $request->syarat1,
            'syarat3' => $request->syarat3,
            'syarat4' => $request->syarat4,
            'syarat5' => $request->syarat5,
            'hu1' => $cekHU1,
            'hu2' => $cekHU2,
            'hu3' => $cekHU3,
            'hu4' => $cekHU4,
            'hu5' => $cekHU5,
            'hash' => $hash,
            'total_sp' => $dataUser->total_sponsor,
            'alamat' => $dataUser
        );
        $canInsert = $modelValidasi->getCheckRequestVendor($dataAll);
        return view('member.ajax.confirm_request_vendor')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function getSearchUserCodeVendor(Request $request)
    {
        $dataUser =  Auth::user();
        $modelMember = new Member;
        $getDownlineUsername = null;
        if ($request->name != null) {
            $getDownlineUsername = $modelMember->getMyDownlineUsernameVendor($request->name);
        }
        return view('member.ajax.get_name_autocomplete')
            ->with('getData', $getDownlineUsername)
            ->with('dataUser', $dataUser);
    }

    public function postCekAddRequestVStock(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert =  (object) array('can' => true, 'pesan' => '');
        if ($request->metode == 'undefined') {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih metode pembayaran');
            return view('member.ajax.confirm_add_vstock')
                ->with('check', $canInsert)
                ->with('dataUser', $dataUser);
        }
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        if ($request->metode == 2) {
            $bank_name = 'BRI';
            $account_no = '033601001795562';
            $account_name = 'PT LUMBUNG MOMENTUM BANGSA';
        }
        if ($request->metode == 3 || $request->metode == 4) {
            $tron = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        }
        $data = (object) array(
            'id_master' => $request->id_master,
            'royalti' => $request->royalti,
            'buy_metode' => $request->metode,
            'sender' => $dataUser->tron, //user default tron address
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
            'tron' => $tron
        );
        if ($request->metode == 4) {
            $data = (object) array(
                'id_master' => $request->id_master,
                'royalti' => $request->royalti,
                'buy_metode' => $request->metode,
                'sender' => $request->sender,
                'bank_name' => $bank_name,
                'account_no' => $account_no,
                'account_name' => $account_name,
                'tron' => $tron
            );
            return view('member.ajax.confirm_add_vstock_tron')
                ->with('check', $canInsert)
                ->with('data', $data);
        }
        return view('member.ajax.confirm_add_vstock')
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function postCekRejectRequestVStock(Request $request)
    {
        $data = (object) array('id_master' => $request->id_master);
        return view('member.ajax.confirm_reject_vstock')
            ->with('data', $data);
    }

    public function getCekMemberVPembayaran(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $modelMember = new Member;
        $modelBank = new Bank;
        $tron = null;
        $bank_name = null;
        $account_no = null;
        $account_name = null;
        $getStockistBank = null;
        $buy_metode = 0;
        $getDataMaster = $modelSales->getMemberPembayaranVMasterSales($request->sale_id);
        $getStockist = $modelMember->getUsers('id', $getDataMaster->vendor_id);
        if ($request->buy_metode == 1) {
            $buy_metode = 1;
        }
        if ($request->buy_metode == 2) {
            $buy_metode = 2;
            $getStockistBank = $modelBank->getBankMemberActive($getStockist);
            $bank_name = $getStockistBank->bank_name;
            $account_no = $getStockistBank->account_no;
            $account_name = $getStockistBank->account_name;
        }
        if ($request->buy_metode == 3) {
            $buy_metode = 3;
            $tron = $getStockist->tron;
        }
        $dataAll = (object) array(
            'buy_metode' => $buy_metode,
            'getDataMaster' => $getDataMaster,
            'getStockist' => $getStockist,
            'tron' => $tron,
            'bank_name' => $bank_name,
            'account_no' => $account_no,
            'account_name' => $account_name,
        );
        return view('member.ajax.confirm_member_vpembayaran')
            ->with('data', $dataAll);
    }

    public function postCekConfirmVPembelian(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesVendorDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.confirm_confirm_vpembelian')
            ->with('getDataSales', $getDataSales)
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function postCekRejectVPembelian(Request $request)
    {
        $dataUser = Auth::user();
        $modelSales = new Sales;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $id_master = $request->id_master;
        $getDataSales = $modelSales->getMemberReportSalesVendorDetail($id_master, $dataUser->id);
        $data = (object) array(
            'id_master' => $id_master
        );
        return view('member.ajax.reject_vpembelian')
            ->with('getDataSales', $getDataSales)
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function getCekConfirmVPenjualanReward(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($dataUser->is_tron == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_vpenjualan')
                ->with('data', null)
                ->with('check', $canInsert);
        }
        $modelSales = new Sales;
        $getData = $modelSales->getVendorPenjualanMonthYear($dataUser->id, $request->m, $request->y);
        return view('member.ajax.confirm_reward_vpenjualan')
            ->with('data', $getData)
            ->with('check', $canInsert);
    }

    public function getCekConfirmVBelanjaReward(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($dataUser->is_tron == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum mengisi data alamat tron');
            return view('member.ajax.confirm_reward_vbelanja')
                ->with('data', null)
                ->with('check', $canInsert);
        }
        $modelSales = new Sales;
        $getData = $modelSales->getMemberVMasterSalesMonthYear($dataUser->id, $request->m, $request->y);
        return view('member.ajax.confirm_reward_vbelanja')
            ->with('data', $getData)
            ->with('check', $canInsert);
    }

    public function postCekAddDeposit(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $canInsert = $modelValidasi->getCheckAddDeposit($request, $dataUser);
        $data = (object) array(
            'total_deposit' => $request->total_deposit
        );
        return view('member.ajax.confirm_add_deposit')
            ->with('check', $canInsert)
            ->with('data', $data);
    }

    public function postCekAddDepositTransaction(Request $request)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $modelTrans = new Transaction;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if (count($separate) == 2) {
            $cekType = $separate[0];
            $bankId = $separate[1];
            if ($cekType == 0) {
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($bankId);
            }
        }
        $getTrans = $modelTrans->getDetailDepositTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans, 'sender' => $dataUser->tron);
        return view('member.ajax.confirm_add_deposit_transaction')
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('getTrans', $getTrans)
            ->with('cekType', $cekType)
            ->with('data', $data);
    }

    public function postCekAddDepositTransactionTron(Request $request)
    {
        $dataUser = Auth::user();
        $modelTrans = new Transaction;
        $getTrans = $modelTrans->getDetailDepositTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans, 'sender' => $request->sender);
        return view('member.ajax.confirm_add_deposit_transaction_tron')
            ->with('getTrans', $getTrans)
            ->with('data', $data);
    }

    public function postCekRejectDepositTransaction(Request $request)
    {
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_reject_deposit_transaction')
            ->with('data', $data);
    }

    public function postCekTarikDeposit(Request $request)
    {
        $dataUser = Auth::user();
        $canInsert = (object) array('can' => true, 'pesan' => '');
        $separate = explode('_', $request->id_bank);
        if (count($separate) != 2) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Pilih metode penarikan');
            return view('member.ajax.confirm_tarik_deposit')
                ->with('check', $canInsert)
                ->with('data', null);
        }
        if (!is_numeric($request->total_deposit)) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nominal harus dalam angka');
            return view('member.ajax.confirm_tarik_deposit')
                ->with('check', $canInsert)
                ->with('data', null);
        }
        if ($request->total_deposit <= 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Nominal harus diatas 0');
            return view('member.ajax.confirm_tarik_deposit')
                ->with('check', $canInsert)
                ->with('data', null);
        }
        $modelBank = new Bank;
        $cekType = $separate[0];
        $bankId = $separate[1];
        if ($cekType == 0) {
            $getUserBank = $modelBank->getBankMemberID($bankId, $dataUser);
        } else {
            $getUserBank = $dataUser;
        }
        $modelPin = new Pin;
        $modelTrans = new Transaction;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataUser);
        $dataDeposit = $modelPin->getTotalDepositMember($dataUser);
        $sum_deposit_masuk = 0;
        $sum_deposit_keluar = 0;
        if ($dataDeposit->sum_deposit_masuk != null) {
            $sum_deposit_masuk = $dataDeposit->sum_deposit_masuk;
        }
        if ($getTransTarik->deposit_keluar != null) {
            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar;
        if ($totalDeposit <  $request->total_deposit) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak bisa tarik deposit. Saldo deposit kosong atau kurang');
            return view('member.ajax.confirm_tarik_deposit')
                ->with('check', $canInsert)
                ->with('data', null);
        }
        $data = (object) array(
            'total_deposit' => $request->total_deposit
        );
        return view('member.ajax.confirm_tarik_deposit')
            ->with('check', $canInsert)
            ->with('metode', $cekType)
            ->with('getUserBank', $getUserBank)
            ->with('data', $data);
    }

    public function postCekAddTarikTransaction(Request $request)
    {
        $dataUser = Auth::user();
        $modelBank = new Bank;
        $modelTrans = new Transaction;
        $separate = explode('_', $request->id_bank);
        $getPerusahaanBank = null;
        $cekType = null;
        if (count($separate) == 2) {
            $cekType = $separate[0];
            $bankId = $separate[1];
            if ($cekType == 0) {
                $getPerusahaanBank = $modelBank->getBankPerusahaanID($bankId);
            } else {
                $getPerusahaanBank = $modelBank->getTronPerusahaanID($bankId);
            }
        }
        $getTrans = $modelTrans->getDetailDepositTransactionsMember($request->id_trans, $dataUser);
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_add_tarik_transaction')
            ->with('bankPerusahaan', $getPerusahaanBank)
            ->with('getTrans', $getTrans)
            ->with('cekType', $cekType)
            ->with('data', $data);
    }

    public function postCekRejectTarikTransaction(Request $request)
    {
        $data = (object) array('id_trans' => $request->id_trans);
        return view('member.ajax.confirm_reject_tarik_transaction')
            ->with('data', $data);
    }

    public function getCekPOBX(Request $request)
    {
        $dataUser = Auth::user();
        $modelValidasi = new Validation;
        $modelBonus = new Bonus;
        $modelWD = new Transferwd;
        $modelMember = new Member;
        $canInsert = (object) array('can' => true, 'pesan' => '');
        if ($request->jaringan == null) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda belum memilih jaringan');
            return view('member.ajax.confirm_add_pulsa')
                ->with('check', $canInsert)
                ->with('data', null);
        }
        $jaringan = $request->jaringan;
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $sign = md5($username . $apiKey . 'pl');
        $json = '{
                    "commands" : "pricelist",
                    "username" : "' . $username . '",
                    "sign"     : "' . $sign . '",
                    "status" : "active"
            }';
        $url = $getDataAPI->master_url . '/v1/legacy/index/pulsa/' . $jaringan;
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);
        $totalBonusAll = $modelBonus->getTotalBonusSponsor($dataUser);
        if ($totalBonusAll == 0) {
            $canInsert = (object) array('can' => false, 'pesan' => 'Anda tidak memiliki bonus Sponsor');
            return view('member.ajax.confirm_add_pulsa')
                ->with('check', $canInsert)
                ->with('data', null);
        }
        $totalWD = $modelWD->getTotalDiTransferByType($dataUser, 1);
        $dataAll = (object) array(
            'total_bonus_get' => $totalBonusAll,
            'total_wd' => $totalWD->total_wd,
            'total_tunda' => $totalWD->total_tunda,
            'saldo' => (int) ($totalBonusAll - ($totalWD->total_wd + $totalWD->total_tunda)),
            'wd_fee' => $totalWD->total_wd_admin_fee,
            'wd_tunda_fee' => $totalWD->total_tunda_admin_fee,
            'jaringan' => $jaringan,
            //'data_jaringan' => $arrayData['data']
            'data_jaringan' => array(
                array(
                    "pulsa_code" => "htelkomsel1000",
                    "pulsa_op" => "Telkomsel",
                    "pulsa_nominal" => "1000",
                    "pulsa_price" => 1900,
                    "pulsa_type" => "pulsa",
                    "masaaktif" => "3",
                    "status" => "active"
                ),
                array(
                    "pulsa_code" => "htelkomsel10000",
                    "pulsa_op" => "Telkomsel",
                    "pulsa_nominal" => "10000",
                    "pulsa_price" => 10900,
                    "pulsa_type" => "pulsa",
                    "masaaktif" => "15",
                    "status" => "active",
                ),
                array(
                    "pulsa_code" => "htelkomsel20000",
                    "pulsa_op" => "Telkomsel",
                    "pulsa_nominal" => "20000",
                    "pulsa_price" => 20500,
                    "pulsa_type" => "pulsa",
                    "masaaktif" => "30",
                    "status" => "active",
                )
            )
        );
        return view('member.ajax.confirm_add_pulsa')
            ->with('check', $canInsert)
            ->with('data', $dataAll);
    }

    public function postCekBuyPPOBHP(Request $request)
    {
        //cek validasi lg
        //cek vendor punya deposit ga
        $type = $request->type;
        $no_hp = $request->no_hp;
        if ($no_hp == null) {
            return view('member.ajax.confirm_cek_ppob')
                ->with('data', null)
                ->with('message', 'Anda belum memasukan nomor HP')
                ->with('dataVendor', null);
        }

        if ($type < 3) {
            if (substr($no_hp, 0, 2) != '08') {
                return view('member.ajax.confirm_cek_ppob')
                    ->with('data', null)
                    ->with('message', 'No HP harus berawalan 08...')
                    ->with('dataVendor', null);
            }
        }

        $buy_method = $request->type_pay;
        if ($buy_method == 'undefined') {
            return view('member.ajax.confirm_cek_ppob')
                ->with('data', null)
                ->with('message', 'Anda tidak memilih jenis pembayaran')
                ->with('dataVendor', null);
        }
        $vendor_id = $request->vendor_id;
        if ($buy_method == 3) {
            $vendor_id = 1;
        }
        $dataVendor = (object) array(
            'id' => $vendor_id
        );
        if ($vendor_id == null) {
            return view('member.ajax.confirm_cek_ppob')
                ->with('data', null)
                ->with('message', 'Anda belum memilih Vendor tujuan pembelian')
                ->with('dataVendor', null);
        }
        if ($request->harga == 'undefined') {
            return view('member.ajax.confirm_cek_ppob')
                ->with('data', null)
                ->with('message', 'Anda belum memilih nominal pembelian')
                ->with('dataVendor', null);
        }

        $modelPin = new Pin;
        $modelTrans = new Transaction;
        //cek $no_hp ga boleh dalam 10 menit
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if ($cekHP != null) {
            return view('member.ajax.confirm_cek_ppob')
                ->with('data', null)
                ->with('message', 'Nomor HP ini masih belum melewati jeda 10 menit dari transaksi sebelumnya.')
                ->with('dataVendor', null);
        }
        $separate = explode('__', $request->harga);
        $buyer_sku_code = $separate[0];
        $price = $separate[1];
        $brand = $separate[2];
        $desc = $separate[3];
        $real_price = $separate[4];
        $product_name = $separate[5];
        if ($buy_method == 1) {
            $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataVendor);
            $getTotalDeposit = $modelPin->getTotalDepositMember($dataVendor);
            $getTotalPPOBOut = $modelPin->getPPOBFly($vendor_id);
            $sum_deposit_masuk = 0;
            $sum_deposit_keluar1 = 0;
            $sum_deposit_keluar = 0;
            $sum_ppob_keluar = 0;
            if ($getTotalDeposit->sum_deposit_masuk != null) {
                $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
            }
            if ($getTotalDeposit->sum_deposit_keluar != null) {
                $sum_deposit_keluar1 = $getTotalDeposit->sum_deposit_keluar;
            }
            if ($getTransTarik->deposit_keluar != null) {
                $sum_deposit_keluar = $getTransTarik->deposit_keluar;
            }
            if ($getTotalPPOBOut->deposit_out != null) {
                $sum_ppob_keluar = $getTotalPPOBOut->deposit_out;
            }
            $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1 - $sum_ppob_keluar - $real_price;
            if ($totalDeposit < 0) {
                return view('member.ajax.confirm_cek_ppob')
                    ->with('data', null)
                    ->with('message', 'Transaksi tidak dapat dilanjutkan, saldo vendor kurang')
                    ->with('dataVendor', null);
            }
        }

        $modelMember = new Member;
        if ($type >= 21 && $type < 29) {
            $getData = (object) array(
                'buyer_sku_code' => $buyer_sku_code,
                'price' => $price,
                'brand' => $brand,
                'no_hp' => $no_hp,
                'vendor_id' => $vendor_id,
                'buy_method' => $buy_method,
                'harga_modal' => $real_price,
                'message' => $product_name
            );
        } else {
            $getData = (object) array(
                'buyer_sku_code' => $buyer_sku_code,
                'price' => $price,
                'brand' => $brand,
                'no_hp' => $no_hp,
                'vendor_id' => $vendor_id,
                'buy_method' => $buy_method,
                'harga_modal' => $real_price,
                'message' => $desc
            );
        }

        $getVendor = $modelMember->getUsers('id', $vendor_id);

        return view('member.ajax.confirm_cek_ppob')
            ->with('data', $getData)
            ->with('type', $type)
            ->with('dataVendor', $getVendor);
    }

    public function getVendorQuickBuy(Request $request)
    {
        $dataUser = Auth::user();
        $type = $request->type;
        $no_hp = $request->no_hp;
        $vendor_id = $dataUser->id;

        $modelPin = new Pin;
        $modelTrans = new Transaction;
        //check 10mins interval for multitrans
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if ($cekHP != null) {
            return view('member.ajax.confirm_vendor_quickbuy')
                ->with('data', null)
                ->with('message', 'No HP ini masih dalam masa tenggang 10 menit dari transaksi sebelumnya.')
                ->with('dataVendor', null);
        }

        //get product info
        $separate = explode('__', $request->product);
        $buyer_sku_code = $separate[0];
        $product_name = $separate[1];
        $price = $separate[2];

        //check available Vendor's deposit
        $vendorBalance = $this->getVendorAvailableDeposit($vendor_id);
        if ($price > $vendorBalance) {
            return view('member.ajax.confirm_vendor_quickbuy')
                ->with('data', null)
                ->with('message', 'Saldo Deposit tidak cukup!')
                ->with('dataVendor', null);
        }

        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'product_name' => $product_name,
            'no_hp' => $no_hp,
        );

        return view('member.ajax.confirm_vendor_quickbuy')
            ->with('data', $getData)
            ->with('type', $type)
            ->with('vendor_id', $vendor_id);
    }

    public function getVendorQuickbuyPostpaid(Request $request)
    {
        $dataUser = Auth::user();
        $type = $request->type;
        $no_hp = $request->no_hp;
        $vendor_id = $dataUser->id;
        $buyer_sku_code = $request->buyer_sku_code;
        $ref_id = $request->ref_id;
        $product_name = $request->product_name;
        $price = $request->price;

        $modelPin = new Pin;
        //check 10mins interval for multitrans
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if ($cekHP != null) {
            return view('member.ajax.confirm_order_postpaid')
                ->with('data', null)
                ->with('message', 'No Pelanggan ini baru saja dibayarkan.')
                ->with('dataVendor', null);
        }

        //check available Vendor's deposit
        $vendorBalance = $this->getVendorAvailableDeposit($vendor_id);
        if ($price > $vendorBalance) {
            return view('member.ajax.confirm_order_postpaid')
                ->with('data', null)
                ->with('message', 'Saldo Deposit tidak cukup!')
                ->with('dataVendor', null);
        }
        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'product_name' => $product_name,
            'ref_id' => $ref_id,
            'price' => $price,
            'no_hp' => $no_hp
        );

        return view('member.ajax.confirm_order_postpaid')
            ->with('data', $getData)
            ->with('type', $type)
            ->with('quickbuy', true)
            ->with('vendor_id', $vendor_id);
    }

    public function getCheckOrder(Request $request)
    {
        $type = $request->type;
        $no_hp = $request->no_hp;
        $vendor_id = $request->vendor_id;

        $modelPin = new Pin;
        //check 10mins interval for multitrans
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if ($cekHP != null) {
            return view('member.ajax.confirm_order_pulsa_data_pln')
                ->with('data', null)
                ->with('message', 'No HP ini masih dalam masa tenggang 10 menit dari transaksi sebelumnya.')
                ->with('dataVendor', null);
        }

        //get product info
        $separate = explode('__', $request->product);
        $buyer_sku_code = $separate[0];
        $product_name = $separate[1];
        $price = $separate[2];

        //check available Vendor's deposit
        $vendorBalance = $this->getVendorAvailableDeposit($vendor_id);
        if ($price > $vendorBalance) {
            return view('member.ajax.confirm_order_pulsa_data_pln')
                ->with('data', null)
                ->with('message', 'Saldo Deposit tidak cukup!')
                ->with('dataVendor', null);
        }
        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'product_name' => $product_name,
            'no_hp' => $no_hp
        );

        return view('member.ajax.confirm_order_pulsa_data_pln')
            ->with('data', $getData)
            ->with('type', $type)
            ->with('vendor_id', $vendor_id);
    }

    public function getCheckOrderPostpaid(Request $request)
    {
        $type = $request->type;
        $no_hp = $request->no_hp;
        $vendor_id = $request->vendor_id;
        $buyer_sku_code = $request->buyer_sku_code;
        $ref_id = $request->ref_id;
        $product_name = $request->product_name;
        $price = $request->price;

        $modelPin = new Pin;
        //check 10mins interval for multitrans
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if ($cekHP != null) {
            return view('member.ajax.confirm_order_postpaid')
                ->with('data', null)
                ->with('message', 'No Pelanggan ini baru saja dibayarkan.')
                ->with('dataVendor', null);
        }

        //check available Vendor's deposit
        $vendorBalance = $this->getVendorAvailableDeposit($vendor_id);
        if ($price > $vendorBalance) {
            return view('member.ajax.confirm_order_postpaid')
                ->with('data', null)
                ->with('message', 'Saldo Deposit tidak cukup!')
                ->with('dataVendor', null);
        }
        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'product_name' => $product_name,
            'ref_id' => $ref_id,
            'price' => $price,
            'no_hp' => $no_hp
        );

        return view('member.ajax.confirm_order_postpaid')
            ->with('data', $getData)
            ->with('type', $type)
            ->with('vendor_id', $vendor_id);
    }

    public function postConfirmPPOBPayment(Request $request)
    {
        $dataUser = Auth::user();
        $modelPin = new Pin;
        $masterSalesID = $request->masterSalesID;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($masterSalesID, $dataUser);
        if ($getDataMaster == null) {
            return response()->json(['success' => false, 'message' => 'Data Pesanan tidak ditemukan!']);
        }

        if ($request->buy_method == 1) {
            $dataUpdate = array(
                'status' => 1,
                'buy_metode' => 1,
                'confirm_at' => date('Y-m-d H:i:s')
            );
            $modelPin->getUpdatePPOB('id', $masterSalesID, $dataUpdate);

            return response()->json(['success' => true]);
        } elseif ($request->buy_method == 3) {
            $hash = $request->tron_transfer;
            $check = $modelPin->checkUsedHashExist($hash, 'ppob', 'tron_transfer');
            if ($check) {
                return response()->json(['success' => false, 'message' => 'Hash Transaksi sudah pernah digunakan pada pembayaran sebelumnya']);
            }
            $receiver = 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge';
            $amount = $getDataMaster->ppob_price;
            $timestamp = strtotime($getDataMaster->created_at) * 1000;

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
                return response()->json(['success' => false, 'message' => 'Hash Transaksi Bermasalah!']);
            };

            $hashTime = $response['raw_data']['timestamp'];
            $hashSender = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['owner_address']);
            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            if ($hashTime > $timestamp) {
                if ($hashAmount == $amount * 100) {
                    if ($hashAsset == '1002652') {
                        if ($hashReceiver == $receiver) {
                            $dataUpdate = array(
                                'status' => 1,
                                'buy_metode' => 3,
                                'tron_transfer' => $hash,
                                'confirm_at' => date('Y-m-d H:i:s')
                            );
                            $modelPin->getUpdatePPOB('id', $masterSalesID, $dataUpdate);

                            PPOBexecuteJob::dispatch($masterSalesID)->onQueue('tron');

                            return response()->json(['success' => true]);
                        } else {
                            return response()->json(['success' => false, 'message' => 'Alamat Tujuan Transfer Salah!']);
                        }
                    } else {
                        return response()->json(['success' => false, 'message' => 'Bukan token eIDR yang benar!']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Nominal Transfer Salah!']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Hash sudah terpakai!']);
            }
        }
    }

    public function postCancelPPOBPayment(Request $request)
    {
        $dataUser = Auth::user();
        $modelPin = new Pin;
        $masterSalesID = $request->masterSalesID;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($masterSalesID, $dataUser);
        if ($getDataMaster == null) {
            return response()->json(['success' => false, 'message' => 'Data pesanan tidak ditemukan!']);
        } else {
            $dataUpdate = array(
                'status' => 3,
                'reason' => 'Dibatalkan oleh pembeli',
                'deleted_at' => date('Y-m-d H:i:s')
            );
            $modelPin->getUpdatePPOB('id', $masterSalesID, $dataUpdate);
            return response()->json(['success' => true]);
        }
    }

    public function postCekBuyPPOBPasca(Request $request)
    {
        //cek validasi lg
        //cek vendor punya deposit ga
        $dataUser = Auth::user();
        $no_hp = $request->no_hp;
        if ($no_hp == null) {
            return view('member.ajax.confirm_cek_ppob_pasca')
                ->with('data', null)
                ->with('message', 'Anda tidak memasukan nomor')
                ->with('dataVendor', null);
        }
        $vendor_id = $request->vendor_id;
        $dataVendor = (object) array(
            'id' => $vendor_id
        );
        if ($vendor_id == null) {
            return view('member.ajax.confirm_cek_ppob_pasca')
                ->with('data', null)
                ->with('message', 'Anda tidak memilih Vendor')
                ->with('dataVendor', null);
        }
        if ($request->harga == 'undefined') {
            return view('member.ajax.confirm_cek_ppob_pasca')
                ->with('data', null)
                ->with('message', 'Anda tidak memilih harga')
                ->with('dataVendor', null);
        }
        $buy_method = $request->type_pay;
        if ($buy_method == 'undefined') {
            return view('member.ajax.confirm_cek_ppob_pasca')
                ->with('data', null)
                ->with('message', 'Anda tidak memilih jenis pembayaran')
                ->with('dataVendor', null);
        }

        $modelPin = new Pin;
        $modelTrans = new Transaction;
        //cek $no_hp ga boleh dalam 10 menit
        $cekHP = $modelPin->getCekHpOn10Menit($no_hp);
        if ($cekHP != null) {
            return view('member.ajax.confirm_cek_ppob_pasca')
                ->with('data', null)
                ->with('message', 'Nomor Pelanggan ini baru saja dibayarkan.')
                ->with('dataVendor', null);
        }
        $buyer_sku_code = $request->buyer_sku_code;
        $price = $request->harga;
        $real_price = $request->price;
        $getTransTarik = $modelTrans->getMyTotalTarikDeposit($dataVendor);
        $getTotalDeposit = $modelPin->getTotalDepositMember($dataVendor);
        $getTotalPPOBOut = $modelPin->getPPOBFly($vendor_id);
        $sum_deposit_masuk = 0;
        $sum_deposit_keluar1 = 0;
        $sum_deposit_keluar = 0;
        $sum_ppob_keluar = 0;
        if ($getTotalDeposit->sum_deposit_masuk != null) {
            $sum_deposit_masuk = $getTotalDeposit->sum_deposit_masuk;
        }
        if ($getTotalDeposit->sum_deposit_keluar != null) {
            $sum_deposit_keluar1 = $getTotalDeposit->sum_deposit_keluar;
        }
        if ($getTransTarik->deposit_keluar != null) {
            $sum_deposit_keluar = $getTransTarik->deposit_keluar;
        }
        if ($getTotalPPOBOut->deposit_out != null) {
            $sum_ppob_keluar = $getTotalPPOBOut->deposit_out;
        }
        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1 - $sum_ppob_keluar - $real_price;
        if ($totalDeposit < 0) {
            return view('member.ajax.confirm_cek_ppob_pasca')
                ->with('data', null)
                ->with('message', 'tidak dapat dilanjutkan, saldo vendor kurang')
                ->with('dataVendor', null);
        }
        $type = $request->type;
        $modelMember = new Member;
        if ($type == 4) {
            $typeName = 'Pembayaran BPJS';
        }
        if ($type == 5) {
            $typeName = 'Pembayaran PLN Pascabayar';
        }
        if ($type == 6) {
            $typeName = 'Pembayaran HP Pascabayar';
        }
        if ($type == 7) {
            $typeName = 'Pembayaran TELKOM PSTN';
        }
        if ($type == 8) {
            $typeName = 'Pembayaran PDAM';
        }
        if ($type == 9) {
            $typeName = 'Gas Negara';
        }
        if ($type == 10) {
            $typeName = 'Multifinance';
        }
        $getData = (object) array(
            'buyer_sku_code' => $buyer_sku_code,
            'price' => $price,
            'no_hp' => $no_hp,
            'vendor_id' => $vendor_id,
            'buy_method' => $buy_method,
            'harga_modal' => $real_price,
            'message' => $typeName,
            'ref_id' => $request->ref_id
        );
        $getVendor = $modelMember->getUsers('id', $vendor_id);
        return view('member.ajax.confirm_cek_ppob_pasca')
            ->with('data', $getData)
            ->with('type', $type)
            ->with('dataVendor', $getVendor);
    }

    public function postMemberBuyPPOBHP(Request $request)
    {
        $dataUser = Auth::user();
        $modelPin = new Pin;
        $modelMember = new Member;
        $getDataMaster = $modelPin->getMemberPembayaranPPOB($request->ppob_id, $dataUser);
        $getVendor = (object) array(
            'tron' => 'TDtvo2jCoRftmRgzjkwMxekh8jqWLdDHNB'
        );
        if ($getDataMaster->buy_metode == 1) {
            $getVendor = $modelMember->getUsers('id', $getDataMaster->vendor_id);
        }
        return view('member.ajax.confirm_pembayaran_ppob')
            ->with('data', $getDataMaster)
            ->with('dataVendor', $getVendor);
    }

    public function getCek2FAConfirmPPOB(Request $request)
    {
        $dataUser = Auth::user();
        $modelPin = new Pin;
        $getDataMaster = $modelPin->getVendorPPOBDetail($request->id_ppob, $dataUser);
        $is_2fa = false;
        if ($dataUser->{'2fa'} != null) {
            $is_2fa = true;
        }

        return view('member.ajax.confirm_2fa_cek_vppob')
            ->with('ppobId', $getDataMaster->id)
            ->with('is_2fa', $is_2fa)
            ->with('vendorId', $dataUser->id);
    }

    public function postRejectBuyPPOBHP(Request $request)
    {
        $dataUser = Auth::user();
        $id_ppob = $request->id_ppob;
        return view('member.ajax.reject_pembayaran_ppob')
            ->with('id_ppob', $id_ppob)
            ->with('dataUser', $dataUser);
    }

    public function getCreateTelegramLink()
    {
        $dataUser = Auth::user();
        $length = 10;
        $linkCode = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
        Cache::put($linkCode, $dataUser->id, 600);
        return response()->json(['success' => true, 'message' => $linkCode], 201);
    }
}
