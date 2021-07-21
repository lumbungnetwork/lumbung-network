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
                <a href="{{ route('finance.contracts') }}">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Back to Contracts</span>
                </a>

            </div>

            <div class="px-4 sm:px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-3xl sm:text-4xl text-gray-600">{{$title}}</h2>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <small class="float-right font-extralight">#{{sprintf('%07s', $contract->id)}}</small>
                    <div class="flex items-center py-2">
                        @if ($contract->status == 0)
                        <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Processing</p>
                        @elseif ($contract->status == 1)
                        <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Active</p>
                        @elseif ($contract->status == 2)
                        <span class="w-3 h-3 bg-yellow-600 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Breaking Process</p>
                        @elseif ($contract->status == 3)
                        <span class="w-3 h-3 bg-red-600 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Ended</p>
                        @endif

                    </div>
                    @php

                    $diff = time() - strtotime($contract->created_at);
                    $days = floor($diff / (60*60*24));
                    $progress = 3;
                    $strategy = 'Leveraged Stable Lending (Perpetual)';

                    if ($contract->strategy == 2) {
                    $strategy = 'Liquidity Yield Farming (365 days staging)';

                    $progress = round(($days / 365) * 100, 2);

                    if ($progress < 3) { $progress=3; } } $upgrade=0; if($days>= 16 && $contract->strategy == 2 &&
                        $contract->grade == 1) {
                        $upgrade = 1;
                        } elseif ($days >= 106 && $contract->strategy == 2 && $contract->grade == 2) {
                        $upgrade = 2;
                        } elseif ($days >= 196 && $contract->strategy == 2 && $contract->grade == 3) {
                        $upgrade = 3;
                        }
                        @endphp
                        <div class="flex items-end justify-between py-2">
                            <p class="text-gray-600">Day {{$days}}</p>
                            <p class="text-xl font-extralight">
                                ${{number_format($contract->principal + $contract->compounded, 2)}}
                            </p>
                        </div>

                        @if ($contract->strategy == 2)
                        <div class="h-3 relative max-w-xl rounded-full overflow-hidden">
                            <div class="w-full h-full nm-inset-gray-50 absolute"></div>
                            <div class="h-full bg-green-500 absolute" style="width:{{$progress}}%"></div>
                        </div>
                        @endif


                        <div class="py-2">
                            <p class=" text-gray-600 text-md font-light">Strategy: </p>
                            <span class="font-extralight text-sm text-gray-800">{{$strategy}}</span>
                        </div>
                        @if ($contract->strategy == 2 && $contract->grade > 0)
                        @php
                        switch($contract->grade) {
                        case 1:
                        $grade = "Grade C";
                        break;
                        case 2:
                        $grade = "Grade B";
                        break;
                        case 3:
                        $grade = "Grade A";
                        break;
                        case 4:
                        $grade = "Grade S";
                        break;

                        }

                        @endphp
                        <div class="py-2">
                            <p class=" text-gray-600 text-md font-light">{{$grade}}</p>
                        </div>
                        @endif
                        <div class="py-2">
                            @if ($contract->status < 2) <div class="space-x-0">
                                <p class=" text-gray-600 text-md font-light inline-block">Principal: </p>
                                <span
                                    class="font-extralight text-sm text-gray-800 inline-block">${{number_format($contract->principal, 2)}}</span>
                        </div>
                        <div class="space-x-0">
                            <p class=" text-gray-600 text-md font-light inline-block">Compounded: </p>
                            <span
                                class="font-extralight text-sm text-gray-800 inline-block">${{number_format($contract->compounded, 2)}}</span>
                        </div>
                        @else
                        <p class=" text-gray-600 text-md font-light inline-block">Capital: </p>
                        <span
                            class="font-extralight text-sm text-gray-800 inline-block">${{number_format($contract->principal, 2)}}</span>
                        @php
                        $release = '48 hours';
                        if ($contract->strategy == 2) {
                        $release = '7 days';
                        }
                        @endphp
                        @if ($contract->status == 2)
                        <p class=" text-gray-600 text-md font-light inline-block">Capital fund will be released within
                            {{$release}}</p>
                        @endif
                        @if ($contract->status == 3)
                        <p class=" text-gray-600 text-md font-light inline-block">Contract ended, fund released.</p>
                        @endif
                        @endif



                </div>

                @if ($upgrade == 1)
                <form action="{{ route('finance.contracts.post.upgrade', ['contract_id' => $contract->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="hash" value="0">
                    <div class="flex justify-center">
                        <button type="submit"
                            class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Upgrade
                            Contract</button>
                    </div>

                </form>

                @endif

                @if ($upgrade > 1)
                <div class="flex justify-center">
                    <button type="button" onclick="upgrade({{$contract->id}})"
                        class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Upgrade
                        Contract</button>
                </div>
                <form action="{{ route('finance.contracts.post.upgrade', ['contract_id' => $contract->id]) }}"
                    method="POST" id="trc10-confirm-form">
                    @csrf
                    <input type="hidden" id="hash" name="hash" value="0">
                </form>
                @endif

            </div>

            <div class="my-4 nm-convex-gray-50 rounded-xl p-6 text-center">
                <p>Yield Available</p>
                <h2 class="mt-3 text-black text-6xl font-extralight">${{number_format($yield->net, 2)}}</h2>
                <div class="flex justify-center space-x-2">
                    @if ($contract->status < 2) <button type="button" id="compound-btn"
                        class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">
                        Compound</button>
                        <form id="compound-form"
                            action="{{ route('finance.contracts.post.compound', ['contract_id' => $contract->id]) }}"
                            method="POST">
                            @csrf
                        </form>
                        @endif
                        <button type="button" id="withdraw-btn"
                            class="mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Withdraw</button>

                        <form id="withdraw-form"
                            action="{{ route('finance.contracts.post.withdraw', ['contract_id' => $contract->id]) }}"
                            method="POST">
                            @csrf
                        </form>
                </div>

                <a onclick="history({{$contract->id}})" class="underline mt-5 text-md font-light block">History</a>


            </div>

            @if ($contract->status == 1 && $contract->strategy == 1 && $days >= 365)
            <form action="{{ route('finance.contracts.post.break', ['contract_id' => $contract->id]) }}" method="POST"
                id="break-form">
                @csrf
            </form>
            <div class="flex justify-center">
                <button onclick="breakContract()" type="button"
                    class="p-3 w-32 rounded-xl bg-red-800 text-white focus:outline-none focus:bg-red-800 text-xs">Break
                    Contract</button>
            </div>
            @endif

            @if ($contract->status == 1 && $contract->strategy == 2)
            <form action="{{ route('finance.contracts.post.break', ['contract_id' => $contract->id]) }}" method="POST"
                id="break-form">
                @csrf
            </form>
            <div class="flex justify-center">
                <button onclick="breakContract()" type="button"
                    class="p-3 w-32 rounded-xl bg-red-800 text-white focus:outline-none focus:bg-red-800 text-xs">Break
                    Contract</button>
            </div>
            @endif

            <div class="mt-4 nm-inset-gray-200 rounded-2xl p-4">
                @if ($contract->strategy == 1)
                <p class="text-xs font-light text-gray-600">All actions are subject to <b>4% fee</b>. <br> Breaking
                    the
                    contract of
                    <b>Leveraged Stable Lending Strategy</b> will remove the principal and compounded amount to
                    <b>Availabe Yield</b> (within 48
                    hours), then it would be available for withdraw action.</p>
                @elseif ($contract->strategy == 2)
                <p class="text-xs font-light text-gray-600">All actions are subject to <b>4% fee</b>. <br> Breaking
                    the
                    contract of
                    <b>Liquidity
                        Yield Farming Strategy</b> will remove the principal and compounded amount to <b>Availabe
                        Yield</b> (within 7 days), then it would be available for withdraw action.</p>
                @endif

            </div>


        </div>

    </div>
