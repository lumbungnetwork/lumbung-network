<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
</div>
<div class="modal-body">
    @if($getData != null)
    <div class="row">
        <div class="col-md-12">
            <div class="form-group justify-content-center">
                <h5 class="modal-profile ml-auto mr-auto" style="text-align: center;">
                    <?php
                                $text = 'Proses Transfer';
                                $label = 'info';
                                if($getData->status == 1){
                                    $text = 'Tuntas';
                                    $label = 'success';
                                }
                                if($getData->status == 2){
                                    $text = 'Reject';
                                    $label = 'danger';
                                }
                            ?>
                    <p class="text-{{$label}}">{{$text}}</p>
                </h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label>User ID</label>
                <input type="text" class="form-control" readonly="" value="{{$getData->username}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" readonly="" class="form-control" value="{{$getData->hp}}">
            </div>
        </div>
    </div>
    <?php
                $jmlWD = $getData->wd_total + $getData->admin_fee;
            ?>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label>Jml. WD</label>
                <input type="text" readonly="" class="form-control" value="{{number_format($jmlWD, 0, ',', '.')}}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Admin Fee</label>
                <input type="text" readonly="" class="form-control"
                    value="{{number_format($getData->admin_fee, 0, ',', '.')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Jml. Transfer</label>
                <input type="text" readonly="" class="form-control"
                    value="{{number_format($getData->wd_total, 0, ',', '.')}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Alasan Reject</label>
                <textarea class="form-control" id="reason" rows="2" required="">{{$getData->reason}}</textarea>
            </div>
        </div>
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