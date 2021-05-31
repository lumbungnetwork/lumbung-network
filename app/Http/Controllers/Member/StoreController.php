<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Model\Member\Product;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Model\Member\SellerProfile;
use Intervention\Image\ImageManager;
use App\Model\Member\Category;
use App\Model\Member\DigitalSale;
use App\Model\Member\MasterSales;
use App\Model\Member\EidrBalance;
use App\Model\Member\Sales;
use App\Jobs\FireDigiflazzTransactionJob;

class StoreController extends Controller
{
    public function getStoreInfo()
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        if (!$user->is_profile) {
            Alert::warning('Oops', 'Anda perlu melengkapi data ini terlebih dahulu');
            return redirect()->route('member.profile');
        }
        $sellerProfile = SellerProfile::where('seller_id', $user->id)->first();

        return view('member.app.store.info')
            ->with(compact('sellerProfile'))
            ->with(compact('user'))
            ->with('title', 'Info Toko');
    }

    public function getStoreEditInfo()
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        if (!$user->is_profile) {
            Alert::warning('Oops', 'Anda perlu melengkapi data ini terlebih dahulu');
            return redirect()->route('member.profile');
        }
        $sellerProfile = SellerProfile::where('seller_id', $user->id)->first();

        return view('member.app.store.edit_info')
            ->with(compact('sellerProfile'))
            ->with(compact('user'))
            ->with('title', 'Edit Info Toko');
    }

    public function postStoreAddInfo(Request $request)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        $imageName = 'default.jpg';

        $validated = $request->validate([
            'shop_name' => 'required|string|max:25',
            'motto' => 'required|string|max:65',
            'tg_user' => 'nullable|string|max:60',
            'no_hp' => 'required|numeric|digits_between:9,16'
        ]);

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $request->validate([
                    'image' => 'required|mimes:jpeg,png|max:3000',
                ]);

                $name = $user->username . '-' . 1;
                $extension = 'jpg';
                $imageClass = new ImageManager;
                $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/sellers/' . $name . "." . $extension), 80, 'jpg');
                $imageName = $name . "." . $extension;
            } else {
                Alert::error('Gagal', 'Gagal upload gambar');
                return redirect()->back();
            }
        }

        SellerProfile::create([
            'seller_id' => $user->id,
            'shop_name' => $validated['shop_name'],
            'motto' => $validated['motto'],
            'no_hp' => $validated['no_hp'],
            'tg_user' => $validated['tg_user'],
            'image' => $imageName
        ]);

        Alert::success('Berhasil!', 'Data Profile telah dibuat');
        return redirect()->back();
    }

    public function postStoreEditInfo(Request $request)
    {
        $user = Auth::user();
        $sellerProfile = SellerProfile::where('seller_id', $user->id)->first();
        // Checking
        if ($user->user_type != 10 || !$user->is_store || !$sellerProfile) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        $imageName = $sellerProfile->image;

        $validated = $request->validate([
            'shop_name' => 'required|string|max:25',
            'motto' => 'required|string|max:65',
            'tg_user' => 'nullable|string|max:60',
            'no_hp' => 'required|numeric|digits_between:9,16'
        ]);

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $request->validate([
                    'image' => 'required|mimes:jpeg,png|max:3000',
                ]);

                // increment image name counter
                $a = explode('-', $imageName);
                $i = 1;
                if (count($a) > 1) {
                    $b = end($a);
                    $c = str_replace('.jpg', '', $b);
                    $i = $c + 1;
                }

                $name = $user->username . '-' . $i;
                $extension = 'jpg';
                $imageClass = new ImageManager;
                $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/sellers/' . $name . "." . $extension), 80, 'jpg');
                $imageName = $name . "." . $extension;
            } else {
                Alert::error('Gagal', 'Gagal upload gambar');
                return redirect()->back();
            }
        }

        SellerProfile::where('seller_id', $user->id)->update([
            'shop_name' => $validated['shop_name'],
            'motto' => $validated['motto'],
            'no_hp' => $validated['no_hp'],
            'tg_user' => $validated['tg_user'],
            'image' => $imageName
        ]);

        Alert::success('Berhasil!', 'Data Profile telah diperbarui');
        return redirect()->route('member.store.info');
    }

    public function getStoreInventory()
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }
        $products = Product::where('seller_id', $user->id)->where('is_active', 1)->get();

        return view('member.app.store.inventory')
            ->with('title', 'Inventory')
            ->with(compact('products'))
            ->with(compact('user'));
    }

    public function getStoreAddProduct()
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        $categories = Category::all();

        return view('member.app.store.add_product')
            ->with('title', 'Add Product')
            ->with(compact('categories'))
            ->with(compact('user'));
    }

    public function postStoreAddProduct(Request $request)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // input validation
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:70'],
            'size' => ['required', 'string', 'min:1', 'max:50'],
            'price' => ['required', 'integer', 'digits_between:1,10'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'qty' => ['integer'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



        // start creating the product record
        $product = new Product;
        $product->type = 1;
        $product->seller_id = $user->id;
        $product->name = $request->name;
        $product->size = $request->size;
        $product->price = $request->price;

        if (filled($request->desc)) {
            $validateDesc = $request->validate([
                'desc' => 'string|max:100'
            ]);
            $product->desc = $validateDesc['desc'];
        }

        $product->qty = $request->qty;
        $product->category_id = $request->category_id;

        // handle if there's image uploaded
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $request->validate([
                    'image' => 'required|mimes:jpeg,png|max:3000',
                ]);

                $name = str_replace(' ', '-', $request->name) . '-' . str_replace(' ', '-', $request->size);
                $extension = 'jpg';
                $imageName = strtolower($name) . "." . $extension;
                // prevent duplicate
                $check = Product::where('image', $imageName)->exists();
                if ($check) {
                    $imageName = strtolower($name) . '-' . $user->id . "." . $extension;
                }
                $imageClass = new ImageManager;
                $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/products/' . $imageName), 80, 'jpg');

                // insert image name
                $product->image = $imageName;
            } else {
                Alert::error('Gagal', 'Gagal upload gambar');
                return redirect()->back();
            }
        } else {
            // insert image name (no upload)
            $validated = $request->validate([
                'image' => 'required|string|exists:products,image'
            ]);
            $product->image = $validated['image'];
        }

        // save the product
        $product->save();

        Alert::success('Berhasil', 'Produk telah dibuat');
        return redirect()->route('member.store.inventory');
    }

    public function getStoreEditProduct($product_id)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // check if the caller is rightful owner
        $product = Product::findOrFail($product_id);
        if ($product->seller_id != $user->id || !$product->is_active) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->route('member.store.inventory');
        }
        $categories = Category::all();

        return view('member.app.store.edit_product')
            ->with('title', 'Edit Product')
            ->with(compact('product'))
            ->with(compact('categories'))
            ->with(compact('user'));
    }

    public function postStoreEditProduct(Request $request, $product_id)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // input validation
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:70'],
            'size' => ['required', 'string', 'min:1', 'max:50'],
            'price' => ['required', 'integer', 'digits_between:1,10'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'qty' => ['integer'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // check if the caller is rightful owner
        $product = Product::findOrFail($product_id);
        if ($product->seller_id != $user->id || !$product->is_active) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // start editing the product record
        $product->name = $request->name;
        $product->size = $request->size;
        $product->price = $request->price;

        if (filled($request->desc)) {
            $validateDesc = $request->validate([
                'desc' => 'string|max:100'
            ]);
            $product->desc = $validateDesc['desc'];
        }

        $product->qty = $request->qty;
        $product->category_id = $request->category_id;

        // handle if there's image uploaded
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $request->validate([
                    'image' => 'required|mimes:jpeg,png|max:3000',
                ]);

                $name = str_replace(' ', '-', $request->name) . '-' . str_replace(' ', '-', $request->size);
                $extension = 'jpg';
                $imageName = strtolower($name) . "." . $extension;
                // prevent duplicate
                $check = Product::where('image', $imageName)->exists();
                if ($check) {
                    $imageName = strtolower($name) . '-' . $user->id . "." . $extension;
                }
                $imageClass = new ImageManager;
                $imageClass->make($request->image)->fit(200)->save(storage_path('app/public/products/' . $imageName), 80, 'jpg');

                // insert image name
                $product->image = $imageName;
            } else {
                Alert::error('Gagal', 'Gagal upload gambar');
                return redirect()->back();
            }
        } else {
            // insert image name (no upload)
            $validated = $request->validate([
                'image' => 'required|string|exists:products,image'
            ]);
            $product->image = $validated['image'];
        }

        // save the product
        $product->save();

        Alert::success('Berhasil', 'Data Produk telah diubah');
        return redirect()->route('member.store.inventory');
    }

    public function postStoreDeleteProduct($product_id)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // check if the caller is rightful owner
        $product = Product::findOrFail($product_id);
        if ($product->seller_id != $user->id) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->route('member.store.inventory');
        }

        // delete the product (deactivate)
        $product->is_active = 0;
        $product->save();

        Alert::success('Deleted', 'Produk telah dihapus');
        return redirect()->route('member.store.inventory');
    }

    public function getStoreTransactions()
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // Physical goods
        $physical_tx = MasterSales::where('stockist_id', $user->id)
            ->where('status', '>', 0)
            ->where('buy_metode', '>', 0)
            ->with('buyer')
            ->orderByDesc('created_at')
            ->paginate(15);

        // Digital goods
        $digital_tx = DigitalSale::where('vendor_id', $user->id)
            ->with('buyer')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.app.store.transactions')
            ->with('title', 'Transaksi')
            ->with(compact('physical_tx'))
            ->with(compact('digital_tx'));
    }

    public function getStoreConfirmPhysicalOrder($masterSalesID)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // get masterSales data
        $masterSalesData = MasterSales::findOrFail($masterSalesID);
        $salesData = Sales::where('master_sales_id', $masterSalesID)->get();
        if ($masterSalesData->stockist_id != $user->id) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // get internal eIDR balance
        $EidrBalance = new EidrBalance;
        $balance = $EidrBalance->getUserNeteIDRBalance($user->id);

        return view('member.app.store.confirm_physical_payment')
            ->with('title', 'Transaksi')
            ->with(compact('masterSalesData'))
            ->with(compact('salesData'))
            ->with(compact('balance'));
    }

    public function getStoreConfirmDigitalOrder($id)
    {
        $user = Auth::user();
        $quickbuy = false;

        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // get data
        $data = DigitalSale::findOrFail($id);
        if ($data->vendor_id != $user->id) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // check quickbuy
        if ($data->user_id == $user->id) {
            $quickbuy = true;
        }

        // get internal eIDR balance
        $EidrBalance = new EidrBalance;
        $balance = $EidrBalance->getUserNeteIDRBalance($user->id);

        return view('member.app.store.confirm_digital_payment')
            ->with('title', 'Transaksi')
            ->with(compact('data'))
            ->with(compact('quickbuy'))
            ->with(compact('balance'));
    }

    public function postStoreConfirmDigitalOrder(Request $request)
    {
        $user = Auth::user();
        // Checking
        if ($user->user_type != 10 || !$user->is_store) {
            Alert::error('Oops', 'Access Denied');
            return redirect()->back();
        }

        // validate input
        $validator = Validator::make($request->all(), [
            'salesID' => 'required|integer|exists:ppob,id',
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
            if ($data->vendor_id != $user->id || $data->buy_metode != 1) {
                $lock->release();
                Alert::error('Error', 'Access Denied!');
                return redirect()->back();
            }

            // Get seller's balance, check remaining and deduct
            $EidrBalance = new EidrBalance;
            $balance = $EidrBalance->getUserNeteIDRBalance($user->id);
            $remaining = $balance - $data->harga_modal;
            if ($remaining < 0) {
                $lock->release();
                Alert::error('Gagal', 'Saldo eIDR anda tidak cukup!');
                return redirect()->back();
            }

            // create new negative balance to deduct
            $newBalance = new EidrBalance;
            $newBalance->user_id = $user->id;
            $newBalance->amount = $data->harga_modal;
            $newBalance->type = 0;
            $newBalance->source = 6;
            $newBalance->tx_id = $data->id;
            $newBalance->note = 'Transaksi: ' . $data->buyer_code . ' ' . $data->product_name;
            $newBalance->save();

            // Dispatch job
            FireDigiflazzTransactionJob::dispatch($data->id)->onQueue('digital');

            // Update Sales Data status to processing
            $data->status = 5;
            $data->tx_id = $newBalance->id;
            $data->save();

            $lock->release();

            Alert::success('Pembayaran Berhasil', 'Pesanan anda segera diproses!');
            return redirect()->back();
        }
        Alert::error('Error', 'Access Denied!');
        return redirect()->back();
    }
}
