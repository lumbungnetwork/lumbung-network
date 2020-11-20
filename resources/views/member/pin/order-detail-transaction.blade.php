@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <input type="hidden" id="isTronWeb" value="0">
        <input type="hidden" id="userTron" value="0" disabled>
        <input type="hidden" id="txType" value="2" disabled>
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
                    <h6 class="mb-3">{{$headerTitle}}</h6>
                    <span id="showAddress"></span>

                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
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
                            <h5 class="mb-3">Invoice # <br>
                                <small>{{$getData->transaction_code}}</small>
                            </h5>
                            <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getData->created_at))}}</p>
                            <p class="m-t-10"><strong>Order Status: </strong> <span
                                    class="label label-{{$label}}">{{$status}}</span></p>
                        </div>
                        <div class="container px-3">
                            <table class="table table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Jumlah Pin</th>
                                        <th>Harga (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$getData->total_pin}}</td>
                                        <td>{{number_format($getData->price, 0, ',', ',')}}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>

                <div class="card shadow rounded bg-white p-3 mb-3">
                    <h6>Pilih Metode Pembayaran</h6>
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
                        Alamat Tron: <strong>TDtvo2jCoRftmRgzjkwMxekh8jqWLdDHNB</strong>
                        @endif
                        @if($getData->is_tron == 1 && $getData->bank_perusahaan_id == 9)
                        <br>
                        Nama: <strong>Pembayaran via eIDR Autoconfirm</strong>
                        <br>
                        Alamat Tron: <strong>TDtvo2jCoRftmRgzjkwMxekh8jqWLdDHNB</strong>
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
                                                    style="border: 0; font-size:9.5px; font-weight:200;"
                                                    value="TDtvo2jCoRftmRgzjkwMxekh8jqWLdDHNB" readonly>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="copy('eidr-addr')">Copy</button>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </address>

                </div>
                <?php
                    $total = $getData->price + $getData->unique_digit;
                ?>


                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-sm-6 col-xs-6">
                            <p class="text-xs-right"><b>Sub-total:</b> Rp.
                                {{number_format($getData->price, 0, ',', ',')}}</p>
                            <p class="text-xs-right"><b>Kode Unik:</b>
                                {{number_format($getData->unique_digit, 0, ',', ',')}}</p>
                            <hr>
                            <h3 class="text-xs-right">Rp. {{number_format($total, 0, ',', ',')}}</h3>
                            <br>
                            <span class="text-xs-right" id="saldo-eidr"></span>
                            <hr>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-6 col-xs-6 col-md-offset-6">
                            @if($getData->status == 0)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <input type="hidden" value="{{$getData->id}}" name="id_trans" id="id_trans">
                                    <input type="hidden" value="{{$total}}" name="nominal" id="nominal">
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
                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="confirmDetail">
                        </div>
                    </div>
                    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="rejectDetail">
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
    <link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
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
                     url: "{{ URL::to('/') }}/m/cek/add-transaction?id_trans="+id_trans+"&id_bank="+id_bank,
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
                     url: "{{ URL::to('/') }}/m/cek/add-transaction-tron?id_trans="+id_trans+"&sender="+sender,
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
                     url: "{{ URL::to('/') }}/m/cek/reject-transaction?id_trans="+id_trans,
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
    </script>
    @endif
    @stop
