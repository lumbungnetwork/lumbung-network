@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">
    <div class="relative w-full max-w-md mx-auto">
        <div
            class="absolute inset-0 -mr-2 bg-gradient-to-r from-green-100 to-yellow-300 shadow-lg transform skew-y-0 rotate-3 rounded-3xl">
        </div>
        <div class="relative nm-flat-gray-100 rounded-3xl">

            <div class="flex items-center justify-start pt-6 pl-6">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            </div>

            <div class="px-10 lg:px-20 py-6">

                <div class="flex justify-center">
                    <img class="object-contain w-32 opacity-90" src="/image/lumbung-finance_2.png"
                        alt="lumbung finance">
                </div>

                @if (session('status'))
                <div class="nm-inset-green-100 rounded-2xl p-3 my-3">
                    {{ session('status') }}
                </div>
                @endif

                <!-- Layout  -->
                <h1 class="text-3xl font-medium mb-2">Reset Password</h1>
                <p class="text-sm">Fill this form to create new password.</p>
                <form class="mt-5" action="/password/reset" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <label for="email" class="text-gray-500">Email</label>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input type="text" class="w-full focus:outline-none bg-transparent" value="{{$email}}"
                            name="email" id="email" required>
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
                    <label for="password_confirmation" class="text-gray-500">Re-type Password</label>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                        <input type="password" class="w-full focus:outline-none bg-transparent"
                            name="password_confirmation" id="password_confirmation" required>
                    </div>

                    <button type="submit"
                        class="mt-3 focus:outline-none focus:bg-yellow-400 text-center w-full bg-yellow-300 rounded-full text-black  py-3 font-medium">Reset
                        Password</button>

                </form>


            </div>

        </div>
    </div>
</div>

</div>

@endsection