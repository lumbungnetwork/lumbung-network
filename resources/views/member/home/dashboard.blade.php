@extends('layout.member.main')

@section('content')
@include('layout.member.topbar')
@include('layout.member.sidebar')

    <div class="content-page">
        <div class="content">
            <div class="container">
                @if($dataUser->is_active == 1)
                    @if($dataOrder > 0)
                        <div class="alert alert-warning" role="alert">
                            <b> Permintaan order paket (Total {{$dataOrder}}) - </b> <a href="{{ URL::to('/') }}/m/list/order-package" class="label label-primary">link</a>
                        </div>
                    @endif
                    @if($getDataMemberBuy != null)
                        <div class="alert alert-warning" role="alert">
                            <b> Konfirmasi penjualan member (Total {{count($getDataMemberBuy)}}) - </b> <a href="{{ URL::to('/') }}/m/stockist-report" class="label label-primary">link</a>
                        </div>
                    @endif
                    @if($dataUser->id > 4)
                        @if($dataUser->upline_id == null)
                            <div class="alert alert-warning" role="alert">
                                Akun anda belum diplacement, Silahkan Hubungi Sponsor Anda Untuk Placement
                            </div>
                        @endif
                    @endif
                @endif
                @if($dataUser->is_active == 0)
                    @if($dataOrder > 0)
                        <div class="alert alert-warning" role="alert">
                            Silahkan Hubungi Sponsor Anda Untuk Aktifasi
                        </div>
                    @endif
                @endif
                @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }} 
                    </div>
                @endif
                
                @if($dataUser->is_active == 1)
                    @if($dataUser->upline_id == null)
                    @if($dataSponsor != null)
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="card-box tilebox-three">
                                <div class="bg-icon pull-xs-left">
                                    @if($dataSponsor->gender == 2)
                                    <i class="icon-user-female"></i>
                                    @else 
                                    <i class="icon-user"></i>
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
                    @endif
                    @endif
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="card-box widget-user">
                                <div>
                                    @if($dataMy->image != null)
                                        <img src="{{$dataMy->image}}" class="img-responsive img-circle" alt="{{$dataUser->user_code}}">
                                    @endif
                                    @if($dataMy->image == null)
                                        <img src="/asset_member/images/profile.jpg" class="img-responsive img-circle" alt="{{$dataUser->user_code}}">
                                    @endif
                                    <div class="wid-u-info">
                                        <h6> {{$dataUser->user_code}}</h6>
                                        <p class="text-muted m-b-0 font-13">{{$dataMy->name}}</p>
                                        <div class="user-position">
                                            <span class="text-warning font-weight-bold">Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<!--                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-5">
                            <div class="card-box tilebox-two">
                                @if($dataMy->image != null)
                                    <img src="{{$dataMy->image}}" class="img-responsive pull-xs-right text-muted" alt="{{$dataUser->user_code}}" style="height: 90px;">
                                @endif
                                @if($dataMy->image == null)
                                    <img src="/asset_member/images/profile.jpg" class="img-responsive pull-xs-right text-muted" alt="{{$dataUser->user_code}}" style="height: 90px;">
                                @endif
                                <h6 class="text-muted text-uppercase m-b-15 m-t-10">Peringkat Anda</h6>
                                <h2 class="m-b-10">{{$dataMy->name}}</h2>
                            </div>
                        </div>-->

                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="card-box tilebox-two">
                                <a href="{{ URL::to('/') }}/ref/{{$dataUser->user_code}}" class="btn btn-sm btn-custom waves-effect waves-light pull-xs-right" target="_blank">View</a>
                                <h6 class="text-muted text-uppercase m-b-15">Referral Link</h6>
                                <i class="icon-share"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-basket-loaded pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Akumulasi Belanja (Rp.)</h6>
                                <h2 class="m-b-20">{{number_format($dataMy->sales, 0, ',', '.')}}</g2>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Total Bonus (Rp.)</h6>
                                <h2 class="m-b-20">{{number_format($dataAll->total_bonus, 0, ',', '.')}}</h2>
                            </div>
                        </div>
                        <?php
                            $saldo = $dataAll->total_bonus - $dataAll->total_wd - $dataAll->total_tunda - $dataAll->total_fee_admin;
                            if($saldo < 0){
                                $saldo = 0;
                            }
                            $total_wd = $dataAll->total_wd + $dataAll->fee_tuntas;
                        ?>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-rocket pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Bonus Ditransfer (Rp.)</h6>
                                <h2 class="m-b-20">{{number_format($total_wd, 0, ',', '.')}}</h2>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-lock pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Saldo Bonus (Rp.)</h6>
                                <h2 class="m-b-20">{{number_format($saldo, 0, ',', '.')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-user-following pull-xs-right text-muted text-success"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Jml Sponsor</h6>
                                <h2 class="m-b-20">{{$dataUser->total_sponsor}}</h2>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-vector pull-xs-right text-muted text-purple"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Jml Kiri</h6>
                                <h2 class="m-b-20">{{$dataAll->kiri}}</h2>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-vector pull-xs-right text-muted text-pink"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Jml Kanan</h6>
                                <h2 class="m-b-20">{{$dataAll->kanan}}</h2>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="card-box tilebox-one">
                                <i class="icon-user-unfollow pull-xs-right text-muted text-danger"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Member Belum Aktif</h6>
                                <h2 class="m-b-20">{{$dataAll->member_tdk_aktif}}</h2>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($dataUser->is_active == 0)
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="card-box tilebox-three">
                            <div class="bg-icon pull-xs-left">
                                @if($dataSponsor->gender == 2)
                                <i class="icon-user-female"></i>
                                @else 
                                <i class="icon-user"></i>
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
                @endif
            </div>
        </div>
    </div>
@include('layout.member.footer')
@stop

@section('styles')
<!--<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />-->
<!--<link rel="stylesheet" href="{{ asset('asset_member/plugins/morris/morris.css') }}">-->
@stop
@section('javascript')
<!--<script src="{{ asset('asset_member/plugins/morris/morris.min.js') }}"></script>-->
<!--<script src="{{ asset('asset_member/plugins/raphael/raphael-min.js') }}"></script>-->
<!--<script src="{{ asset('asset_member/plugins/waypoints/lib/jquery.waypoints.js') }}"></script>-->
<!--<script src="{{ asset('asset_member/plugins/counterup/jquery.counterup.min.js') }}"></script>-->
<!--<script src="{{ asset('asset_member/pages/jquery.dashboard.js') }}"></script>-->
@stop