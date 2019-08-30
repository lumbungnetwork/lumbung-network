@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')

    <div class="main-panel">
        <?php //MENU HEADER  ?>
        <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <div class="navbar-toggle">
                        <button type="button" class="navbar-toggler">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </button>
                    </div>
                    <p class="navbar-brand">{{$headerTitle}}</p>
                </div>
            </div>
        </nav>
        <?php //BATAS MENU HEADER  ?>
        <div class="content">
            @if($dataUser->is_active == 1)
                @if($dataOrder > 0)
                    <div class="alert alert-warning alert-dismissible fade show">
                        <span>
                            <b> New Order Package (Total {{$dataOrder}}) - </b> Check this <a href="{{ URL::to('/') }}/m/list/order-package">link</a>
                        </span>
                    </div>
                @endif
            @endif
            @if($dataUser->is_active == 0)
                @if($dataOrder > 0)
                    <div class="alert alert-warning alert-dismissible fade show">
                        <span>
                            <b> Waiting conformation from your sponsor </b>
                        </span>
                    </div>
                @endif
            @endif
            @if ( Session::has('message') )
                <div class="widget-content mt10 mb10 mr15">
                    <div class="alert alert-{{ Session::get('messageclass') }}">
                        <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                        {{  Session::get('message')    }} 
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-pin-3 text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Total Pin</p>
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
                                        <p class="card-title">{{$total}}
                                        </p>
                                        <p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-pin-3 text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Total Pin Terjual</p>
                                        <p class="card-title">{{$sum_pin_keluar}}
                                        </p>
                                        <p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@stop