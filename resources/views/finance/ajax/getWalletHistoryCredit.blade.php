<div class="p-0 text-left ">
    <h4 class="text-xl font-light text-center">History</h4>
    @if (count($data) > 0)
    <div class="mt-3 p-0">
        <table class="my-2">
            <thead>
                <tr>
                    <th class="bg-blue-200 text-center border py-4">
                        Date
                    </th>
                    <th class="bg-blue-200 text-center border py-4">
                        Details
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td class="text-center border py-4 px-2">
                        {{date('j M y G:i:s', strtotime($item->created_at))}}
                    </td>
                    <td class="border py-4 px-2">
                        @if ($item->type == 1)
                        @if ($item->source == 1)
                        <strong class=" text-green-500">+${{number_format($item->amount, 2)}}</strong> Referral Bonus
                        from
                        {{$item->username}}
                        @endif
                        @if ($item->source == 2)
                        <strong class="text-green-500">+${{number_format($item->amount, 2)}}</strong> Withdrawed from
                        Contract No.{{$item->source_id}}
                        @endif
                        @if ($item->source == 3)
                        <strong class="text-green-500">+${{number_format($item->amount, 2)}}</strong> Transfer from
                        {{$item->username}}
                        @endif
                        @if ($item->source == 4)
                        <strong class="text-green-500">+${{number_format($item->amount, 2)}}</strong> Convert from USDT
                        @endif

                        @else
                        @if ($item->source == 3)
                        <strong class="text-red-500">-${{number_format($item->amount, 2)}}</strong> Transfer to
                        {{$item->username}}
                        @endif
                        @if ($item->source == 4)
                        <strong class="text-red-500">-${{number_format($item->amount, 2)}}</strong> Convert to USDT
                        @endif

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center">
        You don't have any activity, yet.
    </p>
    @endif

    <button type="button" onclick="swal.close()"
        class="float-right mt-3 py-3 px-1 bg-gray-500 rounded-2xl w-32 text-white text-xs focus:outline-none focus:bg-gray-600">Close</button>
    <div class="clear-right"></div>
</div>