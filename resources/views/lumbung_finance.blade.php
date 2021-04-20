@extends('finance.layout.app')
@section('style')
<style>
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection
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
                            <a href="#destinations"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Destinasi
                                <svg class="w-3.5 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            <a href="#"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Community
                                <svg class="w-3.5 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            <a href="#"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Plans
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center justify-center nm-convex-gray-50 px-3 py-2 rounded-2xl">
                        <a href="/login"
                            class="text-md sm:text-lg font-medium text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                            &#8680; Login</a>

                    </div>
                </div>
                <!-- /nav -->

                <!-- hero section -->
                <div class="mt-8 lg:float-right lg:2/6 xl:w-2/4">
                    <img class="object-contain w-full max-h-32 lg:max-h-96" src="/image/lumbung-finance_2.png"
                        alt="lumbung finance">
                </div>
                <div class="lg:2/6 xl:w-2/4 mt-10 lg:mt-48 lg:ml-16 text-left">

                    <h1
                        class="text-2xl sm:text-3xl md:text-6xl font-extralight text-gray-900 leading-none text-center lg:text-left">
                        Lumbung
                        Finance
                    </h1>
                    <p class="mt-6 text-sm sm:text-lg font-light text-true-gray-500 antialiased">A community founded
                        Yield
                        Optimizer. <br><br> Creating fair
                        opportunity for
                        everyone to generate gains from Decentralized Finance with affordable plan.
                    </p>

                </div>
                <button
                    class="lg:ml-16 mt-6 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                    Learn More
                </button>
                <div class="mt-12 lg:mt-32 lg:ml-20 text-left">
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

<div class="mt-12 px-3 py-4 flex flex-wrap items-center justify-center">

    <div class="px-6 sm:px-10 lg:px-20 py-6 nm-flat-gray-50 rounded-3xl">

        <div class="mt-8 lg:float-right lg:2/6">
            <img class="object-contain w-full max-h-48 lg:max-h-96" src="/image/defi_farm1.png"
                alt="lumbung finance defi farm">
        </div>
        <div class="lg:2/6 mt-10 lg:mt-48 lg:ml-4 text-left">

            <p class="mt-6 sm:text-lg text-sm font-light text-true-gray-500 antialiased">Affordable starting capital
                (from $10), with no expertise on smart-contract interactions needed. Everyone can start their DeFi farm
                with small capital.
            </p>

        </div>

    </div>
    <div class="mt-6 px-6 sm:px-10 lg:px-20 py-6 nm-flat-gray-50 rounded-3xl lg:flex lg:justify-center">

        <div class="mt-8 lg:w-2/6">
            <img class="object-contain w-full max-h-48 lg:max-h-96" src="/image/defi_farm2.png"
                alt="lumbung finance defi farm">
        </div>
        <div class="lg:w-3/6 mt-10 lg:mt-48 lg:ml-4 text-left">

            <p class="mt-6 sm:text-lg text-sm font-light text-true-gray-500 antialiased">Simple asset management, no
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

            <p class="mt-6 sm:text-lg text-sm font-light text-true-gray-500 antialiased">Choose your strategy or mix
                them to suit your risk profile and your expected gains. We provide only proven strategies from
                conservative to aggresive approach.
            </p>

        </div>

    </div>
</div>

@endsection

@section('footer')
@include('finance.layout.footer')
@endsection