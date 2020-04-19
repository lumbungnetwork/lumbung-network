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
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Input Stock Vendor</h6>
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
                        <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 20px;text-align: center;">
                            <div class="sc-product-item thumbnail card card-block">
                                <div class="caption">
                                    <img data-name="product_image" src="{{$row->image}}" alt="..." style="width: 150px;">
                                    <h5 data-name="product_name">{{$row->name}} </h5>
                                    <h6 data-name="product_desc">{{$row->ukuran}} </h6>
                                    <h5 data-name="product_name"><b>Rp. {{number_format($row->stockist_price, 0, ',', ',')}}</b></h5>
                                    <div>
                                        <div class="form-group2">
                                            <input class="sc-cart-item-qty" name="product_quantity" min="1" value="1" type="number">
                                        </div>
                                        <input name="product_price" value="{{number_format($row->stockist_price, 0, ',', '')}}" type="hidden" />
                                        <input name="product_id" value="{{$row->id}}" type="hidden" />
                                        <button class="sc-add-to-cart btn btn-success btn-sm  margin-tb-10">Masuk ke keranjang</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-xl-12 col-xs-12">
                            <div class="card-box tilebox-one">
                                <form action="/m/purchase/input-vstock" method="POST">
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('#smartcart').smartCart();
        });
</script>
@stop