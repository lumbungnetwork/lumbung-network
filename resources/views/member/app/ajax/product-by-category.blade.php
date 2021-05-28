@if(!empty($products->toArray()))
@foreach ($products as $product)
<div class="nm-flat-gray-50 rounded-xl p-0 mx-1 my-2" onclick="showDetail({{$product->id}})" style="max-width: 46%">
    <div>
        <img class="object-cover rounded-tl-2xl rounded-tr-2xl"
            src="{{ asset('/storage/products') }}/{{$product->image}}" alt="product-picture">
    </div>
    <div class="mt-1 py-1 text-center">
        <h4 class="text-xs font-medium text-gray-700">{{$product->name}}</h4>
        <p class="font-light text-gray-600" style="font-size: 12px;">{{$product->size}}</p>
        <p class="text-xs text-black">Rp{{number_format($product->price)}}</p>
        <p class="text-xs text-gray-600 font-extralight">Stok: <b>{{$product->qty}}</b></p>
    </div>
</div>
@endforeach
@else
<div class="container text-center">
    <h6>Tidak ada produk</h6>
</div>
@endif