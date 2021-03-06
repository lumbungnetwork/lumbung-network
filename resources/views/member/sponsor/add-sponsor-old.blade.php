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
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in"
                            role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }}
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="input_email">Email</label>
                                    <input type="email" class="form-control" id="input_email" name="email"
                                        autocomplete="off" required="">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="input_hp">No. HP</label>
                                    <input type="text" class="form-control allownumericwithoutdecimal" id="input_hp"
                                        name="hp" autocomplete="off" required="">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="input_username">Username (Login User)</label>
                                    <input type="text" class="form-control" id="input_username" name="username"
                                        autocomplete="off" required="">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="input_password">Password</label>
                                    <input type="password" class="form-control" id="input_password" name="password"
                                        required="">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="input_repassword">Ketik Ulang Password</label>
                                    <input type="password" class="form-control" id="input_repassword" name="repassword"
                                        required="">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="checkbox" class="form-checkbox"> Show password
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="checkbox" class="form-checkbox1"> Show password
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary" id="submitBtn" data-toggle="modal"
                                    data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog"
                            aria-labelledby="modalLabel" aria-hidden="true">
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

@section('javascript')
<script>
    function inputSubmit(){
           var email = $("#input_email").val();
           var password = $("#input_password").val();
           var repassword = $("#input_repassword").val();
           var name = $("#input_name").val();
           var username = $("#input_username").val();
           var hp = $("#input_hp").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-sponsor?email="+email+"&password="+password+"&repassword="+repassword+"&name="+name+"&username="+username+"&hp="+hp ,
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
        
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
        $(document).ready(function(){		
            $('.form-checkbox').click(function(){
                if($(this).is(':checked')){
                        $('#input_password').attr('type','text');
                }else{
                        $('#input_password').attr('type','password');
                }
            });
        });
        
        $(document).ready(function(){		
            $('.form-checkbox1').click(function(){
                if($(this).is(':checked')){
                        $('#input_repassword').attr('type','text');
                }else{
                        $('#input_repassword').attr('type','password');
                }
            });
        });

</script>
@stop