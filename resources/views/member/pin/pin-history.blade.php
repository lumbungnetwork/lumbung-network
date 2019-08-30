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
                                    <th>Total Pin</th>
                                    <th>Tanggal</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                <?php $no = 0; ?>
                                @foreach($getData as $row)
                                <?php
                                    $no++;
                                    if($row->pin_status == 0){
                                        $status = 'Beli Dari Admin';
                                        $label = 'success';
                                        if($row->transaction_code == null){
                                            $status = 'Transfer dari '.$row->name_transfer_from;
                                        }
                                    }
                                    if($row->pin_status == 1){
                                        $status = 'Aktifasi '.$row->name_activation;
                                        $label = 'warning';
                                    }
                                    if($row->pin_status == 2){
                                        $status = 'Transfer ke '.$row->name_transfer_to;;
                                        $label = 'warning';
                                    }
                                    ?>
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{$row->total_pin}}</td>
                                    <td>{{date('d-F-Y H:i', strtotime($row->created_at))}}</td>
                                    <td><label class="label label-{{$label}}">{{$status}}</label></td>
                                </tr>
                                @endforeach
                                @endif
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