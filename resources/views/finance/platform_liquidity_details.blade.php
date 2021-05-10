@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-6">

    <div class="relative w-full max-w-md mx-auto">
        <div
            class="hidden sm:absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-green-200 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
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

            <div class="p-3 sm:p-6">
                <div class="text-center">
                    <h2 class="font-extralight text-3xl text-gray-600">{{$title}}</h2>

                </div>


                <div class="mt-4 nm-inset-gray-50 rounded-2xl p-3">
                    <h4 class="my-3 font-extralight text-xl sm:text-2xl text-center">Supplied Liquidity</h4>
                    <div id="liquidity-chart">
                    </div>

                    <hr class="my-3">

                    <div class="my-2 nm-inset-gray-50 rounded-full w-full h-1"></div>


                    <p class="mt-2 font-extralight text-sm">Lending Protocol: <span
                            class="font-semibold text-sm">JustLend.org</span></p>
                    <p class="mt-2 font-extralight text-sm">Custody Address: <span
                            class="font-light text-indigo-400 text-xs"><a
                                href="https://tronscan.org/#/address/TCeqDdaXxvQr25TUjmA1rk3aZLj53DAqN5">TCeqDdaX...53DAqN5</a></span>
                        <p class="mt-2 font-extralight text-sm">Min Collateral Ratio: <span
                                class="font-semibold text-sm">150%</span>
                        </p>

                </div>



            </div>

        </div>
    </div>


</div>


@endsection

@section('style')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"
    integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA=="
    crossorigin="anonymous"></script>
@endsection

@section('scripts')
<script>
    $( function () {
            setTimeout(function () {
                main()
                Swal.fire('Loading...');
                swal.showLoading();
            }, 200)
        })
    
        function main() {
            $.ajax({
                type: "GET",
                url: "{{ route('finance.ajax.getPlatformLiquidity') }}",
                success: function(response){
                    if(response.success) {
                        let data = response.data;
                        
                        $('#liquidity-chart').html(`
                            <canvas id="supplied-liquidity" class="my-3 chartjs" width="400" height="400"></canvas>
                            

                            <p class="text-sm font-light">Bitcoin: ${data.btc_balance.toFixed(6)} BTC</p>
                            <p class="text-sm font-light">Ethereum: ${data.eth_balance.toFixed(6)} ETH</p>
                            <p class="text-sm font-light">Tron: ${data.trx_balance.toFixed(2)} TRX</p>
                            <p class="text-sm font-light">USDJ: ${data.usdj_balance.toFixed(2)} USDJ</p>
                            <p class="text-sm font-light">USDT: ${data.usdt_balance.toFixed(2)} USDT</p>

                            <p class="text-sm font-normal">Total Value: $${data.total_value.toLocaleString("en-US")}</p>


                        `);

                        let chartData = [data.btc_value, data.eth_value, data.trx_value, data.usdj_value, data.usdt_value];

                        
                            new Chart(document.getElementById("supplied-liquidity"), {
                                "type": "pie",
                                "data": {
                                    "labels": ["BTC", "ETH", "TRX", "USDJ", "USDT"],
                                    "datasets": [{
                                        "label": "Total Supplied Liquidity (USD)",
                                        "backgroundColor": ["#f2a900", "#919191", "#d91c1c", "#cf5555", "#59a85b"],
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