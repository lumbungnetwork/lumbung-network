<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Transfer</h5>
    </div>
    @if($bankPerusahaan != null)
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <div class="row" id="loading" style="display:none;">
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="text-danger" style="display: block;text-align: center;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only"></span>
                        </div>
                        Loading...
                    </h5>
                </div>
            </div>
        </div>
        <form id="form-add" method="POST" action="/m/add/transaction">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_trans" value="{{$data->id_trans}}">
                        <input type="hidden" name="bank_perusahaan_id" value="{{$bankPerusahaan->id}}">
                        <input type="hidden" name="is_tron" value="{{$cekType}}">
                        <p class="lead text-muted" style="display: block;text-align: center;">Sudahkah anda Transfer?
                        </p>
                    </div>
                </div>
            </div>
            @if($cekType == 0)
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
            @endif
            @if($cekType == 1)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->tron_name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Alamat Tron</label>
                        <input type="text" class="form-control" disabled="" value="{{$bankPerusahaan->tron}}">
                    </div>
                </div>
            </div>
            @endif
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
                        <label>Total</label>
                        <input type="text" class="form-control" disabled=""
                            value="Rp. {{number_format($total, 0, ',', '.')}}">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">Submit</button>
    </div>
    @endif
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
