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
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <form class="login100-form validate-form" method="get" action="/m/explorer/user">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xs-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label>Periksa User</label>
                                    <input type="text" class="form-control" id="get_id" autocomplete="off" placeholder="minimal 3 huruf">
                                    <input type="hidden" name="get_id" id="id_get_id">
                                    <ul class="typeahead dropdown-menu form-control" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 98%;margin-left: 11px;" id="get_id-box"></ul>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary" >Cari</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    @if($dataExplore != null)
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-7">
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
                                            <td>Aktif Sejak</td>
                                            <td>{{date('d F Y', strtotime($dataExplore->user->active_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Kadaluarsa</td>
                                            <td>{{date('d F Y', strtotime('+365 days', strtotime($dataExplore->user->active_at)))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Siklus</td>
                                            <td>1</td>
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
                                        ?>
                                        <tr>
                                            <td>Total Bonus (Rp)</td>
                                            <td>{{number_format($dataExplore->total_bonus, 0, ',', '.')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Belanja (Rp)</td>
                                            <td>{{number_format($dataExplore->total_sales, 0, ',', '.')}}</td>
                                        </tr>
<!--                                        <tr>
                                            <td>Bonus Diterima Bulan Lalu (Rp)</td>
                                            <td>11</td>
                                        </tr>
                                        <tr>
                                            <td>Akumulasi Belanja Bulan Lalu (Rp)</td>
                                            <td>11</td>
                                        </tr>-->
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop


@section('javascript')
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