@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Type selector --}}
@php
$pricelistTitle = 'Pulsa';
switch ($type) {
case 2:
$pricelistTitle = 'Data';
break;
case 3:
$pricelistTitle = 'PLN Prepaid';
break;
case 4:
$pricelistTitle = 'e-Money';
break;

}
@endphp

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        {{-- Enter Customer Number --}}
        <div class="nm-convex-gray-100 rounded-2xl p-4">
            <div class="my-2 text-xs text-gray-500">Masukkan No. Tujuan</div>
            <div class="nm-inset-gray-200 rounded-lg p-1">
                {{-- Order Form --}}
                <form action="{{ route('member.shopping.postShoppingPrepaidOrder') }}" method="POST" id="order-form">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="buyer_sku_code" id="buyer_sku_code">
                    <input type="text"
                        class="bg-transparent text-xs font-light ml-1 focus:outline-none allownumericwithoutdecimal"
                        inputmode="numeric" pattern="[0-9]*" name="customer_no" id="customer_no"
                        placeholder="No. {{$pricelist[0]['brand']}}">
                </form>

            </div>
        </div>
        {{-- Pricelist --}}
        <div class="mt-8 p-3 border border-gray-400 border-solid rounded-xl">
            <div class="-mt-7 w-14 text-lg text-center font-bold text-gray-600 tracking-wider bg-gray-200">
                {{ $pricelistTitle }}</div>
            <div class="mt-2 space-y-2">
                @foreach ($pricelist as $item)
                <div class="nm-flat-gray-50 p-1 rounded-lg">
                    <div class="text-sm text-gray-500 font-medium tracking-tighter">{{ $item['product_name'] }}</div>
                    <div style="font-size: 10px" class="text-gray-400 font-extralight">By: {{ $item['seller_name'] }}
                    </div>
                    <div class="flex justify-end">
                        <button onclick="check('{{ $item['product_name'] }}', '{{ $item['buyer_sku_code'] }}')"
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Rp{{ number_format($item['price']) }}</button>
                    </div>
                </div>

                @endforeach
            </div>
        </div>

    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    const type = {{ $type }};
    let _token = '{{ csrf_token() }}';

    function check(product_name, buyer_sku_code) {
        let customer_no = $('#customer_no').val();
        if (!customer_no) {
            Swal.fire('Oops', 'Anda belum memasukkan nomor tujuan', 'error');
            return false;
        }
        if (type <= 2) {
            if (customer_no.substring(0,2) != '08') {
                Swal.fire('Oops', 'Nomor tujuan harus diawali 08...', 'error');
                return false;
            }
        }
        
        Swal.fire({
            html: `<div class="text-md text-gray-500">Konfirmasi Order</div>
                    <div class="mt-3 text-left p-1">
                        <div class="text-xs text-gray-400">Produk:</div>
                        <div class="mb-3 nm-inset-gray-200 rounded-lg p-2">
                            <div class="ml-1 text-xs text-gray-600 font-light">${product_name}</div>
                        </div>
                        <div class="text-xs text-gray-400">No. Pelanggan:</div>
                        <div class="nm-inset-gray-200 rounded-lg p-2">
                            <div class="ml-1 text-xs text-gray-600 font-light">${customer_no}</div>
                        </div>
                        
                        
                        
                    </div>`,
            showCloseButton: false,
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'OK',
            focusConfirm: false,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Sedang Memproses...');
                Swal.showLoading();
                $('#buyer_sku_code').val(buyer_sku_code);
                $('#order-form').submit();

            }
        });
    }

    $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which> 57)) {
            event.preventDefault();
        }
    });
</script>
@endsection