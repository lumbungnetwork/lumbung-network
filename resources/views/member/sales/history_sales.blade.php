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
                        <h4 class="page-title">Status Sponsor</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-block">
                        <?php
                            $text = '*Anda belum memenuhi Belanja Wajib';
                            $color = 'danger';
                            if($sum > 100000){
                                $text = '*Anda telah memenuhi Belanja Wajib';
                                $color = 'success';
                            }
                        ?>
                        <p class="text-{{$color}}">
                            <b>{{$text}}</b>
                        </p>
                        <h5 class="m-b-20">
                            Total <span><b>Rp. {{number_format($sum, 0, ',', ',')}}</b></span>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-12 card-box table-responsive">
                    @if ( Session::has('message') )
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }} 
                        </div>
                    @endif
                    <form class="login100-form validate-form m-b-20" method="get" action="/m/history/shoping">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-3 col-xs-12">
                                <fieldset class="form-group">
                                    <label>Search</label>
                                    <select class="form-control" name="month" id="bank_name">
                                        <option value="none">- Pilih Bulan -</option>
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-xs-12">
                                <fieldset class="form-group">
                                    <label>&nbsp;</label>
                                    <select class="form-control" name="year" id="bank_name">
                                        <option value="none">- Pilih Tahun -</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-xl-1  col-xs-12">
                                <fieldset class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control btn btn-primary">Cari</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                    <h4 class="header-title m-t-0">Periode <b>{{$getDate->textMonth}}</b></h4>
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Stockist</th>
                                <th>Nominal Belanja (Rp.)</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>###</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($getData != null)
                                <?php $no = 0; ?>
                                @foreach($getData as $row)
                                    <?php
                                        $no++;
                                        $status = 'konfirmasi pembayaran';
                                        $label = 'info';
                                        if($row->status == 1){
                                            $status = 'proses stockist';
                                            $label = 'info';
                                        }
                                        if($row->status == 2){
                                            $status = 'tuntas';
                                            $label = 'success';
                                        }
                                        if($row->status == 10){
                                            $status = 'batal';
                                            $label = 'danger';
                                        }
                                        $buy = 'Belum';
                                        if($row->buy_metode == 1){
                                            $buy = 'COD';
                                        }
                                        if($row->buy_metode == 2){
                                            $buy = 'Transfer Bank';
                                        }
                                        if($row->buy_metode == 3){
                                            $buy = 'eIDR';
                                        }
                                    ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{date('d-m-Y', strtotime($row->sale_date))}}</td>
                                        <td>{{$row->user_code}}</td>
                                        <td>{{number_format($row->sale_price, 0, ',', ',')}}</td>
                                        <td>
                                                <span class="label label-{{$label}}">{{$status}}</span>
                                        </td>
                                        <td>
                                                <span class="label label-info">{{$buy}}</span>
                                        </td>
                                        <td>
                                            <a class="label label-primary" href="{{ URL::to('/') }}/m/pembayaran/{{$row->id}}">detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop
@section('styles')
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable();
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false
        });
    } );
    
</script>
@stop