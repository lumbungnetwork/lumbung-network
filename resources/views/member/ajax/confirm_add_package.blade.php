@if($setuju == 1)
<form class="login100-form validate-form" method="post" action="/m/add/package">
    {{ csrf_field() }}
    <div class="modal-header justify-content-center">
        <h4 class="title title-up">Order</h4>
    </div>
    <div class="modal-body">
        <?php 
            $price = $getData->pin * $pinSetting->price;
            ?>
        <div class="col-md-12 pricing-table">
            <div class="pricing-item pricing-featured">
                <div class="selected">Membership</div>
                <div class="pricing-value">
                    <img src="/image/logo_lumbung2.png" alt="user" class="img-circle" style="width: 180px;">
                </div>
                <div class="pricing-title">
                    <h5><b>{{$getData->name}}</b></h5>
                    <h5><b>Rp. {{number_format($price, 0, ',', ',')}}</b></h5>
                    <h5><b>({{$getData->pin}} PIN)</b></h5>
                </div>
                <ul class="pricing-features">
                    <li><span class="keywords">Iuran Keanggotaan Tahunan</span></li>
                    <li>Bonus LMB dari Akumulasi Belanja</li>
                    <li>1 Hak Usaha</li>
                    <li>Bonus Insentif dan Rewards</li>
                </ul>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_paket" value="{{$getData->id}}">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary waves-effect waves-light">Order</button>
    </div>
</form>
@endif
@if($setuju == 0)
    <div class="modal-header justify-content-center">
        <h5 class="modal-title" id="modalLabel">Order</h5>
    </div>
    <div class="modal-body"  style="overflow-y: auto;max-height: 330px;">
        <h4 class="text-danger" style="text-align: center;"> Anda belum menyetujui menyetujui Aturan dan Ketentuan Keanggotaan Lumbung Network </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
@endif