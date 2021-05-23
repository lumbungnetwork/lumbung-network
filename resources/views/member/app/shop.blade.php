@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="mt-4 px-4">
        <h2 class="text-lg font-medium text-gray-800">{{ $sellerProfile->shop_name }}</h2>
    </div>

    <div class="p-4">

        <div class="p-2 nm-convex-gray-50 rounded-2xl">
            <div class="flex justify-between">
                <div class="w-7/12">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 text-center">
                        {{ $sellerData->alamat }}</p>
                    <div class="mt-2 flex justify-around">
                        {{-- Phone --}}
                        <div class="rounded-full p-2 w-8 h-8 nm-convex-gray-100">
                            <a href="tel:{{$sellerProfile->no_hp}}">
                                <svg viewBox="0 0 64 64">
                                    <path data-name="layer2"
                                        d="M49.4 50.3l-8.8-5.8a4.1 4.1 0 0 0-5.6 1.1c-2 2.5-4.5 6.6-13.8-2.8S15.9 31 18.4 29a4.1 4.1 0 0 0 1.1-5.5l-5.8-8.8c-.8-1.1-1.8-2.9-4.2-2.6S1 15.9 1 23.5s6 17 14.2 25.2S32.8 63 40.5 63s11.2-6.8 11.5-8.5-1.5-3.5-2.6-4.2z"
                                        fill="#202020"></path>
                                    <path data-name="layer1"
                                        d="M29 1a2 2 0 0 0 0 4 30 30 0 0 1 30 30 2 2 0 1 0 4 0A34 34 0 0 0 29 1z"
                                        fill="#202020">
                                    </path>
                                    <path data-name="layer1"
                                        d="M29 11a2 2 0 1 0 0 4 20 20 0 0 1 20 20 2 2 0 1 0 4 0 24 24 0 0 0-24-24z"
                                        fill="#202020">
                                    </path>
                                    <path data-name="layer1"
                                        d="M38 35a2 2 0 1 0 4 0 13 13 0 0 0-13-13 2 2 0 0 0 0 4 9 9 0 0 1 9 9z"
                                        fill="#202020">
                                    </path>
                                </svg>
                            </a>
                        </div>

                        {{-- WhatsApp --}}
                        <div class="rounded-full p-2 w-8 h-8 nm-convex-gray-100">
                            <a href="https://wa.me/62{{substr($sellerProfile->no_hp, 1)}}">
                                <svg viewBox="0 0 64 64">
                                    <path data-name="layer2"
                                        d="M30.287 2.029A29.769 29.769 0 0 0 5.223 45.266L2.064 60.6a1.158 1.158 0 0 0 1.4 1.361L18.492 58.4A29.76 29.76 0 1 0 30.287 2.029zm17.931 46.2"
                                        fill="none" stroke="#3ac230" stroke-linecap="round" stroke-miterlimit="10"
                                        stroke-width="4" stroke-linejoin="round"></path>
                                    <path data-name="layer1"
                                        d="M46.184 38.205l-5.765-1.655a2.149 2.149 0 0 0-2.126.561l-1.41 1.436a2.1 2.1 0 0 1-2.283.482c-2.727-1.1-8.463-6.2-9.927-8.754a2.1 2.1 0 0 1 .166-2.328l1.23-1.592a2.148 2.148 0 0 0 .265-2.183l-2.424-5.485a2.149 2.149 0 0 0-3.356-.769c-1.609 1.361-3.517 3.428-3.749 5.719-.409 4.039 1.323 9.13 7.872 15.242 7.566 7.063 13.626 8 17.571 7.04 2.238-.542 4.026-2.714 5.154-4.493a2.15 2.15 0 0 0-1.218-3.221z"
                                        fill="none" stroke="#3ac230" stroke-linecap="round" stroke-miterlimit="10"
                                        stroke-width="4" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>

                        {{-- Telegram --}}
                        <div class="rounded-full p-2 w-8 h-8 nm-convex-gray-100">
                            <a href="https://t.me/{{$sellerProfile->tg_user}}">
                                <svg viewBox="0 0 240 240">
                                    <path
                                        d="M66.964 134.874s-32.08-10.062-51.344-16.002c-17.542-6.693-1.57-14.928 6.015-17.59 7.585-2.66 186.38-71.948 194.94-75.233 8.94-4.147 19.884-.35 14.767 18.656-4.416 20.407-30.166 142.874-33.827 158.812-3.66 15.937-18.447 6.844-18.447 6.844l-83.21-61.442z"
                                        fill="none" stroke="#0088cc" stroke-width="10" />
                                    <path d="M92.412 201.62s4.295.56 8.83-3.702c4.536-4.26 26.303-25.603 26.303-25.603"
                                        fill="none" stroke="#0088cc" stroke-width="10" />
                                    <path
                                        d="M66.985 134.887l28.922 14.082-3.488 52.65s-4.928.843-6.25-3.613c-1.323-4.455-19.185-63.12-19.185-63.12z"
                                        fill-rule="evenodd" stroke="#0088cc" stroke-width="10"
                                        stroke-linejoin="bevel" />
                                    <path
                                        d="M66.985 134.887s127.637-77.45 120.09-71.138c-7.55 6.312-91.168 85.22-91.168 85.22z"
                                        fill-rule="evenodd" stroke="#0088cc" stroke-width="9.67"
                                        stroke-linejoin="bevel" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
                {{-- Seller Profile Picture --}}
                <div class="w-5/12 p-2">
                    <img class="object-contain rounded-2xl"
                        src="{{ asset('/storage/sellers') }}/{{$sellerProfile->image}}" alt="seller-profile-picture">
                </div>

            </div>
            {{-- Motto --}}
            <div class="mt-2 px-2 w-full">
                <p class="font-light text-xs sm:text-sm text-gray-600 text-center">
                    "{{$sellerProfile->motto}}"</p>
            </div>
        </div>
        {{-- Category select --}}
        <div class="mt-4 nm-concave-gray-100 rounded-2xl p-2">
            <div class="flex justify-between px-2">
                <p class="text-sm font-medium">Kategori</p>
                <select onchange="filterProduct()" id="category-select"
                    class="text-xs bg-transparent focus:outline-none">
                    <option value="0" selected>Tampilkan Semua</option>
                    @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="px-1">
        <div id="products" class="flex flex-wrap justify-between w-full p-0">
            @if(!empty($products->toArray()))
            @foreach ($products as $product)
            <div class="nm-flat-gray-50 rounded-xl p-0 mx-1 my-2" onclick="showDetail({{$product->id}})"
                style="max-width: 46%">
                <div>
                    <img class="object-cover rounded-tl-2xl rounded-tr-2xl"
                        src="{{ asset('/storage/products') }}/{{$product->image}}" alt="product-picture">
                </div>
                <div class="mt-1 py-1 text-center">
                    <h4 class="text-xs font-medium text-gray-700">{{$product->name}}</h4>
                    <p class="font-light text-gray-600" style="font-size: 12px;">{{$product->size}}</p>
                    <p class="text-xs text-black">Rp{{number_format($product->price)}}</p>
                    <p class="text-xs text-gray-600 font-extralight">Stok: <b>{{$product->qty}}</b></p>
                </div>
            </div>
            @endforeach
            @else
            <div class="container text-center">
                <h6>Tidak ada produk</h6>
            </div>
            @endif
        </div>
    </div>


