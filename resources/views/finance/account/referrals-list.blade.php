@extends('finance.layout.app')
@section('content')

<div class="mt-10 flex flex-col justify-center px-3 sm:px-5">

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

            <div class="px-3 sm:px-6 py-6">
                <div class="text-center">
                    <h2 class="font-extralight text-3xl sm:text-4xl text-gray-600">{{$title}}</h2>

                </div>



                <div class="mt-5 nm-inset-gray-50 rounded-xl p-2">

                    @if ($referrals == null)
                    <p class="text-xl font-light text-center">You don't have referral, yet.</p>
                    @else
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase">
                                                    Username
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase">
                                                    Liquidity
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($referrals as $referral)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs font-light">
                                                    {{$referral['username']}}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs font-light">
                                                    ${{number_format($referral['liquidity'])}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                </div>


            </div>

        </div>
    </div>


</div>


@endsection