</div>


</div>


@endsection


@section('scripts')
<script>
    const _yield = {{$yield->net}};

       $('#compound-btn').click( function() {
            if (_yield < 1) {
                errorToast('Insufficient Yield for this action!');
                return false;
            }
            Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to compound this yield to your contract?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, compound!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Compounding...');
                        Swal.showLoading();
                        $('#compound-form').submit();
                    }
                })
       }) 

       $('#withdraw-btn').click( function() {
            if (_yield < 1) {
                errorToast('Insufficient Yield for this action!');
                return false;
            }
            Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to withdraw this yield to your credit balance?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Withdraw!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Withdrawing...');
                        Swal.showLoading();
                        $('#withdraw-form').submit();
                    }
                })
       }) 

       // Toast
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

        function errorToast (message) {
            Toast.fire({
                icon: 'error',
                title: message
            })
        }

        function breakContract() {
            Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to break this contract?",
                    icon: 'danger',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, I am sure!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Are you REALLY sure?',
                            text: "Breaking Contract is PERMANENT and CANNOT be UNDONE",
                            icon: 'danger',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, BREAK THIS!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire('Processing...');
                                Swal.showLoading();
                                $('#break-form').submit();
                            }
                        })
                    }
                })
        }

        function upgrade(contract_id) {
           $.ajax({
            type: "GET",
            url: "{{ route('finance.ajax.getContractUpgrade') }}",
            data: {contract_id:contract_id},
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false
                })
                setTimeout(function () {
                    main()
                }, 200)
            }
        });  
        }

        function history(contract_id) {
        $.ajax({
            type: "GET",
            url:
            "{{ route('finance.ajax.getYieldHistory') }}",
            data: {contract_id:contract_id},
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

@if ($upgrade > 1)
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
                        var userAddress, tronWeb;
                        let sendAmount = 0;
                        
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
                                    $('#LMBTRC10balance').html(lmbtrc10balance + ' LMB (TRC10)');
                                    
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
                                        if (tx.transaction.txID.length == 64) {
                                            $('#trc10-confirm-form').submit();
                                            Swal.fire('Verifying...');
                                            Swal.showLoading();
                                        } else {
                                            eAlert('Submit Failed, Please Report to Admin');
                                        }
                                        
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
@endif
@endsection