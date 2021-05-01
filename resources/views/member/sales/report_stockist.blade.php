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
                        <div class="col-12">
                            <a href="{{ URL::to('/') }}/m/getpos" class="text-decoration-none float-right">
                                <div class="rounded icon-ppob text-center">
                                    <div class="box-icon bg-green text-center">
                                        <i class="mdi mdi-desktop-classic icon-menu"></i>
                                    </div>
                                    <dd>P.O.S</dd>
                                </div>
                            </a>
                        </div>
                        <div class="col-12">
                            <div class="card-box tilebox-one">
                                <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted m-b-20">Riwayat Penjualan</h6>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="table-responsive">

                            <table id="datatable" class="table" style="font-size: 14px;">
                                <thead>
                                    <th><em>Klik status untuk melihat detail</em></th>
                                </thead>
                                <tbody>
                                    @if($getData != null)
                                    @foreach($getData as $row)
                                    @php
                                    $status = 'Proses stockist';
                                    $label = 'warning';
                                    if($row->status == 2){
                                    $status = 'Tuntas';
                                    $label = 'success';
                                    }
                                    if($row->status == 10){
                                    $status = 'Batal';
                                    $label = 'danger';
                                    }
                                    $buy = 'proses pemilihan';
                                    if($row->buy_metode == 1){
                                    $buy = 'Tunai';
                                    }
                                    if($row->buy_metode == 2){
                                    $buy = 'Transfer Bank';
                                    }
                                    if($row->buy_metode == 3){
                                    $buy = 'eIDR';
                                    }
                                    @endphp
                                    <tr>

                                        <td style="display:block; box-sizing:border-box; clear:both">
                                            <div class="rounded-lg bg-light shadow px-3 py-2 mb-1">
                                                <small class="float-right">{{$row->created_at}}</small>
                                                <p class="mb-1">{{$row->username}}</p>

                                                <a class="btn btn-sm btn-{{$label}} float-right"
                                                    href="{{ URL::to('/') }}/m/detail/stockist-report/{{$row->id}}"><span
                                                        style="font-size: 14px;">{{$status}}</span></a>
                                                @if ($row->status == 2)
                                                <a class="mr-2 btn btn-sm btn-warning float-right"
                                                    href="{{ URL::to('/') }}/m/print-shopping-receipt/{{$row->id}}"><span
                                                        style="font-size: 14px;">Print Struk</span></a>
                                                @endif
                                                <dd>Rp{{number_format($row->sale_price)}}</dd>
                                            </div>
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
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<style>
    table {
        width: 100%;
        table-layout: fixed;
        overflow-wrap: break-word;
    }
</style>
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
            lengthChange: false,
            order: [[0, 'desc']],
        });

    } );

</script>
@stop