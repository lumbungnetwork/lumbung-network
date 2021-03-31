@extends('layout.member.new_main')
@section('content')

<div class="wrapper">


    <!-- Page Content -->
    <div id="content">

        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/staking">
                        <i class="fa fa-arrow-left"></i> Back to Staking
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h5 class="mb-3">{{$headerTitle}}</h5>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Username</th>
                                    <th>Total Stake</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($stakers != null)
                                <?php $no = 0; ?>
                                @foreach($stakers as $staker)
                                @php
                                $no++;
                                @endphp
                                <tr>
                                    <td>{{$no}}</td>
                                    <td> @if ($staker->user_code == 'lumbung001')
                                        LumbungCustody
                                        @else
                                        {{$staker->user_code}}
                                        @endif </td>
                                    <td>{{number_format($staker->net, 2)}}</td>

                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            lengthChange: false,
            
        });

    } );
</script>
@stop