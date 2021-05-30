@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="px-2 my-1">
        {{-- Order Info --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl p-4">
            <h4 class="text-sm font-light text-gray-600">Pembeli: {{ $data->buyer->username }}</h4>
            <div class="text-xs font-extralight text-gray-500">{{ $data->ppob_code }}</div>
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

        @if ($data->status == 1)
        {{-- Waiting for Seller Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Menunggu Konfirmasi Toko</h4>
            <p class="text-xs font-light text-gray-600">Silakan tagih pembayaran TUNAI dari pembeli dan konfirmasi order
                ini dengan menekan tombol Konfirmasi di bawah.</p>
        </div>
        @endif

        @if ($data->status == 3)
        {{-- Canceled --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-red-600">Batal</h4>
            <p class="text-xs font-light text-gray-600">{{ $data->reason }}</p>
            @php
            $returnData = json_decode($data->return_buy, true);
            @endphp
            <p class="text-xs font-light text-gray-600">{{ $returnData['data']['message'] }}</p>
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

    @if ($data->status == 1)
    {{-- Payment Summary --}}
    <div class="mt-4 nm-convex-gray-100 rounded-2xl px-4 py-2">
        <div class="mt-3 space-y-1">
            <div class="text-xs text-gray-500 font-medium">
                Total Penjualan:
            </div>
            <div class="text-lg text-gray-700 font-extralight">
                Rp{{number_format($data->ppob_price)}}
            </div>
        </div>

        {{-- Internal eIDR --}}
        <div id="int-eidr-info" class="mt-1 space-y-1">
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
        <button id="pay-btn" disabled
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Konfirmasi</button>
        <button onclick="cancel()" id="cancel-btn"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batalkan</button>
    </div>

    {{-- Order Confirmation form --}}
    <form action="{{ route('member.store.postConfirmDigitalOrder') }}" method="POST" id="order-confirmation-form">
        @csrf
        <input type="hidden" name="password" id="password">
        <input type="hidden" name="salesID" value="{{ $data->id }}">
    </form>
    @endif



</div>



</div>



@include('member.components.mobile_sticky_nav')

@endsection


@section('scripts')
@if ($data->status == 1)
<script>
    const inteIDRbalance = {{ $balance }};
        let href = window.location.href;
        const salesID = {{ $data->id }};
        const totalPrice = {{ $data->ppob_price }};
        let _token = "{{ csrf_token() }}";

        $( function () {
            let remaining = inteIDRbalance - totalPrice;
            if (remaining > 0) {
                $('#int-eidr-warning').remove();
                $('#pay-btn').prop("disabled", false);
             }
        })
    
    
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
                        url: "{{ route('ajax.store.postStoreCancelDigitalOrder') }}",
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
                                setTimeout(window.location.replace(href), 3000);
                                
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
            Swal.fire({
                    title: 'Konfirmasi Order',
                    text: "Apakah anda ingin mengonfirmasi pesanan ini?",
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
                            input: 'number',
                            inputLabel: 'Masukkan Pin 2FA anda',
                            inputPlaceholder: 'Pin 2FA',
                            showCancelButton: true,
                            inputAttributes: {
                                maxlength: 10,
                                autocapitalize: 'off',
                                autocorrect: 'off'
                            }
                        })

                        if (password) {
                            swal.showLoading();
                            $('#password').val(password);
                            $('#order-confirmation-form').submit();
                        }
                    
                        
                    }
                })
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