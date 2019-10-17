@extends('layout.member.main')
@section('content')
@include('layout.member.topbar')
@include('layout.member.sidebar')
<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Belanja</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 col-xs-12">
                    <div class="card">
                        <div class="card-block">
                            <a href="/m/shoping/{{$stokist_id}}" class="btn btn-primary">Back</a>
                            
                        </div>
                        <img class="card-img-top img-fluid" src="{{$getData->image}}" alt="{{$getData->name}}">
                        <div class="card-block">
                            <h4 class="card-title">{{$getData->name}}</h4>
                            <h5 class="card-text">Harga Rp. {{number_format($getData->member_price, 0, ',', '.')}}</h5>
                            <p class="card-text">{{$getData->name}} {{$getData->ukuran}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="card">
                        <h3 class="card-header"></h3>
                        <div class="center">
                            Jumlah Pesan
                              <div class="input-group">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="total_buy">
                                          <span class="zmdi zmdi-minus"></span>
                                      </button>
                                  </span>
                                  <input type="text" name="total_buy" class="form-control input-number" value="1" min="1" max="50" id="total_buy">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="total_buy">
                                          <span class="zmdi zmdi-plus"></span>
                                      </button>
                                  </span>
                              </div>
                            <p></p>
                        </div>
<!--                        <div class="card-block">
                                <div class="radio radio-primary">
                            <input type="radio" name="radio" id="radio1" value="1">
                            <label for="radio1">
                                Cash/  Tunai
                            </label>
                        </div>
                        </div>-->
                        
                        
                        <div class="card-block">
                            <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Buy</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" id="confirmDetail">
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop

@section('styles')
<style>
    .center{
        width: 115px;
          margin: 5px auto;

        }
</style>
@stop
@section('javascript')

<script>
       function inputSubmit(){
           var total_buy = $("#total_buy").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/shoping?id_barang="+{{$getData->id}}+"&total_buy="+total_buy+"&stokist_id="+{{$stokist_id}} ,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }
        
        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
            $('#tutupModal').remove();
            $('#submit').remove();
        }

$('.btn-number').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
    
});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>
@stop
