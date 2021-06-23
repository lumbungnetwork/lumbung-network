@extends('market.components.main')
@section('content')

@php
// Selector to switch between Physical or Digital transactions
$data = $masterSalesData;
if ($type == 'digital') {
    $data = $digitalSaleData;
    if ($data) {
        $return_buy = json_decode($data->return_buy, true);
    }
}
@endphp

<div class="mt-2 flex flex-col justify-center px-6">
    <div class="w-full max-w-md mx-auto">
        {{-- Action navs --}}
        <div class="my-1 py-2 flex justify-between">
            {{-- Back --}}
            <div class="text-sm font-extrabold">
                <button onclick="history.back()"
                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-pink-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                &#8592; Back</button>
            </div>
            <div class="flex justify-end space-x-1">
                {{-- Share --}}
                <div>
                    <button id="share-btn" class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-yellow-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                        <div class="flex space-x-1 items-center">
                            <svg class="w-5" viewBox="0 0 20 20">
                                <path fill="black" d="M8.416,3.943l1.12-1.12v9.031c0,0.257,0.208,0.464,0.464,0.464c0.256,0,0.464-0.207,0.464-0.464V2.823l1.12,1.12c0.182,0.182,0.476,0.182,0.656,0c0.182-0.181,0.182-0.475,0-0.656l-1.744-1.745c-0.018-0.081-0.048-0.16-0.112-0.224C10.279,1.214,10.137,1.177,10,1.194c-0.137-0.017-0.279,0.02-0.384,0.125C9.551,1.384,9.518,1.465,9.499,1.548L7.76,3.288c-0.182,0.181-0.182,0.475,0,0.656C7.941,4.125,8.234,4.125,8.416,3.943z M15.569,6.286h-2.32v0.928h2.32c0.512,0,0.928,0.416,0.928,0.928v8.817c0,0.513-0.416,0.929-0.928,0.929H4.432c-0.513,0-0.928-0.416-0.928-0.929V8.142c0-0.513,0.416-0.928,0.928-0.928h2.32V6.286h-2.32c-1.025,0-1.856,0.831-1.856,1.856v8.817c0,1.025,0.832,1.856,1.856,1.856h11.138c1.024,0,1.855-0.831,1.855-1.856V8.142C17.425,7.117,16.594,6.286,15.569,6.286z"></path>
                            </svg>
                            <div>
                                Share
                            </div>
                        </div>
                    </button>
                </div>
                {{-- Print --}}
                <div>
                    <button id="print-btn" onclick="window.print()" class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                        <div class="flex space-x-1 items-center">
                            <svg class="w-5" viewBox="0 0 20 20">
                                <path fill="black" d="M7.126,14.692h5.748c0.39,0,0.706-0.316,0.706-0.706c0-0.39-0.315-0.706-0.706-0.706H7.126c-0.39,0-0.706,0.316-0.706,0.706C6.42,14.376,6.736,14.692,7.126,14.692z"></path>
                                <path fill="black" d="M7.126,16.899h4.641c0.39,0,0.706-0.315,0.706-0.706c0-0.389-0.316-0.706-0.706-0.706H7.126c-0.39,0-0.706,0.317-0.706,0.706C6.42,16.584,6.736,16.899,7.126,16.899z"></path>
                                <path fill="black" d="M18.933,3.163h-3.309V1.686c0-0.392-0.316-0.706-0.706-0.706H5.061c-0.39,0-0.706,0.314-0.706,0.706v1.477H1.067c-0.39,0-0.706,0.317-0.706,0.706v7.997c0,0.391,0.316,0.706,0.706,0.706h3.301v5.743c0,0.39,0.316,0.706,0.706,0.706h7.801c0.192,0,0.373-0.077,0.507-0.215l2.054-2.121c0.127-0.131,0.198-0.306,0.198-0.491v-3.622h3.3c0.39,0,0.706-0.315,0.706-0.706V3.869C19.638,3.481,19.323,3.163,18.933,3.163z M14.222,15.908l-1.647,1.701H5.779v-5.037h8.443V15.908z M18.227,11.16h-3.3H5.074H1.773V4.575h3.288c0.39,0,0.706-0.314,0.706-0.706V2.392h8.446v1.477c0,0.392,0.316,0.706,0.706,0.706h3.309V11.16z"></path>
                            </svg>
                            <div>
                                Print
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-white w-full" id="section-to-print">
            {{-- Header --}}
            <div class="my-2 bg-yellow-100 w-full px-2 py-4 flex justify-between">
                <div>
                    <img class="h-8" src="/image/icon_lumbung_1x.png" alt="lumbung network logo">
                </div>
                <div class="text-right">
                    @if ($data)
                    <div class="text-xs text-gray-500">{{ date('d M Y', strtotime($data->created_at)) }}</div>
                    <div class="text-xs text-gray-500">#{{ $data->id }}</div>
                    @endif
                </div>
            </div>
            @if ($data)
            {{-- Buyer/Seller Details --}}
            <div class="my-1 p-2 flex justify-around">
                <div class="flex flex-col justify-center text-center">
                    <div class="text-xs text-gray-400">Buyer</div>
                    <div class="text-xs text-gray-500">{{ $data->buyer->username }}</div>
                </div>
                <div class="flex flex-col justify-center text-center">
                    <div class="text-xs text-gray-400">Seller</div>
                    <div class="text-xs text-gray-500">
                        {{ $data->seller->sellerProfile->shop_name ?? $data->seller->username }}</div>
                </div>
            </div>
            {{-- Transaction Details --}}
            <div class="p-2">
                <div class="text-md font-bold text-gray-400">Detail Transaksi</div>
                <hr class="border border-b border-gray-400 w-full">
            </div>
            <div class="bg-gray-50 p-2">
                {{-- Physical --}}
                @if ($type == 'physical' && count($salesData) > 0)
                @foreach ($salesData as $row)
                {{-- Pretty Receipt view --}}
                <div class="flex justify-between space-x-1 exclude-print">
                    <div class="w-1/4">
                        <img class="object-cover rounded-2xl"
                            src="{{ asset('/storage/products') }}/{{$row->product->image}}" alt="product-picture">
                    </div>
                    <div class="w-3/4">
                        <div class="text-sm text-gray-800">
                            {{$row->product->name}}
                        </div>
                        <div class="text-xs text-gray-500 font-light"><b>{{ number_format($row->amount, 0) }}x</b>
                            {{$row->product->size}}</div>
                        <div class="text-xs font-light">Rp{{number_format(($row->sale_price/$row->amount))}}</div>
                        <hr class="my-1 border-b-0 border-gray-300">
                        <div class="text-xs text-right">Subtotal: Rp{{number_format($row->sale_price)}}</div>
                    </div>
                </div>
                {{-- Printing view --}}
                <div class="hidden justify-between space-x-1 include-to-print">
                    <div class="w-3/4 text-sm text-gray-800">
                        <b>{{ number_format($row->amount, 0) }}x </b> {{$row->product->name}}
                    </div>
                    <div class="w-1/4 text-sm text-gray-800 font-bold">{{number_format($row->sale_price)}}</div>
                </div>
                <hr class="my-2 border-b-0 border-gray-400">
                @endforeach
                <div class="text-sm font-medium text-right">Total:
                    <b>Rp{{number_format($masterSalesData->total_price)}}</b></div>
                @endif
                {{-- Digital --}}
                @if ($type == 'digital' )
                <div class="p-1">
                    <div class="text-sm font-bold text-gray-600">No.: {{ $data->product_name }}</div>
                    <div class="text-sm text-gray-500">Produk: {{ $data->buyer_code }}</div>
                    <div class="text-xs font-light text-gray-500">{{ $data->message }}</div>
                </div>
                {{-- Specific details --}}
                <div class="p-1 text-gray-500 text-xs">
                    {{-- PLN Prepaid --}}
                    @if ($data->type == 3)
                    <?php $separate = explode('/', $return_buy['data']['sn']) ?>
                    <br>
                    a/n: {{$separate[1]}}
                    <br>
                    @if(isset($separate[2], $separate[3], $separate[3]))
                    Tipe/Daya: {{$separate[2]}} / {{$separate[3]}}
                    <br>
                    Jumlah KWh: {{$separate[4]}}
                    <br>
                    @else
                    Jumlah KWh: {{$separate[2]}}
                    <br>
                    @endif
                    <br>
                    Kode Token:
                    <br>
                    <span style="font-size: 14px; font-weight: 700;">{{$separate[0]}}</span>
                    @endif

                    {{-- Postpaid --}}
                    @if ($data->type > 2 && $data->type < 21 )
                        a/n: {{$return_buy['data']['customer_name'] ?? ''}}
                    @endif
                    {{-- PDAM, PGN & PLN Pasca --}}
                    @if ($data->type == 8)
                    <br>
                    @if(isset($return_buy['data']['desc']['alamat']))
                    Alamat: {{$return_buy['data']['desc']['alamat'] ?? ''}}
                    @endif
                    @endif
                    @if ($data->type == 8 || $data->type == 5 || $data->type == 9)
                    <br>
                    <br>
                    Detail:
                    <br>
                    Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan'] ?? ''}}
                    <br>
                    @if (isset($return_buy['data']['desc']['detail']))
                    @foreach ($return_buy['data']['desc']['detail'] as $detail)
                    <br>
                    Periode Tagihan: {{$detail['periode'] ?? ''}}
                    <br>
                    Meter Awal: {{$detail['meter_awal']  ?? ''}}
                    <br>
                    Meter Akhir: {{$detail['meter_akhir']  ?? ''}}
                    <br>
                    Denda: Rp{{$detail['denda']  ?? '0'}}
                    <br>

                    @endforeach
                    @endif
                    @endif

                    {{-- Telkom --}}
                    @if ($data->type == 7)
                    <br>
                    Detail:
                    <br>
                    Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']  ?? ''}}
                    <br>
                    @foreach ($return_buy['data']['desc']['detail'] as $detail)
                    <br>
                    Periode Tagihan: {{$detail['periode']  ?? ''}}
                    <br>
                    Nilai Tagihan: Rp{{number_format($detail['nilai_tagihan'] + 1000)}}
                    <br>
                    Admin: Rp{{number_format($detail['admin'])}}
                    <br>
                    @endforeach

                    @endif

                    {{-- BPJS --}}
                    @if ($data->type == 4)

                    <br>
                    Alamat: {{$return_buy['data']['desc']['alamat'] ?? ''}}
                    <br>
                    Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan'] ?? ''}}
                    <br>
                    Jumlah Peserta: {{$return_buy['data']['desc']['jumlah_peserta'] ?? ''}}

                    @endif
                </div>

                {{-- Billing Amount --}}
                <hr class="my-3 border border-b border-gray-500 w-full">
                @if ($data->type > 2 && $data->type < 21) <div class="flex justify-between">
                    <div class="text-xs text-gray-600">Tagihan:</div>
                    <div class="text-sm text-gray-600">Rp{{ number_format($data->ppob_price - 2500) }}</div>
            </div>
            <div class="flex justify-between">
                <div class="text-xs text-gray-600">Biaya Adm.:</div>
                <div class="text-sm text-gray-600">Rp2500
                    <hr class="border border-b border-gray-500">
                </div>
            </div>
            @endif
            <div class="flex justify-between">
                <div class="text-sm text-gray-600">Total Bayar:</div>
                <div class="text-md font-extrabold text-gray-600">Rp{{ number_format($data->ppob_price) }}</div>
            </div>
            @endif
        </div>
        {{-- Payment --}}
        <div class="my-1 p-2">
            <div class="text-sm text-gray-500">Pembayaran: {{ ($data->buy_metode == 1) ? 'TUNAI' : 'eIDR' }}</div>
        </div>
        @else
        <div class="my-4 p4 h-32 flex flex-col justify-center">
            <div class="text-md font-bold text-gray-500 text-center">Transaksi tidak ditemukan</div>
        </div>

        @endif

        {{-- Footer --}}
        <div class="mt-4 py-4 flex flex-col justify-center text-center">
            <div class="text-sm text-gray-600">Lumbung Network</div>
            <div class="text-xs font-light italic text-gray-500">Memberdayakan Pengeluaran Tetap Menjadi Penghasilan
                Tetap
            </div>
            <div class="text-xs text-gray-500 underline">https://lumbung.network</div>
        </div>
    </div>
