@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="px-2 my-1">
        {{-- Order Info --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl p-4">
            @if ($data->status == 0)
            <h4 class="text-xs font-light text-gray-500">Penjual: <span class="text-sm font-light text-gray-600"
                    id="seller">{{ $sellerArr['shop_name'] }}</span></h4>
            <div style="font-size: 10px" class="font-light text-gray-500" id="seller-info">Penjual dipilih otomatis
                berdasarkan lokasi
                anda.</div>
            <div class="flex justify-end">
                <button id="change-seller" style="font-size: 10px"
                    class="rounded-lg py-1 px-2 h-6 bg-gradient-to-br from-red-400 to-purple-300 text-gray-800 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ganti
                    Penjual</button>
                {{-- Change seller --}}
                <div id="change-seller-div" class="hidden mt-2 mb-8">
                    <div style="font-size: 10px" class="text-gray-400 font-light">Ketikkan 3-4 huruf awal
                        nama toko, akan tampil list nama, silakan klik nama toko yang diinginkan.</div>
                    <div class="mt-2 nm-inset-gray-200 p-2 rounded-2xl">
                        <input class="ml-2 bg-transparent focus:outline-none w-full" type="text" name="input_toko"
                            id="get_id" placeholder="cari toko..." autocomplete="off">
                    </div>
                    <div class="px-2">
                        <input type="hidden" id="id_get_id">
                        <ul class="text-sm font-light max-h-32 overflow-auto border border-solid border-gray-200 w-full hidden"
                            id="get_id-box"></ul>
                    </div>
                </div>
            </div>
            @else
            <h4 class="text-xs font-light text-gray-500">Penjual: <span class="text-sm font-light text-gray-600"
                    id="seller">{{ $sellerArr['shop_name'] }}</span></h4>
            @endif

            <div class="mt-4 text-xs font-extralight text-gray-500">{{ $data->ppob_code }}</div>
            <div class="text-xs font-extralight text-gray-500">Date:
                {{ date('d-M-y', strtotime($data->created_at)) }}</div>


        </div>

        <div class="mt-4 nm-inset-gray-100 rounded-2xl p-2 overflow-x-scroll">
            <div class="p-2">
                <div class="">
                    <div class="text-sm text-gray-800">
                        Customer: {{$data->product_name}}
                    </div>
                    <div class="text-xs text-gray-500 font-light">
                        {{$data->message}}</div>

                </div>
                <hr class="my-2 border-b-0 border-gray-400">
                <div class="text-sm font-medium text-right">Total:
                    <b>Rp{{number_format($data->ppob_price, 0)}}</b></div>
            </div>

        </div>

        @if ($data->status == 0)
        {{-- Waiting for Buyer Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Proses Pembayaran</h4>
            <p class="text-xs font-light text-gray-600">Silakan pilih metode pembayaran anda:</p>
        </div>
        {{-- Payment Options --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <h4 class="mt-2 text-md font-light text-gray-600">Pilih pembayaran:</h4>
            <div class="mt-2 shadow-md rounded-2xl">
                <div onclick="setPayment(1)" class="tab w-full overflow-hidden border-t">
                    <input class="absolute opacity-0" id="tab-single-one" type="radio" name="tab">
                    <label class="block p-5 leading-none cursor-pointer text-sm text-gray-600"
                        for="tab-single-one">Tunai</label>
                    <div class="tab-content overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-3">
                        <p class="p-3 text-xs font-light">Pembayaran Tunai langsung kepada penjual, memerlukan
                            konfirmasi
                            manual oleh penjual setelah
                            pembayaran lunas.</p>
                    </div>
                </div>
                <div onclick="setPayment(2)" class="tab w-full overflow-hidden border-t">
                    <input class="absolute opacity-0" id="tab-single-two" type="radio" name="tab">
                    <label class="block p-5 leading-none cursor-pointer text-sm text-gray-600" for="tab-single-two">eIDR
                        Internal</label>
                    <div class="tab-content overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-3">
                        <p class="p-3 text-xs font-light">Pembayaran langsung dengan saldo eIDR internal anda,
                            otomatis terkonfirmasi dan tuntas.</p>
                    </div>
                </div>

            </div>
        </div>
        @endif

        @if ($data->status == 1)
        {{-- Waiting for Seller Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Menunggu Konfirmasi Toko</h4>
            <p class="text-xs font-light text-gray-600">Silakan lakukan pembayaran TUNAI dan minta konfirmasi penjual.
            </p>
        </div>
        @endif

        @if ($data->status == 3)
        {{-- Canceled --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-red-600">Batal</h4>
            <p class="text-xs font-light text-gray-600">{{ $data->reason }}</p>
        </div>
        @endif

        @if ($data->status == 2)
        {{-- Done --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-green-600">Tuntas</h4>
            @php
            $payment = 'TUNAI (langsung melalui Toko)';
            if ($data->buy_metode == 2) {
            $payment = 'eIDR Internal';
            } elseif ($data->buy_metode == 3) {
            $payment = 'eIDR Eksternal';
            }
            $returnData = json_decode($data->return_buy, true);
            @endphp
            <p class="text-xs font-light text-gray-600">Metode Pembayaran: {{ $payment }}</p>

            @if ($data->type < 3 || $data->type > 20)
                <div class="text-xs font-light text-gray-600">SN: {{ $returnData['data']['sn'] }}</div>
                @endif
                @if ($data->type == 3)
                <div class="text-xs font-light text-gray-600">Token: {{ $returnData['data']['sn'] }}</div>
                @endif
        </div>
        @if ($data->type > 2 && $data->type < 11) {{-- Print Receipt button --}} <div class="mt-4 flex justify-end">
            <a href="{{ route('member.shopping.receipt', ['sale_id' => $data->id]) }}">
                <button
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Print
                    Struk</button>
            </a>
    </div>
    @endif
    @endif

    @if ($data->status == 5)
    {{-- Processing --}}
    <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
        <div class="text-xs text-gray-500">Status:</div>
        <h4 class="text-md text-yellow-600">Dalam Proses</h4>
        <div class="flex justify-center items-center p-4">
            <div>
                @include('member.components.ellipsis_spinner')
            </div>
        </div>

        <p class="text-xs font-light text-gray-600">Pesanan anda sedang diproses, mohon tunggu sebentar...</p>
    </div>
    @endif

    @if ($data->status == 0)
    {{-- Payment Summary --}}
    <div class="mt-4 nm-convex-gray-100 rounded-2xl px-4 py-2">
        <div class="mt-3 space-y-1">
            <div class="text-xs text-gray-500 font-medium">
                Total Tagihan:
            </div>
            <div class="text-lg text-gray-700 font-extralight">
                Rp{{number_format($data->ppob_price)}}
            </div>
        </div>

        {{-- Internal eIDR --}}
        <div id="int-eidr-info" class="hidden mt-1 space-y-1">
            <div class="text-xs text-gray-500 font-medium">
                Saldo eIDR internal:
            </div>
            <div class="text-md text-gray-700 font-extralight">
                {{ number_format($balance, 0) }} eIDR
            </div>
            <div id="int-eidr-warning" class="text-xs text-red-600 font-light">Saldo eIDR internal anda kurang,
                silakan <a class="text-purple-600 underline" href="{{ route('member.wallet') }}">melakukan
                    Deposit</a></div>
        </div>

    </div>

    {{-- Action Buttons --}}
    <div class="mt-3 flex justify-end space-x-1 items-center">
        <button id="pay-btn"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Konfirmasi</button>
        <button onclick="cancel()" id="cancel-btn"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batalkan</button>
    </div>

    {{-- eIDR payment form --}}
    <form action="{{ route('member.shopping.postShoppingConfirmDigitalOrderByEidr') }}" method="POST"
        id="eidr-payment-form">
        @csrf
        <input type="hidden" name="password" id="password">
        <input type="hidden" name="salesID" value="{{ $data->id }}">
        <input type="hidden" name="seller_id" id="seller_id" value="{{ $sellerArr['id'] }}">
    </form>
    @endif

</div>



</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<style>
    /* Tab content - closed */
    .tab-content {
        max-height: 0;
        -webkit-transition: max-height .35s;
        -o-transition: max-height .35s;
        transition: max-height .35s;
    }

    /* :checked - resize to full height */
    .tab input:checked~.tab-content {
        max-height: 100vh;
    }

    /* Label formatting when open */
    .tab input:checked+label {
        /*@apply text-xl p-5 border-l-2 border-indigo-500 bg-gray-100 text-indigo*/
        font-size: 1.25rem;
        /*.text-xl*/
        padding: 1.25rem;
        /*.p-5*/
        border-left-width: 2px;
        /*.border-l-2*/
        border-color: #6574cd;
        /*.border-indigo*/
        background-color: #f8fafc;
        /*.bg-gray-100 */
        color: #6574cd;
        /*.text-indigo*/
    }

    /* Icon */
    .tab label::after {
        float: right;
        right: 0;
        top: 0;
        display: block;
        width: 1.5em;
        height: 1.5em;
        line-height: 1.5;
        font-size: 1.25rem;
        text-align: center;
        -webkit-transition: all .35s;
        -o-transition: all .35s;
        transition: all .35s;
    }

    /* Icon formatting - closed */
    .tab input[type=checkbox]+label::after {
        content: "+";
        font-weight: bold;
        /*.font-bold*/
        border-width: 1px;
        /*.border*/
        border-radius: 9999px;
        /*.rounded-full */
        border-color: #b8c2cc;
        /*.border-grey*/
    }

    .tab input[type=radio]+label::after {
        content: "\25BE";
        font-weight: bold;
        /*.font-bold*/
        border-width: 1px;
        /*.border*/
        border-radius: 9999px;
        /*.rounded-full */
        border-color: #b8c2cc;
        /*.border-grey*/
    }

    /* Icon formatting - open */
    .tab input[type=checkbox]:checked+label::after {
        transform: rotate(315deg);
        background-color: #6574cd;
        /*.bg-indigo*/
        color: #f8fafc;
        /*.text-grey-lightest*/
    }

    .tab input[type=radio]:checked+label::after {
        transform: rotateX(180deg);
        background-color: #6574cd;
        /*.bg-indigo*/
        color: #f8fafc;
        /*.text-grey-lightest*/
    }
</style>
@endsection


@section('scripts')
@if ($data->status == 0)
<script>
    let payment = 0;
    const eIDRBalance = {{ $balance }};
        let href = window.location.href;
        const salesID = {{ $data->id }};
        const totalPrice = {{ $data->ppob_price }};
        let _token = "{{ csrf_token() }}";

    function setPayment(type) {
        payment = type;
        if (payment == 2) {
            if (eIDRBalance >= totalPrice) {
                $('#int-eidr-warning').remove();
            }
            $('#int-eidr-info').show();
        }  else {
            $('#int-eidr-info').hide();
        }
    }

    
        // Cancel
        function cancel() {
            Swal.fire({
                title: 'Batalkan Transaksi',
                text: "Apakah anda yakin untuk membatalkan transaksi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Batalkan!',
                cancelButtonText: 'Jangan'
            }).then((result) => {
                if (result.isConfirmed) {
                
                    Swal.fire('Sedang Memproses...');
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('ajax.shopping.postCancelDigitalShoppingPaymentBuyer') }}",
                        data: {
                            salesID:salesID,
                            _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                    'Dibatalkan',
                                    'Order belanja telah dibatalkan',
                                    'info'
                                )
                                let home = '{{ route('member.home') }}';
                                setTimeout(window.location.replace(home), 3000);
                                
                            } else {
                                Swal.fire(
                                    'Gagal',
                                    'Gagal Membatalkan',
                                    'error'
                                )
                                setTimeout(window.location.replace(href), 3000);
                            }
                        }
                    })
                }
            })
        }
    
        // Confirm btn
        $('#pay-btn').click( function () {
            if (payment == 0) {
                Swal.fire('Pilih Pembayaran!', 'Anda belum memilih metode pembayaran', 'warning');
                return false;
            }
            
            // Cash payment
            if (payment == 1) {
                let toko = $('#seller').text();
                let seller_id = $('#seller_id').val();

                Swal.fire({
                    title: 'Pembayaran TUNAI',
                    text: "Apakah anda ingin membayar Tunai di Toko " + toko + "?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                    
                        Swal.fire('Sedang Memproses...');
                        Swal.showLoading();
                        $.ajax({
                            type: "POST",
                            url: "{{ route('ajax.shopping.postDigitalShoppingPaymentCash') }}",
                            data: {
                                salesID:salesID,
                                seller_id:seller_id,
                                _token:_token
                            },
                            success: function(response){
                                if(response.success) {
                                    Swal.fire(
                                        'Berhasil',
                                        'Pesanan anda telah diteruskan ke Penjual, silakan konfirmasi pembayaran ke Penjual',
                                        'info'
                                    )
                                    setTimeout(window.location.replace(href), 3000);
                                    
                                } else {
                                    Swal.fire(
                                        'Gagal',
                                        'Ada yang salah, coba sesaat lagi.',
                                        'error'
                                    )
                                    setTimeout(window.location.replace(href), 3000);
                                }
                            }
                        })
                    }
                }) 
            } else if (payment == 2) {
            // internal eIDR
                Swal.fire({
                    title: 'Pembayaran eIDR',
                    text: "Apakah anda ingin membayar dari saldo eIDR internal anda?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const { value: password } = await Swal.fire({
                            title: 'Verifikasi 2FA',
                            html: '<input id="swal-input1" class="w-3/4 h-12 text-lg py-2 text-center justify-center bg-gray-200" inputmode="numeric" pattern="[0-9]*">',
                            focusConfirm: false,
                            showCancelButton: true,
                            preConfirm: () => {
                                return [
                                    document.getElementById('swal-input1').value,
                                ]
                            }
                        
                        })

                        if (password) {
                            let pw = JSON.stringify(password);
                            let pass = pw.replace(/[""\[\]]/g, '');
                            Swal.fire('Memproses...')
                            swal.showLoading();
                            $('#password').val(pass);
                            $('#eidr-payment-form').submit();
                        }
                    }
                })
            }
        })

        // Change Seller
        $(document).ready(function(){
            $("#get_id").keyup(function(){
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.shopping.getShopName') }}" + "?name=" + $(this).val() ,
                    success: function(data){
                        $("#get_id-box").show();
                        $("#get_id-box").html(data);
                    }
                });
            });
        });
        function selectUsername(val) {
            var valNew = val.split("___");
            $("#seller").html(valNew[1]);
            $("#get_id").val(valNew[1]);
            $("#seller_id").val(valNew[0]);
            $("#get_id-box").hide();
            $('#change-seller-div').hide();
            $('#change-seller').show();
            $('#seller-info').html('Dipilih manual oleh anda.');

        }

        $('#change-seller').click( function () {
            $('#change-seller-div').show();
            $('#change-seller').hide();
        })

    
</script>
@endif



@if ($data->status == 5)
<script>
    const salesID = {{ $data->id }};
    let href = window.location.href;
        $( function () {
            setInterval(checkStatus, 5000);

            function checkStatus() {
                console.log('check')
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.shopping.getCheckDigitalOrderStatus') }}",
                    data: {salesID:salesID},
                    success: function(response){
                        if (response.success) {
                            window.location.replace(href);
                        }
                    }
                });
            }
        })
</script>
@endif

@endsection