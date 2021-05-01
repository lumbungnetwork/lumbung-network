<form class="login100-form validate-form" method="post" action="/adm/change/2fa/member">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>UserID</label>
                    <input type="text" class="form-control" readonly="" value="{{$getData->username}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Handphone</label>
                    <input type="text" readonly="" class="form-control" value="{{$getData->hp}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kode Pin 2FA</label>
                    <input type="password" class="form-control" name="password" required="" autocomplete="off"
                        placeholder="minimal 6 karakter">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Ketik ulang Pin 2FA</label>
                    <input type="password" class="form-control" name="repassword" required="" autocomplete="off">
                </div>
            </div>
            <input type="hidden" name="cekId" value="{{$getData->id}}">
            <input type="hidden" name="username" value="{{$getData->username}}">
        </div>
        @else
        Tidak ada data
        @endif
    </div>

    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        <div class="divider"></div>
        @if($getData != null)
        <div class="right-side">
            <button type="submit" class="btn btn-info btn-link">Confirm</button>
        </div>
        @endif
    </div>
</form>