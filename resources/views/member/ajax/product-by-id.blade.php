<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="text-center">
            <img src="{{ asset('/storage/products') }}/{{$product->image}}" style="width: auto; max-width: 100%;">
            <h6 style="font-size: 14px; font-weight:200; margin-top: 10px;">
                {{$product->name}} {{$product->size}}</h6>

            <dd style="font-size: 15px;">
                <b>Rp{{number_format($product->price)}}</b></dd>
            <p>Stok tersedia:
                <b>{{$product->qty}}</b>
            </p>
            <dd>Deskripsi Produk:</dd>
            <p>{{$product->desc}}</p>
            <br>

            @if($product->qty > 0)
            <form id="form-order" method="post" action="/m/add-to-cart">
                @csrf
                <label for="quantity">Jumlah Order</label>
                <div class="quantity-block">
                    <input class="minus" type="button" value="-">
                    <input type="number" inputmode="numeric" pattern="[0-9]*" value="1" id="quantity" name="quantity"
                        class="quantity" size="4" min="1" max="{{$product->qty}}" />
                    <input class="plus" type="button" value="+">
                </div>

                <input type="hidden" id="product_id" name="product_id" value="{{$product->id}}">

            </form>
        </div>


    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="addToCart()">Masukkan ke keranjang</button>
    </div>

    @else

</div>
<h6 class="text-center text-muted">Stok Produk habis.</h6>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
</div>
@endif
</div>
