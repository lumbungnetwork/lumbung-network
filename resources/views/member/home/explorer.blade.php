@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
        
    
        <!-- Page Content -->
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
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <h6 class="mb-3">Explorer</h6>
                        <div class="row">
                            <div class="col-12">
                                <p class="f-14">
                                    Lumbung Network adalah platform desentralisasi yang mengutamakan transparasi, dijalankan dengan prinsip
                                    managemen terbuka.
                                </p>
                                <p class="f-14">
                                    Siapapun bebas untuk menelusuri semua data yang ada di platform Lumbung Network
                                </p>
                                <p class="f-14">
                                    Anda bisa menelusuri pengguna (user) lain atau data statistik perkembangan Lumbung Network disetai perhitungan-perhitungan
                                    pembagian hasilnya
                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">&nbsp;</div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/explorer/statistic" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-chart-line icon-menu"></i>
                                        </div>
                                        <dd>Statistik</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/explorer/user" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-account-card-details icon-menu"></i>
                                        </div>
                                        <dd>User</dd>
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
