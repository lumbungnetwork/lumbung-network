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
                        <h4 class="page-title">Invoice</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="panel-body">
                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <h5>Invoice # <br>
                                                    <small>{{$getData->transaction_code}}</small>
                                                </h5>
                                            </div>
                                            @if($getData->bank_perusahaan_id == null)
                                            <div style="text-align: center;">
                                                <h3>Pilih Metode Pembayaran Anda</h3>
                                            </div>
                                            @endif
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="pull-xs-left m-t-30">
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
                                                <div class="pull-xs-right m-t-30">
                                                    <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getData->created_at))}}</p>
                                                    <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-{{$label}}">{{$status}}</span></p>
                                                </div>
                                            </div><!-- end col -->
                                        </div>
                                        <!-- end row -->

                                        <div class="m-h-50"></div>

                                        <div class="row">
                                            <div class="col-xs-12">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                
                                            </div>
                                            <?php
                                                $total = $getData->price + $getData->unique_digit;
                                            ?>
                                            <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-3">
                                                <p class="text-xs-right"><b>Sub-total:</b> Rp. {{number_format($getData->price, 0, ',', ',')}}</p>
                                                <p class="text-xs-right"><b>Kode Unik:</b> {{number_format($getData->unique_digit, 0, ',', ',')}}</p>
                                                <hr>
                                                <h3 class="text-xs-right">Rp. {{number_format($total, 0, ',', ',')}}</h3>
                                            </div>
                                        </div>
                                        <hr>
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
            </div>
        </div>
    </div>
</div>

@include('layout.member.footer')
@stop

@section('javascript')
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