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
                    <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg bg-white p-3 mb-3">
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissibl" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <fieldset class="form-group">
                                <label for="user_name">Masukkan Nama Toko Tujuan Belanja Anda:</label>
                                <small><em>Ketikkan 3-4 huruf awal, lalu pilih dari opsi yang tampil</em></small>
                                <input type="text" class="form-control" id="get_id" autocomplete="off">
                                <input type="hidden" id="id_get_id">
                                <ul class="typeahead dropdown-menu"
                                    style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;"
                                    id="get_id-box"></ul>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <a href="#" class="btn btn-success float-right" id="check-shop-btn">Cek Toko</a>
                        </div>
                    </div>
                </div>
                @if($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0)
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <small>
                                Ingin menjadi Stokis di Area Anda? <br>
                                Dapatkan <b>2 LMB</b> setiap kelipatan
                                Rp100.000,00 pembelanjaan Member
                                di Stokis Anda!
                            </small><br>
                            <a href="{{ URL::to('/') }}/m/req/stockist" class="btn btn-success float-right">Apply</a>
                        </div>
                    </div>
                </div>
                @endif
                <?php
                        $totKota = 0;
                        if($getDataKota != null){
                            $totKota = count($getDataKota);
                        }
                        $totKec = 0;
                        if($getDataKecamatan != null){
                            $totKec = count($getDataKecamatan);
                        }
                        $totKel = 0;
                        if($getDataKelurahan != null){
                            $totKel = count($getDataKelurahan);
                        }
                        $totalData = $totKota + $totKec + $totKel;
                    ?>


                <div class="rounded-lg bg-white p-3 mb-3">
                    @if($totalData == 0)
                    <h6 class="text-center">Belum ada Stockist di daerah anda.</h6>
                    @endif
                    @if($totalData > 0)
                    <h6>Stockist Terdekat</h6>

                    <div class="row">
                        @if($getDataKelurahan != null)
                        @foreach($getDataKelurahan as $row)
                        <div class="col-6 p-2 mb-3 text-center">
                            <div class="rounded-lg bg-white shadow p-2 px-0">
                                <a href="{{ URL::to('/') }}/m/shopping/{{$row->id}}">
                                    <img src="{{ asset('/storage/sellers') }}/{{$row->sellerProfile->image}}"
                                        style="width: auto; max-width: 100%;">
                                </a>
                                <h6 style="font-size: 14px; font-weight:200; margin-top: 10px;">
                                    {{$row->sellerProfile->shop_name}} </h6>
                                <dd style="font-size: 12px;">
                                    {{$row->alamat}}, {{$row->kelurahan}}, {{$row->kecamatan}}</dd>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        @if($getDataKecamatan != null)
                        @foreach($getDataKecamatan as $row)
                        <div class="col-6 p-2 mb-3 text-center">
                            <div class="rounded-lg bg-white shadow p-2 px-0">
                                <a href="{{ URL::to('/') }}/m/shopping/{{$row->id}}">
                                    <img src="{{ asset('/storage/sellers') }}/{{$row->sellerProfile->image}}"
                                        style="width: auto; max-width: 100%;">
                                </a>
                                <h6 style="font-size: 14px; font-weight:200; margin-top: 10px;">
                                    {{$row->sellerProfile->shop_name}} </h6>
                                <dd style="font-size: 12px;">
                                    {{$row->alamat}}, {{$row->kelurahan}}, {{$row->kecamatan}}</dd>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        @if($getDataKota != null)
                        @foreach($getDataKota as $row)
                        <div class="col-6 p-2 mb-3 text-center">
                            <div class="rounded-lg bg-white shadow p-2 px-0">
                                <a href="{{ URL::to('/') }}/m/shopping/{{$row->id}}">
                                    <img src="{{ asset('/storage/sellers') }}/{{$row->sellerProfile->image}}"
                                        style="width: auto; max-width: 100%;">
                                </a>
                                <h6 style="font-size: 14px; font-weight:200; margin-top: 10px;">
                                    {{$row->sellerProfile->shop_name}} </h6>
                                <dd style="font-size: 12px;">
                                    {{$row->alamat}}, {{$row->kelurahan}}, {{$row->kecamatan}}</dd>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    @endif
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
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#get_id").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/ajax/get-shop-name" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box").show();
                    $("#get_id-box").html(data);
                }
            });
        });
    });
    function selectUsername(val) {
        var valNew = val.split("___");
        $("#get_id").val(valNew[1]);
        $("#check-shop-btn").attr("href", "{{ URL::to('/') }}/m/shopping/" + valNew[0]);
        $("#get_id-box").hide();
    }
</script>
@stop
