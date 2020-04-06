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
                        <h4 class="page-title">List Vendor Stock</h4>
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
                        <div class="m-b-20">
                            <a href="{{ URL::to('/') }}/m/purchase/input-vstock" class="btn btn-sm btn-custom waves-effect waves-light">
                                Pengajuan Input Stock
                            </a>
                        </div>
                        
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Stok Tersedia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                        @if($row->hapus == 0)
                                        <?php
                                            $no++;
                                        ?>
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$row->ukuran}} {{$row->name}}</td>
                                            <td>{{$row->code}}</td>
                                            <td>{{number_format($row->total_sisa, 0, ',', ',')}}</td>
                                        </tr>
                                        @endif
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