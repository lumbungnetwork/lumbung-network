@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        @if ($sellerProfile != null)
        <div class="p-2 nm-convex-gray-50 rounded-2xl">
            <div class="flex justify-between">
                <div class="w-7/12">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 text-center">
                        {{ $user->alamat }}</p>
                    <div class="mt-2 flex justify-around">
                        {{-- Phone --}}
                        <div class="rounded-full p-2 w-8 h-8 nm-convex-gray-100">
                            <a href="tel:{{$sellerProfile->no_hp}}">
                                <svg viewBox="0 0 64 64">
                                    <path data-name="layer2"
                                        d="M49.4 50.3l-8.8-5.8a4.1 4.1 0 0 0-5.6 1.1c-2 2.5-4.5 6.6-13.8-2.8S15.9 31 18.4 29a4.1 4.1 0 0 0 1.1-5.5l-5.8-8.8c-.8-1.1-1.8-2.9-4.2-2.6S1 15.9 1 23.5s6 17 14.2 25.2S32.8 63 40.5 63s11.2-6.8 11.5-8.5-1.5-3.5-2.6-4.2z"
                                        fill="#202020"></path>
                                    <path data-name="layer1"
                                        d="M29 1a2 2 0 0 0 0 4 30 30 0 0 1 30 30 2 2 0 1 0 4 0A34 34 0 0 0 29 1z"
                                        fill="#202020">
                                    </path>
                                    <path data-name="layer1"
                                        d="M29 11a2 2 0 1 0 0 4 20 20 0 0 1 20 20 2 2 0 1 0 4 0 24 24 0 0 0-24-24z"
                                        fill="#202020">
                                    </path>
                                    <path data-name="layer1"
                                        d="M38 35a2 2 0 1 0 4 0 13 13 0 0 0-13-13 2 2 0 0 0 0 4 9 9 0 0 1 9 9z"
                                        fill="#202020">
                                    </path>
                                </svg>
                            </a>
                        </div>

                        {{-- WhatsApp --}}
                        <div class="rounded-full p-2 w-8 h-8 nm-convex-gray-100">
                            <a href="https://wa.me/62{{substr($sellerProfile->no_hp, 1)}}">
                                <svg viewBox="0 0 64 64">
                                    <path data-name="layer2"
                                        d="M30.287 2.029A29.769 29.769 0 0 0 5.223 45.266L2.064 60.6a1.158 1.158 0 0 0 1.4 1.361L18.492 58.4A29.76 29.76 0 1 0 30.287 2.029zm17.931 46.2"
                                        fill="none" stroke="#3ac230" stroke-linecap="round" stroke-miterlimit="10"
                                        stroke-width="4" stroke-linejoin="round"></path>
                                    <path data-name="layer1"
                                        d="M46.184 38.205l-5.765-1.655a2.149 2.149 0 0 0-2.126.561l-1.41 1.436a2.1 2.1 0 0 1-2.283.482c-2.727-1.1-8.463-6.2-9.927-8.754a2.1 2.1 0 0 1 .166-2.328l1.23-1.592a2.148 2.148 0 0 0 .265-2.183l-2.424-5.485a2.149 2.149 0 0 0-3.356-.769c-1.609 1.361-3.517 3.428-3.749 5.719-.409 4.039 1.323 9.13 7.872 15.242 7.566 7.063 13.626 8 17.571 7.04 2.238-.542 4.026-2.714 5.154-4.493a2.15 2.15 0 0 0-1.218-3.221z"
                                        fill="none" stroke="#3ac230" stroke-linecap="round" stroke-miterlimit="10"
                                        stroke-width="4" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>

                        {{-- Telegram --}}
                        <div class="rounded-full p-2 w-8 h-8 nm-convex-gray-100">
                            <a href="https://t.me/{{$sellerProfile->tg_user}}">
                                <svg viewBox="0 0 240 240">
                                    <path
                                        d="M66.964 134.874s-32.08-10.062-51.344-16.002c-17.542-6.693-1.57-14.928 6.015-17.59 7.585-2.66 186.38-71.948 194.94-75.233 8.94-4.147 19.884-.35 14.767 18.656-4.416 20.407-30.166 142.874-33.827 158.812-3.66 15.937-18.447 6.844-18.447 6.844l-83.21-61.442z"
                                        fill="none" stroke="#0088cc" stroke-width="10" />
                                    <path d="M92.412 201.62s4.295.56 8.83-3.702c4.536-4.26 26.303-25.603 26.303-25.603"
                                        fill="none" stroke="#0088cc" stroke-width="10" />
                                    <path
                                        d="M66.985 134.887l28.922 14.082-3.488 52.65s-4.928.843-6.25-3.613c-1.323-4.455-19.185-63.12-19.185-63.12z"
                                        fill-rule="evenodd" stroke="#0088cc" stroke-width="10"
                                        stroke-linejoin="bevel" />
                                    <path
                                        d="M66.985 134.887s127.637-77.45 120.09-71.138c-7.55 6.312-91.168 85.22-91.168 85.22z"
                                        fill-rule="evenodd" stroke="#0088cc" stroke-width="9.67"
                                        stroke-linejoin="bevel" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
                {{-- Seller Profile Picture --}}
                <div class="w-5/12 p-2">
                    <img class="object-contain rounded-2xl"
                        src="{{ asset('/storage/sellers') }}/{{$sellerProfile->image}}" alt="seller-profile-picture">
                </div>

            </div>
            {{-- Motto --}}
            <div class="mt-2 px-2 w-full">
                <p class="font-light text-xs sm:text-sm text-gray-600 text-center">
                    "{{$sellerProfile->motto}}"</p>
            </div>
        </div>
        @endif

        <div class="mt-4 nm-concave-gray-100 rounded-2xl p-3">
            @if ($sellerProfile != null)
            <div style="font-size: 10px" class="text-gray-400 font-light">Nama Toko</div>
            <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p class="ml-2 text-xs font-extralight">{{$sellerProfile->shop_name}}</p>
            </div>
            <div style="font-size: 10px" class="text-gray-400 font-light">Motto/Slogan Toko (Max 70 karakter)</div>
            <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <textarea rows="3"
                    class="bg-transparent w-full ml-2 text-xs font-extralight">{{$sellerProfile->motto}}</textarea>
            </div>
            <div style="font-size: 10px" class="text-gray-400 font-light">Username Telegram</div>
            <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p class="ml-2 text-xs font-extralight">@ {{$sellerProfile->tg_user}}</p>
            </div>
            <div style="font-size: 10px" class="text-gray-400 font-light">No. HP/WhatsApp</div>
            <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                <p class="ml-2 text-xs font-extralight">{{$sellerProfile->no_hp}}</p>
            </div>
            <div class="mt-2">
                <img src="{{ asset('/storage/sellers') }}/{{$sellerProfile->image}}" alt="seller pic"
                    class="object-contain w-20 rounded-2xl">
            </div>
            <div class="mt-2 flex justify-end">
                <a href="{{ route('member.store.editInfo') }}"
                    class="rounded-lg py-1 px-2 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ubah
                    Info</a>
            </div>

            @else
            <form action="{{ route('member.store.postStoreAddInfo') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="my-3 text-xs text-gray-500">Silakan lengkapi Info Toko anda:</div>
                <div style="font-size: 10px" class="text-gray-400 font-light">Nama Toko</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                        name="shop_name" required>
                </div>
                @error('shop_name')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Motto/Slogan Toko (Max 70 karakter)</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <textarea rows="3" class="bg-transparent w-full ml-2 text-xs font-extralight focus:outline-none"
                        name="motto" required></textarea>
                </div>
                @error('motto')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Username Telegram</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <span class="ml-2 text-xs font-extralight">@</span><input type="text"
                        class="ml-2 w-10/12 text-xs font-extralight bg-transparent focus:outline-none" name="tg_user"
                        required>
                </div>
                @error('tg_user')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">No. HP/WhatsApp</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                        name="no_hp" required>
                </div>
                @error('no_hp')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div class="mt-2">
                    <div id="img-preview" class="object-contain w-20 rounded-2xl">
                        <img src="{{ asset('/storage/sellers') }}/default.jpg" alt="default pic">
                    </div>
                    <div class="mt-2">
                        <label
                            class="rounded-lg py-1 px-2 h-6 bg-gradient-to-br from-blue-300 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                            <span class="mt-2 text-xs font-light tracking-tighter">Pilih Gambar</span>
                            <input type='file' accept="image/*" class="hidden" name="image" id="image" />
                        </label>
                    </div>


                    @error('image')
                    <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2 flex justify-end">
                    <button
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-300 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Simpan
                        Info</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
@if ($sellerProfile == null)
<script>
    const chooseFile = document.getElementById("image");
        const imgPreview = document.getElementById("img-preview");
    
        chooseFile.addEventListener("change", function () {
            getImgData();
        });
    
        function getImgData() {
            const files = chooseFile.files[0];
            if (files) {
                const fileReader = new FileReader();
                fileReader.readAsDataURL(files);
                fileReader.addEventListener("load", function () {
                    imgPreview.style.display = "block";
                    imgPreview.innerHTML = '<img src="' + this.result + '" />';
                });
            }
        }
</script>
@endif

@endsection