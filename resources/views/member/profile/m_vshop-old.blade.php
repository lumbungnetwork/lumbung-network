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
                        <h4 class="page-title">Belanja</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                    $col = 6;
                    if($cekRequest != null){
                        $col = 12;
                    }
                ?>
                <div class="col-sm-{{$col}} col-xs-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
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
                                    <ul class="typeahead dropdown-menu form-control" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 98%;margin-left: 11px;" id="get_id-box"></ul>
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                @if($cekRequest == null)
                <div class="col-sm-6 col-xs-12">
                    <div class="card card-block">
                        <p class="card-text">
                            Ingin menjadi Vendor di Area Anda?
                            Dapatkan <b>2 LMB</b> setiap kelipatan
                            Rp100.000,00 pembelanjaan Member
                            di Vendor Anda!
                        </p>
                        <a href="{{ URL::to('/') }}/m/req/vendor" class="btn btn-info">Apply</a>
                    </div>
                </div>
                @endif
            </div>
            
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
            <div class="row">
                <div class="col-sm-12 card-box table-responsive">
                    <h4 class="header-title m-t-0">Vendor terdekat di Area anda</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>HP</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($getDataKelurahan != null)
                                @foreach($getDataKelurahan as $row)    
                                    <tr>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</td>
                                        <td>{{$row->hp}}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/vshoping/{{$row->id}}"> <i class="fa fa-rocket m-r-5"></i> <span>Shop</span> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            
                            @if($getDataKecamatan != null)
                                @foreach($getDataKecamatan as $row)    
                                    <tr>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</td>
                                        <td>{{$row->hp}}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/vshoping/{{$row->id}}"> <i class="fa fa-rocket m-r-5"></i> <span>Shop</span> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            
                            @if($getDataKota != null)
                                @foreach($getDataKota as $row)    
                                    <tr>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</td>
                                        <td>{{$row->hp}}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/vshoping/{{$row->id}}"> <i class="fa fa-rocket m-r-5"></i> <span>Shop</span> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if($getData != null)
            <div class="row">
                <div class="col-sm-12 card-box table-responsive">
                    <h4 class="header-title m-t-0">Vendor terdekat di Area anda</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>HP</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($getData as $row)    
                                    <tr>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</td>
                                        <td>{{$row->hp}}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/vshoping/{{$row->id}}"> <i class="fa fa-rocket m-r-5"></i> <span>Shop</span> </a>
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop

@section('javascript')
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