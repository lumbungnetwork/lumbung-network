@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-6">

    <div class="relative w-full max-w-md mx-auto">
        <div
            class="hidden sm:absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative nm-flat-gray-200 rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="mt-2">
                <h2 class="text-gray-600 font-light text-xl text-center">Test Zone</h2>
            </div>

            <div class="mt-4 px-4 py-2">
                <h4 class="my-3 text-gray-500 text-sm text-center">Wallet Info</h4>
                <div class="mt-3 nm-convex-gray-50 rounded-2xl p-2">
                    {{-- Active Wallet info --}}
                    <div class="mb-0 text-gray-600 font-light" id="showAddress"></div>
                    <div id="USDTbalance"></div>
                    <p id="tronweb-warning" class="text-xs font-medium text-red-600">Use Tronlink app/extension or other
                        Dapp Browser.</p>
                </div>
            </div>

            <div class="px-4 py-2" x-data="{ tab: 'trc10' }">
                {{-- Transfers--}}
                <h4 class="my-3 text-gray-500 text-sm text-center">Transfer</h4>
                <div class="mt-3 border-gray-50 border-b-2">
                    <ul class='flex cursor-pointer justify-center'>
                        <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-200 text-sm'
                            :class="{ 'active': tab === 'trc10' }" @click="tab = 'trc10'">TRC10</li>
                        <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-200 text-sm'
                            :class="{ 'active': tab === 'trc20' }" @click="tab = 'trc20'">TRC20</li>
                    </ul>
                </div>


                <div class="nm-convex-gray-50 rounded-2xl p-2 overflow-x-scroll">
                    <div x-show="tab === 'trc10'">
                        {{-- TRC10 --}}
                        <div class="mt-2 px-4 py-2">
                            <p class="text-sm text-gray-500 font-light">Token ID</p>
                            <div class="mb-4 nm-inset-gray-200 rounded-2xl p-2">
                                <input class="bg-transparent focus:outline-none w-full" type="text" name="trc10-tokenid"
                                    id="trc10-tokenid" value="1002000">
                            </div>
                            <p class="text-sm text-gray-500 font-light">Receiver Address</p>
                            <div class="mb-4 nm-inset-gray-200 rounded-2xl p-2">
                                <input class="text-xs font-extralight bg-transparent focus:outline-none w-full"
                                    type="text" name="trc10-receiver" id="trc10-receiver">
                            </div>
                            <p class="text-sm text-gray-500 font-light">Amount (using 6 decimals default)</p>
                            <div class="mb-4 nm-inset-gray-200 rounded-2xl p-2">
                                <input class="bg-transparent focus:outline-none w-full" type="text" name="trc10-amount"
                                    id="trc10-amount">
                            </div>
                            <div class="mt-2 flex justify-end">
                                <button id="trc10-send-btn"
                                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-400 to-purple-300 text-lg text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Send</button>
                            </div>
                        </div>

                    </div>
                    <div x-show="tab === 'trc20'">
                        {{-- TRC20 --}}
                        <div class="mt-2 px-4 py-2">
                            <p class="text-sm text-gray-500 font-light">Contract Address</p>

                            <div class="mb-0 nm-inset-gray-200 rounded-2xl p-2">
                                <input disabled class="text-xs font-extralight bg-transparent focus:outline-none w-full"
                                    type="text" name="trc20-contractAddress" id="trc20-contractAddress"
                                    value="TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t">

                            </div>
                            <small style="font-size: 12px; line-height: 0.6em;" class="text-gray-500 font-light">We are
                                using
                                USDT
                                contract as default
                                here,
                                triggering 'transfer' method</small>
                            <p class="mt-4 text-sm text-gray-500 font-light">Receiver Address</p>
                            <div class="mb-4 nm-inset-gray-200 rounded-2xl p-2">
                                <input class="text-xs font-extralight bg-transparent focus:outline-none w-full"
                                    type="text" name="trc20-receiver" id="trc20-receiver">
                            </div>
                            <p class="text-sm text-gray-500 font-light">Amount (using 6 decimals default)</p>
                            <div class="mb-4 nm-inset-gray-200 rounded-2xl p-2">
                                <input class="bg-transparent focus:outline-none w-full" type="text" name="trc20-amount"
                                    id="trc20-amount">
                            </div>
                            <div class="mt-2 flex justify-end">
                                <button id="trc20-send-btn"
                                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-400 to-purple-300 text-lg text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Send</button>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 nm-inset-gray-200 rounded-2xl p-2">
                        <h4 class="text-sm text-gray-600 font-light text-center">Log</h4>
                        <textarea rows="20" class="bg-transparent px-2 w-full" id="console-log">

                        </textarea>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>


