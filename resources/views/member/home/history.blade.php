@extends('layout.member.new_main')
@section('content')

<div class="wrapper">


    <!-- Page Content -->
    <div id="content">

        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ url()->previous() }}">
                        <i class="fa fa-arrow-left"></i> Back
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
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data != null)
                                <?php $no = 0; ?>
                                @foreach($data as $row)
                                @php
                                $no++;
                                if ($row->hash != null) {
                                $shortenHash = substr($row->hash, 0, 5) . '...' . substr($row->hash, -5);
                                }
                                @endphp

                                <tr>
                                    <td>{{date('Y-m-d', strtotime($row->date))}}</td>
                                    <td>{{$row->amount}}</td>

                                    <td>@if (isset($row->type))
                                        @if ($row->type == 1)
                                        @if (strpos($row->hash, 'Stake dari Shopping Reward') !== false)
                                        {{$row->hash}}
                                        @else
                                        <a href="https://tronscan.org/#/transaction/{{$row->hash}}"
                                            class="text-info">Stake</a>
                                        @endif

                                        @else
                                        @if (strpos($row->hash, 'Unstaking') !== false)
                                        {{$row->hash}}
                                        @else
                                        <a href="https://tronscan.org/#/transaction/{{$row->hash}}"
                                            class="text-info">Unstake</a>
                                        @endif
                                        @endif
                                        @else
                                        @if ($row->hash == null)
                                        Processing
                                        @else
                                        <a href="https://tronscan.org/#/transaction/{{$row->hash}}"
                                            class="text-info">{{$shortenHash}}</a>
                                        @endif
                                        @endif

                                    </td>

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