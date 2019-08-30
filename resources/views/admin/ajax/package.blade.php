<form class="login100-form validate-form" method="post" action="/adm/package">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        @if($getData != null)

            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" class="form-control" name="name"  value="{{$getData->name}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Package Description</label>
                        <input type="text" class="form-control" name="short_desc"  value="{{$getData->short_desc}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Pin</label>
                        <input type="number" class="form-control" name="pin" value="{{$getData->pin}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Stock Poin WD</label>
                        <input type="number" class="form-control" name="stock_wd" value="{{number_format($getData->stock_wd, 0, ',', '')}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Discount</label>
                        <input type="number" class="form-control" name="discount" value="{{$getData->discount}}">
                    </div>
                </div>
            </div>
            <input type="hidden" name="cekId" value="{{$getData->id}}" >
            
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