@extends('member.components.main')
@section('content')

<div class="mt-8 min-w-full flex flex-col justify-center px-3 sm:px-6">
    <div class="relative w-full max-w-2xl lg:max-w-6xl xl:max-w-screen-2xl mx-auto">
        <div
            class="absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative nm-flat-gray-50 rounded-3xl">

            <div class="hidden md:flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="px-6 sm:px-10 lg:px-20 py-6">

                <!-- nav -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-center">
                        <div class="hidden lg:flex items-center justify-center text-xl font-light text-true-gray-800">
                            <img class="w-12 px-2" src="/image/eidr-coin.png" alt="eidr coin">
                            Lumbung Finance
                        </div>
                        <div class="hidden lg:flex items-center justify-center antialiased lg:ml-20 pt-1">

                            <a href="#farms"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Farms

                            </a>
                            <a href="#strategies"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Strategies
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center justify-center px-3 mt-0">
                        <a href="/login"
                            class="lg:ml-16 mt-6 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-200 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                            Login</a>

                    </div>
                </div>
                <!-- /nav -->

                <!-- hero section -->
                <div class="mt-8 lg:float-right lg:2/6 xl:w-2/4">
                    <img class="object-contain w-full max-h-32 lg:max-h-96" src="/image/lumbung-finance_2.png"
                        alt="lumbung finance">
                </div>
                <div class="lg:2/6 xl:w-2/4 mt-10 lg:mt-48 lg:ml-16 text-left">

                    <h1 class="text-2xl sm:text-3xl md:text-6xl font-extralight text-gray-900 leading-none">
                        Lumbung
                        Finance
                    </h1>
                    <p class="mt-4 text-sm sm:text-lg font-light text-true-gray-500 antialiased">Creating fair
                        opportunity for
                        everyone to generate gains from Decentralized Finance protocols with affordable plan.
                    </p>

                </div>

                <div class="mt-12">
                    <a href="#learn"
                        class="lg:ml-16 mt-6 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                        Learn More
                    </a>
                </div>

                <div class="mt-6 lg:mt-32 lg:ml-20 text-left">
                    <bottom type="button"
                        class="flex items-center justify-center w-12 h-12 rounded-full bg-cool-gray-100 text-gray-800 animate-bounce hover:text-gray-900 hover:bg-cool-gray-50 transition duration-300 ease-in-out cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </bottom>
                </div>

                <!-- /hero section -->

            </div>
        </div>


    </div>

</div>

<div id="learn" class="mt-6 px-3 py-4 flex flex-wrap justify-evenly">

    <div class="px-4 py-2 sm:px-10 lg:px-60 text-center">
        <h2 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl">DeFi.
            Simplified.</h2>
        <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Lumbung Finance is a service, providing easier
            way to generate gains
            from Decentralized
            Finance (DeFi)
            protocols.<br><br> This service packed with proven strategies, rendering the autonomous process of managing
            your
            capital into DeFi protocols such as Lending, Collateral, Leverage, Providing Liquidity, Harvest Yields, turn
            Yields back to Capital starting point, manage your debt-ratio, repeating the process and take heuristic
            action to every opportunities.<br><br>
            The service built to help literally everyone, with minimum knowledge requirement to harness the maximum
            potency of DeFi movements.
        </p>
    </div>

    <div
        class="mt-6 px-6 sm:px-10 lg:px-20 py-6 nm-flat-gray-50 rounded-3xl lg:bg-transparent lg:shadow-none lg:w-5/12">

        <div class="mt-8">
            <img class="object-contain w-full max-h-48" src="/image/defi_farm1.png" alt="lumbung finance defi farm">
        </div>
        <div class="mt-10 text-left lg:text-center">

            <p class="mt-6 sm:text-lg text-sm font-light text-gray-700 antialiased">Affordable starting capital
                (from $10), with no expertise on smart-contract interactions needed. Everyone can start their DeFi farm
                with small capital and grow their assets by compounding their yields.
            </p>

        </div>

    </div>
    <div
        class="mt-6 px-6 sm:px-10 lg:px-20 py-6 nm-flat-gray-50 rounded-3xl lg:bg-transparent lg:shadow-none lg:w-5/12">

        <div class="mt-8">
            <img class="object-contain w-full max-h-48" src="/image/defi_farm2.png" alt="lumbung finance defi farm">
        </div>
        <div class="mt-10 text-left lg:text-center">

            <p class="mt-6 sm:text-lg text-sm font-light text-gray-700 antialiased">Simple asset management, no
                need to watch bunch of assets performance on a stuffed portfolio viewer. Start with only USDT and track
                your asset's growth in USD (realtime) value.
            </p>

        </div>

    </div>
    <div class="mt-6 px-6 sm:px-10 lg:px-20 py-6 nm-flat-gray-50 rounded-3xl">

        <div class="mt-8 lg:float-right lg:2/6">
            <img class="object-contain w-full max-h-48 lg:max-h-96" src="/image/defi_farm3.png"
                alt="lumbung finance defi farm">
        </div>
        <div class="lg:2/6 mt-10 lg:mt-48 lg:ml-4 text-left">

            <p class="mt-6 sm:text-lg text-sm font-light text-gray-700 antialiased">Choose your strategy or mix
                them to suit your risk profile and your expected gains. We provide only proven strategies from
                conservative to aggresive approach.
            </p>

        </div>

    </div>
</div>

<div id="strategies" class="mt-6 text-left flex flex-wrap justify-evenly px-4 py-2 lg:px-10">
    <div class="lg:w-5/12">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">Our
            Strategies.</h3>
        <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Only proven strategies, cautiously chained
            with
            efficiency and risk mitigation in mind.</p>
    </div>

    <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 w-full lg:w-5/12">
        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2">
            <select class="w-full focus:outline-none bg-transparent" name="strategy" id="strategy">
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
            <div id="strategy-3" class="hidden">
                <p class="text-sm font-extralight">A flexible asset saving system, by letting your asset stay inside
                    the platform's liquidity (in form of <strong>Credits</strong>) Your Credits will gain its yield
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

        </div>

    </div>
</div>

<div id="farms" class="mt-6 px-4 py-2 flex flex-wrap lg:px-40">
    <div class="lg:w-6/12">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">Farming
            Fields.</h3>
        <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">We work on world's leading Decentralized
            Finance protocols.</p>
    </div>
    <div class="mt-6 nm-convex-gray-50 rounded-2xl p-3 lg:mx-auto">
        <table class="table-auto border-white border">
            <thead class="border-white border">
                <tr>
                    <th>Protocols</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody class="font-extralight text-sm">
                <tr>
                    <td class="px-2 py-2">Autofarm</td>
                    <td class="px-2 py-2">Yield Optimizer</td>
                </tr>
                <tr>
                    <td class="px-2 py-2">Yearn Finance</td>
                    <td class="px-2 py-2">Yield Optimizer</td>
                </tr>
                <tr>
                    <td class="px-2 py-2">Beefy Finance</td>
                    <td class="px-2 py-2">Yield Optimizer</td>
                </tr>
                <tr>
                    <td class="px-2 py-2">Pancake Swap</td>
                    <td class="px-2 py-2">Liquidity Mining</td>
                </tr>
                <tr>
                    <td class="px-2 py-2">Uniswap</td>
                    <td class="px-2 py-2">Liquidity Mining</td>
                </tr>
                <tr>
                    <td class="px-2 py-2">Venus</td>
                    <td class="px-2 py-2">Lending</td>
                </tr>
                <tr>
                    <td class="px-2 py-2">JustLend</td>
                    <td class="px-2 py-2">Lending</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6 p-4 sm:px-10 lg:px-40 py-6 flex flex-wrap justify-evenly">

    <div class="mt-4 nm-inset-gray-50 rounded-2xl p-3 w-full lg:w-1/3">
        <h4 class="my-3 font-extralight text-xl sm:text-2xl text-center">Supplied Liquidity</h4>
        <div id="liquidity-chart">
        </div>

        <hr class="my-3">

        <div class="my-2 nm-inset-gray-50 rounded-full w-full h-1"></div>


        <p class="mt-2 font-extralight text-sm">Lending Protocol: <span
                class="font-semibold text-sm">JustLend.org</span>
        </p>
        <p class="mt-2 font-extralight text-sm">Custody Address: <span class="font-light text-indigo-400 text-xs"><a
                    href="https://tronscan.org/#/address/TCeqDdaXxvQr25TUjmA1rk3aZLj53DAqN5">TCeqDdaX...53DAqN5</a></span>
            <p class="mt-2 font-extralight text-sm">Min Collateral Ratio: <span
                    class="font-semibold text-sm">150%</span>
            </p>

    </div>
    <div class="lg:w-2/6 mt-10 lg:mt-48 lg:ml-4 text-left">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">
            Transparency.</h3>
        <p class="mt-6 sm:text-lg text-sm font-light text-gray-700 antialiased">We believe in the freedom of
            information, all Lumbung Finance strategy and positions are open to public.
        </p>

    </div>

