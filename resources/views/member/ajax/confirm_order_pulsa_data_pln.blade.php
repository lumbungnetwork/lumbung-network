@if($data != null)
<form id="form-add" method="POST" action="/m/buy/ppob">
    @csrf
    @php
    $textProductName = 'No HP';
    if($type == 3){
    $textProductName = 'No. PLN' ;
    }
    if($type == 22){
    $textProductName = 'No. Kartu eToll' ;
    }
    if($type == 27){
    $textProductName = 'No. Kartu BRIZZI' ;
    }
    if($type == 28){
    $textProductName = 'No. Kartu Tapcash' ;
    }
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label>{{$textProductName}}</label>
                <input type="text" class="form-control" readonly="" name="no_hp" value="{{$data->no_hp}}">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label>Produk</label>
                <input type="text" class="form-control" readonly="" name="brand" value="{{$data->product_name}}">
            </div>
        </div>
    </div>
    <input type="hidden" name="vendor_id" value="{{$vendor_id}}">
    <input type="hidden" name="buyer_sku_code" value="{{$data->buyer_sku_code}}">
    <input type="hidden" name="type" value="{{$type}}">
    <button type="button" class="btn btn-success" onclick="confirmBuy()">Konfirmasi</button>
    <button type="button" class="btn btn-secondary" onclick="swal.close()">Tunda</button>
</form>
@endif

@if($data == null)
<h5 class="text-danger">{{$message}}</h5>
<button type="button" class="btn btn-secondary" onclick="swal.close()">Tutup</button>

@endif
