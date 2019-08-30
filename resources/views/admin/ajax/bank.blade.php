<form class="login100-form validate-form" method="post" action="/adm/bank">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        @if($getData != null)
                {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" name="bank_name"  value="{{$getData->bank_name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" name="account_no" value="{{$getData->account_no}}">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" name="account_name" value="{{$getData->account_name}}">
                    </div>
                </div>
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