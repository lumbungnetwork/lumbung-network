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
                        <h4 class="page-title">Status Member</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @if($dataOrder > 0)
                        <div class="alert alert-warning" role="alert">
                            <b> Permintaan order paket (Total {{$dataOrder}}) - </b> <a href="{{ URL::to('/') }}/m/list/order-package" class="label label-primary">link</a>
                        </div>
                    @endif
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
                                    <th>Email</th>
                                    <th>No. HP</th>
                                    <th>Paket</th>
                                    <th>tgl. Aktif</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                    <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                        <?php
                                            $no++;
                                            $status = 'Aktif';
                                            $label = 'success';
                                            $active_at = date('d M Y', strtotime($row->active_at));
                                            if($row->is_active == 0){
                                                $active_at = '--';
                                                $status = 'Belum Diaktifasi';
                                                $label = 'warning';
                                                if($row->package_id == null){
                                                    $status = 'Belum Pilih Paket';
                                                    $label = 'danger';
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>{{$row->email}}</td>
                                            <td>{{$row->hp}}</td>
                                            <td>{{$row->paket_name}}</td>
                                            <td>{{$active_at}}</td>
                                            <td><label class="label label-{{$label}}">{{$status}}</label></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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