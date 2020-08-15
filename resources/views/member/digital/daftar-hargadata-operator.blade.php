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
                            <h6 class="mb-3">Isi Paket Data</h6>
                            @if ( Session::has('message') )
                                <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{  Session::get('message')    }} 
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-xl-12 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="user_name">Masukan No. HP Tujuan</label>
                                        <input type="text" class="form-control" name="no_hp" id="no_hp" autocomplete="off" placeholder="No. HP">
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
                                                    <td>{{number_format($row['price'], 0, ',', ',')}}</td>
                                                    <td><input type="radio" name="harga" id="harga" value="{{$row['buyer_sku_code']}}__{{$row['price']}}__{{$row['brand']}}__{{$row['desc']}}__{{$row['real_price']}}"></td>
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
                                <div class="col-xl-12 col-xs-12" id="vendor_name">
                                    <fieldset class="form-group">
                                        <label for="user_name">Masukkan Username Vendor Tujuan Belanja Anda:</label>
                                        <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                        <input type="hidden" name="get_id" id="id_get_id">
                                        <ul class="typeahead dropdown-menu" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;" id="get_id-box"></ul>
                                    </fieldset>
                                </div>
                                <div class="col-xl-12 col-xs-12">

                                    <div class="rounded-lg shadow-sm p-2">
                                        <div class="radio radio-primary">
                                            <input type="radio" id="type_pay" name="type_pay" value="1" class="cod">
                                            <label for="radio1">
                                                Bayar via vendor terdekat <b>(COD)</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="rounded-lg shadow-sm p-2">
                                        <div class="radio radio-primary">
                                            <input type="radio" id="type_pay"  name="type_pay" value="3" class="eidr">
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
                                    <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                                </div>
                            </div>
                            <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document" id="confirmDetail">
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
           var no_hp = $("#no_hp").val();
           var vendor_id = $("#id_get_id").val();
           var harga = $('input[type=radio][name=harga]:checked').attr('value');
           var type_pay = $('input[type=radio][name=type_pay]:checked').attr('value');
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/buy/ppob?no_hp="+no_hp+"&vendor_id="+vendor_id+"&harga="+harga+"&type_pay="+type_pay+"&type=2",
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
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
        
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        $('#vendor_name').show();
        $(".cod").click(function() {
          $('#vendor_name').show();
        });

        $(".eidr").click(function() {
          $('#vendor_name').hide();
        });
    </script>
@stop
