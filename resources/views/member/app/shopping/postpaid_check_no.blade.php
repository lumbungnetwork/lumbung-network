@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Type selector --}}
{{-- 4 = BPJS, 5 = PLN Pasca, 6 = HP Pasca, 7 = Telkom, 8 = PDAM, 9 = PGN --}}
@php
$pricelistTitle = 'BPJS';
switch ($type) {
case 5:
$pricelistTitle = 'PLN Pascabayar';
break;
case 7:
$pricelistTitle = 'Telkom';
break;
case 9:
$pricelistTitle = 'PGN';
break;

case 6: {
switch ($buyer_sku_code) {
case 'HALO':
$pricelistTitle = 'KartuHALO';
break;

case 'MATRIX':
$pricelistTitle = 'Matrix';
break;
case 'SMARTPOST':
$pricelistTitle = 'Smartfren Postpaid';
break;
case 'TRIPOST':
$pricelistTitle = 'Three Postpaid';
break;
case 'XLPOST':
$pricelistTitle = 'XL Postpaid';
break;
}
}


}
// Quickbuy route
$formRoute = route('member.shopping.postShoppingDigitalOrder');
if ($quickbuy) {
$formRoute = route('member.shopping.postShoppingStoreQuickbuy');
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
                <form action="{{ $formRoute }}" method="POST" id="order-form">
                    @csrf
                    <input type="hidden" name="buyer_sku_code" id="buyer_sku_code" value="{{ $buyer_sku_code }}">
                    <input type="hidden" name="type" id="type" value="{{ $type }}">
                    <input type="text"
                        class="bg-transparent text-xs font-light ml-1 focus:outline-none allownumericwithoutdecimal"
                        inputmode="numeric" pattern="[0-9]*" name="customer_no" id="customer_no"
                        placeholder="No. {{$pricelistTitle}}" autocomplete="off">
                </form>


            </div>
            <div class="mt-3 flex justify-end">
                <button onclick="check()"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Cek
                    Tagihan</button>
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
    let buyer_sku_code = '{{ $buyer_sku_code }}';

    function check() {
        let customer_no = $('#customer_no').val();
        if (!customer_no) {
            Swal.fire('Oops', 'Anda belum memasukkan nomor tujuan', 'error');
            return false;
        }
        Swal.fire('Memproses...');
        Swal.showLoading();
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getCheckPostpaidCustomerNo') }}",
            data: {
                customer_no:customer_no,
                buyer_sku_code:buyer_sku_code,
                type:type
            },
            success: function(url){
                Swal.fire({
                    html: url,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                });
            }
        });
         
    };

    function submit() {
        $('#order-form').submit();
    }

    $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which> 57)) {
            event.preventDefault();
        }
    });
</script>
@endsection