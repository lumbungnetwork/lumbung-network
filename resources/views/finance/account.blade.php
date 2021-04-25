@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-6">

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

                <div class="flex flex-wrap justify-between justify-items-auto" id="account-menus">
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
                    @if ($user->chat_id == null)
                    <a onclick="telegram()">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fab fa-telegram text-2xl"></i>
                            <p class="text-sm font-extralight">Link Telegram</p>

                        </div>
                    </a>
                    @else
                    <a onclick="unlinkTelegram()">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fab fa-telegram text-2xl"></i>
                            <p class="text-sm font-extralight">Unlink Telegram</p>

                        </div>
                    </a>
                    @endif

                    <a href="{{ route('finance.account.activate') }}">
                        <div class="mt-4 w-20 h-20 nm-convex-gray-50 rounded-xl p-2 text-center items-baseline">
                            <i class="fas fa-fire text-2xl"></i>
                            <p class="text-sm font-extralight">Activation</p>

                        </div>
                    </a>
                </div>





                <div class="mt-4 nm-concave-gray-50 rounded-xl p-4 ">
                    <h4 class="font-extralight text-center text-xl">Referral Link</h4>
                    <p class="text-sm font-light"><strong>50%</strong> from every fees generated will be distributed
                        to referrers (upto 4 Levels), FOREVER! <br> <br> Lv.1 => 25% <br>Lv.2 => 15% <br>Lv.3 => 5%
                        <br>Lv.4
                        => 5%
                        <br><br> Minimum 100 USDT needed to activate Referral Bonus. </p>
                    <div class="mt-3 nm-inset-gray-50 rounded-xl p-2 flex-wrap overflow-hidden">
                        <p class="text-black text-md font-extralight" id="ref">
                            {{url('/ref') . '/' . $user->username}}
                        </p>
                    </div>

                    <div class="flex flex-wrap space-x-2">
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
            Swal.fire({
                title: 'Link Telegram?',
                text: "Make sure already have Telegram App installed and activated, you'll be redirected to Telegram App, please click START button there.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Continue on your Telegram App, Click START');
                    Swal.showLoading();
                    $.ajax({
                        type: "GET",
                        url: "{{ route('finance.ajax.getAccountTelegram') }}",
                        success: function(response){
                        location.assign("https://t.me/LumbungNetworkBot?start=" + response.message);
                        }
                    });

                }
            })
        }

        function unlinkTelegram() {
            Swal.fire({
                title: 'Unlink Telegram?',
                text: "Are you sure to unlink this account from your Telegram?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Don\'t!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Unlink Telegram...');
                    Swal.showLoading();
                    $.ajax({
                        type: "GET",
                        url: "{{ route('finance.ajax.getAccountUnlinkTelegram') }}",
                        success: function(response){
                            if (response.success) {
                                location.reload();
                            }

                        }
                    });

                }
            })
        }
    
</script>
@include('finance.layout.copy')
@endsection