@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <input type="hidden" id="isTronWeb" value="0" readonly>
        <input type="hidden" id="userTron" value="0" readonly>
        <input type="hidden" id="txType" value="3" readonly>
        <input type="hidden" id="username" value="{{$dataUser->user_code}}" readonly>
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
                <div class="card shadow rounded bg-white p-3 mb-3">
                    <h4 class="mb-3">Isi Deposit Vendor</h4>
                    <span id="showAddress"></span>

                    @if ( Session::has('message') )
                    <div class="container">
                        <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{  Session::get('message')    }}
                        </div>
                    </div>
                    @endif

                </div>

                <?php
                    $status = 'Tuntas';
                    $label = 'success';
                    if($getData->status == 0){
                        $status = 'Proses Transfer';
                        $label = 'danger';
                    }
                    if($getData->status == 1){
                        $status = 'Menunggu Konfirmasi';
                        $label = 'warning';
                    }
                    if($getData->status == 3){
                        $status = 'Batal';
                        $label = 'danger';
                    }
                ?>
                <div class="card rounded shadow bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <small class="mb-3"><strong>Invoice</strong>
                                <small>{{$getData->transaction_code}}</small><br>
                            </small>
                            <small><strong>Tanggal Order:
                                </strong>{{date('d F Y', strtotime($getData->created_at))}}</small><br>
                            <small class="m-t-10"><strong>Order Status: </strong> <span
                                    class="label f-12 label-{{$label}}">{{$status}}</span></small>
                        </div>
                        <div class="table-responsive mt-3 px-4">
                            <table class="table m-t-30">

                                <tbody>
                                    <tr>
                                        <td>Isi Deposit</td>
                                        <td>Rp{{number_format($getData->price, 0, ',', ',')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                                $total = $getData->price + $getData->unique_digit;
                            ?>
                        </div>

                    </div>
                </div>

                <div class="card shadow rounded bg-white p-3 mb-3">
                    <h5>Pilih Metode Pembayaran</h5>
                    <address>
                        @if($getData->bank_perusahaan_id != null)
                        @if($getData->is_tron == 0)
                        <br>
                        Nama Rekening: <strong>{{$bankPerusahaan->account_name}}</strong>
                        <br>
                        Nama Bank: <strong>{{$bankPerusahaan->bank_name}}</strong>
                        <br>
                        No. Rekening: <strong>{{$bankPerusahaan->account_no}}</strong>
                        @endif
                        @if($getData->is_tron == 1 && $getData->bank_perusahaan_id != 9)
                        <br>
                        Nama: <strong>Pembayaran via eIDR</strong>
                        <br>
                        Alamat Tron: <strong>TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge</strong>
                        @endif
                        @if($getData->is_tron == 1 && $getData->bank_perusahaan_id == 9)
                        <br>
                        Nama: <strong>Pembayaran via eIDR Autoconfirm</strong>
                        <br>
                        Alamat Tron: <strong>TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge</strong>
                        @endif
                        @endif
                        <div class="accordion mt-2" id="accordionExample">
                            @if($getData->bank_perusahaan_id == null)
                            <?php $no = 1; ?>
                            @foreach($bankPerusahaan as $rowBank)
                            <div class="card">
                                <div class="card-header" id="heading{{$no}}">
                                    <h1 class="mb-0">
                                        <button class="btn btn-outline-primary btn-lg" id="bankbutton{{$no}}"
                                            type="button" data-toggle="collapse" data-target="#collapse{{$no}}"
                                            aria-expanded="true" aria-controls="collapse{{$no}}">
                                            Bayar via Transfer {{$rowBank->bank_name}}
                                        </button>
                                    </h1>
                                </div>

                                <div id="collapse{{$no}}" class="collapse" aria-labelledby="heading{{$no}}"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="radio radio-primary">
                                            <input type="radio" name="radio" id="radio{{$no}}"
                                                value="0_{{$rowBank->id}}">
                                            <label for="radio{{$no}}">
                                                {{$rowBank->bank_name}} a/n <b>{{$rowBank->account_name}}</b>
                                                <br>
                                                <input type="text" id="bank-{{$no}}" style="border: 0;"
                                                    value="{{$rowBank->account_no}}" readonly>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="copy('bank-{{$no}}')">Copy</button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $no++; ?>
                            @endforeach
                            <?php $eidrno = count($bankPerusahaan) + 1; ?>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h1 class="mb-0">
                                        <button class="btn btn-outline-warning btn-lg" id="eidrbutton" type="button"
                                            data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            Bayar via eIDR
                                        </button>
                                    </h1>
                                </div>
                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="radio radio-primary">
                                            <input type="radio" name="radio" id="radio{{$eidrno}}" value="1_1" checked>
                                            <label for="radio{{$eidrno}}">
                                                eIDR
                                                <br>
                                                <input size="50" type="text" id="eidr-addr"
                                                    style="border: 0; font-size:10px; font-weight:200;"
                                                    value="TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge" readonly>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="copy('eidr-addr')">Copy</button>
                                                <br><small class="text-danger" style="display:none"
                                                    id="address-warning">Harus ditransfer dari
                                                    alamat TRON akun
                                                    ini</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </address>

                </div>



                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-xs-right"><b>Sub-total:</b>
                                Rp{{number_format($getData->price, 0, ',', ',')}}</small><br>
                            <small class="text-xs-right"><b>Kode Unik:</b>
                                {{number_format($getData->unique_digit, 0, ',', ',')}}</small>
                            <hr>
                            <h5 class="text-xs-right">Rp{{number_format($total, 0, ',', ',')}}</h5>
                            <br>
                            <span class="text-xs-right" id="saldo-eidr"></span>
                            <hr>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12">
                            @if($getData->status == 0)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <input type="hidden" value="{{$getData->id}}" name="id_trans" id="id_trans">
                                    <input type="hidden" value="{{$total}}" name="deposit" id="deposit">
                                    <button type="submit" class="btn btn-danger" id="rejectBtn" data-toggle="modal"
                                        data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" data-toggle="modal"
                                        data-target="#confirmSubmit" onClick="inputSubmit()">Saya sudah
                                        transfer</button>
                                    <button type="submit" class="btn btn-success" id="eidr-pay-button"
                                        data-toggle="modal" data-target="#confirmSubmit"
                                        onClick="inputSubmitTron()">Bayar via eIDR</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endif
                            @if($getData->status == 1 || $getData->status == 2 || $getData->status == 3)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <a class="btn btn-success" href="{{ URL::to('/') }}/m/list/transactions">Kembali</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            @include('layout.member.nav')
        </div>
        <div class="overlay"></div>
    </div>
    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" data-backdrop="true">
        <div class="modal-dialog" role="document" id="confirmDetail">
        </div>
    </div>
    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" data-backdrop="true">
        <div class="modal-dialog" role="document" id="rejectDetail">
        </div>
    </div>

    @stop


    @section('styles')
    <link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

    @stop

    @section('javascript')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
    </script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.cart.min.js') }}"></script>
    <script src="{{ asset('asset_new/js/tronweb.js') }}"></script>
    @if($getData->status == 0)
    <script>
        $(function() {
            $('#eidr-pay-button').hide();
            setTimeout(function(){
                if($('#isTronWeb').val() > 0 && $('#eidr-balance').val() >= {{$total}}){
                    $('#submitBtn').hide();
                    $('#eidr-pay-button').show();
                }
            }, 2000);
            setTimeout(function(){
                if($('#isTronWeb').val() == 0){
                    $('#address-warning').show();
                }
            }, 2500);
        })

        @for ($i = 1; $i < $eidrno; $i++)
            $('#bankbutton{{$i}}').click(function() {
                $('#radio{{$i}}').prop('checked', true)
                $('#submitBtn').show();
                $('#eidr-pay-button').hide();
            })
        @endfor

            $('#eidrbutton').click(function() {
                $('#radio{{$eidrno}}').prop('checked', true)
                if($('#isTronWeb').val() > 0 && $('#eidr-balance').val() >= {{$total}}){
                    $('#submitBtn').hide();
                    $('#eidr-pay-button').show();
                }
            })


           function inputSubmit(){
                var id_trans = $("#id_trans").val();
                var id_bank = $('input[name=radio]:checked').val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add/deposit-transaction?id_trans="+id_trans+"&id_bank="+id_bank,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }

           function inputSubmitTron(){
                var id_trans = $("#id_trans").val();
                var sender = $("#userTron").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/add/deposit-transaction-tron?id_trans="+id_trans+"&sender="+sender,
                     success: function(url){
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }

           function rejectSubmit(){
                var id_trans = $("#id_trans").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject/deposit-transaction?id_trans="+id_trans,
                     success: function(url){
                         $("#rejectDetail" ).empty();
                         $("#rejectDetail").html(url);
                     }
                 });
           }

            function confirmSubmit(){
                var dataInput = $("#form-add").serializeArray();
                $('#form-add').submit();
                $('#form-add').remove();
                $('#loading').show();
                $('#tutupModal').remove();
                $('#submit').remove();
            }

            function confirmSubmitTron(){
                var dataInput = $("#form-add-tron").serializeArray();
                $('#form-add-tron').submit();
                $('#form-add-tron').remove();
                $('#loading').show();
                $('#tutupModal').remove();
                $('#submit').remove();
            }

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
    @endif
    @stop
