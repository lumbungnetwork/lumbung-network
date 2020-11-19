@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/edit/address">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" class="form-control" readonly="" name="provinsi"
                            value="{{$dataRequest->provinsi}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" class="form-control" readonly="" name="kota" value="{{$dataRequest->kota}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" class="form-control" readonly="" name="kecamatan"
                            value="{{$dataRequest->kecamatan}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Kelurahan</label>
                        <input type="text" class="form-control" readonly="" name="kelurahan"
                            value="{{$dataRequest->kelurahan}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" readonly="" name="alamat"
                            value="{{$dataRequest->alamat}}">
                    </div>
                </div>
            </div>
            <input type="hidden" name="kode_daerah" value="{{$dataRequest->kode_daerah}}">
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
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
