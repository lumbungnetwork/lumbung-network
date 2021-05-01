@extends('layout.member.main')
@section('content')

<div class="m-25">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 mb-5">
                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-center">
                        <img src="/image/logo_lumbung2.png" class="w-25" alt="">
                        <p class="text-muted m-b-0 font-13 m-t-20">Recovery password anda</p>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                @if ( Session::has('message') )
                                <div class="widget-content mt10 mb10 mr15">
                                    <div class="alert alert-{{ Session::get('messageclass') }}">
                                        <button class="close" type="button" data-dismiss="alert"><span
                                                aria-hidden="true">&times;</span></button>
                                        {{  Session::get('message')    }}
                                    </div>
                                </div>
                                @endif
                                <form class="form-horizontal m-t-20" method="post" action="/auth/passwd">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="password" required=""
                                                placeholder="password" name="password" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="password" required=""
                                                placeholder="repassword" name="repassword" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group text-center m-t-20 m-b-0">
                                        <input type="hidden" name="userID" value="{{$data->username}}">
                                        <input type="hidden" name="authCode" value="{{$hiddenCode}}">
                                        <input type="hidden" name="emailCheck" value="{{$data->email}}">
                                        <div class="form-group">
                                            <button class="btn btn-dark btn-block"> Send Email </button>
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
                        <p class="text-muted m-b-0 font-13 m-t-20">Recovery password anda</p>
                        @if ( Session::has('message') )
                        <div class="widget-content mt10 mb10 mr15">
                            <div class="alert alert-{{ Session::get('messageclass') }}">
                                <button class="close" type="button" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }}
                            </div>
                        </div>
                        @endif
                    </div>
                    <form class="form-horizontal m-t-20" method="post" action="/auth/passwd">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" placeholder="password"
                                    name="password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" placeholder="repassword"
                                    name="repassword" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20 m-b-0">
                            <input type="hidden" name="userID" value="{{$data->username}}">
                            <input type="hidden" name="authCode" value="{{$hiddenCode}}">
                            <input type="hidden" name="emailCheck" value="{{$data->email}}">
                            <div class="col-xs-12">
                                <button class="btn btn-success btn-block waves-effect waves-light"
                                    type="submit">Submit</button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
@stop