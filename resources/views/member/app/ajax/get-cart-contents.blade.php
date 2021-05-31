<div class="-mx-2">
    <div class="my-1">
        <h5 class="text-xl text-gray-600 font-medium">Keranjang Belanja Anda</h5>
    </div>

    <hr class="my-4 border-b-0 border-gray-300">

    <div>
        @php
        $no = 1;
        @endphp
        @foreach ($products as $product)
        <div id="item-{{$no}}" class="flex items-center justify-between my-4">
            <div class="text-left">
                <div class="text-sm text-gray-800">{{$product->name}}</div>
                <div class="text-md text-gray-600 font-light">Harga: Rp{{number_format($product->price)}}</div>
                <div class="text-xs text-gray-600 font-medium">Qty: {{$product->quantity}}</div>
            </div>
            <div>
                <div class="text-xs text-red-600 font-medium" onclick="deleteCartItem({{$product->id}}, {{$no}})">Hapus
                </div>
            </div>
        </div>
        <hr class="my-4 border-b-0 border-gray-300">
        @php
        $no++;
        @endphp
        @endforeach

        <div class="my-4 flex justify-end">
            <div class="text-xl font-light text-gray-800">Total: Rp{{number_format(Cart::getSubTotal())}}</div>
        </div>

        <div class="flex justify-center space-x-2">
            <button onclick="swal.close()"
                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batal</button>
            <button onclick="checkout()"
                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                <div class="flex justify-between items-center">
                    <p>Checkout</p>
                    <div>
                        <svg class="w-5 h-5" viewBox="0 0 20 20">
                            <path fill="black"
                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                            </path>
                        </svg>
                    </div>
                </div>
            </button>
        </div>

    </div>
</div>