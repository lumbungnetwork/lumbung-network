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
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box tilebox-one">
                                <h6 class="text-muted m-b-20">Permintaan Aktivasi Member</h6>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">

                                <tbody>
                                    @foreach($allPackage as $row)
                                    <tr>
                                        <td>
                                            <div class="rounded-lg shadow p-3">
                                                <h6>{{$row->username}}</h6>
                                                <small>{{$row->hp}}</small><br><br>
                                                <button class="btn btn-success float-right"
                                                    onclick="inputSubmit({{$row->id}})">Aktivasi</button>
                                                <button class="btn btn-danger"
                                                    onclick="rejectSubmit({{$row->id}})">Tolak Aktivasi</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>
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
    function inputSubmit(id_paket){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-order?id_paket="+id_paket,
                success: function(url){
                    Swal.fire({
                        html:url,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                }
            });
        }

        function rejectSubmit(id_paket){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/reject-order?id_paket="+id_paket,
                success: function(url){
                    Swal.fire({
                        html:url,
                        showCancelButton: false,
                        showConfirmButton: false
                    })
                }
            });
        }

        function confirmSubmit(){
            var form = $('#form-add');
            $(document.body).append(form);
            form.submit();
            Swal.fire('Sedang Memproses');
            Swal.showLoading();
        }

</script>
@stop