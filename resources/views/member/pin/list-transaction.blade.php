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
                        <h4 class="page-title">List Transaksi</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 card-box table-responsive">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tgl</th>
                                    <th>Jml Pin</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>###</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                <?php $no = 0; ?>
                                @foreach($getData as $row)
                                <?php
                                    $no++;
                                    $status = 'batal';
                                    $label = 'danger';
                                    if($row->status == 0){
                                        $status = 'proses transfer';
                                        $label = 'info';
                                    }
                                    if($row->status == 1){
                                        $status = 'menunggu konfirmasi';
                                        $label = 'info';
                                    }
                                    if($row->status == 2){
                                        $status = 'tuntas';
                                        $label = 'success';
                                    }
                                    ?>
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{$row->transaction_code}}</td>
                                    <td>{{date('d F Y', strtotime($row->created_at))}}</td>
                                    <td>{{$row->total_pin}}</td>
                                    <td>{{number_format($row->price, 0, ',', ',')}}</td>
                                    <td><label class="label label-{{$label}}">{{$status}}</label></td>
                                    <td>
                                        <a rel="tooltip" title="View" class="text-primary" href="{{ URL::to('/') }}/m/add/transaction/{{$row->id}}">detail</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop
@section('styles')
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!--<link href="{{ asset('asset_member/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />-->
@stop
@section('javascript')
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!--<script src="/asset_member/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/asset_member/plugins/datatables/responsive.bootstrap4.min.js"></script>-->
<script>
    function inputSubmit(){
        var total_pin = $("#input_jml_pin").val();
         $.ajax({
             type: "GET",
             url: "{{ URL::to('/') }}/m/cek/add-pin?total_pin="+total_pin,
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
    
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable();
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
        });
    } );
    
</script>
@stop