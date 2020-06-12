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
                    <h6 class="mb-3">Request Vendor Input Stock</h6>
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
                                @if($getDataMaster->buy_metode == 2)
                                    <br>
                                    Nama Rekening: <strong>{{$getDataMaster->account_name}}</strong>
                                    <br>
                                    Nama Bank: <strong>{{$getDataMaster->bank_name}}</strong>
                                    <br>
                                    No. Rekening: <strong>{{$getDataMaster->account_no}}</strong>
                                @endif
                                @if($getDataMaster->buy_metode == 3)
                                    <br>
                                    Nama: <strong>{{$getDataMaster->tron}}</strong>
                                    <br>
                                    Alamat Tron: <strong>{{$getDataMaster->tron_transfer}}</strong>
                                @endif
                                @if($getDataMaster->buy_metode == 0)
                                <div class="radio radio-primary">
                                    <input type="radio" name="radio" id="radio1" value="2">
                                    <label for="radio1">
                                        BRI a/n <b>PT LUMBUNG MOMENTUM BANGSA</b>
                                        <br>
                                        033601001795562
                                    </label>
                                </div>
                                <div class="radio radio-primary">
                                    <input type="radio" name="radio" id="radio2" value="3">
                                    <label for="radio2">
                                        eIDR
                                        <br>
                                        <b>TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ</b>
                                    </label>
                                </div>
                                @endif
                            </address>
                            <?php
                                $status = 'batal';
                                $label = 'danger';
                                if($getDataMaster->status == 0){
                                    $status = 'konfirmasi';
                                    $label = 'info';
                                }
                                if($getDataMaster->status == 1){
                                    $status = 'proses admin';
                                    $label = 'info';
                                }
                                if($getDataMaster->status == 2){
                                    $status = 'confirmed';
                                    $label = 'success';
                                }
                            ?>
                            
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getDataMaster->created_at))}}</p>
                            <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-{{$label}}">{{$status}}</span></p>
                        </div>
                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead class="bg-faded">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getDataItem != null)
                                        <?php $no = 0; ?>
                                        @foreach($getDataItem as $row)
                                            <?php $no++; ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$row->ukuran}} {{$row->name}}</td>
                                                <td>{{number_format($row->qty, 0, ',', '')}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-md-9 col-sm-6 col-xs-6"></div>
                        <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-9">
                            <p class="text-xs-right"><b>Total Harga : </b> Rp. {{number_format($getDataMaster->price, 0, ',', ',')}}</p>
                            <?php
                                $royalti = 4/100 * $getDataMaster->price; //2% bisa dinamis nanti
                            ?>
                            <p class="text-xs-right"><b>Total Royalti : </b>Rp. {{number_format($royalti, 0, ',', ',')}}</p>
                            <hr>
                            <p class="text-xs-right">Total yang harus ditransfer</p>
                            <h3 class="text-xs-right">Rp. {{number_format($royalti, 0, ',', ',')}}</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 col-sm-6 col-xs-6"></div>
                        <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-9">
                            @if($getDataMaster->status == 0)
                        <div class="hidden-print">
                            <div class="pull-xs-right">
                                <input type="hidden" value="{{$getDataMaster->id}}" name="id_master" id="id_master">
                                <input type="hidden" value="{{$royalti}}" name="royalti" id="royalti">
                                <button type="submit" class="btn btn-danger"  id="submitBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Confirm</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        @endif
                        @if($getDataMaster->status == 1 || $getDataMaster->status == 2 || $getDataMaster->status == 10)
                        <div class="hidden-print">
                            <div class="pull-xs-right">
                                <a  class="btn btn-success" href="{{ URL::to('/') }}/m/purchase/list-vstock">Kembali</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        </div>
                        
                        @endif
                    </div>
                        
                </div>
                
                <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" id="confirmDetail">
                    </div>
                </div>
                <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
    @if($getDataMaster->status == 0)
    <script>
           function inputSubmit(){
                var id_master = $("#id_master").val();
                var metode = $('input[name=radio]:checked').val(); 
                var royalti = $("#royalti").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add-vstock?id_master="+id_master+"&metode="+metode+"&royalti="+royalti,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }
           
           function rejectSubmit(){
                var id_master = $("#id_master").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-vstock?id_master="+id_master,
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