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
                        <h4 class="page-title">Explorer and Verifier</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <h4>Lifetime Total</h4>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase m-b-20">Total Aktifasi</h6>
                        <h2 class="m-b-20">{{number_format($dataAll->total_aktifasi, 0, ',', '.')}}</h2>
                    </div>
                </div>
                <?php
                    $total_wd = $dataAll->total_wd + $dataAll->fee_tuntas;
                ?>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase m-b-20">Bonus Dibayarkan (Rp)</h6>
                        <h2 class="m-b-20">{{number_format($total_wd, 0, ',', '.')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase m-b-20">Belanja Member (Rp)</h6>
                        <h2 class="m-b-20">{{number_format($dataAll->total_sales, 0, ',', '.')}}</h2>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase m-b-20">LMB Diklaim</h6>
                        <h2 class="m-b-20">{{number_format($dataAll->lmb_claim, 0, ',', '.')}}</h2>
                    </div>
                </div>
                <?php
                    $deviden_lmb = (20 / 100 * $dataAll->total_aktifasi * 100000) + ($dataAll->total_sales * 1 / 100);
                ?>
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase m-b-20">Dividend LMB (Rp)</h6>
                        <h2 class="m-b-20">{{number_format($deviden_lmb, 0, ',', '.')}}</h2>
                    </div>
                </div>
            </div>
            <?php
                    $total_wd_lastmonth = $dataAll->total_wd + $dataAll_month->fee_tuntas;
                    $deviden_lmb_lastmonth = (20 / 100 * $dataAll_month->total_aktifasi * 100000) + ($dataAll_month->total_sales * 1 / 100);
                ?>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-8">
                    <div class="card-box table-responsive">
                        <h4>Bulan Lalu</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-active">
                                    <td>Jumlah Aktifasi</td>
                                    <td>{{number_format($dataAll_month->total_aktifasi, 0, ',', '.')}}</td>
                                </tr>
                                <tr>
                                    <td>Bonus Dibayarkan (Rp)</td>
                                    <td>{{number_format($total_wd_lastmonth, 0, ',', '.')}}</td>
                                </tr>
                                <tr class="table-active">
                                    <td>Koin LMB Diklaim</td>
                                    <td>{{number_format($dataAll_month->lmb_claim, 0, ',', '.')}}</td>
                                </tr>
                                <tr>
                                    <td>Belanja Member (Rp)</td>
                                    <td>{{number_format($dataAll_month->total_sales, 0, ',', '.')}}</td>
                                </tr>
                                <tr class="table-active">
                                    <td>Dividend LMB (Rp)</td>
                                    <td>{{number_format($deviden_lmb_lastmonth, 0, ',', '.')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop