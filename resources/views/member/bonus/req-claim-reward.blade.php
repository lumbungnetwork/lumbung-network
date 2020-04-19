@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
        
    
        <!-- Page Content -->
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
                        <div class="col-xl-12 col-xs-12">
                            <div class="card-box tilebox-two">
                                <h6 class="text-muted text-uppercase m-b-15 m-t-10">Claimed Reward</h6>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="rounded bg-white p-3 text-center">
                                    @if($dataMy->image != null)
                                        <img src="{{$dataMy->image}}" class="img-responsive pull-xs-right text-muted" alt="user" style="height: 90px;">
                                    @endif
                                    <div class="">
                                        <h6 class="text-muted text-uppercase m-b-15 m-t-10">Peringkat Anda</h6>
                                        <h2 class="m-b-10">{{$dataMy->name}}</h2>
                                        <p class="m-b-10">
                                            <h2 class="m-b-10"><a href="{{ URL::to('/') }}/m/history/reward" class="btn btn-success btn-sm">history reward</a></h2>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($getMyPeringkat != null)
                    @if($canClaim == true )
                    <br>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="rounded bg-white p-3 text-center">
                                    @if($dataMy->image != null)
                                        <img src="/image/koin_lmb.png" class="img-responsive pull-xs-right text-muted" alt="user" style="height: 90px;">
                                    @endif
                                    <div class="">
                                        <h6 class="text-muted text-uppercase m-b-15 m-t-10">Claimed Reward</h6>
                                        <h2 class="m-b-10">{{$getMyPeringkat->reward_detail}}</h2>
                                        <p class="m-b-10">
                                            <input type="hidden" id="reward_id" value="{{$getMyPeringkat->id}}">
                                            <button type="submit" class="btn btn-secondary btn-sm"  id="submitBtn" data-toggle="modal" data-target="#confirmSubmit" onClick="inputSubmit()">claimed</button>
                                        </p>
                                    </div>
                                    <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" id="confirmDetail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    <br>
                    
                    <div class="rounded-lg bg-white p-3 mb-3">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="card-box tilebox-one">
                                    <i class="icon-trophy pull-xs-right text-muted text-warning"></i>
                                    <h6 class="text-muted text-uppercase m-b-20">Tim Anda</h6>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="table-responsive">
                                @if ( Session::has('message') )
                                    <div class="alert alert-{{ Session::get('messageclass') }} alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        {{  Session::get('message')    }} 
                                    </div>
                                @endif
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
            @include('layout.member.nav')
        </div>
        <div class="overlay"></div>
    </div>

@stop

@section('styles')
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/fonts/slick.woff">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
    <script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
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
