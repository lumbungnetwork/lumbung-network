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

                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <div class="flex float-right mr-6 text-xl text-red-500 nm-convex-gray-50 rounded-full px-3 py-2">

                        <button type="submit"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                    </div>
                </form>

                <div class="mt-8 mb-4">
                    <img class="object-contain w-full max-h-24 lg:max-h-32" src="/image/logo_lumbung2.png"
                        alt="logo lumbung network">
                </div>

                <div>
                    <h1>Welcome, {{$user->user_code}}</h1>
                </div>

            </div>

        </div>
    </div>
</div>

</div>

@endsection