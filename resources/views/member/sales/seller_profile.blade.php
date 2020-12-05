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
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                            <div class="card-box tilebox-one">
                                <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                <h6 class="text-muted text-uppercase m-b-20">Seller Profile</h6>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            @if ($profile != null)
                            <button class="btn btn-info mb-3" data-toggle="modal" data-target="#editProfileModal">Ubah
                                Data Profile</button>
                            <div class="form-group">
                                <label for="name">Nama Toko</label>
                                <input type="text" class="form-control" value="{{$profile->shop_name}}"
                                    autocomplete="off" readonly>
                            </div>
                            <div class="form-group">
                                <label for="motto">Motto/Slogan Stokis (Max 70 Karakter)</label>
                                <textarea rows="2" class="form-control" autocomplete="off"
                                    readonly>{{$profile->motto}}</textarea>
                            </div>
                            <label for="tg_user">Username Telegram</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Username"
                                    aria-describedby="basic-addon1" value="{{$profile->tg_user}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="no_hp">No. HP/WhatsApp</label>
                                <input type="text" class="form-control" autocomplete="off" value="{{$profile->no_hp}}"
                                    readonly>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <img src="{{ asset('/storage/sellers') }}/{{$profile->image}}"
                                        style="max-width: 100px;" alt="">
                                </div>

                            </div>

                            @else
                            <div class="container">
                                <p>Anda belum memiliki data Seller Profile, silakan diisi:</p>
                            </div>

                            <form id="form-add" method="POST" action="/m/seller/add-profile"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="shop_name">Nama Toko</label>
                                    <input type="text" name="shop_name" class="form-control" id="shop_name"
                                        autocomplete="off">
                                    @error('shop_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="motto">Motto/Slogan Stokis (Max 70 Karakter)</label>
                                    <textarea name="motto" rows="2" class="form-control" id="motto"
                                        autocomplete="off"></textarea>
                                    @error('motto')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <label for="tg_user">Username Telegram</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">@</span>
                                    </div>
                                    <input type="text" class="form-control" id="tg_user" name="tg_user"
                                        placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                    @error('tg_user')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">No. HP/WhatsApp</label>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" name="no_hp"
                                        placeholder="08..." class="form-control" id="no_hp" autocomplete="off">
                                    @error('no_hp')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-6" id="profil-image">
                                        <img src="{{ asset('/storage/sellers') }}/default.jpg" style="max-width: 200px;"
                                            alt="">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="image">Pilih Gambar</label>

                                    <input id="image" type="file" name="image">
                                </div>
                                @error('image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </form>

                            <button class="btn btn-success" onclick="addProfile()">Buat Profile</button>


                            @endif
                        </div>
                    </div>
                </div>



            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>

    @if ($profile != null)
    <!-- EditProfileModal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit" method="post" action="/m/seller/edit-profile" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name-edit">Nama Toko</label>
                            <input type="text" name="shop_name" class="form-control" id="shop_name-edit"
                                autocomplete="off" value="{{$profile->shop_name}}">
                            @error('shop_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="motto-edit">Motto/Slogan Stokis (Max 70 Karakter)</label>
                            <textarea name="motto" rows="2" class="form-control" id="motto-edit"
                                autocomplete="off">{{$profile->motto}}</textarea>
                            @error('motto')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tg_user">Username Telegram</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">@</span>
                            </div>
                            <input type="text" class="form-control" id="tg_user-edit" name="tg_user"
                                placeholder="Username" aria-label="Username" aria-describedby="basic-addon1"
                                value="{{$profile->tg_user}}">
                            @error('tg_user')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No. HP/WhatsApp</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" name="no_hp" class="form-control"
                                id="no_hp-edit" autocomplete="off" value="{{$profile->no_hp}}">
                            @error('no_hp')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6" id="profil-image-edit">
                                <img src="{{ asset('/storage/sellers') }}/{{$profile->image}}" style="max-width: 150px;"
                                    alt="">
                            </div>

                        </div>
                        <a class="label label-info" id="edit-picture" onclick="editPicture()"> Ubah Gambar</a>
                        <div id="upload-picture" class="form-group">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="editProfile()">Ubah Profile</button>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>

@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

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

    function addProfile() {
        if($.trim($('#shop_name').val()) == ''){
            errorToast('Nama Toko harus diisi');
        }else if($.trim($('#motto').val()) == ''){
            errorToast('Motto Toko harus diisi');
        }else if($.trim($('#no_hp').val()) == ''){
            errorToast('No HP harus diisi');
        } else {
            $("#form-add").submit();
        }

    }

    function editProfile() {
        if($.trim($('#shop_name-edit').val()) == ''){
            errorToast('Nama Toko harus diisi');
        }else if($.trim($('#motto-edit').val()) == ''){
            errorToast('Motto Toko harus diisi');
        }else if($.trim($('#no_hp-edit').val()) == ''){
            errorToast('No HP harus diisi');
        } else {
            $("#form-edit").submit();
        }

    }

    function editPicture() {
        $('#upload-picture').html(`
            <label for="image">Pilih Gambar</label><br>
            <input id="image-edit" type="file" name="image"><br>
            @error('no_hp')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            <a onclick="abortPicture()" class="label label-danger">batal</a>
        `);
        $('#edit-picture').hide();

    }

    function abortPicture() {
        $('#upload-picture').empty();
        $('#edit-picture').show();
    }






</script>
@stop
