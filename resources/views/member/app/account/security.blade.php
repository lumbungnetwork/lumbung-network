@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        <div class="nm-convex-gray-100 rounded-2xl p-2">
            <h4 class="text-center text-lg text-gray-600 font-light">2FA</h4>
            <div class="text-center text-xs text-gray-500 font-light">(Two Factor Authentication)</div>
            <div class="mt-2">
                @if ($user->{'2fa'} == null)
                <form action="{{ route('member.security.postCreate2FA') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="my-3 text-gray-500 text-justify text-xs leading-4 font-light">
                        Pin 2FA adalah password lapis kedua yang melindungi akun anda dari kebocoran password utama,
                        minimal 4 digit angka. Pin ini diperlukan untuk beberapa konfirmasi yang bersifat sensitif.
                    </div>
                    <div class="mt-4 text-gray-400 text-xs font-extralight">Pin 2FA Baru:</div>
                    <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                        <input inputmode="numeric" pattern="[0-9]*" required
                            class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                            name="password">
                    </div>
                    <div class="mt-4 text-gray-400 text-xs font-extralight">Pin 2FA Baru (Ulangi):</div>
                    <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                        <input inputmode="numeric" pattern="[0-9]*" required
                            class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                            name="password_confirmation">
                    </div>
                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Buat
                            Pin 2FA</button>
                    </div>
                </form>
                @else
                <div class="p-4 text-sm text-green-400 font-medium text-center">2FA Pin anda telah aktif</div>
                <div x-data="{ open: false }">
                    <div class="my-2 flex justify-end">
                        <button @click="open = true" x-show="!open" type="button"
                            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ubah
                            Pin 2FA</button>
                    </div>
                    <div x-show="open">
                        <form action="{{ route('member.security.postEdit2FA') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="mt-4 text-gray-400 text-xs font-extralight">Pin 2FA Lama:</div>
                            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                                <input inputmode="numeric" pattern="[0-9]*" required
                                    class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                                    name="old_password">
                            </div>
                            <div class="mt-4 text-gray-400 text-xs font-extralight">Pin 2FA Baru:</div>
                            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                                <input inputmode="numeric" pattern="[0-9]*" required
                                    class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                                    name="password">
                            </div>
                            <div class="mt-4 text-gray-400 text-xs font-extralight">Pin 2FA Baru (Ulangi):</div>
                            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                                <input inputmode="numeric" pattern="[0-9]*" required
                                    class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                                    name="password_confirmation">
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button type="submit"
                                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ubah
                                    Pin 2FA</button>
                            </div>
                        </form>
                    </div>
                </div>

                @endif

            </div>
        </div>

        {{-- Password --}}
        <div class="mt-6 nm-convex-gray-100 rounded-2xl p-2">
            <h4 class="my-3 text-center text-lg text-gray-600 font-light">Ubah Password</h4>

            <form action="{{ route('member.security.postChangePassword') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mt-4 text-gray-400 text-xs font-extralight">Password Lama:</div>
                <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                    <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                        name="old_password" required>
                </div>
                <div class="mt-4 text-gray-400 text-xs font-extralight">Password Baru:</div>
                <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                    <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password" name="password"
                        required>
                </div>
                <div class="mt-4 text-gray-400 text-xs font-extralight">Password Baru (Ulangi):</div>
                <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                    <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="password"
                        name="password_confirmation" required>
                </div>
                <div class="mt-3 flex justify-end">
                    <button type="submit"
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ubah
                        Password</button>
                </div>
            </form>
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('style')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection

@section('scripts')
<script>
    $(":submit").click( function () {
        Swal.fire('Memproses...')
        swal.showLoading();
    })
    
    
</script>
@endsection