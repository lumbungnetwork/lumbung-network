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
                <div class="rounded-lg bg-white p-3 mb-3">
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif
                    <form class="row" method="get" action="/m/my/sponsor-tree">
                        {{ csrf_field() }}
                        <div class="col-xl-12 col-xs-12">
                            <fieldset class="form-group">
                                <label>Cari Member (By Username)</label>
                                <input type="text" class="form-control" id="get_id" autocomplete="off">
                                <input type="hidden" name="get_id" id="id_get_id">
                                <ul class="typeahead dropdown-menu"
                                    style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 98%;margin-left: 11px;"
                                    id="get_id-box"></ul>
                            </fieldset>
                        </div>

                        <div class="col-xl-12">
                            <button type="submit" class="btn btn-success">Cari</button>
                        </div>
                    </form>
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    @if($back == true)
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="{{ URL::to('/') }}/m/my/sponsor-tree" class="btn btn-dark btn-sm">
                                Back
                            </a>
                            @if($dataUser->id != $sessionUser->id)
                            <a href="{{ URL::to('/') }}/m/my/sponsor-tree?get_id={{$dataUser->sponsor_id}}"
                                class="btn btn-purple btn-sm">
                                One Level Up
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="region region-content">
                                <div id="block-system-main" class="block block-system clearfix">
                                    <div class="binary-genealogy-tree binary_tree_extended">
                                        <div class="sponsor-tree-wrapper">
                                            <div class="eps-sponsor-tree eps-tree" style="max-width: 270px;">
                                                <ul>
                                                    <li>
                                                        <div class="eps-nc" nid="12">
                                                            <div class="user-pic">
                                                                <div class="images_wrapper text-primary"
                                                                    style="font-size: 40px;margin: 10px 0;">
                                                                    <a
                                                                        href="{{ URL::to('/') }}/m/my/sponsor-tree?get_id={{$dataUser->id}}">
                                                                        <i class="fa fa-user-o"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="user-name">
                                                                {{$dataUser->username}}
                                                            </div>
                                                            <div class="user-name"
                                                                style="background-color: #e0dfdf;color: #444;">
                                                                Total Sp : {{$dataUser->total_sponsor}}
                                                            </div>
                                                        </div>
                                                        @if($getData != null)
                                                        <ul>
                                                            @foreach($getData as $row)
                                                            <li>
                                                                <div class="eps-nc" nid="13">
                                                                    <div class="user-pic">
                                                                        <div class="images_wrapper text-success"
                                                                            style="font-size: 40px;margin: 10px 0;">
                                                                            <a
                                                                                href="{{ URL::to('/') }}/m/my/sponsor-tree?get_id={{$row->id}}">
                                                                                <i class="fa fa-user-o"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="user-name">
                                                                        {{$row->username}}
                                                                    </div>
                                                                    <div class="user-name"
                                                                        style="background-color: #e0dfdf;color: #444;">
                                                                        Total Sp : {{$row->total_sponsor}}
                                                                    </div>
                                                                    <div class="user-popup">
                                                                        <div class="popup-loader">
                                                                            <div class="loader loader-bar">sdfsdfds
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<link href="{{ asset('asset_member/css/tree-style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('asset_member/css/developer.css') }}" rel="stylesheet" type="text/css" />
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#get_id").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/usercode" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box").show();
                    $("#get_id-box").html(data);
                }
            });
        });
    });
    function selectUsername(val) {
        var valNew = val.split("____");
        $("#get_id").val(valNew[1]);
        $("#id_get_id").val(valNew[0]);
        $("#get_id-box").hide();
    }
</script>
@stop