@if($check->can == true)
    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
            </div>
            <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
                <form id="form-add" method="POST" action="/m/add/repeat-order">
                    {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Total Pin Resubscribe</label>
                            <input type="number" class="form-control" readonly="" name="total_pin" value="{{$data->total_pin}}">
                        </div>
                    </div>
                </div>
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