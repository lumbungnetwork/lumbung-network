@if($data != null)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <div class="row" id="loading" style="display:none;">
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="text-danger" style="display: block;text-align: center;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h5>
                </div>
            </div>
        </div>
        <form id="form-add" method="POST" action="/m/buy/ppob">
            {{ csrf_field() }}
            <?php
                $textProductName = 'No HP';
                if($type == 3){
                    $textProductName = 'No. PLN'  ;
                }
            ?>
            <div class="row">
                <div class="col-md-5 col-xs-12">
                    <div class="form-group">
                        <label>{{$textProductName}}</label>
                        <input type="text" class="form-control" readonly="" name="no_hp" value="{{$data->no_hp}}">
                    </div>
                </div>
                <div class="col-md-7 col-xs-12">
                    <div class="form-group">
                        <label>Operator</label>
                        <input type="text" class="form-control" readonly="" name="brand" value="{{$data->brand}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" class="form-control" readonly="" value="Rp {{number_format($data->price, 0, ',', ',')}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <?php
                            $metode = 'COD';
                            if($data->buy_method == 3){
                                $metode = 'eIDR';
                            }
                        ?>
                        <label>Metode Pembayaran</label>
                        <input type="text" class="form-control" readonly=""  value="{{$metode}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Vendor</label>
                        <input type="text" class="form-control" readonly="" value="{{$dataVendor->user_code}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" readonly="" value="{{$data->message}}">
                    </div>
                </div>
            </div>
            <input type="hidden" name="price"  value="{{$data->price}}">
            <input type="hidden" name="vendor_id"  value="{{$dataVendor->id}}">
            <input type="hidden" name="buyer_sku_code"  value="{{$data->buyer_sku_code}}">
            <input type="hidden" name="buy_method"  value="{{$data->buy_method}}">
            <input type="hidden" name="type"  value="{{$type}}">
            <input type="hidden" name="harga_modal"  value="{{$data->harga_modal}}">
            <input type="hidden" name="message"  value="{{$data->message}}">
        </form>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Submit</button>
    </div>
</div>
@endif

@if($data == null)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <h5 class="text-danger">{{$message}}</h5> 
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
    </div>
</div>
@endif