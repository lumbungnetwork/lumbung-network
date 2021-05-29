@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4 flex flex-wrap justify-between">
        {{-- Operator List --}}
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.prepaidPhonePricelist', ['operator_id' => 1, 'type_id' => $type]) }}"
                class="my-2 nm-convex-gray-100 w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img src="/asset_new/img/providers/telkomsel.png" alt="logo telkomsel">
                </div>
                <div class="text-xs text-gray-500 font-extralight">Telkomsel</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.prepaidPhonePricelist', ['operator_id' => 2, 'type_id' => $type]) }}"
                class="my-2 nm-convex-gray-100 w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img src="/asset_new/img/providers/indosat.png" alt="logo indosat">
                </div>
                <div class="text-xs text-gray-500 font-extralight">Indosat</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.prepaidPhonePricelist', ['operator_id' => 3, 'type_id' => $type]) }}"
                class="my-2 nm-convex-gray-100 w-20 h-20 p-2 rounded-lg flex flex-col flex-wrap justify-center items-center">
                <div class="object-contain w-10">
                    <img src="/asset_new/img/providers/xl.png" alt="logo XL">
                </div>
                <div class="text-xs text-gray-500 font-extralight">XL</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.prepaidPhonePricelist', ['operator_id' => 4, 'type_id' => $type]) }}"
                class="my-2 nm-convex-gray-100 w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img src="/asset_new/img/providers/axis.png" alt="logo Axis">
                </div>
                <div class="text-xs text-gray-500 font-extralight">Axis</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.prepaidPhonePricelist', ['operator_id' => 5, 'type_id' => $type]) }}"
                class="my-2 nm-convex-gray-100 w-20 h-20 p-2 rounded-lg flex flex-col flex-wrap justify-between items-center">
                <div class="object-contain w-8">
                    <img src="/asset_new/img/providers/3.png" alt="logo 3">
                </div>
                <div class="text-xs text-gray-500 font-extralight">3</div>
            </a>
        </div>
        <div class="w-1/2 flex justify-center">
            <a href="{{ route('member.shopping.prepaidPhonePricelist', ['operator_id' => 6, 'type_id' => $type]) }}"
                class="my-2 nm-convex-gray-100 w-20 h-20 p-2 rounded-lg flex flex-wrap justify-center items-center">
                <div class="object-contain">
                    <img src="/asset_new/img/providers/smartfren.png" alt="logo Smartfren">
                </div>
                <div class="text-xs text-gray-500 font-extralight">Smartfren</div>
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