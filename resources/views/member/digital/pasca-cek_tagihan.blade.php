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
                        @if($getData['rc'] != '00')
                            <div class="container">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{ $getData['message'] }}
                                </div>
                            </div>

                        @endif

                        @if($getData['rc'] == '00')
                            <?PHP
                                if($type == 4){
                                    $nama = 'BPJS';
                                    $selling_price = $getData['selling_price'];
                                }
                                if($type == 5){
                                    $nama = 'PLN';
                                    $selling_price = $getData['selling_price'] + 500;
                                }
                                if($type == 6){
                                    $nama = 'HP Pascabayar';
                                    $selling_price = $getData['selling_price'] + 1000;
                                }
                                if($type == 7){
                                    $nama = 'Telkom PTSN';
                                    $selling_price = $getData['selling_price'] + 1000;
                                }
                                if($type == 8){
                                    $nama = 'PDAM';
                                    $selling_price = $getData['selling_price'] + 900;
                                }
                            ?>

                        <h4 class="mb-3">Pembayaran {{$nama}}</h4>

                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label>No Pelanggan</label>
                                    <input type="text" class="form-control"  autocomplete="off" value="{{$getData['customer_no']}}">
                                </fieldset>
                            </div>

                        </div>
                    </div>



                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-xs-8 col-md-12 col-lg-12 col-xl-12">
                                <fieldset class="form-group">
                                    <label>Nama Pelanggan</label>
                                    <input type="text" class="form-control" autocomplete="off" value="{{$getData['customer_name']}}">
                                </fieldset>
                            </div>
                            <div class="col-xs-4 col-md-12 col-lg-12 col-xl-12">
                                <fieldset class="form-group">
                                    <label>Biaya</label>
                                    <input type="text" class="form-control" autocomplete="off" value="Rp. {{$selling_price}}">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            @if($getData['rc'] == '00')
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="user_name">Masukkan Username Vendor Tujuan Belanja Anda:</label>
                                    <small>Ketikkan 3-4 huruf awal, lalu klik opsi yang tampil</small>
                                    <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                    <input type="hidden" name="get_id" id="id_get_id">
                                    <ul class="typeahead dropdown-menu" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;" id="get_id-box"></ul>
                                </fieldset>
                            </div>
                            @endif

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
    @if($getData['rc'] == '00')

    <script>
       function inputSubmit(){
           var vendor_id = $("#id_get_id").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/buy/ppob-pasca?no_hp={{$getData['customer_no']}}&vendor_id="+vendor_id+"&harga={{$selling_price}}&type_pay=1&type={{$type}}&ref_id={{$getData['ref_id']}}&price={{$getData['selling_price']}}&customer_no={{$getData['customer_no']}}&buyer_sku_code={{$buyer_sku_code}}",
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

    </script>
    @endif
@stop
