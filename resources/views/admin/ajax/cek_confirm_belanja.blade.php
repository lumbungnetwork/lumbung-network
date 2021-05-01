<form class="login100-form validate-form" method="post" action="/adm/confirm-belanja">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$getData->id}}">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" disabled="" value="{{$getData->username}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="text" class="form-control" disabled=""
                        value="{{date('d F Y', strtotime($getData->sale_date))}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Sale Price</label>
                    <input type="text" class="form-control" disabled=""
                        value="{{number_format($getData->sale_price, 0, ',', ',')}}">
                </div>
            </div>
        </div>
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