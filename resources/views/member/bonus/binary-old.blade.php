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
                        <h4 class="page-title">Bonus Binary</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 card-box table-responsive">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
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
                                    <th>Tgl</th>
                                    <th>Jml. Pasangan</th>
                                    <th>Total Aktifasi</th>
                                    <th>Total Pasangan</th>
                                    <th>Index (Rp.)</th>
                                    <th>Jml Bonus (Rp.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                    <?php
                                        $no++;
                                    ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{date('d M Y', strtotime($row->bonus_date))}}</td>
                                        <td>{{$row->total_binary}}</td>
                                        <td>{{$row->total_activated}}</td>
                                        <td>{{$row->total_all_binary}}</td>
                                        <td>{{number_format(floor($row->bonus_index), 0, ',', '.')}}</td>
                                        <td>{{number_format(floor($row->bonus_price), 0, ',', '.')}}</td>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable();
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
        });
    } );
    
</script>
@stop