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
                    <h5 class="mb-3">Invoice # <br>
                        <small>{{$getData->transaction_code}}</small>
                    </h5>
                    @if ( Session::has('message') )
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }} 
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <address>
                                @if($getData->bank_perusahaan_id != null)
                                    @if($getData->is_tron == 0)
                                        <br>
                                        Nama Rekening: <strong>{{$bankPerusahaan->account_name}}</strong>
                                        <br>
                                        Nama Bank: <strong>{{$bankPerusahaan->bank_name}}</strong>
                                        <br>
                                        No. Rekening: <strong>{{$bankPerusahaan->account_no}}</strong>
                                    @endif
                                    @if($getData->is_tron == 1)
                                        <br>
                                        Nama: <strong>{{$bankPerusahaan->tron_name}}</strong>
                                        <br>
                                        Alamat Tron: <strong>{{$bankPerusahaan->tron}}</strong>
                                    @endif
                                @endif
                                @if($getData->bank_perusahaan_id == null)
                                <?php $no = 1; ?>
                                @foreach($bankPerusahaan as $rowBank)
                                    <?php $no++; ?>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio{{$no}}" value="0_{{$rowBank->id}}">
                                        <label for="radio{{$no}}">
                                            {{$rowBank->bank_name}} a/n <b>{{$rowBank->account_name}}</b>
                                            <br>
                                            {{$rowBank->account_no}}
                                        </label>
                                    </div>
                                @endforeach
                                <?php $no1 = count($bankPerusahaan) + 1; ?>
                                @foreach($tronPerusahaan as $rowTron)
                                    <?php $no1++; ?>
                                    <div class="radio radio-primary">
                                        <input type="radio" name="radio" id="radio{{$no1}}" value="1_{{$rowTron->id}}">
                                        <label for="radio{{$no1}}">
                                            {{$rowTron->tron_name}}
                                            <br>
                                            <b>{{$rowTron->tron}}</b>
                                        </label>
                                    </div>
                                @endforeach
                                @endif
                            </address>
                        </div>
                    </div>
                </div>
                <?php
                    $status = 'Tuntas';
                    $label = 'success';
                    if($getData->status == 0){
                        $status = 'Proses Transfer';
                        $label = 'danger';
                    }
                    if($getData->status == 1){
                        $status = 'Menunggu Konfirmasi';
                        $label = 'warning';
                    }
                    if($getData->status == 3){
                        $status = 'Batal';
                        $label = 'danger';
                    }
                ?>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getData->created_at))}}</p>
                            <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-{{$label}}">{{$status}}</span></p>
                        </div>
                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead class="bg-faded">
                                    <tr>
                                        <th>#</th>
                                    <th>Jumlah Pin</th>
                                    <th>Harga (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{$getData->total_pin}}</td>
                                        <td>{{number_format($getData->price, 0, ',', ',')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                                $total = $getData->price + $getData->unique_digit;
                            ?>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-6">
                            <p class="text-xs-right"><b>Sub-total:</b> Rp. {{number_format($getData->price, 0, ',', ',')}}</p>
                            <p class="text-xs-right"><b>Kode Unik:</b> {{number_format($getData->unique_digit, 0, ',', ',')}}</p>
                            <hr>
                            <h3 class="text-xs-right">Rp. {{number_format($total, 0, ',', ',')}}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        
                        <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-9">
                            @if($getData->status == 0)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <input type="hidden" value="{{$getData->id}}" name="id_trans" id="id_trans">
                                    <button type="submit" class="btn btn-danger"  id="submitBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                    <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endif
                            @if($getData->status == 1 || $getData->status == 2 || $getData->status == 3)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <a  class="btn btn-success" href="{{ URL::to('/') }}/m/list/transactions">Kembali</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="confirmDetail">
                        </div>
                    </div>
                    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="rejectDetail">
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
<link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
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
    <script src="{{ asset('asset_member/js/jquery.cart.min.js') }}"></script>
    @if($getData->status == 0)
    <script>
           function inputSubmit(){
                var id_trans = $("#id_trans").val();
//                var id_bank = $("#bank_name").val();
                var id_bank = $('input[name=radio]:checked').val(); 
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add-transaction?id_trans="+id_trans+"&id_bank="+id_bank,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }
           
           function rejectSubmit(){
                var id_trans = $("#id_trans").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-transaction?id_trans="+id_trans,
                     success: function(url){
                         $("#rejectDetail" ).empty();
                         $("#rejectDetail").html(url);
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