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
                        <h4 class="page-title">Input Stock</h4>
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
                                    <h3>Request Input Stock</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">

                                    <?php
                                        $status = 'batal';
                                        $label = 'danger';
                                        if($getDataMaster->status == 0){
                                            $status = 'konfirmasi';
                                            $label = 'info';
                                        }
                                        if($getDataMaster->status == 1){
                                            $status = 'proses admin';
                                            $label = 'info';
                                        }
                                        if($getDataMaster->status == 2){
                                            $status = 'confirmed';
                                            $label = 'success';
                                        }
                                    ?>
                                    <div class="pull-xs-right m-t-30">
                                        <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getDataMaster->created_at))}}</p>
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
                                                            <td>{{number_format($row->qty, 0, ',', '')}}</td>
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
                            </div>
                            <hr>
                            @if($getDataMaster->status == 0)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <input type="hidden" value="{{$getDataMaster->id}}" name="id_master" id="id_master">
                                    <button type="submit" class="btn btn-danger"  id="submitBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                    <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endif
                            @if($getDataMaster->status == 1 || $getDataMaster->status == 2 || $getDataMaster->status == 3)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <a  class="btn btn-success" href="{{ URL::to('/') }}/m/purchase/list-stock">Kembali</a>
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
@if($getDataMaster->status == 0)
    <script>
           function inputSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add-stock?id_master="+id_master,
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
                     url: "{{ URL::to('/') }}/m/cek/reject-stock?id_master="+id_master,
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