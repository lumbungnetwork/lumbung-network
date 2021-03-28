@extends('finance.layout.app')
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
                    <img class="object-contain w-32 opacity-90" src="/image/lumbung-finance_2.png"
                        alt="lumbung finance">
                </div>

                <!-- Layout  -->
                <h1 class="text-3xl font-medium mb-2">Login</h1>
                <p class="text-sm">Use your username and password to log into your account.</p>
                <form class="space-y-5 mt-5" method="POST" action="/login">
                    @csrf
                    <input type="text" class="w-full h-12 border border-gray-400 rounded px-3" placeholder="Username"
                        name="username" />
                    <input type="password" class="w-full h-12 border border-gray-400 rounded px-3"
                        placeholder="Password" name="password" />

                    <div class="">
                        <a href="#!" class="font-medium text-blue-700 hover:bg-blue-300 rounded-full p-2">Forgot
                            Password ?</a>
                    </div>

                    <button type="submit"
                        class="text-center w-full bg-yellow-300 rounded-full text-black  py-3 font-medium">Login</button>

                </form>

                <!-- Footer -->
                <p class="mt-2">...or, you may <a href="/register" class="text-blue-700 font-medium">register
                        here</a> </p>
            </div>

        </div>
    </div>
</div>

</div>

@endsection