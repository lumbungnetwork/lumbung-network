@extends('layout.member.new_main')
@section('content')

<div class="wrapper">


    <!-- Page Content -->
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
                <div class="rounded-lg p-3">
                    <div class="rounded-lg bg-white p-3 shadow">
                        <p>Saldo Bonus Royalti Anda:</p>
                        <?php
                                        $saldoRO = $dataAll->total_bonus - $dataAll->total_wd - $dataAll->total_tunda - $dataAll->total_fee_admin;
                                        if($saldoRO < 0){
                                            $saldoRO = 0;
                                        }
                                    ?>
                        <h5 class="text-warning">Rp {{number_format($saldoRO, 0, ',', '.')}}</h5>
                    </div>

                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Withdraw ke Rekening Bank</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-xl-8 col-xs-12">
                            <fieldset class="form-group">
                                <label for="input_jml">Jumlah (Rp.)</label>
                                <input type="text" inputmode="numeric" pattern="[0-9]*"
                                    class="form-control allownumericwithoutdecimal" id="input_jml" name="jml_wd"
                                    autocomplete="off" placeholder="Minimum WD Rp20.000,-">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>Admin Fee (Rp.)</label>
                                <input type="text" class="form-control" disabled="" value="6.500">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <button type="submit" class="btn btn-success" id="submitBtn" data-toggle="modal"
                                data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                        </div>
                    </div>
                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="confirmDetail">
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Withdraw via eIDR</h6>

                    <div class="row">
                        <div class="col-xl-8 col-xs-12">
                            <fieldset class="form-group">
                                <label for="input_jml_eidr">Jumlah (Rp.)</label>
                                <input type="text" inputmode="numeric" pattern="[0-9]*"
                                    class="form-control allownumericwithoutdecimal" id="input_jml_eidr"
                                    name="jml_wd_eidr" autocomplete="off" placeholder="Minimum WD Rp10.000,-">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>Admin Fee (Rp.)</label>
                                <input type="text" class="form-control" disabled="" value="3.000">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <button type="submit" class="btn btn-success" id="submitBtneIDR" data-toggle="modal"
                                data-target="#confirmSubmit" onClick="inputSubmiteIDR()">Submit</button>
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
           var input_jml_wd = $("#input_jml").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd-royalti?input_jml_wd="+input_jml_wd,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }

       function inputSubmiteIDR(){
           var input_jml_wd = $("#input_jml_eidr").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd-royalti-eidr?input_jml_wd="+input_jml_wd,
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

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

</script>
@stop
