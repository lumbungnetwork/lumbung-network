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
                    <h6 class="mb-3">Upload Gambar</h6>

                    <form id="form-upload" class="mt-3" method="post" action="/m/image/upload"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group mt-3">
                            <input class="form-control" type="text" placeholder="Masukkan nama file" name="name"
                                id="name" autocomplete="off">
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <small>Buat nama yang jelas sesuai produk. <br> <span class="text-danger">Cth:
                                    beras-cap-abc-10-kg</span><br>Format: Nama + Ukuran<br>dipisahkan oleh tanda
                                sambung "-"</small>
                        </div>
                        <div class="form-group mt-5">
                            <label for="image">Pilih Gambar</label>

                            <input id="image" type="file" name="image">
                            @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <small>Perhatikan: <br>Gambar harus format <mark> JPEG (.jpg)</mark>, dengan <mark>ratio
                                1:1</mark> (persegi).
                            Disarankan dengan <mark>dimensi 200x200 pixel</mark>.</small>

                    </form>
                    <div class="form-group mt-3">
                        <button class="btn btn-dark btn-block" onclick="confirmUpload()"> Upload </button>
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
@stop

@section('javascript')
<script type="text/javascript">
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom',
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
    function confirmUpload() {
        if($.trim($('#name').val()) == ''){
            errorToast('Nama produk harus diisi');
        }else if($.trim($('#image').val()) == ''){
            errorToast('File Gambar harus dipilih');
        }else {
            $("#form-upload").submit();
        }
    }
</script>
@stop
