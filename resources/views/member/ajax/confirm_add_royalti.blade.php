@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Transfer Royalti</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/transfer-royalti">
            {{ csrf_field() }}
            @if($data->royalti_metode == 1)
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" readonly="" name="royalti_bank_name" value="{{$data->royalti_bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" readonly="" name="royalti_account_no" value="{{$data->royalti_account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" name="royalti_account_name" value="{{$data->royalti_account_name}}">
                    </div>
                </div>
            </div>
            @endif
            @if($data->royalti_metode == 2)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" name="royalti_tron" value="{{$data->royalti_tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" rows="2" name="royalti_tron_transfer" placeholder="Salin dan Tempelkan Hash# transaksi transfer dari Blockchain TRON di sini"></textarea>
                    </div>
                </div>
            </div>
            @endif
            <?php
                $harga_dasar = ($getDataSales->sale_price * 10) / 11;
                $royalti = 8/100 * $harga_dasar;
            ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Total Royalti</label>
                        <input type="text" class="form-control" readonly="" value="Rp. {{number_format($royalti, 0, ',', ',')}}">
                    </div>
                </div>
            </div>
            <input type="hidden" name="master_id"  value="{{$data->id_master}}">
            <input type="hidden" name="metode"  value="{{$data->royalti_metode}}">
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
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> {{$check->pesan}} </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif