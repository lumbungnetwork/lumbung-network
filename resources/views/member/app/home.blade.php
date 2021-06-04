@extends('member.components.main')
@section('content')

{{-- Top bar --}}
<div class="bg-white px-4 py-2">
    <div x-data="{ open: false }">
        <div class="mt-2 flex justify-between max-h-full">
            {{-- Profile --}}
            <div class="flex justify-start items-center focus:outline-none cursor-pointer">
                <div>
                    <button @click="open = true" id="profile">
                        <svg class="bg-gray-600 rounded-full w-6" viewBox="0 0 20 20">
                            <path fill="white"
                                d="M10,10.9c2.373,0,4.303-1.932,4.303-4.306c0-2.372-1.93-4.302-4.303-4.302S5.696,4.223,5.696,6.594C5.696,8.969,7.627,10.9,10,10.9z M10,3.331c1.801,0,3.266,1.463,3.266,3.263c0,1.802-1.465,3.267-3.266,3.267c-1.8,0-3.265-1.465-3.265-3.267C6.735,4.794,8.2,3.331,10,3.331z">
                            </path>
                            <path fill="white"
                                d="M10,12.503c-4.418,0-7.878,2.058-7.878,4.685c0,0.288,0.231,0.52,0.52,0.52c0.287,0,0.519-0.231,0.519-0.52c0-1.976,3.132-3.646,6.84-3.646c3.707,0,6.838,1.671,6.838,3.646c0,0.288,0.234,0.52,0.521,0.52s0.52-0.231,0.52-0.52C17.879,14.561,14.418,12.503,10,12.503z">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="text-gray-600 font-light ml-1">
                    {{$user->username}}
                </div>
            </div>

            {{-- Notification --}}
            <div class="flex justify-end">
                <svg class="w-6" viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M14.38,3.467l0.232-0.633c0.086-0.226-0.031-0.477-0.264-0.559c-0.229-0.081-0.48,0.033-0.562,0.262l-0.234,0.631C10.695,2.38,7.648,3.89,6.616,6.689l-1.447,3.93l-2.664,1.227c-0.354,0.166-0.337,0.672,0.035,0.805l4.811,1.729c-0.19,1.119,0.445,2.25,1.561,2.65c1.119,0.402,2.341-0.059,2.923-1.039l4.811,1.73c0,0.002,0.002,0.002,0.002,0.002c0.23,0.082,0.484-0.033,0.568-0.262c0.049-0.129,0.029-0.266-0.041-0.377l-1.219-2.586l1.447-3.932C18.435,7.768,17.085,4.676,14.38,3.467 M9.215,16.211c-0.658-0.234-1.054-0.869-1.014-1.523l2.784,0.998C10.588,16.215,9.871,16.447,9.215,16.211 M16.573,10.27l-1.51,4.1c-0.041,0.107-0.037,0.227,0.012,0.33l0.871,1.844l-4.184-1.506l-3.734-1.342l-4.185-1.504l1.864-0.857c0.104-0.049,0.188-0.139,0.229-0.248l1.51-4.098c0.916-2.487,3.708-3.773,6.222-2.868C16.187,5.024,17.489,7.783,16.573,10.27">
                    </path>
                </svg>
            </div>
        </div>

        {{-- Profile Dropdown Menu --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" @click.away="open = false" id="profile-menu"
            class="hidden absolute mt-2 z-50 bg-gray-700 opacity-90 rounded-br-2xl rounded-bl-2xl drop-shadow w-11/12 py-2 px-4">
            <div class="flex justify-between">
                <div class="space-y-2">
                    @if ($user->user_type == 10)
                    <p
                        class="rounded-lg bg-gradient-to-br from-yellow-200 to-red-300 text-sm py-1 px-2 w-36 text-green-600 font-medium">
                        Premium Member</p>
                    @if ($user->is_store == 1)
                    <p
                        class="rounded-lg bg-gradient-to-br from-yellow-200 to-green-200 text-sm font-medium py-1 px-2 w-12">
                        Toko
                    </p>
                    @endif
                    @else
                    @if ($user->user_type == 9)
                    <p
                        class="rounded-lg bg-gradient-to-br from-gray-200 to-gray-400 text-sm py-1 px-2 w-36 text-gray-700 font-medium">
                        Starter Member</p>
                    @endif
                    @endif

                </div>
                <div>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full p-1 bg-red-400">
                            <svg class="w-4" viewBox="0 0 20 20">
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
    </div>
    {{-- Search box --}}
    {{-- <div class="mt-3 mb-2">
        <div class="border border-gray-300 rounded-full py-1 px-2 w-full flex">
            <svg class="w-6" viewBox="0 0 20 20">
                <path fill="gray"
                    d="M18.125,15.804l-4.038-4.037c0.675-1.079,1.012-2.308,1.01-3.534C15.089,4.62,12.199,1.75,8.584,1.75C4.815,1.75,1.982,4.726,2,8.286c0.021,3.577,2.908,6.549,6.578,6.549c1.241,0,2.417-0.347,3.44-0.985l4.032,4.026c0.167,0.166,0.43,0.166,0.596,0l1.479-1.478C18.292,16.234,18.292,15.968,18.125,15.804 M8.578,13.99c-3.198,0-5.716-2.593-5.733-5.71c-0.017-3.084,2.438-5.686,5.74-5.686c3.197,0,5.625,2.493,5.64,5.624C14.242,11.548,11.621,13.99,8.578,13.99 M16.349,16.981l-3.637-3.635c0.131-0.11,0.721-0.695,0.876-0.884l3.642,3.639L16.349,16.981z">
                </path>
            </svg>
            <form action="#" method="POST" autocomplete="off">
                @csrf
                <input type="text" class="ml-1 w-full focus:outline-none bg-transparent font-extralight text-sm"
                    placeholder="cari beras, gula, minyak...">
            </form>
        </div>
    </div> --}}
</div>

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Monthly Spending infos --}}
    <div class="mt-3 py-2 px-4">
        <div
            class="bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl px-2 py-2 flex justify-around items-center">
            <div class="flex flex-col align-middle items-center">
                <img class="object-contain w-10" src="/image/koin_lmb2.png" alt="koin LMB">
                <div class="mt-1">
                    <a href="{{ route('member.claim.shoppingReward') }}"
                        class="cursor-pointer rounded-lg py-1 px-2 bg-gradient-to-br from-yellow-200 to-red-200 text-sm text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Claim</a>
                </div>
            </div>
            <div>
                @php
                $spent = $spending->total;
                $multiplier = 1;
                // LMB Reward Rate
                $rate = 0.025;
                // If Spending > 300000, bonus 100% LMB
                if ($spent > 300000) {
                $multiplier = 2;
                }

                // Calculate the base spending (per 1000)
                $base = floor($spent / 1000);
                $reward = number_format($base * $rate * $multiplier, 2);
                @endphp
                <h2 class="text-gray-600 sm:text-xs" style="font-size: 10px">Akumulasi Belanja bulan ini:</h2>
                <p class="font-extralight text-xl text-right">Rp{{ number_format($spent) }}</p>
                <hr class="my-1 border-gray-50">
                <h3 class="text-gray-600 sm:text-xs" style="font-size: 10px">Estimasi Reward LMB:</h3>
                <p class="font-extralight text-lg text-right">{{ $reward }} LMB</p>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="mt-1 px-3 flex flex-wrap justify-between">
        {{-- Belanja --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center" href="{{ route('member.shopping') }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-blue-300 to-green-300 w-12 h-12 p-1 flex justify-center items-end">
                <svg class="w-8 " viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M17.671,13.945l0.003,0.002l1.708-7.687l-0.008-0.002c0.008-0.033,0.021-0.065,0.021-0.102c0-0.236-0.191-0.428-0.427-0.428H5.276L4.67,3.472L4.665,3.473c-0.053-0.175-0.21-0.306-0.403-0.306H1.032c-0.236,0-0.427,0.191-0.427,0.427c0,0.236,0.191,0.428,0.427,0.428h2.902l2.667,9.945l0,0c0.037,0.119,0.125,0.217,0.239,0.268c-0.16,0.26-0.257,0.562-0.257,0.891c0,0.943,0.765,1.707,1.708,1.707S10,16.068,10,15.125c0-0.312-0.09-0.602-0.237-0.855h4.744c-0.146,0.254-0.237,0.543-0.237,0.855c0,0.943,0.766,1.707,1.708,1.707c0.944,0,1.709-0.764,1.709-1.707c0-0.328-0.097-0.631-0.257-0.891C17.55,14.182,17.639,14.074,17.671,13.945 M15.934,6.583h2.502l-0.38,1.709h-2.312L15.934,6.583zM5.505,6.583h2.832l0.189,1.709H5.963L5.505,6.583z M6.65,10.854L6.192,9.146h2.429l0.19,1.708H6.65z M6.879,11.707h2.027l0.189,1.709H7.338L6.879,11.707z M8.292,15.979c-0.472,0-0.854-0.383-0.854-0.854c0-0.473,0.382-0.855,0.854-0.855s0.854,0.383,0.854,0.855C9.146,15.596,8.763,15.979,8.292,15.979 M11.708,13.416H9.955l-0.189-1.709h1.943V13.416z M11.708,10.854H9.67L9.48,9.146h2.228V10.854z M11.708,8.292H9.386l-0.19-1.709h2.512V8.292z M14.315,13.416h-1.753v-1.709h1.942L14.315,13.416zM14.6,10.854h-2.037V9.146h2.227L14.6,10.854z M14.884,8.292h-2.321V6.583h2.512L14.884,8.292z M15.978,15.979c-0.471,0-0.854-0.383-0.854-0.854c0-0.473,0.383-0.855,0.854-0.855c0.473,0,0.854,0.383,0.854,0.855C16.832,15.596,16.45,15.979,15.978,15.979 M16.917,13.416h-1.743l0.189-1.709h1.934L16.917,13.416z M15.458,10.854l0.19-1.708h2.218l-0.38,1.708H15.458z">
                    </path>
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Belanja</p>
        </a>
        {{-- Pulsa --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center"
            href="{{ route('member.shopping.operatorList', ['type' => 1]) }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-red-300 to-purple-200 w-12 h-12 p-1 flex justify-center items-end">
                <svg class="w-8" viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M10,15.654c-0.417,0-0.754,0.338-0.754,0.754S9.583,17.162,10,17.162s0.754-0.338,0.754-0.754S10.417,15.654,10,15.654z M14.523,1.33H5.477c-0.833,0-1.508,0.675-1.508,1.508v14.324c0,0.833,0.675,1.508,1.508,1.508h9.047c0.833,0,1.508-0.675,1.508-1.508V2.838C16.031,2.005,15.356,1.33,14.523,1.33z M15.277,17.162c0,0.416-0.338,0.754-0.754,0.754H5.477c-0.417,0-0.754-0.338-0.754-0.754V2.838c0-0.417,0.337-0.754,0.754-0.754h9.047c0.416,0,0.754,0.337,0.754,0.754V17.162zM13.77,2.838H6.23c-0.417,0-0.754,0.337-0.754,0.754v10.555c0,0.416,0.337,0.754,0.754,0.754h7.539c0.416,0,0.754-0.338,0.754-0.754V3.592C14.523,3.175,14.186,2.838,13.77,2.838z M13.77,13.77c0,0.208-0.169,0.377-0.377,0.377H6.607c-0.208,0-0.377-0.169-0.377-0.377V3.969c0-0.208,0.169-0.377,0.377-0.377h6.785c0.208,0,0.377,0.169,0.377,0.377V13.77z">
                    </path>
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Pulsa</p>
        </a>
        {{-- Paket Data --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center"
            href="{{ route('member.shopping.operatorList', ['type' => 2]) }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-purple-400 to-blue-200 w-12 h-12 p-1 flex justify-center items-end">
                <svg class="w-8" viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M10,15.654c-0.417,0-0.754,0.338-0.754,0.754S9.583,17.162,10,17.162s0.754-0.338,0.754-0.754S10.417,15.654,10,15.654z M14.523,1.33H5.477c-0.833,0-1.508,0.675-1.508,1.508v14.324c0,0.833,0.675,1.508,1.508,1.508h9.047c0.833,0,1.508-0.675,1.508-1.508V2.838C16.031,2.005,15.356,1.33,14.523,1.33z M15.277,17.162c0,0.416-0.338,0.754-0.754,0.754H5.477c-0.417,0-0.754-0.338-0.754-0.754V2.838c0-0.417,0.337-0.754,0.754-0.754h9.047c0.416,0,0.754,0.337,0.754,0.754V17.162zM13.77,2.838H6.23c-0.417,0-0.754,0.337-0.754,0.754v10.555c0,0.416,0.337,0.754,0.754,0.754h7.539c0.416,0,0.754-0.338,0.754-0.754V3.592C14.523,3.175,14.186,2.838,13.77,2.838z M13.77,13.77c0,0.208-0.169,0.377-0.377,0.377H6.607c-0.208,0-0.377-0.169-0.377-0.377V3.969c0-0.208,0.169-0.377,0.377-0.377h6.785c0.208,0,0.377,0.169,0.377,0.377V13.77z">
                    </path>
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Paket Data</p>
        </a>
        {{-- Token PLN --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center" href="{{ route('member.shopping.plnPrepaid') }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-green-300 to-yellow-100 w-12 h-12 p-1 flex justify-center items-end">
                <svg viewBox="0 0 64 64" class="w-8">
                    <path d="M23.688 5.469h21.999l-11.084 20h13.084L22.094 58.531l6.053-23.062H16.313l7.375-30z"
                        fill="#fce349" data-name="layer2"></path>
                    <path
                        d="M34.603 25.469l11.084-20h-7.003l-11.083 20h7.002zm6.081 0L25.687 44.844l-3.593 13.687 25.593-33.062h-7.003z"
                        opacity=".25" fill="#fff" data-name="layer1"></path>
                    <path d="M23.688 5.469h21.999l-11.084 20h13.084L22.094 58.531l6.053-23.062H16.313l7.375-30z"
                        stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="#2e4369" fill="none"
                        data-name="stroke"></path>
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Token PLN</p>
        </a>
        {{-- e-Money --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center" href="{{ route('member.shopping.emoney') }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-purple-300 to-green-200 w-12 h-12 p-1 flex justify-center items-end">
                <svg xmlns="http://www.w3.org/2000/svg" fill="gray" class="w-8" viewBox="0 0 16 16">
                    <path
                        d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z" />
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">e-Money</p>
        </a>
        {{-- Pascabayar --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center" href="{{ route('member.shopping.postpaid') }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-yellow-300 to-blue-200 w-12 h-12 p-1 flex justify-center items-end">
                <svg class="w-8" viewBox="0 0 20 20">
                    <path fill="gray"
                        d="M11.174,14.993h1.647c0.228,0,0.412-0.184,0.412-0.411v-1.648c0-0.228-0.185-0.411-0.412-0.411h-1.647c-0.227,0-0.412,0.184-0.412,0.411v1.648C10.762,14.81,10.947,14.993,11.174,14.993 M3.759,13.346h4.943c0.227,0,0.412-0.184,0.412-0.412c0-0.228-0.185-0.411-0.412-0.411H3.759c-0.227,0-0.412,0.184-0.412,0.411C3.347,13.162,3.532,13.346,3.759,13.346 M3.759,14.993h3.295c0.228,0,0.412-0.184,0.412-0.411S7.282,14.17,7.055,14.17H3.759c-0.227,0-0.412,0.185-0.412,0.412S3.532,14.993,3.759,14.993 M14.881,5.932H1.7c-0.455,0-0.824,0.369-0.824,0.824v9.886c0,0.454,0.369,0.823,0.824,0.823h13.181c0.455,0,0.823-0.369,0.823-0.823V6.755C15.704,6.301,15.336,5.932,14.881,5.932M14.881,16.642H1.7v-5.767h13.181V16.642z M14.881,8.403H1.7V6.755h13.181V8.403z M18.176,2.636H4.995c-0.455,0-0.824,0.37-0.824,0.824v1.236c0,0.228,0.185,0.412,0.412,0.412c0.228,0,0.412-0.184,0.412-0.412V3.46h13.181v9.886H16.94c-0.228,0-0.412,0.185-0.412,0.412s0.185,0.412,0.412,0.412h1.235c0.455,0,0.824-0.369,0.824-0.824V3.46C19,3.006,18.631,2.636,18.176,2.636">
                    </path>
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Pascabayar</p>
        </a>
        {{-- Aset Digital --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center" href="#">
            <div
                class="rounded-2xl bg-gradient-to-br from-yellow-200 to-red-300 w-12 h-12 p-1 flex justify-center items-end">
                <svg viewBox="0 0 24 24" class="w-8" fill="gray">
                    <path
                        d="M23.638 14.904c-1.602 6.43-8.113 10.34-14.542 8.736C2.67 22.05-1.244 15.525.362 9.105 1.962 2.67 8.475-1.243 14.9.358c6.43 1.605 10.342 8.115 8.738 14.548v-.002zm-6.35-4.613c.24-1.59-.974-2.45-2.64-3.03l.54-2.153-1.315-.33-.525 2.107c-.345-.087-.705-.167-1.064-.25l.526-2.127-1.32-.33-.54 2.165c-.285-.067-.565-.132-.84-.2l-1.815-.45-.35 1.407s.975.225.955.236c.535.136.63.486.615.766l-1.477 5.92c-.075.166-.24.406-.614.314.015.02-.96-.24-.96-.24l-.66 1.51 1.71.426.93.242-.54 2.19 1.32.327.54-2.17c.36.1.705.19 1.05.273l-.51 2.154 1.32.33.545-2.19c2.24.427 3.93.257 4.64-1.774.57-1.637-.03-2.58-1.217-3.196.854-.193 1.5-.76 1.68-1.93h.01zm-3.01 4.22c-.404 1.64-3.157.75-4.05.53l.72-2.9c.896.23 3.757.67 3.33 2.37zm.41-4.24c-.37 1.49-2.662.735-3.405.55l.654-2.64c.744.18 3.137.524 2.75 2.084v.006z" />
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Aset Digital</p>
        </a>
        {{-- Riwayat --}}
        <a class="mt-1 mx-0.5 flex flex-col w-14 items-center" href="{{ route('member.shopping.transactions') }}">
            <div
                class="rounded-2xl bg-gradient-to-br from-purple-400 to-green-200 w-12 h-12 p-1 flex justify-center items-end">
                <svg class="w-8" fill="gray" viewBox="0 0 20 20">
                    <path
                        d="M10,1.529c-4.679,0-8.471,3.792-8.471,8.471c0,4.68,3.792,8.471,8.471,8.471c4.68,0,8.471-3.791,8.471-8.471C18.471,5.321,14.68,1.529,10,1.529 M10,17.579c-4.18,0-7.579-3.399-7.579-7.579S5.82,2.421,10,2.421S17.579,5.82,17.579,10S14.18,17.579,10,17.579 M14.348,10c0,0.245-0.201,0.446-0.446,0.446h-5c-0.246,0-0.446-0.201-0.446-0.446s0.2-0.446,0.446-0.446h5C14.146,9.554,14.348,9.755,14.348,10 M14.348,12.675c0,0.245-0.201,0.446-0.446,0.446h-5c-0.246,0-0.446-0.201-0.446-0.446s0.2-0.445,0.446-0.445h5C14.146,12.229,14.348,12.43,14.348,12.675 M7.394,10c0,0.245-0.2,0.446-0.446,0.446H6.099c-0.245,0-0.446-0.201-0.446-0.446s0.201-0.446,0.446-0.446h0.849C7.194,9.554,7.394,9.755,7.394,10 M7.394,12.675c0,0.245-0.2,0.446-0.446,0.446H6.099c-0.245,0-0.446-0.201-0.446-0.446s0.201-0.445,0.446-0.445h0.849C7.194,12.229,7.394,12.43,7.394,12.675 M14.348,7.325c0,0.246-0.201,0.446-0.446,0.446h-5c-0.246,0-0.446-0.2-0.446-0.446c0-0.245,0.2-0.446,0.446-0.446h5C14.146,6.879,14.348,7.08,14.348,7.325 M7.394,7.325c0,0.246-0.2,0.446-0.446,0.446H6.099c-0.245,0-0.446-0.2-0.446-0.446c0-0.245,0.201-0.446,0.446-0.446h0.849C7.194,6.879,7.394,7.08,7.394,7.325">
                    </path>
                </svg>
            </div>
            <p class="font-light text-xs text-center text-gray-600">Riwayat</p>
        </a>

    </div>

    {{-- LMB Dividend and Stake --}}
    <div class="mt-2 px-2 flex justify-center">
        <div class="nm-convex-gray-100 rounded-2xl py-3 w-full flex justify-between text-center">
            <div class="w-7/12 space-y-1">
                <h3 class="font-medium text-gray-600 text-xs">LMB Dividend Pool</h3>
                <p class="font-extralight text-xl">Rp{{ number_format($LMBDividendPool) }}</p>
                <p class="font-light text-xs">Next dist:
                    <span>Rp{{ number_format($LMBDividendPool * 2/100) }}</span>
                </p>
            </div>
            <div class="w-5/12 space-y-1">
                <h4 class="font-medium text-gray-600 text-xs">Your Stake</h4>
                <p class="text-sm font-light">{{ number_format($userStakedLMB, 2) }} LMB</p>
                <div>
                    <a href="{{ route('member.stake') }}"
                        class="rounded-lg py-1 px-2 bg-gradient-to-r from-yellow-100 to-yellow-200 text-sm text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Stake</a>
                </div>
            </div>
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection

@section('scripts')
<script>
    $( function () {
        setTimeout( () => $('#profile-menu').removeClass('hidden'), 500);
    })
</script>
@endsection