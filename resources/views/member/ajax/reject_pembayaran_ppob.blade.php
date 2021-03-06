<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Reject Pembayaran</h5>
    </div>
    <form id="form-add" method="POST" action="/m/reject/vppob">
        <div class="modal-body" style="overflow-y: auto;max-height: 330px;">

            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <p class="lead text-muted" style="display: block;text-align: center;">Apakah anda ingin
                            membatalkan transaksi pembelian ppob?</p>
                    </div>
                </div>
            </div>

            <input type="hidden" name="ppob_id" value="{{$id_ppob}}">

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit">Batalkan
                Transaksi</button>
        </div>
    </form>
</div>
