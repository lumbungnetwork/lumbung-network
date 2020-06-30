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
                    <div class="rounded-lg bg-white p-3">
                        <h6 class="mb-3">Notifikasi</h6>
                        @if($dataOrder > 0)
                        <div class="alert alert-warning" role="alert">
                            <div class="row">
                                <div class="col-9">
                                    Permintaan order paket <?php //Permintaan Aktivasi Member Baru ?> ({{$dataOrder}})
                                </div>
                                <div class="col-3"> 
                                    <a href="{{ URL::to('/') }}/m/list/order-package" class="btn btn-sm btn-success rounded-pill"> Action </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($getDataMemberBuy != null)
                        <div class="alert alert-warning" role="alert">
                            <div class="row">
                                <div class="col-9">
                                    Konﬁrmasi Stockist Belanja Member ({{count($getDataMemberBuy)}})
                                </div>
                                <div class="col-3"> 
                                    <a href="{{ URL::to('/') }}/m/stockist-report" class="btn btn-sm btn-success rounded-pill"> Action </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($getDataVendorMemberBuy != null)
                        <div class="alert alert-warning" role="alert">
                            <div class="row">
                                <div class="col-9">
                                    Konﬁrmasi Vendor Belanja Member ({{count($getDataVendorMemberBuy)}})
                                </div>
                                <div class="col-3"> 
                                    <a href="{{ URL::to('/') }}/m/vendor-report" class="btn btn-sm btn-success rounded-pill"> Action </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($getDataVendorPPOBMemberBuy != null)
                        <div class="alert alert-warning" role="alert">
                            <div class="row">
                                <div class="col-9">
                                    Konﬁrmasi Vendor PPOB ({{count($getDataVendorPPOBMemberBuy)}})
                                </div>
                                <div class="col-3"> 
                                    <a href="{{ URL::to('/') }}/m/list/vppob-transaction" class="btn btn-sm btn-success rounded-pill"> Action </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

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
