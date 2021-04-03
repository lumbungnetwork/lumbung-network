@extends('finance.layout.app')
@section('content')

<div class="mt-8 min-w-full flex flex-col justify-center px-6">
    <div class="relative w-full max-w-2xl lg:max-w-6xl xl:max-w-screen-2xl mx-auto">
        <div
            class="absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative nm-flat-gray-100 rounded-3xl">

            <div class="hidden md:flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="px-10 lg:px-20 py-6">

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
                    <div class="flex items-center justify-center">
                        <a href="/login"
                            class="mr-5 text-lg font-medium text-true-gray-800 hover:text-cool-gray-700 transition duration-150 ease-in-out">Login</a>

                    </div>
                </div>
                <!-- /nav -->

                <!-- hero section -->
                <div class="mt-8 lg:float-right lg:2/6 xl:w-2/4">
                    <img class="object-contain w-full max-h-32 lg:max-h-96" src="/image/lumbung-finance_2.png"
                        alt="lumbung finance">
                </div>
                <div class="lg:2/6 xl:w-2/4 mt-10 lg:mt-48 lg:ml-16 text-left">

                    <div class="text-6xl font-extralight text-gray-900 leading-none">Lumbung Finance
                    </div>
                    <div class="mt-6 text-lg font-light text-true-gray-500 antialiased">A community founded Yield
                        Optimizer. Bringing Decentralized Finance exposure to everyone. Creating fair opportunity for
                        everyone on affordable plan.
                    </div>

                </div>
                <button
                    class="lg:ml-16 mt-6 px-8 py-4 rounded-full font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-grey-800 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
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

<div class="px-3 py-4 flex flex-wrap items-center justify-center sm:justify-between">
    <div class="mt-4 max-w-xs sm:max-w-md bg-white p-5 rounded-lg tracking-wide shadow-lg">
        <div id="header" class="flex">
            <i class="fas fa-dice-d20 text-4xl text-green-300"></i>
            <div id="body" class="flex flex-col ml-5">
                <h4 class="text-xl font-semibold mb-2 text-green-500">Blockchain Technology</h4>
                <p class="text-gray-800 font-light mt-2">Built on top of Blockchain Technology, enabling maximum
                    security, full-ownership and transparency.
                </p>

            </div>
        </div>
    </div>
    <div class="mt-4 max-w-xs sm:max-w-md bg-white p-5 rounded-lg tracking-wide shadow-lg">
        <div id="header" class="flex">
            <i class="fas fa-puzzle-piece text-4xl text-green-300"></i>
            <div id="body" class="flex flex-col ml-5">
                <h4 class="text-xl font-semibold mb-2 text-green-500">Affordable</h4>
                <p class="text-gray-800 font-light mt-2">Creating fair opportunity for everyone to participate in
                    Decentralized Finance Protocols. Anyone can start with as little as $10.</p>

            </div>
        </div>
    </div>
    <div class="mt-4 max-w-xs sm:max-w-md bg-white p-5 rounded-lg tracking-wide shadow-lg">
        <div id="header" class="flex">
            <i class="fas fa-users text-4xl text-green-300"></i>
            <div id="body" class="flex flex-col ml-5">
                <h4 class="text-xl font-semibold mb-2 text-green-500">Community Consensus</h4>
                <p class="text-gray-800 font-light mt-2">Built and founded by a strong community, Lumbung Network is an
                    open protocol governed by the community consensus. The community's voice shape the future of
                    platforms built on top its protocol. .</p>

            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
@include('finance.layout.footer')
@endsection