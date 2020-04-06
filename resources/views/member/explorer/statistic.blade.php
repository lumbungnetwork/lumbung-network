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
                        <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                            <i class="fas fa-power-off text-danger icon-bottom"></i>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <h6 class="mb-3">Explorer and Verifier</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Total Aktifasi
                                    </p>
                                    <h6 class="text-warning">{{number_format($dataAll->total_aktifasi, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Total Bonus Dibayarkan
                                    </p>
                                    <?php
                                        $total_wd = $dataAll->total_wd + $dataAll->fee_tuntas;
                                    ?>
                                    <h6 class="text-warning">Rp {{number_format($total_wd, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Belanja Member
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($dataAll->total_sales, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        LMB Diklaim
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($dataAll->lmb_claim, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Devidend LMB
                                    </p>
                                    <?php
                                        $deviden_lmb = (20 / 100 * $dataAll->total_aktifasi * 100000) + ($dataAll->total_sales * 1 / 100);
                                    ?>
                                    <h6 class="text-warning">Rp {{number_format($deviden_lmb, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <h6 class="mb-3">Bulan Lalu</h6>
                            <?php
                                $total_wd_lastmonth = $dataAll->total_wd + $dataAll_month->fee_tuntas;
                                $deviden_lmb_lastmonth = (20 / 100 * $dataAll_month->total_aktifasi * 100000) + ($dataAll_month->total_sales * 1 / 100);
                            ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
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
            @include('layout.member.nav')
        </div>
        <div class="overlay"></div>
    </div>


    <!-- Modal help-topup -->
    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" id="confirmDetail">
        </div>
    </div>
    <div class="modal fade" id="confirmSubmitTopUp" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" id="confirmDetailTopUp">
        </div>
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
