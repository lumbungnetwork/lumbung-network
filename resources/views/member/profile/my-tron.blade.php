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
                            <div class="col-xl-12 col-xs-12">
                                    <fieldset class="form-group" disabled>
                                        <label>Alamat TRON Anda</label>
                                        <input type="text" class="form-control" value="{{$dataUser->tron}}">
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="card card-block" style="border: none;">
                                    <h4 class="card-title text-danger">Perhatian!!!</h4>
                                    <p class="card-text">
                                        Pastikan anda menggunakan alamat TRON yang benar-benar anda kuasai sepenuhnya 
                                        (Anda memiliki Private Key alamat tersebut).
                                        Jangan menggunakan alamat TRON dari Exchanger seperti Indodax/Binance!!
                                    </p>
                                    <p class="card-text">
                                        Lumbung Network merekomendasikan aplikasi TronWallet (Download di AppStore atau PlayStore).
                                    </p>
                                    <p class="card-text">
                                        Anda hanya bisa memasukan alamat TRON 1 kali saja. Apabila anda ingin mengganti alamat TRON anda, 
                                        anda harus mengajukan permohonan tertulis kepada tim BackOffice.
                                    </p>
                                </div>
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