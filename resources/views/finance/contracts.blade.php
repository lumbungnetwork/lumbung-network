@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">

    <div class="relative w-full max-w-md mx-auto">
        <div
            class="absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-green-200 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative bg-gray-100 shadow-lg rounded-3xl">

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

            <div class="px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">Contracts</h2>

                </div>

                <div id="active-contracts">
                    @if ($contracts == null)
                    <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                        <small class="float-right font-extralight">#000000</small>
                        <div class="flex items-center py-2">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                            <p class="text-sm font-light ">Inactive</p>
                        </div>
                        <div class="mt-4 font-light text-2xl text-center p-3">
                            <h4>You don't have any active contract. :( </h4>
                        </div>
                    </div>
                    @else
                    @foreach ($contracts as $contract)
                    <a href="{{ route('finance.contracts.detail', ['contract_id' => $contract->id]) }}">
                        <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                            <small class="float-right font-extralight">#{{sprintf('%07s', $contract->id)}}</small>
                            <div class="flex items-center py-2">
                                @if ($contract->status == 0)
                                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                                <p class="text-sm font-light ">Processing</p>
                                @elseif ($contract->status == 1)
                                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                                <p class="text-sm font-light ">Active</p>
                                @endif

                            </div>
                            @php
                            // Get days of progress and progress bar percentage
                            $diff = time() - strtotime($contract->created_at);
                            $days = floor($diff / (60*60*24));
                            $progress = 3;
                            $strategy = 'Leveraged Stable Lending (Perpetual)';

                            if ($contract->strategy == 2) {
                            $strategy = 'Liquidity Yield Farming (365 days staging)';

                            $progress = round($days * (365 / 100),2);

                            if ($progress < 3) { $progress=3; } } @endphp <div
                                class="flex items-end justify-between py-2">
                                <p class="text-gray-600">Day {{$days}}</p>
                                <p class="text-xl font-extralight">
                                    ${{number_format($contract->principal + $contract->compounded, 2)}}
                                </p>
                        </div>


                        <div class="h-3 relative max-w-xl rounded-full overflow-hidden">
                            <div class="w-full h-full nm-inset-gray-50 absolute"></div>
                            <div class="h-full bg-green-500 absolute" style="width:{{$progress}}%"></div>
                        </div>

                        <div class="py-2">
                            <p class=" text-gray-600 text-md font-light">Strategy: </p>
                            <span class="font-extralight text-sm text-gray-800">{{$strategy}}</span>
                        </div>

                </div>
                </a>
                @endforeach
                @endif

            </div>

            <div class="mt-2 flex items-center justify-end py-2">
                <a href="/new-contract" type="button"
                    class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">New
                    Contract</a>


            </div>





            <div class="mt-4 nm-concave-gray-50 rounded-xl p-6 text-center">
                <p>Total Available Yields</p>
                <h2 class="mt-3 text-black text-6xl font-extralight">${{number_format($yields->available, 2)}}</h2>
                <p class="mt-6">Total Earned Yields</p>
                <h2 class="mt-3 text-black text-6xl font-extralight">${{number_format($yields->earned, 2)}}</h2>
                <a href="{{ route('finance.wallet') }}" type="button"
                    class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Manage
                    Balances</a>
            </div>

            <div class="mt-5 nm-inset-gray-50 rounded-xl p-3">
                <p class="font-extralight">Account Info</p>
                <div class="my-2 nm-inset-gray-50 rounded-full w-full h-1"></div>
                @php
                $activeContracts = 0;
                if ($activeContracts != null) {
                $activeContracts = count($contracts);
                }
                @endphp
                <p class="mt-2 font-extralight text-sm">Active contracts: </p><span>{{$activeContracts}}</span>
                <p class="mt-2 font-extralight text-sm">Lending contracts: </p><span>{{$activeContracts}}</span>
                <p class="mt-2 font-extralight text-sm">Loan contracts: </p><span>0</span>
                <p class="mt-2 font-extralight text-sm">Referrals: </p><span>{{$referrals}}</span>
            </div>


        </div>

    </div>
</div>


</div>


@endsection