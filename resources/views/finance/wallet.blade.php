@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-6">

    <div class="relative w-full max-w-md mx-auto">

        <div class="relative nm-flat-gray-200 rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="flex float-right mr-6 text-xl text-red-500">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="mt-5"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                </form>

            </div>

            <div class="flex items-center justify-start pt-6 pl-6">
                <a href="/dashboard">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Dashboard</span>
                </a>

            </div>

            <div class="px-4 sm:px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-3xl sm:text-4xl text-gray-600">Wallet Balances</h2>

                </div>

                <div class="mt-4 nm-concave-gray-50 rounded-xl p-6 text-center">
                    <p>USDT (Tether USD)</p>
                    <h2 class="mt-3 text-black text-3xl sm:text-6xl font-extralight">{{number_format($USDTbalance, 2)}}
                    </h2>
                    <div class="flex justify-center">
                        <a href="/wallet/deposit-usdt"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl w-24 text-white text-xs focus:outline-none focus:bg-gray-600">Deposit</a>
                        <button type="button" id="withdraw-btn"
                            class="ml-2 mt-3 p-3 bg-gray-500 rounded-2xl w-24 text-white text-xs focus:outline-none focus:bg-gray-600">Withdraw</button>
                    </div>
                    <a onclick="history('usdt')" class="underline mt-5 text-md font-light block">History</a>


                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 text-center">
                    <p>Credits (USD)</p>
                    <h2 class="mt-3 text-black text-3xl sm:text-6xl font-extralight">
                        {{number_format($creditBalance, 2)}}</h2>
                    <div class="flex flex-wrap py-2 justify-center">
                        <button type="button" id="convert-from-usdt-btn" disabled
                            class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Convert
                            from USDT</button>
                        <button type="button" id="convert-to-usdt-btn" disabled
                            class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Convert
                            to USDT</button>
                    </div>

                    <div class="flex flex-wrap py-2 justify-center">
                        <button type="button" id="transfer-btn"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Transfer</button>
                        <button type="button" id="receive-btn"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Receive</button>

                    </div>
                    <a onclick="history('credit')" class="underline mt-5 text-md font-light block">History</a>


                </div>

            </div>

        </div>
    </div>


</div>


@endsection


@section('scripts')
<script>
    const USDTbalance = {{$USDTbalance}};
    const creditBalance = {{$creditBalance}};

    $(function() {
        if (USDTbalance > 1) {
            $('#convert-from-usdt-btn').prop("disabled", false)
        }
        if (creditBalance > 1) {
            $('#convert-to-usdt-btn').prop("disabled", false)
        }

    });

    $('#convert-from-usdt-btn').click( function() {
        // Check minimum $2 USDT balance to convert
        if (USDTbalance < 2) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimum $2 for conversion!'
            });
            return false;
        }
        $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getConvertFromUSDT') }}",
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            }
        });
    })

    $('#convert-to-usdt-btn').click( function() {
        // Check minimum $2 credit balance to convert
        if (creditBalance < 2) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimum $2 for conversion!'
            });
            return false;
        }
        $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getConvertToUSDT') }}",
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            }
        });
    })

    $('#receive-btn').click( function() {
        Swal.fire({
            icon: 'info',
            title: 'Receive',
            text: 'To Receive Credit transfers, you just need to provide your Username to the sender as destination address.'
        })
    })

    $('#transfer-btn').click( function() {
        // Check minimum $2 credit balance to convert
        if (creditBalance < 2) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimum $2 available balance for transfer!'
            });
            return false;
        }
        $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getCreditTransfer') }}",
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            }
        });
    })

    $('#withdraw-btn').click( function() {
        // Check minimum $2 USDT balance to convert
        if (USDTbalance < 2) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimum $2 for withdraw!'
            });
            return false;
        }
        $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getUSDTWithdraw') }}",
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            }
        });
    })

    function history(type) {
        $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getWalletHistory') }}",
            data: {type:type},
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            }
        }); 
    }
</script>
@endsection