@if($lanjut == true)
<div class="row">
    <div class="col-12">
        <form id="form-add" method="post" action="/m/confirm/package">
            @csrf
            <div class="form-group">
                <input type="hidden" name="id_paket" value="{{$data->id_paket}}">
                <dd>Anda yakin untuk
                    mengaktivasi akun Member ini? <br><br>1 PIN akan dipakai mengaktivasi</dd>
            </div>

        </form>

    </div>

    <div class="col-12 mt-3">
        <button type="button" class="btn btn-secondary waves-effect" onclick="swal.close()">Tutup</button>
        <a class="btn btn-success ml-3" onclick="confirmSubmit()">Aktivasi</a>
    </div>

</div>
@endif

@if($lanjut == false)
<div class=" row">
    <div class="col-12">
        <div class="form-group">
            <dd class="text-danger">Anda tidak memiliki PIN
                Aktivasi untuk mengaktivasi member ini. <br><br>Silakan beli PIN terlebih dahulu.</dd>
        </div>
    </div>

    <div class="col-12 mt-3">
        <button type="button" class="btn btn-dark waves-effect" onclick="swal.close()">Tutup</button>
        <a class="btn btn-success ml-3" href="{{ URL::to('/') }}/m/add/pin">Beli PIN</a>
    </div>
</div>
@endif
