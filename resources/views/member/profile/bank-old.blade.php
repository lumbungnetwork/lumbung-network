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
                        <h4 class="page-title">Bank</h4>
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
                            <div class="col-xl-5 col-xs-12">
                                    <fieldset class="form-group">
                                        <label>Nama Rekening (Sesuai dengan nama di profil)</label>
                                        <input type="text" class="form-control" disabled="" autocomplete="off" value="{{$dataUser->full_name}}">
                                    </fieldset>
                            </div>
                            <div class="col-xl-3 col-xs-12">
                                    <fieldset class="form-group">
                                        <label for="account_no">Nomor Rekening</label>
                                        <input type="text" class="form-control allownumericwithoutdecimal" id="account_no" name="account_no" autocomplete="off">
                                    </fieldset>
                            </div>
                            <div class="col-xl-4 col-xs-12">
                                <fieldset class="form-group">
                                    <label for="bank_name">Nama Bank</label>
                                    <select class="form-control" name="bank_name" id="bank_name">
                                        <option value="none">- Pilih Bank -</option>
                                        <option value="BCA">BCA</option>
                                        <option value="CIMB">CIMB</option>
                                        <option value="Bank Permata">Bank Permata</option>
                                        <option value="Bank Mandiri">Bank Mandiri</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BTN">BTN</option>
                                        <option value="BTPN Jenius">BTPN Jenius</option>
                                    </select>
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
                <div class="col-sm-12 card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>No. Rekening</th>
                                    <th>Nama Bank</th>
                                    <th>Status</th>
                                    <th>###</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($getData != null)
                                    <?php $no = 0; ?>
                                    @foreach($getData as $row)
                                        <?php
                                            $no++;
                                            $status = 'Aktif';
                                            $color = 'success';
                                            if($row->is_active == 0){
                                                $status = 'Tidak Aktif';
                                                $color = 'danger';
                                            }
                                        ?>
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$row->account_name}}</td>
                                            <td>{{$row->account_no}}</td>
                                            <td>{{$row->bank_name}}</td>
                                            <td>
                                                <label class="label label-{{$color}}">{{$status}}</label>
                                            </td>
                                            <td>
                                                @if($row->is_active == 0)
                                                    <a rel="tooltip"  data-toggle="modal" data-target="#activateBank" class="text-primary" href="{{ URL::to('/') }}/m/activate/bank/{{$row->id}}">aktifkan</a>
                                                @endif
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
@include('layout.member.footer')
@stop

@section('javascript')
<script>
       function inputSubmit(){
           var account_no = $("#account_no").val();
           var bank_name = $("#bank_name").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/add-bank?account_no="+account_no+"&bank_name="+bank_name ,
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