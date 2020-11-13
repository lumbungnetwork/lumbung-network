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

                    <?php
                    /*
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="card-box tilebox-one">
                                    <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                    <?php
                                        $text = '*Anda belum memenuhi Belanja Wajib';
                                        $color = 'danger';
                                        if($sum > 100000){
                                            $text = '*Anda telah memenuhi Belanja Wajib';
                                            $color = 'success';
                                        }
                                    ?>
                                    <h6 class="text-{{$color}} text-uppercase m-b-20">{{$text}}</h6>
                                    <h5 class="m-b-20">Rp. {{number_format($sum, 0, ',', ',')}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                     *
                     */
                    ?>

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
                                <form class="login100-form validate-form m-b-20" method="get" action="/m/list/buy-ppob">
                                    {{ csrf_field() }}
                                    <div class="row px-3">
                                        <div class="col-6">
                                            <fieldset>
                                                <label>Search</label>
                                                <select class="form-control" name="month">
                                                    <option value="none">- Bulan -</option>
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
                                        <div class="col-6">
                                            <fieldset>
                                                <label>&nbsp;</label>
                                                <select class="form-control" name="year">
                                                    <option value="none">- Tahun -</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2022">2022</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-12">
                                            <fieldset>
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control btn btn-success">Cari Data</button>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                                <br><br>
                                <div class="text-center"><h5 class="header-title m-t-0">Periode <b>{{$getDate->textMonth}}</b></h5></div>
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Vendor</th>
                                            <th>Produk</th>
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
                                                    $type = 'Pulsa';
                                                    if($row->type == 2){
                                                        $type = 'Paket Data';
                                                    }
                                                    if($row->type == 3){
                                                        $type = 'PLN';
                                                    }
                                                    if($row->type == 8){
                                                        $type = 'PDAM';
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

                                                    if($row->type > 3 && $row->type < 8){
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
                                                    $siVendor = 'Admin';
                                                    if($row->vendor_id != 1){
                                                        $siVendor = $row->user_code;
                                                    }
                                                ?>
                                                <tr>
                                                    <td>{{$no}}</td>
                                                    <td>{{date('d-m-Y', strtotime($row->ppob_date))}}<br><span class="label label-{{$label}}">{{$status}}</span></td>
                                                    <td>{{$siVendor}}<br><a class="label label-primary" href="{{ URL::to('/') }}/m/detail/buy-ppob/{{$row->id}}">detail</a>
                                                        @if($row->type > 2 && $row->type < 8)
                                                        @if($row->status == 2)
                                                        <a class="label label-primary" href="{{ URL::to('/') }}/m/invoice/ppob/{{$row->id}}">pdf</a>
                                                        @endif
                                                        @endif</td>
                                                    <td>{{$row->message}}</td>


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
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
            $('#datatable').DataTable({
                lengthChange: false
            });

    } );
</script>
@stop
