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
                        <h6 class="mb-3">Profil Saya</h6>
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-xl-9 col-xs-12">
                            <a href="{{ URL::to('/') }}/m/edit/address" class="btn btn-sm btn-success waves-effect waves-light">Ubah Alamat</a>
                            </div>
                            <div class="col-xl-9 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label>Nama Lengkap (sesuai dengan Nama pada Rekening Bank)</label>
                                        <input type="text" class="form-control" value="{{$dataUser->full_name}}">
                                    </fieldset>
                            </div>
                            <?php
                                $gender = 'Pria';
                                if($dataUser->gender == 2){
                                    $gender = 'Wanita';
                                }
                            ?>
                            <div class="col-xl-3 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label for="gender">Gender</label>
                                        <input type="text" class="form-control" id="gender" value="{{$gender}}">
                                    </fieldset>
                            </div>
                            <div class="col-xl-6 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label for="province">Provinsi</label>
                                        <input type="text" class="form-control" id="province" value="{{$dataUser->provinsi}}">
                                    </fieldset>
                            </div>
                            <div class="col-xl-6 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label for="city">Kota/Kabupaten</label>
                                        <input type="text" class="form-control" id="city" value="{{$dataUser->kota}}">
                                    </fieldset>
                            </div>
                            <div class="col-xl-6 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label for="province">Kecamatan</label>
                                        <input type="text" class="form-control" id="province" value="{{$dataUser->kecamatan}}">
                                    </fieldset>
                            </div>
                            <div class="col-xl-6 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label for="city">Kelurahan</label>
                                        <input type="text" class="form-control" id="city" value="{{$dataUser->kelurahan}}">
                                    </fieldset>
                            </div>
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group" disabled>
                                    <label for="alamt_lengkap">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamt_lengkap" rows="2">{{$dataUser->alamat}}</textarea>
                                </fieldset>
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
@stop
