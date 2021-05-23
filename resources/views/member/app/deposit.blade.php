@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Select Method Tab --}}
    <div class="px-4 py-2" x-data="{ tab: 'toko' }">

        <div class="mt-3">
            <ul class='flex cursor-pointer justify-center'>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'toko' }" @click="tab = 'toko'">Toko</li>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'produk' }" @click="tab = 'produk'">Produk</li>
            </ul>
        </div>


        <div class="nm-convex-white rounded-2xl p-2 overflow-x-scroll">
            <div x-show="tab === 'toko'">
                {{-- Toko --}}
                <div class="px-2">
                    <div>
                        <p class="mb-2 text-lg text-gray-600 font-light text-center">Cari Toko</p>
                        <p class="text-xs text-gray-400 font-light py-0 leading-3">Ketikkan 3-4 huruf awal nama toko
                            lalu klik
                            pada
                            nama yang
                            tampil, lalu klik "Go"</p>
                    </div>
                    <div>
                        <div class="mt-2 nm-inset-gray-50 p-2 rounded-2xl">
                            <input class="ml-2 bg-transparent focus:outline-none w-full" type="text" name="input_toko"
                                id="get_id">
                        </div>
                        <div class="px-2">
                            <input type="hidden" id="id_get_id">
                            <ul class="text-sm font-light max-h-32 overflow-auto border border-solid border-gray-200 w-full hidden"
                                id="get_id-box"></ul>
                        </div>
                        <div class="mt-2 flex justify-end">
                            <a id="check-store-btn" href="#"
                                class="rounded-lg py-1 px-2 h-8 w-20 bg-gradient-to-br from-green-200 to-purple-300 text-md text-center font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Go</a>
                        </div>
                    </div>
                </div>


            </div>
            <div x-show="tab === 'produk'">
                {{-- Produk --}}
                <p class="mb-2 text-lg text-gray-600 font-light text-center">Cari Produk</p>
                <p class="text-md text-red-600 text-center">(Dalam proses konstruksi)</p>
                <div class="mt-2 nm-inset-gray-50 p-2 rounded-2xl">
                    <input class="ml-2 bg-transparent focus:outline-none w-full" type="text" name="product"
                        id="product">
                </div>
                <div class="mt-2 flex justify-end">
                    <a id="check-store-btn" href="#"
                        class="rounded-lg py-1 px-2 h-8 w-20 bg-gradient-to-br from-green-200 to-purple-300 text-md text-center font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Go</a>
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