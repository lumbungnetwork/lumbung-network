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
                        <h4 class="page-title">Order Paket</h4>
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
                                                <h5>Detail Paket</h5>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="pull-xs-left m-t-30">
                                                    <address>
                                                      <strong>Data Pengorder:</strong>
                                                      <br>
                                                      Nama : <strong>{{$getData->name_user}}</strong>
                                                      <br>
                                                      Email: <strong>{{$getData->email}}</strong>
                                                      <br>
                                                      No. HP: <strong>{{$getData->hp}}</strong>
                                                      </address>
                                                </div>
                                                <div class="pull-xs-right m-t-30">
                                                    <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getData->created_at))}}</p>
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
                                                                <th>No</th>
                                                            <th>Nama Paket</th>
                                                            <th>Deskripsi</th>
                                                            <th>Jumlah Pin</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{$getData->name}}</td>
                                                                <td>{{$getData->short_desc}}</td>
                                                                <td>{{$getData->total_pin}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
<!--                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                
                                            </div>
                                            <?php 
                                                $price = $getData->total_pin * $pinSetting->price;
                                            ?>
                                            <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-3">
                                                <hr>
                                                <h3 class="text-xs-right">Rp. {{number_format($price, 0, ',', ',')}}</h3>
                                            </div>
                                        </div>-->
                                        <hr>
                                        <div class="hidden-print">
                                            <div class="pull-xs-right">
                                                <input type="hidden" value="{{$getData->id}}" name="id_paket" id="id_paket">
                                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Aktifasi</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                            <div class="modal-dialog" role="document" id="confirmDetail">
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

@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
<script>
       function inputSubmit(){
           var id_paket = $("#id_paket").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-order?id_paket="+id_paket,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
        }

</script>
@stop