@if($check->can == true)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Pembayaran Royalti</h5>
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
        @if($data->buy_metode == 2)
        <form id="form-add" method="POST" action="/m/add/req-vstock">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_master" value="{{$data->id_master}}">
                        <input type="hidden" name="metode" value="{{$data->buy_metode}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Sudahkah anda
                            transfer?
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-12">
                    <div class="form-group">
                        <label>Royalti</label>
                        <input type="text" class="form-control" readonly
                            value="Rp. {{number_format($data->royalti, 0, ',', '.')}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" name="bank_name" readonly value="{{$data->bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" name="account_no" readonly
                            value="{{$data->account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" name="account_name" readonly
                            value="{{$data->account_name}}">
                    </div>
                </div>
            </div>

        </form>
        @endif
        @if($data->buy_metode == 3)
        <form id="form-add" method="POST" action="/m/add/req-vstock-tron">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_master" value="{{$data->id_master}}">
                        <input type="hidden" name="sender" value="{{$data->sender}}">
                        <input type="hidden" name="royalti" value="{{$data->royalti}}">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nominal Transfer</label>
                        <input type="text" class="form-control"
                            value="Rp. {{number_format($data->royalti, 0, ',', '.')}}" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Transaction Hash #</label>
                        <textarea onchange="cleanHash()" class="form-control" style="font-size: 12px;" id="hash"
                            rows="3" name="transfer"
                            placeholder="Tempelkan (Paste) Hash transaksi transfer eIDR sesuai nominal royalti di sini"></textarea>
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
</div>
@endif

@if($check->can == false)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Input Stock</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> {{$check->pesan}} </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif
