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
                        <h4 class="page-title">{{$headerTitle}}</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
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
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="row">
                            <div class="col-xl-8 col-xs-12">
                                <fieldset class="form-group" disabled>
                                    <label for="alamt_lengkap">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamt_lengkap" rows="2">{{$dataUser->alamat}}</textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-2 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label for="kode_pos">Kode Pos</label>
                                        <input type="text" class="form-control" id="kode_pos" value="{{$dataUser->kode_pos}}">
                                    </fieldset>
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
<!--<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />-->
@stop