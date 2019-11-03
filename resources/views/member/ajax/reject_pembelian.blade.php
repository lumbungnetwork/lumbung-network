@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Reject Pembayaran</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/reject-pembelian">
            {{ csrf_field() }}
            @if($getDataSales->buy_metode == 1)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Metode</label>
                        <input type="text" class="form-control" readonly="" value="COD">
                    </div>
                </div>
            </div>
            @endif
            @if($getDataSales->buy_metode == 2)
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataSales->bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataSales->account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataSales->account_name}}">
                    </div>
                </div>
            </div>
            @endif
            @if($getDataSales->buy_metode == 3)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataSales->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" readonly="" rows="2">{{$getDataSales->tron_transfer}}</textarea>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Total Transfer</label>
                        <input type="text" class="form-control" readonly="" value="Rp. {{number_format($getDataSales->sale_price, 0, ',', ',')}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alasan</label>
                        <textarea class="form-control" id="reason" rows="2" name="reason" autocomplete="off"></textarea>
                    </div>
                </div>
            </div>
            <input type="hidden" name="master_id"  value="{{$data->id_master}}">
        </form>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Submit</button>
    </div>
</div>
@endif