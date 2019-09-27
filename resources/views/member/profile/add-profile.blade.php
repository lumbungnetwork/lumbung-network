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
                            <div class="col-xl-8 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="input_nama_lengkap">Nama Lengkap (sesuai dengan Nama pada Rekening Bank)</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" autocomplete="off">
                                    </fieldset>
                            </div>
                            <div class="col-xl-4 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" name="gender" id="gender">
                                                <option value="1">Pria</option>
                                                <option value="2">Wanita</option>
                                        </select>
                                    </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="alamat">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamat" rows="2" name="alamat" autocomplete="off"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="kota">Kota/Kabupaten</label>
                                        <input type="text" class="form-control" id="kota" name="kota" autocomplete="off">
                                    </fieldset>
                            </div>
                            <div class="col-xl-3 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="provinsi">Provinsi</label>
                                        <select class="form-control" name="provinsi" id="provinsi">
                                                <option value="Aceh">Aceh</option>
                                                <option value="Sumatera Utara">Sumatera Utara</option>
                                                <option value="Sumatera Barat">Sumatera Barat</option>
                                                <option value="Riau">Riau</option>
                                                <option value="Jambi">Jambi</option>
                                                <option value="Sumatera Selatan">Sumatera Selatan</option>
                                                <option value="Bengkulu">Bengkulu</option>
                                                <option value="Lampung">Lampung</option>
                                                <option value="Kep. Bangka Belitung">Kep. Bangka Belitung</option>
                                                <option value="Kepulauan Riau">Kepulauan Riau</option>
                                                <option value="DKI Jakarta">DKI Jakarta</option>
                                                <option value="Jawa Barat">Jawa Barat</option>
                                                <option value="Banten">Banten</option>
                                                <option value="Jawa Tengah">Jawa Tengah</option>
                                                <option value="Yogyakarta">Yogyakarta</option>
                                                <option value="Jawa Timur">Jawa Timur</option>
                                                <option value="Kalimantan Barat">Kalimantan Barat</option>
                                                <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                                <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                                <option value="Kalimantan Timur">Kalimantan Timur</option>
                                                <option value="Kalimantan Utara">Kalimantan Utara</option>
                                                <option value="Bali">Bali</option>
                                                <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                                                <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                                <option value="Sulawesi Utara">Sulawesi Utara</option>
                                                <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                                <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                                <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                                <option value="Sulawesi Barat">Sulawesi Barat</option>
                                                <option value="Gorontalo">Gorontalo</option>
                                                <option value="Maluku">Maluku</option>
                                                <option value="Maluku Utara">Maluku Utara</option>
                                                <option value="Papua">Papua</option>
                                                <option value="Papua Barat">Papua Barat</option>
                                        </select>
                                    </fieldset>
                            </div>
                            <div class="col-xl-2 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="kode_pos">Kode Pos</label>
                                        <input type="text" class="form-control allownumericwithoutdecimal" id="kode_pos" name="kode_pos" autocomplete="off">
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
@section('styles')
<!--<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />-->
@stop
@section('javascript')
<script>
       function inputSubmit(){
           var full_name = $("#full_name").val();
           var gender = $("#gender").val();
           var alamat = $("#alamat").val();
           var kota = $("#kota").val();
           var provinsi = $("#provinsi").val();
           var kode_pos = $("#kode_pos").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-profile?full_name="+full_name+"&gender="+gender+"&kota="+kota+"&provinsi="+provinsi+"&kode_pos="+kode_pos+"&alamat="+alamat ,
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

</script>
@stop