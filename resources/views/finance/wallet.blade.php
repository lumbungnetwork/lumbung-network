@extends('finance.layout.app')
@section('content')
@if ($user->loginSecurity == null)
<div class="p-2 bg-yellow-300">
    <p class="text-center font-light">It's strongly recommended to activate 2FA to protect your account and fund!. <a
            class="underline" href="{{ route('finance.2fa') }}">Set 2FA now!</a></p>
</div>
@endif


<div class="mt-8 flex flex-col justify-center px-2 sm:px-6">

    <div class="relative w-full max-w-md mx-auto">

        <form id="logout-form" action="{{ url('/logout') }}" method="POST">
            @csrf
            <div class="flex float-right mr-6 text-xl text-red-500 nm-convex-gray-50 rounded-full px-3 py-2">

                <button type="submit"><i class="fa fa-power-off" aria-hidden="true"></i></button>
            </div>
        </form>

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

            <div class="mt-4 nm-convex-gray-50 rounded-xl p-4 text-center">
                <div class="mb-4 flex justify-end">
                    @if ($user->active_credits == 0)
                    <button id="activate-btn"
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-gray-300 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Activate</button>
                    @else
                    <button disabled
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-yellow-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Active</button>
                    @endif

                </div>
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

    $('#activate-btn').click( function () {
        if (creditBalance < 100) {
            Swal.fire('Insufficient Credits', 'Minimum 100 USD Credits needed to activate daily yield', 'error');
            return false;
        } else {
            $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getCreditActivate') }}",
            success: function(response){
                if (response.success) {
                    Swal.fire('Activated!', 'Credits activated, you will start receiving yields', 'success');
                    Swal.fire({
                        title: 'Activated?',
                        text: "Credits activated, you will start receiving yields!",
                        icon: 'success',
                        confirmButtonColor: '#56c759',
                        confirmButtonText: 'OK!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let href = window.location.href;
                            location.replace(href);
                        }
                    })
                } else {
                    Swal.fire('Failed', response.message, 'error');
                }

                
            }
        });
        }
    })

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

                // Check amount
                const max = creditBalance;
                var amount = $('input[name="amount"]');
                amount.on('keyup', function () {
                    if(amount.val() > max) {
                        amount.val(max.toFixed(2));
                    }
                    if(amount.val() >= 2 && amount.val() <= 100) {
                        var converted = parseFloat(amount.val() - 1);
                        $('#converted').html('Converted amount: $' + converted.toFixed(2) );
                    } else if (amount.val() > 100) {
                        var fee = amount.val() * (1 / 100);
                        var converted = parseFloat(amount.val() - parseFloat(fee));
                        $('#converted').html('Converted amount: $' + converted.toFixed(2) );
                    } else {
                        $('#converted').html('Converted amount: $0');
                    }
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
        // Check minimum $1 credit balance to transfer
        if (creditBalance < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimum $1 available balance for transfer!'
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
                
                // Check amount
                const max = creditBalance - 0.3;
                var amount = $('input[name="amount"]');
                amount.on('keyup', function () {
                    if(amount.val() > max) {
                        amount.val(max.toFixed(2));
                    }
                    if(amount.val() >= 0.7) {
                        var totalDebit = parseFloat(amount.val()) + 0.3;
                        $('#totalDebit').html('Total Debit: $' + totalDebit.toFixed(2) );
                        $('#totalDebit').show();
                    }else {
                        $('#totalDebit').hide();
                    }
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