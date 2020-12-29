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
                                    $selling_price = $getData['selling_price'] + 650;
                                }
                                if($type == 9){
                                    $nama = 'PGN';
                                    $selling_price = $getData['selling_price'] + 800;
                                }
                                if($type == 10){
                                    $nama = 'Multifinance';
                                    $selling_price = $getData['selling_price'] + 5000;
                                }
                            ?>

                    <h4 class="mb-3">Pembayaran {{$nama}}</h4>

                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <fieldset class="form-group">
                                <label>No Pelanggan</label>
                                <input type="text" class="form-control" autocomplete="off"
                                    value="{{$getData['customer_no']}}">
                            </fieldset>
                        </div>

                    </div>
                </div>



                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xs-8 col-md-12 col-lg-12 col-xl-12">
                            <fieldset class="form-group">
                                <label>Nama Pelanggan</label>
                                <input type="text" class="form-control" autocomplete="off"
                                    value="{{$getData['customer_name']}}">
                            </fieldset>
                        </div>
                        <div class="col-xs-4 col-md-12 col-lg-12 col-xl-12">
                            <fieldset class="form-group">
                                <label>Tagihan</label>
                                <input type="text" class="form-control" autocomplete="off"
                                    value="Rp{{number_format($selling_price)}}">
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        @if($dataUser->is_vendor == 0)
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="user_name">Masukkan Username Vendor Tujuan Belanja Anda:</label>
                                <small>Ketikkan 3-4 huruf awal, lalu klik opsi yang tampil</small>
                                <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                <input type="hidden" name="get_id" id="id_get_id">
                                <ul class="typeahead dropdown-menu"
                                    style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;"
                                    id="get_id-box"></ul>
                            </fieldset>
                        </div>
                        <br>
                        <div class="col-12">
                            <button type="button" class="btn btn-lg btn-block btn-success" id="submitBtn"
                                onClick="checkOrder()">Lanjut ke
                                Pembayaran</button>
                        </div>
                        @else
                        <div class="col-12">
                            <button type="button" class="btn btn-lg btn-block btn-success" id="vendorPayBtn"
                                onClick="checkVendorPay()">Bayar
                                Sekarang</button>
                        </div>
                        @endif

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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
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

        var no_hp = '{{$getData['customer_no']}}';
        var vendor_id = $("#id_get_id").val();
</script>
@if($getData['rc'] == '00')

<script>
    var buyer_sku_code = '{{$getData['buyer_sku_code']}}';
    var price = '{{$selling_price}}';
    var product_name = '{{$nama}}';

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        function confirmBuy() {
            var form = $('#form-add');
            Swal.fire('Sedang Memproses');
            Swal.showLoading();
            $(document.body).append(form);
            form.submit();
        }

        function checkOrder() {
            if(vendor_id == undefined) {
                errorToast('Anda belum mengisi vendor tujuan belanja');
                return false;
            }

            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/check-order-postpaid?no_hp="+no_hp+"&product_name="+product_name+"&vendor_id="+vendor_id+"&price="+price+"&buyer_sku_code="+buyer_sku_code+"&type={{$type}}",
                success: function(url){
                    Swal.fire({
                        html: url,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                }
            });
        }

        function checkVendorPay() {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/confirm-vendor-quickbuy-postpaid?no_hp="+no_hp+"&product_name="+product_name+"&price="+price+"&buyer_sku_code="+buyer_sku_code+"&type={{$type}}",
                success: function(url){
                    Swal.fire({
                        html: url,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                }
            });
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            width: 200,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        function errorToast (message) {
            Toast.fire({
                icon: 'error',
                title: message
            })
        }

</script>
@endif
@stop
