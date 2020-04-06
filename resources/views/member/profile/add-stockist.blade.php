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
                        <h6 class="mb-3">Request Stockist Baru</h6>
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
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox1" type="checkbox">
                                    <label for="checkbox1">
                                        Saya telah memiliki 3 (tiga) Hak Usaha atas nama saya sendiri.
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
                            <div class="col-xl-3 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu1" name="hu1" autocomplete="off" required="" >
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu2" name="hu2" autocomplete="off" required="">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-xs-12">
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="hu3" name="hu3" autocomplete="off" required="">
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
                                        Saya menyatakan bahwa di RW/Lingkungan tempat tinggal saya BELUM ADA Stokis Lumbung Network.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <div class="checkbox checkbox-success">
                                    <input id="checkbox4" type="checkbox">
                                    <label for="checkbox4">
                                        Saya telah membaca dan menyetujui <a href="#">Peraturan dan Kode Etik Stokis</a> Lumbung Network.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Apply</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
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
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/req-stockist?syarat1="+syarat1+"&syarat2="+syarat2+"&syarat3="+syarat3+"&syarat4="+syarat4+"&hu1="+hu1+"&hu2="+hu2+"&hu3="+hu3,
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
