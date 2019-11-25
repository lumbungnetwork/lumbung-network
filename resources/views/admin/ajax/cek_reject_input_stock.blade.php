<form class="login100-form validate-form" method="post" action="/adm/reject-input-stock">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$master_item_id}}">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <?php
        $royalti = 4/100 * $getDataMaster->price;
    ?>
    <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" disabled="" value="{{$getDataMaster->user_code}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Royalti Belanja</label>
                    <input type="text" class="form-control" disabled="" value="Rp. {{number_format($royalti, 0, ',', ',')}}">
                </div>
            </div>
        </div>
        @if($getDataMaster->buy_metode == 2)
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataMaster->bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataMaster->account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataMaster->account_name}}">
                    </div>
                </div>
            </div>
        @endif
        @if($getDataMaster->buy_metode == 3)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getDataMaster->tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" readonly="" rows="2">{{$getDataMaster->tron_transfer}}</textarea>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Reason</label>
                    <textarea class="form-control" id="reason" name="reason" rows="1"></textarea>
                </div>
            </div>
        </div>
        <hr style="margin: 5px 0 2px;">
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