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
                            <img class="w-10 px-2" src="/image/logo_lumbung2.png" alt="logo lumbung network"
                                href="https://lumbung.network">
                            Lumbung Network
                        </div>
                        <div class="hidden lg:flex items-center justify-center antialiased lg:ml-20 pt-1">
                            <a href="https://lumbung.network"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Protokol

                            </a>
                            <a href="https://t.me/lumbungnetwork"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Community

                            </a>
                            <a href="#plans"
                                class="flex items-center justify-center mr-10 text-base text-gray-700 text-opacity-90 font-medium tracking-tight hover:text-cool-gray-600 transition duration-150 ease-in-out">
                                Membership Plans
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center justify-center px-3 mt-0">
                        <a href="/area/login"
                            class="lg:ml-16 mt-0 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-200 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                            Login</a>

                    </div>
                </div>
                <!-- /nav -->

                <!-- hero section -->
                <div class="py-3 flex justify-center lg:float-right lg:2/6 xl:w-2/4">
                    <img class="object-contain w-2/3" src="/image/lumbung-networking.png" alt="join lumbung network">
                </div>
                <div class="lg:2/6 xl:w-2/4 mt-18 lg:mt-40 lg:ml-16 text-left">
                    <h2 class="text-2xl lg:text-6xl font-light text-gray-700 leading-none">Jadi Member, <br>Jual/Beli
                        Lebih
                        Untung
                    </h2>
                    <p class="mt-6 text-md sm:text-lg lg:text-xl font-light text-gray-500 antialiased">Jadilah anggota
                        komunitas kami, untuk mendapatkan keuntungan penuh dari seluruh aktifitas pemenuhan kebutuhan
                        sehari-hari anda.</p>
                    <div class="mt-6">
                        <a href="#starter"
                            class="mt-6 px-4 py-2 sm:px-8 sm:py-4 rounded-full font-light text-sm sm:text-lg lg:text-xl tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-grey-800 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                            Coba Gratis!
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

<div class="mt-8 px-4 py-2">
    <div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
        <div class="mt-8 lg:w-1/2">
            <img class="object-contain w-full max-h-48 lg:max-h-72" src="/image/belanja-lumbung-network.png"
                alt="belanja di lumbung network">
        </div>
        <div class="lg:w-5/12">
            <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">Belanja
                Untung.</h3>
            <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Komunitas Lumbung Network percaya
                bahwa setiap kegiatan pemenuhan kebutuhan hidup sehari-hari memiliki <em>kekuatan</em> tersendiri, kita
                melakukannya setiap hari, seumur hidup kita. Pertanyaannya, apakah kita mendapatkan timbal baliknya?
                <br><br>
                Dengan berbelanja di Lumbung Network, anda membangun <em>aset</em> dari pengeluaran rutin, dan menerima
                hasil
                dari aset tersebut selamanya.
            </p>
        </div>
    </div>
    <div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
        <div class="lg:w-5/12">
            <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">Jualan
                Untung.</h3>
            <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Di Lumbung Network, pedagang bisa mendapatkan
                eksposur untuk usaha dagangnya, sekaligus berkontribusi memberikan bagi hasil kepada para pembelinya.
                Semuanya berlangsung otomatis dan didukung oleh sistem manajemen produk dan pembukuan yang canggih.
                <br><br>
                Dengan bergabung menjadi "Toko" di Lumbung Network, pedagang langsung mendapatkan akses ratusan Produk
                Digital dan Pembayaran Tagihan yang siap dijual dengan keuntungan yang kompetitif. Toko juga akan
                mendapatkan aset LMB dari setiap transaksinya.
            </p>
        </div>
        <div class="mt-8 lg:w-1/2">
            <img class="object-contain w-full max-h-48 lg:max-h-72" src="/image/toko-lumbung-network.png"
                alt="toko lumbung network">
        </div>
    </div>
    <div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
        <div class="mt-8 lg:w-1/2">
            <img class="object-contain w-full max-h-48" src="/image/eidr-coin.png" alt="eIDR">
        </div>
        <div class="lg:w-5/12">

            <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">
                Network Bonus dan Dividen.</h3>
            <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Full Member berhak untuk menerima
                Network Bonus dari kegiatan afiliasi (Sponsorship member baru, Pengembangan Jaringan Binary, dan Bonus
                Royalty dari downline yang aktif berbelanja hingga 7 generasi ke bawah) dan Distribusi Dividen LMB
                setiap hari (Staking Reward). <br><br>
                Starter Member (Free), hanya berhak untuk berbelanja dan menerima Reward LMB dari akumulasi belanjanya.
            </p>
        </div>

    </div>
</div>

