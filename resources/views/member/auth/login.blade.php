@extends('member.components.main')
@section('content')

<div class="mt-10 flex flex-col justify-center px-4">
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

            <div class="px-4 lg:px-20 py-6">

                <div class="mt-8 mb-4">
                    <img class="object-contain w-full max-h-24 lg:max-h-32" src="/image/logo_lumbung2.png"
                        alt="logo lumbung network">
                </div>

                <!-- Layout  -->
                <h1 class="text-3xl font-light mb-2">Login</h1>
                <p class="text-sm font-light">Silakan masuk ke akun anda dengan username dan password anda.</p>
                <form class="space-y-5 mt-5" method="POST" action="/login" autocomplete="off">
                    @csrf
                    <div class="mt-2 nm-inset-gray-50 rounded-xl py-2 px-4 ">

                        <input placeholder="Username" name="username" type="text"
                            class="w-full focus:outline-none bg-transparent" required>
                    </div>
                    <div class="mt-2 nm-inset-gray-50 rounded-xl py-2 px-4 ">

                        <input type="password" class="w-full focus:outline-none bg-transparent" placeholder="Password"
                            name="password" required>
                    </div>


                    <div class="">
                        <a href="/password/reset"
                            class="font-medium text-blue-700 hover:bg-blue-300 rounded-full p-2">Lupa Password?</a>
                    </div>

                    <button type="submit"
                        class="text-center w-full bg-yellow-300 rounded-full text-black  py-3 font-medium focus:outline-none focus:bg-yellow-500">Login</button>

                </form>
            </div>

        </div>
    </div>
</div>

</div>

@endsection