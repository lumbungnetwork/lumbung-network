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
                        <h6 class="mb-3">Explorer and Verifier</h6>
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        
                            <form class="row" method="get" action="/m/explorer/user">
                            {{ csrf_field() }}
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="get_id" autocomplete="off" placeholder="Periksa User. minimal 3 huruf">
                                    <input type="hidden" name="get_id" id="id_get_id">
                                    <ul class="typeahead dropdown-menu" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 98%;margin-left: 11px;" id="get_id-box"></ul>
                                </fieldset>
                            </div>
                            <div class="col-xl-12">
                                <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                            </form>
                    </div>
                    
                    @if($dataExplore != null)
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="card-box table-responsive">
                                    <h4>Detail User</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Username</td>
                                                <td>{{$dataExplore->user->user_code}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>Aktif</td>
                                            </tr>
                                            <tr>
                                                <td>Sponsor</td>
                                                <td>{{$dataExplore->sponsor->user_code}}</td>
                                            </tr>
                                            <?php
                                                $active_at = $dataExplore->user->active_at;
                                                if($dataExplore->user->pin_activate_at != null){
                                                    $active_at = $dataExplore->user->pin_activate_at;
                                                }
                                            ?>
                                            <tr>
                                                <td>Aktif Sejak</td>
                                                <td>{{date('d F Y', strtotime($dataExplore->user->active_at))}}</td>
                                            </tr>
                                            <tr>
                                                <td>Kadaluarsa</td>
                                                <td>{{date('d F Y', strtotime('+365 days', strtotime($active_at)))}}</td>
                                            </tr>
                                            <tr>
                                                <td>Siklus</td>
                                                <td>{{$dataExplore->user->pin_activate}}</td>
                                            </tr>
                                            <tr>
                                                <td>Peringkat</td>
                                                <td>{{$dataExplore->peringkat}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total LMB Diklaim</td>
                                                <td>{{number_format($dataExplore->lmb_claim, 0, ',', '.')}}</td>
                                            </tr>
                                            <?php
                                                $total_wd = $dataExplore->total_wd + $dataExplore->fee_tuntas;
                                                $total_bonus_blmWD = $dataExplore->total_bonus - $dataExplore->total_wd - $dataExplore->fee_tuntas;
                                            ?>
                                            <tr>
                                                <td>Total Bonus (Rp)</td>
                                                <td>{{number_format($dataExplore->total_bonus, 0, ',', '.')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Belanja (Rp)</td>
                                                <td>{{number_format($dataExplore->total_sales, 0, ',', '.')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total PIN Tersedia</td>
                                                <td>{{$dataExplore->pin_tersedia}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total PIN Terpakai</td>
                                                <td>{{$dataExplore->pin_terpakai}}</td>
                                            </tr>
                                            <?php
                                                $tron = '--';
                                                if($dataExplore->user->tron != null){
                                                    $tron = $dataExplore->user->tron;
                                                }
                                            ?>
                                            <tr>
                                                <td>Alamat TRON</td>
                                                <td>{{$tron}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
    <!--                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-5">
                                <div class="card-box table-responsive">
                                    <h4>Aktifitas terbaru</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="table-active">
                                                <td>Bonus</td>
                                            </tr>
                                            <tr>
                                                <td>Request WD</td>
                                            </tr>
                                            <tr class="table-active">
                                                <td>Aktifasi</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    @endif
                    
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
    $(document).ready(function(){
        $("#get_id").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/explore/member" + "?name=" + $(this).val() ,
                success: function(data){
                    if(data != null){
                        $("#get_id-box").show();
                        $("#get_id-box").html(data);
                    }
                }
            });
        });
    });
    function selectUsername(val) {
        var valNew = val.split("____");
        $("#get_id").val(valNew[1]);
        $("#id_get_id").val(valNew[0]);
        $("#get_id-box").hide();
    }
</script>
@stop
