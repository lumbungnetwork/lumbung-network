@if($check->can == true)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Input Stock Vendor</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/req-vstock-tron">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Royalti</label>
                        <input type="text" class="form-control" readonly=""  value="Rp. {{number_format($data->royalti, 0, ',', ',')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Tujuan Transfer</label>
                        <input type="text" class="form-control" readonly="" name="tron" value="{{$data->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="hash" readonly rows="2" value="0" name="transfer"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_master" value="{{$data->id_master}}">
                        <input type="hidden" name="metode"  value="{{$data->buy_metode}}">
                        <input type="hidden" name="sender"  value="{{$data->sender}}">
                        <input type="hidden" name="royalti"  value="{{$data->royalti}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Silakan tekan tombol <b>Konfirmasi</b> di bawah:</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-primary waves-effect waves-light" id="submit" disabled onclick="confirmSubmit()">Konfirmasi</button>
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
