<form id="form-add" method="post" action="/m/reject/package">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <input type="hidden" name="id_paket" value="{{$data->id_paket}}">
                <dd class="text-warning">Apakah anda ingin Menolak Aktivasi Member ini?</dd>
            </div>
        </div>
    </div>
</form>

<div class="mt-3">
    <button type="button" class="btn btn-secondary" onclick="swal.close()">Tutup</button>
    <button type="button" class="btn btn-danger ml-3" id="submit" onclick="confirmSubmit()">Tolak Aktivasi</button>
</div>
