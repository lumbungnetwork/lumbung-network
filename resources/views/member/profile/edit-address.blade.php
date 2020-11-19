@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                        <i class="fa fa-arrow-left"></i> Beranda
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h6 class="mb-3">Profil Saya</h6>
                    @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-xl-5 col-xs-12">
                            <fieldset class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <select class="form-control" name="provinsi" id="provinsi"
                                    onChange="getSearchKota(this.value);">
                                    <option value="0">- Pilih Provinsi -</option>
                                    @if($provinsi != null)
                                    @foreach($provinsi as $row)
                                    <option value="{{$row->id_prov}}">{{$row->nama}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-7 col-xs-12">
                            <fieldset class="form-group">
                                <label for="kota">Kota/Kabupaten</label>
                                <select class="form-control" name="kota" id="kota"
                                    onChange="getSearchKecamatan(this.value);">
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-xs-12">
                            <fieldset class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <select class="form-control" name="kecamatan" id="kecamatan"
                                    onChange="getSearchKelurahan(this.value);">
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-xs-12">
                            <fieldset class="form-group">
                                <label for="kelurahan">Kelurahan</label>
                                <select class="form-control" name="kelurahan" id="kelurahan">
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-12 col-xs-12">
                            <fieldset class="form-group">
                                <label for="alamat">Alamat Lengkap</label>
                                <textarea class="form-control" id="alamat" rows="2" name="alamat"
                                    autocomplete="off"></textarea>
                            </fieldset>
                        </div>
                        <div class="col-xl-6">
                            <button type="submit" class="btn btn-primary" id="submitBtn" data-toggle="modal"
                                data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                        </div>
                    </div>
                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document" id="confirmDetail">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>
</div>

@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script>
    function inputSubmit(){
           var alamat = $("#alamat").val();
           var kota = $("#kota").val();
           var kecamatan = $("#kecamatan").val();
           var kelurahan = $("#kelurahan").val();
           var provinsi = $("#provinsi").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/edit-address?&kota="+kota+"&provinsi="+provinsi+"&kecamatan="+kecamatan+"&kelurahan="+kelurahan+"&alamat="+alamat,
                success: function(url){
                    $("#confirmDetail" ).empty();
                    $("#confirmDetail").html(url);
                }
            });
        }

        function confirmSubmit(){
            var dataInput = $("#form-add").serializeArray();
            $('#form-add').submit();
        }

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

        function getSearchKecamatan(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search-new/kecamatan" + "?kota=" + val,
                success: function(url){
                        $( "#kecamatan" ).empty();
                        $("#kecamatan").html(url);
                }
            });
        }

        function getSearchKelurahan(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search-new/kelurahan" + "?kecamatan=" + val,
                success: function(url){
                        $( "#kelurahan" ).empty();
                        $("#kelurahan").html(url);
                }
            });
        }

</script>
@stop
