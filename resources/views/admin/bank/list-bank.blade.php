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
                                        <tr>
                                            <td>{{$getData->bank_name}}</td>
                                            <td>{{$getData->account_no}}</td>
                                            <td>{{$getData->account_name}}</td>
                                            <td><a rel="tooltip"  data-toggle="modal" data-target="#editBankPerusahaan" class="text-primary" href="{{ URL::to('/') }}/ajax/adm/bank">edit</a></td>
                                        </tr>
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
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
    $("#editBankPerusahaan").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
</script>
@stop