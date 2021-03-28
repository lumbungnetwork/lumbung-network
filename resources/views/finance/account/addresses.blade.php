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
                <a href="/account">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Back to Account</span>
                </a>

            </div>

            <div class="px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">{{$title}}</h2>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <img class="w-20 float-right" src="/image/tron-logo.png" alt="tron logo">
                    <div class="flex items-center py-2">
                        <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Active</p>
                    </div>
                    @if ($user->tron == null)
                    <form action="/account/set-tron" method="POST">
                        @csrf
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                            <input class="w-full focus:outline-none" type="text" placeholder="Tron Address"
                                value="{{ old('tron') }}" name="tron">
                        </div>
                        @error('tron')
                        <div class="text-red-600">{{ $message }}</div>
                        @enderror

                        <button type="submit"
                            class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Set
                            Address</button>

                    </form>
                    @else
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <p class="text-xs font-extralight">{{$user->tron}}</p>
                    </div>
                    <form action="/account/reset-tron" method="POST">
                        @csrf

                        <button type="submit"
                            class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Reset
                            Address</button>

                    </form>
                    @endif

                    <div class="clear-right"></div>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <img class="w-20 float-right" src="/image/binance-smart-chain.png" alt="binance smart chain logo">
                    <div class="flex items-center py-2">
                        <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Inactive</p>
                    </div>
                    <p class="text-xl font-extralight">Coming Soon!</p>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-6 ">
                    <img class="w-20 float-right" src="/image/ethereum.png" alt="ethereum logo">
                    <div class="flex items-center py-2">
                        <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                        <p class="text-sm font-light ">Inactive</p>
                    </div>
                    <p class="text-xl font-extralight">Coming Soon!</p>

                </div>




            </div>

        </div>
    </div>


</div>


@endsection