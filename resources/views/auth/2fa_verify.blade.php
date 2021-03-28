@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">

    <div class="relative w-full max-w-md mx-auto">

        <div class="relative nm-flat-gray-200 rounded-3xl">

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
                    <p class="text-xl font-light my-4">
                        This area is protected with Two Factor Authentication.
                    </p>

                </div>



                @if ($errors->any())
                <div class="nm-inset-red-100 rounded-2xl p-3 my-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                @endif

                <p class="mt-4 text-sm font-medium">Enter the 6 digit code from Google Authenticator app:</p>

                <form class="" action="{{ route('finance.2faVerify') }}" method="POST" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">

                        <input id="one_time_password" name="one_time_password" type="text"
                            class="w-full focus:outline-none bg-transparent" placeholder="Authenticator Code" required>
                    </div>

                    <button
                        class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600"
                        type="submit">Authenticate</button>
                    <div class="clear-right"></div>
                </form>


            </div>

        </div>
    </div>


</div>

@endsection