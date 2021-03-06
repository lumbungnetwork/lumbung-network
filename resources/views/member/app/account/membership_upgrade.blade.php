@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Balance Card --}}
    <div class="p-4">
        <div class="mt-3 bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl p-2">
            <div class="p-2 text-2xl font-extralight text-gray-700 text-center">
                {{ number_format($netBalance, 0) }} eIDR
            </div>

        </div>
    </div>

    {{-- Select Method Tab --}}
    <div class="px-4 py-2" x-data="{ tab: 'bank' }">

        <div class="mt-3">
            <ul class='flex cursor-pointer justify-center'>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'bank' }" @click="tab = 'bank'">via eIDR</li>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'tron' }" @click="tab = 'tron'">via LMB</li>
            </ul>
        </div>


        <div class="nm-convex-white rounded-2xl p-2 overflow-x-scroll">
            <div x-show="tab === 'bank'">
                {{-- via eIDR --}}
                <div class="px-2">
                    <div class="my-2 text-xs text-gray-500">Upgrade to Premium</div>
                    <div class="nm-inset-gray-200 rounded-lg px-2 py-4">
                        <div class="text-md text-yellow-500 font-black tracking-wider text-center">100,000 eIDR</div>
                    </div>
                    {{-- Order Form --}}
                    <form action="{{ route('member.account.postMembership') }}" method="POST" id="upgrade-form">
                        @csrf
                        <input type="hidden" name="password" class="password">

                        @if ($user->member_type == 0)
                        <div class="mt-3 ">
                            @if ($user->sponsor_id == 5)
                            <div class="my-2 text-xs text-gray-500">
                                Pilih Sponsor (OPSIONAL)
                            </div>
                            <button type="button" id="change-sponsor" style="font-size: 10px"
                                class="rounded-lg py-1 px-2 h-6 bg-gradient-to-br from-red-400 to-purple-300 text-gray-800 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Pilih
                                Sponsor?</button>
                            <div class="hidden" id="change-sponsor-div">
                                <div id="instruction" style="font-size: 10px" class="text-gray-400 font-light">Ketikkan
                                    3-4 huruf awal
                                    username pembeli, akan tampil list username, silakan klik username yang diinginkan.
                                </div>
                                <div class="mt-2 nm-inset-gray-200 p-2 rounded-lg">
                                    <input class="ml-2 bg-transparent focus:outline-none w-full" type="text" id="get_id"
                                        placeholder="cari username..." autocomplete="off">
                                </div>
                                <div class="px-2">
                                    <input type="hidden" id="sponsor_id" name="sponsor_id">
                                    <ul class="text-sm font-light max-h-32 overflow-auto border border-solid border-gray-200 w-full hidden"
                                        id="get_id-box"></ul>
                                </div>
                            </div>
                            @endif

                        </div>
                        @endif

                    </form>

                    <div class="mt-3 flex justify-end">
                        <button onclick="upgrade()"
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Upgrade</button>
                    </div>
                </div>




            </div>
            <div x-show="tab === 'tron'">
                {{-- TRON --}}
                <div class="px-2">
                    <div class="my-2 text-xs text-gray-500">Resubscribe</div>
                    <div class="nm-inset-gray-200 rounded-lg px-2 py-4">
                        <div class="text-md text-yellow-500 font-black tracking-wider text-center">100 LMB</div>
                    </div>

                    @if ($user->member_type == 0)
                    <div class="my-4 text-sm text-gray-500 text-center">
                        Anda belum berhak untuk Resubscribe.
                    </div>
                    @else
                    {{-- Active Wallet info --}}
                    <div class="mb-0" id="showAddress"></div>
                    <div class="availableLMB"></div>
                    <p id="tronweb-warning" class="text-xs font-medium text-red-600">Gunakan Tronlink, TokenPocket atau
                        Browser DAPP
                        lainnya.</p>
                    {{-- Order Form --}}
                    <form action="{{ route('member.postWalletWithdraw') }}" method="POST" id="order-form-2">
                        @csrf
                        <input type="hidden" name="hash" id="hash">

                    </form>
                    <div class="mt-3 flex justify-end">
                        <button id="resubscribe-btn" disabled
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Resubscribe</button>
                    </div>
                    @endif


                </div>
            </div>

        </div>


    </div>
