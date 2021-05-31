@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-3">
        <div class="mt-4 nm-convex-gray-50 rounded-xl p-2 ">
            <img class="w-20 float-right" src="/image/tron-logo.png" alt="tron logo">

            @if ($user->tron == null)
            <div class="flex items-center py-2">
                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                <p class="text-sm font-light ">Inactive</p>
            </div>
            <form action="{{ route('member.tron.postSetTron') }}" method="POST">
                @csrf
                <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input style="font-size: 10px" class="w-full bg-transparent focus:outline-none" type="text"
                        placeholder="Tron Address" value="{{ old('tron') }}" name="tron">
                </div>
                @error('tron')
                <div class="text-red-600 text-xs font-light">{{ $message }}</div>
                @enderror

                <button type="submit"
                    class="float-right mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Set
                    Address</button>

            </form>
            @else
            <div class="flex items-center py-2">
                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                <p class="text-sm font-light ">Active</p>
            </div>
            <div class="mt-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p style="font-size: 10px" class="font-extralight">{{$user->tron}}</p>
            </div>
            <div class="mt-2 flex justify-end">
                <button id="reset-btn"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Reset
                    Address</button>
            </div>
            <form action="{{ route('member.tron.postResetTron') }}" method="POST" id="reset-form">
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