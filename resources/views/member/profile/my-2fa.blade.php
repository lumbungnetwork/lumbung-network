@extends('layout.member.new_main')
@section('content')

<div class="wrapper">


        <!-- Page Content -->
        <div id="content">

            <div class="bg-gradient-sm">
                <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                    <div class="container">
                        <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                            <i class="fa fa-arrow-left"></i> Beranda
                        </a>
                        <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-navbar btn-transparent">
                            <i class="fas fa-power-off text-danger icon-bottom"></i>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">
                        @if ( Session::has('message') )
                            <div class="container">
                                <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                    {{  Session::get('message')    }}
                                </div>
                            </div>
                        @endif

                        @if($dataUser->{'2fa'} == null)
                            <h5>Buat Kode Pin 2FA Baru</h5>
                            <div class="row">
                                <div class="col-12">
                                    <fieldset class="form-group">
                                        <label for="password">Kode Pin</label>
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                                    </fieldset>
                                </div>
                                <div class="col-12">
                                    <fieldset class="form-group">
                                        <label for="repassword">Ketik Ulang Kode Pin</label>
                                        <input type="password" class="form-control" id="repassword" name="repassword" autocomplete="off">
                                    </fieldset>
                                </div>
                            </div>
                        @endif

                        @if($dataUser->{'2fa'} != null)
                        <h5>Ganti Kode Pin 2FA</h5>
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="password">Konfirmasi Kode Pin Lama</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password" autocomplete="off">
                                </fieldset>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="password">Pin Baru</label>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="repassword">Ketik Ulang Pin Baru</label>
                                    <input type="password" class="form-control" id="repassword" name="repassword" autocomplete="off">
                                </fieldset>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Buat Kode Pin</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @include('layout.member.nav')
        </div>
        <div class="overlay"></div>
    </div>

@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    <script>


        @if($dataUser->{'2fa'} == null)
            function inputSubmit(){
            var password = $("#password").val();
            var repassword = $("#repassword").val();
                $.ajax({
                    type: "GET",
                    url: "{{ URL::to('/') }}/m/cek/2fa?&type=0&password="+password+"&repassword="+repassword,
                    success: function(url){
                        $("#confirmDetail" ).empty();
                        $("#confirmDetail").html(url);
                    }
                });
            }
        @endif

        @if($dataUser->{'2fa'} != null)
        function inputSubmit(){
            var password = $("#password").val();
            var repassword = $("#repassword").val();
            var old_password = $("#old_password").val();
                $.ajax({
                    type: "GET",
                    url: "{{ URL::to('/') }}/m/cek/2fa?&type=1&password="+password+"&repassword="+repassword+"&old_password="+old_password,
                    success: function(url){
                        $("#confirmDetail" ).empty();
                        $("#confirmDetail").html(url);
                    }
                });
            }
        @endif

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

</script>
@stop
