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
                    <h6 class="mb-3">Konfirmasi Pembayaran</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            Pembeli: <strong>{{$getDataSales->username}}</strong>
                            <address>
                                @if($getDataSales->status == 1 || $getDataSales->status == 2)
                                @if($getDataSales->buy_metode == 1)
                                <br>
                                COD
                                @endif
                                @if($getDataSales->buy_metode == 2)
                                <br>
                                Nama Rekening: <strong>{{$getDataSales->account_name}}</strong>
                                <br>
                                Nama Bank: <strong>{{$getDataSales->bank_name}}</strong>
                                <br>
                                No. Rekening: <strong>{{$getDataSales->account_no}}</strong>
                                @endif
                                @if($getDataSales->buy_metode == 3)
                                <br>
                                Nama: <strong>{{$getDataSales->tron}}</strong>
                                <br>
                                Alamat Tron: <strong>{{$getDataSales->tron_transfer}}</strong>
                                @endif
                                @endif
                            </address>
                            <?php
                                $confirm = 'Konfirmasi Pembayaran';
                                $text = 'Konfirmasi';
                                $status = 'proses pembeli';
                                $label = 'info';
                                if($getDataSales->status == 1){
                                    $status = 'proses vendor';
                                    $label = 'warning';
                                }
                                if($getDataSales->status == 2){
                                    $status = 'TUNTAS';
                                    $label = 'success';
                                }
                                if($getDataSales->status == 10){
                                    $status = 'BATAL';
                                    $label = 'danger';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getDataSales->sale_date))}}
                            </p>
                            <p class="m-t-10"><strong>Order Status: </strong> <span
                                    class="label label-{{$label}}">{{$status}}</span></p>
                        </div>
                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead class="bg-faded">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getDataItem != null)
                                    <?php $no = 0; ?>
                                    @foreach($getDataItem as $row)
                                    <?php 
                                                $no++; 
                                            ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->ukuran}} {{$row->name}}</td>
                                        <td>{{number_format($row->amount, 0, ',', '')}}</td>
                                        <td>{{number_format($row->sale_price, 0, ',', '.')}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-3">
                            <p class="text-xs-right">Total pembayaran</p>
                            <h3 class="text-xs-right">Rp. {{number_format($getDataSales->sale_price, 0, ',', ',')}}</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 col-sm-6 col-xs-6"></div>
                        <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-9">
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    @if($getDataSales->status == 1)
                                    <input type="hidden" value="{{$getDataSales->id}}" name="id_master" id="id_master">
                                    <button type="submit" class="btn btn-danger" id="submitBtn" data-toggle="modal"
                                        data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" data-toggle="modal"
                                        data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                                    @else
                                    @if($getDataSales->status == 0)
                                    <input type="hidden" value="{{$getDataSales->id}}" name="id_master" id="id_master">
                                    <button type="submit" class="btn btn-danger" id="submitBtn" data-toggle="modal"
                                        data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                    @endif
                                    <a class="btn btn-info" href="{{ URL::to('/') }}/m/vendor-report">Kembali</a>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    @if($getDataSales->status == 1)
                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="confirmDetail">
                        </div>
                    </div>
                    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="rejectDetail">
                        </div>
                    </div>
                    @endif
                    @if($getDataSales->status == 0)
                    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="rejectDetail">
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
    <link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
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
    <script src="{{ asset('asset_member/js/jquery.cart.min.js') }}"></script>
    @if($getDataSales->status == 1)
    <script>
        function inputSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/confirm-vpembelian?id_master="+id_master,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }
           
           function rejectSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-vpembelian?id_master="+id_master,
                     success: function(url){
                         $("#rejectDetail" ).empty();
                         $("#rejectDetail").html(url);
                     }
                 });
           }

            function confirmSubmit(){
                var dataInput = $("#form-add").serializeArray();
                $('#form-add').submit();
                $('#tutupModal').remove();
                $('#submit').remove();
            }
    </script>
    @endif

    @if($getDataSales->status == 0)
    <script>
        function rejectSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-vpembelian?id_master="+id_master,
                     success: function(url){
                         $("#rejectDetail" ).empty();
                         $("#rejectDetail").html(url);
                     }
                 });
           }

            function confirmSubmit(){
                var dataInput = $("#form-add").serializeArray();
                $('#form-add').submit();
                $('#tutupModal').remove();
                $('#submit').remove();
            }
    </script>
    @endif


    @stop