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
                                <button class="close" type="button" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }}
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped nowrap" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>User ID</th>
                                        <th>Type</th>
                                        <th>Kode</th>
                                        <th>Status</th>
                                        <th>EIDR/Bank</th>
                                        <th>TRON/Detail Bank</th>
                                        <th>Tgl Buat</th>
                                        <th>JML Deposit</th>
                                        <th>By Admin</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if($getAllTransaction != null)
                                    <?php
                                        $no = 0;
                                        ?>
                                    @foreach($getAllTransaction as $row)
                                    <?php
                                            $no++;
                                            $price = $row->price;
                                            $buy_metode = '-';
                                            if($row->buy_metode != null){
                                                $buy_metode = $row->buy_metode;
                                            }
                                            $typePay = 'Bank';
                                            if($row->is_tron == 1){
                                                $typePay = 'EIDR';
                                            }
                                            if($row->is_tron == 1 && $row->bank_perusahaan_id == 9){
                                                $typePay = 'eIDR Autoconfirm by TronWeb';
                                            }
                                            $type = 'Isi Deposit';
                                            if($row->type == 2){
                                                $type = 'Tarik Deposit';
                                            }
                                            $status = 'proses awal';
                                            $label = 'info';
                                            if($row->status == 1){
                                                $status = 'proses transfer';
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
                                            $name = $row->submit_name;
                                            if($row->submit_by == 1){
                                                $name = 'Master Admin';
                                            }
                                        ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->username}}</td>
                                        <td>{{$row->transaction_code}}</td>
                                        <td>{{$type}}</td>
                                        <td>
                                            <span class="badge badge-pill badge-{{$label}}">{{$status}}</span>
                                        </td>
                                        <td>{{$typePay}}</td>
                                        <td>{{$buy_metode}}</td>
                                        <td>{{date('d M Y', strtotime($row->created_at))}}</td>
                                        <td>{{number_format($price, 0, ',', ',')}}</td>
                                        <td>{{$name}}</td>
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