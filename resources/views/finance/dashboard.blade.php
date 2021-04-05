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

            <div class="flex float-right mr-6 text-xl text-red-500">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="mt-5"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                </form>

            </div>

            <div class="p-4 sm:p-6">
                <div class="nm-inset-gray-100 w-1/2 px-3 py-2 text-center rounded-2xl flex flex-wrap overflow-hidden">
                    <a href="/account">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                        <span class="font-light">{{$user->username}} </span>
                        <span class="font-bold text-xl "></span>
                    </a>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 text-center">
                    <p class="text-gray-600 text-lg sm:text-2xl font-light">Total Platform Liquidity</p>
                    <h2 class="my-4 text-black text-3xl sm:text-5xl font-extralight" id="platform_liquidity">

                    </h2>
                    <svg class="animate-spin h-12 w-12 mr-3 ..." viewBox="0 0 24 24" id="liquidity_loading">
                        <!-- ... -->
                    </svg>
                    <a href="{{ route('finance.platformLiquidityDetails') }}"
                        class="p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Explore
                        Details</a>

                </div>
                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 text-center">
                    <p class="text-gray-600 text-lg sm:text-2xl font-light">Your Liquidity</p>
                    <h2 class="my-4 text-black text-3xl sm:text-5xl font-extralight">
                        ${{number_format($totalLiquidity, 2)}}</h2>
                    <a href="{{ route('finance.contracts') }}"
                        class="p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Manage
                        Contracts</a>

                </div>

                <div class="mt-5 nm-convex-gray-50 rounded-xl p-6 text-center">
                    <p class="text-gray-600 text-lg sm:text-2xl font-light">Total Yield Earned</p>
                    <h2 class="my-4 text-black text-3xl sm:text-5xl font-extralight">
                        ${{number_format($yields->earned, 2)}}</h2>
                    <a href="{{ route('finance.wallet') }}"
                        class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Manage
                        Balances</a>
                </div>

                <div class="mt-5 nm-inset-gray-50 rounded-xl p-3">
                    <p class="font-extralight">Account Info</p>
                    <div class="my-2 nm-inset-gray-50 rounded-full w-full h-1"></div>

                    <p class="mt-2 font-extralight text-sm">Active contracts: <span
                            class="font-medium">{{$activeContracts}}</span></p>
                    <p class="mt-2 font-extralight text-sm">Lending contracts: <span
                            class="font-medium">{{$activeContracts}}</span></p>
                    <p class="mt-2 font-extralight text-sm">Loan contracts: <span>0</span></p>
                    <p class="mt-2 font-extralight text-sm">Referrals: <span class="font-medium">{{$referrals}}</span>
                    </p>
                </div>


            </div>

        </div>
    </div>


</div>


@endsection

@section('scripts')
<script>
    $( function () {
        setTimeout(function () {
            main()
        }, 200)
    })

    async function main() {
        $.ajax({
            type: "GET",
            url: "{{ route('finance.ajax.getPlatformLiquidity') }}",
            success: function(response){
                if(response.success) {
                    $('#liquidity_loading').remove();
                    $('#platform_liquidity').html(`$` + response.data.total_value.toLocaleString("en-US"));
                }
            }
        });
    }
</script>
@endsection