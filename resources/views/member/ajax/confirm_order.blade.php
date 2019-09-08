@if($lanjut == true)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Order</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="post" action="/m/confirm/package">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <input type="hidden" name="id_paket" value="{{$data->id_paket}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Apakah anda ingin mengaktifasi member baru</p>
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
@endif

@if($lanjut == false)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Order</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <p class="lead text-danger" style="display: block;text-align: center;">Pin Anda tidak cukup untuk mengaktifasi member baru. Silakan beli Pin</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Tutup</button>
    </div>
</div>
@endif
