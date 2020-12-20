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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">List</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled team-members">
                            @if($getAllAdmin != null)
                            @foreach($getAllAdmin as $row)
                            <?php
                                $type = 'Master Admin';
                                if($row->user_type == 3){
                                    $type = 'Admin';
                                }
                                ?>
                            <li>
                                <div class="row">
                                    <div class="col-md-2 col-2">
                                        <div class="avatar">
                                            <i class="nc-icon nc-circle-10" style="  font-size: 26px;"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-7">
                                        {{$row->name}}
                                        <br>
                                        <span class="text-muted">
                                            <small>{{$type}}</small>
                                        </span>
                                    </div>
                                    @if($row->id > 2)
                                    <div class="col-md-3 col-3">
                                        <div class="table-icons">
                                            <a rel="tooltip" data-toggle="modal" data-target="#editAdmin"
                                                class="text-primary"
                                                href="{{ URL::to('/') }}/ajax/adm/admin/1/{{$row->id}}"><i
                                                    class="nc-icon nc-badge"></i></a>
                                            &nbsp;
                                            <a rel="tooltip" data-toggle="modal" data-target="#rmAdmin"
                                                class="text-danger"
                                                href="{{ URL::to('/') }}/ajax/adm/admin/2/{{$row->id}}"><i
                                                    class="nc-icon nc-simple-remove"></i></a>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-3 col-3">
                                        <div class="table-icons">
                                            <a rel="tooltip" data-toggle="modal" data-target="#editAdmin"
                                                class="text-primary"
                                                href="{{ URL::to('/') }}/ajax/adm/admin/1/{{$row->id}}"><i
                                                    class="nc-icon nc-badge"></i></a>

                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                            <div class="modal fade" id="editAdmin" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                            <div class="modal fade" id="rmAdmin" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-user">
                    <div class="card-header">
                        <h5 class="card-title">Create</h5>
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
                        <form class="login100-form validate-form" method="post" action="/adm/new-admin">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" name="email" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control" name="user_type" id="user_type">
                                            <option value="3">Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" name="f_name" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label>Re-type Password</label>
                                        <input type="password" class="form-control" name="repassword" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="role_form">
                                <div class="col-md-12">
                                    <label>Privileges</label>
                                    <div class="form-group" style="border: 1px #ccc solid;padding: 10px;">
                                        <div class="col-sm-12 checkbox-radios">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="role[]"
                                                        value="2">
                                                    <span class="form-check-sign"></span>
                                                    Withdrawal Menu
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="role[]"
                                                        value="3">
                                                    <span class="form-check-sign"></span>
                                                    Pin Menu
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="role[]"
                                                        value="4">
                                                    <span class="form-check-sign"></span>
                                                    Member Menu
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="role[]"
                                                        value="5">
                                                    <span class="form-check-sign"></span>
                                                    Stockist Menu
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="role[]"
                                                        value="6">
                                                    <span class="form-check-sign"></span>
                                                    Product Menu
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="role[]"
                                                        value="7">
                                                    <span class="form-check-sign"></span>
                                                    Setting Menu
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
    $("#editAdmin").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    $("#rmAdmin").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
</script>
@stop
