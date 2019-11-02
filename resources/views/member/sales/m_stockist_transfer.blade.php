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
                        $confirm = 'Transfer Royati';
                        $text = 'Transfer';
                        if($getDataSales->status == 1){
                            $confirm = 'Konfirmasi Penjualan';
                            $text = 'Konfirmasi';
                        }
                    ?>
                    <div class="page-title-box">
                        <h4 class="page-title">Stockist {{$confirm}}</h4>
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
                                    <h3>{{$text}}</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pull-xs-left m-t-30">
                                        Pembeli: <strong>{{$getDataSales->user_code}}</strong>
                                        <address>
                                            @if($getDataSales->status == 1)
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
                                            @if($getDataSales->status == 3)
                                                @if($getDataSales->royalti_metode == 1)
                                                    <br>
                                                    Nama Rekening: <strong>{{$getDataSales->royalti_account_name}}</strong>
                                                    <br>
                                                    Nama Bank: <strong>{{$getDataSales->royalti_bank_name}}</strong>
                                                    <br>
                                                    No. Rekening: <strong>{{$getDataSales->royalti_account_no}}</strong>
                                                @endif
                                                @if($getDataSales->royalti_metode == 2)
                                                    <br>
                                                    Nama: <strong>{{$getDataSales->royalti_tron}}</strong>
                                                    <br>
                                                    Alamat Tron: <strong>{{$getDataSales->royalti_tron_transfer}}</strong>
                                                @endif
                                                @if($getDataSales->royalti_metode == 0)
                                                <div class="radio radio-primary">
                                                    <input type="radio" name="radio" id="radio1" value="1">
                                                    <label for="radio1">
                                                        BRI a/n <b>PT LUMBUNG MOMENTUM BANGSA</b>
                                                        <br>
                                                        033601001795562
                                                    </label>
                                                </div>
                                                <div class="radio radio-primary">
                                                    <input type="radio" name="radio" id="radio2" value="2">
                                                    <label for="radio2">
                                                        eIDR
                                                        <br>
                                                        <b>TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ</b>
                                                    </label>
                                                </div>
                                                @endif
                                            @endif
                                            
                                        </address>
                                    </div>
                                    <div class="pull-xs-right m-t-30">
                                        <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getDataSales->sale_date))}}</p>
                                        <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-info">{{$confirm}}</span></p>
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
                                                    <th>Nama</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($getDataItem != null)
                                                    <?php $no = 0; ?>
                                                    @foreach($getDataItem as $row)
                                                        <?php $no++; ?>
                                                        <tr>
                                                            <td>{{$no}}</td>
                                                            <td>{{$row->ukuran}} {{$row->name}}</td>
                                                            <td>{{number_format($row->amount, 0, ',', '')}}</td>
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
                                    @if($getDataSales->status == 1)
                                        <p class="text-xs-right">Total transfer</p>
                                        <h3 class="text-xs-right">Rp. {{number_format($getDataSales->sale_price, 0, ',', ',')}}</h3>
                                    @endif
                                    @if($getDataSales->status == 3)
                                    <p class="text-xs-right"><b>Total Belanja:</b> Rp. {{number_format($getDataSales->sale_price, 0, ',', ',')}}</p>
                                    <?php
                                        $harga_dasar = ($getDataSales->sale_price * 10) / 11;
                                        $royalti = 8/100 * $harga_dasar;
                                    ?>
                                    <p class="text-xs-right"><b>Total harga Dasar:</b>Rp. {{number_format($harga_dasar, 0, ',', ',')}}</p>
                                    <p class="text-xs-right"><b>Total Royalti:</b>Rp. {{number_format($royalti, 0, ',', ',')}}</p>
                                    <hr>
                                    <p class="text-xs-right">Total yang harus ditransfer</p>
                                    <h3 class="text-xs-right">Rp. {{number_format($royalti, 0, ',', ',')}}</h3>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <input type="hidden" value="{{$getDataSales->id}}" name="id_master" id="id_master">
                                    <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.member.footer')
@stop

@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
    @if($getDataSales->status == 1)
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

            function confirmSubmit(){
                var dataInput = $("#form-add").serializeArray();
                $('#form-add').submit();
                $('#tutupModal').remove();
                $('#submit').remove();
            }
    </script>
    @endif
    
    @if($getDataSales->status == 3)
    <script>
           function inputSubmit(){
                var id_master = $("#id_master").val();
                var metode = $('input[name=radio]:checked').val(); 
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add-royalti?id_master="+id_master+"&metode="+metode,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
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