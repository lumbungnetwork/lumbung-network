@extends('member.components.main')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3">
    <div class="relative w-full max-w-md mx-auto">

        <div class="relative nm-flat-gray-200 rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="px-3 lg:px-20 py-6">

                <div class="mt-8">
                    <img class="object-contain w-full max-h-32" src="/image/icon_lumbung_4x.png"
                        alt="logo lumbung network">
                </div>

                <!-- Layout  -->
                <h1 class="text-3xl font-medium mb-2">Register</h1>
                <p class="text-sm">Isi formulir ini untuk mendaftarkan akun anda.</p>
                <form class="mt-5" action="{{ route('member.postRegister') }}" method="POST" id="register-form"
                    autocomplete="off">
                    @csrf
                    <label for="username" class="text-gray-500">Username</label>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input type="text" class="w-full focus:outline-none bg-transparent" name="username"
                            id="username" required>
                    </div>
                    @error('username')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <label for="email" class="text-gray-500">Email</label>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input type="text" class="w-full focus:outline-none bg-transparent" name="email" id="email"
                            required>
                    </div>
                    @error('email')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <label for="password" class="text-gray-500">Password</label>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input type="password" class="w-full focus:outline-none bg-transparent" name="password"
                            id="password" required>
                    </div>

                    @error('password')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <label for="password_confirmation" class="text-gray-500">Ulangi Password</label>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input type="password" class="w-full focus:outline-none bg-transparent"
                            name="password_confirmation" id="password_confirmation" required>
                    </div>
                    @if (isset($referral))
                    <label for="referral" class="text-gray-600">Diundang oleh: {{$referral->username}}</label>
                    <input type="hidden" name="referral" id="referral" value="{{$referral->id}}" />
                    @endif

                    <div class="mt-3 p-1">
                        {!! NoCaptcha::display(['data-theme' => 'light']) !!}
                    </div>

                    @error('g-recaptcha-response')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror

                    <button type="submit"
                        class="mt-3 focus:outline-none focus:bg-yellow-400 text-center w-full bg-yellow-300 rounded-full text-black  py-3 font-medium">Register
                        Akun</button>

                </form>

                <!-- Footer -->
                <p class="mt-2">...atau, anda bisa <a href="{{ route('member.login') }}"
                        class="text-blue-700 font-medium">log in</a> ke akun anda.</p>
            </div>

        </div>
    </div>
</div>

</div>

@endsection
@section('scripts')
{!! NoCaptcha::renderJs() !!}
@endsection