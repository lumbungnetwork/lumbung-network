@extends('member.components.main')
@section('content')
<div class="mt-4 px-4 py-2 lg:px-24">
    <div class="aspect-w-16 aspect-h-9">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/HN9qDw9XcNk" title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
    </div>
</div>
<div class="mt-8 px-4 py-2">
    <div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
        <div class="mt-8 lg:w-1/2">
            <img class="object-contain w-full max-h-48" src="/image/LumbungNetwork_hero.png" alt="lumbung network">
        </div>
        <div class="lg:w-5/12">
            <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">Jual.
                Beli.</h3>
            <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Di <em>marketplace</em> Lumbung Network,
                anda bisa berperan sebagai penjual atau pembeli, atau bahkan keduanya. <br><br>
                Mulai dari sembako, kebutuhan sehari-hari hingga produk-produk digital dan pembayaran tagihan bisa
                diperjualbelikan di marketplace Lumbung Network.
            </p>
        </div>
    </div>
    <div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
        <div class="lg:w-5/12">
            <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">Distribusi
                Network Value.</h3>
            <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Lumbung Network mendistribusikan SELURUH
                KEUNTUNGAN kembali kepada masyarakatnya dengan menggunakan model Tokenomic LMB dan LNS. <br><br>
                Untuk pembeli, setiap kelipatan belanja Rp1000,- akan menerima <strong>0.025 LMB</strong>, apabila
                akumulasi lebih dari
                Rp300.000,- per bulan akan mendapatkan bonus reward <strong>100%</strong> (Max 50 LMB). Untuk penjual,
                setiap kelipatan penjualan Rp1000,- akan menerima <strong>0.01 LMB</strong> (Max 200 LMB). <br><br>
                Reward dikalkulasi dan dapat di-claim setiap awal bulan.
            </p>
        </div>
        <div class="mt-8 lg:w-1/2">
            <img class="object-contain w-full max-h-48" src="/image/koin_lmb2.png" alt="koin LMB">
        </div>
    </div>
    <div class="mt-8 lg:mt-20 px-4 lg:px-24 lg:flex lg:flex-wrap">
        <div class="mt-8 lg:w-1/2">
            <img class="object-contain w-full max-h-48" src="/image/eidr-coin.png" alt="eIDR">
        </div>
        <div class="lg:w-5/12">

            <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">
                Bagi Hasil.</h3>
            <p class="font-extralight text-gray-700 sm:text-lg lg:text-xl">Bagi Hasil atau Dividen berasal dari setiap
                aktivitas yang berlangsung di atas protokol Lumbung Network. Aktivitas ini meliputi segala macam
                transaksi Jual-Beli di Marketplace, Iuran Keanggotaan, dan beragam kontribusi dari produk/program yang
                didirikan di atas Protokol Lumbung Network. <br><br>
                LMB dan LNS adalah kedua aset digital yang berperan sebagai media distribusi bagi hasil sesuai tokenomic
                yang sudah
                disepakati bersama di ekosistem Lumbung Network.
            </p>
        </div>

    </div>
</div>

<div class="mt-6 text-center">
    <div class="">
        <img class="object-contain w-full max-h-48 lg:max-h-96" src="/image/lumbung-network-teamwork.png"
            alt="lumbung finance defi farm">
    </div>
    <div class="px-4 lg:px-60 py-2 lg:py-6">
        <h3 class="my-2 text-yellow-400 text-opacity-75 font-extrabold text-3xl sm:text-4xl lg:text-6xl ">
            Oleh Kita, Untuk Kita.</h3>
        <p class="mt-6 sm:text-lg text-sm font-light text-gray-700 antialiased">Lumbung Network didirikan, dijalankan
            dan dibesarkan oleh komunitasnya sendiri. Dari berbagai kalangan dan daerah, bahu membahu mewujudkan
            cita-cita ekonomi yang demokratis, adil dan berpihak kepada masyarakat. <br><br>
            Mari, bergabunglah dengan komunitas Lumbung Network, dari silaturahmi kita bisa belajar dan saling
            mencerahkan!
        </p>


    </div>
    <div class="my-10">
        <a href="https://t.me/lumbungnetwork"
            class="mt-6 px-4 py-4 text-sm lg:text-xl lg:px-12 lg:py-6 rounded-full font-bold tracking-wide bg-gradient-to-r from-yellow-100 to-yellow-400 text-gray-600 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
            Gabung di Grup Telegram Kami!
        </a>
    </div>
</div>

@endsection

@section('footer')
@include('finance.layout.footer')
@endsection