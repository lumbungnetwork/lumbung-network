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
                            <div class="col-4 mb-4">
                                <a href="/m/daftar-harga/1" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu" style="color: #e5131d;"></i>
                                        </div>
                                        <dd>Telkomsel</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4 mb-4">
                                <a href="/m/daftar-harga/2" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu" style="color: #fcd401;"></i>
                                        </div>
                                        <dd>Indosat</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4 mb-4">
                                <a href="/m/daftar-harga/3" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu" style="color: #164396;"></i>
                                        </div>
                                        <dd>XL</dd>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-4">
                                <a href="/m/daftar-harga/5" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu" style="color: #ff2082;"></i>
                                        </div>
                                        <dd>Tri</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4 mb-4">
                                <a href="/m/daftar-harga/6" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu" style="color: #ff1659;"></i>
                                        </div>
                                        <dd>Smartfren</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4 mb-4">
                                <a href="/m/daftar-harga/4" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cellphone-android icon-menu" style="color: #34cef9;"></i>
                                        </div>
                                        <dd>Axis</dd>
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