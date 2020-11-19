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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Data Produk</h5>
                    </div>
                    <div class="card-body">
                        <form class="login100-form validate-form" method="post" action="/adm/add/purchase">
                            {{ csrf_field() }}


                            <div class="row">
                                <label class="col-md-2 col-form-label">Nama</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Ukuran Kemasan</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="ukuran" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <!--                            <div class="row">
                                <label class="col-md-2 col-form-label">Harga Stockist</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="stockist_price" required="true" autocomplete="off">
                                    </div>
                                </div>
                            </div>-->
                            <div class="row">
                                <label class="col-md-2 col-form-label">Harga Jual</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="member_price" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Kode Produk</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="code" required="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Area</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="provinsi" id="provinsi"
                                            onChange="getSearchKota(this.value);">
                                            <option value="0">- Pilih Provinsi -</option>
                                            @if($provinsi != null)
                                            @foreach($provinsi as $row)
                                            <option value="{{$row->id_prov}}">{{$row->nama}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">&nbsp;</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="kota" id="kota">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">Gambar (URL)</label>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="image" required="true"
                                            autocomplete="off">
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

        function getSearchKota(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search-new/kota" + "?provinsi=" + val,
                success: function(url){
                        $( "#kota" ).empty();
                        $("#kota").html(url);
                }
            });
        }


</script>
@stop
