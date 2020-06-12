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
                    <h6 class="mb-3">Keranjang Transaksi Member</h6>
                    @if ( Session::has('message') )
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }} 
                        </div>
                    @endif
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Total Harga (Rp.)</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getDataMaster != null)
                                        <tr>
                                            <td><b>{{$getDataMaster->message}}</td>
                                            <td>{{number_format($getDataMaster->ppob_price, 0, ',', ',')}}</td>
                                            <td>{{$getDataMaster->product_name}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><b>Total</b></td>
                                        <td><b>{{number_format($getDataMaster->ppob_price, 0, ',', ',')}}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="card-text">Metode Pembayaran</p>
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    @if($getDataMaster->buy_metode == 1)
                                    <div class="radio radio-primary" style="margin-bottom: 15px;">
                                        <input type="radio" name="radio" id="radio1" value="1" checked="">
                                        <label for="radio1">
                                            COD
                                        </label>
                                    </div>
                                    @endif
                                    @if($getDataMaster->buy_metode == 3)
                                    <div class="radio radio-primary" style="margin-bottom: 15px;">
                                        <input type="radio" name="radio" id="radio3" value="3" checked="">
                                        <label for="radio3">
                                            eIDR
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    @if($getDataMaster->status == 1)
                                        <form method="POST" action="/m/confirm/vppob">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="ppob_id" value="{{$getDataMaster->id}}">
                                            <input type="hidden" name="harga_modal" value="{{$getDataMaster->harga_modal}}">
                                            <a class="btn btn-dark" href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                            <button type="submit" class="btn btn-success" id="submitBtn">Konfirmasi</button>
                                        </form>
                                    @endif
                                    @if($getDataMaster->status != 1)
                                        <a class="btn btn-dark" href="{{ URL::to('/') }}/m/list/vppob-transaction">Kembali</a>
                                    @endif
                                </div>
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