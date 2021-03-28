@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-6">

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
                <a href="/account">
                    <span class="font-bold text-xl">&#8592;</span>
                    <span class="font-light text-lg">Back to Account</span>
                </a>

            </div>

            <div class="px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-4xl text-gray-600">{{$title}}</h2>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-4 ">
                    <h4 class="font-light text-md">Active Email</h4>

                    <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 flex-wrap overflow-hidden">
                        <p class="text-black text-sm font-extralight">{{$user->email}}</p>
                    </div>

                    <h4 class="mt-4 font-light text-md">Change Email</h4>

                    <form action="/account/change-email" method="POST" autocomplete="off">
                        @csrf
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                            <input class="w-full focus:outline-none bg-transparent" type="text" placeholder="New Email"
                                name="new-email">
                        </div>
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2">
                            <input class="w-full focus:outline-none bg-transparent" type="password"
                                placeholder="Password" name="password">
                        </div>

                        <button type="submit"
                            class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Change
                            Email</button>

                    </form>

                    <div class="clear-right"></div>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-4 ">
                    <h4 class="font-light text-md">Change Password</h4>

                    <form action="/account/change-password" method="POST" autocomplete="off">
                        @csrf
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                            <input class="w-full focus:outline-none bg-transparent" type="password"
                                placeholder="Old Password" name="old-password">
                        </div>
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2">
                            <input class="w-full focus:outline-none bg-transparent" type="password"
                                placeholder="New Password" name="password">
                        </div>
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2">
                            <input class="w-full focus:outline-none bg-transparent" type="password"
                                placeholder="Re-type New Password" name="password_confirmation">
                        </div>

                        <button type="submit"
                            class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Change
                            Password</button>

                    </form>

                    <div class="clear-right"></div>

                </div>

                <div class="mt-4 nm-convex-gray-50 rounded-xl p-4 ">
                    <h4 class="font-light text-md">Beneficiary (Optional)</h4>
                    <p class="text-sm font-extralight">You could set an email to be the beneficiary of this account if
                        in case there's no activity in this account for set amount of time, a link will be sent to this
                        email to gain access to this account.</p>
                    <form action="/account/set-beneficiary" method="POST" autocomplete="off">
                        @csrf
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                            <input class="w-full focus:outline-none bg-transparent" type="text"
                                placeholder="Beneficiary's Email" name="beneficiary-email">
                        </div>
                        <div class="mt-2 nm-inset-gray-50 rounded-xl p-2 ">
                            <select class="w-full focus:outline-none" name="heart-beat">
                                <option value="180" selected>6 months</option>
                                <option value="365">12 months</option>
                                <option value="730">2 years</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Set
                            Beneficiary</button>

                    </form>

                    <div class="clear-right"></div>

                </div>

            </div>

        </div>
    </div>


</div>


@endsection