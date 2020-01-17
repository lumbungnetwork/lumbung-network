@if($getData != null)
<form class="login100-form validate-form" method="post" action="/adm/edit-stock">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" disabled="" value="{{$getData->name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Kode Produk</label>
                        <input type="text" class="form-control" disabled="" value="{{$getData->code}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Stok Tersedia</label>
                        <input type="text" class="form-control" disabled="" value="{{$getData->total_sisa}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Metode</label>
                        <select class="form-control" name="metode">
                            <option value="1">Tambah</option>
                            <option value="2">Kurang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Jumlah Stock</label>
                        <input type="number" class="form-control" name="jml_stock"  min="1">
                    </div>
                </div>
            </div>
            <input type="hidden" name="purchase_id" value="{{$getData->purchase_id}}">
            <input type="hidden" name="stockist_id" value="{{$getDataUser->id}}">
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
@endif