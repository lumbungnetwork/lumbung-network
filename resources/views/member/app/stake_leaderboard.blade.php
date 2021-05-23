@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto px-3 py-2">
    {{-- History Table --}}
    <div class="mt-2 nm-inset-gray-200 rounded-2xl p-2 w-full overflow-x-scroll">

        <table class="table table-auto text-xs font-extralight">
            <thead>
                <tr>
                    <th class="py-1 px-2">No.</th>
                    <th class="py-1 px-2">Username</th>
                    <th class="py-1 px-2">Total Stake</th>
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
                    <td class="py-1 px-2">{{$no}}</td>
                    <td class="py-1 px-2"> @if ($staker->username == 'lumbung001')
                        LumbungCustody
                        @else
                        {{$staker->username}}
                        @endif </td>
                    <td class="py-1 px-2">{{number_format($staker->net, 2)}}</td>

                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        <div class="p-1 text-sm">
            {{$stakers->links()}}
        </div>
    </div>


</div>



@include('member.components.mobile_sticky_nav')

@endsection