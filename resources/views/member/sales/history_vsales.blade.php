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
                        <form class="row" method="get" action="/m/history/vshoping">
                            {{ csrf_field() }}
                                <div class="col-xl-12 col-xs-12">
                                    <fieldset>
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
                                <div class="col-xl-12 col-xs-12">
                                    <fieldset>
                                        <label>&nbsp;</label>
                                        <select class="form-control" name="year" id="bank_name">
                                            <option value="none">- Pilih Tahun -</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-12  col-xs-12">
                                    <fieldset>
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control btn btn-success">Cari</button>
                                    </fieldset>
                                </div>
                        </form>
                    </div>
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Vendor</th>
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
                                                        $status = 'proses vendor';
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
                                                        <a class="label label-primary" href="{{ URL::to('/') }}/m/vpembayaran/{{$row->id}}">detail</a>
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
    $(document).ready(function() {
        $('#datatable').DataTable();
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false
        });
    } );
    
</script>
@stop