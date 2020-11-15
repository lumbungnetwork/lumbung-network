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
            <div class="col-12">
                <div class="col-6">
                    <div class="card card-user">
                        <div class="card-body" style="min-height: auto;">

                                <form class="login100-form validate-form" method="post" action="/adm/search-list/member">
                                    {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="selector" id="inlineRadio1" value="1" checked>
                                                        <label class="form-check-label" for="inlineRadio1">Name</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="selector" id="inlineRadio2" value="2">
                                                        <label class="form-check-label" for="inlineRadio2">Tron Address</label>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="input" placeholder="Minimal 3 karakter">
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <button type="submit" class="form-control btn btn-sm btn-info " title="cari" style="margin: 0;">Cari</button>
                                                    </div>
                                                </div>


                                            </div>
                                </form>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card card-user">
                        <div class="card-body">

                                <form class="login100-form validate-form" method="post" action="/adm/search-list/member-by-month">
                                    {{ csrf_field() }}
                                    <div class="col-xl-12 col-xs-12">
                                    <fieldset>
                                        <select class="form-control" name="month" id="bank_name">
                                            <option value="none">- Bulan -</option>
                                            <option value="01">Januari</option>
                                            <option value="02">Februari</option>
                                            <option value="03">Maret</option>
                                            <option value="04">April</option>
                                            <option value="05">Mei</option>
                                            <option value="06">Juni</option>
                                            <option value="07">Juli</option>
                                            <option value="08">Agustus</option>
                                            <option value="09">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-12 col-xs-12">
                                    <fieldset>
                                        <label>&nbsp;</label>
                                        <select class="form-control" name="year" id="bank_name">
                                            <option value="none">- Tahun -</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-12  col-xs-12">
                                    <fieldset>
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control btn btn-success">Cari</button>
                                    </fieldset>
                                </div>
                                </form>
                        </div>
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
                                        <th>Tron</th>
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
                                                $active_at = $row->active_at;
                                                if($row->pin_activate_at != null){
                                                    $active_at = $row->pin_activate_at;
                                                }
                                                if(strtotime(date('Y-m-d')) >= strtotime('+365 days', strtotime($active_at))){
                                                    $active= 'expired';
                                                    $label = 'danger';
                                                }
                                            }
                                            $tron = '';
                                            if($row->is_tron == 1){
                                                $tron = $row->tron;
                                            }
                                        ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$row->user_code}}</td>
                                                <td>{{$row->hp}}</td>
                                                <td>{{$sp}}</td>
                                                <td><span class="badge badge-pill badge-{{$label}}">{{$active}}</span></td>
                                                <td>{{date('d F Y', strtotime($active_at))}}</td>
                                                <td>{{date('d F Y', strtotime('+365 days', strtotime($active_at)))}}</td>
                                                <td>{{$tron}}</td>
                                                <td class="td-actions text-left" >
                                                    <div class="table-icons">
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp"  href="{{ URL::to('/') }}/ajax/adm/change-passwd/member/{{$row->id}}" class="text-warning">passwd</a>
                                                        &nbsp;&nbsp;
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp4"  href="{{ URL::to('/') }}/ajax/adm/change-2fa/member/{{$row->id}}" class="text-warning">2fa</a>
                                                        &nbsp;&nbsp;
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp1"  href="{{ URL::to('/') }}/ajax/adm/change-data/member/{{$row->id}}" class="text-primary">data</a>
                                                        @if($row->is_tron == 1)
                                                        &nbsp;&nbsp;
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp2"  href="{{ URL::to('/') }}/ajax/adm/change-tron/member/{{$row->id}}" class="text-help">tron</a>
                                                        @endif
                                                        &nbsp;&nbsp;
                                                        <a rel="tooltip"  data-toggle="modal" data-target="#popUp3"  href="{{ URL::to('/') }}/ajax/adm/change-block/member/{{$row->id}}" class="text-danger">blokir</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                             <div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                             <div class="modal fade" id="popUp1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                             <div class="modal fade" id="popUp2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                             <div class="modal fade" id="popUp3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                             <div class="modal fade" id="popUp4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
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
    $("#popUp3").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    $("#popUp4").on("show.bs.modal", function(e) {
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
                   },
                   {
                        extend: 'copyHtml5',
                        text: 'Copy All',
                        header: false,

                        exportOptions: {

                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                   },
                   {
                        extend: 'copyHtml5',
                        text: 'Copy Laporan',
                        header: false,

                        exportOptions: {

                            columns: [2, 4, 6]
                        }
                   },
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
