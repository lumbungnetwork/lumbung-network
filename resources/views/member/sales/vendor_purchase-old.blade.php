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
                                    <th>Tgl</th>
                                    <th>Jml. Harga</th>
                                    <th>Detail</th>
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
                                        $status = 'konfirmasi';
                                        $label = 'info';
                                    }
                                    if($row->status == 1){
                                        $status = 'proses admin';
                                        $label = 'info';
                                    }
                                    if($row->status == 2){
                                        $status = 'confirmed';
                                        $label = 'success';
                                    }
                                    ?>
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{date('d M Y', strtotime($row->created_at))}}</td>
                                    <td>{{number_format($row->price, 0, ',', ',')}}</td>
                                    <td>
                                        @foreach($row->detail_all as $rowAll)
                                            <p style="margin: 0;">{{$rowAll->ukuran}} {{$rowAll->name}}</p>
                                            <?php $harga = $rowAll->qty * $rowAll->price ?>
                                            <p style="margin: 0;">{{number_format($rowAll->qty, 0, ',', '')}}x @ {{number_format($rowAll->price, 0, ',', ',')}} </p>
                                            <p style="margin-bottom: 5px;"><b>Rp. {{number_format($harga, 0, ',', ',')}}</b></p>
                                        @endforeach
                                    </td>
                                    <td><label class="label label-{{$label}}">{{$status}}</label></td>
                                    <td>
                                        <a rel="tooltip" title="View" class="text-primary" href="{{ URL::to('/') }}/m/purchase/detail-vstock/{{$row->id}}">detail</a>
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