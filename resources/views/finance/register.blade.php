@extends('lfinance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">
    <div class="relative w-full max-w-md mx-auto">
        <div
            class="absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative bg-white shadow-lg rounded-lg sm:rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="px-10 lg:px-20 py-6">

                <div class="flex justify-center">
                    <img class="object-contain w-32 opacity-90" src="/image/lumbung-finance_3.png"
                        alt="lumbung finance">
                </div>

                <!-- Layout  -->
                <h1 class="text-3xl font-medium mb-2">Register</h1>
                <p class="text-sm">Fill this form to register new account.</p>
                <form class="mt-5" action="/register" method="POST" id="register-form">
                    @csrf
                    <label for="username" class="text-gray-500">Username</label>
                    <input type="text" class="mb-3 w-full h-12 border border-gray-800 rounded px-3" name="username"
                        id="username" value="{{ old('username') }}" />
                    @error('username')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <label for="email" class="text-gray-500">Email</label>
                    <input type="text" class="mb-3 w-full h-12 border border-gray-800 rounded px-3" name="email"
                        id="email" value="{{ old('email') }}" />
                    @error('email')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <label for="password" class="text-gray-500">Password</label>
                    <input type="password" class="mb-3 w-full h-12 border border-gray-800 rounded px-3" name="password"
                        id="password" />
                    @error('password')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <label for="password_confirmation" class="text-gray-500">Re-type Password</label>
                    <input type="password" class="mb-3 w-full h-12 border border-gray-800 rounded px-3"
                        name="password_confirmation" id="password_confirmation" />
                    @if (isset($referral))
                    <label for="referral" class="text-gray-600">Invited by: {{$referral->username}}</label>
                    <input type="hidden" name="referral" id="referral" value="{{$referral->id}}" />
                    @endif

                    {!! NoCaptcha::display(['data-theme' => 'light']) !!}
                    @error('g-recaptcha-response')
                    <div class="text-red-600">{{ $message }}</div>
                    @enderror

                    <button type="submit"
                        class="mt-3 focus:outline-none focus:bg-yellow-400 text-center w-full bg-yellow-300 rounded-full text-black  py-3 font-medium">Register
                        Account</button>

                </form>

                <!-- Footer -->
                <p class="mt-2">...or, you may <a href="/login" class="text-blue-700 font-medium">log in</a> to
                    existing
                    account.</p>
            </div>

        </div>
    </div>
</div>

</div>

@endsection
@section('scripts')
{!! NoCaptcha::renderJs() !!}
@endsection