@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        @if ($digital_tx)
        @foreach ($digital_tx as $tx)
        <div class="my-3 nm-flat-white rounded-2xl p-2 flex justify-between items-center">
            <div class="text-sm text-gray-600">Request pembelian {{ $tx->buyer_code }} oleh {{ $tx->buyer->username }}
            </div>
            <a href="{{ route('member.store.confirmDigitalOrder', ['id' => $tx->id]) }}">
                <button style="font-size: 10px"
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-300 to-purple-300 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Detail</button>
            </a>
        </div>
        @endforeach

        @endif

        @if ($physical_tx)
        @foreach ($physical_tx as $tx)
        <div class="my-3 nm-flat-white rounded-2xl p-2 flex justify-between items-center">
            <div class="text-sm text-gray-600">Request belanja produk fisik oleh {{ $tx->buyer->username }}
            </div>
            <a href="{{ route('member.store.confirmPhysicalOrder', ['masterSalesID' => $tx->id]) }}">
                <button style="font-size: 10px"
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-300 to-purple-300 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Detail</button>
            </a>
        </div>
        @endforeach

        @endif
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>

</script>
@endsection