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
                        <h4 class="page-title">Profile Baru</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        @if ( Session::has('message') )
                            <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{  Session::get('message')    }} 
                            </div>
                        @endif
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-5 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="provinsi">Provinsi</label>
                                        <select class="form-control" name="provinsi" id="provinsi" onChange="getSearchKota(this.value);">
                                                <option value="0">- Pilih Provinsi -</option>
                                                @if($provinsi != null) 
                                                    @foreach($provinsi as $row)
                                                        <option value="{{$row->propinsi}}">{{$row->nama}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </fieldset>
                            </div>
                            <div class="col-xl-7 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="kota">Kota/Kabupaten</label>
                                        <select class="form-control" name="kota" id="kota" onChange="getSearchKecamatan(this.value);">
                                        </select>
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="kecamatan">Kecamatan</label>
                                    <select class="form-control" name="kecamatan" id="kecamatan" onChange="getSearchKelurahan(this.value);">
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
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="alamat">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamat" rows="2" name="alamat" autocomplete="off"></textarea>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <button type="submit" class="btn btn-primary"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">Submit</button>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="confirmDetail">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop

@section('javascript')
<script>
       function inputSubmit(){
           var alamat = $("#alamat").val();
           var kota = $("#kota").val();
           var kecamatan = $("#kecamatan").val();
           var kelurahan = $("#kelurahan").val();
           var provinsi = $("#provinsi").val();
           var kode_pos = $("#kode_pos").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/edit-address?&kota="+kota+"&provinsi="+provinsi+"&kecamatan="+kecamatan+"&kelurahan="+kelurahan+"&kode_pos="+kode_pos+"&alamat="+alamat ,
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
                url: "{{ URL::to('/') }}/m/search/kota" + "?provinsi=" + val,
                success: function(url){
                        $( "#kota" ).empty();
                        $("#kota").html(url);
                }
            });
        }
        
        function getSearchKecamatan(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search/kecamatan" + "?kota=" + val,
                success: function(url){
                        $( "#kecamatan" ).empty();
                        $("#kecamatan").html(url);
                }
            });
        }
        
        function getSearchKelurahan(val) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/search/kelurahan" + "?kecamatan=" + val,
                success: function(url){
                        $( "#kelurahan" ).empty();
                        $("#kelurahan").html(url);
                }
            });
        }

</script>
@stop