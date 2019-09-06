@extends('layout.member.main')
@section('content')
<div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="account-bg">
            <div class="card-box m-b-0">
                <div class="text-xs-center">
                    <div class="logo">
                        <img src="/image/logo_lumbung2.png" style="width: 120px;">
                    </div>
                </div>
                <div class="m-t-30 m-b-20">
                    <div class="m-t-30 m-b-20">
                        <div class="col-xs-12 text-xs-center">
                            <p class="text-muted m-b-0 font-13 m-t-20">Masukan userID anda, dan kami akan mengirimkan instruksi melalui email  </p>
                            @if ( Session::has('message') )
                            <div class="widget-content mt10 mb10 mr15">
                                <div class="alert alert-{{ Session::get('messageclass') }}">
                                    <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                    {{  Session::get('message')    }} 
                                </div>
                            </div>
                        @endif
                        </div>
                        <form class="form-horizontal m-t-20" method="post" action="/forgot/passwd">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" required="" placeholder="UserID" name="user_id">
                                </div>
                            </div>

                            <div class="form-group text-center m-t-20 m-b-0">
                                <div class="col-xs-12">
                                    <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Send Email</button>
                                </div>
                            </div>

                        </form>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@stop