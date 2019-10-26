@extends('layout.member.main')
@section('content')
@include('layout.member.topbar')
@include('layout.member.sidebar')

<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Belanja</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-8">
                    <div class="card-box tilebox-one">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <div class="row">
                            @foreach($getData as $row)
                            <div class="col-md-4 col-sm-6" style="margin-bottom: 20px;text-align: center;">
                                <div class="sc-product-item thumbnail">
                                    <img data-name="product_image" src="{{$row->image}}" alt="..." style="width: 150px;">
                                    <div class="caption">
                                        <h5 data-name="product_name">{{$row->name}} </h5>
                                        <h6 data-name="product_desc">{{$row->ukuran}} </h6>
                                        <h5 data-name="product_name"><b>Rp. {{number_format($row->stockist_price, 0, ',', ',')}}</b></h5>
                                        <div>
                                            <div class="form-group2">
                                                <input class="sc-cart-item-qty" name="product_quantity" min="1" value="1" type="number">
                                            </div>
                                            <input name="product_price" value="{{number_format($row->stockist_price, 0, ',', '')}}" type="hidden" />
                                            <input name="product_id" value="{{$row->id}}" type="hidden" />
                                            <button class="sc-add-to-cart btn btn-success btn-sm m-t-10">Masuk ke keranjang</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card-box tilebox-one">
                        <form action="/m/stockist-shoping" method="POST">
                            {{ csrf_field() }}
                            <div id="smartcart"></div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_member/css/cart.css') }}">
@stop
@section('javascript')
<script src="{{ asset('asset_member/js/jquery.cart.min.js') }}"></script>
<script type="text/javascript">
        $(document).ready(function(){
            $('#smartcart').smartCart();
        });
</script>
@stop