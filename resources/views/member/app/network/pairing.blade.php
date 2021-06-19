@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        {{-- Binary Balance Card --}}
        <div class="flex justify-between">
            <div class="nm-flat-gray-100 w-20 p-2 rounded-lg flex-col justify-center items-center text-center">
                <div class="text-lg text-gray-600 font-bold">{{ $left }}</div>
                <div class="text-xs text-gray-500">Total Left</div>
            </div>
            <div class="nm-flat-gray-100 w-20 p-2 rounded-lg flex-col justify-center items-center text-center">
                <div class="text-lg text-gray-600 font-bold">{{ $paid->sum_left ?? 0 }}</div>
                <div class="text-xs text-gray-500">Total Paid</div>
            </div>
            <div class="nm-flat-gray-100 w-20 p-2 rounded-lg flex-col justify-center items-center text-center">
                <div class="text-lg text-gray-600 font-bold">{{ $right }}</div>
                <div class="text-xs text-gray-500">Total Right</div>
            </div>
        </div>
        {{-- Binary History --}}
        <div class="mt-4 nm-inset-gray-100 p-2 rounded-2xl overflow-x-scroll">
            @if (count($binaryHistory) > 0)
            <table class="table table-auto text-xs font-extralight w-ful">
                <thead>
                    <tr>
                        <th class="py-1 px-2">Date</th>
                        <th class="py-1 px-2">Bonus</th>
                        <th class="py-1 px-2">Pasangan</th>
                        <th class="py-1 px-2">Index</th>
                        <th class="py-1 px-2">Total Omzet</th>
                        <th class="py-1 px-2">Total Pasangan</th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($binaryHistory as $row)
                    <tr class="border-t border-gray-300">
                        <td class="py-1 px-2">{{date('d M y', strtotime($row->index_date))}}</td>
                        <td class="py-1 px-2">{{ number_format($row->amount, 2) }} eIDR</td>
                        <td class="py-1 px-2 text-center">{{ $row->pairs }}</td>
                        <td class="py-1 px-2">{{ number_format($row->index, 2) }} eIDR</td>
                        <td class="py-1 px-2 text-center">{{ $row->total_premiums }}</td>
                        <td class="py-1 px-2 text-center">{{ $row->total_pairs }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="text-xs text-gray-500 font-extralight">Hanya menampilkan history bonus binary sejak Lumbung
                v.3.0
            </div>
            <div class="object-contain w-full text-xs p-2">
                {{ $binaryHistory->links() }}
            </div>
            @else
            <div class="my-3 text-sm text-gray-600 text-center">
                Anda belum memiliki Riwayat Bonus Binary sejak migrasi ke Lumbung v.3.0
            </div>
            @endif
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>

</script>
@endsection