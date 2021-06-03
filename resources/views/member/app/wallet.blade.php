@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        {{-- Balance Card --}}
        <div class="bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl p-2">
            <div class="p-2 text-2xl font-extralight text-gray-700 text-center">
                {{ number_format($netBalance, 0) }} eIDR
            </div>
            <div class="flex justify-center space-x-1 items-center">
                <a href="{{ route('member.walletDeposit') }}">
                    <button
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Deposit</button>
                </a>
                <a href="">
                    <button
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Withdraw</button>
                </a>
            </div>
        </div>

        {{-- History Table --}}
        <div class="mt-3 nm-inset-gray-200 rounded-2xl p-2 w-full overflow-x-scroll">
            <div class="my-2 text-md font-light text-gray-600 text-center">History</div>
            <table class="table table-auto text-xs font-extralight w-full">
                <thead>
                    <tr>
                        <th class="py-1 px-2">Date</th>
                        <th class="py-1 px-2">Amount</th>
                        <th class="py-1 px-2">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if($history != null)
                    @foreach($history as $row)
                    @php
                    $href = "";
                    if ($row->source == 6) {
                    $href = route('member.shopping.digitalPayment', ['masterSalesID' => $row->tx_id]);
                    }
                    @endphp

                    <tr>
                        <td class="py-1 px-2">{{date('d-m-y', strtotime($row->created_at))}}</td>
                        <td class="py-1 px-2">Rp{{number_format($row->amount, 0)}}</td>

                        <td class="py-1 px-2">
                            @if ($row->source == 6)
                            {{-- If from Sales --}}
                            @if ($row->type == 1)
                            <a class="text-green-600 font-medium underline" href="{{ $href }}">{{ $row->note }}</a>
                            @else
                            <a class="text-red-600 font-medium underline" href="{{ $href }}">{{ $row->note }}</a>
                            @endif
                            @else
                            {{-- Anything else --}}
                            @if ($row->type == 1)
                            <span class="text-green-600 font-medium">{{ $row->note }}</span>
                            @else
                            <span class="text-red-600 font-medium">{{ $row->note }}</span>
                            @endif
                            @endif


                        </td>

                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            <div>
                {{$history->links()}}
            </div>
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>

</script>
@endsection