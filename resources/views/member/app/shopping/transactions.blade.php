@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Select Store/Products Tab --}}
    <div class="px-4 py-2" x-data="{ tab: 'physical' }">

        <div class="mt-3">
            <ul class='flex cursor-pointer justify-center'>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'physical' }" @click="tab = 'physical'">Fisik</li>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'digital' }" @click="tab = 'digital'">Digital</li>
            </ul>
        </div>


        <div class="nm-flat-gray-200 rounded-2xl p-2 overflow-x-scroll">
            <div x-show="tab === 'physical'">
                {{-- Fisik --}}
                <div class="mt-4 p-3 border border-gray-400 border-solid rounded-xl">
                    <div class="-mt-7 w-32 text-lg text-center font-bold text-gray-600 tracking-wider bg-gray-200">
                        Produk Fisik</div>
                    <div class="mt-2 space-y-2">
                        @if (count($physical_tx) > 0) @foreach ($physical_tx as $tx) <div
                            class="nm-flat-white rounded-lg p-2 flex justify-between space-x-2">
                            <div class="w-3/4">
                                <div class="text-xs text-gray-700 font-medium">Seller:
                                    {{ $tx->seller->sellerProfile->shop_name ?? '' }}</div>
                                <div class="text-xs text-gray-600 font-light">Rp{{ number_format($tx->total_price, 0) }}
                                </div>
                                <div style="font-size: 10px" class="text-gray-600 font-light">
                                    {{ date('d-M-y', strtotime($tx->created_at)) }}</div>
                            </div>
                            <div class="w-1/4 flex flex-col items-center">

                                <a href="{{ route('member.shopping.payment', ['masterSalesID' => $tx->id]) }}">
                                    <button style="font-size: 10px"
                                        class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-300 to-purple-300 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Detail</button>
                                </a>
                                @if ($tx->status == 1)
                                <div style="font-size: 10px" class="text-yellow-400 font-medium tracking-tighter">
                                    Proses
                                </div>
                                @endif
                                @if ($tx->status == 2)
                                <div style="font-size: 10px" class="text-green-400 font-medium tracking-tighter">
                                    Tuntas
                                </div>
                                @endif
                                @if ($tx->status == 10)
                                <div style="font-size: 10px" class="text-red-600 font-medium tracking-tighter">
                                    Batal
                                </div>
                                @endif

                            </div>
                        </div>
                        @endforeach
                        <div>{{ $physical_tx->links() }}</div>
                        @else
                        <div class="text-sm text-gray-600 font-medium">
                            Anda belum memiliki riwayat transaksi produk fisik.
                        </div>
                        @endif
                    </div>
                </div>


            </div>
            <div x-show="tab === 'digital'">
                {{-- Digital --}}
                <div class="mt-4 p-3 border border-gray-400 border-solid rounded-xl">
                    <div class="-mt-7 w-32 text-md text-center font-bold text-gray-600 bg-gray-200">
                        Produk Digital</div>
                    <div class="mt-2 space-y-2">
                        @if (count($digital_tx) > 0) @foreach ($digital_tx as $tx) <div
                            class="nm-flat-white rounded-lg p-2 flex justify-between space-x-2">
                            <div class="w-3/4">
                                <div class="text-xs text-gray-700 font-medium">Seller:
                                    {{ $tx->seller->sellerProfile->shop_name ?? '' }}</div>
                                <div class="text-xs text-gray-600 font-light">Rp{{ number_format($tx->ppob_price, 0) }}
                                </div>
                                <div class="text-xs text-gray-400 font-extralight tracking-tighter">
                                    {{ substr($tx->message, 0, 20) }}</div>
                                <div style="font-size: 10px" class="text-gray-600 font-light">
                                    {{ date('d-M-y', strtotime($tx->created_at)) }}</div>
                            </div>
                            <div class="w-1/4 flex flex-col items-center">

                                <a href="{{ route('member.shopping.digitalPayment', ['sale_id' => $tx->id]) }}">
                                    <button style="font-size: 10px"
                                        class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-300 to-purple-300 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Detail</button>
                                </a>
                                @if ($tx->status == 1)
                                <div style="font-size: 10px" class="text-yellow-300 font-medium tracking-tighter">
                                    Proses
                                </div>
                                @endif
                                @if ($tx->status == 2)
                                <div style="font-size: 10px" class="text-green-400 font-medium tracking-tighter">
                                    Tuntas
                                </div>
                                @endif
                                @if ($tx->status == 3)
                                <div style="font-size: 10px" class="text-red-600 font-medium tracking-tighter">
                                    Batal
                                </div>
                                @endif

                            </div>
                        </div>
                        @endforeach
                        <div>{{ $digital_tx->links() }}</div>
                        @else
                        <div class="text-sm text-gray-600 font-medium">
                            Anda belum memiliki riwayat transaksi produk digital.
                        </div>
                        @endif
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

    </script>
    @endsection