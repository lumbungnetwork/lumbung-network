<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Reject Konfirmasi Order</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="post" action="/m/reject/package">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <input type="hidden" name="id_paket" value="{{$data->id_paket}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Apakah anda ingin Me-Reject member baru</p>
                    </div>
                </div>
            </div>
        </form>     
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Submit</button>
    </div>
</div>
