<form class="login100-form validate-form" method="post" action="/adm/confirm/transaction">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->name}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Type</label>
                        <input type="text" readonly=""  class="form-control" value="{{$getData->hp}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Kode Transaksi</label>
                        <input type="text" readonly=""  class="form-control" value="{{$getData->transaction_code}}">
                    </div>
                </div>
            </div>
            <?php
                $price = $getData->price + $getData->unique_digit;
                $status = 'prose transfer';
                if($getData->status == 1){
                    $status = 'konfirmasi';
                }
            ?>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Total Pin</label>
                        <input type="text" class="form-control" readonly=""  value="{{number_format($getData->total_pin, 0, ',', ',')}}">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Total harga</label>
                        <input type="text" readonly=""  class="form-control" value="Rp. {{number_format($price, 0, ',', ',')}}">
                    </div>
                </div>
                <input type="hidden" name="cekId" value="{{$getData->id}}" >
                <input type="hidden" name="cekMemberId" value="{{$getData->user_id}}" >
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
            <button type="submit" class="btn btn-info btn-link">Confirm</button>
        </div>
    </div>
</form>   