</div>

<button
    class="block sticky bottom-16 left-2 z-50 opacity-90 rounded-lg py-1 px-2 h-10 bg-gradient-to-br from-green-200 to-purple-300 text-md text-center font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out"
    id="cart" onclick="checkCart()">
    <div class="flex justify-evenly items-center">
        <svg class="w-8 h-8" viewBox="0 0 20 20">
            <path fill="gray"
                d="M17.671,13.945l0.003,0.002l1.708-7.687l-0.008-0.002c0.008-0.033,0.021-0.065,0.021-0.102c0-0.236-0.191-0.428-0.427-0.428H5.276L4.67,3.472L4.665,3.473c-0.053-0.175-0.21-0.306-0.403-0.306H1.032c-0.236,0-0.427,0.191-0.427,0.427c0,0.236,0.191,0.428,0.427,0.428h2.902l2.667,9.945l0,0c0.037,0.119,0.125,0.217,0.239,0.268c-0.16,0.26-0.257,0.562-0.257,0.891c0,0.943,0.765,1.707,1.708,1.707S10,16.068,10,15.125c0-0.312-0.09-0.602-0.237-0.855h4.744c-0.146,0.254-0.237,0.543-0.237,0.855c0,0.943,0.766,1.707,1.708,1.707c0.944,0,1.709-0.764,1.709-1.707c0-0.328-0.097-0.631-0.257-0.891C17.55,14.182,17.639,14.074,17.671,13.945 M15.934,6.583h2.502l-0.38,1.709h-2.312L15.934,6.583zM5.505,6.583h2.832l0.189,1.709H5.963L5.505,6.583z M6.65,10.854L6.192,9.146h2.429l0.19,1.708H6.65z M6.879,11.707h2.027l0.189,1.709H7.338L6.879,11.707z M8.292,15.979c-0.472,0-0.854-0.383-0.854-0.854c0-0.473,0.382-0.855,0.854-0.855s0.854,0.383,0.854,0.855C9.146,15.596,8.763,15.979,8.292,15.979 M11.708,13.416H9.955l-0.189-1.709h1.943V13.416z M11.708,10.854H9.67L9.48,9.146h2.228V10.854z M11.708,8.292H9.386l-0.19-1.709h2.512V8.292z M14.315,13.416h-1.753v-1.709h1.942L14.315,13.416zM14.6,10.854h-2.037V9.146h2.227L14.6,10.854z M14.884,8.292h-2.321V6.583h2.512L14.884,8.292z M15.978,15.979c-0.471,0-0.854-0.383-0.854-0.854c0-0.473,0.383-0.855,0.854-0.855c0.473,0,0.854,0.383,0.854,0.855C16.832,15.596,16.45,15.979,15.978,15.979 M16.917,13.416h-1.743l0.189-1.709h1.934L16.917,13.416z M15.458,10.854l0.19-1.708h2.218l-0.38,1.708H15.458z">
            </path>
        </svg>
        Total: Rp<b id="cartTotal">0</b>
    </div>
