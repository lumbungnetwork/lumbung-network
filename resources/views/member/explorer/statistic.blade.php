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
                    <h5 class="mb-3">Sepanjang Masa</h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow-sm p-2">
                                <p>
                                    <a href="#">Total Aktivasi Member</a>
                                </p>
                                <h6 class="text-warning">{{number_format($dataAll->total_aktivasi, 0, ',', '.')}}</h6>
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
                                <h6 class="text-warning">Rp{{number_format($total_wd, 0, ',', '.')}}</h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow-sm p-2">
                                <p>
                                    Belanja Member Stockist
                                </p>
                                <h6 class="text-warning">Rp{{number_format($dataAll->total_sales, 0, ',', '.')}}</h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow-sm p-2">
                                <p>
                                    Belanja Member Vendor
                                </p>
                                <h6 class="text-warning">
                                    Rp{{number_format(($dataAll->total_vsales + $dataAll->total_ppob), 0, ',', '.')}}
                                </h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow-sm p-2">
                                <p>
                                    LMB Diklaim
                                </p>
                                <h6 class="text-warning">{{number_format($dataAll->lmb_claim, 0, ',', '.')}}</h6>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow-sm p-2">
                                <p>
                                    Dividend LMB
                                </p>
                                <?php
                                        $tambahan16persen =  ($dataAll->total_vsales + $dataAll->total_ppob) * 1.6 / 100;
                                        $deviden_lmb = (20 / 100 * $dataAll->total_aktivasi * 100000) + ($dataAll->total_sales * 1 / 100) + $tambahan16persen;
                                    ?>
                                <h6 class="text-warning">Rp{{number_format($deviden_lmb, 0, ',', '.')}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <?php
                                $total_wd_lastmonth = $dataAll_month->total_wd + $dataAll_month->fee_tuntas;
                                $dividen_lmb_lastmonth = (20 / 100 * $dataAll_month->total_aktivasi * 100000) + ($dataAll_month->total_sales * 1 / 100) + ($dataAll_month->profitSharingPool * 80 / 100);
                            ?>
                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="table-responsive">
                                <h5>Bulan Lalu</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-active">
                                            <td>Jumlah Aktivasi</td>
                                            <td>{{number_format($dataAll_month->total_aktivasi, 0, ',', '.')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Bonus Dibayarkan</td>
                                            <td>Rp. {{number_format($total_wd_lastmonth, 0, ',', '.')}}</td>
                                        </tr>
                                        <tr class="table-active">
                                            <td>Koin LMB Diklaim</td>
                                            <td>{{number_format($dataAll_month->lmb_claim, 0, ',', '.')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Belanja Member (Stockist)</td>
                                            <td>Rp {{number_format($dataAll_month->total_sales, 0, ',', '.')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Belanja Member (Vendor)</td>
                                            <td>Rp.
                                                {{number_format(($dataAll_month->total_vsales + $dataAll_month->total_ppob), 0, ',', '.')}}
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <td>Dividend LMB</td>
                                            <td>{{number_format($dividen_lmb_lastmonth, 0, ',', '.')}}</td>
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
<div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="false">
    <div class="modal-dialog" role="document" id="confirmDetail">
    </div>
</div>
<div class="modal fade" id="confirmSubmitTopUp" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog" role="document" id="confirmDetailTopUp">
    </div>
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
