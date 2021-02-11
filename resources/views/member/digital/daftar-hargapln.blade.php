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
                    <h6 class="mb-3">Data Pelanggan</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="nomor-pelanggan">Nomor Pelanggan</label>
                        <input type="text" readonly class="form-control" id="nomor-pelanggan"
                            value="{{$dataCustomer['customer_no']}}">

                    </div>
                    <div class="form-group">
                        <label for="nama-pelanggan">Nama Pelanggan</label>
                        <input type="text" readonly class="form-control" id="nama-pelanggan"
                            value="{{$dataCustomer['name']}}">

                    </div>
                    <div class="form-group">
                        <label for="segmen-daya">Segmen/Daya</label>
                        <input type="text" readonly class="form-control" id="segmen-daya"
                            value="{{$dataCustomer['segment_power']}}">

                    </div>
                </div>

                @if($daftarHarga != null)
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box table-responsive">
                                <table class="price-table table">
                                    <tbody>
                                        @php
                                        $no = 1;
                                        @endphp
                                        @foreach($daftarHarga as $row)
                                        <tr>
                                            <td>
                                                <div class="pretty p-icon p-curve p-tada">
                                                    <input type="radio" name="product" id="harga{{$no}}"
                                                        value="{{$row['buyer_sku_code']}}__{{$row['product_name']}}__{{$row['price']}}">
                                                    <div class="state p-primary-o">
                                                        <i class="icon mdi mdi-check"></i>
                                                        <label>{{$row['product_name']}}</label>
                                                    </div>

                                                </div>
                                                <a class="btn btn-warning btn-sm float-right mt-2"
                                                    onclick="priceButton('harga{{$no}}')">Rp{{number_format($row['price'])}}</a>
                                                <br>
                                                <small class="text-muted">{{$row['desc']}}</small>
                                                @php
                                                $no++;
                                                @endphp
                                            </td>
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
                    @if($dataUser->is_vendor == 0)
                    <div class="row">
                        <div class="col-xl-12 col-xs-12" id="vendor_name">
                            <fieldset class="form-group">
                                <label for="user_name">Masukkan Username Vendor Tujuan:</label><br>
                                <small>Ketikkan 3-4 huruf awal, lalu klik opsi yang tampil</small>
                                <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                <input type="hidden" name="get_id" id="id_get_id">
                                <ul class="typeahead dropdown-menu"
                                    style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;"
                                    id="get_id-box"></ul>
                            </fieldset>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <button class="btn btn-lg btn-block btn-success" id="submitBtn" onClick="checkOrder()">Order
                                Sekarang</button>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div style="display: none" class="col-12" id="buyer_name">
                            <fieldset class="form-group">
                                <label for="user_name">Masukkan Username Pembeli:</label><br>
                                <small>Ketikkan 3-4 huruf awal, lalu klik opsi yang tampil</small>
                                <input type="text" class="form-control" id="get_id" name="user_name" autocomplete="off">
                                <input type="hidden" name="get_id" id="id_get_id">
                                <ul class="typeahead dropdown-menu"
                                    style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px;"
                                    id="get_id-box"></ul>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-lg btn-block btn-info" id="showBuyerInput"
                                onClick="showBuyerInput()">Beli untuk User
                                lain</button>
                            <button class="btn btn-lg btn-block btn-success" id="vendorPayBtn"
                                onClick="checkVendorPay()">Beli Langsung sebagai
                                Vendor</button>
                        </div>
                    </div>

                    @endif

                </div>
            </div>

        </div>
    </div>
    @include('layout.member.nav')
</div>
<div class="overlay"></div>
<div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="true">
    <div class="modal-dialog" role="document" id="confirmDetail">
    </div>
</div>
</div>

@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script type="text/javascript">
    @if($dataUser->is_vendor == 0)
    var uri = "/m/cek/usercode-vendor";
    @else
    var uri = "/m/cek/usercode-buyer";
    function showBuyerInput() {
        $("#showBuyerInput").hide();
        $("#buyer_name").show();
        $("#vendorPayBtn").html('Lanjutkan Pembayaran');
    }
    @endif

    $(document).ready(function(){
            $("#get_id").keyup(function(){
                $.ajax({
                    type: "GET",
                    url: "{{ URL::to('/') }}" + uri + "?name=" + $(this).val(),
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
    $("input[type=radio][name=product]").change(function() {
        if(this.checked) {
            setTimeout(function(){
                $("html, body").animate({ scrollTop: 2000 }, 1500);
            }, 800);

        }
    });

    function priceButton(id) {
        $("#" + id).prop("checked", true).trigger("click").change();
    }

    function confirmBuy() {
        var form = $('#form-add');
        Swal.fire('Sedang Memproses');
        Swal.showLoading();
        $(document.body).append(form);
        form.submit();
    }

        function checkOrder() {
            var no_hp = $("#nomor-pelanggan").val();
            var vendor_id = $("#id_get_id").val();
            var product = $('input[type=radio][name=product]:checked').attr('value');
            var isChecked = $('input[type=radio][name=product]').is(':checked');

            if(isChecked == false) {
                errorToast('Anda belum memilih nominal');
                return false;
            }
            if(vendor_id == '') {
                errorToast('Anda belum memilih vendor tujuan belanja');
                return false;
            }

            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/check-order?no_hp="+no_hp+"&product="+product+"&vendor_id="+vendor_id+"&type={{$type}}",
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
            var no_hp = $("#nomor-pelanggan").val();
            var product = $('input[type=radio][name=product]:checked').attr('value');
            var isChecked = $('input[type=radio][name=product]').is(':checked');
            var user_id = $("#id_get_id").val();
            if(isChecked == false) {
                errorToast('Anda belum memilih nominal');
                return false;
            }

            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/confirm-vendor-quickbuy?no_hp="+no_hp+"&product="+product+"&user_id="+user_id+"&type={{$type}}",
                success: function(url){
                    Swal.fire({
                        html: url,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                }
            });
        }

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

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
@stop
