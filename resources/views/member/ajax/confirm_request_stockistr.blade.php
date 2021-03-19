@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Apply Stockist</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/req/stockist">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" name="hash" value="0">
                        <input type="hidden" name="delegate" value="{{$data->delegate}}">
                        <dd class="text-success" style="text-align: center;"> Untuk mengajukan permohonan Stockist ini
                            anda perlu membakar
                            <strong>50
                                LMB</strong></dd>
                    </div>
                    <div class="mt-3 float-right card rounded-lg bg-light p-4">
                        <div id="showAddress"></div>
                        <h6 class="text-success availableLMB">0 LMB</h6>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <small class="mt-2 text-danger d-block" id="tronweb-warning">Use Tronlink or Dapp enabled
            browser</small>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success" id="tronwebPay" disabled>Ajukan</button>
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
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
    </div>
</div>
@endif