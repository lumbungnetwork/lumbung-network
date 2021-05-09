@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-6">

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
                <a href="/wallet">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Back to Wallet</span>
                </a>

            </div>

            <div class="px-3 sm:px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">{{$title}}</h2>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <img class="mb-4 w-20 float-right" src="/image/tron-logo.png" alt="tron logo">
                    <div class="clear-right"></div>
                    <div class="hidden" id="active-info">
                        <div class="flex items-center py-2">
                            <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                            <p class="text-sm font-light ">Active</p>&nbsp;
                            <p class="text-sm font-thin " id="showAddress"></p>
                        </div>
                    </div>
                    <div class="" id="inactive-info">
                        <div class="flex items-center py-2">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                            <p class="text-sm font-light ">Inactive</p><br>

                        </div>
                        <p class="mt-2 text-sm font-light text-red-600">Use Tronlink or Dapp Enabled Browser</p>
                    </div>

                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input class="w-full focus:outline-none bg-transparent" autocomplete="off" inputmode="numeric"
                            pattern="[0-9]*" type="text" placeholder="0 USDT" name="deposit" id="deposit">
                    </div>
                    <div class="font-light text-green-600" id="USDTbalance">Available 0 USDT</div>
                    <button type="button"
                        class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600"
                        id="deposit-btn">Deposit</button>


                    <div class="clear-right"></div>

                </div>

                <div class="mt-4 nm-concave-gray-50 rounded-xl p-6 text-center">
                    <p>USDT (Tether USD)</p>
                    <h2 class="mt-3 text-black text-6xl font-extralight">{{number_format($USDTbalance, 2)}}</h2>

                    <a onclick="history('usdt')" class="underline mt-5 text-md font-light block">History</a>


                </div>


            </div>

        </div>
    </div>


</div>


@endsection

@section('scripts')
<script>
    let usdtBalance = 0;
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
        const toAddress = 'TKzyAPds4Au5oReKNM6FAbRecaq23vx4kd';
        const USDTcontract = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";
        var userAddress, tronWeb;
        let sendAmount = 0;
        
        $(document).ready(function () {
            setTimeout(function () {
                main()
            }, 200)
            console.log('ready');
    
            $( "#deposit" ).on('mouseup keyup', function() {
              var max = usdtBalance;
              var min = 0;
              if ($(this).val() > max)
              {
                  $(this).val(max);
              }
              else if ($(this).val() < min)
              {
                  $(this).val(min);
              }       
            }); 
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
        
        //show USDT balance
        async function showTronBalance() {
            userAddress = tronWeb.defaultAddress.base58;
            
            const {
                    abi
                } = await tronWeb.trx.getContract(USDTcontract);
                // console.log(JSON.stringify(abi));

            const contract = tronWeb.contract(abi.entrys, USDTcontract);
            const balance = await contract.methods.balanceOf(userAddress).call();
            usdtBalance = balance.toString() / 1000000;

            if (usdtBalance > 0) {
                $('#USDTbalance').html('Available: ' + usdtBalance + ' USDT');
            }

            $("#showAddress").html(`${shortId(userAddress, 5)}`);
    
            
        }
        
        //Pay using TronWeb service
        $("#deposit-btn").click(async function () {
            Swal.fire({
                title: 'Confirming...',
                allowOutsideClick: false
            });
            Swal.showLoading();
            const {
                    abi
                } = await tronWeb.trx.getContract(USDTcontract);
            const contract = tronWeb.contract(abi.entrys, USDTcontract);
            sendAmount = $('#deposit').val() * 1000000;
            if (sendAmount > 0) {
                try {
                    const hash = await contract.methods.transfer(toAddress, sendAmount).send();
                    
                    Swal.fire({
                    title: 'Verifying...',
                    text: 'Please wait 10-12 seconds to verify the transaction',
                    allowOutsideClick: false
                    });
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
                                Swal.fire({
                                    title: 'Decoding...',
                                    text: 'Almost done...',
                                    allowOutsideClick: false
                                });
                                Swal.showLoading();
                                const decodedData = contract.decodeInput(response.message);

                                setTimeout(function() {
                                    
                                    Swal.fire({
                                        title: 'Validating...',
                                        allowOutsideClick: false
                                    });
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
            } else {
                eAlert("Amount must be filled with numeric value");
            }
            
            
        });

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