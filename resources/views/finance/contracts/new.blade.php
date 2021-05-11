@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">

    <div class="relative w-full max-w-md mx-auto">
        <form id="logout-form" action="{{ url('/logout') }}" method="POST">
            @csrf
            <div class="flex float-right text-xl text-red-500 nm-convex-gray-50 rounded-full px-3 py-2">

                <button type="submit"><i class="fa fa-power-off" aria-hidden="true"></i></button>
            </div>
        </form>

        <div class="flex items-center justify-start pt-6">
            <a href="/contracts">
                <span class="font-bold text-xl">&#8592;</span>
                <span class="font-light text-sm">Back to Contracts</span>
            </a>

        </div>

        <div class="px-0 py-6">
            <div class="text-center">
                <h2 class="font-extralight text-2xl sm:text-4xl text-gray-600">{{$title}}</h2>

            </div>

            <div id="strategies">

                <a href="/contract/1">

                </a>
            </div>

            <div class="mt-2 flex items-center justify-end py-2">
                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 w-full">
                    <h4 class="mt-6 font-light text-xl text-gray-700">Pick your strategy:</h4>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2">
                        <select class="w-full focus:outline-none bg-transparent text-xs font-light" name="strategy"
                            id="strategy">
                            <option value="0" id="strategy-placeholder" selected>
                                Pick Strategy</option>
                            <option value="3">Stable Lending (Flexible)</option>
                            <option value="1">Leveraged Stable (Perpetual)</option>
                            <option value="2">Liquidity Yield Farming</option>
                        </select>
                    </div>
                    <div class="mt-3" id="explanation">
                        <div id="strategy-1" class="hidden">
                            <p class="text-sm font-extralight">Buy into composite basket of Top Crypto Assets
                                (Bitcoin & Ethereum), supply it to lending platform, take strategically timed Stable
                                coin loan to buy more composite asset to compound the supply, thus generating
                                leveraged compounding returns.</p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Contract Terms:</h4>
                            <p class="text-sm font-light">
                                <strong>Duration:</strong> Perpetual (min. 12 months)<br>
                                <strong>Yield:</strong> 26-30% APY<br>
                                <strong>Payout:</strong> Monthly<br>
                                <strong>Top-up:</strong> Yes<br>
                                <strong>Compound:</strong> Yes<br>
                                <strong>Collateralizable:</strong> Yes (upto 80% LTV)<br>
                                <strong>Performance Fee:</strong> <span class="line-through">10%</span>
                                <strong>FREE</strong><br>
                                <strong>Minimum Deposit:</strong> 100 USDT<br>
                            </p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Platform Terms:</h4>
                            <p class="text-xs font-extralight">
                                Digital assets like Bitcoin, Stable coins, Algorithm based stable coins and other
                                Cryptocurrencies are subject to market volatility and global economic movements.
                                These gains stated above are projections based on current market situations, it may
                                (or may not) change depends on the performance of platform's optimizer algorithm.
                                <br> The only guaranteed thing is your principal. Creating any contract on this
                                platform will be taken as an agreement to this term.
                            </p>
                        </div>
                        <div id="strategy-2" class="hidden">
                            <p class="text-sm font-extralight">Buy into composite basket of Top Crypto Assets
                                Liqudity Yield Farming in Binance Smart Chain, Ethereum, TRON and other promising
                                blockchain projects. The composite ratio will be 40% in Stable-to-Stable LP, 30% in
                                Stable-to-Segniorage LP, 30% in Stable pair LP.</p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Contract Terms:</h4>
                            <p class="text-sm font-light">
                                <strong>Duration:</strong> 365 days<br>
                                <strong>Yield:</strong> 66-142% APY (Graded)<br>
                                <strong>Payout:</strong> Biweekly (14 days)<br>
                                <strong>Top-up:</strong> No<br>
                                <strong>Compound:</strong> Yes<br>
                                <strong>Collateralizable:</strong> Yes (upto 60% LTV)<br>
                                <strong>Performance Fee:</strong> <span class="line-through">10%</span>
                                <strong>5%</strong><br>
                                <strong>Minimum Deposit:</strong> 10 USDT<br>
                            </p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Grading Terms:</h4>
                            <p class="text-sm font-light">
                                <strong>Mature:</strong> 48 hours (in or out)<br>
                                <strong>Grade C:</strong> 12-36% APY (14 days)<br>
                                <strong>Grade B:</strong> 36-60% APY (90 days)<br>
                                <strong>Grade A:</strong> 60-84% APY (90 days)<br>
                                <strong>Grade S:</strong> 66-142% APY (168 days)<br>
                                <strong>Upgrade Fee:</strong> 10 LMB (Burned)<br>
                            </p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Platform Terms:</h4>
                            <p class="text-xs font-extralight">
                                Digital assets like Bitcoin, Stable coins, Algorithm based stable coins and other
                                Cryptocurrencies are subject to market volatility and global economic movements.
                                These gains stated above are projections based on current market situations, it may
                                (or may not) change depends on the performance of platform's optimizer algorithm.
                                <br> The only guaranteed thing is your principal. Creating any contract on this
                                platform will be taken as an agreement to this term.
                            </p>
                        </div>
                        <div id="strategy-3" class="hidden">
                            <p class="text-sm font-extralight">A flexible asset saving system, by letting your asset
                                stay inside
                                the platform's liquidity (in form of <strong>Credits</strong>) Your Credits will
                                gain its yield
                                everyday and automatically compounded.</p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Contract Terms:</h4>
                            <p class="text-sm font-light">
                                <strong>Duration:</strong> Flexible, no Contract needed.<br>
                                <strong>Yield:</strong> 12% APY<br>
                                <strong>Payout:</strong> Daily<br>
                                <strong>Top-up:</strong> Yes<br>
                                <strong>Compound:</strong> Auto<br>
                                <strong>Performance Fee:</strong> <span class="line-through">10%</span>
                                <strong>FREE</strong><br>
                                <strong>Minimum Deposit:</strong> 100 USD (Credits)<br>
                            </p>
                            <h4 class="mt-6 font-light text-xl text-gray-700">Platform Terms:</h4>
                            <p class="text-xs font-extralight">
                                Digital assets like Bitcoin, Stable coins, Algorithm based stable coins and other
                                Cryptocurrencies are subject to market volatility and global economic movements.
                                These gains stated above are projections based on current market situations, it may
                                (or may not) change depends on the performance of platform's optimizer algorithm.
                                <br> The only guaranteed thing is your principal. Taking any action on this
                                platform will be taken as an agreement to this term.
                            </p>
                        </div>

                    </div>



                </div>


            </div>





            <div id="usdt-balance" class="hidden mt-4 nm-concave-gray-50 rounded-xl p-6 text-center">
                <p>Available USDT</p>
                <h2 class="mt-3 text-black text-6xl font-extralight">{{number_format($USDTbalance, 2)}}</h2>
                <a href="/wallet" type="button"
                    class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Manage
                    Balances</a>
            </div>

            <div class="hidden mt-4 nm-convex-gray-50 rounded-xl p-4" id="open-contract">
                <h4 class="mt-6 font-light text-xl text-gray-700 text-center">Open Contract</h4>
                <form action="{{ route('finance.contracts.post.new') }}" method="POST" id="new-contract-form"
                    autocomplete="off" onkeydown="return event.key != 'Enter';">
                    @csrf
                    <input type="hidden" value="0" id="contract_strategy" name="contract_strategy">
                    <p class="mt-3 text-sm font-light">Initial Deposit (USDT)</p>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input class="w-full focus:outline-none bg-transparent" inputmode="numeric" pattern="[0-9]*"
                            type="text" placeholder="10 USDT" value="{{ old('deposit') }}" name="deposit" id="deposit">
                    </div>
                    @error('deposit')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    @error('contract_strategy')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror



                </form>
                <button type="button" id="create-contract-btn"
                    class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Create
                    Contract</button>
                <div class="clear-right"></div>
            </div>

            <div class="hidden mt-4 nm-convex-gray-50 rounded-xl p-4" id="active-credits">
                <h4 class="mt-6 font-light text-xl text-gray-700 text-center">Available Credits</h4>
                <h2 class="mt-3 text-black text-3xl sm:text-6xl font-extralight text-center">
                    ${{number_format($creditBalance, 2)}}</h2>
                <p id="credits-check" class="text-sm font-light text-red-600 text-center">Insufficient Credits
                    Balance, You can get more Credits by converting from USDT.</p>
                <div class="mt-3 p-3">
                    <p class="text-sm font-light text-gray-600">You don't need to create contract for this Strategy, you
                        just need to have more than $100 Credits
                        available and you can activate by clicking the "Activate" button.</p>
                </div>
                <div class="flex justify-center">
                    <a href="/wallet" type="button"
                        class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Go
                        to Wallet</a>
                </div>
            </div>


        </div>
    </div>


