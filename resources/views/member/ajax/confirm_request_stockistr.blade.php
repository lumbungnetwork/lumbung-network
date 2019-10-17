@if($check->can == true)
    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
            </div>
            <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
                <form id="form-add" method="POST" action="/m/req/stockist">
                    {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" name="success" value="1">
                            <h4 class="text-success" style="text-align: center;"> Syarat pengajuan Stockist anda telah terpenuhi. Silakan submit agar diaktifasi oleh admin</h4>
                        </div>
                    </div>
                </div>
                </form> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
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