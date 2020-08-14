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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Detail Transaksi</h5>
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
                        <input type="hidden" name="id" value="{{$getData->id}}">

                        <div class="row">
                            <label class="col-md-2 col-form-label">Kode Transaksi</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="ppob_code" id="ppob_code" disable="" autocomplete="off" value="{{$getData->ppob_code}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 col-form-label">Jenis Transaksi</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="message" disable="" autocomplete="off" value="{{$getData->message}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 col-form-label">Harga</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="member_price" disable="" autocomplete="off" value="{{number_format($getData->ppob_price, 0, ',', '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 col-form-label">Alamt Tron</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="code" disable="" autocomplete="off" value="{{$getData->tron_transfer}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <a type="submit" class="btn btn-muted"  href="/adm/list/ppob-transaction/eidr">Kembali</a>
                                <button type="submit" class="btn btn-success" id="submitBtn" data-toggle="modal" data-target="#popUp" onclick="inputSubmit()">Approve</button>
                                <button type="submit" class="btn btn-danger" id="submitBtn" data-toggle="modal" data-target="#popUpReject" onclick="rejectSubmit()">Reject</button>
                            </div>
                        </div>
                        <div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                         <div class="modal fade" id="popUpReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
                            <div class="modal-dialog" role="document" id="rejectDetail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
           function inputSubmit(){
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/ajax/adm/cek/ppob-transaction/{{$getData->id}}/1",
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }
           
           function rejectSubmit(){
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/ajax/adm/cek/ppob-transaction/{{$getData->id}}/2",
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
@stop
