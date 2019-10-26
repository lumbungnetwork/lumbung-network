@if($data->buy_metode > 0)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Pembayaran</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="post" action="/m/pembayaran">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="master_sale_id" value="{{$data->getDataMaster->id}}">
                        <input type="hidden" name="buy_metode" value="{{$data->buy_metode}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Total Belanja</label>
                        <input type="text" class="form-control" readonly="" value="Rp. {{number_format($data->getDataMaster->total_price, 0, ',', ',')}}">
                    </div>
                </div>
            </div>
            @if($data->buy_metode == 1)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <input type="text" class="form-control" readonly="" value="COD">
                    </div>
                </div>
            </div>
            @endif
            @if($data->buy_metode == 2)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" name="account_name" value="{{$data->account_name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nomor Rekening</label>
                        <input type="text" class="form-control" readonly="" name="account_no" value="{{$data->account_no}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" readonly="" name="bank_name" value="{{$data->bank_name}}">
                    </div>
                </div>
            </div>
            @endif
            @if($data->buy_metode == 3)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Alamat eIDR</label>
                        <input type="text" class="form-control" readonly="" name="tron" value="{{$data->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" rows="2" name="tron_transfer" placeholder="Salin dan Tempelkan Hash# transaksi transfer dari Blockchain TRON di sini"></textarea>
                    </div>
                </div>
            </div>
            @endif
        </form>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Confirm</button>
    </div>
</div>
@endif

@if($data->buy_metode == 0)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> Anda Belum memilih metode pembayaran </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif