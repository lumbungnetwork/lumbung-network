<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Checkout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    @if($status == false)

    <div class="modal-body">

        <h5>Stock tidak cukup!</h5>
        <p>Produk <mark>{{$name}}</mark> hanya tersedia sebanyak {{$stock}} saja. Silakan menghapus produk tersebut dan
            menambahkan
            sesuai stock yang tersedia.</p>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="checkCart()">Perbaiki Keranjang</button>
    </div>

    @else

    <div class="modal-body">

        <h5>Keranjang belanja anda sudah siap!</h5>
        <p>Silakan klik Bayar untuk lanjut ke pembayaran.</p>

    </div>
    <div class="modal-footer">
        <form method="POST" action="/m/settlement">
            @csrf
            <input type="hidden" name="masterSalesID" value="{{$masterSalesID}}">
            <input type="hidden" name="sellerType" value="{{$sellerType}}">
            <button type="submit" class="btn btn-primary">Pilih Pembayaran</button>
        </form>

    </div>
    @endif
</div>
