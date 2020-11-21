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
                        <h5 class="card-title">List Vendor Stock {{$getStockist->user_code}}</h5>
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
                                        <th>No.</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Stok Tersedia</th>
                                        <!--<th>###</th>-->
                                    </tr>
                                </thead>

                                <tbody>
                                    @if($getData != null)
                                    <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                    @if($row->hapus == 0)
                                    @if($row->total_sisa > 0)
                                    <?php
                                                    $no++;
                                                ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->ukuran}} {{$row->name}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>{{number_format($row->total_sisa, 0, ',', ',')}}</td>
                                        <!--                                                <td>
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp1" class="text-info" href="{{ URL::to('/') }}/ajax/adm/edit-vstock/{{$getStockist->id}}/{{$row->purchase_id}}">edit</a>
                                                        @if($row->total_sisa <= 0)
                                                            &nbsp;&nbsp;
                                                            <a rel="tooltip"  data-toggle="modal" data-target="#popUp2" class="text-danger" href="{{ URL::to('/') }}/ajax/adm/rm-vstock/{{$getStockist->id}}/{{$row->purchase_id}}">hapus</a>
                                                        @endif
                                                    </td>-->
                                    </tr>
                                    @endif
                                    @endif
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
<div class="modal fade" id="popUp1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-backdrop="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade" id="popUp2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-backdrop="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>
@stop




@section('javascript')

<script type="text/javascript">
    $("#popUp1").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    $("#popUp2").on("show.bs.modal", function(e) {
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
                searching: false,
                 pagingType: "full_numbers",
                 "paging":   true,
                 "info":     false,
                 "ordering": true
        } );
    } );
</script>
@stop
