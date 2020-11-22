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
                    <h6 class="mb-3">Request Vendor Baru</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1">
                                    Saya telah memiliki 5 (lima) Hak Usaha atas nama saya sendiri, di mana 4 (empat) di
                                    antaranya saya sponsori langsung.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="input_email">Berikut adalah username dari 5 akun tersebut:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="hu1" name="hu1"
                                    placeholder="username01 (Stockist)" autocomplete="off" required>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="hu2" name="hu2"
                                    value={{$dataUser->user_code}} autocomplete="off" readonly>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="hu3" name="hu3" placeholder="username03"
                                    autocomplete="off" required="">
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="hu4" name="hu4" placeholder="username04"
                                    autocomplete="off" required="">
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="hu5" name="hu5" placeholder="username05"
                                    autocomplete="off" required="">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox3" type="checkbox">
                                <label for="checkbox3">
                                    Saya siap untuk mengikuti pelatihan dan arahan dari Tim Delegasi Lumbung Network di
                                    daerah saya.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox4" type="checkbox">
                                <label for="checkbox4">
                                    Saya telah membaca dan menyetujui <a href="https://lumbung.network/about">Peraturan
                                        dan Kode Etik</a> Lumbung Network.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="checkbox checkbox-success">
                                <input id="checkbox5" type="checkbox">
                                <label for="checkbox5">
                                    Saya bersedia untuk menjaminkan 100 LMB sebagai kontribusi saya kepada komunitas
                                    Lumbung Network selama saya aktif sebagai seorang Vendor.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="rounded-lg shadow bg-white p-3">
                                <h6 class="text-muted mt-2">Silakan transfer 100 LMB ke alamat TRON di bawah ini:</h6>
                                <input size="45" type="text" id="eidr-addr"
                                    style="border: 0; font-size:12px; font-weight:200;"
                                    value="TPu2RaFyEkujmC6K1MtP3LwcunNEmRhxgf" readonly>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="copy('eidr-addr')">Copy</button>
                                <br>
                                <h6 class="text-muted mt-4">Lalu Copy dan Paste (tempel) Hash dari transaksi tersebut ke
                                    kolom di bawah ini:</h6>
                                <div class="form-group">
                                    <textarea onchange="cleanHash()" class="form-control" style="font-size: 11px;"
                                        id="hash" rows="2" name="tron_transfer" placeholder="Transaction Hash #"
                                        \></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-block btn-success mt-3" id="submitBtn"
                                data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Apply</button>
                        </div>
                    </div>
                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    function inputSubmit(){
           var syarat1 = 0;
           if($("#checkbox1").prop('checked') == true){
                var syarat1 = 1;
            }

            var syarat3 = 0;
           if($("#checkbox3").prop('checked') == true){
                var syarat3 = 1;
            }
            var syarat4 = 0;
           if($("#checkbox4").prop('checked') == true){
                var syarat4 = 1;
            }
            var syarat5 = 0;
           if($("#checkbox5").prop('checked') == true){
                var syarat5 = 1;
            }
            var hash = $("#tron_transfer").val();

            var hu1 = $("#hu1").val();
            var hu2 = $("#hu2").val();
            var hu3 = $("#hu3").val();
            var hu4 = $("#hu4").val();
            var hu5 = $("#hu5").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/req-vendor?syarat1="+syarat1+"&syarat3="+syarat3+"&syarat4="+syarat4+"&syarat5="+syarat5+"&hu1="+hu1+"&hu2="+hu2+"&hu3="+hu3+"&hu4="+hu4+"&hu5="+hu5+"&hash="+hash,
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

        function copy(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Berhasil menyalin: " + copyText.value);
        }

        function cleanHash() {
            if ($("#hash").val().includes("tronscan")) {
                let hashOnly = $("#hash").val().split("/")[5];
                $("#hash").val(hashOnly);
            }
        };

</script>
@stop
