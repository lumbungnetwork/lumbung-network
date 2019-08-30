<form class="login100-form validate-form" method="post" action="/m/add/package">
    {{ csrf_field() }}
    <div class="modal-header justify-content-center">
        <h4 class="title title-up">Order</h4>
    </div>
    <div class="modal-body">
        <?php 
                    $price = $getData->pin * $pinSetting->price;
                    ?>
        <div class="card">
            <div class="card-block">
                <h4 class="card-title">Package {{$getData->name}}</h4>
                <p class="card-text">{{$getData->short_desc}}</p>
                <p class="card-text">Rp. {{number_format($price, 0, ',', ',')}}</p>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_paket" value="{{$getData->id}}">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary waves-effect waves-light">Order</button>
    </div>
</form>