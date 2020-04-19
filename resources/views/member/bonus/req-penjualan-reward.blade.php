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
                        <h6 class="mb-3">Reward Penjualan</h6>
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="card-box tilebox-one">
                                    <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                    <h6 class="text-muted text-uppercase m-b-20">Total Claim Reward</h6>
                                    <h2 class="m-b-20">{{$getTotal}} LMB</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class=" table-responsive">
                                    <table class="table">
                                        <thead class="thead-default">
                                            <tr>
                                                <th>Periode</th>
                                                <th>Total Belanja (Rp.)</th>
                                                <th>Reward LMB</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($getData != null)
                                                @foreach($getData as $row)
                                                    <?php
                                                        $kelipatan = floor(($row->month_sale_price/10000)/10) * 2;
                                                    ?>
                                                    <tr>
                                                        <td>{{$row->monthly}}</td>
                                                        <td>{{number_format($row->month_sale_price, 0, ',', '.')}}</td>
                                                        <td>{{$kelipatan}} LMB</td>
                                                        <td>
                                                            @if($row->canClaim == 1)
                                                            <a rel="tooltip" data-toggle="modal" data-target="#confirmSubmit" class="btn btn-primary btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/cek/confirm-penjualan-reward?m={{$row->month}}&y={{$row->year}}">
                                                                Claim
                                                            </a>
                                                            @endif
                                                            @if($row->canClaim == 0)
                                                            <label class="btn btn-dark btn-sm waves-effect waves-light">
                                                                Claim
                                                            </label>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" id="confirmDetail">
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
    <script>
    function confirmSubmit(){
        var dataInput = $("#form-add").serializeArray();
        $('#form-add').submit();
        $('#tutupModal').remove();
        $('#submit').remove();
    }
        
    $("#confirmSubmit").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-dialog").load(link.attr("href"));
    });
</script>
@stop
