@if(!empty($products->toArray()))
@foreach ($products as $product)
<div class="col-6 p-2 mb-3 text-center">
    <div class="rounded-lg bg-white shadow p-2 px-0">
        <img src="{{ asset('/storage/products') }}/{{$product->image}}" style="width: auto; max-width: 100%;"
            data-toggle="modal" data-target="#orderModal" onclick="showDetail({{$product->id}})">
        <h6 style="font-size: 14px; font-weight:200; margin-top: 10px;">
            {{$product->name}} </h6>
        <dd style="font-size:12px">{{$product->size}} </dd>
        <dd style="font-size: 15px;">
            <b>Rp{{number_format($product->price)}}</b></dd>
        <small>Stok tersedia:
            <b>{{$product->qty}}</b>
        </small>
    </div>
</div>
@endforeach
@else
<div class="container text-center">
    <h6>Tidak ada produk</h6>
</div>
@endif
