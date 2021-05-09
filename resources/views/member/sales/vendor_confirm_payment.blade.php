@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <input type="hidden" id="isTronWeb" value="0">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                        <i class="fa fa-arrow-left"></i> Beranda
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">{{$headerTitle}}</h6>
                    <span id="showAddress"></span>
                    <div style="margin-top: -10px; margin-bottom: 10px;">
                        <dd style="display: inline; margin-right: 15px">Dompet Lokal</dd>
                        <div style="font-size: 13px;" class="pretty p-switch p-fill">
                            @if($eIDRbalance != null)
                            <input type="checkbox" id="is_active_switch" checked>
                            <div class="state p-success p-on">
                                <label id="is_active_status">Aktif</label>
                            </div>
                            @else
                            <input type="checkbox" id="is_active_switch">
                            <div class="state p-success p-off">
                                <label id="is_active_status">Non-Aktif</label>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            Pembeli: <strong>{{$getDataSales->username}}</strong>

                            <?php
                                $confirm = 'Konfirmasi Pembayaran';
                                $text = 'Konfirmasi';
                                $status = 'proses pembeli';
                                $label = 'info';
                                if($getDataSales->status == 1){
                                    $status = 'menunggu konfirmasi';
                                    $label = 'warning';
                                }
                                if($getDataSales->status == 2){
                                    $status = 'TUNTAS';
                                    $label = 'success';
                                }
                                if($getDataSales->status == 10){
                                    $status = 'BATAL';
                                    $label = 'danger';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <small><strong>Tanggal Order:
                                </strong>{{date('d F Y', strtotime($getDataSales->sale_date))}}
                            </small><br>
                            <small class="m-t-10"><strong>Order Status: </strong> <span
                                    class="label label-{{$label}}">{{$status}}</span></small>
                            @if($getDataSales->status == 10)
                            <small>{{$getDataSales->reason}}</small>
                            @endif
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table m-t-30">
                                <thead class="bg-faded">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getDataItem != null)
                                    <?php $no = 0; ?>
                                    @foreach($getDataItem as $row)
                                    <?php
                                                $no++;
                                            ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->size}} {{$row->name}}</td>
                                        <td>{{number_format($row->amount, 0, ',', '')}}</td>
                                        <td>{{number_format($row->sale_price, 0, ',', '.')}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted"><strong> Total Belanja Member</strong></small>
                            <h5>Rp{{number_format($getDataSales->sale_price)}}</h5>
                            @if($getDataSales->status == 1)
                            <?php $royalti = $getDataSales->sale_price * 2 / 100 ?>
                            <small class="text-muted"><strong> Kontribusi Bagi Hasil</strong></small>
                            <h5>Rp{{number_format($royalti)}}</h5>
                            <small class="text-muted"><strong> Saldo eIDR anda</strong></small>
                            <h5 id="eIDRbalance">Rp{{number_format(0)}}</h5>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            @if($getDataSales->status == 1)
                            <form id="form-confirm" method="POST" action="/m/payment-confirmation">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{$getDataSales->id}}" name="masterSalesID"
                                    id="masterSalesID">
                                <input type="hidden" value="0" name="userTron" id="userTron">
                                <input type="hidden" value="0" name="hash" id="hash">
                                <input type="hidden" value="2" name="sellerType" id="sellerType">

                            </form>
                            <form id="form-reject" method="POST" action="/m/reject-shopping">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{$getDataSales->id}}" name="masterSalesID"
                                    id="masterSalesID">
                                <input type="hidden" value="2" name="sellerType">

                            </form>
                            <button class="btn btn-danger" onclick="reject()">Batal</button>

                            <button class="btn btn-success" id="confirmButton" disabled>Konfirmasi</button>
                            @endif

                            @if ($getDataSales->status == 2)
                            <a class="btn btn-warning" href="/m/print-shopping-receipt/{{$getDataSales->id}}">Print
                                Struk</a>
                            @endif


                        </div>
                    </div>

                </div>
            </div>
            @include('layout.member.nav')
        </div>
        <div class="overlay"></div>
    </div>

    @stop


    @section('styles')
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

    @stop

    @section('javascript')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
    </script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    @if($getDataSales->status == 1)
    <script>
        let _token = '{{ csrf_token() }}';

        $('#is_active_switch').click(function(){
            if($(this).is(':checked')){
                $('#is_active_status').text('Aktif');
                toggleLocalWallet(1);
            }else{
                $('#is_active_status').text('Non-Aktif');
                toggleLocalWallet(0);
            }
        });

        function toggleLocalWallet(value) {
            $.ajax({
                type: "POST",
                url: "{{ URL::to('/') }}/m/ajax/toggle-local-wallet",
                data: {
                    is_active:value,
                    _token:_token
                },
                success: function(response){
                    if(response.success) {
                        Swal.fire(
                        'Berhasil',
                        response.message,
                        'success'
                        )
                        setTimeout(function() {
                        window.location.reload(true);
                        }, 2000)
                    } else {
                        Swal.fire(
                        'Gagal',
                        response.message,
                        'error'
                        )
                    }

                }
            })
        }

            function reject() {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Anda ingin membatalkan pesanan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Jangan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-reject').submit();
                    }
                })
            }

        function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }

        @if($eIDRbalance == null)

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
            var toAddress = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
            var userAddress, tronWeb;
            let sendAmount = 0;

            $(document).ready(function () {
                setTimeout(function () {
                    main()
                }, 200)
                console.log('ready');
            });

            setTimeout(function(){
                if($('#isTronWeb').val() > 0 && $('#eidr-balance').val() >= {{$royalti}}){
                    $('#confirmButton').attr("disabled", false);
                }
            }, 2000);

            var waiting = 0;

            async function main() {
                if (!(window.tronWeb && window.tronWeb.ready)) return (waiting += 1, 50 == waiting) ? void console.log('Failed to connect to TronWeb') : (console.warn("main retries", "Could not connect to TronLink.", waiting), void setTimeout(main, 500));
                tronWeb = window.tronWeb;
                tronWeb.setHeader({ "TRON-PRO-API-KEY": 'eb052158-9f47-4a63-85ac-a2ae28bc1dd7' });
                try {
                    await showTronBalance();
                } catch (a) {
                    console.log(a);
                }

            }


            //show eIDR balance
            async function showTronBalance() {
                userAddress = tronWeb.defaultAddress.base58;
                let tokenBalancesArray;
                let balanceCheck = await tronWeb.trx
                    .getAccount(userAddress)
                    .then((result) => (tokenBalancesArray = result.assetV2));
                balanceCheck;
                let eIDRexist = await tokenBalancesArray.some(function (tokenID) {
                    return tokenID.key == "1002652";
                });
                if (eIDRexist) {
                    let eIDRarray = await tokenBalancesArray.find(function (tokenID) {
                        return tokenID.key == "1002652";
                    });
                    let eIDRbalance = eIDRarray.value / 100;

                    if (eIDRbalance >= {{$royalti}}) {
                        $('#confirmButton').attr("disabled", false);
                    }

                    $("#eIDRbalance").html(
                        `Rp${eIDRbalance.toLocaleString("en-US")}
                        `
                    );

                    $("#userTron").val(userAddress);
                    $("#showAddress").html(`<p>Active Wallet: <mark>${shortId(userAddress, 5)}</mark></p> `);
                    $('#isTronWeb').val(1);

                } else {
                    $("#eIDRbalance").html(`Alamat TRON ini tidak memiliki eIDR`);
                }
            }



            //Pay using TronWeb service
            $("#confirmButton").click(async function () {
                sendAmount = {{$royalti}} * 100;
                
                try {
                    var tx = await tronWeb.transactionBuilder.sendAsset(
                        toAddress,
                        sendAmount,
                        "1002652",
                        userAddress,
                    );

                    var signedTx = await tronWeb.trx.sign(tx);
                    var broastTx = await tronWeb.trx.sendRawTransaction(signedTx);
                    if (broastTx.result) {
                        $('#hash').val(broastTx.txid);
                        $('#form-confirm').submit();
                        Swal.fire('Sedang Memproses');
                        Swal.showLoading();

                    } else {
                        eAlert('Transaksi Gagal, periksa koneksi dan ulangi kembali');
                    }
                } catch (e) {
                    if (e.includes("assetBalance is not sufficient")) {
                        eAlert("Saldo eIDR tidak mencukupi");
                    } else if (e.includes("assetBalance must be greater than")) {
                        eAlert("Alamat TRON ini tidak memiliki eIDR");
                    } else if (e.includes("declined by user")) {
                        eAlert("Anda membatalkan Transaksi");
                    } else if (e.includes("cancle")) {
                        eAlert("Anda membatalkan Transaksi");
                    } else {
                        eAlert("Ada yang salah, restart aplikasi wallet ini.")
                    }
                }
            });

        @else

            $(document).ready(function () {
                setTimeout(function () {
                    showeIDRbalanceLocalWallet()
                }, 200)
            });

            //show eIDR balance from localWallet
            function showeIDRbalanceLocalWallet() {
                let localAddress = '{{$localAddress}}';
                let eIDRbalance = {{$eIDRbalance}};
                if (eIDRbalance >= {{$royalti}}) {
                    $('#confirmButton').attr("disabled", false);
                }

                $("#eIDRbalance").html(
                    `Rp${eIDRbalance.toLocaleString("en-US")}
                    `
                );

                $("#userTron").val(localAddress);
                $("#showAddress").html(`<p>Active Wallet: <mark>${shortId(localAddress, 5)}</mark></p> `);

            }

            //pay using localWallet
            $("#confirmButton").click(async function () {
                Swal.fire({
                    title: 'Konfirmasi Belanja',
                    text: "Anda ingin mengkonfirmasi pesanan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, konfirmasi!',
                    cancelButtonText: 'Jangan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        payByLocalWallet();
                    }
                })


            });

            function payByLocalWallet() {
                var masterSalesID = {{$getDataSales->id}};
                $.ajax({
                        type: "POST",
                        url: "{{ URL::to('/') }}/m/ajax/pay-by-local-wallet-vendor",
                        data: {
                            masterSalesID:masterSalesID,
                            _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                'Berhasil',
                                'Belanja member telah dikonfirmasi.',
                                'success'
                                )
                                setTimeout(function() {
                                    window.location.reload(true);
                                }, 3000)
                            } else {
                                Swal.fire(
                                'Gagal',
                                response.message,
                                'error'
                                )
                            }

                        }
                    })
            }

            @endif
    </script>

    @endif


    @stop