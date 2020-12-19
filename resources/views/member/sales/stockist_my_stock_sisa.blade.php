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
                                <h6 class="text-muted text-uppercase m-b-20">Stock Lama</h6>
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

                            @php
                            $totalPrice = 0;
                            @endphp
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Produk</th>
                                        <th>Sisa Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getData != null)
                                    <?php $no = 0;  ?>
                                    @foreach($getData as $row)
                                    @if($row->hapus == 0 && $row->total_sisa > 0)
                                    <?php
                                        $no++;
                                    ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->ukuran}} {{$row->name}} @ Rp{{number_format($row->member_price)}}
                                        </td>
                                        <td>{{number_format($row->total_sisa, 0, ',', ',')}}</td>
                                    </tr>
                                    <?php $totalPrice +=  $row->total_sisa * $row->member_price;?>

                                    @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                            @if($totalPrice > 0)
                            <div class="rounded-lg bg-light shadow p-3">
                                <form id="form-claim" action="/m/claim-old-royalti" method="POST">
                                    @csrf
                                    <dd>Nilai Stock: Rp{{number_format($totalPrice)}}</dd>
                                    <h6>Sisa Royalti: Rp{{number_format($totalPrice * 4 / 100)}}</h6>
                                    <input type="hidden" name="amount" value="{{$totalPrice * 4 / 100}}">
                                </form>

                                <button class="btn btn-success" onclick="claim()">Claim</button>
                            </div>
                            @endif
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script type="text/javascript">
    function claim() {
        Swal.fire({
            title: 'Ajukan Claim?',
            text: "Data Stok lama anda akan dihapus, ketika sisa royalti dikembalikan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, setuju!',
            cancelButtonText: 'Jangan!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Sedang Memproses');
                Swal.showLoading();
                $('#form-claim').submit();
            }
        })
    }
</script>
@stop
