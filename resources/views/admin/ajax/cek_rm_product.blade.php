<form class="login100-form validate-form" method="post" action="/adm/rm/purchase">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        @if($getData != null)
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6 pr-1">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" disabled="" class="form-control"  value="{{$getData->name}}">
                </div>
            </div>
            <div class="col-md-6 pl-1">
                <div class="form-group">
                    <label>Ukuran</label>
                    <input type="text" disabled="" class="form-control" value="{{$getData->ukuran}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Kode</label>
                    <input type="text" class="form-control" disabled="" value="{{$getData->code}}">
                </div>
            </div>
        </div>
        
        <div class="row">
            <input type="hidden" name="id" value="{{$getData->id}}" >
        </div>
        @else 
            No Data
        @endif
    </div>
    
    <div class="modal-footer" style="margin-right: 10px;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Confirm</button>
    </div>
</form>   