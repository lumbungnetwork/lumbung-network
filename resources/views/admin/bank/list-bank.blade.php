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
                        <h5 class="card-title">List Bank</h5>
                        <a class="btn btn-info btn-fill btn-sm" href="{{ URL::to('/') }}/adm/add/bank">Tambah Bank</a>
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
                         <div>
                            <table class="table" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>Nama Bank</th>
                                        <th>Nama Rekening</th>
                                        <th>No. Rekening</th>
                                        <th>###</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @if($getData != null)
                                        @foreach($getData as $row)
                                            <tr>
                                                <td>{{$row->bank_name}}</td>
                                                <td>{{$row->account_name}}</td>
                                                <td>{{$row->account_no}}</td>
                                                <td><a rel="tooltip"  data-toggle="modal" data-target="#editBankPerusahaan" class="text-primary" href="{{ URL::to('/') }}/ajax/adm/bank/{{$row->id}}">edit</a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                             <div class="modal fade" id="editBankPerusahaan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">List Tron</h5>
                        <a class="btn btn-info btn-fill btn-sm" href="{{ URL::to('/') }}/adm/add/tron">Tambah Tron</a>
                    </div>
                    <div class="card-body">
                         <div>
                            <table class="table" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Alamat Tron</th>
                                        <th>###</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @if($getDataTron != null)
                                        @foreach($getDataTron as $row)
                                            <tr>
                                                <td>{{$row->tron_name}}</td>
                                                <td>{{$row->tron}}</td>
                                                <td><a rel="tooltip"  data-toggle="modal" data-target="#editTronPerusahaan" class="text-primary" href="{{ URL::to('/') }}/ajax/adm/tron/{{$row->id}}">edit</a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                             <div class="modal fade" id="editTronPerusahaan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

@section('javascript')
<script type="text/javascript">
    $("#editBankPerusahaan").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    
    $("#editTronPerusahaan").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
</script>
@stop