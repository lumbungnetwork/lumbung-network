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
                        <h4 class="page-title">Struktur Sponsor</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        <form class="login100-form validate-form" method="get" action="/m/my/sponsor-tree">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xs-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label>Cari Member (By Username)</label>
                                    <input type="text" class="form-control" id="get_id" autocomplete="off">
                                    <input type="hidden" name="get_id" id="id_get_id">
                                    <ul class="typeahead dropdown-menu form-control" style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 98%;margin-left: 11px;" id="get_id-box"></ul>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary" >Cari</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="region region-content">
                                    <div id="block-system-main" class="block block-system clearfix">
                                        <div class="binary-genealogy-tree binary_tree_extended">
                                            <div class="sponsor-tree-wrapper">
                                                <div class="eps-sponsor-tree eps-tree" style="max-width: 270px;">
                                                    <ul>
                                                        <li>
                                                            <div class="eps-nc" nid="12">
                                                                <div class="user-pic">
                                                                    <div class="images_wrapper text-primary" style="font-size: 40px;margin: 10px 0;">
                                                                        <i class="icon-user"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="user-name">
                                                                   {{$dataUser->user_code}}
                                                                </div>
                                                                <div class="user-name" style="background-color: #e0dfdf;color: #444;">
                                                                   Total Sp : {{$dataUser->total_sponsor}}
                                                                </div>
                                                            </div>
                                                            @if($getData != null)
                                                            <ul>
                                                                @foreach($getData as $row)
                                                                    <li>
                                                                        <div class="eps-nc" nid="13">
                                                                            <div class="user-pic">
                                                                                <div class="images_wrapper text-success" style="font-size: 40px;margin: 10px 0;">
                                                                                    <i class="icon-user"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="user-name">
                                                                                {{$row->user_code}}
                                                                            </div>
                                                                            <div class="user-name" style="background-color: #e0dfdf;color: #444;">
                                                                                Total Sp : {{$row->total_sponsor}}
                                                                            </div>
                                                                            <div class="user-popup">
                                                                                <div class="popup-loader">
                                                                                    <div class="loader loader-bar">sdfsdfds</div>
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
    </div>
</div>
@include('layout.member.footer')
@stop
@section('styles')
<link href="{{ asset('asset_member/css/tree-style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('asset_member/css/developer.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
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