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
                        <div class="table-responsive">
                            @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }}
                            </div>
                            @endif
                            <br>
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Pembeli</th>
                                        <th>Nominal / Status</th>
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
                                                        $label = 'warning';
                                                    }
                                                    if($row->status == 2){
                                                        $status = 'tuntas';
                                                        $label = 'success';
                                                    }
                                                    if($row->status == 3){
                                                        $status = 'batal';
                                                        $label = 'danger';
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
                                                    $type = 'Pulsa';
                                                    if($row->type == 2){
                                                        $type = 'Paket Data';
                                                    }
                                                    if($row->type == 3){
                                                        $type = 'Token PLN';
                                                    }
//                                                    if($row->type == 4){
//                                                        $type = 'PLN Pascabayar';
//                                                    }
//                                                    if($row->type == 5){
//                                                        $type = 'TELKOM PSTN';
//                                                    }
//                                                    if($row->type == 6){
//                                                        $type = 'HP Pascabayar';
//                                                    }
//                                                    if($row->type == 7){
//                                                        $type = 'BPJS';
//                                                    }
                                                    if($row->type > 3 && $row->type < 11){
                                                        $type = $row->message;
                                                    }
                                                    if($row->type == 21){
                                                        $type = 'GO PAY';
                                                    }
                                                    if($row->type == 22){
                                                        $type = 'e-Toll';
                                                    }
                                                    if($row->type == 23){
                                                        $type = 'OVO';
                                                    }
                                                    if($row->type == 24){
                                                        $type = 'DANA';
                                                    }
                                                    if($row->type == 25){
                                                        $type = 'LinkAja';
                                                    }
                                                    if($row->type == 26){
                                                        $type = 'Shopee Pay';
                                                    }
                                                ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{date('d-m-Y H:i', strtotime($row->created_at))}}</td>
                                        <td>{{$row->username}}<br><a class="badge badge-primary"
                                                href="{{ URL::to('/') }}/m/detail/vppob/{{$row->id}}">Detail</a>
                                            @if($row->status == 2)
                                            &nbsp;
                                            @if($row->type > 2 && $row->type < 11) <a class="badge badge-warning"
                                                href="{{ URL::to('/') }}/m/vinvoice/ppob/{{$row->id}}">Struk</a>
                                                @endif
                                                @endif
                                        </td>
                                        <td>{{number_format($row->ppob_price, 0, ',', ',')}}<br>{{$type}}<br><span
                                                class="label label-{{$label}}">{{$status}}</span></td>


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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
            $('#datatable').DataTable({
                lengthChange: false
            });

    } );
</script>
@stop