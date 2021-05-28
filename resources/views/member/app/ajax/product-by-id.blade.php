<div class="-mx-2">
    <div class="my-1">
        <h3 class="text-lg font-light text-center text-gray-700">Detail Produk</h3>
    </div>
    <div class="mt-2">
        <div class="flex justify-center">
            <img class="object-cover rounded-tl-2xl rounded-tr-2xl"
                src="{{ asset('/storage/products') }}/{{$product->image}}" alt="product-picture">
        </div>
        <div class="mt-1 py-1 text-center">
            <h4 class="text-sm font-medium text-gray-600">{{$product->name}} {{$product->size}}</h4>
            <p class="text-md font-light text-black">Rp{{number_format($product->price)}}</p>
            <p class="text-xs text-gray-600 font-extralight">Stok: <b>{{$product->qty}}</b></p>
            <p class="text-xs text-gray-600 font-extralight">Deskripsi: {{$product->desc}}</p>
        </div>
    </div>
    <div class="mt-1">
        @if($product->qty > 0)
        <form id="form-order" method="post" action="/m/add-to-cart">
            @csrf
            <label for="quantity">Jumlah Order</label>
            <div class="mt-2 mb-4">
                <input id="minus" class="rounded-xl bg-red-300 border-none p-2 w-10 text-sm focus:outline-none"
                    type="button" value="-">
                <input type="number" inputmode="numeric" pattern="[0-9]*" value="1" id="quantity" name="quantity"
                    class="quantity p-2 border-none w-10" size="4" min="1" max="{{$product->qty}}" />
                <input id="plus" class="rounded-xl bg-green-300 border-none p-2 w-10 text-sm focus:outline-none"
                    type="button" value="+">
            </div>

            <input type="hidden" id="product_id" name="product_id" value="{{$product->id}}">

        </form>
    </div>
    <div class="flex justify-center space-x-1">

        <button onclick="swal.close()"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batal</button>
        <button onclick="addToCart()"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
            <div class="flex justify-between items-center">
                <p>Tambah ke</p>
                <div>
                    <svg class="w-5 h-5" viewBox="0 0 20 20">
                        <path fill="black"
                            d="M17.671,13.945l0.003,0.002l1.708-7.687l-0.008-0.002c0.008-0.033,0.021-0.065,0.021-0.102c0-0.236-0.191-0.428-0.427-0.428H5.276L4.67,3.472L4.665,3.473c-0.053-0.175-0.21-0.306-0.403-0.306H1.032c-0.236,0-0.427,0.191-0.427,0.427c0,0.236,0.191,0.428,0.427,0.428h2.902l2.667,9.945l0,0c0.037,0.119,0.125,0.217,0.239,0.268c-0.16,0.26-0.257,0.562-0.257,0.891c0,0.943,0.765,1.707,1.708,1.707S10,16.068,10,15.125c0-0.312-0.09-0.602-0.237-0.855h4.744c-0.146,0.254-0.237,0.543-0.237,0.855c0,0.943,0.766,1.707,1.708,1.707c0.944,0,1.709-0.764,1.709-1.707c0-0.328-0.097-0.631-0.257-0.891C17.55,14.182,17.639,14.074,17.671,13.945 M15.934,6.583h2.502l-0.38,1.709h-2.312L15.934,6.583zM5.505,6.583h2.832l0.189,1.709H5.963L5.505,6.583z M6.65,10.854L6.192,9.146h2.429l0.19,1.708H6.65z M6.879,11.707h2.027l0.189,1.709H7.338L6.879,11.707z M8.292,15.979c-0.472,0-0.854-0.383-0.854-0.854c0-0.473,0.382-0.855,0.854-0.855s0.854,0.383,0.854,0.855C9.146,15.596,8.763,15.979,8.292,15.979 M11.708,13.416H9.955l-0.189-1.709h1.943V13.416z M11.708,10.854H9.67L9.48,9.146h2.228V10.854z M11.708,8.292H9.386l-0.19-1.709h2.512V8.292z M14.315,13.416h-1.753v-1.709h1.942L14.315,13.416zM14.6,10.854h-2.037V9.146h2.227L14.6,10.854z M14.884,8.292h-2.321V6.583h2.512L14.884,8.292z M15.978,15.979c-0.471,0-0.854-0.383-0.854-0.854c0-0.473,0.383-0.855,0.854-0.855c0.473,0,0.854,0.383,0.854,0.855C16.832,15.596,16.45,15.979,15.978,15.979 M16.917,13.416h-1.743l0.189-1.709h1.934L16.917,13.416z M15.458,10.854l0.19-1.708h2.218l-0.38,1.708H15.458z">
                        </path>
                    </svg>
                </div>
            </div>
        </button>

    </div>
    @else
    <p class="mt-4 text-gray-400 font-medium text-center text-xl">Stok Produk Habis</p>
</div>
<div class="flex justify-end">

    <button onclick="swal.close()"
        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batal</button>
</div>
@endif
</div>