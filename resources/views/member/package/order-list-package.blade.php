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
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
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
                                    <th>Nama</th>
                                    <th>Handphone</th>
                                    <th>Jenis Paket</th>
                                    <th>###</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 0;
                                ?>
                                @foreach($allPackage as $row)
                                <?php 
                                    $no++;
                                ?>
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{$row->name_user}}</td>
                                    <td>{{$row->hp}}</td>
                                    <td>{{$row->name}}</td>
                                    <td class="td-actions text-left" >
                                        <a class="text-primary" href="{{ URL::to('/') }}/m/detail/order-package/{{$row->id}}">detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
<link href="{{ asset('asset_member/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/asset_member/plugins/datatables/responsive.bootstrap4.min.js"></script>
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
    
        table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    } );
    
</script>
@stop