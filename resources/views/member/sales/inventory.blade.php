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
                                <h6 class="text-muted text-uppercase m-b-20">Inventory</h6>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-success btn-lg mb-3 p-1" data-toggle="modal"
                                data-target="#addProductModal"><span style="font-size: 16px;">+</span> Product</button>
                        </div>

                        <div class="table-responsive">
                            @if(!empty($products->toArray()))

                            <dd class="text-muted ml-2">Produk Aktif</dd>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    @if($product->is_active == 1)

                                    <tr>
                                        <td colspan="2"><a onclick="editProduct({{$product->id}})">{{$product->name}}
                                                {{$product->size}}</a></td>
                                        <td>{{$product->category->name}}</td>
                                        <td>{{$product->qty}}</td>
                                    </tr>
                                    @endif

                                    @endforeach
                                </tbody>
                            </table>

                            <dd class="text-muted mt-4 ml-2">Produk Non-Aktif</dd>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)

                                    @if($product->is_active == 0)

                                    <tr class="text-muted">
                                        <td colspan="2"><a onclick="editProduct({{$product->id}})">{{$product->name}}
                                                {{$product->size}}</a></td>
                                        <td>{{$product->category->name}}</td>
                                        <td>{{$product->qty}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="container">
                                <h6 class="text-center">Anda belum memiliki produk.</h6>
                                <p class="text-center">Silakan buat produk dengan klik
                                    tombol + Product di atas.</p>
                            </div>
                            @endif

                            @if($dataUser->is_stockist == 1)
                            <a class="text-info float-right mr-2" href="{{ URL::to('/') }}/m/purchase/my-stock">Old
                                Stock</a>
                            @else
                            <a class="text-info float-right mr-2" href="{{ URL::to('/') }}/m/purchase/my-vstock">Old
                                Stock</a>
                            @endif

                        </div>
                    </div>
                </div>



            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>

    <!-- AddProductModal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-add" method="POST" action="/m/add/product">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" class="form-control" id="name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="size">Ukuran</label>
                            <input type="text" name="size" class="form-control" id="size" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="price">Harga (Rp)</label>
                            <input inputmode="numeric" name="price" pattern="[0-9]*" type="text" class="form-control"
                                id="price" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select class="form-control" name="category_id" id="category_id">
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="desc">Deskripsi Produk (Opsional)</label>
                            <textarea rows="2" class="form-control" name="desc" id="desc" autocomplete="off"></textarea>
                        </div>
                        <fieldset class="form-group">
                            <label for="user_name">Gambar Produk</label><br>
                            <small><em>Ketikkan 3-4 huruf awal, lalu pilih opsi yang tampil</em></small>
                            <input type="text" class="form-control mb-0" id="get_name" name="image" autocomplete="off">
                            <ul class="typeahead dropdown-menu"
                                style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px; margin-top: -140px;"
                                id="get_id-box">
                            </ul>
                        </fieldset>
                        <div class="row">
                            <div class="col-6" id="product-image">
                                <img src="{{ asset('/storage/products') }}/default.jpg" style="max-width: 100px;"
                                    alt="">
                            </div>
                            <div class="col-6">
                                <a class="text-info" href="{{ URL::to('/') }}/m/image/upload">Upload Gambar</a>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="createProduct()">Buat Produk</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EditProductModal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" data-backdrop="static" role="dialog"
        aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" id="editDetail">
        </div>
    </div>
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
    $(document).ready(function() {

        $("#get_name").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/product-image" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box").show();
                    $("#get_id-box").html(data);
                }
            });
        });
    } );

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



    function selectName(val) {
        var newImage = `<img src="{{ asset('/storage/products') }}/` + val +`" style="max-width: 100px;" alt="">`;
        $("#get_name").val(val);
        $("#product-image").empty();
        $("#get_id-box").hide();
        $("#product-image").html(newImage);

    }

    function errorToast (message) {
        Toast.fire({
            icon: 'error',
            title: message
        })
    }

    function createProduct() {
        if($.trim($('#name').val()) == ''){
            errorToast('Nama produk harus diisi');
        }else if($.trim($('#size').val()) == ''){
            errorToast('Ukuran produk harus diisi');
        }else if($.trim($('#price').val()) == ''){
            errorToast('Harga produk harus diisi');
        }else if($.trim($('#get_name').val()) == ''){
            errorToast('Gambar produk harus diisi');
        } else {
            $("#form-add").submit();
        }

    }

    function editProduct (id) {

        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/cek/edit-product/" + id,
            success: function(data){
                $("#editDetail").empty();
                $("#editDetail").html(data);
                $("#editProductModal").modal('show');
                if($('#is_active').val() == 1) {
                    $('#is_active_switch').prop('checked', true);
                }


            }
        });
    }

    function confirmEditProduct() {
        if($.trim($('#name-edit').val()) == ''){
            errorToast('Nama produk harus diisi');
        }else if($.trim($('#size-edit').val()) == ''){
            errorToast('Ukuran produk harus diisi');
        }else if($.trim($('#price-edit').val()) == ''){
            errorToast('Harga produk harus diisi');
        }else if($.trim($('#get_name_edit').val()) == ''){
            errorToast('Gambar produk harus diisi');
        } else {
            $("#form-edit").submit();
        }

    }

    function deleteProduct() {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Item yang dihapus akan hilang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Jangan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#form-delete").submit();
            }
        })

    }

    $("#editProductModal").on('shown.bs.modal', function(e) {
        $("#get_name_edit").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/product-image-edit" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box_edit").show();
                    $("#get_id-box_edit").html(data);


                }
            });
        });

        $('#is_active_switch').click(function(){
            if($(this).is(':checked')){
                $('#is_active').val(1);
                $('#is_active_status').text('Aktif');
            }else{
                $('#is_active').val(0);
                $('#is_active_status').text('Non-Aktif');
            }
        });
    });

    function selectNameEdit(val) {
        var newImage = `<img src="{{ asset('/storage/products') }}/` + val +`" style="max-width: 100px;" alt="">`;
        $("#get_name_edit").val(val);
        $("#product-image-edit").empty();
        $("#get_id-box_edit").hide();
        $("#product-image-edit").html(newImage);

    }






</script>
@stop
