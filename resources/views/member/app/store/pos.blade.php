@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        <div class="mt-4 p-3 border border-gray-400 border-solid rounded-xl">
            <div class="-mt-7 w-32 text-lg font-bold text-gray-600 tracking-wider bg-gray-200">Input Belanja</div>
            <div class="mt-3 flex justify-between">
                <button id="self_buy_btn"
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Beli Putus</button>
                <button id="change_buyer_btn"
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Untuk Member</button>
            </div>
            {{-- Change Buyer --}}
            <div id="change_buyer_div" class="hidden mt-2 mb-8">
                <div style="font-size: 10px" class="text-gray-400 font-light">Ketikkan 3-4 huruf awal
                    username pembeli, akan tampil list nama, silakan klik username yang diinginkan.</div>
                <div class="mt-2 nm-inset-gray-200 p-2 rounded-2xl">
                    <input class="ml-2 bg-transparent focus:outline-none w-full" type="text" name="input_toko"
                        id="get_id" placeholder="cari username..." autocomplete="off">
                </div>
                <div class="px-2">
                    <ul class="text-sm font-light max-h-32 overflow-auto border border-solid border-gray-200 w-full hidden"
                        id="get_id-box"></ul>
                </div>
            </div>
            {{-- Buyer Info --}}
            <div class="hidden mt-6" id="buyer-info">
                <hr class="my-1 border border-b border-gray-300">
                <div class="text-xs text-gray-500">Username Pembeli:</div>
                <div class="text-sm text-gray-600" id="buyer"></div>
                <form action="" method="POST" id="input_pos_form">
                    @csrf
                    <input type="hidden" name="buyer_id" id="buyer_id">
                    <div class="flex justify-end">
                        <button class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Input Belanja</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Shop's metrics --}}
        <div class="mt-6 p-3 border border-gray-400 border-solid rounded-xl">
            <div class="-mt-7 w-32 text-lg font-bold text-gray-600 tracking-wider bg-gray-200">Laporan Toko</div>
            <div class="mt-3">
                <div class="text-sm text-gray-500 font-bold">Penjualan Bulan ini:</div>
                <div class="mt-3 flex justify-evenly space-x-2">
                    <div class="nm-flat-gray-100 p-2 rounded-lg flex-1 flex-col justify-center">
                        <div class="text-xs text-gray-500">Produk Digital</div>
                        <a href="{{ route('member.store.transactions') }}">
                            <div class="text-md text-gray-600">Rp{{ number_format($sales->digital) }}</div>
                        </a>
                    </div>
                    <div class="nm-flat-gray-100 p-2 rounded-lg flex-1 flex-col justify-center">
                        <div class="text-xs text-gray-500">Produk Fisik</div>
                        <a href="{{ route('member.store.transactions') }}">
                            <div class="text-md text-gray-600">Rp{{ number_format($sales->physical) }}</div>
                        </a>
                    </div>
                </div>
                {{-- Next reward estimation --}}
                <div class="mt-3 bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl px-2 py-2 flex justify-around items-center">
                    <div class="flex flex-col align-middle items-center">
                        <img class="object-contain w-10" src="/image/koin_lmb2.png" alt="koin LMB">
                        <div class="mt-1">
                            <a href="{{ route('member.claim.shoppingReward') }}"
                                class="cursor-pointer rounded-lg py-1 px-2 bg-gradient-to-br from-yellow-200 to-red-200 text-sm text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Claim</a>
                        </div>
                    </div>
                    <div>
                        @php
                        $sold = $sales->total;
                        $multiplier = 1;
                        // LMB Reward Rate
                        $rate = 0.01;
                        // Calculate the base spending (per 1000)
                        $base = floor($sold / 1000);
                        $reward = number_format($base * $rate * $multiplier, 2);
                        @endphp
                        <div class="text-gray-600 text-sm">Estimasi Reward LMB:</div>
                        <div class="font-extralight text-2xl text-right">{{ $reward }} LMB</div>
                    </div>
                </div>
                {{-- Last Month Best Seller --}}
                <div class="mt-3">
                    <div class="text-sm text-gray-500 font-bold text-gray-600">Best Selling Bulan Lalu</div>
                    {{-- Best Selling Physical Product --}}
                    <div class="mt-3">
                        <div class="text-xs text-gray-400">Produk Fisik</div>
                        @if (count($bestSells->physical) > 0)
                        <table class="table table-auto w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="px-1 py-2">Produk</th>
                                    <th class="px-1 py-2">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bestSells->physical as $item)
                                    <tr>
                                        <td class="px-1 py-2">{{ $item->product }}</td>
                                        <td class="px-1 py-2 text-center">{{ $item->sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="my-3 text-sm text-gray-500 font-bold text-center">Tidak cukup data dari bulan lalu.</div>
                        @endif
                        
                    </div>
                    {{-- Best Selling Digital Product --}}
                    <div class="mt-3">
                        <div class="text-xs text-gray-400">Produk Digital</div>
                        @if (count($bestSells->digital) > 0)
                        <table class="table table-auto w-full text-sm text-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-1 py-2">Produk</th>
                                    <th class="px-1 py-2">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bestSells->digital as $item)
                                    <tr>
                                        <td class="px-1 py-2">{{ $item->buyer_code }}</td>
                                        <td class="px-1 py-2 text-center">{{ $item->sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="my-3 text-sm text-gray-500 font-bold text-center">Tidak cukup data dari bulan lalu.</div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    const seller_id = '{{ $user->id }}';
    // SelfBuy
    $('#self_buy_btn').click( function () {
        $('#buyer_id').val(seller_id);
        $('#input_pos_form').submit();
    } )

    // Change Buyer
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
            $("#buyer").html(valNew[1]);
            $("#get_id").val(valNew[1]);
            $("#buyer_id").val(valNew[0]);
            $("#get_id-box").hide();
            $('#change_buyer_div').hide();
            $('#change_buyer_btn').show();
            $('#change_buyer_btn').text('Ganti Pembeli');
            $('#buyer-info').show();
        }

        $('#change_buyer_btn').click( function () {
            $('#change_buyer_div').show();
            $('#change_buyer_btn').hide();
        })
</script>
@endsection