</div>

<div class="mt-3 p-2 flex justify-center">
    <a href="https://member.{{ config('services.app.domain') }}">
        <button class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-yellow-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Join Lumbung Network!</button>
    </a>
</div>

@endsection


@section('style')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .exclude-print, .exclude-print * {
            display: none !important;
        }
        .include-to-print, .include-to-print * {
            display: flex !important;
        }
        #section-to-print, #section-to-print * {
            visibility: visible;
        }
        #section-to-print {
            position: absolute;
            left: 0;
            top: 0;
        }

        @page {
            size: 58mm 200mm;
            margin: 0;
        }
    }
    
</style>
@endsection

@section('scripts')
    <script>
        const shareData = {
            title: 'Detail Transaksi Lumbung Network',
            text: 'Detail transaksi #' + '{{ $data->id }}',
            url: window.location.href,
        };

        $('#share-btn').click( function () {
            Swal.fire({
                title: 'Share Detail Transaksi',
                text: "Apakah anda hendak membagikan Struk Transaksi ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Share!'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    try {
                        await navigator.share(shareData);
                        Swal.fire('Shared!', 'Receipt Transaksi ini telah berhasil di-share', 'success');
                    } catch(err) {
                        Swal.fire('Error', 'Perangkat anda tidak mendukung link sharing', 'error');
                    }
                }
            })
        });
    </script>
@endsection