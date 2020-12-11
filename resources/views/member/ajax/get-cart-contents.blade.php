<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Keranjang Belanja Anda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">

        <div class="list-group">
            <?php $no = 1; ?>
            @foreach($products as $product)
            <a href="#" id="item-{{$no}}" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{$product->name}}</h5>
                    <small class="text-info" onclick="deleteCartItem({{$product->id}}, {{$no}})">hapus</small>
                </div>
                <p class="mb-1"><b>Harga:</b> Rp{{number_format($product->price)}}<br>
                    <b>Kuantitas:</b> {{$product->quantity}}</p>
            </a>
            <?php $no++ ?>
            @endforeach
        </div>

        <div class="rounded-lg bg-white shadow float-right p-3 mt-2">
            <h5 class="mb-0">Total: {{number_format(Cart::getSubTotal())}}</h5>
        </div>

    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="checkout()">Checkout</button>
    </div>


</div>
