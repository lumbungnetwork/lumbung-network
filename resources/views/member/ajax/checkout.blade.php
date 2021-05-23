<div class="-mx-2">
    <div class="my-1">
        <h5 class="text-xl text-gray-600 font-medium">Checkout</h5>
    </div>

    @if ($status == false)
    <div class="my-1">
        <h5 class="text-xl text-red-600 text-center font-medium">Stock tidak cukup!</h5>
        <p class="text-sm text-gray-600 font-light">Produk <mark>{{$name}}</mark> hanya tersedia sebanyak {{$stock}}
            saja. Silakan menghapus produk tersebut dan
            menambahkan
            sesuai stock yang tersedia.</p>
    </div>
    <div class="my-1 flex justify-end">
        <button
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out"
            onclick="checkCart()">Perbaiki Keranjang</button>
    </div>
    @else
    <div class="my-1">
        <h5 class="text-xl text-green-600 text-center font-medium">Keranjang belanja anda sudah siap!</h5>
        <p class="text-sm text-gray-600 font-light">Silakan klik Bayar untuk lanjut ke pembayaran.</p>
    </div>
    <div class="mt-2">
        <form action="{{ route('member.shopping.postCheckout') }}" method="POST">
            @csrf
            <input type="hidden" name="masterSalesID" value="{{ $masterSalesID }}">
            <button type="submit"
                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                Bayar &#10003;
            </button>
        </form>

    </div>

    @endif
</div>