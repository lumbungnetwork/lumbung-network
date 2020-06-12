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
                                        <th>No</th>
                                        <th>UserID</th>
                                        <th>HP</th>
                                        <th>Tanggal</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Royalti</th>
                                        <th>Status</th>
                                        <th>By Admin</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @if($getData != null)
                                        <?php 
                                        $no = 0; 
                                        ?>
                                        @foreach($getData as $row)
                                        <?php 
                                            $no++;
                                            $metode = 'eIDR';
                                            $detail = 'TWJtGQHBS88PfZTXvWAYhQEMrx36eX2F9Pc';
                                            if($row->buy_metode == 2){
                                                $metode = 'Transfer Bank';
                                                $detail = 'BRI 
                                                    <br>
                                                    a/n <b>PT LUMBUNG MOMENTUM BANGSA</b>
                                                    <br>
                                                    033601001795562';
                                            }
                                            $royalti = 4/100 * $row->price;
                                            $status = 'waiting';
                                            $label = 'primary';
                                            if($row->status == 2){
                                                $status = 'Approve';
                                                $label = 'success';
                                            }
                                            if($row->status == 10){
                                                $status = 'Reject';
                                                $label = 'danger';
                                            }
                                            if($row->submit_by != 1){
                                                $name = '--';
                                                if($row->status >= 2){
                                                    $name = $row->submit_name;
                                                }
                                            }
                                            
                                            if($row->submit_by == 1){
                                                $name = 'Master Admin';
                                            }
                                        ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$row->user_code}}</td>
                                                <td>{{$row->hp}}</td>
                                                <td>{{date('d F Y', strtotime($row->created_at))}}</td>
                                                <td>{{$metode}} <br> <?php echo $detail ?></td>
                                                <td>Rp. {{number_format($royalti, 0, ',', ',')}}</td>
                                                <td>
                                                    <span class="badge badge-pill badge-{{$label}}">{{$status}}</span>
                                                </td>
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
    $("#popUp").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    $("#popUp1").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    $(document).ready(function() {
        $('#myTable').DataTable( {
                dom: 'Bfrtip',
                "deferRender": true,
                columnDefs: [
                    { orderable: false, targets: -1 }
                 ],
                 buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'export_history_input_stock' ,
                   }
                ],
                searching: false,
                 pagingType: "full_numbers",
                 "paging":   true,
                 "info":     false,
                 "ordering": true
        } );
    } );
</script>
@stop