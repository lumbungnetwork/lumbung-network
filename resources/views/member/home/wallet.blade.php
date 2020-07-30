@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
        <div id="content">
            <div class="bg-gradient-sm">
                <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                    <div class="container">
                        <a class="navbar-brand" href="#">
                        </a>
                        <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                            <i class="fas fa-power-off text-danger icon-bottom"></i>
                        </a>
                    </div>
                </nav>
            </div>
          <?php
            $saldo = $dataAll->total_bonus - $dataAll->total_wd - $dataAll->total_tunda - $dataAll->total_fee_admin - ($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr + $dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr);
            if($saldo > -5000 && $saldo <= 0){
                $saldo = 0;
            }
            ?>
            <div class="mt-min-10">
                <div class="container">
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Saldo Bonus:
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($saldo, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/req/wd" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-cash-refund icon-menu"></i>
                                        </div>
                                        <dd>Withdraw</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/history/wd" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-history icon-menu"></i>
                                        </div>
                                        <dd>Riwayat WD</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Proses Transfer:
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($dataAll->total_tunda + $dataAll->fee_tunda, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Total Ditransfer:
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($dataAll->total_wd + $dataAll->fee_tuntas, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-3 mb-3">
                        <h6 class="mb-3">eIDR</h6>
                        <div class="form-group">
                            <label for="exampleInputtext1" class="f-12 label">
                                Konversi Saldo Bonus ke eIDR
                                <a href="" class="text-decoration-none" data-toggle="modal" data-target="#help-konversi"><i class="mdi mdi-help-circle-outline text-warning"></i></a>
                            </label>
                            <div class="form-row">
                                <div class="form-group col-7">
                                    <input type="text" class="form-control form-control-sm allownumericwithoutdecimal" id="input_jml" name="jml_wd" aria-describedby="textHelp" autocomplete="off" placeholder="Minimum Withdraw Rp. 20.000 Admin Fee (Rp.5000)">
                                </div>
                                <div class="form-group col-5">
                                    <button type="submit" class="btn btn-sm btn-success btn-block" id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()"> <span class="f-14"> Konversi </span></button>
                                    <a href="{{ URL::to('/') }}/m/history/wd-eidr" class="text-decoration-none">
                                        <small class="f-12"><i class="mdi mdi-history"></i> Riwayat Konversi</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Proses Konversi:
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($dataAll->total_tunda_eidr + $dataAll->fee_tunda_eidr, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Total Dikonversi:
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($dataAll->total_wd_eidr + $dataAll->fee_tuntas_eidr, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputtext1" class="f-12 label">
                                Request TopUp eIDR
                                <a href="" class="text-decoration-none" data-toggle="modal" data-target="#help-topup"><i class="mdi mdi-help-circle-outline text-warning"></i></a>
                            </label>
                            <div class="form-row">
                                <div class="form-group col-7">
                                    <input type="text" class="form-control form-control-sm allownumericwithoutdecimal" id="input_jml_topup" aria-describedby="textHelp" name="input_topup" autocomplete="off" placeholder="Minimum Top Up Rp. 20.000">
                                </div>
                                <div class="form-group col-5">
                                    <button type="submit" class="btn btn-sm btn-success btn-block" id="submitBtn" data-toggle="modal" data-target="#confirmSubmitTopUp" onClick="inputSubmitTopUp()"> <span class="f-14"> TopUp </span></button>
                                    <a href="{{ URL::to('/') }}/m/history/topup-saldo" class="text-decoration-none">
                                        <small class="f-12"><i class="mdi mdi-history"></i> Riwayat Top Up</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-3 mb-3">
                        <h6 class="mb-3">Bonus</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Saldo Bonus:
                                    </p>
                                    <h6 class="text-warning">Rp {{number_format($saldo, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/sponsor/bonus" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-console-network-outline icon-menu"></i>
                                        </div>
                                        <dd>Sponsor</dd>
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/binary/bonus" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-axis-arrow icon-menu"></i>
                                        </div>
                                        <dd>Pairing</dd>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="rounded-lg shadow-sm p-2">
                                    <p>
                                        Saldo Bonus Royalti:
                                    </p>
                                    <?php
                                        $saldoRO = $dataAll->total_bonus_ro - $dataAll->total_wd_ro - $dataAll->total_tunda_ro - $dataAll->total_fee_admin_ro;
                                        if($saldo < 0){
                                            $saldo = 0;
                                        }
                                    ?>
                                    <h6 class="text-warning">Rp {{number_format($saldoRO, 0, ',', '.')}}</h6>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <a href="{{ URL::to('/') }}/m/req/wd-royalti" class="text-decoration-none">
                                    <div class="rounded icon-ppob text-center">
                                        <div class="box-icon bg-green text-center">
                                            <i class="mdi mdi-console-network-outline icon-menu"></i>
                                        </div>
                                        <dd>Withdraw Royalti</dd>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            @include('layout.member.nav')
        </div>
        <!-- Dark Overlay element -->
        <div class="overlay"></div>
    </div>

    <!-- Modal help-konversi -->
<!--    <div class="modal fade" id="help-konversi" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Konversi eIDR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Laboriosam nam voluptates provident soluta tempora, quo reiciendis vero voluptate magnam et dolorum debitis, id rerum nisi ullam tempore quidem dolor quisquam?
                    </p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>-->

    <!-- Modal help-topup -->
    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document" id="confirmDetail">
        </div>
    </div>
    <div class="modal fade" id="confirmSubmitTopUp" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document" id="confirmDetailTopUp">
        </div>
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
           var input_jml_wd = $("#input_jml").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd-eidr?input_jml_wd="+input_jml_wd,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function inputSubmitTopUp(){
           var input_jml_wd = $("#input_jml_topup").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-topup?input_jml_topup="+input_jml_wd,
                success: function(url){
                    $("#confirmDetailTopUp" ).empty();
                    $("#confirmDetailTopUp").html(url);
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
