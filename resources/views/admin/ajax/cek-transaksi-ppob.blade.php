<div class="modal-content">
@if($type == 1)
<form class="login100-form validate-form" method="post" action="/adm/approve/ppob-transaction/eidr">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Approve</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        <div class="row">
            <input type="hidden" name="ppob_id" value="{{$getData->id}}">
            <p class="lead text-success" style="display: block;text-align: center;">Apakah anda ingin meneruskan transaksi pembelian?</p>
        </div>
    </div>
    
    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        <div class="divider"></div>
        <div class="right-side">
            <button type="submit" class="btn btn-info btn-link">Submit</button>
        </div>
    </div>
</form>
@endif

@if($type == 2)
<form class="login100-form validate-form" method="post" action="/adm/reject/ppob-transaction/eidr">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Batal</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        <div class="row">
            <input type="hidden" name="ppob_id" value="{{$getData->id}}">
            <p class="lead text-danger" style="display: block;text-align: center;">Apakah anda ingin membatalkan transaksi pembelian?</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Alasan</label>
                    <input type="text" class="form-control" name="reason" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        <div class="divider"></div>
        <div class="right-side">
            <button type="submit" class="btn btn-info btn-link">Submit</button>
        </div>
    </div>
</form>
@endif
</div>