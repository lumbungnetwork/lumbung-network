<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Transfer</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <div class="row" id="loading" style="display:none;">
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="text-danger" style="display: block;text-align: center;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only"></span>
                        </div>
                        Loading...
                    </h5>
                </div>
            </div>
        </div>
        <form id="form-add" method="POST" action="/m/reject/transaction">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_trans" value="{{$data->id_trans}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Apakah anda ingin
                            membatalkan transaksi pembelian pin?</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alasan</label>
                        <input type="text" class="form-control" name="reason" autocomplete="off">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">Submit</button>
    </div>
</div>
