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
                <p class="text-sm">Enter your email, a link to reset password will be sent.</p>
                <form class="space-y-5 mt-5" method="POST" action="{{ url('/password/email') }}">
                    @csrf
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">

                        <input placeholder="Email" name="email" id="email" type="text"
                            class="w-full focus:outline-none bg-transparent" required>
                    </div>
                    <button type="submit"
                        class="text-center w-full bg-yellow-300 rounded-full text-black  py-3 font-medium focus:outline-none focus:bg-yellow-500">Reset</button>

                </form>

                <!-- Footer -->
                <p class="mt-2">...or, you may try to <a href="/login" class="text-blue-700 font-medium">login
                        first</a> </p>
            </div>

        </div>
    </div>
</div>

</div>

@endsection