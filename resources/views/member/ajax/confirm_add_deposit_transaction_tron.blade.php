<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Isi Deposit Vendor</h5>
    </div>

    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <div class="row" id="loading" style="display:none;">
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="text-warning" style="display: block;text-align: center;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only"></span>
                        </div>
                        Sedang Memproses...
                    </h5>
                </div>
            </div>
        </div>
        <form id="form-add-tron" method="POST" action="/m/add/deposit-transaction-tron">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_trans" value="{{$data->id_trans}}">
                        <input type="hidden" name="sender" value="{{$data->sender}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                    $total = $getTrans->price + $getTrans->unique_digit;
                ?>
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nominal Transfer</label>
                        <input type="text" class="form-control" disabled=""
                            value="Rp. {{number_format($total, 0, ',', '.')}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Transaction Hash #</label>
                        <textarea class="form-control" id="hash" rows="2" name="tron_transfer" readonly></textarea>
                    </div>
                </div>
            </div>


        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" disabled
            onclick="confirmSubmitTron()">Konfirmasi</button>
    </div>


</div>
