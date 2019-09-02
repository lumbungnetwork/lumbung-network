@extends('layout.member.main')
@section('content')
<div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="account-bg">
            <div class="card-box m-b-0">
                <div class="text-xs-center m-t-20">
                    <div class="logo">
                        <img src="/image/logo_lumbung.jpg" style="width: 100px;">
                    </div>
                </div>
                <div class="m-t-30 m-b-20">
                    <div class="col-xs-12 text-xs-center">
                        <h6 class="text-muted text-uppercase m-b-0 m-t-0">Sign In</h6>
                    </div>
                    @if ( Session::has('message') )
                        <div class="widget-content mt10 mb10 mr15">
                            <div class="alert alert-{{ Session::get('messageclass') }}">
                                <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }} 
                            </div>
                        </div>
                    @endif
                    <form class="form-horizontal m-t-20" method="post" action="/area/login">
                        {{ csrf_field() }}
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input type="text" class="form-control" placeholder="Username" name="admin_email" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input type="password" class="form-control" placeholder="Password" name="admin_password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-30">
                            <input type="hidden" name="type" value="10">
                            <div class="col-xs-12">
                                <button class="btn btn-dark btn-block waves-effect waves-light" type="submit" style="background-color: #333;">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop