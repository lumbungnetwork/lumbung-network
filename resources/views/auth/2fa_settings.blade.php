@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">

    <div class="relative w-full max-w-md mx-auto">

        <div class="relative nm-flat-gray-50 rounded-3xl">

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
                <a href="/dashboard">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Dashboard</span>
                </a>

            </div>

            <div class="px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">Two Factor Authentication (2FA)</h2>

                </div>

                <p class="text-sm font-light my-4">
                    Two factor authentication (2FA) strengthens access security by requiring two methods (also
                    referred to as factors) to verify your identity. Two factor authentication protects against
                    phishing, social engineering and password brute force attacks and secures your logins from
                    attackers exploiting weak or stolen credentials.
                </p>

                @if (session('error'))
                <div class="nm-inset-red-100 rounded-2xl p-3 my-3">
                    {{ session('error') }}
                </div>
                @endif
                @if (session('success'))
                <div class="nm-inset-green-100 rounded-2xl p-3 my-3">
                    {{ session('success') }}
                </div>
                @endif

                @if($data['user']->loginSecurity == null)
                <form class="" method="POST" action="{{ route('finance.generate2faSecret') }}" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="nm-convex-gray-50 rounded-2xl p-4 flex items-center">
                        <p class="text-lg font-light">Generate Secret Key to Enable 2FA</p>
                        <button type="submit"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">
                            Generate
                        </button>
                    </div>
                </form>
                @elseif(!$data['user']->loginSecurity->google2fa_enable)
                <div class="nm-convex-gray-50 rounded-2xl p-4">
                    <p class="text-sm font-medium">1. Scan this QR code with your Google Authenticator App.</p>
                    <div class="my-3">
                        <img class="mx-auto" src="{{$data['google2fa_url'] }}" alt="">
                    </div>
                    <p class="text-sm font-medium">Or, if you use only one device, you can copy this code below:</p>
                    <div class="text-center py-4">
                        <code class="text-center" id="code">{{ $data['secret'] }}</code><br />
                        <button onclick="copy('code')"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Copy
                            Code</button>
                    </div>
                    <p class="text-sm font-medium">...after you copied the code above, open your Google Authenticator
                        App and setup with Key instead of QR code. You can set any name for the account name.</p>
                    <div class="my-3">
                        <img class="mx-auto" src="{{ Config::get('services.app.protocol_url') }}/image/2fa-example.jpeg"
                            alt="">
                    </div>
                    <p class="mt-4 text-sm font-medium">2. Now, after you set up the code, you'll see a six digit code
                        with timer, this code will expired when the time reach zero and will be replaced with new code.
                        Providing safe 2FA token for your account.</p>
                    <p class="mt-4 text-sm font-medium">3. Copy the One Time Password (touch the 6 digits number), and
                        enter the
                        OTP below to authenticate your account. Make sure the timer still have enough time before
                        changing to new OTP</p>
                    <form class="form-horizontal" method="POST" action="{{ route('finance.enable2fa') }}"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <div class="mt-3">
                            <label for="secret" class="text-lg font-light text-center">Authenticator Code</label>
                            <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">

                                <input id="secret" type="text" class="w-full focus:outline-none bg-transparent"
                                    name="secret" required>
                            </div>

                            @if ($errors->has('verify-code'))
                            <div class="nm-inset-red-100 rounded-2xl p-3">
                                {{ $errors->first('verify-code') }}
                            </div>
                            @endif

                        </div>
                        <button type="submit"
                            class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">
                            Enable 2FA
                        </button>
                    </form>
                </div>


                @elseif($data['user']->loginSecurity->google2fa_enable)
                <div class="nm-inset-green-100 rounded-2xl p-3">

                    <p class="text-lg font-medium">2FA is currently <strong>enabled</strong> on your account.</p>
                </div>

                <p class="mt-4 font-light text-md">If you are looking to disable Two Factor Authentication. Please
                    confirm your password and Click
                    Disable 2FA Button.</p>

                <form class="" method="POST" action="{{ route('finance.disable2fa') }}" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="mt-3">
                        <p class="mt-4 text-md font-light">Confirm your password:</p>
                        <div class="my-2 nm-inset-gray-50 rounded-xl">
                            <input type="password" placeholder="your password"
                                class="p-2 focus:outline-none bg-transparent w-full" name="current-password"
                                id="current-password" required>
                        </div>

                        @if ($errors->has('current-password'))
                        <div class="nm-inset-red-100 rounded-2xl p-3">
                            {{ $errors->first('current-password') }}
                        </div>
                        @endif
                    </div>
                    <button type="submit"
                        class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">
                        Disable 2FA
                    </button>
                </form>
                @endif




            </div>

        </div>
    </div>


</div>

@endsection

@section('scripts')
<script>
    function copy(id) {
        var copyText = document.getElementById(id);
        var textArea = document.createElement("textarea");
        textArea.value = copyText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
        successToast("Code Copied");
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        width: 200,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    function successToast (message) {
        Toast.fire({
            icon: 'success',
            title: message
        })
    }
</script>

@endsection