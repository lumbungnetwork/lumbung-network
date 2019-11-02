<form class="login100-form validate-form" method="post" action="/adm/req-input-stock">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$master_item_id}}">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" disabled="" value="{{$getUser->user_code}}">
                </div>
            </div>
        </div>
        <hr style="margin: 2px 0;">
        @if($getData != null)
            @foreach($getData as $row)
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="text" class="form-control" disabled="" value="{{number_format($row->qty, 0, ',', ',')}}">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" disabled="" value="{{$row->ukuran}} {{$row->name}}">
                        </div>
                    </div>
                </div>
            <hr style="margin: 2px 0;">
            @endforeach
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