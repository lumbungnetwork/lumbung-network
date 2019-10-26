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
                        <h4 class="page-title">{{$headerTitle}}</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 card-box ">
                    @if ( Session::has('message') )
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }} 
                        </div>
                    @endif
                    <h4 class="header-title m-t-0">Keranjang Pembayaran Anda</h4>

                    <div class="p-20">
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
                                            <td><b>{{number_format($row->amount, 0, ',', '')}}x</b> {{$row->name}} {{$row->ukuran}}</td>
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
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-xl-6">
                                    <a class="btn btn-dark" href="{{ URL::to('/') }}/m/history/shoping">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop

@section('javascript')
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
    
</script>
@endif
@stop