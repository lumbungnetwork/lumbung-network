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

                @if($getData != null)
                <div class="rounded-lg p-0 mb-3">
                    <h6 class="mb-3">Input Stock</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif
                    <div class="row">
                        @foreach($getData as $row)
                        <div class="col-6 p-2" style="margin-bottom: 20px;text-align: center;">
                            <div class="sc-product-item thumbnail rounded-lg bg-white shadow p-2 px-0">

                                <div style="line-height: 5px;" class="caption">
                                    <img data-name="product_image" src="{{$row->image}}" alt="{{$row->name}}"
                                        style="width: auto; max-width: 100%;">
                                    <h6 style="font-size: 14px; font-weight:200;" data-name="product_name">
                                        {{$row->name}} </h6>
                                    <dd style="font-size:12px" data-name="product_desc">{{$row->ukuran}} </dd>
                                    <dd style="font-size: 15px; margin-top: 15px;">
                                        <b>Rp{{number_format($row->member_price, 0, ',', ',')}}</b>
                                    </dd>

                                    <div>
                                        <div class="form-group2">
                                            <input class="sc-cart-item-qty" name="product_quantity" min="1" value="1"
                                                type="number">
                                        </div>
                                        <input name="product_price"
                                            value="{{number_format($row->member_price, 0, ',', '')}}" type="hidden" />
                                        <input name="product_id" value="{{$row->id}}" type="hidden" />
                                        <button class="sc-add-to-cart btn btn-success btn-sm margin-tb-10"> <span
                                                style="font-size: 15px;">+</span> ke Keranjang</button>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>

                        @endforeach
                    </div>
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <div class="card-box tilebox-one">
                                <form action="/m/purchase/input-stock" method="POST">
                                    {{ csrf_field() }}
                                    <div id="smartcart"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($getData == null)
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">

                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-12">
                            <div class="card-box tilebox-one">
                                <div class="alert alert-warning alert-dismissible" role="alert">
                                    Tidak ada data stock
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @endif

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
<script type="text/javascript">
    $(document).ready(function(){
            $('#smartcart').smartCart();
        });
</script>
@stop
