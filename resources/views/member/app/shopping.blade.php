@extends('member.components.main')
@section('content')

{{-- Top bar --}}
<div class="bg-white px-4 py-2">
    <div class="mt-2 flex justify-between">
        {{-- Back --}}
        <div class="flex">
            <button onclick="history.back()">
                <svg class="w-4" viewBox="0 0 20 20">
                    <path fill="black"
                        d="M3.24,7.51c-0.146,0.142-0.146,0.381,0,0.523l5.199,5.193c0.234,0.238,0.633,0.064,0.633-0.262v-2.634c0.105-0.007,0.212-0.011,0.321-0.011c2.373,0,4.302,1.91,4.302,4.258c0,0.957-0.33,1.809-1.008,2.602c-0.259,0.307,0.084,0.762,0.451,0.572c2.336-1.195,3.73-3.408,3.73-5.924c0-3.741-3.103-6.783-6.916-6.783c-0.307,0-0.615,0.028-0.881,0.063V2.575c0-0.327-0.398-0.5-0.633-0.261L3.24,7.51 M4.027,7.771l4.301-4.3v2.073c0,0.232,0.21,0.409,0.441,0.366c0.298-0.056,0.746-0.123,1.184-0.123c3.402,0,6.172,2.709,6.172,6.041c0,1.695-0.718,3.24-1.979,4.352c0.193-0.51,0.293-1.045,0.293-1.602c0-2.76-2.266-5-5.046-5c-0.256,0-0.528,0.018-0.747,0.05C8.465,9.653,8.328,9.81,8.328,9.995v2.074L4.027,7.771z">
                    </path>
                </svg>
            </button>

        </div>

        <div>
            <h1 class="font-light text-sm text-gray-600">{{$title}}</h1>
        </div>

        {{-- Logout --}}
        <div class="flex justify-end">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="rounded-full p-1 bg-red-400">
                    <svg class="w-3" viewBox="0 0 20 20">
                        <path fill="black" d="M13.53,2.238c-0.389-0.164-0.844,0.017-1.01,0.41c-0.166,0.391,0.018,0.845,0.411,1.01
                                                    								c2.792,1.181,4.598,3.904,4.6,6.937c0,4.152-3.378,7.529-7.53,7.529c-4.151,0-7.529-3.377-7.529-7.529
                                                    								C2.469,7.591,4.251,4.878,7.01,3.683C7.401,3.515,7.58,3.06,7.412,2.67c-0.17-0.392-0.624-0.571-1.014-0.402
                                                    								c-3.325,1.44-5.472,4.708-5.47,8.327c0,5.002,4.069,9.071,9.071,9.071c5.003,0,9.073-4.07,9.073-9.071
                                                    								C19.07,6.939,16.895,3.659,13.53,2.238z"></path>
                        <path fill="black"
                            d="M9.999,7.616c0.426,0,0.771-0.345,0.771-0.771v-5.74c0-0.426-0.345-0.771-0.771-0.771
                                                    								c-0.427,0-0.771,0.345-0.771,0.771v5.74C9.228,7.271,9.573,7.616,9.999,7.616z">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</div>

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Selet Store/Products Tab --}}
    <div class="px-4 py-2" x-data="{ tab: 'toko' }">
        {{-- History Tab--}}
        <div class="mt-3 border-gray-50 border-b-2">
            <ul class='flex cursor-pointer justify-center'>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-200 text-sm'
                    :class="{ 'active': tab === 'toko' }" @click="tab = 'toko'">Toko</li>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-200 text-sm'
                    :class="{ 'active': tab === 'produk' }" @click="tab = 'produk'">Produk</li>
            </ul>
        </div>


        <div class="nm-convex-gray-100 rounded-2xl p-2 overflow-x-scroll">
            <div x-show="tab === 'toko'">
                {{-- Toko --}}
                <div class="px-2">
                    <div>
                        <p class="text-lg text-gray-600 font-light">Cari Toko</p>
                        <small class="text-xs text-gray-500 font-light py-0">Ketikkan 3-4 huruf awal nama toko lalu klik
                            pada
                            nama yang
                            tampil, lalu tekan Cari</small>
                    </div>
                    <div>
                        <div class="mt-2 nm-inset-gray-50 p-2 rounded-2xl">
                            <input class="bg-transparent focus:outline-none w-full" type="text" name="input_toko"
                                id="input_toko">
                        </div>
                        <div class="flex justify-end">
                            <button
                                class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Cari</button>
                        </div>
                    </div>
                </div>


            </div>
            <div x-show="tab === 'produk'">
                {{-- Produk --}}

            </div>

        </div>
    </div>


</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<style>
    .active {
        background-color: white;
        color: black;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection