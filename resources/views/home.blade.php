@extends('layouts.app')
@section('body')

<body class="bg-gray-100">
    <div class="mt-8 min-w-full flex flex-col justify-center px-4">
        <div class="relative w-full max-w-2xl lg:max-w-6xl xl:max-w-screen-2xl mx-auto">
            <div
                class="absolute inset-0 -mr-3.5 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
            </div>
            <div class="relative bg-white shadow-lg rounded-lg sm:rounded-3xl">

                <div class="hidden md:flex items-center justify-start pt-6 pl-6">
                    <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                </div>

                <div class="px-10 lg:px-20 py-6">

                    <!-- nav -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-center">
                            <div
                                class="hidden lg:flex items-center justify-center text-xl font-light text-true-gray-800">
                                <img class="w-10 px-2" src="/image/logo_lumbung2.png" alt="logo lumbung network">
                                Lumbung Network
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
                        <div class="hidden md:flex items-center justify-center">
                            <a href="https://member.{{Config::get('services.app.url')}}"
                                class="mr-5 text-lg font-medium text-true-gray-800 hover:text-cool-gray-700 transition duration-150 ease-in-out">Login</a>
                            <button
                                class="px-6 py-3 rounded-3xl font-medium bg-gradient-to-b from-gray-900 to-black text-white outline-none focus:outline-none hover:shadow-md hover:from-true-gray-900 transition duration-200 ease-in-out">Sign
                                Up</button>
                        </div>
                    </div>
                    <!-- /nav -->

                    <!-- hero section -->
                    <div class="lg:float-right lg:2/6 xl:w-2/4">
                        <img class="object-contain w-full lg:max-h-96" src="/image/LumbungNetwork_hero.png"
                            alt="jual beli lumbung network">
                    </div>
                    <div class="lg:2/6 xl:w-2/4 mt-18 lg:mt-40 lg:ml-16 text-left">
                        <div class="text-4xl lg:text-6xl font-semibold text-gray-900 leading-none">Memberdayakan
                            Pengeluaran Menjadi
                            Penghasilan
                        </div>
                        <div class="mt-6 text-xl font-light text-true-gray-500 antialiased">Lumbung Network adalah
                            sebuah protokol terdesentralisasi untuk mengembalikan ekonomi ke tangan rakyat.</div>
                        <button
                            class="mt-6 px-8 py-4 rounded-full font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-grey-800 outline-none focus:outline-none hover:shadow-lg hover:from-blue-700 transition duration-200 ease-in-out">
                            Pelajari Lebih Lanjut
                        </button>
                    </div>
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

    <!-- Destination Cards -->
    <div class="p-24 flex flex-wrap items-center justify-center" id="destinations">

        <div class="flex-shrink-0 m-6 relative overflow-hidden bg-yellow-400 rounded-lg max-w-xs shadow-lg">
            <a href="https://member.{{Config::get('services.app.url')}}">
                <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                    style="transform: scale(1.5); opacity: 0.2;">
                    <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                        fill="white" />
                    <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
                </svg>
                <div class="relative pt-10 px-10 flex items-center justify-center">
                    <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                        style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                    </div>
                    <img class="relative w-40" src="/image/login.svg" alt="login-icon">
                </div>
                <div class="relative text-white px-6 pb-6 mt-6">
                    <span class="block opacity-95 -mb-1">Main App</span>
                    <div class="flex justify-between">
                        <span class="block font-semibold text-xl">Member Login</span>
                        <span
                            class="bg-white rounded-full text-yellow-500 text-xs font-bold px-3 py-2 leading-none flex items-center">Go
                            &#10149;</span>
                    </div>
                </div>
            </a>

        </div>
        <div class="flex-shrink-0 m-6 relative overflow-hidden bg-green-400 rounded-lg max-w-xs shadow-lg">
            <a href="https://finance.{{Config::get('services.app.url')}}">
                <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                    style="transform: scale(1.5); opacity: 0.1;">
                    <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                        fill="white" />
                    <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
                </svg>
                <div class="relative pt-10 px-10 flex items-center justify-center">
                    <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                        style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                    </div>
                    <img class="relative w-40" src="/image/lumbung-finance_1.png" alt="lumbung finance">
                </div>
                <div class="relative text-white px-6 pb-6 mt-6">
                    <span class="block opacity-95 -mb-1">Fintech</span>
                    <div class="flex justify-between">
                        <span class="block font-semibold text-xl">Lumbung Finance</span>

                    </div>
                </div>
            </a>
        </div>

        <div class="flex-shrink-0 m-6 relative overflow-hidden bg-purple-500 rounded-lg max-w-xs shadow-lg">
            <a href="#">
                <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                    style="transform: scale(1.5); opacity: 0.1;">
                    <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                        fill="white" />
                    <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
                </svg>
                <div class="relative pt-10 px-10 flex items-center justify-center">
                    <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                        style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                    </div>
                    <img class="relative w-40" src="/image/marketplace.svg" alt="marketplace icon">
                </div>
                <div class="relative text-white px-6 pb-6 mt-6">
                    <span class="block opacity-95 -mb-1">Service</span>
                    <div class="flex justify-between">
                        <span class="block font-semibold text-xl">Marketplace</span>
                        <span
                            class="bg-white rounded-full text-purple-500 text-xs font-bold px-3 py-2 leading-none flex items-center">Go
                            &#10149;</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="flex-shrink-0 m-6 relative overflow-hidden bg-yellow-400 rounded-lg max-w-xs shadow-lg">
            <a href="https://member.{{Config::get('services.app.url')}}">
                <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                    style="transform: scale(1.5); opacity: 0.2;">
                    <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                        fill="white" />
                    <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
                </svg>
                <div class="relative pt-10 px-10 flex items-center justify-center">
                    <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                        style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                    </div>
                    <img class="relative w-40" src="/image/koin_lmb.png" alt="koin LMB">
                </div>
                <div class="relative text-white px-6 pb-6 mt-6">
                    <span class="block opacity-95 -mb-1">Native Asset</span>
                    <div class="flex justify-between">
                        <span class="block font-semibold text-xl">LMB & LNS</span>
                        <span
                            class="bg-white rounded-full text-yellow-500 text-xs font-bold px-3 py-2 leading-none flex items-center">Go
                            &#10149;</span>
                    </div>
                </div>
            </a>

        </div>
        <div class="flex-shrink-0 m-6 relative overflow-hidden bg-green-400 rounded-lg max-w-xs shadow-lg">
            <a href="https://eidr.{{Config::get('services.app.url')}}">
                <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                    style="transform: scale(1.5); opacity: 0.1;">
                    <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                        fill="white" />
                    <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
                </svg>
                <div class="relative pt-10 px-10 flex items-center justify-center">
                    <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                        style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                    </div>
                    <img class="relative w-40" src="/image/eidr-coin.png" alt="eidr coin">
                </div>
                <div class="relative text-white px-6 pb-6 mt-6">
                    <span class="block opacity-95 -mb-1">Utility</span>
                    <div class="flex justify-between">
                        <span class="block font-semibold text-xl">eIDR</span>
                        <span
                            class="bg-white rounded-full text-green-500 text-xs font-bold px-3 py-2 leading-none flex items-center">Go
                            &#10149;</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="flex-shrink-0 m-6 relative overflow-hidden bg-purple-500 rounded-lg max-w-xs shadow-lg">
            <a href="#">
                <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                    style="transform: scale(1.5); opacity: 0.1;">
                    <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                        fill="white" />
                    <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
                </svg>
                <div class="relative pt-10 px-10 flex items-center justify-center">
                    <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                        style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                    </div>
                    <img class="relative w-40" src="/image/blockchain-verify.png" alt="blockchain verify">
                </div>
                <div class="relative text-white px-6 pb-6 mt-6">
                    <span class="block opacity-95 -mb-1">Service</span>
                    <div class="flex justify-between">
                        <span class="block font-semibold text-xl">Block Explorer</span>

                    </div>
                </div>
            </a>
        </div>

    </div>
</body>

@endsection

@section('footer')
@include('layouts.footer')
@endsection