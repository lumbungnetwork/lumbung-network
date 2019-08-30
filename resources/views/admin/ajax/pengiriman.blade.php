<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/adm/kirim-paket">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Total Pin</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->total_pin}}">
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Alamat Kirim</label>
                        <textarea class="form-control" id="alamat_kirim" rows="2" readonly="">{{$getData->alamat_kirim}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Kurir</label>
                        <input type="text" class="form-control" name="kurir_name" readonly="" value="{{$data->kurir_name}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>No. Resi</label>
                        <input type="text" class="form-control" name="no_resi"readonly="" value="{{$data->no_resi}}">
                    </div>
                </div>
            </div>
            <input type="hidden" name="cekId" id="cekId" value="{{$data->id}}" >
            <input type="hidden" name="cekUserId" id="cekUserId" value="{{$data->user_id}}" >
        </form>    
    </div>
    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-default btn-link" data-dismiss="modal">Tutup</button>
        </div>
        <div class="divider"></div>
        <div class="right-side">
            <button type="button" class="btn btn-danger btn-link" id="submit" onclick="confirmSubmit()">Submit</button>
        </div>
    </div>
</div>