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
                        <h4 class="page-title">Request Vendor Baru</h4>
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
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox1" type="checkbox">
                                    <label for="checkbox1">
                                        Saya telah memiliki 5 (tiga) Hak Usaha atas nama saya sendiri.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                    <label for="input_email">Sebutkan ketiga username Hak Usaha tersebut:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu1" name="hu1" autocomplete="off" required="" >
                                </fieldset>
                            </div>
                            <div class="col-xl-2 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu2" name="hu2" autocomplete="off" required="">
                                </fieldset>
                            </div>
                            <div class="col-xl-2 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu3" name="hu3" autocomplete="off" required="">
                                </fieldset>
                            </div>
                            <div class="col-xl-2 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu4" name="hu4" autocomplete="off" required="">
                                </fieldset>
                            </div>
                            <div class="col-xl-2 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu5" name="hu5" autocomplete="off" required="">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox2" type="checkbox">
                                    <label for="checkbox2">
                                        Saya menyanggupi modal belanja awal senilai minimal Rp 2.000.000,00
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox3" type="checkbox">
                                    <label for="checkbox3">
                                        Saya menyatakan bahwa di RW/Lingkungan tempat tinggal saya BELUM ADA Vendor Lumbung Network.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox4" type="checkbox">
                                    <label for="checkbox4">
                                        Saya telah membaca dan menyetujui <a href="#">Peraturan dan Kode Etik Vendor</a> Lumbung Network.
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Apply</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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

@section('javascript')
<script>
       function inputSubmit(){
           var syarat1 = 0;
           if($("#checkbox1").prop('checked') == true){
                var syarat1 = 1;
            }
            var syarat2 = 0;
           if($("#checkbox2").prop('checked') == true){
                var syarat2 = 1;
            }
            var syarat3 = 0;
           if($("#checkbox3").prop('checked') == true){
                var syarat3 = 1;
            }
            var syarat4 = 0;
           if($("#checkbox4").prop('checked') == true){
                var syarat4 = 1;
            }
            var hu1 = $("#hu1").val();
            var hu2 = $("#hu2").val();
            var hu3 = $("#hu3").val();
            var hu4 = $("#hu4").val();
            var hu5 = $("#hu5").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/req-vendor?syarat1="+syarat1+"&syarat2="+syarat2+"&syarat3="+syarat3+"&syarat4="+syarat4+"&hu1="+hu1+"&hu2="+hu2+"&hu3="+hu3+"&hu4="+hu4+"&hu5="+hu5,
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
        
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

</script>
@stop