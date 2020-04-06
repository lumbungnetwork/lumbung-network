@extends('layout.member.new_main')
@section('content')

<div class="m-25">
    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-5 mb-5">
              <div class="bg-white shadow rounded-lg p-5">
                  <div class="text-center">
                      <img src="/image/logo_lumbung2.png" class="w-25" alt="">
                      <p class="text-muted m-b-0 font-13 m-t-20">Masukan userID anda, dan kami akan mengirimkan instruksi melalui email  </p>
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
                              <form class="mt-3" method="post" action="/forgot/passwd">
                                {{ csrf_field() }}
                                  <div class="form-group">
                                      <input class="form-control" type="text" required="" placeholder="UserID" name="user_id" autocomplete="off">
                                  </div>
                                  <div class="form-group">
                                      <button class="btn btn-dark btn-block"> Send Email </button>
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
@stop