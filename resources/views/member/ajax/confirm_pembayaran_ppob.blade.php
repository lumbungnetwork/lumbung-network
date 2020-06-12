<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/confirm/buy-ppob">
            {{ csrf_field() }}
            @if($data->buy_metode == 3)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Alamat eIDR</label>
                        <input type="text" class="form-control" readonly="" name="tron" value="{{$dataVendor->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" rows="2" name="tron_transfer" placeholder="Salin dan Tempelkan Hash# transaksi transfer dari Blockchain TRON di sini"></textarea>
                    </div>
                </div>
            </div>
            @endif
            <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
                <h5 class="text-success" style="text-align: center;"> Apakah Orderan pembelian anda ingin diteruskan ke Vendor pilihan anda? </h5>
            </div>
            <input type="hidden" name="id"  value="{{$data->id}}">
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Submit</button>
    </div>
</div>