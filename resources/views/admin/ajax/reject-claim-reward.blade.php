<form class="login100-form validate-form" method="post" action="/adm/reject/claim-reward">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>User ID</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->user_code}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Reward Detail</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->reward_detail}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alasan Reject</label>
                        <textarea class="form-control" id="reason" rows="2" name="reason" required=""></textarea>
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
        <div class="right-side">
            <button type="submit" class="btn btn-info btn-link">Reject</button>
        </div>
    </div>
</form>   