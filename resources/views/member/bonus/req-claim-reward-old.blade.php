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
                            <h4 class="page-title">Reward Peringkat</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                @if ( Session::has('message') )
                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{  Session::get('message')    }} 
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="card-box tilebox-two">
                            @if($dataMy->image != null)
                                <img src="{{$dataMy->image}}" class="img-responsive pull-xs-right text-muted" alt="user" style="height: 90px;">
                            @endif
                            <h6 class="text-muted text-uppercase m-b-15 m-t-10">Peringkat Anda</h6>
                            <h2 class="m-b-10">{{$dataMy->name}}</h2>
                        </div>
                    </div>
                    @if($getMyPeringkat != null)
                        @if($canClaim == true )
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                                <div class="card-box tilebox-two">
                                    @if($dataMy->image != null)
                                        <img src="/image/koin_lmb.png" class="img-responsive pull-xs-right text-muted" alt="user" style="height: 90px;">
                                    @endif
                                    <h6 class="text-muted text-uppercase m-b-15 m-t-10">Reward</h6>
                                    <h2 class="m-b-10">{{$getMyPeringkat->reward_detail}}</h2>
                                    <p class="m-b-10">
                                        <input type="hidden" id="reward_id" value="{{$getMyPeringkat->id}}">
                                        <button type="submit" class="btn btn-secondary btn-sm"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">claimed</button>
                                    </p>
                                </div>
                            </div>
                        
                            <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document" id="confirmDetail">
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="card-box tilebox-two">
                                <i class="icon-trophy pull-xs-right text-muted"></i>
                                <h6 class="text-muted text-uppercase m-b-15 m-t-10">Claimed</h6>
                                <h2 class="m-b-10"><a href="{{ URL::to('/') }}/m/history/reward" class="btn btn-primary btn-sm">view</a></h2>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="false">
                    <div class="modal-dialog" role="document" id="confirmDetail">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-block">
                                <h5 class="card-text">Tim Anda</h5>
                                <div class="row">
                                    <table id="datatable" class="table table-striped table-bordered">
                                        <tbody>
                                            @if($getMyTeam != null)
                                                @foreach($getMyTeam as $row)
                                                    <?php
                                                        $name = 'Member Baru';
                                                        $image = '';
                                                        if($row->name != null){
                                                            $name = $row->name;
                                                            $image = '<img src="'.$row->image.'" class="img-responsive" alt="user" style="height: 50px;">';
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><b>{{$row->user_code}}</b></td>
                                                        <td>Total Sponsor: {{$row->total_sponsor}}</td>
                                                        <td>{{$name}}</td>
                                                        <td><?php echo $image; ?></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    
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

@section('javascript')
<script>
    @if($canClaim == true )
       function inputSubmit(){
           var reward_id = $("#reward_id").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/') }}/m/cek/confirm-claim-reward?reward_id="+reward_id,
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
        @endif
        
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

</script>
@stop