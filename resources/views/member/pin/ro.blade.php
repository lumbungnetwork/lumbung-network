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
                        <h4 class="page-title">Subscription (Iuran Anggota Tahunan)</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <?php
                $sum_pin_masuk = 0;
                $sum_pin_keluar = 0;
                if($dataPin->sum_pin_masuk != null){
                    $sum_pin_masuk = $dataPin->sum_pin_masuk;
                }
                if($dataPin->sum_pin_keluar != null){
                    $sum_pin_keluar = $dataPin->sum_pin_keluar;
                }
                $total = $sum_pin_masuk - $sum_pin_keluar;
                $future = strtotime('+1 years', strtotime($dataUser->active_at)); 
                $timefromdb =time(); 
                $timeleft = $future - $timefromdb;
                $daysleft = round((($timeleft/24)/60)/60); 
            ?>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card-box tilebox-one">
                        <a href="{{ URL::to('/') }}/m/add/pin" class="btn btn-sm btn-custom waves-effect waves-light pull-xs-right">Beli Pin</a>
                        <h6 class="text-muted text-uppercase m-b-20">Jumlah PIN tersedia</h6>
                        <h3 class="m-b-20">{{$total}}</h3>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box tilebox-two">
                        <h6 class="text-muted text-uppercase m-b-15">Masa Aktif Keanggotaan</h6>
                        <h3 class="m-b-20"><span>{{$daysleft}}</span></h3>
                        <p class="text-muted m-b-0 font-13">hari sebelum kadaluwarsa</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card-box tilebox-two">
                        <h6 class="text-muted text-uppercase m-b-15">Detail</h6>
                        <p class="text-muted m-b-0 font-13">Aktif sejak: <b>{{date('d F Y', strtotime($dataUser->active_at))}}</b></p>
                        <p class="text-muted m-b-0 font-13">Siklus <b>0 Siklus</b></p>
                        <p class="text-muted m-b-0 font-13">Kadaluwarsa: <b>{{date('d F Y', strtotime('+365 days', strtotime($dataUser->active_at)))}}</b></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-11">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">??</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xl-12">
                                    <fieldset class="form-group">
                                        <label for="input_jml_pin">Resubscribe (Perpanjang Keanggotaan)</label>
                                        <input type="hidden" class="form-control allownumericwithoutdecimal invalidpaste" id="input_jml_pin" name="total_pin" autocomplete="off" value ="1">
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Resubscribe</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop
@section('javascript')
<script>
       function inputSubmit(){
           var total_pin = $("#input_jml_pin").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/repeat-order?total_pin="+total_pin,
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
        
        $('.invalidpaste').on('paste', function (event) {
            if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
                event.preventDefault();
            }
        });

</script>
@stop