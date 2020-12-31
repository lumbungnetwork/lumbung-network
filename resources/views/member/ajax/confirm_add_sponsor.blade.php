@if($check->can == true)

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Data</h5>
    </div>
    <div class="modal-body" style="overflow-y: auto;">
        <form id="form-add" method="POST" action="/m/add/sponsor">
            {{ csrf_field() }}
            <div class="row">
                @if($dataRequest->affiliate == 1)
                <div class="col-12">
                    <div class="form-group">
                        <label>Username Akun KBB</label>
                        <input type="text" class="form-control" readonly="" name="user_code"
                            value="{{$dataRequest->username}}">
                    </div>
                </div>
                <input type="hidden" name="email" value="{{$dataRequest->email}}">
                <input type="hidden" name="hp" value="{{$dataRequest->hp}}">
                <input type="hidden" name="password" value="{{$dataRequest->password}}">
                @else
                <div class="col-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" readonly="" name="email"
                            value="{{$dataRequest->email}}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" readonly="" name="hp" value="{{$dataRequest->hp}}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" readonly="" name="user_code"
                            value="{{$dataRequest->username}}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" class="form-control" readonly="" name="password"
                            value="{{$dataRequest->password}}">
                    </div>
                </div>
                @endif
                @if ($dataRequest->affiliate > 0)
                @php
                if ($dataRequest->affiliate == 1) {
                $affiliate = "KBB";
                } elseif ($dataRequest->affiliate == 2) {
                $affiliate = "KBB-Pasif";
                }
                @endphp
                <div class="col-12">
                    <div class="form-group">
                        <label>Afiliasi</label>
                        <input type="text" class="form-control" readonly value="{{$affiliate}}">
                    </div>
                </div>
                @endif
            </div>
            <input type="hidden" name="affiliate" value="{{$dataRequest->affiliate}}">
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit"
            onclick="confirmSubmit()">OK</button>
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
