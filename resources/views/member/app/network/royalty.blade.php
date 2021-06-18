@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Available Rewards --}}
    <div class="mt-3 py-2 px-4">
        <div
            class="bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl px-2 py-2 flex justify-around items-center">
            <div class="flex flex-col align-middle items-center">
                <img class="object-contain w-16" src="/image/koin_lmb2.png" alt="koin LMB">

            </div>
            <div class="space-y-2">

                <h2 class="font-medium text-gray-600 text-xs text-center">Bonus Royalty tersedia:</h2>
                <p class="font-extralight text-xl text-center">{{number_format($bonus->net, 2)}} LMB</p>
                <div class="my-1 flex justify-center space-x-1">
                    <button id="claim-btn"
                        class="rounded-lg py-1 px-2 bg-gradient-to-br from-green-400 to-purple-300 text-xs text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Claim</button>
                    <button id="stake-btn"
                        class="rounded-lg py-1 px-2 bg-gradient-to-br from-yellow-400 to-purple-300 text-xs text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Stake</button>
                </div>

            </div>
        </div>
    </div>

    <div class="px-4 py-2" x-data="{ tab: 'rewarded' }">
        {{-- History Tab--}}
        <div class="mt-3 border-gray-50 border-b-2">
            <ul class='flex cursor-pointer justify-center'>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-200 text-sm'
                    :class="{ 'active': tab === 'rewarded' }" @click="tab = 'rewarded'">Rewarded</li>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-200 text-sm'
                    :class="{ 'active': tab === 'claimed' }" @click="tab = 'claimed'">Claimed</li>
            </ul>
        </div>


        <div class="nm-inset-gray-100 rounded-2xl p-2 overflow-x-scroll">
            <div x-show="tab === 'rewarded'">
                {{-- Rewarded --}}
                @if (count($rewardedHistory) > 0)
                <table class="table table-auto text-xs font-extralight w-full">
                    <thead>
                        <tr>
                            <th class="py-1 px-2">Periode</th>
                            <th class="py-1 px-2">Downline</th>
                            <th class="py-1 px-2">Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rewardedHistory as $row)
                        <tr>
                            <td class="py-1 px-2">{{ date('M y', strtotime($row->created_at)) }}</td>
                            <td class="py-1 px-2 text-center">{{ $row->from->username }}</td>
                            <td class="py-1 px-2 text-center">{{ $row->level_id }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="my-3 text-sm text-gray-600 text-center">
                    Anda belum memiliki Riwayat Bonus Royalty
                </div>
                @endif
            </div>
            <div x-show="tab === 'claimed'">
                {{-- Claimed --}}
                @if (count($claimedHistory) > 0)
                <table class="table table-auto text-xs font-extralight w-full">
                    <thead>
                        <tr>
                            <th class="py-1 px-2">Date</th>
                            <th class="py-1 px-2">LMB</th>
                            <th class="py-1 px-2">Hash</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($claimedHistory as $row)
                        <tr>
                            <td class="py-1 px-2">{{date('d M y', strtotime($row->created_at))}}</td>
                            <td class="py-1 px-2">{{ number_format($row->amount, 2) }}</td>
                            <td class="py-1 px-2">
                                @if ($row->hash == null)
                                Processing
                                @else
                                @if (strpos($row->hash, 'Staked') !== false)
                                Staked
                                @else
                                @php
                                $shortHash = substr($row->hash, 0, 5) . '...' . substr($row->hash, -5, 5);
                                @endphp
                                <a class="text-indigo-500 underline"
                                    href="https://tronscan.org/#/transaction/{{$row->hash}}">{{$shortHash}}</a>
                                @endif

                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                @else
                <div class="my-3 text-sm text-gray-600 text-center">
                    Anda belum memiliki Riwayat Claim LMB dari Bonus Royalty
                </div>
                @endif

            </div>

        </div>


    </div>



</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<style>
    .active {
        background-color: white;
        color: black;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection

@section('scripts')
<script>
    let _token = '{{ csrf_token() }}';
    // Claim Button
    $('#claim-btn').click( function () {
        Swal.fire({
            title: 'Claim Reward LMB',
            text: "LMB akan ditransfer ke alamat TRON anda",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Claim!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Proses Claim');
                swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.claim.royalty') }}",
                    data: {_token:_token},
                    success: function(response){
                        
                        if (response.success) {
                            Swal.fire(
                                'Reward Claimed',
                                'LMB berhasil diklaim, dan segera masuk ke alamat TRON anda',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Claim Gagal',
                                response.message,
                                'error'
                            );
                        }
                        let href = window.location.href;
                        setTimeout( () => location.replace(href), 1000);
                    }
                });
            }
        })
    })

    // Stake Button
    $('#stake-btn').click( function () {
        Swal.fire({
            title: 'Stake Reward LMB',
            text: "Apakah anda ingin langsung Stake LMB dari Bonus Royalty ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Stake!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Proses Stake');
                swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.stake.royalty') }}",
                    data: {_token:_token},
                    success: function(response){
                        
                        if (response.success) {
                            Swal.fire(
                                'Reward Staked',
                                'Reward LMB berhasil di Stake',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Stake Gagal',
                                response.message,
                                'error'
                            );
                        }
                        let href = window.location.href;
                        setTimeout( () => location.replace(href), 1000);
                    }
                });
            }
        })
    })


</script>
@endsection