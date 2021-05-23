@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3">

    <div class="relative w-full max-w-md mx-auto">

        <div class="relative nm-flat-gray-50 rounded-3xl">

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
                <a href="{{route('finance.account')}}">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Back to Account</span>
                </a>

            </div>



            <div class="px-4 py-6">
                @if ($user->is_active == 0)
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">{{$title}}</h2>

                </div>


                <div class="mt-5">
                    <p class="text-sm font-light">To activate your account, you need to <strong>burn</strong> specific
                        amount of LMB.
                        <br>
                        LMB is a token that captures and represents the network value in Lumbung Network ecosystems and
                        protocols. <br>
                        <strong>12.5% of every fee</strong> in Lumbung Finance will contribute to LMB Dividend Pool.
                        <br><br>
                        You can trade LMB (TRC20) in JustSwap DEX or the TRC10 by Peer-to-peer trade with other members.
                    </p>
                </div>


                <div class="mt-3 nm-inset-gray-200 rounded-2xl p-6 text-center">
                    <h5 class="text-lg font-light text-gray-600">LMB needed to Activate</h5>
                    <img class="object-contain mx-auto max-h-14"
                        src="{{Config::get('services.app.protocol_url')}}/image/koin_lmb.png" alt="">
                    <h4 class="text-2xl font-medium text-yellow-400">10 LMB</h4>
                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <img class="w-14 float-right" src="/image/tron-logo.png" alt="tron logo">
                    <div class="hidden" id="active-info">
                        <div class="flex items-center py-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            <p class="text-xs font-light ">Active</p>&nbsp;
                            <p class="text-xs font-thin " id="showAddress"></p>
                        </div>
                    </div>
                    <div class="" id="inactive-info">
                        <div class="flex items-center py-2">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            <p class="text-sm font-light ">Inactive</p><br>

                        </div>
                        <p class="mt-2 text-sm font-light text-red-600">Use Tronlink or Dapp Enabled Browser</p>
                    </div>

                    <div class="font-light text-sm text-green-600" id="LMBTRC10balance">Available 0 LMB (TRC10)</div>
                    <div class="font-light text-sm text-green-600" id="LMBTRC20balance">Available 0 LMB (TRC20)</div>
                    <div class=" p-2 flex justify-between">
                        <button type="button" onclick="burn('trc10')"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600"
                            id="trc10-btn">TRC10</button>
                        <button type="button" onclick="burn('trc20')"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600"
                            id="trc20-btn">TRC20</button>
                    </div>
                    <p class="text-sm text-center text-gray-400">Click on the type of LMB you want to burn.</p>



                    <div>
                        <form action="{{ route('finance.account.activate.postTRC10') }}" method="POST"
                            id="trc10-confirm-form">
                            @csrf
                            <input type="hidden" id="hash" name="hash" value="0">
                        </form>
                    </div>

                </div>

                @else
                <div class="flex nm-concave-gray-100 rounded-2xl justify-center p-6">
                    <span class="w-4 h-4 bg-green-400 rounded-full mr-2"></span>
                    <p class="text-xl text-center text-green-500">Your account is Active.</p>
                </div>
                @endif
            </div>

        </div>
    </div>


</div>


@endsection

