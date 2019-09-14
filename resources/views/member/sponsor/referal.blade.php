@extends('layout.member.main')
@section('content')
<div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="account-bg">
            <div class="card-box m-b-0">
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box widget-user">
                        <div>
                            <img src="/asset_member/images/profile.jpg" class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h5 class="m-t-20 m-b-5">{{$dataUser->user_code}}</h5>
                                <p class="text-muted m-b-0 font-13">{{$dataUser->hp}}</p>
                                <div class="user-position">
                                    <span class="text-info font-weight-bold">Sponsor</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-t-30 m-b-20">
                    <div class="col-xs-12 text-xs-center">
                        <h6 class="text-muted text-uppercase m-b-0 m-t-0">Register</h6>
                    </div>
                    @if ( Session::has('message') )
                        <div class="widget-content mt10 mb10 mr15">
                            <div class="alert alert-{{ Session::get('messageclass') }}">
                                <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }} 
                            </div>
                        </div>
                    @endif
                    <form class="form-horizontal m-t-20" method="post" action="/ref">
                        {{ csrf_field() }}
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input type="email" class="form-control" placeholder="Email" name="email" autocomplete="off" value="{{$dataValue->email}}" required="">
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input type="text" class="form-control allownumericwithoutdecimal" placeholder="No. Handphone" name="hp" autocomplete="off" value="{{$dataValue->hp}}" required="">
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input type="text" class="form-control" placeholder="Username" name="user_code" autocomplete="off" value="{{ Session::get('user_code') }}" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input type="password" class="form-control" placeholder="Re-type Password" name="repassword" autocomplete="off" required="">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-30">
                            <input type="hidden" name="ref" class="form-control" value="{{$dataUser->user_code}}">
                            <div class="col-xs-12">
                                <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
<script>
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
</script>
@stop