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

                <div class="rounded-lg bg-white p-3 mb-3">
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif
                    <div class="row">
                        @php
                        $sum_deposit_masuk = 0;
                        $sum_deposit_keluar = 0;
                        $sum_deposit_tarik = 0;
                        $sum_on_the_fly = 0;
                        if($dataDeposit->sum_deposit_masuk != null){
                        $sum_deposit_masuk = $dataDeposit->sum_deposit_masuk;
                        }
                        if($dataDeposit->sum_deposit_keluar != null){
                        $sum_deposit_keluar = $dataDeposit->sum_deposit_keluar;
                        }
                        if($dataTarik->deposit_keluar != null){
                        $sum_deposit_tarik = $dataTarik->deposit_keluar;
                        }
                        if($onTheFly->deposit_out != null){
                        $sum_on_the_fly = $onTheFly->deposit_out;
                        }
                        $totalDeposit = $sum_deposit_masuk - $sum_deposit_keluar - $sum_deposit_tarik -
                        $sum_on_the_fly;
                        @endphp
                        <div class="col-12 mb-4">
                            <div class="rounded-lg bg-white shadow p-3">
                                <dd>Saldo Tersedia: </dd>
                                <h5 class="text-warning">Rp{{number_format($totalDeposit)}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <form id="form-withdraw" action="/m/vendor-withdraw-deposit" method="POST">
                                @csrf
                                <fieldset class="form-group">
                                    <label for="input-amount">Penarikan Deposit</label><br>
                                    <small><em>Deposit akan ditarik ke alamat TRON utama</em></small>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*"
                                        class="form-control allownumericwithoutdecimal invalidpaste" id="input-amount"
                                        name="amount" autocomplete="off" placeholder="Rp0">
                                </fieldset>
                            </form>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-lg float-right" id="submitBtn"
                                onClick="checkWithdraw()">Tarik</button>
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    function checkWithdraw(){
            var availableDeposit = {{$totalDeposit}};
            let amount = $("#input-amount").val();
            if(amount == '') {
                amount = 0;
            }
            if(amount > availableDeposit) {
                errorToast('Saldo Deposit Tidak Mencukupi');
            }
            if(amount <= 0) {
                errorToast('Nominal Penarikan Tidak Benar');
            }
            if(amount <= availableDeposit && amount > 0) {
                Swal.fire({
                    title: 'Tarik Deposit',
                    text: 'Saldo Deposit sejumlah Rp'+ parseInt(amount).toLocaleString() + ' akan ditarik ke alamat TRON utama anda',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Setuju',
                    cancelButtonText: 'Tunda',
                    showCancelButton: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Sedang Memproses');
                        Swal.showLoading();
                        $("#form-withdraw").submit();
                    }
                })

            }

        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            width: 200,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        function errorToast (message) {
            Toast.fire({
                icon: 'error',
                title: message
            })
        }

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        $('.invalidpaste').on('paste', function (event) {
            if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
                event.preventDefault();
            }
        });

</script>
@stop
