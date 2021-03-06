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
                        <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                            <i class="fas fa-power-off text-danger icon-bottom"></i>
                        </a>
                    </div>
                </nav>
            </div>
            @if(!empty($data))
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">

                        <h5>{{$headerTitle}}</h5>
                        <div class="row mt-3">
                            @foreach($data as $row)
                            <div class="col-6 mb-6 p-3">
                                <a href="/m/detail/pdam/{{$row['buyer_sku_code']}}?product_name={{$row['product_name']}}" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-water icon-menu" style="color: #357db8;"></i>
                                        </div>
                                        <dd>{{$row['product_name']}}</dd>
                                    </div>
                                </a>
                            </div>
                            @endforeach
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
