@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4 flex flex-wrap justify-between">
        {{-- Operator List --}}
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 21]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img class="w-10" src="/asset_new/img/emoney/gopay.jpg" alt="logo GoPay">
                </div>
                <div class="text-xs text-gray-500 font-extralight">GoPay</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 22]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img class="w-10" src="/asset_new/img/emoney/etoll.jpg" alt="logo E-Toll">
                </div>
                <div class="text-xs text-gray-500 font-extralight">E-Toll</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 23]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-col flex-wrap justify-center items-center">
                <div class="object-contain w-10">
                    <img src="/asset_new/img/emoney/ovo.jpg" alt="logo OVO">
                </div>
                <div class="text-xs text-gray-500 font-extralight">OVO</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 24]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img class="w-10" src="/asset_new/img/emoney/dana.jpg" alt="logo DANA">
                </div>
                <div class="text-xs text-gray-500 font-extralight">DANA</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 25]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-col flex-wrap justify-between items-center">
                <div class="object-contain w-8">
                    <img src="/asset_new/img/emoney/linkaja.jpg" alt="logo LinkAja">
                </div>
                <div class="text-xs text-gray-500 font-extralight">LinkAja</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 26]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img class="w-10" src="/asset_new/img/emoney/shopeepay.jpg" alt="logo ShoppeePay">
                </div>
                <div class="text-xs text-gray-500 font-extralight">ShoppeePay</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 27]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-col flex-wrap justify-between items-center">
                <div class="object-contain w-8">
                    <img src="/asset_new/img/emoney/brizzi.jpg" alt="logo BRIZZI">
                </div>
                <div class="text-xs text-gray-500 font-extralight">BRIZZI</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.emoneyPricelist', ['operator_id' => 28]) }}"
                class="my-2 nm-flat-white w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img class="w-10" src="/asset_new/img/emoney/tapcash.jpg" alt="logo Tapcash">
                </div>
                <div class="text-xs text-gray-500 font-extralight">Tapcash</div>
            </a>
        </div>



    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>

</script>
@endsection