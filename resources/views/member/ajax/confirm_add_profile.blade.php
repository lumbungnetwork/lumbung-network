@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="POST" action="/m/add/profile">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" readonly="" name="full_name" value="{{$dataRequest->full_name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>No. KTP</label>
                        <input type="text" class="form-control" readonly="" name="ktp" value="{{$dataRequest->ktp}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gender</label>
                        <?php
                            $gender = 'Pria';
                            if($dataRequest->gender == 2){
                                $gender = 'Wanita';
                            }
                        ?>
                        <input type="text" class="form-control" readonly="" value="{{$gender}}">
                        <input type="hidden" class="form-control" name="gender" value="{{$dataRequest->gender}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" readonly="" name="alamat" value="{{$dataRequest->alamat}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" class="form-control" readonly="" name="kota"  value="{{$dataRequest->kota}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" class="form-control" readonly="" name="provinsi"  value="{{$dataRequest->provinsi}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" class="form-control" readonly="" name="kode_pos"  value="{{$dataRequest->kode_pos}}">
                    </div>
                </div>
            </div>
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