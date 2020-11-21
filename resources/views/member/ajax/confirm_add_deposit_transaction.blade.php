<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Transfer</h5>
    </div>
    <?php $total = $getTrans->price + $getTrans->unique_digit;?>
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
        @if($cekType == 0)
        <form id="form-add" method="POST" action="/m/add/deposit-transaction">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_trans" value="{{$data->id_trans}}">
                        <input type="hidden" name="bank_perusahaan_id" value="{{$bankPerusahaan->id}}">
                        <input type="hidden" name="is_tron" value="{{$cekType}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Sudahkah anda transfer?
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-12">
                    <div class="form-group">
                        <label>Total Deposit</label>
                        <input type="text" class="form-control" disabled=""
                            value="Rp. {{number_format($total, 0, ',', '.')}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->account_name}}">
                    </div>
                </div>
            </div>

        </form>
        @endif
        @if($cekType == 1)
        <form id="form-add" method="POST" action="/m/add/deposit-transaction-tron">
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
                        <textarea onchange="cleanHash()" class="form-control" style="font-size: 12px;" id="hash"
                            rows="3" name="tron_transfer"
                            placeholder="Tempelkan (Paste) Hash transaksi transfer eIDR sesuai nominal deposit di sini"></textarea>
                    </div>
                </div>
            </div>


        </form>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">Submit</button>
    </div>
    @if($cekType == null)
    @if($bankPerusahaan == null)
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> Anda belum memilih metode pembayaran</h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
    </div>
    @endif
    @endif

</div>
