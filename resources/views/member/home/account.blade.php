@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="#">
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>

        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg shadow bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/profile" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-account icon-menu"></i>
                                    </div>
                                    <dd>Profile</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/tron" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-diamond-stone icon-menu"></i>
                                    </div>
                                    <dd>Tron</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/bank" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-bank icon-menu"></i>
                                    </div>
                                    <dd>Bank</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/edit/password" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-lock icon-menu"></i>
                                    </div>
                                    <dd>Password</dd>
                                </div>
                            </a>
                        </div>

                        @php
                        $expiration = strtotime($dataUser->expired_at);
                        $timeleft = $expiration - time();
                        $daysleft = round((($timeleft/24)/60)/60);
                        @endphp
                        <div class="col-6 mb-3">
                            <dd>
                                Masa Aktif Keanggotaan
                            </dd>
                            @if($daysleft <= 0) <h5 class="text-danger">Keanggotaan anda sudah kadaluarsa</h5>
                                @endif
                                @if($daysleft > 0)
                                <h5 class="text-warning mb-0">{{$daysleft}}</h5>
                                <p class="f-12">Hari sebelum kadaluwarsa</p>
                                @endif
                        </div>
                        <div class="col-6 mb-3">

                            <a class="text-decoration-none " id="resubscribe-btn" data-toggle="modal"
                                data-target="#resubscribeModal">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-history icon-menu"></i>
                                    </div>
                                    <dd>Resubscribe</dd>
                                </div>
                            </a>
                            @if ($telegram == null)
                            <a class="text-decoration-none " onclick="telegram()">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-telegram icon-menu"></i>
                                    </div>
                                    <dd>Tautkan Telegram</dd>
                                </div>
                            </a>
                            @else
                            <a class="text-decoration-none " onclick="unlinkTelegram()">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-telegram icon-menu"></i>
                                    </div>
                                    <dd>Hapus Telegram</dd>
                                </div>
                            </a>
                            @endif
                        </div>

                    </div>
                </div>

                @if($dataUser->is_stockist == 1)
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Stockist saya</h6>
                    <div class="row">
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/seller/inventory" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-view-grid-plus-outline icon-menu"></i>
                                    </div>
                                    <dd>Inventory</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/seller/profile" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="fa fa-store icon-menu"></i>
                                    </div>
                                    <dd>Profil Toko</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/getpos" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-desktop-classic icon-menu"></i>
                                    </div>
                                    <dd>P.O.S</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/stockist-report" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-chart-bar icon-menu"></i>
                                    </div>
                                    <dd>Transaksi</dd>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @if($dataUser->is_vendor == 1)
                <div class="rounded-lg shadow-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Vendor Saya</h6>
                    <p>Produk Fisik</p>
                    <div class="row">
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/seller/inventory" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-view-grid-plus-outline icon-menu"></i>
                                    </div>
                                    <dd>Inventory</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/seller/profile" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="fa fa-store icon-menu"></i>
                                    </div>
                                    <dd>Profil Toko</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/getpos" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-desktop-classic icon-menu"></i>
                                    </div>
                                    <dd>P.O.S</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/vendor-report" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-chart-bar icon-menu"></i>
                                    </div>
                                    <dd>Transaksi</dd>
                                </div>
                            </a>
                        </div>
                    </div>
                    <p>Produk Digital</p>
                    <div class="row">
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/add/deposit" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-wallet-plus-outline icon-menu"></i>
                                    </div>
                                    <dd>Isi Deposit</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/tarik/deposit" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-logout-variant icon-menu"></i>
                                    </div>
                                    <dd>Tarik Deposit</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/deposit/history" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-history icon-menu"></i>
                                    </div>
                                    <dd>Riwayat Deposit</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/list/deposit-transaction" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-bank-transfer icon-menu"></i>
                                    </div>
                                    <dd>Transaksi Deposit</dd>
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="row">
                        <?php
                                $sum_deposit_masuk = 0;
                                $sum_deposit_keluar1 = 0;
                                $sum_deposit_keluar = 0;
                                if($dataDeposit->sum_deposit_masuk != null){
                                    $sum_deposit_masuk = $dataDeposit->sum_deposit_masuk;
                                }
                                if($dataDeposit->sum_deposit_keluar != null){
                                    $sum_deposit_keluar1 = $dataDeposit->sum_deposit_keluar;
                                }
                                if($dataTarik->deposit_keluar != null){
                                    $sum_deposit_keluar = $dataTarik->deposit_keluar;
                                }
                                $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_keluar1;
                                $locked = $onTheFly->deposit_out;
                            ?>
                        <div class="col-6 mb-3">
                            <p class="mb-0">Saldo Deposit</p>
                            <h5 class="text-warning mt-0"> Rp{{number_format($totalDeposit, 0, ',', '.')}}</h5>
                            <small class="text-danger">Locked: <strong>Rp{{number_format($locked)}}</strong></small>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/edit/2fa" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-lock-outline icon-menu text-warning"></i>
                                    </div>
                                    <dd>2FA PIN</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/list/vppob-transaction" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-bank-transfer icon-menu text-warning"></i>
                                    </div>
                                    <dd>Transaksi Digital</dd>
                                </div>
                            </a>
                        </div>
                    </div>


                </div>
                @endif
                @if($dataUser->is_stockist == 1)
                <a href="{{ URL::to('/') }}/m/stockist/penjualan-reward"
                    class="btn btn-warning btn-block shadow-sm mb-3">
                    <div class="f-14">Claim Reward Penjualan </div>
                </a>
                <!--<a href="#" class="btn btn-warning btn-block shadow-sm mb-3">  <div class="f-14">Claim Reward Penjualan  </div></a>-->
                @endif
                @if($dataUser->is_vendor == 1)
                <a href="{{ URL::to('/') }}/m/vendor/penjualan-reward" class="btn btn-warning btn-block shadow-sm mb-3">
                    <div class="f-14">Claim Reward Penjualan </div>
                </a>
                <!--                    <a href="#" class="btn btn-warning btn-block shadow-sm mb-3">  <div class="f-14">Claim Reward Penjualan  </div></a>-->
                @endif

            </div>
        </div>


        @include('layout.member.nav')




    </div>

    <!-- Resubscribe Modal -->
    <div class="modal fade" id="resubscribeModal" tabindex="-1" role="dialog" aria-labelledby="resubscribeModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Resubscribe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Perbarui keanggotaan tahunan anda dengan membakar (<i>burning</i>) <em>100 LMB</em>.
                    <br>
                    <i>Renew your annual membership by burning 100 LMB</i>
                    <div class="mt-3 float-right card rounded-lg bg-light p-4">
                        <div id="showAddress"></div>
                        <h6 class="text-success availableLMB">0 LMB</h6>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="mt-2 text-danger d-block" id="tronweb-warning">Use Tronlink or Dapp enabled
                        browser</small>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="tronwebPay" disabled>Resubscribe</button>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    let _token = '{{ csrf_token() }}';

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
                window.location.reload(true);
            }
        })
    }
    
    //TronWeb
    var toAddress = 'TLsV52sRDL79HXGGm9yzwKibb6BeruhUzy';
    var userAddress, tronWeb;
    const sendAmount = 100 * 1000000;
    
    $(document).ready(function () {
        setTimeout(function () {
            main()
        }, 200)
        console.log('ready');

    });
    
    
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
        if (LMBexist) {
        let LMBarray = await tokenBalancesArray.find(function (tokenID) {
        return tokenID.key == "1002640";
        });
        let LMBbalance = LMBarray.value / 1000000;

        
        
        if (LMBbalance >= 100) {
            if ({{$daysleft}} < 7) {
                $('#tronwebPay').attr("disabled", false); 
            }
            
            $('#tronweb-warning').remove();
        } else {
            $('#tronweb-warning').html('Not enough LMB Available');
        }
        
        $("#showAddress").html(`<dd class='text-secondary'>Active Wallet: <mark>${shortId(userAddress, 5)}</mark></dd> `);
        $(".availableLMB").html(`<h6 class='text-success'>Available: ${LMBbalance.toLocaleString("en-US")} LMB`);
        
        } else {
        $("#LMBbalance").html(`Alamat TRON ini tidak memiliki LMB`);
        }
    }
    
    //Pay using TronWeb service
    $("#tronwebPay").click(async function () {
        try {
            var tx = await tronWeb.transactionBuilder.sendAsset(
                toAddress,
                sendAmount,
                "1002640",
                userAddress,
            );
            
            var signedTx = await tronWeb.trx.sign(tx);
            var broastTx = await tronWeb.trx.sendRawTransaction(signedTx);
            if (broastTx.result) {
                postAJAXtronweb(broastTx.txid);
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
        
        
    });

    function postAJAXtronweb(hash) {
        Swal.fire('Sedang Memproses...');
        Swal.showLoading();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('/') }}/m/ajax/confirm-resubscribe",
            data: {
            hash:hash,
            _token:_token
            },
            success: function(response){
                if(response.success) {
                    Swal.fire(
                    'Success!',
                    'Resubscribe Berhasil!',
                    'success'
                    )
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 3000)
                } else {
                    Swal.fire(
                    'Oops!',
                    response.message,
                    'error'
                    )
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 5000)
                }

            }
        })
    }

        function telegram() {
            Swal.fire({
                title: 'Tautkan Telegram?',
                text: "Pastikan anda sudah memiliki Akun Telegram, anda akan diarahkan ke Telegram untuk Start/Mulai",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tautkan!',
                cancelButtonText: 'Tidak usah'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Lanjutkan Proses di Aplikasi Telegram Anda, Klik START/MULAI');
                    Swal.showLoading();
                    $.ajax({
                        type: "GET",
                        url: "{{ URL::to('/') }}/m/ajax/create-telegram-link",
                        success: function(response){
                        location.assign("https://t.me/LumbungNetworkBot?start=" + response.message);
                        }
                    });

                }
            })
        }

        function unlinkTelegram() {
            Swal.fire({
                title: 'Hapus Telegram?',
                text: "Yakin ingin memutuskan hubungan dengan akun Telegram yang terdaftar saat ini?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Jangan'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Memutuskan hubungan...');
                    Swal.showLoading();
                    $.ajax({
                        type: "GET",
                        url: "{{ URL::to('/') }}/m/ajax/remove-telegram-link",
                        success: function(response){
                            if (response.success) {
                                location.reload();
                            }

                        }
                    });

                }
            })
        }

</script>
@stop