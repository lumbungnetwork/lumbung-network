@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
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
                                    <h6 class="text-muted text-uppercase m-b-20">History Konversi</h6>
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
                                    <th>Tgl. Konversi</th>
                                    <th>Jml. Konversi</th>
                                    <th>Admin Fee</th>
                                    <th>Jml. Transfer</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                    <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                        <?php
                                            $no++;
                                            $status = 'Tuntas';
                                            $label = 'success';
                                            if($row->status == 0){
                                                $status = 'Proses Transfer';
                                                $label = 'primary';
                                            }
                                            if($row->status == 1){
                                                $status = 'Tuntas';
                                                $label = 'success';
                                            }
                                            if($row->status == 2){
                                                $status = 'Reject';
                                                $label = 'danger';
                                            }
                                            $jml_WD = $row->wd_total + $row->admin_fee;
                                        ?>
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{date('d M Y', strtotime($row->wd_date))}}</td>
                                            <td>{{number_format($jml_WD, 0, ',', '.')}}</td>
                                            <td>{{number_format($row->admin_fee, 0, ',', '.')}}</td>
                                            <td>{{number_format($row->wd_total, 0, ',', '.')}}</td>
                                            <td><label class="label label-{{$label}}">{{$status}}</label></td>
                                            <td>{{$row->reason}}</td>
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
            $('#datatable').DataTable({
                lengthChange: false,
                searching:false
            });
        } );
    </script>
@stop
