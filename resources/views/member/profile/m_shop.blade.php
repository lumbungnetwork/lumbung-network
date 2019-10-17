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
                <div class="col-sm-6">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <form method="POST" action="/m/s/stockist">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="user_name">Masukkan Username Stokis Tujuan Belanja Anda:</label>
                                        <input type="text" class="form-control" id="user_name" name="user_name" autocomplete="off">
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-block">
                        <p class="card-text">
                            Ingin menjadi Stokis di Area Anda?
                            Dapatkan <b>2 LMB</b> setiap kelipatan
                            Rp100.000,00 pembelanjaan Member
                            di Stokis Anda!
                        </p>
                        <a href="{{ URL::to('/') }}/m/req/stockist" class="btn btn-info">Apply</a>
                    </div>
                </div>
            </div>
            @if($getData != null)
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <div class="card-box">
                    <h4 class="header-title m-t-0">Stockist terdekat di Area anda</h4>
                    <div class="p-20">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>HP</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($getData as $row)    
                            <tr>
                                <td>{{$row->full_name}}</td>
                                <td>{{$row->alamat}} {{$row->kelurahan}} {{$row->kecamatan}} {{$row->kota}}</td>
                                <td>{{$row->hp}}</td>
                                <td>
                                    <a class="btn btn-warning btn-sm waves-effect waves-light" href="{{ URL::to('/') }}/m/shoping/{{$row->id}}"> <i class="fa fa-rocket m-r-5"></i> <span>Shop</span> </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop