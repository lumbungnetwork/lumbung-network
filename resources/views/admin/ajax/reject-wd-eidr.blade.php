<form class="login100-form validate-form" method="post" action="/adm/reject/wd">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>User ID</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->user_code}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" readonly=""  class="form-control" value="{{$getData->hp}}">
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
                        <input type="text" readonly=""  class="form-control" value="{{number_format($jmlWD, 0, ',', '.')}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Admin Fee</label>
                        <input type="text" readonly=""  class="form-control" value="{{number_format($getData->admin_fee, 0, ',', '.')}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jml. Transfer</label>
                        <input type="text" readonly=""  class="form-control" value="{{number_format($getData->wd_total, 0, ',', '.')}}">
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