@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container" style="margin-top: -15px;">
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
                <div class="rounded-lg bg-white p-1 mb-3">
                    <div class="col-10 float-right">
                        <div style="margin-top: -32px;" class="card p-3">
                            <h6 class="mb-0 text-right">{{$sellerProfile->shop_name}}</h6>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-4 p-3">
                            <img src="{{ asset('/storage/sellers') }}/{{$sellerProfile->image}}"
                                style="max-width: 100px; border-radius: 10px; margin-left: 5px;" alt="">
                        </div>
                        <div class="col-8">

                            <div class="row p-2 mt-1">
                                <div class="col-12 text-center">
                                    <span class="d-block text-muted">{{$sellerProfile->motto}}<em></em></span>
                                    <dd>{{$sellerAddress}}</dd>
                                </div>

                            </div>
                            <div class="row px-1 justify-content-center" style="font-size: 20px;">
                                <a href="tel:{{$sellerProfile->no_hp}}" class="btn btn-warning rounded-pill p-2 mx-2"><i
                                        class="mdi mdi-phone-in-talk" style="font-size: 20px;"></i></a>
                                <a href="https://wa.me/62{{substr($sellerProfile->no_hp, 1)}}"
                                    class="btn btn-success rounded-pill p-2 mx-2"><i class="mdi mdi-whatsapp"
                                        style="font-size: 20px;"></i></a>
                                <a href="https://t.me/{{$sellerProfile->tg_user}}"
                                    class="btn btn-primary rounded-pill p-2 mx-2"><i class="mdi mdi-telegram"
                                        style="font-size: 20px;"></i></a>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label style="font-size: 12px" class="input-group-text"
                                for="category-select">Kategori</label>
                        </div>
                        <select style="font-size: 13px" class="custom-select" id="category-select"
                            onchange="filterProduct()">
                            <option value="0" selected>Tampilkan Semua</option>
                            @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="products" class="row">
                        @if(!empty($products->toArray()))
                        @foreach ($products as $product)
                        <div class="col-6 p-2 mb-3 text-center">
                            <div class="rounded-lg bg-white shadow p-2 px-0">
                                <img src="{{ asset('/storage/products') }}/{{$product->image}}"
                                    style="width: auto; max-width: 100%;" data-toggle="modal" data-target="#orderModal"
                                    onclick="showDetail({{$product->id}})">
                                <h6 style="font-size: 14px; font-weight:200; margin-top: 10px;">
                                    {{$product->name}} </h6>
                                <dd style="font-size:12px">{{$product->size}} </dd>
                                <dd style="font-size: 15px;">
                                    <b>Rp{{number_format($product->price)}}</b></dd>
                                <small>Stok tersedia:
                                    <b>{{$product->qty}}</b>
                                </small>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="container text-center">
                            <h6>Tidak ada produk</h6>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
        <button id="cart" onclick="checkCart()" data-toggle="modal" data-target="#cartModal"><i
                class="mdi mdi-cart"></i>
            Total: Rp<b id="cartTotal">0</b></button>
    </div>

    @include('layout.member.nav')
</div>

<div class="overlay"></div>
</div>
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="true">
    <div class="modal-dialog" role="document" id="productDetail">
    </div>
</div>
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="true">
    <div class="modal-dialog" role="document" id="cartDetail">
    </div>
</div>

@stop


@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<style>
    .minus,
    .plus {
        border: none;
        padding: 10px;
        width: 40px;
        font-size: 16px;
    }

    input[type=number].quantity::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity {
        padding: 10px;
        border: none;
        width: 40px;
    }

    #cart {
        display: block;
        position: sticky;
        bottom: 55px;
        left: 10px;
        z-index: 99;
        font-size: 14px;
        border: none;
        outline: none;
        background-color: rgba(240, 182, 58, 0.795);
        color: rgb(39, 39, 39);
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 7px;
    }
</style>

@stop

@section('javascript')

<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script type="text/javascript">
    let _token = '{{ csrf_token() }}';
    $(document).ready(function() {
        getTotal();
    })

    function getTotal() {
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/ajax/get-cart-total?user_id="+"{{$user->id}}",
            success: function(total){
                $("#cartTotal" ).empty();
                $("#cartTotal").html(total);
            }
        })
    }

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

    function successToast (message) {
        Toast.fire({
            icon: 'success',
            title: message
        })
    }

    function filterProduct() {
        var category_id = $('#category-select').val();
        var seller_id = {{$seller_id}}
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/ajax/get-product-by-category?category_id="+category_id+"&seller_id="+seller_id,
            success: function(url){
                $("#products" ).empty();
                $("#products").html(url);
            }
        })
    }

    function showDetail(product_id) {
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/ajax/get-product-by-id?product_id="+product_id,
            success: function(url){
                $("#productDetail").empty();
                $("#productDetail").html(url);
                $('.plus').on('click', function(e) {
                    var val = parseInt($(this).prev('input').val());
                    var max = parseInt($(this).prev('input').attr('max'));
                    if(val < max) {
                        $(this).prev('input').attr('value', val + 1);
                    }
                });

                $('.minus').on('click', function(e) {
                    var val = parseInt($(this).next('input').val());
                    if (val !== 0) {
                        $(this).next('input').attr('value', val - 1);
                    }
                });
            }
        })
    }

    function addToCart() {
        var product_id = $('#product_id').val();
        var quantity = $('#quantity').val();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('/') }}/m/ajax/add-to-cart",
            data: {
            product_id:product_id,
            quantity:quantity,
            _token:_token
            },
            success: function(response){
                if(response.success) {
                    $('#orderModal').modal('hide');
                    getTotal();
                    Swal.fire(
                    'Berhasil',
                    response.message,
                    'success'
                    );
                } else {
                    $('#orderModal').modal('hide');
                    getTotal();
                    Swal.fire(
                    'Oops!',
                    response.message,
                    'error'
                    );
                }

            }
        })

    }

    function checkCart() {
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/ajax/get-cart-contents?user_id="+"{{$user->id}}",
            success: function(url){
                $("#cartDetail" ).empty();
                $("#cartDetail").html(url);
            }
        })
    }

    function deleteCartItem(product_id, item_id) {
        $('#item-'+item_id).remove();
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/ajax/delete-cart-item?product_id="+product_id+"&user_id="+"{{$user->id}}",
            success: function(url){
                successToast('Item berhasil dihapus');
                $("#cartDetail" ).empty();
                $("#cartDetail").html(url);
                getTotal();

            }
        })
    }

    function checkout() {
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/m/ajax/cart-checkout?seller_id=" + {{$seller_id}},
            success: function(url){
                $("#cartDetail" ).empty();
                $("#cartDetail").html(url);

            }
        })
    }


</script>
@stop