</div>

<div class="mt-6 text-center">
    <div class="">
        <img class="object-contain w-full max-h-48 lg:max-h-96" src="/image/lumbung-network-teamwork.png"
            alt="lumbung finance defi farm">
    </div>
    <div class="px-4 lg:px-60 py-2 lg:py-6">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">
            Teamwork.</h3>
        <p class="mt-6 sm:text-lg text-sm font-light text-gray-700 antialiased">Some said, DeFi is a huge ocean,
            dominated by big whales. But, as a team, even small fishes could turn the tides. <br><br>
            We are actively supporting and help each other, not only because we incentivized to do that, but because we
            understand: in order to make our dream works, we need teamwork!
        </p>
        <div class="my-10">
            <a href="https://t.me/lumbungnetwork"
                class="mt-6 px-8 py-4 lg:px-12 lg:py-6 rounded-full font-bold tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-gray-600 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                Join Us!
            </a>
        </div>

    </div>
</div>



@endsection


@section('footer')
@include('finance.layout.footer')
@endsection

@section('style')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"
    integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA=="
    crossorigin="anonymous"></script>
@endsection

@section('scripts')
<script>
    // Toggle Strategies Explanations
        $('#strategy').on('change', function () {
            $('#strategy-placeholder').remove()
            $('#open-contract').show()
            if($('#strategy').val() == 1) {
                $('#strategy-1').show()
                $('#strategy-2').hide()
                $('#strategy-3').hide()
                
            } else if($('#strategy').val() == 2) {
                $('#strategy-2').show()
                $('#strategy-1').hide()
                $('#strategy-3').hide()
            } else if($('#strategy').val() == 3) {
                $('#strategy-3').show()
                $('#strategy-1').hide()
                $('#strategy-2').hide()
            }
            $('#contract_strategy').val($('#strategy').val());
        })

        $( function () {
            setTimeout(function () {
                main()
            }, 200)
        })
    
        function main() {
            $.ajax({
                type: "GET",
                url: "{{ route('finance.platform-liquidity') }}",
                success: function(response){
                    if(response.success) {
                        let data = response.data;
                        
                        $('#liquidity-chart').html(`
                            <canvas id="supplied-liquidity" class="my-3 chartjs" width="400" height="400"></canvas>
                            

                            <p class="text-sm font-light">Bitcoin: ${data.btc_balance.toFixed(6)} BTC</p>
                            <p class="text-sm font-light">Ethereum: ${data.eth_balance.toFixed(6)} ETH</p>
                            <p class="text-sm font-light">Tron: ${data.trx_balance.toFixed(2)} TRX</p>
                            <p class="text-sm font-light">USDJ: ${data.usdj_balance.toFixed(2)} USDJ</p>
                            <hr>
                            <p class="text-md font-bold">Total Value: $${data.total_value.toLocaleString("en-US")}</p>


                        `);

                        let chartData = [data.btc_value, data.eth_value, data.trx_value, data.usdj_value];

                        
                            new Chart(document.getElementById("supplied-liquidity"), {
                                "type": "pie",
                                "data": {
                                    "labels": ["BTC", "ETH", "TRX", "USDJ"],
                                    "datasets": [{
                                        "label": "Total Supplied Liquidity (USD)",
                                        "backgroundColor": ["#f2a900", "#919191", "#d91c1c", "#cf5555"],
                                        "data": chartData
                                    }]
                                },
                                "options": {
                                    title: {
                                        display: true,
                                        text: 'Base Supplied Liquidity On Lending Protocol'
                                    }
                                }
                            });
                            swal.close();

                        
                    }
                }
            });
        }
</script>
@endsection