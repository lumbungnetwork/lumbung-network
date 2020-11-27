@if($check->can == true)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/request/penjualan-reward">
            {{ csrf_field() }}
            <?php
                    $rewardFactor = 2;
                    if($dataUser->pin_activate == 2) {$rewardFactor = 1;}
                    if($dataUser->pin_activate == 3) {$rewardFactor = 0.5;}
                    if($dataUser->pin_activate >= 4) {$rewardFactor = 0.1;}
                    $kelipatan = floor(($data->month_sale_price/10000)/10) * $rewardFactor;
                    $reward = '--';
                    if($kelipatan > 0){
                        $reward = $kelipatan;
                        if($kelipatan > 100){
                            $kelipatan = 100;
                            $reward = $kelipatan;
                        }
                    }
                ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Reward</label>
                        <input type="text" class="form-control" readonly="" value="{{$reward}} LMB">
                    </div>
                </div>
            </div>
            <input type="hidden" name="reward" value="{{$reward}}">
            <input type="hidden" name="month" value="{{$data->month}}">
            <input type="hidden" name="year" value="{{$data->year}}">
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">Submit</button>
    </div>
</div>
@endif

@if($check->can == false)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> {{$check->pesan}} </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@endif
