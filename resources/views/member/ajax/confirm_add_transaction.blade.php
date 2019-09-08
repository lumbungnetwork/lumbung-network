<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Transfer</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/transaction">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <input type="hidden" name="id_trans" value="{{$data->id_trans}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Untuk mendapatkan Pin,</p>
                        <p class="lead text-muted" style="display: block;text-align: center;">Silakan transfer ke rekening tersebut.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->bank_name}}">
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->account_name}}">
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
