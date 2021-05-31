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
                    <th class="py-1 px-2">Date</th>
                    <th class="py-1 px-2">Amount</th>
                    <th class="py-1 px-2">Status</th>
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
                    <td class="py-1 px-2">{{date('Y-m-d', strtotime($row->date))}}</td>
                    <td class="py-1 px-2">{{number_format($row->amount, 2)}}</td>

                    <td class="py-1 px-2">@if (isset($row->type))
                        @if ($row->type == 1)
                        @if (strpos($row->hash, 'Stake dari Shopping Reward') !== false)
                        {{$row->hash}}
                        @else
                        <a href="https://tronscan.org/#/transaction/{{$row->hash}}"
                            class="text-blue-500 underline">Stake</a>
                        @endif

                        @else
                        @if (strpos($row->hash, 'Unstaking') !== false)
                        {{$row->hash}}
                        @else
                        <a href="https://tronscan.org/#/transaction/{{$row->hash}}"
                            class="text-blue-500 underline">Unstake</a>
                        @endif
                        @endif
                        @else
                        @if ($row->hash == null)
                        Processing
                        @else
                        @if (strlen($row->hash) < 64) {{$row->hash}} @else <a
                            href="https://tronscan.org/#/transaction/{{$row->hash}}" class="text-blue-500 underline">
                            {{$shortenHash}}</a>
                            @endif

                            @endif
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