</div>


@endsection

@section('scripts')
<script>
    $(function(){

        const creditBalance = {{ $creditBalance }};
        // if (creditBalance >= 100) {
        //     $('#credits-check').remove();
        // }

        var min = 0;
        var max = {{$USDTbalance}};
        
        // Toggle Strategies Explanations
        $('#strategy').on('change', function () {
            $('#strategy-placeholder').remove()
            $('#open-contract').show()
            $('#usdt-balance').show()
            if($('#strategy').val() == 1) {
                $('#strategy-1').show()
                $('#strategy-2').hide()
                $('#strategy-3').hide()
                $('#active-credits').hide()
            } else if($('#strategy').val() == 2) {
                $('#strategy-2').show()
                $('#strategy-1').hide()
                $('#strategy-3').hide()
                $('#active-credits').hide()
            } else if($('#strategy').val() == 3) {
                $('#strategy-3').show()
                $('#strategy-1').hide()
                $('#strategy-2').hide()
                $('#open-contract').hide()
                $('#usdt-balance').hide()
                $('#active-credits').show()
            }
            $('#contract_strategy').val($('#strategy').val());
        })

        // Strict min and max input
        $( "#deposit" ).on('mouseup keyup', function() {
          
          if ($(this).val() > max)
          {
              $(this).val(max);
          }
          else if ($(this).val() < min)
          {
              $(this).val(min);
          }       
        });
    })

    // Create Contract Button function
        $('#create-contract-btn').click( function () {
            if ($('#contract_strategy').val() > 0) {
                if ($('#contract_strategy').val() == 1) {
                    if ($('#deposit').val() < 100) { 
                        errorToast('Minimum 100 USDT needed for this strategy'); 
                        return false; 
                    } 
                } else if ($('#contract_strategy').val() == 2) {
                    if ($('#deposit').val() < 10) { 
                        errorToast('Minimum 10 USDT needed for this strategy'); 
                        return false; 
                    }
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are going to create New Contract!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Processing...');
                        Swal.showLoading();
                        $('#new-contract-form').submit();
                    }
                })
                

            } else {
                errorToast('Please set the initial deposit'); 
                return false;
            }
            
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



</script>
@endsection