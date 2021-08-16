@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto px-3 py-2">
    {{-- History Table --}}
    <div class="mt-2 nm-inset-gray-200 rounded-2xl p-2 w-full overflow-x-scroll">
        <table class="table table-auto w-full text-xs font-extralight">
            <thead>
                <tr>
                    <th class="py-1 px-2">Date</th>
                    <th class="py-1 px-2">Amount</th>
                    <th class="py-1 px-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @if($data != null)
                @foreach($data as $row)

                <tr>
                    <td class="py-1 px-2">{{date('Y-m-d', strtotime($row->date))}}</td>
                    <td class="py-1 px-2">Rp{{number_format($row->amount)}}</td>

                    <td class="py-1 px-2 text-center">
                        @if ($row->type == 1)
                        <span class="text-green-600 font-medium">IN</span>
                        @else
                        <span class="text-red-600 font-medium">OUT</span>
                        @endif
                    </td>

                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        <div>
            {{$data->links()}}
        </div>
    </div>


</div>



@include('member.components.mobile_sticky_nav')

@endsection