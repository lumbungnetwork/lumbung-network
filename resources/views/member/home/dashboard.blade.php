@extends('layout.member.new_main')

@section('content')

    <div class="wrapper">
        <!-- Page Content -->
        <div id="content">
            <div class="bg-gradient-sm">
                <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                    <div class="container">
                        <h6>
                            Halo,
                            <dd> {{$dataUser->user_code}} </dd>
                        </h6>
                        <a href="{{ URL::to('/') }}/user_logout" class="btn btn-transparent">
                            <i class="fas fa-power-off text-danger icon-bottom"></i>
                        </a>
                    </div>
                </nav>
            </div>
<!--            <div class="mt-min-30">
                <div class="container">
                    <h5>
                        Selamat Pagi,
                        <dd> Rio Abi Nugraha </dd>
                    </h5>
                </div>
            </div>-->
            
            @if($dataUser->is_active == 0)
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white shadow-sm p-3">
                        <div class="row">
                            @if($dataOrder > 0)
                            <div class="col-xl-12 col-xs-12">
                                <div class="alert alert-warning" role="alert">
                                    Silahkan Hubungi Sponsor Anda Untuk Aktifasi
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="card-box tilebox-three">
                                    <div class="bg-icon pull-xs-left">
                                        @if($dataSponsor->gender == 2)
                                        <i class="fa fa-user"></i>
                                        @else 
                                        <i class="fa fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="text-xs-right">
                                        <h6 class="text-success text-uppercase m-b-15 m-t-10">{{$dataSponsor->user_code}}</h6>
                                        <h6 class="text-muted m-b-15 m-t-10">{{$dataSponsor->hp}}</h6>
                                        <h2 class="text-warning m-b-10"><span>Sponsor</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($dataUser->is_active == 1)
                @if($dataUser->upline_id == null)
                    @if($dataUser->id > 4)
                        <div class="mt-min-10">
                            <div class="container">
                                <div class="rounded-lg bg-white shadow-sm p-3">
                                    <div class="row">
                                        <div class="col-xl-12 col-xs-12">
                                            <div class="alert alert-warning" role="alert">
                                                Akun anda belum diplacement, Silahkan Hubungi Sponsor Anda Untuk Placement
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="card-box tilebox-three">
                                                <div class="bg-icon pull-xs-left">
                                                    @if($dataSponsor->gender == 2)
                                                    <i class="fa fa-user"></i>
                                                    @else 
                                                    <i class="fa fa-user"></i>
                                                    @endif
                                                </div>
                                                <div class="text-xs-right">
                                                    <h6 class="text-success text-uppercase m-b-15 m-t-10">{{$dataSponsor->user_code}}</h6>
                                                    <h6 class="text-muted m-b-15 m-t-10">{{$dataSponsor->hp}}</h6>
                                                    <h2 class="text-warning m-b-10"><span>Sponsor</span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                    
                @if($dataUser->upline_id != null)
                    <div class="mt-min-10">
                        <div class="container">
                            <div class="rounded-lg bg-white shadow-sm p-3">
                                @if ( Session::has('message') )
                                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        {{  Session::get('message')    }} 
                                    </div>
                                @endif
                                <?php
                                    $countDataMemberBuy = 0;
                                    if($getDataMemberBuy != null){
                                        $countDataMemberBuy = count($getDataMemberBuy);
                                    }
                                    $countDataVMemberBuy = 0;
                                    if($getDataVendorMemberBuy != null){
                                        $countDataVMemberBuy = count($getDataVendorMemberBuy);
                                    }
                                    $totalNotif = $dataOrder + $countDataMemberBuy + $countDataVMemberBuy;
                                ?>
                                @if($totalNotif > 0)
                                <div class="row">
                                    <div class="col-6 offset-5 text-right">
                                        <div id="ex3" class="w-100 mt-min-10">
                                            <a href="{{ URL::to('/') }}/m/notification" class="text-decoration-none">
                                                <span class="p1 fa-stack fa-5x has-badge" data-count="{{$totalNotif}}">
                                                <i class="p2 fa fa-circle fa-stack-2x" style="color: #00b894;"></i>
                                                <i class="p3 far fa-bell fa-stack-1x fa-inverse text-white" data-count="{{$totalNotif}}"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-4 border-right text-center">
                                        @if($dataMy->image != null)
                                            <img src="{{$dataMy->image}}" class="w-100" alt="{{$dataMy->name}}">
                                        @endif
                                        <small>{{$dataMy->name}}</small>
                                        <p>{{$dataUser->user_code}}</p>
                                        @if($dataUser->is_stockist == 1)
                                            <a href="" class="btn btn-warning btn-sm btn-block rounded-pill"> Stokis </a>
                                        @endif
                                    </div>
                                            <?php
                                                $text = '*Anda belum memenuhi Belanja Wajib';
                                                $color = 'danger';
                                                if($sum > 100000){
                                                    $text = '*Anda telah memenuhi Belanja Wajib';
                                                    $color = 'success';
                                                }
                                            ?>
                                    <div class="col-8 text-center">
                                        <span>Akumulasi belanja di Stokis</span>
                                        <p>Rp {{number_format($sum, 0, ',', '.')}}</p>
                                        <small class="text-{{$color}}">{{$text}}</small><hr/>
                                        <span>Akumulasi belanja di Vendor</span>
                                        <p>Rp {{number_format($vsum, 0, ',', '.')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- slider promo -->
                    <div class="slider-promo mt-3">
                        <div>
                            <div class="p-2">
                                <a href="">
                                    <img src="/asset_new/img/promo/promo-1.png" class="w-100 rounded-lg" alt="">
                                </a>
                            </div>
                        </div>
                        <div>
                            <div class="p-2">
                                <a href="">
                                    <img src="/asset_new/img/promo/promo-2.jpg" class="w-100 rounded-lg" alt="">
                                </a>
                            </div>

                        </div>
                        <div>
                            <div class="p-2">
                                <a href="">
                                    <img src="/asset_new/img/promo/promo-3.jpg" class="w-100 rounded-lg" alt="">
                                </a>
                            </div>
                        </div>
                        <div>
                            <div class="p-2">
                                <a href="">
                                    <img src="/asset_new/img/promo/promo-4.jpg" class="w-100 rounded-lg" alt="">
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 2 menu -->
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <div class="rounded bg-white p-3 text-center">
                                    <a href="{{ URL::to('/') }}/m/search/stockist"><i class="fa fa-cubes icon-menu2"></i></a>
                                    <dd>Stokis</dd>
                                    <div class="">
                                        <a href="{{ URL::to('/') }}/m/history/shoping" class="btn btn-sm btn-warning">History</a>
                                        <a href="{{ URL::to('/') }}/m/belanja-reward" class="btn btn-sm btn-warning">Reward</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded bg-white p-3 text-center">
                                    <a href="{{ URL::to('/') }}/m/search/vendor"><i class="fa fa-store icon-menu2"></i></a>
                                    <dd>Vendor</dd>
                                    <div class="">
                                        <a href="{{ URL::to('/') }}/m/history/vshoping" class="btn btn-sm btn-warning">History</a>
                                        <a href="{{ URL::to('/') }}/m/vbelanja-reward" class="btn btn-sm btn-warning">Reward</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- menu ppob -->
                    <div class="container mt-3 bg-light">
                        <div class="row">
                            <div class="col-3 px-3 mb-3">
                                <a href="/m/list/operator/1" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu"></i>
                                        </div>
                                        <dd>Pulsa</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="/m/list/operator/2" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-nfc icon-menu"></i>
                                        </div>
                                        <dd>Data</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-lightbulb-on-outline icon-menu"></i>
                                        </div>
                                        <dd>PLN</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-hospital-building icon-menu"></i>
                                        </div>
                                        <dd>BPJS</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-arrow-down icon-menu"></i>
                                        </div>
                                        <dd>Pasca Bayar</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-wifi icon-menu"></i>
                                        </div>
                                        <dd>Telkom</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-playlist-check icon-menu"></i>
                                        </div>
                                        <dd>Tagihan</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 px-3 mb-3">
                                <a href="/m/list/buy-ppob" class="text-decoration-none">
                                    <div class="rounded icon-ppob p-1 text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-format-align-justify icon-menu"></i>
                                        </div>
<!--                                        <dd>Lainnya</dd>-->
                                        <dd>List Pembelian</dd>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            
            @include('layout.member.nav')
        </div>
        <!-- Dark Overlay element -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    
    <script>
        $('.slider-promo').slick({
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: 3,
            autoplay: true,
            autoplaySpeed: 2000,
            responsive: [
                {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 3
                }
                },
                {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 1
                }
                }
            ]
        });
    </script>
@stop