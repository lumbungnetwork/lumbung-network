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
                <div class="card shadow rounded bg-white p-3 mb-3">
                    <h4 class="mb-3">Top-up eIDR</h4>
                    @if (date('Hi') > '2200')
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        Jam operasional Corporate Banking sudah berlalu, transaksi anda akan dikonfirmasi keesokan hari,
                        pada jam kerja.
                    </div>
                    @endif
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                </div>

                @if ($getData->status == 1)
                <div class="card shadow rounded bg-light p-3 mb-3">
                    <div id="verification-process" class="row p-3">
                        <img src="/image/magnifying-glass-verifying.gif" style="margin: auto; max-width: 80%;" alt="">
                        <br>
                        <small>System akan memverifikasi pembayaran anda dalam kurun waktu 15 menit ke depan secara
                            otomatis</small>
                    </div>

                </div>
                @endif

                <?php
                    $status = 'Tuntas';
                    $label = 'success';
                    if($getData->status == 0){
                        $status = 'Proses Transfer';
                        $label = 'danger';
                    }
                    if($getData->status == 1){
                        $status = 'Sedang Diproses';
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

                            <small><strong>Tanggal Order:
                                </strong>{{date('d F Y', strtotime($getData->created_at))}}</small>
                            <br>
                            <small class="m-t-10"><strong>Order Status: </strong> <span
                                    class="f-14 label label-{{$label}}">{{$status}}</span></small>
                        </div>
                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead class="bg-faded">
                                    <tr>
                                        <th>#</th>
                                        <th>Total Top-up (Rp.)</th>
                                        <th>Unique Digit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{number_format($getData->nominal, 0, ',', ',')}}</td>
                                        <td>{{number_format($getData->unique_digit, 0, ',', ',')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                                $total = $getData->nominal + $getData->unique_digit;
                            ?>
                        </div>

                    </div>
                </div>



                <div class="card shadow rounded bg-white p-3 mb-3">
                    @if($getData->status == 2)
                    <strong>Top-up eIDR Berhasil!</strong>
                    <br>
                    Hash: {{$getData->reason}}
                    @endif
                    @if($getData->status == 3)
                    <strong>Top-up eIDR Dibatalkan!</strong>
                    <br>
                    Alasan: {{$getData->reason}}
                    @endif
                    @if($getData->status < 2) <h6>Metode Pembayaran</h6>
                        <address>

                            @if($getData->bank_perusahaan_id == 1)
                            <br>
                            Nama Rekening: <strong>{{$bankPerusahaan->account_name}}</strong>
                            <br>
                            Nama Bank: <strong>{{$bankPerusahaan->bank_name}}</strong>
                            <br>
                            No. Rekening: <strong>{{$bankPerusahaan->account_no}}</strong>

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

                                @endif
                            </div>
                        </address>
                        @endif

                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    @if ($getData->status == 0)
                    <div class="row">
                        <div class="col-12">
                            <small class="text-xs-right"><b>Sub-total:</b> Rp.
                                {{number_format($getData->nominal, 0, ',', ',')}}</small>
                            <br>
                            <small class="text-xs-right"><b>Kode Unik:</b>
                                {{number_format($getData->unique_digit, 0, ',', ',')}}</small>
                            <br>
                            <span>Total transfer:</span>
                            <h5 class="text-xs-right">Rp. {{number_format($total, 0, ',', ',')}}</h5>

                            <img src="/image/red-dot-pulse.gif" alt=""><small>Pastikan transfer tepat hingga <mark>tiga
                                    digit
                                    terakhir!</mark></small>
                            <hr>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12 m-0">
                            @if($getData->status == 0)
                            <input type="hidden" value="{{$getData->id}}" name="id_topup" id="id_topup">
                            <div class="row p-0">
                                <div class="col-4">
                                    <button type="submit" class="btn btn-danger" id="rejectBtn" data-toggle="modal"
                                        data-target="#rejectSubmit" onClick="rejectSubmit()">Batal</button>

                                </div>
                                <div class="col-8">
                                    <button type="submit" class="btn btn-block btn-success" id="submitBtn"
                                        data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Saya
                                        sudah
                                        transfer</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endif
                            @endif

                            @if($getData->status == 1 || $getData->status == 2 || $getData->status == 3)
                            <div class="hidden-print">
                                <div class="pull-xs-right">
                                    <a class="btn btn-success"
                                        href="{{ URL::to('/') }}/m/history/topup-saldo">Kembali</a>
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

    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document" id="confirmDetail">
        </div>
    </div>
    <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document" id="rejectDetail">
        </div>
    </div>

    @stop


    @section('styles')
    <link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
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
    @if($getData->status == 0)
    <script>
        @for ($i = 1; $i < 4; $i++)
            $('#bankbutton{{$i}}').click(function() {
                $('#radio{{$i}}').prop('checked', true)
            })
        @endfor


           function inputSubmit(){
                var id_topup = $("#id_topup").val();
                var id_bank = $('input[name=radio]:checked').val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/topup-transaction?id_topup="+id_topup+"&id_bank="+id_bank,
                     success: function(url){
                         $("#rejectDetail" ).empty();
                         $("#confirmDetail" ).empty();
                         $("#confirmDetail").html(url);
                     }
                 });
           }

           function rejectSubmit(){
                var id_topup = $("#id_topup").val();
                 $.ajax({
                     type: "GET",
                     url: "{{ URL::to('/') }}/m/cek/reject-topup?id_topup="+id_topup,
                     success: function(url){
                         $("#confirmDetail" ).empty();
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

            function copy(id) {
                var copyText = document.getElementById(id);
                copyText.select();
                copyText.setSelectionRange(0, 99999)
                document.execCommand("copy");
                alert("Berhasil menyalin: " + copyText.value);
            }
    </script>
    @endif

    @if ($getData->status == 1)
    <script>
        $(function (){
            var id_topup = {{$getData->id}};
            setInterval(cekStatus, 10000)

            function cekStatus () {
                $.ajax({
                    type: "GET",
                    url: "{{ URL::to('/') }}/m/cek/topup-transaction-status?id_topup="+id_topup,
                    success: function(result){

                        console.log('result: ' + result);
                        if (result == 2) {
                            window.location.reload(true);
                        }
                    }
                })
            }

        })
    </script>
    @endif
    @stop
