<form class="login100-form validate-form" method="post" action="/adm/confirm/isi-deposit">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>UserID</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->user_code}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Handphone</label>
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
            @if($getData->is_tron == 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->account_name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bank</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->bank_name}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor Rekening</label>
                        <input type="text" readonly=""  class="form-control" value="{{$getData->account_no}}">
                    </div>
                </div>
            </div>
            @endif
            @if($getData->is_tron == 1)
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->tron_name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alamat Tron</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Transaksi Hash</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->tron_transfer}}">
                    </div>
                </div>
            </div>
            @endif
            <?php
                $price = $getData->price + $getData->unique_digit;
                $status = 'proses transfer';
                if($getData->status == 1){
                    $status = 'konfirmasi';
                }
            ?>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Total Deposit</label>
                        <input type="text" readonly=""  class="form-control" value="Rp. {{number_format($getData->price, 0, ',', ',')}}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Unik Digit</label>
                        <input type="text" readonly=""  class="form-control" value="Rp. {{number_format($getData->unique_digit, 0, ',', ',')}}">
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