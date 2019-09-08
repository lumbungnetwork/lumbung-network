@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/transfer-pin">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label>Jumlah Pin</label>
                        <input type="text" class="form-control" readonly="" name="total_pin" value="{{$dataRequest->total_pin}}">
                    </div>
                </div>
                <div class="col-md-9 col-xs-12">
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="text" class="form-control" readonly="" value="{{$dataRequest->user_code}}">
                    </div>
                </div>
                <input type="hidden" name="to_id" value="{{$dataRequest->id}}">
            </div>
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" readonly="" value="{{$dataRequest->email}}">
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>No. Handphone</label>
                        <input type="text" class="form-control" readonly="" value="{{$dataRequest->hp}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="text" class="form-control" name="confirm_password" autocomplete="off" placeholder="Password anda">
                    </div>
                </div>
            </div>
        </form>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Kirim</button>
    </div>
</div>



@endif

@if($check->can == false)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> {{$check->pesan}} </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif