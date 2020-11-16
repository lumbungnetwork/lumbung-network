@if($is_2fa == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <div class="row" id="loading" style="display:none;">
            <div class="col-12">
                <div class="form-group text-center">
                    <h5 class="text-warning" style="display: block;text-align: center;">
                        <div class="spinner-border m-5" role="status">
                            <span class="sr-only"></span>
                        </div>

                    </h5>
                    <h4 class="text-warning">Sedang Memproses...</h4>
                </div>
            </div>
        </div>
        <form id="form-add" method="POST" action="/m/confirm/vppob-new">
            {{ csrf_field() }}
            <h4 class="text-success" style="text-align: center;"> Masukkan Kode Pin 2FA anda untuk mengkonfirmasi
                Pesanan ini: </h4>
            <div class="form-group">
                <label class="mt-3">Kode Pin 2FA</label>
                <input type="password" id="2fa" autocomplete="off" name="password" class="form-control">
                <div class="row">
                    <div class="col-12 my-3">
                        <div class="pretty p-icon p-toggle p-plain">
                            <input type="checkbox" id="show-password" />
                            <div class="state p-success-o p-on">
                                <i class="icon mdi mdi-eye"></i>
                                <label>Sembunyikan Kode Pin</label>
                            </div>
                            <div class="state p-off">
                                <i class="icon mdi mdi-eye-off"></i>
                                <label>Tampilkan Kode Pin</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <input type="hidden" name="ppob_id" value="{{$ppobId}}">
            <input type="hidden" name="vendor_id" value="{{$vendorId}}">

        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">Submit</button>
    </div>
</div>



@endif

@if($is_2fa == false)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> Anda belum Memiliki Kode Pin 2FA </h4>
        <h6>Silakan buat Kode PIN 2FA anda dengan menekan tombol 'Buat Pin 2FA'</h6>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        <a href="{{ URL::to('/') }}/m/edit/2fa" type="button" class="btn btn-primary">Buat Pin 2FA</a>
    </div>
</div>
@endif
