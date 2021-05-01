@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
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
        <form id="form-add" method="POST" action="/m/add/transfer-pin">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label>Pin</label>
                        <input type="text" class="form-control" readonly="" name="total_pin"
                            value="{{$dataRequest->total_pin}}">
                    </div>
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label>Penerima</label>
                        <input type="text" class="form-control" readonly="" value="{{$dataRequest->username}}">
                    </div>
                </div>
                <input type="hidden" name="to_id" value="{{$dataRequest->id}}">
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Konfirmasi Password Anda</label>
                        <input type="password" id="password" class="form-control" name="confirm_password"
                            autocomplete="off" placeholder="Password anda">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 my-3">
                    <div class="pretty p-icon p-toggle p-plain">
                        <input type="checkbox" id="show-password" />
                        <div class="state p-success-o p-on">
                            <i class="icon mdi mdi-eye"></i>
                            <label>Sembunyikan Password</label>
                        </div>
                        <div class="state p-off">
                            <i class="icon mdi mdi-eye-off"></i>
                            <label>Tampilkan Password</label>
                        </div>
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