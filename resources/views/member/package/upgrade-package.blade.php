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
                        <h4 class="page-title">Upgrade Paket</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    @foreach($packageUpgrade as $row)
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-block">
                                                <h4 class="card-title">{{$row->name}} ({{$row->pin}} PIN)</h4>
                                                <p class="card-text">{{$row->short_desc}} Paket Safra</p>
                                                <p class="card-text">Bonus Sponsor Rp. 75.000 / PIN</p>
                                                <p class="card-text">Bonus Pasangan Rp. 15.000 / Pasangan</p>
                                                <p class="card-text">Bonus RO 10 Level Rp. 75.000 /Pin</p>
                                                <p class="card-text">Kuota Bonus /Bulan Max Rp. {{number_format($row->stock_wd, 0, ',', ',')}}</p>
                                                <p class="card-text">XXX Bonus Extra Bulanan XXX</p>
                                                <p class="card-text">Discount Safra Poin</p>
                                                <p class="card-text">Link Referal</p>
                                                <p class="card-text">Download Tools Promosi</p>
                                                <a rel="tooltip" title="View" data-toggle="modal" data-target="#upgradePackage" class="btn  btn-primary" href="{{ URL::to('/') }}/m/cek/upgrade-package/{{$row->id}}">Upgrade</i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="modal fade" id="upgradePackage" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
    $("#upgradePackage").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
</script>
@stop
