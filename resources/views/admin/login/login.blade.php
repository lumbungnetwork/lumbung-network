@extends('layout.admin.main')
@section('content')
<div class="full-page register-page section-image" filter-color="black">
    <div class="content">
        <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <div class="card card-lock text-center">
                    <div class="card-header ">
                        <img src="{{ URL::to('/') }}/image/logo.jpg ">
                    </div>
                    <form method="post" action="/login_admin">
                        {{ csrf_field() }}
                        <div class="card-body ">
                            @if ( Session::has('message') )
                                <div class="widget-content mt10 mb10 mr15">
                                    <div class="alert alert-{{ Session::get('messageclass') }}">
                                        <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                        {{  Session::get('message')    }} 
                                    </div>
                                </div>
                            @endif
                            <h4 class="card-title">Area Login</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Email" name="admin_email" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" name="admin_password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <button type="submit" class="btn btn-primary">Log In</button>
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