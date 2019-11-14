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
                            <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 20px;text-align: center;">
                                <div class="sc-product-item thumbnail card card-block">
                                    <img data-name="product_image" src="{{$row->image}}" alt="..." style="width: 150px;">
                                    <div class="caption">
                                        <h5 data-name="product_name">{{$row->name}} </h5>
                                        <h6 data-name="product_desc">{{$row->ukuran}} </h6>
                                        <h5 data-name="product_name"><b>Rp. {{number_format($row->member_price, 0, ',', ',')}}</b></h5>
                                        <h6>Stok tersedia: <b>{{number_format($row->total_sisa, 0, ',', ',')}}</b> </h6>
                                        <div>
                                            <div class="form-group2">
                                                <input class="sc-cart-item-qty cekInput{{$row->id}}" name="product_quantity" min="1" max="{{number_format($row->total_sisa, 0, ',', '')}}" value="1" type="number">
                                            </div>
                                            <input name="product_price" value="{{number_format($row->member_price, 0, ',', '')}}" type="hidden" />
                                            <input name="product_id" value="{{$row->id}}" type="hidden" />
                                            @if($row->total_sisa > 0)
                                            <button class="sc-add-to-cart btn btn-success btn-sm m-t-10">Masuk ke keranjang</button>
                                            @endif
                                            @if($row->total_sisa <= 0)
                                            <div class="btn btn-dark btn-sm m-t-10">Masuk ke keranjang</div>
                                            @endif
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
                        <form action="/m/shoping" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="stockist_id" value="{{$id}}">
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
        
        @foreach($getData as $row1)
        $('.cekInput{{$row1->id}}').on('input', function () {
            var value = $(this).val();
            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, {{number_format($row1->total_sisa, 0, ',', '')}}), 1));
            }
        });
        @endforeach
</script>
@stop