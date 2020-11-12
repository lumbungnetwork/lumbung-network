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
                        <div class="d-flex flex-row justify-content-around">

                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/emoney/1" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/emoney/gopay.jpg" width="60px" alt="logo-gopay">
                                        <dd>Gopay</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/emoney/2" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/emoney/etoll.jpg" width="60px" alt="logo-etoll">
                                        <dd>E-Toll</dd>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/emoney/3" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/emoney/ovo.jpg" width="60px" alt="logo-ovo">
                                        <dd>OVO</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/emoney/4" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/emoney/dana.jpg" width="60px" alt="logo-dana">
                                        <dd>DANA</dd>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/emoney/5" class="text-decoration-none">

                                    <div class="text-center ">
                                        <img src="/asset_new/img/emoney/linkaja.jpg" width="60px" alt="logo-linkaja">
                                        <dd>LinkAja</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="card p-3 m-3 media-body flex-column justify-content-center">
                                <a href="/m/emoney/6" class="text-decoration-none">

                                    <div class="text-center">
                                        <img src="/asset_new/img/emoney/shopeepay.jpg" width="60px" alt="logo-shopeepay">
                                        <dd>Shopee Pay</dd>
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
