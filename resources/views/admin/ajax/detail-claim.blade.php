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
                    <input type="text" class="form-control" readonly="" value="{{$getData->username}}">
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
        <?php
                $status = 'Proses Transfer';
                $label = 'info';
                if($getData->status == 1){
                    $status = 'Tuntas';
                    $label = 'success';
                }
                if($getData->status == 2){
                    $status = 'Reject';
                    $label = 'danger';
                }
            ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Status</label>
                    <input type="text" class="form-control" readonly="" value="{{$status}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Alasan Reject</label>
                    <textarea class="form-control" id="reason" rows="2" name="reason"
                        readonly="">{{$getData->reason}}</textarea>
                </div>
            </div>
            <input type="hidden" name="cekId" value="{{$getData->id}}">
        </div>
        @else
        Tidak ada data
        @endif
    </div>

    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</form>