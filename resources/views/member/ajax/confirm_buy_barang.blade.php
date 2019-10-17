<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Detail Pembelian</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <form id="form-add" method="post" action="/m/stockist-shoping">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="id_barang" value="{{$getData->id}}">
                        <input type="hidden" name="qty" value="{{$qty}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->name}} {{$getData->ukuran}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" class="form-control" readonly="" value="Rp. {{number_format($getData->stockist_price, 0, ',', '.')}}">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="text" class="form-control" readonly="" value="{{number_format($qty, 0, ',', '.')}}">
                    </div>
                </div>
            </div>
        </form>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" id="tutupModal" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit" onclick="confirmSubmit()">Submit</button>
    </div>
</div>