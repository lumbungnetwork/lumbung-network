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
                            Halo {{$dataUser->name}}, Selamat bergabung di keluarga Lumbung Network. Saat ini status keanggotaan anda belum aktif, Silakan memilih paket di bawah ini  dengan klik "Beli Paket"
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    @foreach($allPackage as $row)
                                    <?php 
                                        $price = $row->pin * $pinSetting->price;
                                        ?>
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-block">
                                                <h4 class="card-title">{{$row->name}}</h4>
                                                <h4 class="card-title">({{$row->pin}} PIN)</h4>
                                                <p class="card-text">Rp. {{number_format($price, 0, ',', ',')}}</p>
                                                <p class="card-text">{{$row->short_desc}} Paket</p>
                                                <p class="card-text">Bonus Sponsor Rp. 20.000 / PIN</p>
                                                <p class="card-text">Link Referal</p>
                                                <!--<p class="card-text">Download Tools Promosi</p>-->
                                                <a rel="tooltip" title="View" data-toggle="modal" data-target="#orderPackage" class="btn  btn-primary" href="{{ URL::to('/') }}/m/cek/add-package/{{$row->id}}">Beli Paket</i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="modal fade" id="orderPackage" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
</script>
@stop
