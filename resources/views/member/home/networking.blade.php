@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
        
    
        <!-- Page Content -->
        <div id="content">
            
            <div class="bg-gradient-sm">
                <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                    <div class="container">
                        <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                            <i class="fa fa-arrow-left"></i> Beranda
                        </a>
                        <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                            <i class="fas fa-power-off text-danger icon-bottom"></i>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/add/sponsor" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-account-plus-outline icon-menu"></i>
                                        </div>
                                        <dd>Daftar</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/add/placement" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-axis-arrow icon-menu"></i>
                                        </div>
                                        <dd>Placement</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/status/member" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-account-check-outline icon-menu"></i>
                                        </div>
                                        <dd>Status</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/status/sponsor" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-console-network-outline icon-menu"></i>
                                        </div>
                                        <dd>Sponsor</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/my/binary" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-file-tree icon-menu"></i>
                                        </div>
                                        <dd>Binary Tree</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/my/sponsor-tree" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-account-network-outline icon-menu"></i>
                                        </div>
                                        <dd>Sponsor Tree</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/req/claim-reward" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-trophy-outline icon-menu"></i>
                                        </div>
                                        <dd>Reward</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-format-align-justify icon-menu"></i>
                                        </div>
                                        <dd>Lainnya</dd>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                    $sum_pin_masuk = 0;
                    $sum_pin_keluar = 0;
                    if($dataPin->sum_pin_masuk != null){
                        $sum_pin_masuk = $dataPin->sum_pin_masuk;
                    }
                    if($dataPin->sum_pin_keluar != null){
                        $sum_pin_keluar = $dataPin->sum_pin_keluar;
                    }
                    $total = $sum_pin_masuk - $sum_pin_keluar;
                ?>
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <h6 class="mb-3">Subscription PIN</h6>
                        <div class="row">
                            <div class="col-5">
                                <p class="f-14">PIN Tersedia</p>
                            </div>
                            <div class="col-5">
                                <h6 class="text-warning"> {{$total}} </h6>
                            </div>
                            <div class="col-5">
                                <p class="f-14">PIN Terpakai</p>
                            </div>
                            <div class="col-5">
                                <h6 class="text-warning"> {{$sum_pin_keluar}} </h6>
                            </div>
                            <div class="col-5">
                                <p class="f-14">PIN Terkirim</p>
                            </div>
                            <div class="col-5">
                                <h6 class="text-warning"> {{$dataTerkirim}} </h6>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/add/pin" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-pin-outline icon-menu"></i>
                                        </div>
                                        <dd>Beli PIN</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/list/transactions" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-clipboard-flow-outline icon-menu"></i>
                                        </div>
                                        <dd>Transaksi</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/add/transfer-pin" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-send icon-menu"></i>
                                        </div>
                                        <dd>Kirim PIN</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/pin/history" class="text-decoration-none">
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
            </div>
            @include('layout.member.nav')
        </div>
        <div class="overlay"></div>
    </div>

@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
@stop
