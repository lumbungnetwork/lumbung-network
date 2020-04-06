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
                        <h4 class="page-title">Report Vendor</h4>
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
                                <th>Tanggal</th>
                                <th>UserID</th>
                                <th>Nominal Belanja (Rp.)</th>
                                <th>Pembayaran</th>
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
                                        $status = 'proses pembeli';
                                        $label = 'info';
                                        if($row->status == 1){
                                            $status = 'proses vendor';
                                            $label = 'warning';
                                        }
                                        if($row->status == 2){
                                            $status = 'tuntas';
                                            $label = 'success';
                                        }
                                        if($row->status == 10){
                                            $status = 'batal';
                                            $label = 'danger';
                                        }
                                        $buy = 'proses pemilihan';
                                        if($row->buy_metode == 1){
                                            $buy = 'COD';
                                        }
                                        if($row->buy_metode == 2){
                                            $buy = 'Transfer Bank';
                                        }
                                        if($row->buy_metode == 3){
                                            $buy = 'eIDR';
                                        }
                                    ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{date('d-m-Y', strtotime($row->sale_date))}}</td>
                                        <td>{{$row->user_code}}</td>
                                        <td>{{number_format($row->sale_price, 0, ',', ',')}}</td>
                                        <td>
                                                <span class="label label-info">{{$buy}}</span>
                                        </td>
                                        <td>
                                                <span class="label label-{{$label}}">{{$status}}</span>
                                        </td>
                                        <td>
                                            <a class="label label-primary" href="{{ URL::to('/') }}/m/detail/vendor-report/{{$row->id}}">detail</a>
                                        </td>
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
            lengthChange: false
        });
    } );
    
</script>
@stop