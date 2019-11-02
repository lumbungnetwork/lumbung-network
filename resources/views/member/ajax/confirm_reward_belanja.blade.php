<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/request/belanja-reward">
            {{ csrf_field() }}
            <?php
                $kelipatan = floor(($data->month_sale_price/10000)/10) * 10;
            ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Reward</label>
                        <input type="text" class="form-control" readonly="" value="{{$kelipatan}} LMB">
                    </div>
                </div>
            </div>
            <input type="hidden" name="reward"  value="{{$kelipatan}}">
            <input type="hidden" name="month"  value="{{$data->month}}">
            <input type="hidden" name="year"  value="{{$data->year}}">
        </form>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Submit</button>
    </div>
</div>