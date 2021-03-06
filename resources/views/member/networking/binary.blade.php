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
                    <form class="row" method="get" action="/m/my/binary">
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
                </div>

                <div class="rounded-lg bg-white p-3 mb-3">
                    @if($back == true)
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="{{ URL::to('/') }}/m/my/binary" class="btn btn-dark btn-sm">
                                Back
                            </a>
                            @if($dataUser->id != $sessionUser->id)
                            <a href="{{ URL::to('/') }}/m/my/binary?get_id={{$dataUser->upline_id}}"
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
                                        <div class="binary-genealogy-level-0 clearfix">
                                            <div class="no_padding parent-wrapper clearfix">
                                                <div class="node-centere-item binary-level-width-100">
                                                    <div class="node-item-root">
                                                        <div class="binary-node-single-item user-block user-0">
                                                            <div class="images_wrapper"
                                                                style="font-size: 40px;margin: 11px 0;">
                                                                <a
                                                                    href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[0]->id}}">
                                                                    @if($getData[0]->gender == 2)
                                                                    <i class="fa fa-user-o"></i>
                                                                    @else
                                                                    <i class="fa fa-user-o"></i>
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <span class="wrap_content ">{{$getData[0]->username}}</span>
                                                            <div class="pop-up-content">
                                                                <div class="profile_tooltip_pick">
                                                                    <div class="full-name">{{$getData[0]->username}}
                                                                    </div>
                                                                    <div class="username">
                                                                        <span class="text-value">Joined</span>
                                                                        <span
                                                                            class="text-value">{{date('d F Y', strtotime($getData[0]->created_at))}}</span>
                                                                    </div>
                                                                </div>
                                                                <!--                                                                    <div class="tooltip_profile_detaile">
                                                                        <div class="text" style="text-align: center;">
                                                                            <span class="text-label">Total Jml PIN</span>
                                                                            <span class="text-value" >{{$getData[0]->total_sponsor}} &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; {{$getData[0]->total_sponsor}}</span>
                                                                        </div>
                                                                        <div class="text" style="text-align: center;">
                                                                            <span class="text-label">Total Omzet</span>
                                                                            <span class="text-value" >{{$getData[0]->total_sponsor}} &nbsp;&nbsp; - &nbsp;&nbsp; {{$getData[0]->total_sponsor}}</span>
                                                                        </div>
                                                                        <div class="text" style="text-align: center;">
                                                                            <span class="text-label">Sisa PIN</span>
                                                                            <span class="text-value" >{{$getData[0]->total_sponsor}} &nbsp;&nbsp; - &nbsp;&nbsp; {{$getData[0]->total_sponsor}}</span>
                                                                        </div>
                                                                    </div>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="parent-wrapper clearfix">
                                                        <div class="node-left-item binary-level-width-50">
                                                            <?php
                                                                    $root = '';
                                                                    if($getData[1] != null){
                                                                        $root = 'node-item-root';
                                                                    }
                                                                ?>
                                                            <div class="{{$root}}">
                                                                <span
                                                                    class="binary-hr-line binar-hr-line-left binary-hr-line-width-25"></span>
                                                                <div class="node-item-1-child-left">
                                                                    @if($getData[1] != null)
                                                                    <div
                                                                        class="binary-node-single-item user-block user-9">
                                                                        <div class="images_wrapper"
                                                                            style="font-size: 40px;margin: 11px 0;">
                                                                            <a
                                                                                href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[1]->id}}">
                                                                                @if($getData[1]->gender == 2)
                                                                                <i class="fa fa-user-o"></i>
                                                                                @else
                                                                                <i class="fa fa-user-o"></i>
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                        <span
                                                                            class="wrap_content ">{{$getData[1]->username}}</span>
                                                                        <div class="pop-up-content">
                                                                            <div class="profile_tooltip_pick">
                                                                                <div class="full-name">
                                                                                    {{$getData[1]->username}}</div>
                                                                                <div class="username">
                                                                                    <span
                                                                                        class="text-value">Joined</span>
                                                                                    <span
                                                                                        class="text-value">{{date('d F Y', strtotime($getData[1]->created_at))}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <!--                                                                                <div class="tooltip_profile_detaile">
                                                                                    <div class="text">
                                                                                        <span class="text-label">Total Sponsor</span>
                                                                                        <span class="text-value">{{$getData[1]->total_sponsor}}</span>
                                                                                    </div>
                                                                                </div>-->
                                                                            <!--                                                                                <div class="tooltip-footer">
                                                                                    <div class="text">
                                                                                        <span class="text-label">Joined : </span>
                                                                                        <span class="text-value">{{date('d F Y', strtotime($getData[1]->created_at))}}</span>
                                                                                    </div>
                                                                                </div>-->
                                                                        </div>
                                                                    </div>
                                                                    @else
                                                                    <div
                                                                        class="binary-node-single-item user-block user-13">
                                                                        <div class="images_wrapper"
                                                                            style="font-size: 40px;margin: 11px 0;">
                                                                            <i class="icon-user-follow text-danger"></i>
                                                                        </div>
                                                                        <!--<span class="wrap_content">Belum tersedia</span>-->
                                                                    </div>
                                                                    <!--                                                                            <div class="last_level_user"><i class="fa fa-2x">&nbsp;</i></div>-->
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if($getData[1] != null)
                                                            <div class="parent-wrapper clearfix">
                                                                <div class="node-left-item binary-level-width-50">
                                                                    <span
                                                                        class="binary-hr-line binar-hr-line-left binary-hr-line-width-25"></span>
                                                                    <div class="node-item-1-child-left">
                                                                        @if($getData[3] != null)
                                                                        <div
                                                                            class="binary-node-single-item user-block user-9">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <a
                                                                                    href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[3]->id}}">
                                                                                    @if($getData[3]->gender == 2)
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @else
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                            <span
                                                                                class="wrap_content ">{{$getData[3]->username}}</span>
                                                                            <div class="pop-up-content">
                                                                                <div class="profile_tooltip_pick">
                                                                                    <div class="full-name">
                                                                                        {{$getData[3]->username}}</div>
                                                                                    <div class="username">
                                                                                        <span
                                                                                            class="text-value">Joined</span>
                                                                                        <span
                                                                                            class="text-value">{{date('d F Y', strtotime($getData[3]->created_at))}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                        <div class="tooltip_profile_detaile">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Total Sponsor</span>
                                                                                                <span class="text-value">{{$getData[3]->total_sponsor}}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="tooltip-footer">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Joined : </span>
                                                                                                <span class="text-value">{{date('d F Y', strtotime($getData[3]->created_at))}}</span>
                                                                                            </div>
                                                                                        </div>-->
                                                                            </div>
                                                                        </div>
                                                                        @else
                                                                        <div
                                                                            class="binary-node-single-item user-block user-13">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <i
                                                                                    class="icon-user-follow text-danger"></i>
                                                                            </div>
                                                                            <!--<span class="wrap_content">Belum tersedia</span>-->
                                                                        </div>
                                                                        <div class="last_level_user"><i
                                                                                class="fa fa-2x">&nbsp;</i></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="node-right-item binary-level-width-50">
                                                                    <span
                                                                        class="binary-hr-line binar-hr-line-right binary-hr-line-width-25"></span>
                                                                    <div class="node-item-1-child-right">
                                                                        @if($getData[4] != null)
                                                                        <div
                                                                            class="binary-node-single-item user-block user-10">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <a
                                                                                    href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[4]->id}}">
                                                                                    @if($getData[4]->gender == 2)
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @else
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                            <span
                                                                                class="wrap_content ">{{$getData[4]->username}}</span>
                                                                            <div class="pop-up-content">
                                                                                <div class="profile_tooltip_pick">
                                                                                    <div class="full-name">
                                                                                        {{$getData[4]->username}}</div>
                                                                                    <div class="username">
                                                                                        <span
                                                                                            class="text-value">Joined</span>
                                                                                        <span
                                                                                            class="text-value">{{date('d F Y', strtotime($getData[4]->created_at))}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                        <div class="tooltip_profile_detaile">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Total Sponsor</span>
                                                                                                <span class="text-value">{{$getData[4]->total_sponsor}}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="tooltip-footer">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Joined : </span>
                                                                                                <span class="text-value">{{date('d F Y', strtotime($getData[4]->created_at))}}</span>
                                                                                            </div>
                                                                                        </div>-->
                                                                            </div>
                                                                        </div>
                                                                        @else
                                                                        <div
                                                                            class="binary-node-single-item user-block user-13">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <i
                                                                                    class="icon-user-follow text-danger"></i>
                                                                            </div>
                                                                            <!--<span class="wrap_content">Belum tersedia</span>-->
                                                                        </div>
                                                                        <div class="last_level_user"><i
                                                                                class="fa fa-2x">&nbsp;</i></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="node-right-item binary-level-width-50">
                                                            <?php
                                                                    $root1 = '';
                                                                    if($getData[2] != null){
                                                                        $root1 = 'node-item-root';
                                                                    }
                                                                ?>
                                                            <div class="{{$root1}}">
                                                                <span
                                                                    class="binary-hr-line binar-hr-line-right binary-hr-line-width-25"></span>
                                                                <div class="node-item-1-child-right">
                                                                    @if($getData[2] != null)
                                                                    <div
                                                                        class="binary-node-single-item user-block user-10">
                                                                        <div class="images_wrapper"
                                                                            style="font-size: 40px;margin: 11px 0;">
                                                                            <a
                                                                                href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[2]->id}}">
                                                                                @if($getData[2]->gender == 2)
                                                                                <i class="fa fa-user-o"></i>
                                                                                @else
                                                                                <i class="fa fa-user-o"></i>
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                        <span
                                                                            class="wrap_content ">{{$getData[2]->username}}</span>
                                                                        <div class="pop-up-content">
                                                                            <div class="profile_tooltip_pick">
                                                                                <div class="full-name">
                                                                                    {{$getData[2]->username}}</div>
                                                                                <div class="username">
                                                                                    <span
                                                                                        class="text-value">Joined</span>
                                                                                    <span
                                                                                        class="text-value">{{date('d F Y', strtotime($getData[2]->created_at))}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <!--                                                                                <div class="tooltip_profile_detaile">
                                                                                    <div class="text">
                                                                                        <span class="text-label">Total Sponsor</span>
                                                                                        <span class="text-value">{{$getData[2]->total_sponsor}}</span>
                                                                                    </div>
                                                                                </div>-->
                                                                            <!--                                                                                <div class="tooltip-footer">
                                                                                    <div class="text">
                                                                                        <span class="text-label">Joined : </span>
                                                                                        <span class="text-value">{{date('d F Y', strtotime($getData[2]->created_at))}}</span>
                                                                                    </div>
                                                                                </div>-->
                                                                        </div>
                                                                    </div>
                                                                    @else
                                                                    <div
                                                                        class="binary-node-single-item user-block user-13">
                                                                        <div class="images_wrapper"
                                                                            style="font-size: 40px;margin: 11px 0;">
                                                                            <i class="icon-user-follow text-danger"></i>
                                                                        </div>
                                                                        <!--<span class="wrap_content">Belum tersedia</span>-->
                                                                    </div>
                                                                    <!--<div class="last_level_user"><i class="fa fa-2x">&nbsp;</i></div>-->
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if($getData[2] != null)
                                                            <div class="parent-wrapper clearfix">
                                                                <div class="node-left-item binary-level-width-50">
                                                                    <span
                                                                        class="binary-hr-line binar-hr-line-left binary-hr-line-width-25"></span>
                                                                    <div class="node-item-1-child-left">
                                                                        @if($getData[5] != null)
                                                                        <div
                                                                            class="binary-node-single-item user-block user-9">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <a
                                                                                    href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[5]->id}}">
                                                                                    @if($getData[5]->gender == 2)
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @else
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                            <span
                                                                                class="wrap_content ">{{$getData[5]->username}}</span>
                                                                            <div class="pop-up-content">
                                                                                <div class="profile_tooltip_pick">
                                                                                    <div class="full-name">
                                                                                        {{$getData[5]->username}}</div>
                                                                                    <div class="username">
                                                                                        <span
                                                                                            class="text-value">Joined</span>
                                                                                        <span
                                                                                            class="text-value">{{date('d F Y', strtotime($getData[5]->created_at))}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                        <div class="tooltip_profile_detaile">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Total Sponsor</span>
                                                                                                <span class="text-value">{{$getData[5]->total_sponsor}}</span>
                                                                                            </div>
                                                                                        </div>-->
                                                                                <!--                                                                                        <div class="tooltip-footer">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Joined : </span>
                                                                                                <span class="text-value">{{date('d F Y', strtotime($getData[5]->created_at))}}</span>
                                                                                            </div>
                                                                                        </div>-->
                                                                            </div>
                                                                        </div>
                                                                        @else
                                                                        <div
                                                                            class="binary-node-single-item user-block user-13">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <i
                                                                                    class="icon-user-follow text-danger"></i>
                                                                            </div>
                                                                            <!--<span class="wrap_content">Belum tersedia</span>-->
                                                                        </div>
                                                                        <div class="last_level_user"><i
                                                                                class="fa fa-2x">&nbsp;</i></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="node-right-item binary-level-width-50">
                                                                    <span
                                                                        class="binary-hr-line binar-hr-line-right binary-hr-line-width-25"></span>
                                                                    <div class="node-item-1-child-right">
                                                                        @if($getData[6] != null)
                                                                        <div
                                                                            class="binary-node-single-item user-block user-10">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <a
                                                                                    href="{{ URL::to('/') }}/m/my/binary?get_id={{$getData[6]->id}}">
                                                                                    @if($getData[6]->gender == 2)
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @else
                                                                                    <i class="fa fa-user-o"></i>
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                            <span
                                                                                class="wrap_content ">{{$getData[6]->username}}</span>
                                                                            <div class="pop-up-content">
                                                                                <div class="profile_tooltip_pick">
                                                                                    <div class="full-name">
                                                                                        {{$getData[6]->username}}</div>
                                                                                    <div class="username">
                                                                                        <span
                                                                                            class="text-value">Joined</span>
                                                                                        <span
                                                                                            class="text-value">{{date('d F Y', strtotime($getData[6]->created_at))}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                        <div class="tooltip_profile_detaile">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Total Sponsor</span>
                                                                                                <span class="text-value">{{$getData[6]->total_sponsor}}</span>
                                                                                            </div>
                                                                                        </div>-->
                                                                                <!--                                                                                        <div class="tooltip-footer">
                                                                                            <div class="text">
                                                                                                <span class="text-label">Joined : </span>
                                                                                                <span class="text-value">{{date('d F Y', strtotime($getData[6]->created_at))}}</span>
                                                                                            </div>
                                                                                        </div>-->
                                                                            </div>
                                                                        </div>
                                                                        @else
                                                                        <div
                                                                            class="binary-node-single-item user-block user-13">
                                                                            <div class="images_wrapper"
                                                                                style="font-size: 40px;margin: 11px 0;">
                                                                                <i
                                                                                    class="icon-user-follow text-danger"></i>
                                                                            </div>
                                                                            <!--<span class="wrap_content">Belum tersedia</span>-->
                                                                        </div>
                                                                        <div class="last_level_user"><i
                                                                                class="fa fa-2x">&nbsp;</i></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
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
            </div>
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
<link href="{{ asset('asset_member/css/binary.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('asset_member/css/developer.css') }}" rel="stylesheet" type="text/css" />
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