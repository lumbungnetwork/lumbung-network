@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')
<div class="main-panel">
    
    <?php //MENU HEADER  ?>
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <p class="navbar-brand">{{$headerTitle}}</p>
            </div>
        </div>
    </nav>
    
    <?php //MENU CONTENT  ?>
    <div class="content">
        <div class="row">
            <div class="col-lg-9">
                <div class="card card-stats">
                    <div class="card-header">
                        <h6 class="card-title">Info Tujuan Transfer</h6>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-4 pr-1">
                              <div class="form-group">
                                <label>Bank</label>
                                <input type="text" class="form-control" disabled=""value="BCA">
                              </div>
                            </div>
                            <div class="col-md-3 px-1">
                              <div class="form-group">
                                <label>No. Rekening</label>
                                <input type="text" class="form-control" disabled=""value="10302018">
                              </div>
                            </div>
                            <div class="col-md-5 pl-1">
                              <div class="form-group">
                                <label>Nama Rekening</label>
                                <input type="text" class="form-control" disabled=""value="Andya Berna">
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Transfer</h5>
                    </div>
                    <div class="card-body">
                         <form class="login100-form validate-form" method="post" action="/adm/transfer/system-deposit">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nominal Transfer</label>
                                        <input type="text" class="form-control allownumericwithoutdecimal" name="total_deposit" required="true" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama Bank</label>
                                        <select class="form-control" name="bank_name" id="bank_name">
                                            <option value="none">- Pilih Bank -</option>
                                            <option value="BCA">BCA</option>
                                            <option value="CIMB Niaga">CIMB</option>
                                            <option value="Bank Permata">Bank Permata</option>
                                            <option value="Bank Mandiri">Bank Mandiri</option>
                                            <option value="BRI">BRI</option>
                                            <option value="BNI">BNI</option>
                                            <option value="BTN">BTN</option>
                                            <option value="BII">BII</option>
                                            <option value="Bank Panin">Bank Panin</option>
                                            <option value="Bank NISP">Bank NISP</option>
                                            <option value="Citibank">Citibank</option>
                                            <option value="Bank Danamon">Bank Danamon</option>
                                            <option value="Bank Mega">Bank Mega</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Rekening</label>
                                        <input type="text" class="form-control" name="account_no" required="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Rekening</label>
                                        <input type="text" class="form-control" name="account_name" required="true" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

</script>
@stop
