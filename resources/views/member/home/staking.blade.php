@extends('layout.member.new_main')
@section('content')

<div class="wrapper">


    <!-- Page Content -->
    <div id="content">
        {{-- navbar --}}
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                        <i class="fa fa-arrow-left"></i> Beranda
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg bg-light p-3 mb-3">
                    <h4 class="mb-3">Staking</h4>

                    <div class="rounded-lg shadow-lg bg-white p-3">
                        <p>Total Staked LMB</p>

                        <h4 class="mt-2 text-right">{{number_format(round($totalStakedLMB, 2, PHP_ROUND_HALF_DOWN), 2)}}
                            LMB</h4>
                        <a href="/m/staking-leaderboard" class="text-info float-right">Leaderboard</a>
                        <div class="clearfix"></div>

                    </div>

                    <div class="mt-2 text-center p-3">
                        <h5>Dividend Pool</h5>
                        <h4 class="text-warning">Rp{{number_format($LMBDividendPool)}}</h4>
                        <small>Next Distribution: Rp{{number_format(2/100 * $LMBDividendPool)}}</small>
                    </div>

                    <div class="mt-3">
                        <div class="mb-0" id="showAddress"></div>
                        <div class="d-block availableLMB"></div>
                    </div>

                    <div class="mb-3 rounded-lg shadow-lg bg-white p-3">
                        <p>Your Stake</p>

                        <p class="mt-2 text-success d-inline" id="staked">{{number_format($userStakedLMB)}} LMB</p>
                        <small>({{number_format($userStakedLMB/$totalStakedLMB * 100, 2)}}%)</small>
                        <button class="ml-2 btn btn-sm btn-success" id="stake-btn" data-toggle="modal"
                            data-target="#stakeModal" disabled>Stake</button>
                        <button class="ml-2 btn btn-sm btn-danger" id="unstake-btn" data-toggle="modal"
                            data-target="#unstakeModal" disabled>Unstake</button>
                        @if ($userUnstaking > 0)
                        <dd class="text-warning">Unstaking in progress: {{$userUnstaking}}</dd>
                        @endif
                        <small class="mt-2 text-danger d-block" id="tronweb-warning">Use Tronlink or Dapp enabled
                            browser</small>
                        <a href="/m/staking-history" class="text-info">History</a>
                    </div>

                    <div class="mb-3 rounded-lg shadow-lg bg-white p-3">
                        <p>Dividend</p>
                        <p class="text-warning d-inline">{{$userDividend->net}} eIDR</p>
                        <button class="ml-3 btn btn-sm btn-success" id="claim-btn">Claim</button>
                    </div>

                    <div class="mb-3 rounded-lg shadow-lg bg-white p-3">
                        <p>Total Claimed</p>
                        <p class="text-warning mb-0">{{$userDividend->claimed}} eIDR</p>
                        <a href="/m/claimed-dividend-history" class="text-info">History</a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Stake Modal --}}
        <div class="modal fade" id="stakeModal" tabindex="-1" role="dialog" aria-labelledby="stakeModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <dd>Staked: <span class="text-success">{{number_format($userStakedLMB)}} LMB</span></dd>
                        <div class="availableLMB"></div>
                        <div class="input-group mb-3">

                            <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control"
                                placeholder="0 LMB" id="inputLMB">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="max-btn"
                                    onclick="inputMax()">Max</button>
                            </div>
                            <button class="btn btn-success float-right" id="tronwebPay">Stake</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Unstake Modal --}}
        <div class="modal fade" id="unstakeModal" tabindex="-1" role="dialog" aria-labelledby="unstakeModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <dd>Staked: <span class="text-success">{{number_format($userStakedLMB)}} LMB</span></dd>

                        <div class="input-group mb-3">

                            <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control"
                                placeholder="0 LMB" id="outputLMB" min="1" max="{{$userStakedLMB}}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="max-btn"
                                    onclick="outputMax()">Max</button>
                            </div>
                            <button class="btn btn-danger float-right" id="unstake">Unstake</button>
                        </div>
                        <small class="text-danger">Unstake LMB need 7 cycle to complete.</small>

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
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    var _LMBbalance = 0;
    let _token = '{{ csrf_token() }}';
    const netDividend = {{$userDividend->net}};

    function inputMax() {
        $('#inputLMB').val(_LMBbalance);
    }

    function outputMax() {
        $('#outputLMB').val({{$userStakedLMB}});
    }

    //TronWeb Validation by Swal
    function eAlert(message) {
        Swal.fire({
            title: 'Gagal!',
            text: message,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }).then((result) => {
                if (result.isConfirmed) {
                window.location.reload(true);
            }
        })
    }
    
    //TronWeb
    var toAddress = 'TY8JfoCbsJ4qTh1r9HBtmZ88xQLsb6MKuZ';
    var userAddress, tronWeb;
    let sendAmount = 0;
    
    $(document).ready(function () {
        setTimeout(function () {
            main()
        }, 200)
        console.log('ready');

        $( "#outputLMB" ).on('mouseup keyup', function() {
          var max = parseInt($(this).attr('max'));
          var min = 0;
          if ($(this).val() > max)
          {
              $(this).val(max);
          }
          else if ($(this).val() < min)
          {
              $(this).val(min);
          }       
        }); 

        $( "#inputLMB" ).on('mouseup keyup', function() {
          var max = _LMBbalance;
          var min = 0;
          if ($(this).val() > max)
          {
              $(this).val(max);
          }
          else if ($(this).val() < min)
          {
              $(this).val(min);
          }       
        }); 
    });
    
    
    var waiting = 0;
    
    async function main() {
        if (!(window.tronWeb && window.tronWeb.ready)) return (waiting += 1, 50 == waiting) ? void console.log('Failed to connect to TronWeb') : (console.warn("main retries", "Could not connect to TronLink.", waiting), void setTimeout(main,
        500));
        tronWeb = window.tronWeb;
        try {
            await showTronBalance();
        } catch (a) {
            console.log(a);
        }
    
    }
    
    function shortId(a, b) { return a.substr(0, b) + "..." + a.substr(a.length - b, a.length) }
    
    //show LMB balance
    async function showTronBalance() {
        userAddress = tronWeb.defaultAddress.base58;
        let tokenBalancesArray;
        let balanceCheck = await tronWeb.trx
        .getAccount(userAddress)
        .then((result) => (tokenBalancesArray = result.assetV2));
        balanceCheck;

        let LMBexist = await tokenBalancesArray.some(function (tokenID) {
        return tokenID.key == "1002640";
        });
        if (LMBexist) {
        let LMBarray = await tokenBalancesArray.find(function (tokenID) {
        return tokenID.key == "1002640";
        });
        let LMBbalance = LMBarray.value / 1000000;
        
        if (LMBbalance > 0) {
            $('#stake-btn').attr("disabled", false);
            $('#tronweb-warning').remove();
            _LMBbalance = LMBbalance;
        } else {
            $('#tronweb-warning').html('No LMB Available');
        }

        if ({{$userStakedLMB}} > 0) {
            $('#unstake-btn').attr("disabled", false);
        }
        
        $("#showAddress").html(`<dd class='text-secondary'>Active Wallet: <mark>${shortId(userAddress, 5)}</mark></dd> `);
        $(".availableLMB").html(`<small class='text-secondary'>Available: ${LMBbalance.toLocaleString("en-US")} LMB`);
        
        } else {
        $("#LMBbalance").html(`Alamat TRON ini tidak memiliki LMB`);
        }
    }
    
    //Pay using TronWeb service
    $("#tronwebPay").click(async function () {
        sendAmount = $('#inputLMB').val() * 1000000;
        if (sendAmount > 0) {
            try {
            var tx = await tronWeb.transactionBuilder.sendAsset(
                toAddress,
                sendAmount,
                "1002640",
                userAddress,
            );
            
            var signedTx = await tronWeb.trx.sign(tx);
            var broastTx = await tronWeb.trx.sendRawTransaction(signedTx);
            if (broastTx.result) {
                postAJAXtronweb(broastTx.txid);
            } else {
                eAlert('Transaksi Gagal, periksa koneksi dan ulangi kembali');
            }
        } catch (e) {
            if (e.includes("assetBalance is not sufficient")) {
                eAlert("Saldo LMB tidak mencukupi");
            } else if (e.includes("assetBalance must be greater than")) {
                eAlert("Alamat TRON ini tidak memiliki LMB");
            } else if (e.includes("declined by user")) {
                eAlert("Anda membatalkan Transaksi");
            } else if (e.includes("cancle")) {
                eAlert("Anda membatalkan Transaksi");
            } else {
                eAlert("Ada yang salah, restart aplikasi wallet ini.")
            }
        }
        } else {
            eAlert("Amount need to be filled");
        }
        
        
    });

    function postAJAXtronweb(hash) {
        var amount = $('#inputLMB').val();
        Swal.fire('Sedang Memproses...');
        Swal.showLoading();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('/') }}/m/ajax/confirm-staking",
            data: {
            amount:amount,
            hash:hash,
            _token:_token
            },
            success: function(response){
                if(response.success) {
                    Swal.fire(
                    'Success!',
                    'LMB successfully staked!',
                    'success'
                    )
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 3000)
                } else {
                    Swal.fire(
                    'Oops!',
                    response.message,
                    'error'
                    )
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 5000)
                }

            }
        })
    }

    $('#unstake').click( function() {
        var amount = $('#outputLMB').val();
        if (amount > 0) {
            Swal.fire('Sedang Memproses...');
            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "{{ URL::to('/') }}/m/ajax/confirm-unstaking",
                data: {
                amount:amount,
                _token:_token
                },
                success: function(response){
                    if(response.success) {
                        Swal.fire(
                        'Success!',
                        'LMB Unstaking Process started, maturing in next 7 days!',
                        'success'
                        )
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 3000)
                    } else {
                        Swal.fire(
                        'Oops!',
                        response.message,
                        'error'
                        )
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 5000)
                    }

                }
            })
        } else {
            eAlert("Amount need to be filled");
        }
        
    })

    $('#claim-btn').click( function() {
        const amount = netDividend;
        if (amount >= 1000) {
            Swal.fire('Sedang Memproses...');
            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "{{ URL::to('/') }}/m/ajax/claim-dividend",
                data: {
                amount:amount,
                _token:_token
                },
                success: function(response){
                    if(response.success) {
                        Swal.fire(
                        'Success!',
                        'Dividend Claimed Succesfully!',
                        'success'
                        )
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 3000)
                    } else {
                        Swal.fire(
                        'Oops!',
                        response.message,
                        'error'
                        )
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 5000)
                    }

                }
            })
        } else {
            eAlert("Minimum Rp1000 to claim");
        }
        
    })

    
</script>
@stop