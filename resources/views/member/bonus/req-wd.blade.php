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
                            <h4 class="page-title">Request Withdrawal</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }} 
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="card">
                            <h3 class="card-header">Withdraw</h3>
                            <div class="card-block">
                                <h4 class="card-title">Minimum Withdraw Rp. 20.000 Admin Fee Rp. 6.500</h4>
                                <p class="card-text">Ajukan withdraw anda disini</p>
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" id="confirmDetail">
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('layout.member.footer')
@stop

@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('asset_member/plugins/morris/morris.css') }}">
@stop
@section('javascript')
<script src="{{ asset('asset_member/plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset('asset_member/plugins/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('asset_member/plugins/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('asset_member/plugins/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('asset_member/pages/jquery.dashboard.js') }}"></script>
<script>
       function inputSubmit(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-wd",
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
        }

</script>
@stop