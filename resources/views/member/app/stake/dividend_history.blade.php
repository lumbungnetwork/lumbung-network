@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        {{-- History Table --}}
        <div class="mt-2 nm-inset-gray-200 rounded-2xl p-2 w-full overflow-x-scroll">
            <table class="table table-auto text-xs font-extralight">
                <thead>
                    <tr>
                        <th class="py-1 px-2">Date</th>
                        <th class="py-1 px-2">Dividend</th>
                        <th class="py-1 px-2">Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @if($data)
                    @foreach($data as $row)

                    <tr>
                        <td class="py-1 px-2">{{date('d M y', strtotime($row->created_at))}}</td>
                        <td class="py-1 px-2">Rp{{number_format($row->amount)}}</td>

                        <td class="py-1 px-2">
                            @php
                            $source = 'Kontribusi Penjualan Produk Fisik';
                            $type = 'physical';
                            switch ($row->type) {
                                case 3:
                                    $source = 'Kontribusi Penjualan Produk Digital';
                                    $type = 'digital';
                                    break;
                                case 4:
                                    $source = 'Kontribusi dari Premium Membership (ID: ' . $row->source_id . ')';
                                    break;
                                case 5:
                                    $source = 'Kontribusi Dari Fee Lumbung Finance';
                                    break;
                            }
                            @endphp
                            @if ($row->type <= 3)
                            <span class="text-green-600 underline">
                                <a href="{{ route('market.transaction', ['type' => $type, 'transaction_id' => $row->source_id]) }}">{{ $source }}</a>
                            </span>
                            @else
                            <span class="text-green-600 font-medium">{{ $source }}</span>
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
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>

</script>
@endsection