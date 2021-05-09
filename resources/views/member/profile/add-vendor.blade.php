@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
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
                    <h6 class="mb-3">Request Vendor Baru</h6>

                    <small><i>Silakan baca dan centang poin-poin di bawah ini:</i></small>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1">
                                    Saya mengajukan diri untuk menjadi Vendor di Lumbung Network, saya menjunjung
                                    tujuan utama Lumbung Network untuk mensejahterakan masyarakat di atas keuntungan
                                    pribadi.
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox2" type="checkbox">
                                <label for="checkbox2">
                                    Saya bersedia memberikan <i>Kontribusi Bagi Hasil</i> sebesar 2% dari setiap
                                    penjualan saya
                                    (Otomatis).
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox3" type="checkbox">
                                <label for="checkbox3">
                                    Saya siap mengikuti pelatihan dan arahan dari Delegates di daerah tempat
                                    tinggal/usaha saya.
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <select class="form-control" name="delegate" id="delegate" onchange="pickDelegate()">
                                    <option value="0">--Pilih Delegasi--</option>
                                    @foreach ($delegates as $delegate)
                                    <option value="{{$delegate->name}}">{{$delegate->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox4" type="checkbox">
                                <label for="checkbox4">
                                    Saya telah membaca dan menyetujui <a href="https://lumbung.network/about/">Peraturan
                                        dan Kode Etik Komunitas</a>
                                    Lumbung Network.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <dd class="text-warning">Untuk mengajukan permohonan Vendor ini anda perlu membakar
                                <strong>100
                                    LMB</strong>
                            </dd>
                            <button type="submit" class="btn btn-lg btn-block btn-success" id="submitBtn"
                                onClick="inputSubmit()">Apply</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>
    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document" id="confirmDetail">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Apply Vendor</h5>
                </div>
                <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
                    <form id="form-add" method="POST" action="/m/req/vendor">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="hidden" name="hash" id="hash" value="0">
                                    <input type="hidden" name="delegate" id="_delegate" value="0">
                                    <dd class="text-success" style="text-align: center;"> Untuk mengajukan permohonan
                                        Vendor ini
                                        anda perlu membakar
                                        <strong>100
                                            LMB</strong></dd>
                                </div>
                                <div class="mt-3 float-right card rounded-lg bg-light p-4">
                                    <div id="showAddress"></div>
                                    <h6 class="text-success availableLMB">0 LMB</h6>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <small class="mt-2 text-danger d-block" id="tronweb-warning">Use Tronlink or Dapp enabled
                        browser</small>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="tronwebPay" disabled>Ajukan</button>
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
    function inputSubmit(){
           if($("#checkbox1").prop('checked') == false){
                errorToast('Anda belum menyetujui syarat pertama');
                return false;
            }
           if($("#checkbox2").prop('checked') == false){
                errorToast('Anda belum menyetujui syarat kedua (Kontribusi Bagi Hasil)');
                return false;
            }
           if($("#checkbox3").prop('checked') == false){
                errorToast('Anda belum menyatakan bersedia untuk dibimbing Tim Delegasi');
                return false;
            }
           if($("#checkbox4").prop('checked') == false){
                errorToast('Anda belum menyetujui Ketentuan dan Kode Etik');
                return false;
            }
            if ($('#delegate').val() == 0) {
                errorToast('Anda belum memilih Delegasi');
                return false;
            }

            $('#confirmSubmit').modal('show');
            
        }

        function pickDelegate() {
            $('#_delegate').val($('#delegate').val());
        }

        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
        }

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

    // Toast
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

        function errorToast (message) {
            Toast.fire({
                icon: 'error',
                title: message
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
            $('#tronwebPay').attr("disabled", false);
            
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
            var tx = await tronWeb.trx.sendToken(
                toAddress,
                sendAmount,
                "1002640",
            );
            if (tx.txid !== undefined) {
                postApplyVendor(tx.txid);
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

    function postApplyVendor(hash) {
        Swal.fire('Sedang Memproses...');
        Swal.showLoading();

        $('#hash').val(hash);
        var dataInput = $("#form-add").serializeArray();
        $('#form-add').submit();
    }

</script>
@stop