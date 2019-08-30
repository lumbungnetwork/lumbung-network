@extends('layout.member.main')
@section('content')
@include('layout.member.topbar')
@include('layout.member.sidebar')
<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Sponsor Baru</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xl-6">
                                    <fieldset class="form-group">
                                        <label for="input_email">Email</label>
                                        <input type="email" class="form-control" id="input_email" name="email" autocomplete="off">
                                    </fieldset>
                            </div>
                            <div class="col-xl-3">
                                    <fieldset class="form-group">
                                        <label for="input_password">Password</label>
                                        <input type="password" class="form-control" id="input_password" name="password">
                                    </fieldset>
                            </div>
                            <div class="col-xl-3">
                                    <fieldset class="form-group">
                                        <label for="input_repassword">Ketik Ulang Password</label>
                                        <input type="password" class="form-control" id="input_repassword" name="repassword">
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                    <fieldset class="form-group">
                                        <label for="input_nama">Nama</label>
                                        <input type="text" class="form-control" id="input_name" name="name" autocomplete="off">
                                    </fieldset>
                            </div>
                            <div class="col-xl-4">
                                    <fieldset class="form-group">
                                        <label for="input_username">Username (Login User)</label>
                                        <input type="text" class="form-control" id="input_username" name="user_code" autocomplete="off">
                                    </fieldset>
                            </div>
                            <div class="col-xl-4">
                                    <fieldset class="form-group">
                                        <label for="input_hp">No. HP</label>
                                        <input type="text" class="form-control" id="input_hp" name="hp" autocomplete="off">
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop
@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
<script>
       function inputSubmit(){
           var email = $("#input_email").val();
           var password = $("#input_password").val();
           var repassword = $("#input_repassword").val();
           var name = $("#input_name").val();
           var user_code = $("#input_username").val();
           var hp = $("#input_hp").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-sponsor?email="+email+"&password="+password+"&repassword="+repassword+"&name="+name+"&user_code="+user_code+"&hp="+hp ,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
        }

</script>
@stop