@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')
<div class="main-panel">
    <?php //MENU HEADER  ?>
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <p class="navbar-brand">{{$headerTitle}}</p>
            </div>
        </div>
    </nav>
    <?php //MENU CONTENT  ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-user">
                    <div class="card-header">
                        <h5 class="card-title">Detail Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        @if ( Session::has('message') )
                        <div class="widget-content mt10 mb10 mr15">
                            <div class="alert alert-{{ Session::get('messageclass') }}">
                                <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }} 
                            </div>
                        </div>
                        @endif
                        @if($getData->status == 1)
                        <div class="alert alert-info alert-with-icon alert-dismissible fade show" data-notify="container">
                            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="nc-icon nc-simple-remove"></i>
                            </button>
                            <span data-notify="icon" class="nc-icon nc-bell-55"></span>
                            <span data-notify="message">Status Pengiriman paket sudah selesai <a href="{{ URL::to('/') }}/adm/list/kirim-paket" class="text-muted">kembali</a></span>
                        </div>
                        @endif
                        @if($getData->status == 2)
                        <div class="alert alert-info alert-with-icon alert-dismissible fade show" data-notify="container">
                            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="nc-icon nc-simple-remove"></i>
                            </button>
                            <span data-notify="icon" class="nc-icon nc-bell-55"></span>
                            <span data-notify="message">Status Pengiriman paket dibatalkan <a href="{{ URL::to('/') }}/adm/list/kirim-paket">kembali</a></span>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" disabled="" value="{{$getData->name}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input type="text" class="form-control" disabled="" value="{{$getData->hp}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label>Total Pin</label>
                                    <input type="number" class="form-control" disabled="" value="{{$getData->total_pin}}">
                                </div>
                            </div>
                            <div class="col-md-9 pl-1">
                                <div class="form-group">
                                    <label>Alamat Kirim</label>
                                    <textarea class="form-control" id="alamat_kirim" disabled="" autocomplete="off">{{$getData->alamat_kirim}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 pr-1">
                                <div class="form-group">
                                    <label>Nama Kurir</label>
                                    <?php
                                        $kurirName = '';
                                        if($getData->kurir_name != null){
                                            $kurirName = $getData->kurir_name;
                                        }
                                    ?>
                                    <input type="text" class="form-control" name="kurir_name" id="kurir_name" required="true" autocomplete="off" value="{{$kurirName}}">
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>No. Resi</label>
                                    <?php
                                        $no_resi = '';
                                        if($getData->no_resi != null){
                                            $no_resi = $getData->no_resi;
                                        }
                                    ?>
                                    <input type="text" class="form-control" name="no_resi" id="no_resi" required="true" autocomplete="off" value="{{$no_resi}}">
                                </div>
                            </div>
                        </div>
                        @if($getData->status == 0)
                        <input type="hidden" name="cekId" id="cekId" value="{{$getData->id}}" >
                        <input type="hidden" name="cekUserId" id="cekUserId" value="{{$getData->user_id}}" >
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@if($getData->status == 0)
@section('javascript')
<script>
    function inputSubmit(){
        var cekId = $("#cekId").val();
        var cekUserId = $("#cekUserId").val();
        var kurir_name = $("#kurir_name").val();
        var no_resi = $("#no_resi").val();
         $.ajax({
             type: "GET",
             url: "{{ URL::to('/') }}/ajax/adm/cek/kirim-paket?cekId="+cekId+"&cekUserId="+cekUserId+"&kurir_name="+kurir_name+"&no_resi="+no_resi,
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
@endif