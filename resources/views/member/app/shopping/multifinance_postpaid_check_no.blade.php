@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Type selector --}}
{{-- 4 = BPJS, 5 = PLN Pasca, 6 = HP Pasca, 7 = Telkom, 8 = PDAM, 9 = PGN --}}
@php

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
            <div class="my-2 text-xs text-gray-500">Pilih Jenis Pembayaran Multifinance <br>lalu masukkan No. Tagihan
            </div>
            {{-- Order Form --}}
            <form action="{{ $formRoute }}" method="POST" id="order-form">
                @csrf
                <div class="my-3 nm-inset-gray-200 rounded-lg p-1">
                    <select class="bg-transparent text-xs font-light ml-1 focus:outline-none w-full overflow-x-auto"
                        name="buyer_sku_code" id="buyer_sku_code">
                        <option id="area_placeholder">--Pilih Type Multifinance--</option>
                        @foreach ($priceArray as $area)
                        <option value="{{ $area['buyer_sku_code'] }}">{{ $area['product_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="type" id="type" value="10">
                <div class="my-3 nm-inset-gray-200 rounded-lg p-1">
                    <input type="text"
                        class="bg-transparent text-xs font-light ml-1 focus:outline-none allownumericwithoutdecimal"
                        inputmode="numeric" pattern="[0-9]*" name="customer_no" id="customer_no"
                        placeholder="No. Tagihan" autocomplete="off">
                </div>

            </form>

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
    const type = 10;
    let _token = '{{ csrf_token() }}';
    let buyer_sku_code = '';

    $('#buyer_sku_code').change( function () {
        buyer_sku_code = $(this).val();
        $('#area_placeholder').remove();
    })

    function check() {
        let customer_no = $('#customer_no').val();
        if (!customer_no) {
            Swal.fire('Oops', 'Anda belum memasukkan nomor tagihan', 'error');
            return false;
        }
        if (buyer_sku_code == '') {
            Swal.fire('Oops', 'Anda belum memilih Type Multifinance', 'error');
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