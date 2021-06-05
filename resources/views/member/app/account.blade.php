@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Menus --}}
    <div class="p-4 flex flex-wrap justify-between space-x-1">
        <a href="{{ route('member.profile') }}"
            class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center">
            <div>
                <svg class="w-6" viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z">
                    </path>
                </svg>
            </div>
            <div class="text-xs text-gray-500 font-extralight">Profile</div>
        </a>
        <a href="{{ route('member.tron') }}"
            class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center">
            <div>
                <svg class="w-5" viewBox="0 0 64 64">
                    <path fill="gray"
                        d="M61.55,19.28c-3-2.77-7.15-7-10.53-10l-.2-.14a3.82,3.82,0,0,0-1.11-.62l0,0C41.56,7,3.63-.09,2.89,0a1.4,1.4,0,0,0-.58.22L2.12.37a2.23,2.23,0,0,0-.52.84l-.05.13v.71l0,.11C5.82,14.05,22.68,53,26,62.14c.2.62.58,1.8,1.29,1.86h.16c.38,0,2-2.14,2-2.14S58.41,26.74,61.34,23a9.46,9.46,0,0,0,1-1.48A2.41,2.41,0,0,0,61.55,19.28ZM36.88,23.37,49.24,13.12l7.25,6.68Zm-4.8-.67L10.8,5.26l34.43,6.35ZM34,27.27l21.78-3.51-24.9,30ZM7.91,7,30.3,26,27.06,53.78Z">
                    </path>
                </svg>
            </div>
            <div class="text-xs text-gray-500 font-extralight">TRON</div>
        </a>
        <a href="{{ route('member.account.bank') }}"
            class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center">
            <div>
                <svg class="w-6" viewBox="0 0 20 20">
                    <path fill="gray" d="M15.123,9.991c-0.944,0-1.71,0.766-1.71,1.71c0,0.945,0.766,1.711,1.71,1.711c0.945,0,1.711-0.766,1.711-1.711
                								C16.834,10.756,16.068,9.991,15.123,9.991z M15.703,12.281h-1.141v-1.141h1.141V12.281z M17.984,4.867h-2.288v-0.57h-0.024
                								c0.011-0.086,0.025-0.17,0.025-0.26V2.852c0-1.092-0.838-1.977-1.871-1.977H2.745C1.8,0.875,1.027,1.618,0.9,2.58H0.875v15.404
                								c0,0.629,0.511,1.141,1.141,1.141h15.969c0.629,0,1.14-0.512,1.14-1.141V6.008C19.124,5.377,18.613,4.867,17.984,4.867z
                								 M2.016,2.586c0-0.315,0.255-0.57,0.57-0.57h11.406c0.314,0,0.57,0.255,0.57,0.57v2.275H2.016V2.586z M17.984,17.414
                								c0,0.314-0.257,0.57-0.57,0.57H2.586c-0.315,0-0.57-0.256-0.57-0.57V6.578c0-0.315,0.255-0.57,0.57-0.57h14.828
                								c0.313,0,0.57,0.255,0.57,0.57V17.414z"></path>
                </svg>
            </div>
            <div class="text-xs text-gray-500 font-extralight">Bank</div>
        </a>
        <a href="{{ route('member.security') }}"
            class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center">
            <div>
                <svg class="w-6" viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M17.308,7.564h-1.993c0-2.929-2.385-5.314-5.314-5.314S4.686,4.635,4.686,7.564H2.693c-0.244,0-0.443,0.2-0.443,0.443v9.3c0,0.243,0.199,0.442,0.443,0.442h14.615c0.243,0,0.442-0.199,0.442-0.442v-9.3C17.75,7.764,17.551,7.564,17.308,7.564 M10,3.136c2.442,0,4.43,1.986,4.43,4.428H5.571C5.571,5.122,7.558,3.136,10,3.136 M16.865,16.864H3.136V8.45h13.729V16.864z M10,10.664c-0.854,0-1.55,0.696-1.55,1.551c0,0.699,0.467,1.292,1.107,1.485v0.95c0,0.243,0.2,0.442,0.443,0.442s0.443-0.199,0.443-0.442V13.7c0.64-0.193,1.106-0.786,1.106-1.485C11.55,11.36,10.854,10.664,10,10.664 M10,12.878c-0.366,0-0.664-0.298-0.664-0.663c0-0.366,0.298-0.665,0.664-0.665c0.365,0,0.664,0.299,0.664,0.665C10.664,12.58,10.365,12.878,10,12.878">
                    </path>
                </svg>
            </div>
            <div class="text-xs text-gray-500 font-extralight">Security</div>
        </a>
        <button id="telegram-btn"
            class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center focus:outline-none">
            <div class="relative">
                <span id="telegram-badge" class="absolute top-0 -right-3 rounded-full w-2 h-2"></span>
                <svg class="w-6" viewBox="0 0 240 240">
                    <path
                        d="M66.964 134.874s-32.08-10.062-51.344-16.002c-17.542-6.693-1.57-14.928 6.015-17.59 7.585-2.66 186.38-71.948 194.94-75.233 8.94-4.147 19.884-.35 14.767 18.656-4.416 20.407-30.166 142.874-33.827 158.812-3.66 15.937-18.447 6.844-18.447 6.844l-83.21-61.442z"
                        fill="none" stroke="gray" stroke-width="10" />
                    <path d="M92.412 201.62s4.295.56 8.83-3.702c4.536-4.26 26.303-25.603 26.303-25.603" fill="none"
                        stroke="gray" stroke-width="10" />
                    <path
                        d="M66.985 134.887l28.922 14.082-3.488 52.65s-4.928.843-6.25-3.613c-1.323-4.455-19.185-63.12-19.185-63.12z"
                        fill-rule="evenodd" stroke="gray" stroke-width="10" stroke-linejoin="bevel" />
                    <path d="M66.985 134.887s127.637-77.45 120.09-71.138c-7.55 6.312-91.168 85.22-91.168 85.22z"
                        fill-rule="evenodd" stroke="gray" stroke-width="9.67" stroke-linejoin="bevel" />
                </svg>
            </div>
            <div class="text-xs text-gray-500 font-extralight">Telegram</div>
        </button>

    </div>
    <div class="p-4">
        {{-- Starter --}}
        @if ($user->user_type == 9)
        <div class="nm-concave-gray-100 rounded-2xl p-2">
            <div>
                <h4 class="text-md text-gray-600 font-semibold tracking-wider">Starter</h4>
                <div class="text-xs text-gray-500">Free Membership</div>
            </div>
            <div class="flex justify-end">
                <div>
                    <a href="{{ route('member.account.membership') }}"
                        class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-200 to-purple-300 text-sm text-center font-bold text-purple-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Go
                        Premium</a>
                </div>
            </div>
        </div>
        @endif
        {{-- Premium --}}
        @if ($user->user_type == 10)
        <div class="bg-gradient-to-r from-green-100 to-yellow-300 opacity-90 shadow rounded-2xl p-2">
            <div>
                <h4 class="text-md text-gray-800 font-bold tracking-wider">Premium</h4>
                <div class="text-xs text-gray-600">Full Membership</div>
            </div>
            <div class="mt-2 flex justify-end">
                <div style="font-size: 10px" class="font-light text-gray-600 tracking-tight text-right">
                    Expired at: {{ date('d-m-Y', strtotime($user->expired_at)) }} <br>
                    Revert to Starter
                </div>
            </div>
        </div>
        @endif

    </div>

    @if ($user->is_store)
    {{-- Store Menu --}}
    <div class="px-2">
        <div class="mt-4 p-3 border border-gray-400 border-solid rounded-xl">
            <div class="-mt-7 w-12 text-lg font-bold text-gray-600 tracking-wider bg-gray-200">Toko</div>
            <div class="mt-2 flex flex-wrap justify-around space-x-2">
                {{-- Info Toko --}}
                <a class="mt-1 mx-0.5 flex flex-col w-8 items-center" href="{{ route('member.store.info') }}">
                    <div
                        class="rounded-2xl bg-gradient-to-br from-blue-300 to-green-300 w-12 h-12 p-1 flex justify-center items-end">
                        <svg class="w-8" viewBox="0 0 20 20">
                            <path fill="gray"
                                d="M18.121,9.88l-7.832-7.836c-0.155-0.158-0.428-0.155-0.584,0L1.842,9.913c-0.262,0.263-0.073,0.705,0.292,0.705h2.069v7.042c0,0.227,0.187,0.414,0.414,0.414h3.725c0.228,0,0.414-0.188,0.414-0.414v-3.313h2.483v3.313c0,0.227,0.187,0.414,0.413,0.414h3.726c0.229,0,0.414-0.188,0.414-0.414v-7.042h2.068h0.004C18.331,10.617,18.389,10.146,18.121,9.88 M14.963,17.245h-2.896v-3.313c0-0.229-0.186-0.415-0.414-0.415H8.342c-0.228,0-0.414,0.187-0.414,0.415v3.313H5.032v-6.628h9.931V17.245z M3.133,9.79l6.864-6.868l6.867,6.868H3.133z">
                            </path>
                        </svg>
                    </div>
                    <p style="font-size: 10px" class="font-light text-center text-gray-600">Info Toko</p>
                </a>
                {{-- Inventory --}}
                <a class="mt-1 mx-0.5 flex flex-col w-8 items-center" href="{{ route('member.store.inventory') }}">
                    <div
                        class="rounded-2xl bg-gradient-to-br from-red-300 to-purple-200 w-12 h-12 p-1 flex justify-center items-end">
                        <svg class="w-8" viewBox="0 0 20 20">
                            <path fill="gray"
                                d="M16.803,18.615h-4.535c-1,0-1.814-0.812-1.814-1.812v-4.535c0-1.002,0.814-1.814,1.814-1.814h4.535c1.001,0,1.813,0.812,1.813,1.814v4.535C18.616,17.803,17.804,18.615,16.803,18.615zM17.71,12.268c0-0.502-0.405-0.906-0.907-0.906h-4.535c-0.501,0-0.906,0.404-0.906,0.906v4.535c0,0.502,0.405,0.906,0.906,0.906h4.535c0.502,0,0.907-0.404,0.907-0.906V12.268z M16.803,9.546h-4.535c-1,0-1.814-0.812-1.814-1.814V3.198c0-1.002,0.814-1.814,1.814-1.814h4.535c1.001,0,1.813,0.812,1.813,1.814v4.534C18.616,8.734,17.804,9.546,16.803,9.546zM17.71,3.198c0-0.501-0.405-0.907-0.907-0.907h-4.535c-0.501,0-0.906,0.406-0.906,0.907v4.534c0,0.501,0.405,0.908,0.906,0.908h4.535c0.502,0,0.907-0.406,0.907-0.908V3.198z M7.733,18.615H3.198c-1.002,0-1.814-0.812-1.814-1.812v-4.535c0-1.002,0.812-1.814,1.814-1.814h4.535c1.002,0,1.814,0.812,1.814,1.814v4.535C9.547,17.803,8.735,18.615,7.733,18.615zM8.64,12.268c0-0.502-0.406-0.906-0.907-0.906H3.198c-0.501,0-0.907,0.404-0.907,0.906v4.535c0,0.502,0.406,0.906,0.907,0.906h4.535c0.501,0,0.907-0.404,0.907-0.906V12.268z M7.733,9.546H3.198c-1.002,0-1.814-0.812-1.814-1.814V3.198c0-1.002,0.812-1.814,1.814-1.814h4.535c1.002,0,1.814,0.812,1.814,1.814v4.534C9.547,8.734,8.735,9.546,7.733,9.546z M8.64,3.198c0-0.501-0.406-0.907-0.907-0.907H3.198c-0.501,0-0.907,0.406-0.907,0.907v4.534c0,0.501,0.406,0.908,0.907,0.908h4.535c0.501,0,0.907-0.406,0.907-0.908V3.198z">
                            </path>
                        </svg>
                    </div>
                    <p style="font-size: 10px" class="font-light text-center text-gray-600">Inventory</p>
                </a>
                {{-- P.O.S --}}
                <a class="mt-1 mx-0.5 flex flex-col w-8 items-center" href="#">
                    <div
                        class="rounded-2xl bg-gradient-to-br from-purple-400 to-blue-200 w-12 h-12 p-1 flex justify-center items-end">
                        <svg class="w-8" viewBox="0 0 20 20">
                            <path fill="gray"
                                d="M10.862,6.47H3.968v6.032h6.894V6.47z M10,11.641H4.83V7.332H10V11.641z M12.585,11.641h-0.861v0.861h0.861V11.641z M7.415,14.226h0.862v-0.862H7.415V14.226z M8.707,17.673h2.586c0.237,0,0.431-0.193,0.431-0.432c0-0.237-0.193-0.431-0.431-0.431H8.707c-0.237,0-0.431,0.193-0.431,0.431C8.276,17.479,8.47,17.673,8.707,17.673 M5.691,14.226h0.861v-0.862H5.691V14.226z M4.83,13.363H3.968v0.862H4.83V13.363z M16.895,4.746h-3.017V3.023h1.292c0.476,0,0.862-0.386,0.862-0.862V1.299c0-0.476-0.387-0.862-0.862-0.862H10c-0.476,0-0.862,0.386-0.862,0.862v0.862c0,0.476,0.386,0.862,0.862,0.862h1.293v1.723H3.106c-0.476,0-0.862,0.386-0.862,0.862v12.926c0,0.476,0.386,0.862,0.862,0.862h13.789c0.475,0,0.861-0.387,0.861-0.862V5.608C17.756,5.132,17.369,4.746,16.895,4.746 M10.862,2.161H10V1.299h0.862V2.161zM11.724,1.299h3.446v0.862h-3.446V1.299z M13.016,4.746h-0.861V3.023h0.861V4.746z M16.895,18.534H3.106v-2.585h13.789V18.534zM16.895,15.088H3.106v-9.48h13.789V15.088z M15.17,12.502h0.862v-0.861H15.17V12.502z M13.447,12.502h0.861v-0.861h-0.861V12.502zM15.17,10.778h0.862V9.917H15.17V10.778z M15.17,9.055h0.862V8.193H15.17V9.055z M16.032,6.47h-4.309v0.862h4.309V6.47zM14.309,8.193h-0.861v0.862h0.861V8.193z M12.585,8.193h-0.861v0.862h0.861V8.193z M13.447,14.226h2.585v-0.862h-2.585V14.226zM13.447,10.778h0.861V9.917h-0.861V10.778z M12.585,9.917h-0.861v0.861h0.861V9.917z">
                            </path>
                        </svg>
                    </div>
                    <p style="font-size: 10px" class="font-light text-center text-gray-600">P.O.S</p>
                </a>
                {{-- Transaksi --}}
                <a class="mt-1 mx-0.5 flex flex-col w-8 items-center" href="{{ route('member.store.transactions') }}">
                    <div
                        class="rounded-2xl bg-gradient-to-br from-green-300 to-yellow-100 w-12 h-12 p-1 flex justify-center items-end">
                        <svg class="w-8" viewBox="0 0 20 20">
                            <path fill="gray"
                                d="M2.25,12.584c-0.713,0-1.292,0.578-1.292,1.291s0.579,1.291,1.292,1.291c0.713,0,1.292-0.578,1.292-1.291S2.963,12.584,2.25,12.584z M2.25,14.307c-0.238,0-0.43-0.193-0.43-0.432s0.192-0.432,0.43-0.432c0.238,0,0.431,0.193,0.431,0.432S2.488,14.307,2.25,14.307z M5.694,6.555H18.61c0.237,0,0.431-0.191,0.431-0.43s-0.193-0.431-0.431-0.431H5.694c-0.238,0-0.43,0.192-0.43,0.431S5.457,6.555,5.694,6.555z M2.25,8.708c-0.713,0-1.292,0.578-1.292,1.291c0,0.715,0.579,1.292,1.292,1.292c0.713,0,1.292-0.577,1.292-1.292C3.542,9.287,2.963,8.708,2.25,8.708z M2.25,10.43c-0.238,0-0.43-0.192-0.43-0.431c0-0.237,0.192-0.43,0.43-0.43c0.238,0,0.431,0.192,0.431,0.43C2.681,10.238,2.488,10.43,2.25,10.43z M18.61,9.57H5.694c-0.238,0-0.43,0.192-0.43,0.43c0,0.238,0.192,0.431,0.43,0.431H18.61c0.237,0,0.431-0.192,0.431-0.431C19.041,9.762,18.848,9.57,18.61,9.57z M18.61,13.443H5.694c-0.238,0-0.43,0.193-0.43,0.432s0.192,0.432,0.43,0.432H18.61c0.237,0,0.431-0.193,0.431-0.432S18.848,13.443,18.61,13.443z M2.25,4.833c-0.713,0-1.292,0.578-1.292,1.292c0,0.713,0.579,1.291,1.292,1.291c0.713,0,1.292-0.578,1.292-1.291C3.542,5.412,2.963,4.833,2.25,4.833z M2.25,6.555c-0.238,0-0.43-0.191-0.43-0.43s0.192-0.431,0.43-0.431c0.238,0,0.431,0.192,0.431,0.431S2.488,6.555,2.25,6.555z">
                            </path>
                        </svg>
                    </div>
                    <p style="font-size: 10px" class="font-light text-center text-gray-600">Transaksi</p>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    let tgActive = {{ ($user->chat_id == null) ? 0 : 1 }}
    $( function () {
        if (tgActive) {
            $('#telegram-badge').addClass('bg-green-300');
        } else {
            $('#telegram-badge').addClass('bg-red-300');
        }
    })

    $('#telegram-btn').click( function () {
        if (tgActive) {
            unlinkTelegram();
        } else {
            telegram()
        }
    })

    function telegram() {
        Swal.fire({
            title: 'Tautkan Telegram?',
            text: "Pastikan anda sudah memiliki Akun Telegram, anda akan diarahkan ke Telegram untuk Start/Mulai",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tautkan!',
            cancelButtonText: 'Tidak usah'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Lanjutkan Proses di Aplikasi Telegram Anda, Klik START/MULAI');
                Swal.showLoading();
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.telegram.link') }}",
                    success: function(response){
                        location.assign("https://t.me/LumbungNetworkBot?start=" + response.message);
                    }
                });
            
            }
        })
    }
    
    function unlinkTelegram() {
        Swal.fire({
            title: 'Hapus Telegram?',
            text: "Yakin ingin memutuskan hubungan dengan akun Telegram yang terdaftar saat ini?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, putuskan!',
            cancelButtonText: 'Jangan'
        }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Memutuskan hubungan...');
            Swal.showLoading();
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.telegram.unlink') }}",
                success: function(response){
                    if (response.success) {
                        location.replace(window.location.href);
                    }
                
                }
            });
            
            }
        })
    }
</script>
@endsection