@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')
<div class="main-panel">
    
    <?php //MENU HEADER  ?>
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <p class="navbar-brand">{{$headerTitle}}</p>
            </div>
        </div>
    </nav>
    
    <?php //MENU CONTENT  ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Produk</h5>
                    </div>
                    <div class="card-body">
                        @if ( Session::has('message') )
                            <div class="widget-content mt10 mb10 mr15">
                                <div class="alert alert-{{ Session::get('messageclass') }}">
                                    <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                    {{  Session::get('message')    }} 
                                </div>
                            </div>
                        @endif
                         <form class="login100-form validate-form" method="post" action="/adm/edit/purchase">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$getData->id}}">

                            <div class="row">
                                <label class="col-md-2 col-form-label">Nama</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" required="true" autocomplete="off" value="{{$getData->name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Ukuran Kemasan</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="ukuran" required="true" autocomplete="off" value="{{$getData->ukuran}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Harga Stockist</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control allownumericwithoutdecimal" name="stockist_price" required="true" autocomplete="off" value="{{number_format($getData->stockist_price, 0, ',', '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Harga Member</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control allownumericwithoutdecimal" name="member_price" required="true" autocomplete="off" value="{{number_format($getData->member_price, 0, ',', '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Kode Produk</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="code" required="true" autocomplete="off" value="{{$getData->code}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Area</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="provinsi" id="provinsi" onChange="getSearchKota(this.value);">
                                                <option value="0">- Pilih Provinsi -</option>
                                                @if($provinsi != null) 
                                                    @foreach($provinsi as $row)
                                                        <option value="{{$row->propinsi}}" @if($detailProvinsi->propinsi == $row->propinsi) selected @endif>{{$row->nama}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">&nbsp;</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="kota" id="kota">
                                            @if($allKota != null) 
                                                @foreach($allKota as $row)
                                                    <option value="{{$row->kode}}" @if($detailKota->kode == $row->kode) selected @endif>{{$row->nama}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="row">
                                <label class="col-md-2 col-form-label">Gambar (URL)</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="image" required="true" autocomplete="off" value="{{$getData->image}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
        function getSearchKota(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search/kota" + "?provinsi=" + val,
                success: function(url){
                        $( "#kota" ).empty();
                        $("#kota").html(url);
                }
            });
        }
        
        function getSearchKecamatan(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search/kecamatan" + "?kota=" + val,
                success: function(url){
                        $( "#kecamatan" ).empty();
                        $("#kecamatan").html(url);
                }
            });
        }
        
        function getSearchKelurahan(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search/kelurahan" + "?kecamatan=" + val,
                success: function(url){
                        $( "#kelurahan" ).empty();
                        $("#kelurahan").html(url);
                }
            });
        }

</script>
@stop