<div class="container mx-auto px-4 py-24">

    <header class="text-center mb-16">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">
            Bergabung bersama kami.</h3>
        <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Anda bisa memilih untuk mencoba keanggotaan
            Gratis (Starter Member) terlebih dahulu, atau langsung menjadi Full Member bila anda berencana untuk
            berjualan atau membangun Network atau memaksimalkan hasil dari aset digital anda.
        </p>
    </header>

    <div id="plans" class="py-3 lg:flex lg:items-center lg:justify-center lg:-mx-2">

        <div id="starter" class="shadow-lg rounded-2xl w-64 bg-white dark:bg-gray-800 p-4">
            <p class="text-gray-800 dark:text-gray-50 text-xl font-medium mb-4">
                Starter
            </p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">
                Gratis!
            </p>
            <p class="text-gray-600 dark:text-gray-100  text-xs mt-4">
                Untuk anda yang ingin mencoba mengenali ekosistem Lumbung Network.
            </p>
            <ul class="text-sm text-gray-600 dark:text-gray-100 w-full mt-6 mb-6">
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Akses ke ekosistem Lumbung Network
                </li>
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Berbelanja dan menerima Reward LMB
                </li>

                <li class="mb-3 flex items-center opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" class="h-6 w-6 mr-2" fill="red"
                        viewBox="0 0 1792 1792">
                        <path
                            d="M1277 1122q0-26-19-45l-181-181 181-181q19-19 19-45 0-27-19-46l-90-90q-19-19-46-19-26 0-45 19l-181 181-181-181q-19-19-45-19-27 0-46 19l-90 90q-19 19-19 46 0 26 19 45l181 181-181 181q-19 19-19 45 0 27 19 46l90 90q19 19 46 19 26 0 45-19l181-181 181 181q19 19 45 19 27 0 46-19l90-90q19-19 19-46zm387-226q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Staking Reward (LMB Dividend)
                </li>
                <li class="mb-3 flex items-center opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" class="h-6 w-6 mr-2" fill="red"
                        viewBox="0 0 1792 1792">
                        <path
                            d="M1277 1122q0-26-19-45l-181-181 181-181q19-19 19-45 0-27-19-46l-90-90q-19-19-46-19-26 0-45 19l-181 181-181-181q-19-19-45-19-27 0-46 19l-90 90q-19 19-19 46 0 26 19 45l181 181-181 181q-19 19-19 45 0 27 19 46l90 90q19 19 46 19 26 0 45-19l181-181 181 181q19 19 45 19 27 0 46-19l90-90q19-19 19-46zm387-226q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Network Bonus
                </li>
                <li class="mb-3 flex items-center opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" class="h-6 w-6 mr-2" fill="red"
                        viewBox="0 0 1792 1792">
                        <path
                            d="M1277 1122q0-26-19-45l-181-181 181-181q19-19 19-45 0-27-19-46l-90-90q-19-19-46-19-26 0-45 19l-181 181-181-181q-19-19-45-19-27 0-46 19l-90 90q-19 19-19 46 0 26 19 45l181 181-181 181q-19 19-19 45 0 27 19 46l90 90q19 19 46 19 26 0 45-19l181-181 181 181q19 19 45 19 27 0 46-19l90-90q19-19 19-46zm387-226q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Membuka Toko (Berjualan)
                </li>
            </ul>
            <div class="flex justify-center">
                <a href="#"
                    class="px-4 py-2 rounded-lg font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-200 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                    Daftar Gratis
                </a>
            </div>

        </div>

        <div class="mt-4 shadow-lg rounded-2xl w-64 lg:-ml-5 lg:w-72 bg-white dark:bg-gray-800 p-4">
            <p class="text-gray-800 dark:text-gray-50 text-xl font-medium mb-4">
                Full Member
            </p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">
                Rp100.000,-

            </p>
            <p class="text-gray-600 dark:text-gray-100  text-xs mt-4">
                Berkontribusi pada ekosistem Lumbung Network, menjadi bagian dari gerakan ekonomi yang demokratis.
            </p>
            <ul class="text-sm text-gray-600 dark:text-gray-100 w-full mt-6 mb-6">
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Akses Penuh
                </li>
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Berbelanja dan menerima Reward LMB
                </li>
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Berhak untuk menjadi "Toko"
                </li>
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Staking Reward (LMB Dividend)
                </li>
                <li class="mb-3 flex items-center ">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="6" height="6"
                        stroke="currentColor" fill="#10b981" viewBox="0 0 1792 1792">
                        <path
                            d="M1412 734q0-28-18-46l-91-90q-19-19-45-19t-45 19l-408 407-226-226q-19-19-45-19t-45 19l-91 90q-18 18-18 46 0 27 18 45l362 362q19 19 45 19 27 0 46-19l543-543q18-18 18-45zm252 162q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z">
                        </path>
                    </svg>
                    Network Bonus
                </li>

            </ul>
            <div class="flex justify-center">
                <a href="#"
                    class="px-4 py-2 rounded-lg font-light tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-200 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                    Pilih Sponsor
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

@section('footer')
@include('finance.layout.footer')
@endsection