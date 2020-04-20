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
                    <h6 class="mb-3">Detail Paket</h6>
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
                            <address>
                                <strong>Data Pengorder:</strong>
                                <br>
                                Nama : <strong>{{$getData->name_user}}</strong>
                                <br>
                                Email: <strong>{{$getData->email}}</strong>
                                <br>
                                No. HP: <strong>{{$getData->hp}}</strong>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <p><strong>Tanggal Order: </strong>{{date('d F Y', strtotime($getData->created_at))}}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table m-t-30">
                                <thead class="bg-faded">
                                    <tr>
                                        <th>No</th>
                                    <th>Nama Paket</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Pin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{$getData->name}}</td>
                                        <td>{{$getData->short_desc}}</td>
                                        <td>{{$getData->total_pin}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php 
                    $price = $getData->total_pin * $pinSetting->price;
                ?>
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-md-9 col-sm-6 col-xs-6"></div>
                        <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-9">
                        <div class="hidden-print">
                            <div class="pull-xs-right">
                                <input type="hidden" value="{{$getData->id}}" name="id_paket" id="id_paket">
                                <button type="submit" class="btn btn-success"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Aktifasi</button>
                                <button type="submit" class="btn btn-danger"  id="rejectBtn" data-toggle="modal" data-target="#rejectSubmit" onClick="rejectSubmit()">Reject</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" id="confirmDetail">
                    </div>
                </div>
                <div class="modal fade" id="rejectSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.cart.min.js') }}"></script>
    <script>
       function inputSubmit(){
           var id_paket = $("#id_paket").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-order?id_paket="+id_paket,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function rejectSubmit(){
            var id_paket = $("#id_paket").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/reject-order?id_paket="+id_paket,
                success: function(url){
                    $("#rejectDetail" ).empty();
                    $("#rejectDetail").html(url);
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