@extends('layout.member.main')
@section('content')
@include('layout.member.topbar')
@include('layout.member.sidebar')

<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php
                        $confirm = 'Konfirmasi Pembayaran';
                        $text = 'Konfirmasi';
                        $status = 'proses pembeli';
                        $label = 'info';
                        if($getDataSales->status == 1){
                            $status = 'proses stockist';
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
                    <div class="page-title-box">
                        <h4 class="page-title">Konfirmasi</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="panel-body">
                            <div class="clearfix">
                                <div style="text-align: center;">
                                    <h3>Konfirmasi Pembayaran</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pull-xs-left m-t-30">
                                        Pembeli: <strong>{{$getDataSales->user_code}}</strong>
                                        <address style="width: 420px; word-wrap: break-word;">
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
                                    </div>
                                    <div class="pull-xs-right m-t-30">
                                        <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getDataSales->sale_date))}}</p>
                                        <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-{{$label}}">{{$status}}</span></p>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->

                            <div class="m-h-50"></div>

                            <div class="row">
                                <div class="col-xs-12">
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
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-3">
                                    <p class="text-xs-right">Total pembayaran</p>
                                    <h3 class="text-xs-right">Rp. {{number_format($getDataSales->sale_price, 0, ',', ',')}}</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    @if($getDataSales->status == 1)
                                        <input type="hidden" value="{{$getDataSales->id}}" name="id_master" id="id_master">
                                        <button type="submit" class="btn btn-danger"  id="submitBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                        <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                                    @else 
                                        @if($getDataSales->status == 0)
                                            <input type="hidden" value="{{$getDataSales->id}}" name="id_master" id="id_master">
                                            <button type="submit" class="btn btn-danger"  id="submitBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                        @endif
                                        <a  class="btn btn-info" href="{{ URL::to('/') }}/m/stockist-report">Kembali</a>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        @if($getDataSales->status == 1)
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                        <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="rejectDetail">
                            </div>
                        </div>
                        @endif
                        @if($getDataSales->status == 0)
                            <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document" id="rejectDetail">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.member.footer')
@stop

@if($getDataSales->status == 1)
@section('javascript')
    <script>
           function inputSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/confirm-pembelian?id_master="+id_master,
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
                     url: "{{ URL::to('/') }}/m/cek/reject-pembelian?id_master="+id_master,
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
@stop
@endif

@if($getDataSales->status == 0)
@section('javascript')
    <script>
          
           function rejectSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-pembelian?id_master="+id_master,
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
@stop
@endif