@if ($user->is_active == 0)
@section('scripts')
<script>
    let lmbtrc10balance = 0;
            let lmbtrc20balance = 0;
            let _token = '{{ csrf_token() }}';
            
                
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
                            window.location.reload(true);
                        }
                    })
                }
                
                //TronWeb
                const toAddress = 'TLsV52sRDL79HXGGm9yzwKibb6BeruhUzy';
                const USDTcontract = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";
                var userAddress, tronWeb;
                let sendAmount = 0;
                
                $(document).ready(function () {
                    setTimeout(function () {
                        main()
                    }, 200)
                    console.log('ready');
            
                });
                
                
                var waiting = 0;
                
                async function main() {
                    if (!(window.tronWeb && window.tronWeb.ready)) return (waiting += 1, 50 == waiting) ? void console.log('Failed to connect to TronWeb') : (console.warn("main retries", "Could not connect to TronLink.", waiting), void setTimeout(main,
                    500));
                    tronWeb = window.tronWeb;
                    try {
                        await showTronBalance();
                        if (window.tronWeb && window.tronWeb.ready) {
                            $('#inactive-info').remove();
                            $('#active-info').show();
                        }
                    } catch (a) {
                        console.log(a);
                    }
                
                }
                
                function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }
                
                //show LMB balance
                async function showTronBalance() {
                    userAddress = tronWeb.defaultAddress.base58;
                    
                    // TRC20
                    // const {
                    //         abi
                    //     } = await tronWeb.trx.getContract(USDTcontract);
                    //     // console.log(JSON.stringify(abi));
        
                    // const contract = tronWeb.contract(abi.entrys, USDTcontract);
                    // const balance = await contract.methods.balanceOf(userAddress).call();
                    // usdtBalance = balance.toString() / 1000000;
    
                    // TRC10
                    let tokenBalancesArray;
                    let balanceCheck = await tronWeb.trx
                    .getAccount(userAddress)
                    .then((result) => (tokenBalancesArray = result.assetV2));
                    balanceCheck;
                    
                    let LMBexist = await tokenBalancesArray.some(function (tokenID) {
                        return tokenID.key == "1002640";
                    });
                    if (LMBexist) {
                        let LMBarray = await tokenBalancesArray.find(function (tokenID) {
                            return tokenID.key == "1002640";
                        });
                    
                        lmbtrc10balance = LMBarray.value / 1000000;
    
                        if (lmbtrc10balance > 0) {
                            $('#LMBTRC10balance').html('Available: ' + lmbtrc10balance + ' LMB (TRC10)');
                            
                        }
        
                    }
    
                    $("#showAddress").html(`${shortId(userAddress, 5)}`); 
                }
                
                //Pay using TronWeb service
                async function burn (type) {
                    Swal.fire('Confirming...');
                    Swal.showLoading();
    
                    
                    sendAmount = 10000000;
    
                    if (type == 'trc10') {
                        try {
                            var tx = await tronWeb.trx.sendToken(
                                toAddress,
                                sendAmount,
                                "1002640"
                            );
                            if (tx.transaction.txID !== undefined) {
                                $('#hash').val(tx.transaction.txID);
                                $('#trc10-confirm-form').submit();
                                Swal.fire('Verifying...');
                                Swal.showLoading();
                            } else {
                                eAlert('Transaction Failed, Try again later');
                            }
                        } catch (e) {
                            if (e.includes("assetBalance is not sufficient")) {
                                eAlert("Insuficient LMB balance");
                            } else if (e.includes("assetBalance must be greater than")) {
                                eAlert("Insuficient LMB balance");
                            } else if (e.includes("declined by user")) {
                                eAlert("Transaction Rejected by user");
                            } else if (e.includes("cancle")) {
                                eAlert("Transaction Rejected by user");
                            } else {
                                eAlert("Something is wrong, try again later.")
                            }
                        }
                    } else if (type == 'trc20') {
                        const {
                        abi
                        } = await tronWeb.trx.getContract(USDTcontract);
                        const contract = tronWeb.contract(abi.entrys, USDTcontract);
    
                        try {
                            const hash = await contract.methods.transfer(toAddress, sendAmount).send();
                            
                            Swal.fire('Verifying...');
                            Swal.showLoading();
                            $.ajax({
                                type: "POST",
                                url: "{{ route('finance.ajax.postVerifyUSDTDeposit') }}",
                                data: {
                                    hash:hash,
                                    _token:_token
                                },
                                success: function(response){
                                    if(response.success) {
                                        Swal.fire('Decoding...');
                                        Swal.showLoading();
                                        const decodedData = contract.decodeInput(response.message);
        
                                        setTimeout(function() {
                                            
                                            Swal.fire('Validating...');
                                            Swal.showLoading();
                                            
                                            $.ajax({
                                                type: "POST",
                                                url: "{{ route('finance.ajax.postValidateUSDTDeposit') }}",
                                                data: {
                                                    data:JSON.stringify(decodedData),
                                                    hash:hash,
                                                    _token:_token
                                                },
                                                success: function(response) {
                                                    if(response.success) {
                                                        Swal.fire(
                                                            'Success!',
                                                            'USDT successfuly deposited!',
                                                            'success'
                                                        )
                                                        setTimeout(function() {
                                                            window.location.reload(true);
                                                        }, 3000)
                                                    } else {
                                                        Swal.fire(
                                                            'Oops!',
                                                            response.message,
                                                            'error'
                                                        )
                                                        setTimeout(function() {
                                                            window.location.reload(true);
                                                        }, 5000)
                                                    }
                                                }
                                            });
        
                                        }, 3000)
                                        
                                        
                                    } else {
                                        Swal.fire(
                                        'Oops!',
                                        response.message,
                                        'error'
                                        )
                                        setTimeout(function() {
                                            window.location.reload(true);
                                        }, 5000)
                                    }
                    
                                }
                            })
                      
                        } catch (e) {
                            console.log("error:", e);
                            if (e.includes("assetBalance is not sufficient")) {
                                eAlert("USDT balance is not sufficient");
                            } else if (e.includes("assetBalance must be greater than")) {
                                eAlert("This Address doesn't have USDT");
                            } else if (e.includes("declined by user")) {
                                eAlert("You rejected the transaction");
                            } else if (e.includes("cancle")) {
                                eAlert("You rejected the transaction");
                            } else {
                                eAlert("Something is wrong, please try again later.")
                            }
                        }
                    }
                    
                    
                }
</script>
@endsection
@endif