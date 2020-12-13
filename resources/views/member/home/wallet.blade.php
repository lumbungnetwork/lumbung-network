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
        <?php
            $saldo = $dataAll->total_bonus - $dataAll->total_wd - $dataAll->total_tunda - $dataAll->total_fee_admin - ($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr + $dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr);
            if($saldo > -5000 && $saldo <= 0){
                $saldo = 0;
            }
            ?>
        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 style="display: inline; margin-right: 10px;">Dompet Lokal</h6>
                    <a tabindex="0" class="btn btn-sm btn-outline-warning" role="button" data-toggle="popover"
                        data-trigger="focus" title="Dompet Lokal" data-content="Dompet Lokal adalah alamat TRON yang dibuat khusus untuk memudahkan transaksi
                                internal di akun anda. Saldo hanya bisa
                                ditarik ke akun TRON utama anda">Apa
                        ini?</a>
                    <div class="row">
                        @if($dataUser->tron == null)
                        <p class="text-center">Anda harus memiliki alamat TRON utama sebelum mulai menggunakan Dompet
                            Lokal</p>
                        @endif

                        @if($dataAll->local_wallet == null)

                        <div class="col-12">
                            <button class="btn btn-success btn-lg btn-block mt-4" onclick="createLocalWallet()"> <span
                                    style="font-size: 15px;">Buat
                                    Dompet Lokal</span> </button>
                        </div>
                        @else
                        <div class="col-12">
                            <div class="rounded-lg shadow p-2 mt-2">
                                <label class="text-muted">
                                    Alamat Dompet Lokal:
                                </label>
                                <?php $localAddr = $dataAll->local_wallet->address;
                                    $shortAddr = substr($localAddr, 0, 7) . '...' . substr($localAddr, -7);
                                ?>
                                <h6>
                                    {{$shortAddr}}
                                </h6>
                            </div>
                        </div>
                        <div class="col-6 pl-3 pr-0">
                            <div class="rounded-lg shadow p-2 mt-3">
                                <label class="text-muted mt-2">
                                    Saldo:
                                </label>
                                <p class="mb-0">TRX: <span class="text-warning">{{$dataAll->trx_balance}}</span></p>
                                <p>eIDR: <span class="text-warning">{{number_format($dataAll->eidr_balance)}}</span></p>

                            </div>
                        </div>
                        <div class="col-6 mb-3 mt-3">
                            <div class="row">
                                <div class="col-6 mb-0">
                                    <a onclick="showQR()" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-qrcode-scan icon-menu"></i>
                                            </div>
                                            <dd>QRcode</dd>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 mb-0">
                                    <a onclick="copy('{{$localAddr}}')" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-content-copy icon-menu"></i>
                                            </div>
                                            <dd>Copy</dd>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 mb-0">
                                    <a onclick="withdrawLocalWallet()" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-cash-refund icon-menu"></i>
                                            </div>
                                            <dd>Withdraw</dd>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 mb-0">
                                    <a href="#" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-history icon-menu"></i>
                                            </div>
                                            <dd>Riwayat</dd>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                @if ( Session::has('message') )
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="container">
                        <div class="alert alert-{{ Session::get('messageclass') }}">
                            <button class="close" type="button" data-dismiss="alert"><span
                                    aria-hidden="true">&times;</span></button>
                            {{  Session::get('message')    }}
                        </div>
                    </div>
                </div>
                @endif

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h5 class="mb-3">eIDR</h5>
                    <div class="form-group mb-0">
                        <label for="input_jml" class="text-muted f-12">Konversi Saldo Bonus ke eIDR</label>
                        <a tabindex="0" class="btn btn-sm btn-outline-warning" role="button" data-toggle="popover"
                            data-trigger="focus" title="Konversi Saldo Bonus ke eIDR"
                            data-content="Mengajukan Penarikan Saldo Bonus ke Dompet Tron anda dalam bentuk eIDR. Minimum penarikan Rp10.000,- (Biaya Rp3.000,- per konversi.)">Apa
                            ini?</a>

                        <div class="form-row mt-2">
                            <div class="form-group col-7">
                                <input type="text" inputmode="numeric" pattern="[0-9]*"
                                    class="form-control form-control-sm allownumericwithoutdecimal" id="input_jml"
                                    name="jml_wd" aria-describedby="textHelp" autocomplete="off"
                                    placeholder="Jumlah Konversi">
                            </div>
                            <div class="form-group col-5">
                                <button type="submit" class="btn btn-sm btn-success btn-block mb-2" id="submitBtn"
                                    data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()"> <span
                                        class="f-14"> Konversi </span></button>
                                <a href="{{ URL::to('/') }}/m/history/wd-eidr" class="text-decoration-none">
                                    <small class="f-10"><i class="mdi mdi-history"></i> Riwayat Konversi</small>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow p-2">
                                <p>
                                    Proses Konversi:
                                </p>
                                <h6 class="text-warning">Rp
                                    {{number_format($dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr, 0, ',', '.')}}
                                </h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow p-2">
                                <p>
                                    Total Dikonversi:
                                </p>
                                <h6 class="text-warning">Rp
                                    {{number_format($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr, 0, ',', '.')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <label for="input_jml_topup" class="f-12 text-muted">Request Top-up eIDR</label>
                        <a tabindex="0" class="btn btn-sm btn-outline-warning" role="button" data-toggle="popover"
                            data-trigger="focus" title="Request Top-up eIDR"
                            data-content="Mengajukan Pengisian Saldo (Top-up) eIDR melalui transfer Bank. Saldo eIDR akan masuk ke alamat TRON anda setelah bukti transfer anda dikonfirmasi oleh sistem.">Apa
                            ini?</a>
                        <div class="form-row mt-2">
                            <div class="form-group col-7">
                                <input type="text" inputmode="numeric" pattern="[0-9]*"
                                    class="form-control form-control-sm allownumericwithoutdecimal" id="input_jml_topup"
                                    aria-describedby="textHelp" name="input_topup" autocomplete="off"
                                    placeholder="Jumlah Top-up">
                            </div>
                            <div class="form-group col-5">
                                <button type="submit" class="btn btn-sm btn-success btn-block mb-2" id="submitBtn"
                                    data-toggle="modal" data-target="#confirmSubmitTopUp" onClick="inputSubmitTopUp()">
                                    <span class="f-14"> Top-up </span></button>
                                <a href="{{ URL::to('/') }}/m/history/topup-saldo" class="text-decoration-none">
                                    <small class="f-10"><i class="mdi mdi-history"></i> Riwayat Top-up</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Bonus</h6>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow p-2">
                                <p>
                                    Saldo Bonus:
                                </p>
                                <h6 class="text-warning">Rp {{number_format($saldo, 0, ',', '.')}}</h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="row">
                                <div class="col-6 mb-0">
                                    <a href="{{ URL::to('/') }}/m/sponsor/bonus" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-console-network-outline icon-menu"></i>
                                            </div>
                                            <dd>Sponsor</dd>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 mb-0">
                                    <a href="{{ URL::to('/') }}/m/binary/bonus" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-axis-arrow icon-menu"></i>
                                            </div>
                                            <dd>Pairing</dd>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 mb-0">
                                    <a href="{{ URL::to('/') }}/m/req/wd" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-cash-refund icon-menu"></i>
                                            </div>
                                            <dd>Withdraw</dd>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 mb-0">
                                    <a href="{{ URL::to('/') }}/m/history/wd" class="text-decoration-none">
                                        <div class="rounded icon-ppob text-center">
                                            <div class="box-icon bg-green text-center">
                                                <i class="mdi mdi-history icon-menu"></i>
                                            </div>
                                            <dd>Riwayat</dd>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow p-2">
                                <p>
                                    Proses Konversi:
                                </p>
                                <h6 class="text-warning">Rp
                                    {{number_format($dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr, 0, ',', '.')}}
                                </h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow p-2">
                                <p>
                                    Total Dikonversi:
                                </p>
                                <h6 class="text-warning">Rp
                                    {{number_format($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr, 0, ',', '.')}}
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow p-2">
                                <p>
                                    Saldo Bonus Royalti:
                                </p>
                                <?php
                                        $saldoRO = $dataAll->total_bonus_ro - $dataAll->total_wd_ro - $dataAll->total_tunda_ro - $dataAll->total_fee_admin_ro;
                                        if($saldo < 0){
                                            $saldo = 0;
                                        }
                                    ?>
                                <h6 class="text-warning">Rp {{number_format($saldoRO, 0, ',', '.')}}</h6>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <a href="{{ URL::to('/') }}/m/req/wd-royalti" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-console-network-outline icon-menu"></i>
                                    </div>
                                    <dd>Withdraw Royalti</dd>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <!-- Dark Overlay element -->
    <div class="overlay"></div>
</div>



<!-- Modal-->
<div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="false">
    <div class="modal-dialog" role="document" id="confirmDetail">
    </div>
</div>
<div class="modal fade" id="confirmSubmitTopUp" tabindex="-1" role="dialog" data-backdrop="true">
    <div class="modal-dialog" role="document" id="confirmDetailTopUp">
    </div>
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
<script>
    let _token = '{{ csrf_token() }}';
    $(function () {
            $('[data-toggle="popover"]').popover()
        })
        $('.popover-dismiss').popover({
            trigger: 'focus'
        })
       function inputSubmit(){
           var input_jml_wd = $("#input_jml").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd-eidr?input_jml_wd="+input_jml_wd,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }

        function inputSubmitTopUp(){
           var input_jml_wd = $("#input_jml_topup").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-topup?input_jml_topup="+input_jml_wd,
                success: function(url){
                    $("#confirmDetailTopUp" ).empty();
                    $("#confirmDetailTopUp").html(url);
                }
            });
        }

        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
            $('#tutupModal').remove();
            $('#submit').remove();
        }

        function createLocalWallet() {
            Swal.fire('Sedang Memproses');
            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "{{ URL::to('/') }}/m/ajax/create-local-wallet",
                data: {
                user_id:{{$dataUser->id}},
                _token:_token
            },
                success: function(response){
                    if(response.success) {
                        Swal.fire(
                        'Berhasil',
                        'Dompet Lokal anda telah berhasil dibuat.',
                        'success'
                        )
                        setTimeout(function() {
                        window.location.reload(true);
                        }, 3000)
                    } else {
                        Swal.fire(
                        'Oops',
                        response.message,
                        'error'
                        )
                        setTimeout(function() {
                        window.location.reload(true);
                        }, 3000)
                    }

                }
            })
        }

        function showQR() {
            Swal.fire({
                html: `<div>{!! QrCode::size(200)->generate($localAddr); !!}</div>

                        <p class="mt-3">{{$localAddr}}</p>
                `,
                confirmButtonText: 'Tutup'
            })
        }

        async function withdrawLocalWallet() {
            const { value: formValues } = await Swal.fire({
                title: 'Multiple inputs',
                html:`
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Pilih aset</label>
                    </div>
                    <select class="custom-select" id="asset-name">
                        <option value="1" selected>eIDR</option>
                        <option value="2">TRX</option>
                    </select>
                </div>
                <input id="amount" inputmode="numeric" pattern="[0-9]*" class="swal2-input" placeholder="Jumlah penarikan">
                `,
                focusConfirm: false,
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Withdraw',
                preConfirm: () => {
                    return [
                        document.getElementById('asset-name').value,
                        document.getElementById('amount').value
                    ]
                }
            })

            if (formValues) {
                Swal.fire('Sedang Memproses');
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('/') }}/m/ajax/withdraw-local-wallet",
                    data: {
                    user_id:{{$dataUser->id}},
                    asset:formValues[0],
                    amount:formValues[1],
                    _token:_token
                },
                    success: function(response){
                        let assetName = 'eIDR';
                        if(formValues[0] == 2) {
                            assetName = 'TRX';
                        }
                        if(response.success) {
                            Swal.fire(
                            'Berhasil',
                            formValues[1] + ' ' + assetName + ' ' + 'telah berhasil ditarik ke dompet TRON utama',
                            'success'
                            )
                            setTimeout(function() {
                            window.location.reload(true);
                            }, 3000)
                        } else {
                            Swal.fire(
                            'Oops',
                            response.message,
                            'error'
                            )
                            setTimeout(function() {
                            window.location.reload(true);
                            }, 3000)
                        }

                    }
                })
            }
        }

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        function copy(string) {
            var textArea = document.createElement("textarea");
            textArea.value = string;
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
@stop
