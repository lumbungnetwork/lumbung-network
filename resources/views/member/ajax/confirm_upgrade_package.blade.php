@if($canInsert->can == true)
<form class="login100-form validate-form" method="post" action="/m/add/upgrade">
    {{ csrf_field() }}
    <div class="modal-header justify-content-center">
        <h4 class="title title-up">Konfirmasi</h4>
    </div>
    <div class="modal-body">
                <h4 style="text-align: center;">Apakah anda ingin meng-upgrade Package {{$dataMyPackage->name}} menjadi {{$dataPackage->name}}?</h4>
    </div>
    <input type="hidden" name="total_pin" value="{{$total_pin}}">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary waves-effect waves-light">Upgrade</button>
    </div>
</form>
@endif

@if($canInsert->can == false)
    <div class="modal-header justify-content-center">
        <h4 class="title title-up">Konfirmasi</h4>
    </div>
    <div class="modal-body">
        <h4 class="text-danger" style="text-align: center;">{{$canInsert->pesan}}</h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
    </div>
@endif