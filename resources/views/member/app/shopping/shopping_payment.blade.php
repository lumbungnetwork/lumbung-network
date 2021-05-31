@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="px-2 my-1">
        {{-- Shopping Cart Info --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl p-4">
            <h4 class="text-sm font-light text-gray-600">Penjual: <a
                    href="{{ route('member.shop', ['id' => $masterSalesData->stockist_id]) }}">{{ $shopName }}</a></h4>
            <div class="text-xs font-extralight text-gray-500">Invoice: {{ $masterSalesData->invoice }}</div>
            <div class="text-xs font-extralight text-gray-500">Date:
                {{ date('d-m-Y', strtotime($masterSalesData->created_at)) }}</div>


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

        @if ($masterSalesData->status == 0)
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
                        <p class="p-3 text-xs font-light">Pembayaran langsung memotong saldo eIDR internal anda,
                            otomatis terkonfirmasi dan tuntas.</p>
                    </div>
                </div>
                <div onclick="setPayment(3)" class="tab w-full overflow-hidden border-t">
                    <input class="absolute opacity-0" id="tab-single-three" type="radio" name="tab">
                    <label class="block p-5 leading-none cursor-pointer text-sm text-gray-600"
                        for="tab-single-three">eIDR
                        Eksternal</label>
                    <div class="tab-content overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-3">
                        <p class="p-3 text-xs font-light">
                            Pastikan anda menggunakan aplikasi yang mendukung Web3 seperti Tronlink atau TokenPocket,
                            tekan tombol Bayar di bawah untuk melakukan pembayaran instan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($masterSalesData->status == 1)
        {{-- Waiting for Seller Confirmation --}}
        <div class="mt-4 nm-flat-gray-200 rounded-2xl px-4 py-2">
            <div class="text-xs text-gray-500">Status:</div>
            <h4 class="text-md text-yellow-500">Menunggu Konfirmasi Toko</h4>
            <p class="text-xs font-light text-gray-600">Silakan lakukan pembayaran Tunai dan minta konfirmasi Toko</p>
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

        @if ($masterSalesData->status == 0)
        {{-- Payment Summary --}}
        <div class="mt-4 nm-convex-gray-100 rounded-2xl px-4 py-2">
            <div class="mt-3 space-y-1">
                <div class="text-xs text-gray-500 font-medium">
                    Total Pembayaran:
                </div>
                <div class="text-md text-gray-700 font-extralight">
                    Rp{{number_format($masterSalesData->total_price)}}
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
            {{-- External eIDR --}}
            <div id="ext-eidr-info" class="hidden mt-1 space-y-1">
                <div class="text-xs text-gray-500 font-medium">
                    Saldo eIDR eksternal:
                </div>
                <div id="eidr-balance" class="text-md text-gray-700 font-extralight">
                    0 eIDR
                </div>
                <div id="showAddress" class="text-xs font-light"></div>
                <div id="eidr-warning" class="text-xs text-red-600 font-light">Untuk menggunakan pembayaran eIDR
                    eksternal, anda perlu menggunakan aplikasi pendukung Web3 seperti Tronlink atau TokenPocket</div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-3 flex justify-end space-x-1 items-center">
            <button id="pay-btn"
                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Konfirmasi</button>
            <button onclick="cancel()" id="cancel-btn"
                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batal</button>
        </div>
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
@if ($masterSalesData->status == 0)
<script>
    let payment = 0;
        let exteIDRbalance = 0;
        const inteIDRbalance = {{ $balance }};
        let href = window.location.href;
        const masterSalesID = {{ $masterSalesData->id }};
        const totalPrice = {{ $masterSalesData->total_price }};
        let _token = "{{ csrf_token() }}";
    
        function setPayment(type) {
            payment = type;
            if (payment == 2) {
                if (inteIDRbalance >= totalPrice) {
                    $('#int-eidr-warning').remove();
                }
                $('#int-eidr-info').show();
                $('#ext-eidr-info').hide();
            } else if (payment == 3) {
                $('#int-eidr-info').hide();
                $('#ext-eidr-info').show();
            } else {
                $('#int-eidr-info').hide();
                $('#ext-eidr-info').hide();
            }
        }
    
        //TronWeb
        const toAddress = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        var userAddress, tronWeb;
        
        $(document).ready(function () {
            setTimeout(main, 200);
        });
    
        var waiting = 0;
        
        async function main() {
            if (!(window.tronWeb && window.tronWeb.ready)) return (waiting += 1, 50 == waiting) ? void console.log('Failed to connect to TronWeb') : (console.warn("main retries", "Could not connect to TronLink.", waiting), void setTimeout(main,
            500));
            tronWeb = window.tronWeb;
            try {
                await showTronBalance();
            } catch (a) {
                console.log(a);
            }
        
        }
        
        function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }
        
        //show eIDR balance
        async function showTronBalance() {
            userAddress = tronWeb.defaultAddress.base58;
            let tokenBalancesArray;
            let balanceCheck = await tronWeb.trx
                .getAccount(userAddress)
                .then((result) => (tokenBalancesArray = result.assetV2));
                balanceCheck;
            let eIDRexist = await tokenBalancesArray.some(function (tokenID) {
                return tokenID.key == "1002652";
            });
            if (eIDRexist) {
                let eIDRarray = await tokenBalancesArray.find(function (tokenID) {
                    return tokenID.key == "1002652";
                });
    
                exteIDRbalance = eIDRarray.value / 100;
                
                $("#eidr-balance").html(
                `Rp${exteIDRbalance.toLocaleString("en-US")}
                `
                );
                
                $("#showAddress").html(`Active Wallet: ${shortId(userAddress, 5)} `);
                $('#eidr-warning').remove();
                
            } else {
                $("#eidr-balance").html(`Alamat TRON ini tidak memiliki eIDR`);
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
                        url: "{{ route('ajax.shopping.postCancelShoppingPaymentBuyer') }}",
                        data: {
                            masterSalesID:masterSalesID,
                            _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                    'Dibatalkan',
                                    'Keranjang belanja telah dibatalkan',
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
    
        // Confirm btb
        $('#pay-btn').click( async function () {
            if (payment == 0) {
                Swal.fire('Pilih Pembayaran!', 'Anda belum memilih metode pembayaran', 'warning');
                return false;
            }
            
            // Cash payment
            if (payment == 1) {
                Swal.fire({
                    title: 'Pembayaran TUNAI',
                    text: "Apakah anda ingin membayar Tunai di Toko?",
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
                            url: "{{ route('ajax.shopping.postShoppingPaymentCash') }}",
                            data: {
                                masterSalesID:masterSalesID,
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
                }).then((result) => {
                    if (result.isConfirmed) {
                    
                        Swal.fire('Sedang Memproses...');
                        Swal.showLoading();
                        $.ajax({
                            type: "POST",
                            url: "{{ route('ajax.shopping.postShoppingPaymentInternaleIDR') }}",
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
            } else if (payment == 3) {
            // external eIDR
                const sendAmount = totalPrice * 100;

                if (sendAmount > 0) {
                    try {
                        var tx = await tronWeb.trx.sendToken(
                            toAddress,
                            sendAmount,
                            "1002652"
                        );
                        
                        if (tx.transaction.txID !== undefined) {
                            postAJAXtronweb(tx.transaction.txID);
                        } else {
                            eAlert('Transaksi Gagal, periksa koneksi dan ulangi kembali');
                        }
                    } catch (e) {
                        if (e.includes("assetBalance is not sufficient")) {
                            eAlert("Saldo LMB tidak mencukupi");
                        } else if (e.includes("assetBalance must be greater than")) {
                            eAlert("Alamat TRON ini tidak memiliki LMB");
                        } else if (e.includes("declined by user")) {
                            eAlert("Anda membatalkan Transaksi");
                        } else if (e.includes("cancle")) {
                            eAlert("Anda membatalkan Transaksi");
                        } else {
                            eAlert("Ada yang salah, restart aplikasi wallet ini.")
                        }
                    }
                } else {
                    eAlert("Jumlah LMB harus diisi.");
                }
            }
        })

        // confirm TRON tx
        function postAJAXtronweb(hash) {
            Swal.fire('Sedang Memproses...');
            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "{{ route('ajax.shopping.postShoppingPaymentExternaleIDR') }}",
                data: {
                    masterSalesID:masterSalesID,
                    hash:hash,
                    _token:_token
                },
                success: function(response){
                    if(response.success) {
                        Swal.fire(
                            'Tuntas!',
                            'Pembayaran berhasil, proses belanja telah tuntas!',
                            'success'
                        )
                        
                        setTimeout(location.replace(href), 3000);
                    } else {
                        Swal.fire(
                            'Oops!',
                            response.message,
                            'error'
                        )
                        
                        setTimeout(location.replace(href), 5000);
                    }
                
                }
            })
        }
    
        // utilities
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
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
    
        function eAlert(message) {
            Swal.fire({
                title: 'Gagal!',
                text: message,
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace(href);
                }
            })
        }
    
</script>
@endif

@endsection