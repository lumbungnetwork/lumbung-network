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
                            <table class="table" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nominal (Rp.)</th>
                                        <th>###</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Rp. {{number_format($getData->start_price, 0, ',', '.')}}</td>
                                        <td>
                                            <a data-toggle="modal" data-target="#popUp" class="text-primary" style="cursor: pointer;">edit</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                             <div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form class="login100-form validate-form" method="post" action="/adm/bonus-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
                                            </div>
                                            <div class="modal-body">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Nominal</label>
                                                                <input type="number" class="form-control" name="start_price" value="{{$getData->start_price}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="modal-footer">
                                                <div class="left-side">
                                                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
                                                </div>
                                                <div class="divider"></div>
                                                <div class="right-side">
                                                    <button type="submit" class="btn btn-info btn-link">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
