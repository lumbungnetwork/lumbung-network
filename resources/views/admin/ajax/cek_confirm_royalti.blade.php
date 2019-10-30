<form class="login100-form validate-form" method="post" action="/adm/verification-royalti">
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
                    <input type="text" class="form-control" disabled="" value="{{$getData->user_code}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="text" class="form-control" disabled="" value="{{date('d F Y', strtotime($getData->sale_date))}}">
                </div>
            </div>
        </div>
        <?php
            $harga_dasar = ($getData->sale_price * 10) / 11;
            $royalti = 8/100 * $harga_dasar;
        ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Total Royalti</label>
                        <input type="text" class="form-control" readonly="" value="Rp. {{number_format($royalti, 0, ',', ',')}}">
                    </div>
                </div>
            </div>
        @if($getData->royalti_metode == 1)
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->royalti_bank_name}}">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>No. Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->royalti_account_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->royalti_account_name}}">
                    </div>
                </div>
            </div>
        @endif
        @if($getData->royalti_metode == 2)
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Nama Rekening</label>
                        <input type="text" class="form-control" readonly="" value="{{$getData->royalti_tron}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                        <label>Hash #</label>
                        <textarea class="form-control" id="tron_transfer" rows="2">{{$getData->royalti_tron_transfer}}</textarea>
                    </div>
                </div>
            </div>
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