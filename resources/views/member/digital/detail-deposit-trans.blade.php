@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <input type="hidden" id="isTronWeb" value="0" readonly>
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
                <div class="card shadow rounded bg-white p-3 mb-3">
                    <h6 class="mb-3">Transaksi Deposit</h6>
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

                </div>

                @php
                $status = 'Tuntas';
                $label = 'success';
                if($getData->status == 0){
                $status = 'Proses Transfer';
                $label = 'danger';
                }
                if($getData->status == 1){
                $status = 'Menunggu Konfirmasi';
                $label = 'warning';
                }
                if($getData->status == 3){
                $status = 'Batal';
                $label = 'danger';
                }
                $total = $getData->price + $getData->unique_digit;
                @endphp

                <div class="rounded-lg shadow bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="rounded-lg bg-light shadow p-3 mb-3">
                                <h6>Isi Deposit: Rp{{number_format($getData->price)}}</h6>
                                <small class="mb-3"><strong>Invoice</strong>
                                    <small>{{$getData->transaction_code}}</small><br>
                                </small>
                                <small>
                                    {{date('d F Y', strtotime($getData->created_at))}}</small><br>
                                <small class="float-right"><span
                                        class="label f-12 label-{{$label}}">{{$status}}</span></small>
                                <div class="clearfix"></div>
                            </div>

                        </div>


                    </div>
                </div>

                <div class="rounded-lg shadow bg-white p-3 mb-3">
                    <h6>Pembayaran</h6>
                    <address>
                        @if($getData->bank_perusahaan_id != null)
                        @if($getData->is_tron == 0)
                        <br>
                        Nama Rekening: <strong>{{$bankPerusahaan->account_name}}</strong>
                        <br>
                        Nama Bank: <strong>{{$bankPerusahaan->bank_name}}</strong>
                        <br>
                        No. Rekening: <strong>{{$bankPerusahaan->account_no}}</strong>
                        @endif
                        @if($getData->is_tron == 1 && $getData->bank_perusahaan_id != 9)
                        <br>
                        Nama: <strong>Pembayaran via eIDR</strong>
                        <br>
                        Alamat Tron: <strong>TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge</strong>
                        @endif
                        @if($getData->is_tron == 1 && $getData->bank_perusahaan_id == 9)
                        <br>
                        Nama: <strong>Pembayaran via eIDR Autoconfirm</strong>
                        <br>
                        Alamat Tron: <strong>TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge</strong>
                        @endif
                        @endif
                        <label>
                            Transfer eIDR ke alamat ini:
                            <br>
                            <div class="mt-2 mb-2">
                                <small><mark id="eidr-addr">TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge</mark></small>
                            </div>
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-primary float-right"
                            onclick="copy('eidr-addr')">Copy</button>
                    </address>

                </div>



                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <small><b>Sub-total:</b>
                                Rp{{number_format($getData->price, 0, ',', ',')}}</small><br>
                            <small><b>Kode Unik:</b>
                                {{number_format($getData->unique_digit, 0, ',', ',')}}</small>
                            <hr>
                            <small>Jumlah yang harus ditransfer:</small>
                            <h5>Rp{{number_format($total)}}</h5>
                            <br>
                            <small>Saldo eIDR tersedia:</small>
                            <h5 class="text-success" id="eIDRbalance">Rp0</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12">
                            @if($getData->status == 0)
                            <form id="form-reject" method="POST" action="/m/reject/deposit-transaction">
                                @csrf
                                <input type="hidden" name="id_trans" value="{{$getData->id}}">
                                <input type="hidden" name="reason" value="Dibatalkan oleh user">
                            </form>

                            <form id="form-confirm" method="POST" action="/m/add/deposit-transaction-tron">
                                @csrf
                                <input type="hidden" name="id_trans" value="{{$getData->id}}">
                                <input type="hidden" id="hash" name="hash" value="0">
                            </form>

                            <button type="button" class="btn btn-danger" id="rejectBtn"
                                onClick="rejectSubmit()">Batal</button>

                            <button type="button" class="btn btn-success" id="confirmButton"
                                onclick="confirmPayment()">Bayar</button>
                            @endif
                            @if($getData->status >= 1)
                            <div class="hidden-print">
                                <div class="float-right">
                                    <a class="btn btn-success" href="{{ URL::to('/') }}/m/list/transactions">Kembali</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
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
    <link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

    @stop

    @section('javascript')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
    </script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.cart.min.js') }}"></script>
    @if($getData->status == 0)
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
            var toAddress = 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge';
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

                    if (eIDRbalance < {{$total}}) {
                        $('#confirmButton').attr("disabled", true);
                    }

                    $("#eIDRbalance").html(
                        `Rp${eIDRbalance.toLocaleString("en-US")}
                        `
                    );

                    $("#showAddress").html(`<p>Active Wallet: <mark>${shortId(userAddress, 5)}</mark></p> `);
                    $('#isTronWeb').val(1);

                } else {
                    $("#eIDRbalance").html(`Alamat TRON ini tidak memiliki eIDR`);
                }
            }



            //Pay using TronWeb service
            async function tronWebPay() {
                sendAmount = {{$total}} * 100;

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
            }


        async function confirmPayment(){
        if ($('#isTronWeb').val() == 1) {
            Swal.fire({
                title: 'Pembayaran via TronWeb',
                text: "Apakah anda ingin membayar via TronWeb?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Nanti saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    tronWebPay();
                }
            })
        } else {
            Swal.fire({
                title: 'Manual Transfer eIDR',
                text: "Anda harus mengkonfirmasi Hash Transaksi anda",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Input Hash!',
                cancelButtonText: 'Nanti saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    manualTransfer();
                }
            })

        }

        async function manualTransfer() {
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
            if(text) {
                $('#hash').val(text);
                $('#form-confirm').submit();
                Swal.fire('Sedang Memproses');
                Swal.showLoading();
            }

        }
    }

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
                if (eIDRbalance < {{$total}}) {
                    $('#confirmButton').attr("disabled", true);
                }

                $("#eIDRbalance").html(
                    `Rp${eIDRbalance.toLocaleString("en-US")}
                    `
                );

                $("#showAddress").html(`<p>Active Wallet: <mark>${shortId(localAddress, 5)}</mark></p> `);

            }

            //pay using localWallet
            async function confirmPayment() {
                Swal.fire({
                    title: 'Bayar via Dompet Lokal',
                    text: "Anda ingin menlakukan pembayaran ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, bayarkan!',
                    cancelButtonText: 'Jangan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        payByLocalWallet();
                    }
                })
            }

            function payByLocalWallet() {
                var id_trans = {{$getData->id}};
                $.ajax({
                        type: "POST",
                        url: "{{ URL::to('/') }}/m/ajax/pay-deposit-by-local-wallet",
                        data: {
                            id_trans:id_trans,
                            _token:_token
                        },
                        success: function(response){
                            if(response.success) {
                                $('#hash').val(response.message);
                                $('#form-confirm').submit();
                                Swal.fire('Sedang Memproses');
                                Swal.showLoading();
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

            function cleanHash() {
                if ($("#hash").val().includes("tronscan")) {
                    let hashOnly = $("#hash").val().split("/")[5];
                    $("#hash").val(hashOnly);
                }
            };

    </script>


    @endif
    @stop