</div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<style>
    .active {
        background: linear-gradient(145deg, #FFFFFF, #D9D9D9);
        color: black;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection

@section('scripts')
<script>
    function upgrade() {
        Swal.fire({
            title: 'Upgrade',
            text: "Anda yakin ingin Upgrade ke Premium?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Nanti!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                const { value: password } = await Swal.fire({
                    title: 'Verifikasi 2FA',
                    html: '<input id="swal-input1" class="w-3/4 h-12 text-lg py-2 text-center justify-center bg-gray-200" inputmode="numeric" pattern="[0-9]*">',
                    focusConfirm: false,
                    showCancelButton: true,
                    preConfirm: () => {
                        return [
                            document.getElementById('swal-input1').value,
                        ]
                    }
                    
                })
                
                if (password) {
                    let pw = JSON.stringify(password);
                    let pass = pw.replace(/[""\[\]]/g, '');
                    Swal.fire('Memproses...')
                    swal.showLoading();
                    $('.password').val(pass);
                    $('#upgrade-form').submit();
                }
            }
        })
    }

    @if ($user->member_type == 0 && $user->sponsor_id == 5)
        $(document).ready(function(){
            $("#get_id").keyup(function(){
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.shopping.getUsername') }}" + "?name=" + $(this).val() ,
                    success: function(data){
                        $("#get_id-box").show();
                        $("#get_id-box").html(data);
                    }
                });
            });
        });
        function selectUsername(val) {
            var valNew = val.split("___");
            $("#get_id").val(valNew[1]);
            $("#sponsor_id").val(valNew[0]);
            $("#get_id-box").hide();
            $('#instruction').remove();

        }

        $('#change-sponsor').click( function () {
            $('#change-sponsor-div').show();
            $('#change-sponsor').hide();
        })
    @endif

    @if ($user->member_type > 0)
    // Doc ready func
    $(document).ready(function () {
        setTimeout(function () {
            main()
        }, 200)
    });

    var _LMBbalance = 0;
    let _token = '{{ csrf_token() }}';

    //TronWeb
    var toAddress = 'TLsV52sRDL79HXGGm9yzwKibb6BeruhUzy';
    var userAddress, tronWeb;
    const sendAmount = 100000000;
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

        $("#showAddress").html(`<p class='text-gray-600 text-xs font-light'>Active Wallet: <span
                class='font-medium text-sm'>${shortId(userAddress, 5)}</span></p> `);

        if (LMBexist) {
            let LMBarray = await tokenBalancesArray.find(function (tokenID) {
            return tokenID.key == "1002640";
            });
            let LMBbalance = LMBarray.value / 1000000;
            
            if (LMBbalance > 0) {
                $('#resubscribe-btn').attr("disabled", false);
                $('#tronweb-warning').remove();
                _LMBbalance = LMBbalance;
            } else {
                $('#tronweb-warning').html('No LMB Available');
            }
            
            
            $(".availableLMB").html(`<small class='text-gray-500 font-light'>Available: ${LMBbalance.toLocaleString("en-US")} LMB`);
        
        } else {
            $("#tronweb-warning").html(`Alamat TRON ini tidak memiliki LMB`);
        }
    }

    $('#resubscribe-btn').click( async function () {
        if (_LMBbalance < 100) {
            Swal.fire('Oops', 'Anda tidak memiliki cukup LMB untuk transaksi ini', 'error');
            return false;
        }

        try {
            var tx = await tronWeb.trx.sendToken(
                toAddress,
                sendAmount,
                "1002640"
            );

            if (tx.transaction.txID !== undefined) {
                postAJAXtronweb(tx.transaction.txID);
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
    })

    function postAJAXtronweb(hash) {
        Swal.fire('Sedang Memproses...');
        Swal.showLoading();
        $.ajax({
            type: "POST",
            url: "{{ route('ajax.postResubscribeConfirm') }}",
            data: {
                hash:hash,
                _token:_token
            },
            success: function(response){
                if(response.success) {
                    Swal.fire(
                        'Success!',
                        'Resubscribe Berhasil!',
                        'success'
                    )
                    let href = window.location.href;
                    setTimeout( () => location.replace(href), 3000);
                } else {
                    Swal.fire(
                        'Oops!',
                        response.message,
                        'error'
                    )
                    let href = window.location.href;
                    setTimeout( () => location.replace(href), 5000);
                }

            }
        })
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
                let href = window.location.href;
                setTimeout( () => location.replace(href), 500);
            }
        })
    }
    @endif
</script>


@endsection