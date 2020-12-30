@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                        <i class="fa fa-arrow-left"></i> Beranda
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">

                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                @if ( Session::has('message') )
                                <div class="container">
                                    <div class="alert alert-{{Session::get('messageclass')}}" role="alert">
                                        {{Session::get('message')}}
                                    </div>
                                </div>

                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group">
                                            <label for="input_email">Email</label>
                                            <input type="email" class="form-control" id="input_email" name="email"
                                                autocomplete="off" required="">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group">
                                            <label for="input_hp">No. HP</label>
                                            <input type="text" class="form-control allownumericwithoutdecimal"
                                                id="input_hp" name="hp" autocomplete="off" required="">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group">
                                            <label for="input_username">Username (Login User)</label>
                                            <input type="text" class="form-control" id="input_username" name="user_code"
                                                autocomplete="off" required="">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group">
                                            <label for="affiliate" style="display: inline;">Afiliasi (Opsional)</label>
                                            <a tabindex="0" class="btn btn-sm btn-outline-warning mb-1" role="button"
                                                data-toggle="popover" data-trigger="focus" title="Afiliasi (Opsional)"
                                                data-content="Ikatan kerja sama yang sudah disepakati dalam sebuah kelompok kerja, bisa memiliki aturan tersendiri namun tetap berlandaskan protokol Lumbung Network.">Apa
                                                ini?</a>
                                            <select class="custom-select" id="affiliate" name="affiliate">
                                                <option value="0" selected>Standard Membership</option>
                                                <option value="1">KBB</option>
                                                <option value="2">KBB-Pasif</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <fieldset class="form-group">
                                            <label for="input_password">Password</label>
                                            <input type="password" class="form-control" id="input_password"
                                                name="password" required="">
                                        </fieldset>
                                    </div>
                                    <div class="col-12">
                                        <fieldset class="form-group">
                                            <label for="input_repassword">Ketik Ulang Password</label>
                                            <input type="password" class="form-control" id="input_repassword"
                                                name="repassword" required="">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="pretty p-icon p-toggle p-plain">
                                            <input type="checkbox" id="show-password" />
                                            <div class="state p-success-o p-on">
                                                <i class="icon mdi mdi-eye"></i>
                                                <label>Sembunyikan Password</label>
                                            </div>
                                            <div class="state p-off">
                                                <i class="icon mdi mdi-eye-off"></i>
                                                <label>Tunjukkan Password</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn"
                                            data-toggle="modal" data-target="#confirmSubmit"
                                            onClick="inputSubmit()">Buat Akun</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>
    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document" id="confirmDetail">
        </div>
    </div>
</div>

@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    function inputSubmit(){
           var email = $("#input_email").val();
           var password = $("#input_password").val();
           var repassword = $("#input_repassword").val();
           var affiliate = $("#affiliate").val();
           var user_code = $("#input_username").val();
           var hp = $("#input_hp").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-sponsor?email="+email+"&password="+password+"&repassword="+repassword+"&affiliate="+affiliate+"&user_code="+user_code+"&hp="+hp ,
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
            $('#show-password').click(function(){
                if($(this).is(':checked')){
                        $('#input_password').attr('type','text');
                        $('#input_repassword').attr('type','text');
                }else{
                        $('#input_password').attr('type','password');
                        $('#input_repassword').attr('type','password');
                }
            });

            $('[data-toggle="popover"]').popover()
        });

        $('.popover-dismiss').popover({
            trigger: 'focus'
        })



</script>
@stop
