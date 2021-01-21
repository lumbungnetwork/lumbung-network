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
        @php
        $url = "/m/detail/stockist-report/" . $masterSalesID;
        if ($sellerType == 2) {
        $url = "/m/detail/vendor-report/" . $masterSalesID;
        }
        @endphp

        <a href="{{$url}}" class="btn btn-primary float-right" type="button">Lanjut ke Pembayaran</a>

    </div>
    @endif
</div>
