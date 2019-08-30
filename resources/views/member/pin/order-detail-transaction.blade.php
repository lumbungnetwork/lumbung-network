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
                        <h4 class="page-title">Invoice</h4>
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
                                            <div class="pull-left">
                                                <h5>Invoice # <br>
                                                    <small>{{$getData->transaction_code}}</small>
                                                </h5>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="pull-xs-left m-t-30">
                                                    <address>
                                                      <strong>Transfer Ke:</strong>
                                                      <br>
                                                      Nama Rekening : <strong>{{$bankPerusahaan->account_name}}</strong>
                                                      <br>
                                                      Nama Bank: <strong>{{$bankPerusahaan->bank_name}}</strong>
                                                      <br>
                                                      No. Rekening: <strong>{{$bankPerusahaan->account_no}}</strong>
                                                      </address>
                                                </div>
                                                <?php
                                                    $status = 'Tuntas';
                                                    $label = 'success';
                                                    if($getData->status == 0){
                                                        $status = 'Proses Transfer';
                                                        $label = 'danger';
                                                    }
                                                    if($getData->status == 1){
                                                        $status = 'Menunggu Konfirmasi';
                                                        $label = 'warning';
                                                    }
                                                    if($getData->status == 3){
                                                        $status = 'Batal';
                                                        $label = 'danger';
                                                    }
                                                ?>
                                                <div class="pull-xs-right m-t-30">
                                                    <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getData->created_at))}}</p>
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
                                                                <th>#</th>
                                                            <th>Jumlah Pin</th>
                                                            <th>Harga (Rp.)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{$getData->total_pin}}</td>
                                                                <td>{{number_format($getData->price, 0, ',', ',')}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                
                                            </div>
                                            <?php
                                                $total = $getData->price + $getData->unique_digit;
                                            ?>
                                            <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-3">
                                                <p class="text-xs-right"><b>Sub-total:</b> Rp. {{number_format($getData->price, 0, ',', ',')}}</p>
                                                <p class="text-xs-right"><b>Kode Unik:</b>: {{number_format($getData->unique_digit, 0, ',', ',')}}</p>
                                                <hr>
                                                <h3 class="text-xs-right">Rp. {{number_format($total, 0, ',', ',')}}</h3>
                                            </div>
                                        </div>
                                        <hr>
                                        @if($getData->status == 0)
                                        <div class="hidden-print">
                                            <div class="pull-xs-right">
                                                <input type="hidden" value="{{$getData->id}}" name="id_trans" id="id_trans">
                                                <button type="submit" class="btn btn-danger"  id="submitBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                                <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        @endif
                                    </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                        <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="rejectDetail">
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
@if($getData->status == 0)
    <script>
           function inputSubmit(){
                var id_trans = $("#id_trans").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add-transaction?id_trans="+id_trans,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }
           
           function rejectSubmit(){
                var id_trans = $("#id_trans").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-transaction?id_trans="+id_trans,
                     success: function(url){
                         $("#rejectDetail" ).empty();
                         $("#rejectDetail").html(url);
                     }
                 });
           }

            function confirmSubmit(){
                var dataInput = $("#form-add").serializeArray();
                $('#form-add').submit();
            }
    </script>
@endif
@stop