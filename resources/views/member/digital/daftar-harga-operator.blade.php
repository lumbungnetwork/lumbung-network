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
                    <form id="form-insert" method="GET" action="/m/prepare/buy/ppob">
                        <div class="rounded-lg bg-white p-3 mb-3">
                            <h6 class="mb-3">Isi Pulsa</h6>
                            @if ( Session::has('message') )
                                <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{  Session::get('message')    }} 
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-xl-6">
                                    <fieldset class="form-group">
                                        <label for="user_name">Masukan No. HP Tujuan</label>
                                        <input type="text" class="form-control" name="no_hp" id="get_id" autocomplete="off" placeholder="No. HP">
                                    </fieldset>
                                </div>
                                <div class="col-xl-6">
                                    <fieldset class="form-group">
                                        <label for="user_name">Masukkan Username Vendor Tujuan Belanja Anda:</label>
                                        <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                        <input type="hidden" name="get_id" id="id_get_id">
                                        <ul class="typeahead dropdown-menu" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;" id="get_id-box"></ul>
                                    </fieldset>
                                </div>
                            </div>    
                            
                        </div>

                        @if($daftarHarga != null)
                        <div class="rounded-lg bg-white p-3 mb-3">
                            <div class="row">
                                <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card-box table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <td>Produk</td>
                                                    <td>Harga</td>
                                                    <td>##</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($daftarHarga as $row)
                                                <tr>
                                                    <td>{{$row['product_name']}}</td>
                                                    <td>{{$row['price']}}</td>
                                                    <td><input type="radio" name="harga" id="radio" value="{{$row['buyer_sku_code']}}__{{$row['price']}}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="rounded-lg bg-white p-3 mb-3">
                            <div class="row">
                                <div class="col-xl-12 col-xs-12">

                                    <div class="rounded-lg shadow-sm p-2">
                                        <div class="radio radio-primary">
                                            <input type="radio" name="type_pay" id="radio1" value="1">
                                            <label for="radio1">
                                                Bayar via vendor terdekat <b>(COD)</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="rounded-lg shadow-sm p-2">
                                        <div class="radio radio-primary">
                                            <input type="radio" name="type_pay" id="radio2" value="3">
                                            <label for="radio2">
                                                Bayar via eIDR (Direct)*
                                            </label>
                                            <br>
                                            <small>
                                                    Pembayaran via eIDR akan ditambah Rp. 1000, sebagai kontribusi langsung ke deviden LMB
                                                </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xl-12">
                                    <button type="submit" class="btn btn-success" >Lanjut</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
    <script>
       function inputSubmit(){
           var full_name = $("#full_name").val();
           var gender = $("#gender").val();
           var alamat = $("#alamat").val();
           var kota = $("#kota").val();
           var kecamatan = $("#kecamatan").val();
           var kelurahan = $("#kelurahan").val();
           var provinsi = $("#provinsi").val();
           var kode_pos = $("#kode_pos").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-profile?full_name="+full_name+"&gender="+gender+"&kota="+kota+"&provinsi="+provinsi+"&kecamatan="+kecamatan+"&kelurahan="+kelurahan+"&kode_pos="+kode_pos+"&alamat="+alamat ,
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
