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
                    <h6 class="mb-3">Tron</h6>
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
                            <fieldset class="form-group" readonly>
                                <label>Alamat TRON Utama Anda</label>
                                <dd><mark>{{$dataUser->tron}}</mark></dd>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            @if ($reset == null)
                            <button class="btn btn-warning float-right" data-toggle="modal"
                                data-target="#confirmSubmit">Reset</button>
                            @endif
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

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengajuan Reset Alamat TRON</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-reset" action="/m/reset/tron" method="POST">
                        @csrf
                        <dd>Untuk melepaskan tautan alamat TRON agar bisa diganti dengan alamat lain, anda harus
                            mengajukannya kepada Delegasi anda:</dd>
                        <select name="delegate" id="delegate">
                            <option value="">--Pilih Delegasi--</option>
                            @foreach ($delegates as $delegate)
                            <option value="{{$delegate->name}}">{{$delegate->name}}</option>
                            @endforeach
                        </select>
                        <small id="delegateWarn" class="text-danger" style="display: none;">Silakan Pilih Delegasi
                            anda.</small>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="submit" onclick="reset()" class="btn btn-primary">Ajukan Reset</button>
                </div>
            </div>
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
    function reset() {
        if ($('#delegate').val() == '') {
            $('#delegateWarn').show();
            return false;
        } else {
            $('#delegateWarn').hide();
            $('#form-reset').submit();
        }


    }
</script>

@stop
