@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/req/vendor">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="success" value="1">
                        <input type="hidden" name="username1" value="{{$data->username1}}">
                        <input type="hidden" name="username2" value="{{$data->username2}}">
                        <input type="hidden" name="username3" value="{{$data->username3}}">
                        <input type="hidden" name="username4" value="{{$data->username4}}">
                        <input type="hidden" name="username5" value="{{$data->username5}}">
                        <input type="hidden" name="delegate" value="{{$data->delegate}}">
                        <input type="hidden" name="hash" value="{{$data->hash}}">
                        <h4 class="text-success" style="text-align: center;"> Syarat pengajuan Vendor anda telah
                            terpenuhi. Silakan klik Ajukan untuk mengirimkan permohonan ke Tim Delegasi. Tim Delegasi
                            akan memoderasi dalam kurun 2x24 jam.</h4>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">Ajukan</button>
    </div>
</div>

@endif

@if($check->can == false)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> {{$check->pesan}} </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif
