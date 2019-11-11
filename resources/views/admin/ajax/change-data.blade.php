<form class="login100-form validate-form" method="post" action="/adm/change/data/member">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" value="{{$getData->email}}" required="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>UserID</label>
                        <input type="text" class="form-control" name="user_code" value="{{$getData->user_code}}" required="">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Handphone</label>
                        <input type="text" name="hp"  class="form-control" value="{{$getData->hp}}" required="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nama Lengkap (sesuai dengan Rekening Bank)</label>
                        <input type="text" class="form-control" name="full_name" value="{{$getData->full_name}}" required="">
                    </div>
                </div>
            </div>
            <input type="hidden" name="cekId" value="{{$getData->id}}" >
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