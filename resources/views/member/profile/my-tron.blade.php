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
                    <h6 class="mb-3">Tron</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <fieldset class="form-group" readonly>
                                <label>Alamat TRON Anda</label>
                                <input style="font-size: 12px;" type="text" class="form-control font-weight-light"
                                    value="{{$dataUser->tron}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
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
                                <p>
                                    Anda hanya bisa memasukan alamat TRON 1 kali saja. Apabila anda ingin mengganti
                                    alamat TRON anda,
                                    anda harus mengajukan permohonan tertulis kepada tim Delegasi di daerah anda.
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

@stop
