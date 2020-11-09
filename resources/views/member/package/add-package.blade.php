@extends('layout.member.new_main')
@section('content')

<div class="wrapper">


        <!-- Page Content -->
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
                        <h6 class="mb-3">Beli Paket</h6>

                        <div class="alert alert-info" role="alert" style="color:#222;">
                            Halo <b>{{$dataUser->name}}</b>, Selamat bergabung di keluarga Lumbung Network. Saat ini status keanggotaan anda belum aktif, silakan ajukan permohonan Aktivasi ke Sponsor anda dengan klik tombol di bawah:
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 pricing-table">
                                        @foreach($allPackage as $row)
                                        <?php
                                            $price = $row->pin * $pinSetting->price;
                                        ?>
                                        <div class="pricing-item pricing-featured">
                                            <div class="selected">Membership</div>
                                            <div class="pricing-value">
                                                <img src="/image/logo_lumbung2.png" alt="user" class="img-circle" style="width: 180px;">
                                            </div>
                                            <div class="pricing-title">
                                                <h5><b>{{$row->name}}</b></h5>
                                                <h5><b>Rp. {{number_format($price, 0, ',', ',')}}</b></h5>
                                                <h5><b>({{$row->pin}} PIN)</b></h5>
                                            </div>
                                            <ul class="pricing-features">
                                                <li><span class="keywords">{{$row->short_desc}}</span></li>
                                                <li>Iuran Keanggotaan Tahunan</li>
                                                <li>Reward Aset dari Belanja</li>
                                                <li>Dividen Setiap Bulan</li>
                                            </ul>
                                            <a rel="tooltip" title="View" data-toggle="modal" data-target="#orderPackage" id="beli" class="btn  btn-success" href="{{ URL::to('/') }}/m/cek/add-package/{{$row->id}}/0" style="margin-bottom: 15px;">Request Aktivasi</i></a>
                                            <div class="checkbox checkbox-success checkbox-single">
                                                <input type="checkbox" id="singleCheckbox2" name="setuju" value="setuju" aria-label="Single checkbox Two" data-url="{{ URL::to('/') }}/m/cek/add-package/{{$row->id}}/1">
                                                <label></label>
                                            </div>
                                            <ul class="pricing-features" style="padding: 2px 5px 5px;">
                                                Saya telah membaca dan menyetujui <a href="https://lumbung.network/aturan-dan-ketentuan-keanggotaan/" target="_blank">Aturan dan Ketentuan Keanggotaan</a> Lumbung Network
                                            </ul>
                                        </div>
                                        @endforeach
                                        <div class="modal fade" id="orderPackage" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                </div>
                                            </div>
                                        </div>
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
    <script type="text/javascript">
    $("#orderPackage").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });

    $("#singleCheckbox2").change(function() {
        var href = $("#singleCheckbox2:checked").first().attr("data-url") || 'Test1';
        $("#beli").attr('href', href);
    });
</script>
@stop
