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
            @if($type == 1)
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/1" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/providers/telkomsel.png" width="60px" alt="logo-telkomsel">
                                        <dd>Telkomsel</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/2" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/providers/indosat.png" width="50px" alt="logo-indosat">
                                        <dd>Indosat</dd>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/3" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/providers/xl.png" height="40px" alt="logo-xl">
                                        <dd>XL</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/4" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/providers/axis.png" width="50px" alt="logo-axis">
                                        <dd>Axis</dd>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/5" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/providers/3.png" height="30px" alt="logo-3">
                                        <dd>3</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/6" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/providers/smartfren.png" width="60px" alt="logo-smartfren">
                                        <dd>smartfren</dd>
                                    </div>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            @endif
            @if($type == 2)
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/data/1" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/providers/telkomsel.png" width="60px" alt="logo-telkomsel">
                                        <dd>Telkomsel</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/data/2" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/providers/indosat.png" width="50px" alt="logo-indosat">
                                        <dd>Indosat</dd>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/data/3" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/providers/xl.png" height="40px" alt="logo-xl">
                                        <dd>XL</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/data/4" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/providers/axis.png" width="50px" alt="logo-axis">
                                        <dd>Axis</dd>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/data/5" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/providers/3.png" height="30px" alt="logo-3">
                                        <dd>3</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/daftar-harga/data/6" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/providers/smartfren.png" width="60px" alt="logo-smartfren">
                                        <dd>smartfren</dd>
                                    </div>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            @endif
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
