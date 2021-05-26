@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="px-2 my-1">
        {{-- Shopping Cart Info --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl p-4">
            <h4 class="text-sm font-light text-gray-600">Pembeli: {{ $masterSalesData->buyer->username }}</h4>
            <div class="text-xs font-extralight text-gray-500">Invoice: {{ $masterSalesData->invoice }}</div>
            <div class="text-xs font-extralight text-gray-500">Date:
                {{ date('d-M-y', strtotime($masterSalesData->created_at)) }}</div>


        </div>

        <div class="mt-4 nm-inset-gray-100 rounded-2xl p-2 overflow-x-scroll">
            <div class="p-2">
                @if (count($salesData) > 0)
                @foreach ($salesData as $row)
                <div class="flex justify-between space-x-1">
                    <div class="w-1/4">
                        <img class="object-cover rounded-2xl"
                            src="{{ asset('/storage/products') }}/{{$row->product->image}}" alt="product-picture">
                    </div>
                    <div class="w-3/4">
                        <div class="text-sm text-gray-800">
                            {{$row->product->name}}
                        </div>
                        <div class="text-xs text-gray-500 font-light"><b>{{ number_format($row->amount, 0) }}x</b>
                            {{$row->product->size}}</div>
                        <div class="text-xs font-light">Rp{{number_format(($row->sale_price/$row->amount))}}</div>
                        <hr class="my-1 border-b-0 border-gray-300">
                        <div class="text-xs text-right">Subtotal: Rp{{number_format($row->sale_price)}}</div>
                    </div>
                </div>
                <hr class="my-2 border-b-0 border-gray-400">
                @endforeach
                <div class="text-sm font-medium text-right">Total:
                    <b>Rp{{number_format($masterSalesData->total_price)}}</b></div>
                @endif
            </div>

        </div>

        @if ($masterSalesData->status == 1)
        {{-- Waiting for Seller Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Menunggu Konfirmasi Toko</h4>
            <p class="text-xs font-light text-gray-600">Silakan tagih pembayaran TUNAI dari pembeli dan serah terima
                produk, setelahnya konfirmasi order ini dengan menekan tombol Konfirmasi di bawah.</p>
        </div>
        @endif

        @if ($masterSalesData->status == 10)
        {{-- Canceled --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-red-600">Batal</h4>
            <p class="text-xs font-light text-gray-600">{{ $masterSalesData->reason }}</p>
        </div>
        @endif

        @if ($masterSalesData->status == 2)
        {{-- Done --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-green-600">Tuntas</h4>
            @php
            $payment = 'TUNAI';
            if ($masterSalesData->buy_metode == 2) {
            $payment = 'eIDR Internal';
            } elseif ($masterSalesData->buy_metode == 3) {
            $payment = 'eIDR Eksternal';
            }
            @endphp
            <p class="text-xs font-light text-gray-600">Metode Pembayaran: {{ $payment }}</p>
        </div>
        @endif

        @if ($masterSalesData->status == 1)
        {{-- Payment Summary --}}
        <div class="mt-4 nm-convex-gray-100 rounded-2xl px-4 py-2">
            <div class="mt-3 space-y-1">
                <div class="text-xs text-gray-500 font-medium">
                    Total Penjualan:
                </div>
                <div class="text-md text-gray-700 font-extralight">
                    Rp{{number_format($masterSalesData->total_price)}}
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <div class="text-xs text-gray-500 font-medium">
                    Kontribusi Bagi Hasil (2%):
                </div>
                @php
                $royalty = $masterSalesData->total_price * (2/100);
                @endphp
                <div class="text-md text-gray-700 font-extralight">
                    Rp{{number_format($royalty)}}
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
        @endif



    </div>



</div>



@include('member.components.mobile_sticky_nav')

@endsection


@section('scripts')
@if ($masterSalesData->status == 1)
<script>
    const inteIDRbalance = {{ $balance }};
        let href = window.location.href;
        const masterSalesID = {{ $masterSalesData->id }};
        const totalPrice = {{ $masterSalesData->total_price }};
        const royalty = totalPrice * 2/100;
        let _token = "{{ csrf_token() }}";

        $( function () {
            let remaining = inteIDRbalance - royalty;
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
                        url: "{{ route('ajax.store.postStoreCancelPhysicalOrder') }}",
                        data: {
                            masterSalesID:masterSalesID,
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
        $('#pay-btn').click( async function () {
            
            Swal.fire({
                title: 'Konfirmasi Belanja',
                text: "Apakah anda ingin mengonfirmasi order ini dan membayarkan kontribusi bagi hasil?",
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
                        url: "{{ route('ajax.store.postStoreConfirmPhysicalOrder') }}",
                        data: {
                        masterSalesID:masterSalesID,
                        _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                'Berhasil',
                                'Pembayaran berhasil, proses belanja telah tuntas!',
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
        })

    
</script>
@endif

@endsection