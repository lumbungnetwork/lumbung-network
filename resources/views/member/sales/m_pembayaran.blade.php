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
                    <h6 class="mb-3">Keranjang Pembayaran Anda</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Harga Satuan (Rp.)</th>
                                        <th>Total Harga (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getDataSales != null)
                                    @foreach($getDataSales as $row)
                                    <tr>
                                        <td><b>{{number_format($row->amount, 0, ',', '')}}x</b> {{$row->name}}
                                            {{$row->ukuran}}</td>
                                        <td>{{number_format(($row->sale_price/$row->amount), 0, ',', ',')}}</td>
                                        <td>{{number_format($row->sale_price, 0, ',', ',')}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><b>Total</b></td>
                                        <td><b>{{number_format($getDataMaster->total_price, 0, ',', ',')}}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-sm-12">
                            @if($getDataMaster->status == 0)
                            <p class="card-text">Metode Pembayaran</p>
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <div class="radio radio-primary" style="margin-bottom: 15px;">
                                        <input type="radio" name="radio" id="radio1" value="1">
                                        <label for="radio1">
                                            Tunai
                                        </label>
                                    </div>
                                    @if($getStockistBank != null)
                                    <div class="radio radio-primary" style="margin-bottom: 15px;">
                                        <input type="radio" name="radio" id="radio2" value="2">
                                        <label for="radio2">
                                            Transfer
                                        </label>
                                        <label>{{$getStockistBank->bank_name}} a/n
                                            {{$getStockistBank->account_name}}</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"
                                                value="{{$getStockistBank->account_no}}" id="bank" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="copy-bank"
                                                    onclick="copy('bank')">Copy</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($getStockist->is_tron != null)
                                    <div class="radio radio-primary" style="margin-bottom: 15px;">
                                        <input type="radio" name="radio" id="radio3" value="3">
                                        <label for="radio3">
                                            eIDR
                                        </label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{$getStockist->tron}}"
                                                id="tron" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="copy-tron"
                                                    onclick="copy('tron')">Copy</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12">
                                    <a class="btn btn-dark" href="{{ URL::to('/') }}/m/history/vshoping">Kembali</a>
                                    <button type="submit" class="btn btn-success" id="submitBtn" data-toggle="modal"
                                        data-target="#confirmSubmit" onclick="inputSubmit()">Konfirmasi</button>
                                </div>
                            </div>
                            <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog"
                                aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document" id="confirmDetail">
                                </div>
                            </div>
                            @endif

                            @if($getDataMaster->status > 0)
                            <p class="card-text">Metode Pembayaran</p>
                            <div class="row">
                                <div class="col-md-12">
                                    @if($getDataMaster->buy_metode == 1)
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio1" value="1" checked="">
                                        <label for="radio1">
                                            COD
                                        </label>
                                    </div>
                                    @endif
                                    @if($getDataMaster->buy_metode == 2)
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio2" value="2" checked="">
                                        <label for="radio2">
                                            Transfer
                                        </label>
                                        <input class="form-control" type="text"
                                            placeholder="{{$getStockistBank->bank_name}} {{$getStockistBank->account_no}} a/n {{$getStockistBank->account_name}}"
                                            disabled="">
                                    </div>
                                    @endif
                                    @if($getDataMaster->buy_metode == 3)
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio3" value="3" checked="">
                                        <label for="radio3">
                                            eIDR
                                        </label>
                                        <input class="form-control" type="text" placeholder="{{$getStockist->tron}}"
                                            disabled="">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <a class="btn btn-dark" href="{{ URL::to('/') }}/m/history/shoping">Kembali</a>
                                </div>
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
@if($getDataMaster->status == 0)
<script>
    function inputSubmit(){
        var buy_metode = $('input[name=radio]:checked').val();
         $.ajax({
             type: "GET",
             url: "{{ URL::to('/') }}/m/cek/member-pembayaran?buy_metode="+buy_metode+"&sale_id="+{{$getDataMaster->id}},
             success: function(url){
                 $("#confirmDetail" ).empty();
                 $("#confirmDetail").html(url);
             }
         });
     }

    function confirmSubmit(){
       var dataInput = $("#form-add").serializeArray();
       $('#form-add').submit();
       $('#tutupModal').remove();
       $('#submit').remove();
    }

    function copy(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert("Berhasil menyalin: " + copyText.value);
    }

</script>
@endif
@stop
