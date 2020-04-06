@extends('layout.member.new_main')
@section('content')

<div class="m-25">
    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-5 mb-5">
              <div class="bg-white shadow rounded-lg p-5">
                  <div class="text-center">
                      <img src="/image/logo_lumbung2.png" class="w-25" alt="">
                      <h6 class="mt-3">Masuk ke akun Anda</h6>
                      <div class="row justify-content-center">
                          <div class="col-md-12">
                              @if ( Session::has('message') )
                                    <div class="widget-content mt10 mb10 mr15">
                                        <div class="alert alert-{{ Session::get('messageclass') }}">
                                            <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                            {{  Session::get('message')    }} 
                                        </div>
                                    </div>
                                @endif
                              <form class="mt-3" method="post" action="/area/login">
                                {{ csrf_field() }}
                                  <div class="form-group">
                                      <input class="form-control" type="text" placeholder="Username" name="admin_email" autocomplete="off">
                                  </div>
                                  <div class="form-group">
                                      <input class="form-control" type="password" placeholder="Password" name="admin_password" autocomplete="off">
                                  </div>
                                  <div class="form-group">
                                      <button class="btn btn-dark btn-block"> LOGIN </button>
                                  </div>
                              </form>
                          </div>
                      </div>
                      <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12">
                                <a href="/forgot/passwd" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot password?</a>
                            </div>
                        </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
@stop