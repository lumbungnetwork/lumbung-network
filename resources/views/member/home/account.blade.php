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
                        <?php
                                $active_at = $dataUser->active_at;
                                if($dataUser->pin_activate_at != null){
                                    $active_at = $dataUser->pin_activate_at;
                                }
                                $future = strtotime('+365 days', strtotime($active_at));
                                $timefromdb =time();
                                $timeleft = $future - $timefromdb;
                                $daysleft = round((($timeleft/24)/60)/60);
                            ?>
                        <div class="col-8 mb-3">
                            <p>
                                Masa Aktif Keanggotaan
                            </p>
                            @if($daysleft <= 0) <h5 class="text-danger">Keanggotaan anda sudah kadaluarsa</h5>
                                @endif
                                @if($daysleft > 0)
                                <h5 class="text-warning">{{$daysleft}}</h5>
                                <p class="f-12">Hari sebelum kadaluwarsa</p>
                                @endif
                        </div>
                        <div class="col-4 mb-3 px-0">
                            <input type="hidden" class="form-control allownumericwithoutdecimal invalidpaste"
                                id="input_jml_pin" name="total_pin" autocomplete="off" value="1">
                            <button type="submit" class="btn btn-success" id="submitBtn" data-toggle="modal"
                                data-target="#confirmSubmit" onClick="inputSubmit()">Resubscribe</button>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog"
                            aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
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
                            <a href="#" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-view-grid-outline icon-menu"></i>
                                    </div>
                                    <dd>Resource</dd>
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
                            <a href="#" class="text-decoration-none">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-view-grid-outline icon-menu"></i>
                                    </div>
                                    <dd>Resource</dd>
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
                            ?>
                        <div class="col-6 mb-3">
                            <p class="f-14">Saldo Deposit Vendor</p>
                            <h5 class="text-warning"> Rp {{number_format($totalDeposit, 0, ',', '.')}}</h5>
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
    <!-- Dark Overlay element -->
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
<script>
    function inputSubmit(){
           var total_pin = $("#input_jml_pin").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/repeat-order?total_pin="+total_pin,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }

        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
            $('#tutupModal').remove();
            $('#submit').remove();
        }

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        $('.invalidpaste').on('paste', function (event) {
            if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
                event.preventDefault();
            }
        });

</script>
@stop
