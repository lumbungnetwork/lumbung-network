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
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="card-box tilebox-one">
                                    <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                    <h6 class="text-muted text-uppercase m-b-20">Report Vendor</h6>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="table-responsive">
                                @if ( Session::has('message') )
                                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        {{  Session::get('message')    }} 
                                    </div>
                                @endif
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>UserID</th>
                                            <th>Nominal Belanja (Rp.)</th>
                                            <th>Pembayaran</th>
                                            <th>Status</th>
                                            <th>###</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($getData != null)
                                            <?php $no = 0; ?>
                                            @foreach($getData as $row)
                                                <?php
                                                    $no++;
                                                    $status = 'proses pembeli';
                                                    $label = 'info';
                                                    if($row->status == 1){
                                                        $status = 'proses vendor';
                                                        $label = 'warning';
                                                    }
                                                    if($row->status == 2){
                                                        $status = 'tuntas';
                                                        $label = 'success';
                                                    }
                                                    if($row->status == 10){
                                                        $status = 'batal';
                                                        $label = 'danger';
                                                    }
                                                    $buy = 'proses pemilihan';
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
                                                            <span class="label label-info">{{$buy}}</span>
                                                    </td>
                                                    <td>
                                                            <span class="label label-{{$label}}">{{$status}}</span>
                                                    </td>
                                                    <td>
                                                        <a class="text-primary" href="{{ URL::to('/') }}/m/detail/vendor-report/{{$row->id}}">detail</a>
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
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
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
    <script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable();
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
        });
    } );
    
</script>
@stop
