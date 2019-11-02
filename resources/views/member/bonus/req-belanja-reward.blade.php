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
                            <h4 class="page-title">Reward Belanja</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="card-box tilebox-one">
                            <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                            <h6 class="text-muted text-uppercase m-b-20">Total Claim Reward</h6>
                            <h2 class="m-b-20">{{$getTotal}} LMB</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 card-box table-responsive">
                            @if ( Session::has('message') )
                                <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{  Session::get('message')    }} 
                                </div>
                            @endif
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
                                                $kelipatan = floor(($row->month_sale_price/10000)/10) * 10;
                                            ?>
                                            <tr>
                                                <td>{{$row->monthly}}</td>
                                                <td>{{number_format($row->month_sale_price, 0, ',', '.')}}</td>
                                                <td>{{$kelipatan}} LMB</td>
                                                <td>
                                                    @if($row->canClaim == 1)
                                                    <a rel="tooltip" data-toggle="modal" data-target="#confirmSubmit" class="btn btn-custom btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/cek/confirm-belanja-reward?m={{$row->month}}&y={{$row->year}}">
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
@include('layout.member.footer')
@stop

@section('javascript')
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