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
                <?php
                    $sellerType = 1;
                    if($getSeller->is_stockist == 0) {
                        $sellerType = 2;
                    }
                ?>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Pembayaran Belanja</h6>
                    <span id="showAddress"></span>
                    <small>Seller: <strong><a class="text-info"
                                href="{{ URL::to('/') }}/m/shopping/{{$getSeller->id}}">{{$shopName}}</a></strong></small>

                </div>
                <div class="rounded-lg bg-white px-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                        </div>
                        <div class="table-responsive p-2">
                            <small class="ml-2 text-muted">{{$getDataMaster->invoice}}</small>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>@</th>
                                        <th>Total(Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getDataSales != null)
                                    @foreach($getDataSales as $row)
                                    <tr>
                                        <td><b>{{number_format($row->amount, 0, ',', '')}}x</b> {{$row->name}}
                                            {{$row->size}}</td>
                                        <td>{{number_format(($row->sale_price/$row->amount), 0, ',', ',')}}</td>
                                        <td>{{number_format($row->sale_price, 0, ',', ',')}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><b>Total</b></td>
                                        <td><b>{{number_format($getDataMaster->total_price, 0, ',', ',')}}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    @if($getDataMaster->status == 0)
                    <p class="card-text">Metode Pembayaran</p>
                    <div class="accordion mt-2" id="accordionExample">
                        @if($getDataMaster->buy_metode == 0)
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h1 class="mb-0">
                                    <button class="btn btn-outline-primary btn-lg" id="tunaibutton" type="button"
                                        data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        Bayar Tunai
                                    </button>
                                </h1>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio1" value="1">
                                        <label for="radio1">

                                        </label>
                                    </div>
                                    <small class="text-info">Pembayaran Tunai langsung kepada penjual, memerlukan
                                        konfirmasi
                                        manual oleh penjual setelah
                                        pembayaran lunas.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h1 class="mb-0">
                                    <button class="btn btn-outline-warning btn-lg" id="eidrbutton" type="button"
                                        data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Bayar via eIDR
                                    </button>
                                </h1>
                            </div>
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio2" value="3" checked>
                                        <label for="radio2">
                                            Transfer eIDR ke alamat ini:
                                            <br>
                                            <input size="50" type="text" id="eidr-addr"
                                                style="border: 0; font-size:9.5px; font-weight:200;"
                                                value="TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ" readonly>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="copy('eidr-addr')">Copy</button>
                                        </label>
                                        <small class="text-info">Terkonfirmasi otomatis.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($getDataMaster->status == 1)
                    <p class="card-text">Status</p>
                    <div class="row">
                        <div class="col-md-12">
                            @if($getDataMaster->buy_metode == 1)
                            <dd>Pembayaran Tunai</dd>
                            <dd><em>Menunggu Konfirmasi dari Stokis <br>(setelah pembayaran dilunasi)</em></dd>
                            @endif
                        </div>
                    </div>

                    @endif

                    @if($getDataMaster->status == 2)
                    <p class="card-text">Status</p>
                    <dd class="text-success">Transaksi Tuntas</dd>
                    <div class="row">
                        <div class="col-md-12">
                            @if($getDataMaster->buy_metode == 1)
                            <dd>Pembayaran Tunai</dd>
                            @endif

                            @if($getDataMaster->buy_metode == 3)
                            <dd>Pembayaran via eIDR</dd>
                            <div id="finalHash"></div>
                            @endif
                        </div>
                    </div>

                    @endif

                    @if($getDataMaster->status == 10)
                    <p class="card-text">Status</p>
                    <div class="row">
                        <div class="col-md-12">

                            <dd class="text-danger">Transaksi Batal</dd>
                        </div>
                    </div>

                    @endif


                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted"><strong> Total pembayaran</strong></small>
                            <h5>Rp{{number_format($getDataMaster->total_price)}}</h5>
                            @if($getDataMaster->status == 0)
                            <small class="text-muted"><strong> Saldo eIDR anda</strong></small>
                            <h6 class="text-success" id="eIDRbalance">Rp{{number_format(0)}}</h6>
                            @endif
                        </div>
                    </div>
                    @if($getDataMaster->status == 0)
                    <hr>
                    <div class="row">
                        <div class="col-xl-12">


                            <button class="btn btn-danger" onclick="cancel()">Batal</button>
                            <button class="btn btn-success" onclick="confirmPayment()">Konfirmasi</button>
                            <button class="btn btn-info" id="tronwebPay" style="display: hidden;" disabled>via
                                TronWeb</button>

                        </div>
                    </div>
                    @endif

                    @if($getDataMaster->status > 0)
                    <div class="row">
                        <div class="col-6">
                            @if($getSeller->is_stockist == 1)
                            <a class="btn btn-dark" href="{{ URL::to('/') }}/m/history/shoping">Kembali</a>
                            @else
                            <a class="btn btn-dark" href="{{ URL::to('/') }}/m/history/vshoping">Kembali</a>
                            @endif
                        </div>
                    </div>
                    @endif
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

@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
@if($getDataMaster->status == 0)
<script>
    var masterSalesID = {{$getDataMaster->id}};
    let _token = '{{ csrf_token() }}';
    let sellerType = {{$sellerType}};

            $('#tunaibutton').click(function() {
                $('#radio1').prop('checked', true)
                $('#tronwebPay').hide()
            })

            $('#eidrbutton').click(function() {
                $('#radio2').prop('checked', true)
                $('#tronwebPay').show()
            })

    async function confirmPayment(){
        var buy_method = $('input[name=radio]:checked').val();
        if (buy_method == 1) {
            Swal.fire({
                title: 'Pembayaran Tunai',
                text: "Belanja anda akan dikonfirmasi oleh Penjual setelah anda membayar lunas",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Setuju!',
                cancelButtonText: 'Nanti saja'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: "{{ URL::to('/') }}/m/ajax/shopping-payment",
                        data: {
                            buy_method:buy_method,
                            masterSalesID:masterSalesID,
                            sellerType:sellerType,
                            _token:_token
                        },
                        success: function(response){
                            Swal.fire(
                            'Berhasil',
                            'Belanja anda telah diajukan ke Penjual.',
                            'success'
                            )
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 3000)
                        }
                    })


                }
            })
        } else {
            let { value: text } = await Swal.fire({
            input: 'textarea',
            inputLabel: 'Transaction Hash',
            inputPlaceholder: 'Tempelkan (Paste) Transaction Hash anda...',
            inputAttributes: {
            'aria-label': 'Paste your transaction hash here'
            },
            inputValidator: (value) => {
                if (value.includes("tronscan")) {
                    let hashOnly = value.split("/")[5];
                    value = hashOnly;
                }
                if (!value) {
                    return 'Hash harus diisi!'
                }

                if (value.length != 64) {
                    return 'Periksa kembali Hash yang anda masukkan'
                }

            },
            showCancelButton: true,
            cancelButtonText: 'Tunda'
            })

            if (text.includes("tronscan")) {
            let hashOnly = text.split("/")[5];
            text = hashOnly;
            }

            if (text) {
                postAJAXtronweb(text);
            }
        }
    }

    function cancel() {
        Swal.fire({
                title: 'Batalkan Transaksi',
                text: "Apakah anda yakin untuk membatalkan transaksi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Batalkan!',
                cancelButtonText: 'Jangan'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire('Sedang Memproses...');
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "{{ URL::to('/') }}/m/ajax/cancel-shopping-payment-buyer",
                        data: {
                        masterSalesID:masterSalesID,
                        sellerType:sellerType,
                        _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                Swal.fire(
                                'Dibatalkan',
                                'Keranjang belanja telah dibatalkan',
                                'info'
                                )
                                setTimeout(function() {
                                    window.location.reload(true);
                                }, 3000)
                            }
                        }
                    })
                }
            })


    }

    function postAJAXtronweb(hash) {
        var buy_method = $('input[name=radio]:checked').val();
        Swal.fire('Sedang Memproses...');
        Swal.showLoading();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('/') }}/m/ajax/shopping-payment",
            data: {
            buy_method:buy_method,
            masterSalesID:masterSalesID,
            tron_transfer:hash,
            sellerType:sellerType,
            _token:_token
            },
            success: function(response){
                if(response.success) {
                    Swal.fire(
                    'Berhasil',
                    'Belanja anda telah Lunas dan Tuntas.',
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


            var waiting = 0;

            async function main() {
                if (!(window.tronWeb && window.tronWeb.ready)) return (waiting += 1, 50 == waiting) ? void console.log('Failed to connect to TronWeb') : (console.warn("main retries", "Could not connect to TronLink.", waiting), void setTimeout(main, 500));
                tronWeb = window.tronWeb;
                
                try {
                    await showTronBalance();
                } catch (a) {
                    console.log(a);
                }

            }

            function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }

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

                    if (eIDRbalance >= {{$getDataMaster->total_price}}) {
                        $('#tronwebPay').attr("disabled", false);
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
            $("#tronwebPay").click(async function () {
                sendAmount = {{$getDataMaster->total_price}} * 100;
                
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
                        postAJAXtronweb(broastTx.txid);

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

    function copy(id) {
        var copyText = document.getElementById(id);
        var textArea = document.createElement("textarea");
        textArea.value = copyText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
        successToast("Berhasil di-Copy");
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        width: 200,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    function successToast (message) {
        Toast.fire({
            icon: 'success',
            title: message
        })
    }

</script>
@endif


@if($getDataMaster->status == 2)
@if($getDataMaster->buy_metode == 3)
<script>
    function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }

    <?php $finalHash = $getDataMaster->tron_transfer; ?>
    var finalHash = "{{$finalHash}}";
    $('#finalHash').html(`<small>Hash: <a class="text-info" href='https://tronscan.org/#/transaction/`+finalHash+`' target="_blank">`+
            shortId(finalHash, 7) +`</a></small>`)
</script>

@endif
@endif
@stop