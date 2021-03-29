@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">

    <div class="relative w-full max-w-md mx-auto">
        <div
            class="absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-yellow-200 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>

        <div class="relative bg-gray-100 shadow-lg rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="flex float-right mr-6 text-xl text-red-500">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="mt-5"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                </form>

            </div>

            <div class="flex items-center justify-start pt-6 pl-6">
                <a href="{{ route('finance.dashboard') }}">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Dashboard</span>
                </a>

            </div>

            <div class="px-4 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">{{$title}}</h2>

                </div>

                <div class="flex flex-wrap justify-between" id="account-menus">
                    <a href="{{ route('finance.account.credentials') }}">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fas fa-user-lock text-2xl"></i>
                            <p class="text-sm font-thin">Credentials</p>

                        </div>
                    </a>
                    <a href="{{ route('finance.2fa') }}">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fas fa-fingerprint text-2xl"></i>
                            <p class="text-sm font-light">2FA</p>

                        </div>
                    </a>
                    <a href="{{ route('finance.account.addresses') }}">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fab fa-ethereum text-2xl"></i>
                            <p class="text-sm font-extralight">Blockchain Adresses</p>

                        </div>
                    </a>
                    <a onclick="telegram()">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fab fa-telegram text-2xl"></i>
                            <p class="text-sm font-extralight">Telegram</p>

                        </div>
                    </a>
                </div>





                <div class="mt-4 nm-concave-gray-50 rounded-xl p-4 ">
                    <h4 class="font-extralight text-center text-xl">Referral Link</h4>
                    <p class="text-sm font-light"><strong>Get 50%</strong> from fees generated by every actions your
                        referred users do, FOREVER!</p>
                    <div class="mt-3 nm-inset-gray-50 rounded-xl p-2 flex-wrap overflow-hidden">
                        <p class="text-black text-md font-extralight" id="ref">{{url('/ref') . '/' . $user->username}}
                        </p>
                    </div>

                    <div class="flex flex-wrap">
                        <button type="button" onclick="copy('ref')"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Copy
                            Link</button>
                        <a href="{{ route('finance.account.refferals') }}"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Referrals
                            List</a>
                    </div>

                </div>



            </div>

        </div>
    </div>


</div>


@endsection


@section('scripts')
<script>
    function telegram() {
        Swal.fire('Coming Soon!');
    }
    
</script>
@include('finance.layout.copy')
@endsection