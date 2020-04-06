@if($check->can == true)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Input Stock</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/req-vstock">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Royalti</label>
                        <input type="text" class="form-control" readonly=""  value="Rp. {{number_format($data->royalti, 0, ',', ',')}}">
                    </div>
                </div>
            </div>
            @if($data->buy_metode == 2)
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" readonly="" name="bank_name" value="{{$data->bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" readonly="" name="account_no" value="{{$data->account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" name="account_name" value="{{$data->account_name}}">
                    </div>
                </div>
            </div>
            @endif
            @if($data->buy_metode == 3)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" name="tron" value="{{$data->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" rows="2" name="transfer" placeholder="Salin dan Tempelkan Hash# transaksi transfer dari Blockchain TRON di sini"></textarea>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_master" value="{{$data->id_master}}">
                        <input type="hidden" name="metode"  value="{{$data->buy_metode}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Apakah anda ingin <b>Mengkonfirmasi</b> Request Input Stock?</p>
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

@if($check->can == false)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Input Stock</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> {{$check->pesan}} </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif
