@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-3">
        <div class="mt-4 nm-convex-gray-50 rounded-xl p-2 ">
            @if ($user->bank == null)
            <div class="flex items-center py-2">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <p class="text-sm font-light ">Inactive</p>
            </div>
            <form action="{{ route('member.tron.postSetBank') }}" method="POST">
                @csrf
                <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <select name="bank" id="bank" class="text-xs ml-1 w-full bg-transparent focus:outline-none">
                        <option value="BCA">BCA</option>
                        <option value="BRI">BRI</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BNI/BNI Syariah">BNI/BNI Syariah</option>
                        <option value="Jenius/BTPN">Jenius/BTPN</option>
                        <option value="Bank Syariah Indonesia">Bank Syariah Indonesia (BSI)</option>
                        <option value="CIMB/CIMB Syariah">CIMB/CIMB Syariah</option>
                        <option value="Muamalat">Muamalat</option>
                        <option value="Permata/Permata Syariah">Permata/Permata Syariah</option>
                        <option value="Digibank/DBS">Digibank/DBS</option>
                    </select>
                </div>
                @error('bank')
                <div class="text-red-600 text-xs font-light">{{ $message }}</div>
                @enderror
                <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input class="text-xs ml-1 w-full bg-transparent focus:outline-none" type="text"
                        placeholder="No. Rekening" value="{{ old('account_no') }}" name="account_no">
                </div>
                @error('account_no')
                <div class="text-red-600 text-xs font-light">{{ $message }}</div>
                @enderror
                <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input class="text-xs ml-1 w-full bg-transparent focus:outline-none" type="text"
                        placeholder="Atas Nama" value="{{ old('name') }}" name="name">
                </div>
                @error('name')
                <div class="text-red-600 text-xs font-light">{{ $message }}</div>
                @enderror

                <button type="submit"
                    class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Simpan</button>

            </form>
            @else
            <div class="flex items-center py-2">
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                <p class="text-sm font-light ">Active</p>
            </div>
            <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p class="ml-2 text-xs font-extralight">{{$user->bank->bank}}</p>
            </div>
            <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p class="ml-2 text-xs font-extralight">{{$user->bank->account_no}}</p>
            </div>
            <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p class="ml-2 text-xs font-extralight">{{$user->bank->name}}</p>
            </div>
            <div class="mt-2 flex justify-end">
                <button id="reset-btn"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ganti
                    Rekening</button>
            </div>
            <form action="{{ route('member.tron.postResetBank') }}" method="POST" id="reset-form">
                @csrf
                <input type="hidden" name="password" id="password">
            </form>
            @endif

            <div class="clear-right"></div>

        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    $('#reset-btn').click(async function () {
        const { value: password } = await Swal.fire({
            title: 'Verifikasi 2FA',
            input: 'number',
            inputLabel: 'Masukkan Pin 2FA anda',
            inputPlaceholder: 'Pin 2FA',
            showCancelButton: true,
            inputAttributes: {
                maxlength: 10,
                autocapitalize: 'off',
                autocorrect: 'off'
            }
        })

        if (password) {
            swal.showLoading();
            $('#password').val(password);
            $('#reset-form').submit();
        }
    })

    $(":submit").click( function () {
        Swal.fire('Memproses...')
        swal.showLoading();
    })
</script>
@endsection