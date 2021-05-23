@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
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
        <a href="#" class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center">
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
        <a href="#" class="my-2 nm-convex-gray-100 w-14 h-14 p-1 rounded-lg flex flex-wrap justify-center items-center">
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
        </a>

    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    let tgActive = {{ ($user->chat_id == null) ? 0 : 1 }}
    $( function () {
        if (tgActive > 0) {
            $('#telegram-badge').addClass('bg-green-300');
        } else {
            $('#telegram-badge').addClass('bg-red-300');
        }
    })
</script>
@endsection