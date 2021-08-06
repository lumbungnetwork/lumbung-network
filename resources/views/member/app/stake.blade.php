@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Total Staked LMB --}}
    <div class="mt-3 py-2 px-4">
        <div
            class="bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl px-2 py-2 flex justify-around items-center">
            <div class="flex flex-col align-middle items-center">
                <img class="object-contain w-16" src="/image/koin_lmb2.png" alt="koin LMB">

            </div>
            <div class="space-y-2">

                <h2 class="font-medium text-gray-600 text-xs text-right">Total Staked LMB</h2>
                <p class="font-extralight text-xl text-center">
                    {{number_format(round($totalStakedLMB, 2, PHP_ROUND_HALF_DOWN), 2)}} LMB</p>
                <div class="my-1 flex justify-end">
                    <a href="{{ route('member.stakeLeaderboard') }}"
                        class="text-xs font-light text-blue-400 text-right">Leaderboard</a>
                </div>

            </div>
        </div>
    </div>

    {{-- Dividend Pool --}}
    <div class="mt-1 py-2 px-4">
        <a href="{{ route('member.stakeDivPoolHistory') }}">
            <div class="nm-inset-gray-100 rounded-2xl py-3 w-full text-center">
                <h3 class="font-medium text-gray-600 text-xs">LMB Dividend Pool</h3>
                <p class="font-light text-yellow-400 text-xl">Rp{{ number_format($LMBDividendPool) }}</p>
                <p class="font-light text-xs">Next dist:
                    <span>Rp{{ number_format($LMBDividendPool * 2/100) }}</span>
                </p>
            </div>
        </a>

    </div>

    <div class="mt-1 py-2 px-4">
        {{-- Active Wallet info --}}
        <div class="mb-0" id="showAddress"></div>
        <div class="availableLMB"></div>
        <p id="tronweb-warning" class="text-xs font-medium text-red-600">Gunakan Tronlink, TokenPocket atau Browser DAPP
            lainnya.</p>

        {{-- Stake info --}}
        <div class="mt-2 nm-flat-gray-50 rounded-2xl p-2 flex justify-between">
            <div>
                <p class="text-xs text-gray-500">Your Stake</p>
                <p class="text-md font-light text-gray-700">{{number_format($userStakedLMB)}} LMB</p>
                <small class="text-xs font-light text-gray-500">({{number_format($userStakedLMB/$totalStakedLMB * 100, 2)}}%)</small>
                @if ($userUnstaking)
                <p class="text-sm font-medium text-yellow-500">Unstaking Process: {{number_format($userUnstaking, 2)}} LMB</p>
                @endif

            </div>
            <div class="my-1 pt-2">
                <div class="flex justify-center space-x-1 items-center">
                    <button id="stake-btn" disabled
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Stake</button>
                    <button id="unstake-btn" disabled
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Unstake</button>
                </div>
                <div class="mt-1 flex justify-end">
                    <a href="{{ route('member.stakeHistory') }}" class="text-xs font-light text-blue-400">History</a>
                </div>
            </div>
        </div>

        {{-- Dividend info --}}
        <div class="mt-2 nm-flat-gray-50 rounded-2xl p-2 flex justify-between">
            <div>
                <p class="text-xs text-gray-500">Your Dividend</p>
                <p class="text-md font-light text-gray-700">{{number_format($userDividend->net)}} eIDR</p>
                <div>
                    <a href="{{ route('member.stakeDivHistory') }}" class="text-xs font-light text-blue-400">History</a>
                </div>
            </div>
            <div class="flex justify-center space-x-1 items-center">
                <button id="claim-btn"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Claim</button>
            </div>
        </div>

        {{-- Total Claimed info --}}
        <div class="mt-2 nm-flat-gray-50 rounded-2xl p-2 flex justify-start">
            <div>
                <p class="text-xs text-gray-500">Total Claimed</p>
                <p class="text-md font-light text-gray-700">{{number_format($userDividend->claimed)}} eIDR</p>
                <div>
                    <a href="{{ route('member.stakeClaimedDivHistory') }}"
                        class="text-xs font-light text-blue-400">History</a>
                </div>
            </div>
        </div>
    </div>


</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    var _LMBbalance = 0;
    let _token = '{{ csrf_token() }}';
    const netDividend = {{$userDividend->net}};
    const userStakedLMB = {{$userStakedLMB}};
    
    // Doc ready func
    $(document).ready(function () {
        setTimeout(function () {
            main();
            if (userStakedLMB > 0) {
                $('#unstake-btn').attr("disabled", false);
            }
        }, 200)
    });

    //TronWeb
    var toAddress = 'TY8JfoCbsJ4qTh1r9HBtmZ88xQLsb6MKuZ';
    var userAddress, tronWeb;
    let sendAmount = 0;
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
    
    //show LMB balance
    async function showTronBalance() {
        userAddress = tronWeb.defaultAddress.base58;
        let tokenBalancesArray;
        let balanceCheck = await tronWeb.trx
        .getAccount(userAddress)
        .then((result) => (tokenBalancesArray = result.assetV2));
        balanceCheck;

        let LMBexist = await tokenBalancesArray.some(function (tokenID) {
        return tokenID.key == "1002640";
        });

        $("#showAddress").html(`<p class='text-gray-600 text-xs font-light'>Active Wallet: <span
                class='font-medium text-sm'>${shortId(userAddress, 5)}</span></p> `);

        if (LMBexist) {
            let LMBarray = await tokenBalancesArray.find(function (tokenID) {
            return tokenID.key == "1002640";
            });
            let LMBbalance = LMBarray.value / 1000000;
            
            if (LMBbalance > 0) {
                $('#stake-btn').attr("disabled", false);
                $('#tronweb-warning').remove();
                _LMBbalance = LMBbalance;
            } else {
                $('#tronweb-warning').html('No LMB Available');
            }
            
            $(".availableLMB").html(`<small class='text-gray-500 font-light'>Available: ${LMBbalance.toLocaleString("en-US")} LMB`);
        
        } else {
            $("#tronweb-warning").html(`Alamat TRON ini tidak memiliki LMB`);
        }
    }

    $('#stake-btn').click( function() {
        
        $.ajax({
            type: "GET",
            url:"{{ route('ajax.stake.add') }}",
            data:{max:_LMBbalance},
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })

                let amount = 0;

                $( "#inputLMB" ).on('mouseup keyup', function() {
                    var max = _LMBbalance;
                    var min = 0;
                    if ($(this).val() > max)
                    {
                        $(this).val(max);
                    }
                    else if ($(this).val() < min)
                    {
                        $(this).val(min);
                    }
                    amount = $(this).val();       
                });

                //Pay using TronWeb service
                $("#stake-add-btn").click(async function () {
                    Swal.fire('Konfirmasi...');
                    swal.showLoading();

                    sendAmount = amount * 1000000;
                    if (sendAmount > 0) {
                        try {
                            var tx = await tronWeb.trx.sendToken(
                                toAddress,
                                sendAmount,
                                "1002640"
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
                    
                    
                });

                function postAJAXtronweb(hash) {
                    Swal.fire('Sedang Memproses...');
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('ajax.stake.confirm') }}",
                        data: {
                        amount:amount,
                        hash:hash,
                        _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                'Success!',
                                'LMB successfully staked!',
                                'success'
                                )
                                let href = window.location.href;
                                setTimeout( () => location.replace(href), 3000);
                            } else {
                                Swal.fire(
                                'Oops!',
                                response.message,
                                'error'
                                )
                                let href = window.location.href;
                                setTimeout( () => location.replace(href), 5000);
                            }

                        }
                    })
                }
                
            }
        });
    })

    function inputMax() {
        $('#inputLMB').val(_LMBbalance);
    }
    
    //TronWeb Validation by Swal
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
                let href = window.location.href;
                setTimeout( () => location.replace(href), 500);
            }
        })
    }
    
    
    $('#unstake-btn').click( function () {
        $.ajax({
            type: "GET",
            url:"{{ route('ajax.stake.substract') }}",
            data:{max:userStakedLMB},
            success: function(url) {
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })

                let amount = 0;

                $( "#outputLMB" ).on('mouseup keyup', function() {
                    var max = userStakedLMB;
                    var min = 0;
                    if ($(this).val() > max)
                    {
                        $(this).val(max);
                    }
                    else if ($(this).val() < min)
                    {
                        $(this).val(min);
                    }
                    amount = $(this).val();       
                });

                $('#stake-substract-btn').click( function() {
                    
                    if (amount > 0) {
                        Swal.fire('Sedang Memproses...');
                        Swal.showLoading();
                        $.ajax({
                            type: "POST",
                            url: "{{ route('ajax.unstake') }}",
                            data: {
                            amount:amount,
                            _token:_token
                            },
                            success: function(response){
                                if(response.success) {
                                    Swal.fire(
                                    'Success!',
                                    'LMB Unstaking Process started, maturing in next 7 days!',
                                    'success'
                                    )
                                    let href = window.location.href;
                                    setTimeout( () => location.replace(href), 3000);
                                } else {
                                    Swal.fire(
                                    'Oops!',
                                    response.message,
                                    'error'
                                    )
                                    let href = window.location.href;
                                    setTimeout( () => location.replace(href), 5000);
                                }

                            }
                        })
                    } else {
                        eAlert("Amount need to be filled");
                    }
                    
                })
            }
        });
    });
    

    

    function outputMax() {
        $('#outputLMB').val(userStakedLMB);
    }

    $('#claim-btn').click( function() {
        const amount = netDividend;
        if (amount >= 1000) {
            Swal.fire('Sedang Memproses...');
            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "{{ route('ajax.claim.stakingDividend') }}",
                data: {
                amount:amount,
                _token:_token
                },
                success: function(response){
                    if(response.success) {
                        Swal.fire(
                        'Success!',
                        'Dividend Claimed Succesfully!',
                        'success'
                        )
                        let href = window.location.href;
                        setTimeout( () => location.replace(href), 3000);
                    } else {
                        Swal.fire(
                        'Oops!',
                        response.message,
                        'error'
                        )
                        let href = window.location.href;
                        setTimeout( () => location.replace(href), 5000);
                    }

                }
            })
        } else {
            eAlert("Minimum Rp1000 to claim");
        }
        
    })
</script>
@endsection