</button>



@include('member.components.mobile_sticky_nav')

@endsection



@section('scripts')
<script type="text/javascript">
    let _token = '{{ csrf_token() }}';
    $(document).ready(function() {
        getTotal();
    })

    function getTotal() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getCartTotal') }}" + "?user_id="+"{{$user->id}}",
            success: function(total){
                $("#cartTotal" ).empty();
                $("#cartTotal").html(total);
            }
        })
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        width: 200,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    function successToast (message) {
        Toast.fire({
            icon: 'success',
            title: message
        })
    }

    function filterProduct() {
        var category_id = $('#category-select').val();
        var seller_id = {{$seller_id}}
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getProductByCategory') }}" + "?category_id="+category_id+"&seller_id="+seller_id,
            success: function(url){
                $("#products" ).empty();
                $("#products").html(url);
            }
        })
    }

    function showDetail(product_id) {
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getProductById') }}" + "?product_id="+product_id,
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                });
                
                $('#plus').on('click', function(e) {
                    var val = parseInt($(this).prev('input').val());
                    var max = parseInt($(this).prev('input').attr('max'));
                    if(val < max) {
                        $(this).prev('input').attr('value', val + 1);
                    }
                });

                $('#minus').on('click', function(e) {
                    var val = parseInt($(this).next('input').val());
                    if (val !== 0) {
                        $(this).next('input').attr('value', val - 1);
                    }
                });
            }
        })
    }

    function addToCart() {
        var product_id = $('#product_id').val();
        var quantity = $('#quantity').val();
        $.ajax({
            type: "POST",
            url: "{{ route('ajax.shopping.postAddToCart') }}",
            data: {
            product_id:product_id,
            quantity:quantity,
            _token:_token
            },
            success: function(response){
                if(response.success) {
                    swal.close();
                    getTotal();
                    Swal.fire(
                        'Berhasil',
                        response.message,
                        'success'
                    );
                } else {
                    swal.close();
                    getTotal();
                    Swal.fire(
                        'Oops!',
                        response.message,
                        'error'
                    );
                }

            }
        })

    }

    function checkCart() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getCartContents') }}" + "?user_id="+"{{$user->id}}",
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                });
            }
        })
    }

    function deleteCartItem(product_id, item_id) {
        $('#item-'+item_id).remove();
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getDeleteCartItem') }}" + "?product_id="+product_id+"&user_id="+"{{$user->id}}",
            success: function(url){
                Swal.update({
                    html: url
                });
                getTotal();
            
            }
        })
    }
    
    function checkout() {
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getCartCheckout') }}" + "?seller_id=" + {{$seller_id}},
            success: function(url){
                Swal.update({
                    html: url
                });
            }
        })
    }

    

    


</script>
@endsection