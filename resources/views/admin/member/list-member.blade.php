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
                <div class="card card-user">
                    <div class="card-header">
                    </div>
                    <div class="card-body" style="min-height: auto;">
                        <form class="login100-form validate-form" method="post" action="/adm/search-list/member">
                            {{ csrf_field() }}
                            <div id="addPenjualan">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Relevant Search</label>
                                            <input type="text" class="form-control" name="name" placeholder="Minimal 3 karakter">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                             <button type="submit" class="form-control btn btn-sm btn-info " title="cari" style="margin: 0;">Cari</button>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </form>
                    </div>
                </div>
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
                                        <th>No. HP</th>
                                        <th>Sponsor</th>
                                        <th>Status</th>
                                        <th>Tgl. Aktif</th>
                                        <th>Tgl. Expired</th>
                                        <th>###</th>
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
                                            $sp = 'Top 001';
                                            if($row->sp_name != null){
                                                $sp = $row->sp_name;
                                            }
                                            $active = 'non-aktif';
                                            $label = 'danger';
                                            if($row->is_active == 1){
                                                $active= 'aktif';
                                                $label = 'success';
                                                if(strtotime(date('Y-m-d')) > strtotime('+365 days', strtotime($row->active_at))){
                                                    $active= 'expired';
                                                    $label = 'danger';
                                                }
                                            }
                                        ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$row->user_code}}</td>
                                                <td>{{$row->hp}}</td>
                                                <td>{{$sp}}</td>
                                                <td><span class="badge badge-pill badge-{{$label}}">{{$active}}</span></td>
                                                <td>{{date('d F Y', strtotime($row->active_at))}}</td>
                                                <td>{{date('d F Y', strtotime('+365 days', strtotime($row->active_at)))}}</td>
                                                <td class="td-actions text-left" >
                                                    <div class="table-icons">
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp"  href="{{ URL::to('/') }}/ajax/adm/change-passwd/member/{{$row->id}}" class="text-warning">passwd</a>
                                                        &nbsp;&nbsp;
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp1"  href="{{ URL::to('/') }}/ajax/adm/change-data/member/{{$row->id}}" class="text-primary">data</a>
<!--                                                        &nbsp;&nbsp;
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp2"  href="{{ URL::to('/') }}/ajax/adm/change-block/member/{{$row->id}}" class="text-danger">blokir</a>-->
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                             <div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                             <div class="modal fade" id="popUp1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                             <div class="modal fade" id="popUp2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
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
                 "ordering": true
        } );
    } );
</script>
@stop