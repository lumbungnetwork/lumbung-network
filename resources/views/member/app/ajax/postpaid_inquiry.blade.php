@if ($status)
@php
if($type == 4){
$nama = 'BPJS';
$selling_price = $data['selling_price'];
}
if($type == 5){
$nama = 'PLN';
$selling_price = $data['selling_price'] + 500;
}
if($type == 6){
$nama = 'HP Pascabayar';
$selling_price = $data['selling_price'] + 1000;
}
if($type == 7){
$nama = 'Telkom PTSN';
$selling_price = $data['selling_price'] + 1000;
}
if($type == 8){
$nama = 'PDAM';
$selling_price = $data['selling_price'] + 800;
}
if($type == 9){
$nama = 'PGN';
$selling_price = $data['selling_price'] + 1000;
}
if($type == 10){
$nama = 'Multifinance';
$selling_price = $data['selling_price'] + 2500;
}
@endphp
<div class="-mx-4">
    <div class="text-sm font-medium text-gray-500">Cek Tagihan {{ $nama }}</div>
    <div class="mt-3 text-left p-2">
        <div class="nm-inset-gray-200 rounded-xl p-2 text-xs text-gray-600 font-light">
            No Pel.: {{ $data['customer_no'] }} <br>
            Nama Pel: {{ $data['customer_name'] }} <br>
            Tagihan: Rp{{ number_format($selling_price, 0) }} <br>
        </div>
    </div>
    <div class="mt-3 flex justify-end space-x-2">
        <button onclick="submit()"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Bayar</button>
        <button onclick="Swal.close()"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batal</button>
    </div>

    @else
    <div class="text-sm font-medium text-red-600">Cek Tagihan Gagal</div>
    <div class="text-xs text-gray-600">{{$message}}</div>
    <div class="mt-3 flex justify-end">
        <button onclick="Swal.close()"
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Batal</button>
    </div>
    @endif
</div>