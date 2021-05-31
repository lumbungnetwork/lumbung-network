@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-2">
        <div class="mt-4 flex justify-end">
            <a href="{{ route('member.store.addProduct') }}">
                <button
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-bold text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">&#xFF0B;
                    Produk</button>
            </a>
        </div>
        <div class="mt-4 p-3 border border-gray-400 border-solid rounded-xl">
            <div class="-mt-7 w-16 text-lg text-center font-bold text-gray-600 tracking-wider bg-gray-200">Produk</div>
            <div class="mt-2 space-y-2">
                @if (count($products) > 0) @foreach ($products as $product) <div
                    class="nm-flat-white rounded-lg p-2 flex justify-between space-x-2">
                    <div class="w-1/4 flex flex-col items-center">
                        <img class="object-cover rounded-lg"
                            src="{{ asset('/storage/products') }}/{{ $product->image }}" alt="product-picture">
                        <a href="{{ route('member.store.editProduct', ['product_id' => $product->id]) }}">
                            <button style="font-size: 10px"
                                class="rounded-lg py-1 px-2 bg-gradient-to-br from-red-300 to-purple-300 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Edit</button>
                        </a>
                    </div>
                    <div class="w-3/4">
                        <h4 class="text-sm text-gray-700 font-medium">{{ $product->name }}</h4>
                        <div class="text-xs text-gray-500 font-light">{{ $product->size }}</div>
                        <div class="text-sm text-gray-600 font-light tracking-tighter">
                            Rp{{ number_format($product->price, 0) }}</div>
                        <div class="text-xs text-gray-500 font-light">Stock: {{ $product->qty }}</div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="text-sm text-gray-600 font-medium">
                    Anda belum memiliki produk, silakan menambahkan produk baru.
                </div>
                @endif
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