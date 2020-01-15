<form class="login100-form validate-form" method="post" action="/adm/edit-stockist">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        @if($getData != null)
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" class="form-control" disabled="" value="{{$getData->user_code}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" class="form-control" name="hp" value="{{$getData->hp}}">
                    </div>
                </div>
            </div>
                <input type="hidden" name="id" value="{{$getData->id}}">
                <input type="hidden" name="id_user" value="{{$getData->id_user}}">
                <input type="hidden" name="user_code" value="{{$getData->user_code}}">
        @else 
            No Data
        @endif
    </div>
    
    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        <div class="divider"></div>
        <div class="right-side">
            <button type="submit" class="btn btn-info btn-link">Submit</button>
        </div>
    </div>
</form>   