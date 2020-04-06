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
                        <h4 class="page-title">eIDR History</h4>
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
                                    <th>Top Up (Rp.)</th>
                                    <th>Status</th>
                                    <th>Tgl</th>
                                    <th>###</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                    <?php
                                        $no++;
                                        $status = 'Proses Transfer';
                                        $label = 'info';
                                        if($row->status == 1){
                                            $status = 'Konfirmasi Admin';
                                            $label = 'warning';
                                        }
                                        if($row->status == 2){
                                            $status = 'Tuntas';
                                            $label = 'success';
                                        }
                                        if($row->status == 3){
                                            $status = 'Reject';
                                            $label = 'danger';
                                        }
                                    ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{number_format($row->nominal, 0, ',', '.')}}</td>
                                        <td>
                                                <span class="label label-{{$label}}">{{$status}}</span>
                                        </td>
                                        <td>{{date('d F Y', strtotime($row->created_at))}}</td>
                                         <td>
                                            <a class="text-primary" href="{{ URL::to('/') }}/m/topup/pembayaran/{{$row->id}}">detail</a>
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
@stop
@section('javascript')
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable();
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
        });
    } );
    
</script>
@stop