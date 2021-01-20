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
                    <h6 class="mb-3">Alamat TRON (TRON Address)</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <label for="tron">Masukan Alamat Tron Anda</label>
                                <input type="text" class="form-control" id="tron" name="tron" autocomplete="off">
                            </fieldset>

                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary float-right" id="submitBtn" data-toggle="modal"
                                data-target="#confirmSubmit" onClick="inputSubmit()">Tautkan</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="rounded-lg shadow p-3">
                                <h6 class="text-danger">Perhatian!!!</h6>
                                <p>
                                    Pastikan anda menggunakan alamat TRON yang benar-benar anda kuasai sepenuhnya
                                    (Anda memiliki Private Key alamat tersebut).
                                    <strong>Jangan menggunakan alamat TRON dari Exchanger seperti
                                        Indodax/Binance</strong>!!
                                </p>
                                <p>
                                    Lumbung Network merekomendasikan aplikasi Tronlink Pro, TokenPocket atau Klever
                                    (Download di AppStore atau PlayStore).
                                </p>
                            </div>
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    function inputSubmit(){
           var tron = $("#tron").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-tron?tron="+tron,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }

        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
            $('#tutupModal').remove();
            $('#submit').remove();
        }

</script>
@stop
