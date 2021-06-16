@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        @if ($user->is_store)
        <div class="text-xs text-gray-600 font-light p-2 leading-4 text-center">
            Anda telah menjadi Toko
        </div>
        @else
        @if ($applied)
        <div class="text-xs text-gray-600 font-light p-2 leading-4 text-center">
            Anda telah mengajukan untuk menjadi Toko dan sedang menunggu proses moderasi dari Tim Delegasi, silakan
            hubungi Delegasi anda untuk mengetahui progress-nya.
        </div>
        @else
        <div class="nm-flat-gray-100 rounded-2xl p-2">
            <div class="text-xs text-gray-600 font-light p-2 leading-4">
                "Toko" adalah sebuah unit usaha yang bisa dimiliki oleh siapapun yang telah menjadi anggota di Komunitas
                Lumbung
                Network. <br><br>Dengan menjadi Toko, anda akan mendapatkan fasilitas toko digital yang bisa menjual
                berbagai
                produk fisik
                maupun digital. <br><br>Untuk produk digital, platform Lumbung Network sudah menyediakan beragam produk
                pilihan
                dengan
                keuntungan yang terjamin kompetitif.
            </div>
            <div class="mt-3 text-xs text-gray-600 font-light p-2 leading-4">
                <h5 class="my-2 text-sm text-gray-700">Syarat pengajuan Toko:</h5>
                <ol class="list-decimal">
                    <li>Bersedia untuk dibina oleh Tim Delegasi setempat</li>
                    <li>Bersedia untuk berkontribusi 2% dari setiap penjualan di Tokonya</li>
                    <li>Bersedia untuk membakar (Burn) 100 LMB untuk pengajuan ini</li>
                    <li>Berkomitmen untuk mensejahterakan masyarakat melalui desentralisasi ekonomi</li>
                </ol>
            </div>
            <div class="mt-3 text-xs text-gray-600 font-light p-2 leading-4">
                <h5 class="my-2 text-sm text-gray-700">Silakan pilih Delegasi anda:</h5>
                <form action="" method="POST" id="apply-form">
                    @csrf
                    <div class="nm-inset-gray-200 p-2 rounded-lg">
                        <select class="bg-transparent w-full focus:outline-none" name="delegate" id="delegate">
                            <option value="0">--Pilih Delegasi--</option>
                            @foreach ($delegates as $delegate)
                            <option value="{{ $delegate->name }}">{{ $delegate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="hash" id="hash">
                </form>
                {{-- Active Wallet info --}}
                <div class="mb-0" id="showAddress"></div>
                <div class="availableLMB"></div>
                <p id="tronweb-warning" class="text-xs font-medium text-red-600">Gunakan Tronlink, TokenPocket atau
                    Browser DAPP
                    lainnya.</p>
                <div class="mt-3 flex justify-end">
                    <button id="apply-btn" disabled
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Apply</button>
                </div>
            </div>
        </div>
        @endif

        @endif
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    // Doc ready func
    $(document).ready(function () {
        setTimeout(function () {
            main()
        }, 200)
    });

    var _LMBbalance = 0;
    let _token = '{{ csrf_token() }}';

    //TronWeb
    var toAddress = 'TLsV52sRDL79HXGGm9yzwKibb6BeruhUzy';
    var userAddress, tronWeb;
    const sendAmount = 100000000;
    var waiting = 0;
    
    async function main() {
        if (!(window.tronWeb && window.tronWeb.ready)) return (waiting += 1, 50 == waiting) ? void console.log('Failed to connect to TronWeb') : (console.warn("main retries", "Could not connect to TronLink.", waiting), void setTimeout(main,
        500));
        tronWeb = window.tronWeb;
        try {
            await showTronBalance();
        } catch (a) {
            console.log(a);
        }
    
    }
    
    function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }
    
    //show LMB balance
    async function showTronBalance() {
        userAddress = tronWeb.defaultAddress.base58;
        let tokenBalancesArray;
        let balanceCheck = await tronWeb.trx
        .getAccount(userAddress)
        .then((result) => (tokenBalancesArray = result.assetV2));
        balanceCheck;

        let LMBexist = await tokenBalancesArray.some(function (tokenID) {
        return tokenID.key == "1002640";
        });

        $("#showAddress").html(`<p class='text-gray-600 text-xs font-light'>Active Wallet: <span
                class='font-medium text-sm'>${shortId(userAddress, 5)}</span></p> `);

        if (LMBexist) {
            let LMBarray = await tokenBalancesArray.find(function (tokenID) {
            return tokenID.key == "1002640";
            });
            let LMBbalance = LMBarray.value / 1000000;
            
            if (LMBbalance > 0) {
                $('#apply-btn').attr("disabled", false);
                $('#tronweb-warning').remove();
                _LMBbalance = LMBbalance;
            } else {
                $('#tronweb-warning').html('No LMB Available');
            }
            
            
            $(".availableLMB").html(`<small class='text-gray-500 font-light'>Available: ${LMBbalance.toLocaleString("en-US")} LMB`);
        
        } else {
            $("#tronweb-warning").html(`Alamat TRON ini tidak memiliki LMB`);
        }
    }

    $('#apply-btn').click( async function () {
        if (_LMBbalance < 100) {
            Swal.fire('Oops', 'Anda tidak memiliki cukup LMB untuk transaksi ini', 'error');
            return false;
        }
        if ($('#delegate').val() == 0) {
            Swal.fire('Oops', 'Anda belum memilih Delegasi', 'error');
            return false;
        }

        Swal.fire({
            title: 'Apply Toko',
            text: "Pengajuan Toko ini akan membakar 100 LMB, ingin ajukan sekarang?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Nanti!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                try {
                    var tx = await tronWeb.trx.sendToken(
                        toAddress,
                        sendAmount,
                        "1002640"
                    );

                    if (tx.transaction.txID !== undefined) {
                        postAJAXtronweb(tx.transaction.txID);
                    } else {
                        eAlert('Transaksi Gagal, periksa koneksi dan ulangi kembali');
                    }
                } catch (e) {
                    if (e.includes("assetBalance is not sufficient")) {
                        eAlert("Saldo LMB tidak mencukupi");
                    } else if (e.includes("assetBalance must be greater than")) {
                        eAlert("Alamat TRON ini tidak memiliki LMB");
                    } else if (e.includes("declined by user")) {
                        eAlert("Anda membatalkan Transaksi");
                    } else if (e.includes("cancle")) {
                        eAlert("Anda membatalkan Transaksi");
                    } else {
                        eAlert("Ada yang salah, restart aplikasi wallet ini.")
                    }
                }
            }
        })

    })

    function postAJAXtronweb(hash) {
        Swal.fire('Sedang Memproses...');
        Swal.showLoading();
        $('#hash').val(hash);
        $('#apply-form').submit();
    }

    //TronWeb Validation by Swal
    function eAlert(message) {
        Swal.fire({
            title: 'Gagal!',
            text: message,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }).then((result) => {
                if (result.isConfirmed) {
                let href = window.location.href;
                setTimeout( () => location.replace(href), 500);
            }
        })
    }
</script>
@endsection