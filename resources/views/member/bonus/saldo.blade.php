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
                            <h4 class="page-title">Saldo Bonus</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }} 
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="card-box tilebox-one">
                            <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Total Bonus (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($dataAll->total_bonus, 0, ',', '.')}}</h2>
                        </div>
                    </div>
                    <?php
                    $saldo = $dataAll->total_bonus - $dataAll->total_wd - $dataAll->total_tunda - $dataAll->total_fee_admin - ($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr + $dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr);
//                    if($saldo < 0){
//                        $saldo = 0;
//                    }
                    if($saldo > -5000 && $saldo <= 0){
                        $saldo = 0;
                    }
                    ?>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
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
                            <i class="icon-rocket pull-xs-right text-muted text-success"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Transfer (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($dataAll->total_wd + $dataAll->fee_tuntas, 0, ',', '.')}}</h2>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="card-box tilebox-one">
                            <i class="icon-speedometer pull-xs-right text-muted text-warning"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Proses Transfer (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($dataAll->total_tunda + $dataAll->fee_tunda, 0, ',', '.')}}</h2>
                        </div>
                    </div>
                
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="card-box tilebox-one">
                            <i class="icon-rocket pull-xs-right text-muted text-success"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Konversi (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr, 0, ',', '.')}}</h2>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="card-box tilebox-one">
                            <i class="icon-speedometer pull-xs-right text-muted text-warning"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Proses Konversi (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr, 0, ',', '.')}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('layout.member.footer')
@stop

@section('styles')
<!--<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('asset_member/plugins/morris/morris.css') }}">-->
@stop
@section('javascript')
<!--<script src="{{ asset('asset_member/plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset('asset_member/plugins/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('asset_member/plugins/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('asset_member/plugins/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('asset_member/pages/jquery.dashboard.js') }}"></script>-->
<script>
       function inputSubmit(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd",
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
        }

</script>
@stop