@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')
<div class="main-panel">

    <?php //MENU HEADER  ?>
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <p class="navbar-brand">{{$headerTitle}}</p>
            </div>
        </div>
    </nav>

    <?php //MENU CONTENT  ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Testing</h5>
                    </div>
                    <div class="card-body">
                        @if ( Session::has('message') )
                        <div class="widget-content mt10 mb10 mr15">
                            <div class="alert alert-{{ Session::get('messageclass') }}">
                                <button class="close" type="button" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="container">
                                    <div class="input-group mb-3">
                                        <input id="input" type="text" class="form-control">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" id="cek" onclick="cekHash()"
                                                type="button">Cek</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container" id="confirmDetail"></div>
                            </div>
                            <div class="col-12">
                                <div class="container">
                                    <div class=" mb-3">
                                        <label for="to-address">To</label>
                                        <input id="to-address" name="to-address" type="text" class="form-control">
                                        <label for="amount">Amount</label>
                                        <input id="amount" name="amount" type="text" class="form-control">
                                        <div class="">
                                            <button class="btn btn-outline-secondary" onclick="send()"
                                                type="button">Kirim</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container" id="confirmDetail2"></div>
                            </div>
                            <div class="col-12">
                                <div class="container">
                                    <div class=" mb-3">
                                        <label for="nominal">Nominal + Unique Digits</label>
                                        <input id="nominal" name="nominal" type="text" class="form-control">
                                        <label for="date">Date (YYYY-MM-DD)</label>
                                        <input id="date" name="date" type="text" class="form-control">
                                        <div class="">
                                            <button class="btn btn-outline-secondary" onclick="checkMutation()"
                                                type="button">Check Mutation</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container" id="confirmDetail3"></div>
                            </div>
                            <div class="col-12">
                                <div class="container">
                                    <div class=" mb-3">
                                        <label for="check-address">Address</label>
                                        <input id="check-address" name="check-address" type="text" class="form-control">
                                        <label for="token-id">Token Id</label>
                                        <input id="token-id" name="token-id" type="text" class="form-control">
                                        <div class="">
                                            <button class="btn btn-outline-secondary" onclick="checkBalance()"
                                                type="button">Check Balance</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container" id="confirmDetail4"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
    $("#popUp").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });

    function cekHash () {
        var inputHash = $('#input').val();
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/ajax/adm/cek/test-hash?hash="+inputHash,
            success: function(url){
                $("#confirmDetail" ).empty();
                $("#confirmDetail").html(url);
            }
        });
    }

    function send () {
        var toAddress = $('#to-address').val();
        var amount = $('#amount').val();
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/ajax/adm/cek/test-send?toAddress="+toAddress+"&amount="+amount,
            success: function(url){
                $("#confirmDetail2" ).empty();
                $("#confirmDetail2").html(url);
            }
        });
    }

    function checkMutation () {
        var nominal = $('#nominal').val();
        var date = $('#date').val();
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/ajax/adm/cek/test-check-mutation?nominal="+nominal+"&date="+date,
            success: function(url){
                $("#confirmDetail3" ).empty();
                $("#confirmDetail3").html(url);
            }
        });
    }

    function checkBalance () {
        var checkAddress = $('#check-address').val();
        var tokenId = $('#token-id').val();
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/ajax/adm/cek/test-check-balance?checkAddress="+checkAddress+"&tokenId="+tokenId,
            success: function(url){
                $("#confirmDetail4" ).empty();
                $("#confirmDetail4").html(url);
            }
        });
    }
</script>
@stop
