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
                    <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">{{$headerTitle}}</h6>

                    @if ( Session::has('message') )
                    <div class="container">
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6>Pembelian Produk</h6>
                    <div class="row">
                        <div class="col-12">
                            <div class="rounded-lg bg-light shadow p-4 mb-1">
                                <p class="mb-1">{{$getDataMaster->message}}</p>
                                <dd>{{$getDataMaster->product_name}}</dd>
                                <a class="btn btn-sm btn-warning float-right"><span
                                        style="font-size: 14px;">Rp{{number_format($getDataMaster->ppob_price)}}</span></a>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            @if($getDataMaster->status == 2)
                            <p class="mb-0">Status</p>
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <h6 class="label label-success"><span style="font-size: 14px;">Tuntas</span></h6>
                                </div>
                            </div>
                            @if ($getDataMaster->type >=1 && $getDataMaster->type <3 || $getDataMaster->type >=21 &&
                                $getDataMaster->type <30)
                                    <?php   $array = json_decode($getDataMaster->return_buy, true);?> <dd><b>SN:</b>
                                    {{$array['data']['sn']}}</dd>
                                    @endif
                                    @endif
                                    @if($getDataMaster->status == 3)
                                    <p class="card-text">Status</p>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-md-12">
                                            <h5 class="label label-danger">Batal</h5>
                                        </div>
                                    </div>
                                    @endif

                                    <p class="card-text">Metode Pembayaran</p>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-md-12">
                                            @if($getDataMaster->buy_metode == 1)
                                            <div class="radio radio-primary" style="margin-bottom: 15px;">
                                                <input type="radio" name="radio" id="radio1" value="1" checked="">
                                                <label for="radio1">
                                                    Tunai
                                                </label>
                                            </div>
                                            @endif
                                            @if($getDataMaster->buy_metode == 3)
                                            <div class="radio radio-primary" style="margin-bottom: 15px;">
                                                <input type="radio" name="radio" id="radio3" value="3" checked="">
                                                <label for="radio3">
                                                    eIDR
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if($getDataMaster->buy_metode >= 1)
                                            @if($getDataMaster->status == 0)
                                            <a class="btn btn-dark"
                                                href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                            <button type="submit" class="btn btn-danger" id="submitBtn"
                                                data-toggle="modal" data-target="#rejectSubmit"
                                                onClick="rejectSubmit()">Batal</button>
                                            @endif
                                            @if($getDataMaster->status == 1)
                                            <a class="btn btn-dark"
                                                href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                            @if($getDataMaster->vendor_cek == null)
                                            @if($getDataMaster->return_buy == null)
                                            <button type="submit" class="btn btn-danger" id="submitBtn"
                                                data-toggle="modal" data-target="#rejectSubmit"
                                                onClick="rejectSubmit()">Batal</button>
                                            &nbsp;
                                            @endif
                                            @endif
                                            <button type="button" class="btn btn-success" id="submitBtn"
                                                data-toggle="modal" data-target="#confirmSubmit"
                                                onClick="inputSubmit()">Konfirmasi</button>
                                            @endif
                                            @if($getDataMaster->status == 2)
                                            <a class="btn btn-dark"
                                                href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                            @if($getDataMaster->type >= 3 && $getDataMaster->type < 11) <a
                                                class="btn btn-warning"
                                                href="{{ URL::to('/') }}/m/vinvoice/ppob/{{$getDataMaster->id}}">Cetak
                                                Struk
                                                Transaksi</a>
                                                @endif
                                                @endif
                                                @if($getDataMaster->status == 3)
                                                <a class="btn btn-dark"
                                                    href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                                @endif
                                                @endif
                                                @if($getDataMaster->buy_metode == 3)
                                                <a class="btn btn-dark"
                                                    href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog"
                                        aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                                        <div class="modal-dialog" role="document" id="rejectDetail">
                                        </div>
                                    </div>
                                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog"
                                        aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                                        <div class="modal-dialog" role="document" id="confirmDetail">
                                        </div>
                                    </div>
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
@if($getDataMaster->buy_metode == 1)
@if($getDataMaster->status < 2) <script>

    function inputSubmit(){
    $.ajax({
    type: "GET",
    url: "{{ URL::to('/') }}/m/cek/confirm-2fa-ppob?id_ppob="+{{$getDataMaster->id}},
    success: function(url){
    $("#confirmDetail" ).empty();
    $("#confirmDetail").html(url);
    setTimeout(() => {
    $('#show-password').click(function(){
    if($(this).is(':checked')){
    $('#2fa').attr('type','text');
    }else{
    $('#2fa').attr('type','password');
    }
    });
    }, 1000);
    }
    });
    }

    function rejectSubmit(){
    $.ajax({
    type: "GET",
    url: "{{ URL::to('/') }}/m/cek/reject/buy-ppob?id_ppob="+{{$getDataMaster->id}},
    success: function(url){
    $("#rejectDetail" ).empty();
    $("#rejectDetail").html(url);
    }
    });
    }

    function confirmSubmit(){
    var dataInput = $("#form-add").serializeArray();
    $('#form-add').submit();
    $('#form-add').remove();
    $('#loading').show();
    $('#tutupModal').remove();
    $('#submit').remove();
    }
    </script>
    @endif
    @endif
    @stop
