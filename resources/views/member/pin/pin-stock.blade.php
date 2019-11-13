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
                            <h4 class="page-title">Stock</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <?php
                    $sum_pin_masuk = 0;
                    $sum_pin_keluar = 0;
                    if($dataPin->sum_pin_masuk != null){
                        $sum_pin_masuk = $dataPin->sum_pin_masuk;
                    }
                    if($dataPin->sum_pin_keluar != null){
                        $sum_pin_keluar = $dataPin->sum_pin_keluar;
                    }
                    $total = $sum_pin_masuk - $sum_pin_keluar;
                ?>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-one">
                            <i class="icon-pin pull-xs-right text-success"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Pin Tesedia</h6>
                            <h2 class="m-b-20">{{$total}}</h2>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-one">
                            <i class="ion-forward pull-xs-right text-pink"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Pin Terpakai</h6>
                            <h2 class="m-b-20">{{$sum_pin_keluar}}</h2>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-one">
                            <i class="icon-rocket pull-xs-right text-info"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Pin Paket Terkirim</h6>
                            <h2 class="m-b-20">{{$dataTerkirim}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('layout.member.footer')
@stop