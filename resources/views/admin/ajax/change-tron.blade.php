<form class="login100-form validate-form" method="post" action="/adm/change/tron/member">
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
                        <input type="text" class="form-control" readonly="" value="{{$getData->user_code}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Handphone</label>
                        <input type="text" readonly=""  class="form-control" value="{{$getData->hp}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Tron Lama</label>
                        <input type="text" readonly=""  class="form-control" value="{{$getData->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Tron Baru</label>
                        <input type="text" class="form-control" name="tron" required="" autocomplete="off">
                    </div>
                </div>
                <input type="hidden" name="cekId" value="{{$getData->id}}" >
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