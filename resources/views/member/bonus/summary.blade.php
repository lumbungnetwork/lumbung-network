@extends('layout.member.main')

@section('content')
@include('layout.member.topbar')
@include('layout.member.sidebar')

    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Ringkasan Bonus</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="card-box tilebox-one">
                                <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Total Bonus (Rp.)</h6>
                                <h2 class="m-b-20" data-plugin="counterup">{{number_format($dataAll->total_bonus, 0, ',', '.')}}</h2>
                            </div>
                        </div>
                        <?php
                            $total_wd = $dataAll->total_wd + $dataAll->fee_tuntas;
                        ?>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="card-box tilebox-one">
                                <i class="icon-rocket pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Bonus Ditransfer (Rp.)</h6>
                                <h2 class="m-b-20" data-plugin="counterup">{{number_format($total_wd, 0, ',', '.')}}</h2>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@include('layout.member.footer')
@stop

@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('asset_member/plugins/morris/morris.css') }}">
@stop
@section('javascript')
<script src="{{ asset('asset_member/plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset('asset_member/plugins/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('asset_member/plugins/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('asset_member/plugins/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('asset_member/pages/jquery.dashboard.js') }}"></script>
@stop