<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Autoconfirm Pembelian PIN</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <div class="row" id="loading" style="display:none;">
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="text-warning" style="display: block;text-align: center;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only"></span>
                        </div>
                        Loading...
                    </h5>
                </div>
            </div>
        </div>
        <form id="form-add" method="POST" action="/m/add/transaction-tron">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_trans" value="{{$data->id_trans}}">
                        <input type="hidden" name="sender" value="{{$data->sender}}">
                        <h5 class="lead text-muted" style="display: block;text-align: center;">Silakan klik Konfirmasi.</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>Jumlah Pin</label>
                        <input type="text" class="form-control" disabled="" value="{{$getTrans->total_pin}}">
                    </div>
                </div>
                <?php
                    $total = $getTrans->price + $getTrans->unique_digit;
                ?>
                <div class="col-md-8 col-xs-12">
                    <div class="form-group">
                        <label>Nominal Transfer</label>
                        <input type="text" class="form-control" disabled="" value="Rp. {{number_format($total, 0, ',', '.')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Transaction Hash #</label>
                        <textarea class="form-control" id="hash" rows="2" value="0" name="hash" readonly></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Konfirmasi</button>
    </div>

</div>
