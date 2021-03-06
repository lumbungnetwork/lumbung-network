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
                            <h4 class="page-title">Request WD</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }} 
                    </div>
                @endif
                <div class="row">
                    <?php
                        $saldo = $dataAll->total_bonus - $dataAll->total_wd - $dataAll->total_tunda - $dataAll->total_fee_admin - ($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr + $dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr);
//                        if($saldo < 0){
//                            $saldo = 0;
//                        }
                        if($saldo > -20000 && $saldo <= 0){
                            $saldo = 0;
                        }
                        $total_wd = $dataAll->total_wd + $dataAll->fee_tuntas;
                        $total_tunda = $dataAll->total_tunda + $dataAll->fee_tunda;
                        $total_wd_eidr = $dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr;
                        $total_tunda_eidr = $dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr;
                    ?>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-one">
                            <i class="icon-lock pull-xs-right text-muted text-warning"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Saldo Bonus (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($saldo, 0, ',', '.')}}</h2>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-one">
                            <i class="icon-rocket pull-xs-right text-muted text-success"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Ditransfer (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($total_wd, 0, ',', '.')}}</h2>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-one">
                            <i class="icon-speedometer pull-xs-right text-muted text-warning"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Proses Transfer (Rp.)</h6>
                            <h2 class="m-b-20">{{number_format($total_tunda, 0, ',', '.')}}</h2>
                        </div>
                    </div>
                
                    
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-block">
                                <p class="card-text">Ajukan withdraw anda disini</p>
                                <div class="row">
                                    <div class="col-xl-8 col-xs-12">
                                        <fieldset class="form-group">
                                            <label for="input_jml">Jumlah (Rp.)</label>
                                            <input type="text" class="form-control allownumericwithoutdecimal" id="input_jml" name="jml_wd" autocomplete="off" placeholder="Minimum Withdraw Rp. 20.000">
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-4 col-xs-12">
                                        <fieldset class="form-group">
                                            <label>Admin Fee (Rp.)</label>
                                            <input type="text" class="form-control" disabled="" value="6.500">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                    <div class="modal-dialog" role="document" id="confirmDetail">
                    </div>
                </div>
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
<script>
       function inputSubmit(){
           var input_jml_wd = $("#input_jml").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd?input_jml_wd="+input_jml_wd,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
            $('#tutupModal').remove();
            $('#submit').remove();
        }
        
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

</script>
@stop