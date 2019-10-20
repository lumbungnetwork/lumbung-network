<form class="login100-form validate-form" method="post" action="/adm/bonus-reward">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        @if($getData != null)
        {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Peringkat</label>
                            <input type="text" class="form-control" name="name"  value="{{$getData->name}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Reward Detail</label>
                            <input type="text" class="form-control" name="reward_detail"  value="{{$getData->reward_detail}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="cekId" value="{{$getData->id}}" >
                </div>
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