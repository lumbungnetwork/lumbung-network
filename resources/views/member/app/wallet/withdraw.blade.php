@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    {{-- Balance Card --}}
    <div class="p-4">
        <div class="mt-3 bg-gradient-to-r from-green-100 to-yellow-300 opacity-80 rounded-2xl p-2">
            <div class="p-2 text-2xl font-extralight text-gray-700 text-center">
                {{ number_format($netBalance, 0) }} eIDR
            </div>

        </div>
    </div>

    {{-- Select Method Tab --}}
    <div class="px-4 py-2" x-data="{ tab: 'bank' }">

        <div class="mt-3">
            <ul class='flex cursor-pointer justify-center'>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'bank' }" @click="tab = 'bank'">via Bank</li>
                <li class='py-2 px-4 rounded-t-lg text-gray-500 bg-gray-300 text-sm'
                    :class="{ 'active': tab === 'tron' }" @click="tab = 'tron'">via TRON</li>
            </ul>
        </div>


        <div class="nm-convex-white rounded-2xl p-2 overflow-x-scroll">
            <div x-show="tab === 'bank'">
                {{-- Bank --}}
                <div class="px-2">
                    <div class="my-2 text-xs text-gray-500">Jumlah Withdraw (Rp)</div>
                    <div class="nm-inset-gray-200 rounded-lg p-1">
                        {{-- Order Form --}}
                        <form action="{{ route('member.postWalletWithdraw') }}" method="POST" id="order-form-1">
                            @csrf
                            <input type="hidden" name="method" value="1">
                            <input type="hidden" name="password" class="password">
                            <input type="text"
                                class="bg-transparent text-xs font-light ml-1 focus:outline-none allownumericwithoutdecimal"
                                inputmode="numeric" pattern="[0-9]*" name="amount" id="amount-1"
                                placeholder="Min. Rp20,000" autocomplete="off">
                        </form>

                    </div>
                    <div class="mt-2 text-xs text-purple-600">Biaya transfer: Rp5.500,- per penarikan.</div>
                    <div class="mt-3 flex justify-end">
                        <button onclick="submit(1)"
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Withdraw</button>
                    </div>
                </div>




            </div>
            <div x-show="tab === 'tron'">
                {{-- TRON --}}
                <div class="px-2">
                    <div class="my-2 text-xs text-gray-500">Jumlah Withdraw (Rp)</div>
                    <div class="nm-inset-gray-200 rounded-lg p-1">
                        {{-- Order Form --}}
                        <form action="{{ route('member.postWalletWithdraw') }}" method="POST" id="order-form-2">
                            @csrf
                            <input type="hidden" name="method" value="2">
                            <input type="hidden" name="password" class="password">
                            <input type="text"
                                class="bg-transparent text-xs font-light ml-1 focus:outline-none allownumericwithoutdecimal"
                                inputmode="numeric" pattern="[0-9]*" name="amount" id="amount-2"
                                placeholder="Min. 1 eIDR" autocomplete="off">
                        </form>

                    </div>
                    <div class="mt-3 flex justify-end">
                        <button onclick="submit(2)"
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 focus:outline-none outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Withdraw</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 p-3 border border-gray-400 border-solid rounded-xl">
            <div class="-mt-7 w-14 text-lg text-center font-bold text-gray-600 tracking-wider bg-gray-200">
                Riwayat</div>
            <div class="mt-2 space-y-2">
                @if (count($data) < 1) <div class="text-sm text-gray-500 text-center">Anda belum memiliki Riwayat
                    Transaksi Withdraw</div>
            @else
            @foreach ($data as $item)
            <div class="nm-flat-gray-50 p-1 rounded-lg">
                <div class="text-sm text-gray-500 font-medium tracking-tighter">
                    Rp{{ number_format($item->amount) }}</div>
                @php
                $status = 'PROSES';
                if ($item->status == 2) {
                $status = 'TUNTAS';
                }
                if ($item->status == 3) {
                $status = 'BATAL';
                }
                @endphp
                <div style="font-size: 10px" class="text-gray-400 font-extralight">Status: {{ $status }}
                </div>

            </div>

            @endforeach
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
        background: linear-gradient(145deg, #FFFFFF, #D9D9D9);
        color: black;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection

@section('scripts')
<script>
    function submit(type) {
        let form_id = '#order-form-' + type;
        let val_id = '#amount-' +type;
        if ($(val_id).val() == '') {
            Swal.fire('Oops', 'Anda belum mengisi jumlah Withdraw', 'error');
            return false;
        }
        if (type == 1 && $(val_id).val() < 20000) {
            Swal.fire('Oops', 'Minimum Withdraw via Bank adalah Rp20.000,-', 'error');
            return false;
        }
        Swal.fire({
            title: 'Withdraw',
            text: "Anda yakin ingin melakukan withdraw?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                const { value: password } = await Swal.fire({
                    title: 'Verifikasi 2FA',
                    html: '<input id="swal-input1" class="w-3/4 h-12 text-lg py-2 text-center justify-center bg-gray-200" inputmode="numeric" pattern="[0-9]*">',
                    focusConfirm: false,
                    showCancelButton: true,
                    preConfirm: () => {
                        return [
                            document.getElementById('swal-input1').value,
                        ]
                    }
                    
                })
                
                if (password) {
                    let pw = JSON.stringify(password);
                    let pass = pw.replace(/[""\[\]]/g, '');
                    Swal.fire('Memproses...')
                    swal.showLoading();
                    $('.password').val(pass);
                    $(form_id).submit();
                }
            }
        })
    }
</script>
@endsection