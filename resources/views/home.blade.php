@extends('finance.layout.app')
@section('content')

<div class="mt-8 min-w-full flex flex-col justify-center px-3 sm:px-4">
    <div class="relative w-full max-w-2xl lg:max-w-6xl xl:max-w-screen-2xl mx-auto">
        <div
            class="absolute inset-0 -mr-3.5 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative bg-white shadow-lg rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="px-4 lg:px-20 py-6">

                <!-- nav -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-center">
                        <div class="hidden lg:flex items-center justify-center text-xl font-light text-true-gray-800">
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
                <div class="py-3 lg:float-right lg:2/6 xl:w-2/4">
                    <img class="object-contain w-full lg:max-h-96" src="/image/LumbungNetwork_hero.png"
                        alt="jual beli lumbung network">
                </div>
                <div class="lg:2/6 xl:w-2/4 mt-18 lg:mt-40 lg:ml-16 text-left">
                    <h2 class="text-2xl lg:text-6xl font-light text-gray-700 leading-none">Memberdayakan
                        Pengeluaran Menjadi
                        Penghasilan
                    </h2>
                    <p class="mt-6 text-sm sm:text-lg lg:text-xl font-light text-gray-500 antialiased">Lumbung Network
                        adalah
                        sebuah protokol terdesentralisasi untuk mengembalikan ekonomi ke tangan rakyat melalui berbagai
                        sistem retail dan finansial yang aktif memberi kontribusi bagi hasil ke masyarakatnya.</p>
                    <div class="mt-6">
                        <a href="https://member.{{ config('services.app.url') }}"
                            class="mt-6 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light text-sm sm:text-lg lg:text-xl tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-grey-800 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                            Buka WebApp
                        </a>
                    </div>
                </div>
                <div class="mt-12 lg:mt-32 lg:ml-20 text-left">
                    <bottom
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

<div class="mt-8 lg:mt-20 px-4 lg:px-24">
    <h3
        class="my-2 lg:my-0 text-yellow-400 text-opacity-75 font-extrabold text-center lg:float-right lg:w-1/2 text-3xl sm:text-4xl lg:text-6xl ">
        Kesadaran Kolektif.</h3>
    <p class="mt-6 sm:text-lg text-sm font-light lg:w-1/2 text-gray-700 antialiased">Lumbung
        Network adalah
        perwujudan dari
        kesadaran kolektif masyarakat untuk menciptakan ekosistem ekonomi yang sehat dan berpihak kepada masyarakat.
        <br><br>
        Lumbung Network dibentuk oleh komunitas konsumen, pedagang dan pemasar bahan pokok dan kebutuhan sehari-hari
        sebagai sebuah protokol terdesentralisasi yang mendistribusikan seluruh keuntungan kembali kepada masyarakatnya.
    </p>
</div>

<div class="mt-8 px-4 lg:px-10 lg:flex">
    <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-center text-3xl sm:text-4xl lg:text-6xl ">
        Non-profit, tapi Menguntungkan.</h3>
    <p class="mt-6 sm:text-lg text-sm font-light lg:items-end lg:w-1/2 text-gray-700 antialiased">Di era
        teknologi
        informasi dan internet ini,
        ada banyak platform marketplace yang bertumbuh besar. Semuanya mengambil poros tengah, mempertemukan pembeli
        dengan penjual, penyedia layanan dengan pengguna layanan, lalu mengambil keuntungan dari semua pihak.
        <br><br>
        Lumbung Network berdiri melawan arus, platform ini didirikan secara kolektif TANPA PEMILIK, TANPA PROFIT,
        seluruh keuntungan yang dihasilkannya didistribusikan kembali sebagai bagi hasil kepada para penggunanya secara
        adil dan transparan.
    </p>
</div>

<div class="mt-8 px-4 lg:px-10 lg:flex lg:flex-wrap lg:justify-center">
    <div class="p-3 rounded-2xl nm-flat-gray-50 lg:w-2/5">
        <div class="my-2 flex flex-col items-center">
            <p class="font-extralight text-gray-600 text-center text-2xl lg:text-4xl animate-pulse statistics bg-gray-400" id="dividends">
                Rp000,000,000.00
            </p>
            <p class="text-sm lg:text-xl font-light text-center text-gray-600">Bagi Hasil</p>
        </div>
        <div class="my-2 flex flex-col items-center">
            <p class="font-extralight text-gray-600 text-center text-2xl lg:text-4xl animate-pulse statistics bg-gray-400" id="bonuses">
                Rp000,000,000.00</p>
            <p class="text-sm lg:text-xl font-light text-center text-gray-600">Network Bonus</p>
        </div>
        <p class="text-sm lg:text-xl font-light text-center text-gray-600">...telah didistribusikan dari total:</p>
        <div class="my-2 flex flex-col items-center">
            <p class="font-extralight text-gray-600 text-center text-2xl lg:text-4xl animate-pulse statistics bg-gray-400" id="sales">
                Rp000,000,000.00</p>
            <p class="text-sm lg:text-xl font-light text-center text-gray-600">Nilai Transaksi</p>
            <p class="text-sm lg:text-xl font-light text-center text-gray-600">Sejak November 2019</p>
        </div>

    </div>
    <div class="mt-4 lg:w-3/5">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-center text-3xl sm:text-4xl lg:text-8xl ">
            Bertumbuh. Bersama.</h3>
    </div>
</div>

<div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
    <div class="lg:w-1/2">
        <img class="object-contain" src="/image/blockchain-verify.png" alt="blockchain-verify">
    </div>
    <div class="mt-6 lg:w-1/2">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-center text-3xl sm:text-4xl lg:text-6xl ">
            Teknologi Masa Depan.</h3>
        <p class="mt-6 sm:text-lg text-sm font-light  text-gray-700 antialiased">Seluruh ekosistem
            Lumbung Network dikembangkan dengan upaya menuju desentralisasi finansial, menggunakan teknologi blockchain
            untuk menangkap Network Value dari setiap aktivitasnya dan mendistribusikan bagi hasil secara adil,
            transparan
            dan tanpa intervensi manusia.
        </p>
    </div>
</div>

<div class="mt-8 px-4 lg:mt-20">
    <div class="mt-6 text-center">
        <a href="/about-core"
            class="mt-6 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light text-sm sm:text-lg lg:text-xl tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-grey-800 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
            Pelajari Lebih Lanjut
        </a>

    </div>
    <div class="lg:flex lg:justify-center lg:px-20 lg:text-center">
        <p class="mt-6 lg:w-1/2 sm:text-lg lg:text-2xl text-sm font-light  text-gray-700 antialiased">Pahami lebih
            detail, bagaimana
            Lumbung
            Network mewujudkan solusi untuk memberdayakan Pengeluaran Sehari-hari menjadi Sumber Pemasukan.
        </p>
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

@endsection

@section('footer')
@include('finance.layout.footer')
@endsection

@section('scripts')
<script>
    $( function () {
            setTimeout(function () {
                main()
            }, 200)
        })

        function main() {
            $.ajax({
                type: "GET",
                url: "{{ route('api.v2.main.statistic.overview') }}",
                success: function(data) {
                    
                    setTimeout( () => {
                        $('#dividends').html('Rp.' + data.dividend.toLocaleString('EN-us'));
                        $('#bonuses').html('Rp.' + data.bonus.toLocaleString('EN-us'));
                        $('#sales').html('Rp.' + data.sales.toLocaleString('EN-us'));
                        $('.statistics').removeClass("animate-pulse bg-gray-400");
                    }, 2000)
                    
                }
            });
        }
</script>
@endsection