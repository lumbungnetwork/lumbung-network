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
                <div class="col-sm-12">
                    @if($getData != null)
                    <div class="card-box">
                            <div class="row">
                            @foreach($getData as $row)
                                <div class="col-sm-3 col-lg-3 col-xs-12">
                                    <div class="card">
                                        <img class="card-img-top img-fluid" src="{{$row->image}}">
                                        <div class="card-block">
                                            <h5 class="card-title">{{$row->name}} {{$row->ukuran}}</h5>
                                            <p class="card-text">Rp. {{number_format($row->stockist_price, 0, ',', ',')}}</p>
                                            <a href="{{ URL::to('/') }}/m/stockist/detail/purchase/{{$row->id}}" class="btn btn-primary">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop