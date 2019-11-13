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
                        <h4 class="page-title">Alamat TRON (TRON Address)</h4>
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
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="tron">Masukan Alamat Tron Anda</label>
                                        <input type="text" class="form-control" id="tron" name="tron" autocomplete="off">
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="card card-block" style="border: none;">
                                    <h4 class="card-title text-danger">Perhatian!!!</h4>
                                    <p class="card-text">
                                        Pastikan anda menggunakan alamat TRON yang benar-benar anda kuasai sepenuhnya 
                                        (Anda memiliki Private Key alamat tersebut).
                                        Jangan menggunakan alamat TRON dari Exchanger seperti Indodax/Binance!!
                                    </p>
                                    <p class="card-text">
                                        Lumbung Network merekomendasikan aplikasi TronWallet (Download di AppStore atau PlayStore).
                                    </p>
                                    <p class="card-text">
                                        Anda hanya bisa memasukan alamat TRON 1 kali saja. Apabila anda ingin mengganti alamat TRON anda, 
                                        anda harus mengajukan permohonan tertulis kepada tim BackOffice.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop

@section('javascript')
<script>
       function inputSubmit(){
           var tron = $("#tron").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-tron?tron="+tron,
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
@stop