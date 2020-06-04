@if($check->can == true)
    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
            </div>
            <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
                <form id="form-add" method="POST" action="/m/tarik/deposit">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label>Total Deposit</label>
                                <input type="text" class="form-control" readonly="" value="Rp. {{$data->total_deposit}}">
                            </div>
                        </div>
                    </div>
                    @if($metode == 0)
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label>Nama Bank</label>
                                    <input type="text" class="form-control" disabled="" value="{{$getUserBank->bank_name}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label>No. Rekening</label>
                                    <input type="text" class="form-control" disabled="" value="{{$getUserBank->account_no}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label>Nama Rekening</label>
                                    <input type="text" class="form-control" disabled="" value="{{$getUserBank->account_name}}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_bank" value="{{$getUserBank->id}}">
                        @endif
                        @if($metode == 1)
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alamat Tron</label>
                                    <input type="text" class="form-control" disabled="" value="{{$getUserBank->tron}}">
                                </div>
                            </div>
                        </div>
                    @endif
                    <input type="hidden" name="total_deposit" value="{{$data->total_deposit}}" >
                    <input type="hidden" name="is_tron" value="{{$metode}}">
                </form>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Order</button>
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