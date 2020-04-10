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
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <form method="POST" action="/m/s/vendor">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="user_name">Masukkan Username Vendor Tujuan Belanja Anda:</label>
                                        <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                        <input type="hidden" name="get_id" id="id_get_id">
                                    <ul class="typeahead dropdown-menu" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;" id="get_id-box"></ul>
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    @if($dataUser->is_stockist == 0 && $dataUser->is_vendor == 0)
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <p>
                                    Ingin menjadi Vendor di Area Anda?
                                    Dapatkan <b>2 LMB</b> setiap kelipatan
                                    Rp100.000,00 pembelanjaan Member
                                    di Vendor Anda!
                                </p>
                                <a href="{{ URL::to('/') }}/m/req/vendor" class="btn btn-success">Apply</a>
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
                    @if($totalData > 0)
                    
                        @if($getDataKelurahan != null)
                            @foreach($getDataKelurahan as $row)
                                <div class="rounded-lg bg-white p-3 mb-3">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <p>{{$row->full_name}}</p>
                                            <p>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</p>
                                            <p>{{$row->hp}}</p>
                                            <p>{{$row->full_name}}</p>
                                            <p><a class="btn btn-success btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/shoping/{{$row->id}}"> <span>Shop</span> </a></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($getDataKecamatan != null)
                                @foreach($getDataKecamatan as $row)
                                <div class="rounded-lg bg-white p-3 mb-3">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <p>{{$row->full_name}}</p>
                                            <p>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</p>
                                            <p>{{$row->hp}}</p>
                                            <p>{{$row->full_name}}</p>
                                            <p><a class="btn btn-success btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/shoping/{{$row->id}}"> <span>Shop</span> </a></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($getDataKota != null)
                                @foreach($getDataKota as $row)  
                                <div class="rounded-lg bg-white p-3 mb-3">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <p>{{$row->full_name}}</p>
                                            <p>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</p>
                                            <p>{{$row->hp}}</p>
                                            <p>{{$row->full_name}}</p>
                                            <p><a class="btn btn-success btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/shoping/{{$row->id}}"> <span>Shop</span> </a></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                    
                    @if($getData != null)
                        <div class="rounded-lg bg-white p-3 mb-3">
                            <div class="row">
                                @foreach($getData as $row)
                                    <div class="col-sm-12 col-xs-12">
                                        <p>{{$row->full_name}}</p>
                                        <p>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</p>
                                        <p>{{$row->hp}}</p>
                                        <p>{{$row->full_name}}</p>
                                        <p><a class="btn btn-success btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/shoping/{{$row->id}}"> <span>Shop</span> </a></p>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
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
    <script type="text/javascript">
    $(document).ready(function(){
        $("#get_id").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/usercode-vendor" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box").show();
                    $("#get_id-box").html(data);
                }
            });
        });
    });
    function selectUsername(val) {
        var valNew = val.split("____");
        $("#get_id").val(valNew[1]);
        $("#id_get_id").val(valNew[0]);
        $("#get_id-box").hide();
    }
</script>
@stop
