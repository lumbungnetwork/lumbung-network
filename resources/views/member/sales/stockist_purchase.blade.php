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
                                    <h6 class="text-muted text-uppercase m-b-20">List Transaksi Stockist</h6>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="table-responsive">
                                    @if ( Session::has('message') )
                                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
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
                                                <th>Tgl</th>
                                                <th>Jml. Harga</th>
                                                <th>Detail</th>
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
                                                $status = 'batal';
                                                $label = 'danger';
                                                if($row->status == 0){
                                                    $status = 'konfirmasi';
                                                    $label = 'info';
                                                }
                                                if($row->status == 1){
                                                    $status = 'proses admin';
                                                    $label = 'info';
                                                }
                                                if($row->status == 2){
                                                    $status = 'tuntas';
                                                    $label = 'success';
                                                }
                                                ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{date('d M Y', strtotime($row->created_at))}}</td>
                                                <td>{{number_format($row->price, 0, ',', ',')}}</td>
                                                <td>
                                                    @foreach($row->detail_all as $rowAll)
                                                        <p style="margin: 0;">{{$rowAll->ukuran}} {{$rowAll->name}}</p>
                                                        <?php $harga = $rowAll->qty * $rowAll->price ?>
                                                        <p style="margin: 0;">{{number_format($rowAll->qty, 0, ',', '')}}x @ {{number_format($rowAll->price, 0, ',', ',')}} </p>
                                                        <p style="margin-bottom: 5px;"><b>Rp. {{number_format($harga, 0, ',', ',')}}</b></p>
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td><label class="label label-{{$label}}">{{$status}}</label></td>
                                                <td>
                                                    <a rel="tooltip" title="View" class="text-primary" href="{{ URL::to('/') }}/m/purchase/detail-stock/{{$row->id}}">detail</a>
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