@endsection

@section('scripts')
<script>
    //TronWeb
        let toAddress = '';
        const USDTcontract = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";
        var userAddress, tronWeb;
        let sendAmount = 0;
        let usdtBalance = 0;
        
        $(document).ready(function () {
            setTimeout(function () {
                main()
            }, 200)
            console.log('ready');
    
            $( ".amount" ).on('mouseup keyup', function() {
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
                    $('#tronweb-warning').remove();
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

        // TRC10 transfer
        $("#trc10-send-btn").click(async function () {
                    Swal.fire('Confirming...');
                    swal.showLoading();
                    $('#console-log').html('');

                    sendAmount = $('#trc10-amount').val() * 1000000;
                    toAddress = $('#trc10-receiver').val();
                    tokenID = $('#trc10-tokenid').val();

                    if (toAddress.length !== 34) {
                        eAlert("Something wrong with address format");
                        return false;
                    }
                    if (sendAmount > 0) {
                        try {
                            var tx = await tronWeb.trx.sendToken(
                                toAddress,
                                sendAmount,
                                tokenID
                            );

                            console.log(tx);
                            $('#console-log').html(JSON.stringify(tx, null, 2));
                            Swal.fire('Success', 'Check the log in your console', 'success')

                        } catch (e) {
                            if (e.includes("assetBalance is not sufficient")) {
                                eAlert("Insufficient asset");
                            } else if (e.includes("assetBalance must be greater than")) {
                                eAlert("Insufficient asset");
                            } else if (e.includes("declined by user")) {
                                eAlert("Canceled by user");
                            } else if (e.includes("cancle")) {
                                eAlert("Canceled by user");
                            } else {
                                eAlert("Somethings wrong.")
                            }
                        }
                    } else {
                        eAlert("amount needed.");
                    }
                    
                    
                });
    // TRC20 USDT transfer
    $("#trc20-send-btn").click(async function () {
            sendAmount = $('#trc20-amount').val() * 1000000;
            toAddress = $('#trc20-receiver').val();
            Swal.fire('Confirming...');
            Swal.showLoading();
            $('#console-log').html('');

            const {
                abi
            } = await tronWeb.trx.getContract(USDTcontract);
            // console.log(JSON.stringify(abi));
            
            const contract = tronWeb.contract(abi.entrys, USDTcontract);
            
            if (sendAmount > 0) {
                try {
                    var parameter = [{type:'address',value:toAddress},{type:'uint256',value:sendAmount}]
                    var options = {
                        feeLimit:100000000
                    }
                    
                    const transactionObject = await tronWeb.transactionBuilder.triggerSmartContract(
                        tronWeb.address.toHex(USDTcontract),
                        "transfer(address,uint256)",
                        options,
                        parameter,
                        tronWeb.address.toHex(userAddress)
                    );
                    
                    var signedTransaction = await tronWeb.trx.sign(transactionObject.transaction);
                    
                    var broadcastTransaction = await tronWeb.trx.sendRawTransaction(signedTransaction);
                    
                    if (broadcastTransaction.txid !== undefined) {
                        console.log(broadcastTransaction);
                        $('#console-log').html(JSON.stringify(broadcastTransaction, null, 2));
                        Swal.fire('Success', 'Check the log in your console', 'success')

                    } else {
                        console.log(broadcastTransaction);
                        $('#console-log').html(JSON.stringify(broadcastTransaction, null, 2));
                        Swal.fire('Failed', 'Check your console log', 'error')
                    }
                    
                    
              
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
</script>
@endsection

@section('style')
<style>
    .active {
        background-color: white;
        color: black;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection