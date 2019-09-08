@if($check->can == true)
    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
            </div>
            <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
                <form id="form-add" method="POST" action="/m/request/wd">
                    {{ csrf_field() }}
                    <?php
                        $saldo_wd = $data->req_wd - $data->admin_fee;
                    ?>
                    <div class="row">
                        <div class="col-md-7 col-xs-12">
                            <div class="form-group">
                                <label>Total Withdraw</label>
                                <input type="text" class="form-control" readonly="" value="Rp. {{number_format($saldo_wd, 0, ',', '.')}}">
                            </div>
                        </div>
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group">
                                <label>Biaya Admin (fee)</label>
                                <input type="text" class="form-control" readonly="" value="Rp. {{number_format($data->admin_fee, 0, ',', '.')}}">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="saldo_wd" value="{{$saldo_wd}}">
                    <input type="hidden" name="admin_fee" value="{{$data->admin_fee}}">
                    <input type="hidden" name="user_bank" value="{{$data->bank}}">
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