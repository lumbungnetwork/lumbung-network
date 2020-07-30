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
                        <h4 class="page-title">Beli Paket</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="alert alert-info" role="alert" style="color:#222;">
                            Halo <b>{{$dataUser->name}}</b>, Selamat bergabung di keluarga Lumbung Network. Saat ini status keanggotaan anda belum aktif, Silakan memilih paket di bawah ini  dengan klik "Beli Paket"
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
                                                <li>Bonus Sponsor Rp. 20.000 / PIN</li>
                                                <li>Link Referal</li>
                                            </ul>
                                            <a rel="tooltip" title="View" data-toggle="modal" data-target="#orderPackage" id="beli" class="btn  btn-primary" href="{{ URL::to('/') }}/m/cek/add-package/{{$row->id}}/0" style="margin-bottom: 15px;">Beli</i></a>
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
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop
@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
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