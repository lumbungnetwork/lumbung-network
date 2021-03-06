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
                            <div class="col-6 mb-6">
                                <a href="/m/detail/pascabayar/2" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-flash icon-menu" style="color: #f5e12e;"></i>
                                        </div>
                                        <dd>PLN Pascabayar</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 mb-6">
                                <a href="/m/detail/pascabayar/4" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-wifi icon-menu" style="color: #db2c2c;"></i>
                                        </div>
                                        <dd>Telkom</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 mb-6">
                                <a href="/m/detail/pascabayar/6" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-fire icon-menu" style="color: #2590e7;"></i>
                                        </div>
                                        <dd>Gas Negara</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 mb-6">
                                <a href="/m/list/hp-pascabayar" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-arrow-down icon-menu" style="color: #ac4937;"></i>
                                        </div>
                                        <dd>HP Pascabayar</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 mb-6">
                                <a href="/m/list/multifinance" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-bank-outline icon-menu" style="color: #f0d458;"></i>
                                        </div>
                                        <dd>Multifinance</dd>
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
