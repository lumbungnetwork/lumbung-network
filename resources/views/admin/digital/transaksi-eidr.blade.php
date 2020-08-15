@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')
<div class="main-panel">
    
    <?php //MENU HEADER  ?>
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <p class="navbar-brand">{{$headerTitle}}</p>
            </div>
        </div>
    </nav>
    
    <?php //MENU CONTENT  ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">List</h5>
                    </div>
                    <div class="card-body">
                        @if ( Session::has('message') )
                            <div class="widget-content mt10 mb10 mr15">
                                <div class="alert alert-{{ Session::get('messageclass') }}">
                                    <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                    {{  Session::get('message')    }} 
                                </div>
                            </div>
                        @endif
                         <div class="table-responsive">
                            <table class="table table-striped nowrap" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Tgl</th>
                                        <th>Pembeli</th>
                                        <th>Vendor</th>
                                        <th>Nominal (Rp.)</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th>Type</th>
                                        <th>###</th>
                                        <th>Cek Transaksi</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @if($getData != null)
                                        <?php $no = 0; ?>
                                        @foreach($getData as $row)
                                            <?php
                                                $no++;
                                                $status = 'konfirmasi pembayaran';
                                                $label = 'info';
                                                if($row->status == 1){
                                                    $status = 'proses admin';
                                                    $label = 'warning';
                                                }
                                                if($row->status == 2){
                                                    $status = 'tuntas';
                                                    $label = 'success';
                                                }
                                                if($row->status == 3){
                                                    $status = 'batal';
                                                    $label = 'danger';
                                                }
                                                if($row->status == 10){
                                                    $status = 'batal';
                                                    $label = 'danger';
                                                }
                                                $buy = 'eIDR';
                                                $type = 'Pulsa';
                                                if($row->type == 2){
                                                    $type = 'Paket Data';
                                                }
                                                if($row->type == 3){
                                                    $type = 'PLN';
                                                }
                                                if($row->type > 3 && $row->type < 8){
                                                    $type = $row->message;
                                                }
                                                if($row->type == 8){
                                                    $type = 'OVO';
                                                }
                                            ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{date('d-m-Y H:i', strtotime($row->created_at))}}</td>
                                                <td>{{$row->user_code_pembeli}}</td>
                                                <td>{{$row->user_code_vendor}}</td>
                                                <td>{{number_format($row->ppob_price, 0, ',', ',')}}</td>
                                                <td>
                                                        <span class="label label-{{$label}}">{{$status}}</span>
                                                </td>
                                                <td>
                                                        <span class="label label-info">{{$buy}}</span>
                                                </td>
                                                <td>{{$type}}</td>
                                                <td>
                                                    <a class="label label-primary" href="{{ URL::to('/') }}/adm/ppob-transaction/eidr/{{$row->id}}">detail</a>
                                                </td>
                                                <td>
                                                    @if($row->status == 2)
                                                        <a class="label label-warning" href="{{ URL::to('/') }}/adm/cek-status/ppob-transaction/{{$row->id}}" title="cek disini untuk memeriksa status transaksi">cek</a>
                                                    @endif
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
    </div>
</div>
@stop


@section('styles')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
@stop

@section('javascript')
<script type="text/javascript" language="javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/buttons.html5.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var myTableRow =  $('#myTable').DataTable( {
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                dom: 'Bfrtip',
                "deferRender": true,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'export_xls' ,
                   }
                ],
                searching: false,
                 pagingType: "full_numbers",
                 "paging":   true,
                 "info":     false,
                 "ordering": true,
        } );
        $('#myTable #example-select-all').change(function() {
                var checked = $(this).is(":checked");
                $("input", myTableRow.rows({search:'applied'}).nodes()).each(function(){
                        if(checked){
                                $(this).attr("checked", true);
                        }
                        else {
                                $(this).attr("checked", false);
                        }
                });
        });
        $("form").submit(function() {
                $(myTableRow.rows({search:'applied'}).nodes()).find('input[type="checkbox"]:checked');
        });
    } );
</script>
@stop