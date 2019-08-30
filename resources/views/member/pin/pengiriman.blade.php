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
                        <h4 class="page-title">Kirim Paket</h4>
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
                        <div class="row">
                            <div class="col-xl-2">
                                <fieldset class="form-group">
                                    <label for="total_pin">Total Pin</label>
                                    <input type="number" class="form-control" id="total_pin" name="total_pin" autocomplete="off">
                                </fieldset>
                            </div>
                            <div class="col-xl-10">
                                    <fieldset class="form-group">
                                        <label for="alamat_kirim">Alamat Pengiriman</label>
                                        <textarea class="form-control" id="alamat_kirim" rows="2" name="alamat_kirim" autocomplete="off"></textarea>
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
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Total Pin</th>
                                    <th>Alamat Pengiriman</th>
                                    <th>Nama Kurir</th>
                                    <th>No. Resi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                    <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                        <?php
                                            $no++;
                                            $status = 'Selesai';
                                            $color = 'success';
                                            if($row->status == 0){
                                                $status = 'Proses Pengiriman';
                                                $color = 'info';
                                            }
                                            if($row->status == 2){
                                                $status = 'Batal';
                                                $color = 'danger';
                                            }
                                        ?>
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$row->total_pin}}</td>
                                            <td>{{$row->alamat_kirim}}</td>
                                            <td>{{$row->kurir_name}}</td>
                                            <td>{{$row->no_resi}}</td>
                                            <td>
                                                <label class="label label-{{$color}}">{{$status}}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="modal fade" id="activateBank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.member.footer')
@stop
@section('styles')
<link href="{{ asset('asset_member/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
<script>
       function inputSubmit(){
           var total_pin = $("#total_pin").val();
           var alamat_kirim = $("#alamat_kirim").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/kirim-paket?total_pin="+total_pin+"&alamat_kirim="+alamat_kirim ,
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

</script>
<script type="text/javascript">
    $("#activateBank").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-dialog").load(link.attr("href"));
    });
    
    function activateSubmit(){
            var dataInput = $("#form-insert").serializeArray();
            $('#form-insert').submit();
        }
</script>
@stop