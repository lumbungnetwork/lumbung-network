@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        <div class="mt-4 nm-concave-gray-100 rounded-2xl p-3">
            <form action="{{ route('member.store.postStoreEditInfo') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="my-3 text-xs text-gray-500">Silakan perbarui Info Toko anda:</div>
                <div style="font-size: 10px" class="text-gray-400 font-light">Nama Toko</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                        name="shop_name" value="{{ $sellerProfile->shop_name }}" required>
                </div>
                @error('shop_name')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Motto/Slogan Toko (Max 70 karakter)</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <textarea rows="3" class="bg-transparent w-full ml-2 text-xs font-extralight focus:outline-none"
                        name="motto" required>{{ $sellerProfile->motto }}</textarea>
                </div>
                @error('motto')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Username Telegram</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <span class="ml-2 text-xs font-extralight">@</span><input type="text"
                        class="ml-2 w-10/12 text-xs font-extralight bg-transparent focus:outline-none" name="tg_user"
                        value="{{ $sellerProfile->tg_user }}" required>
                </div>
                @error('tg_user')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">No. HP/WhatsApp</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                        name="no_hp" value="{{ $sellerProfile->no_hp }}" required>
                </div>
                @error('no_hp')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div class="mt-2">
                    <div id="img-preview" class="object-contain w-20 rounded-2xl">
                        <img src="{{ asset('/storage/sellers') }}/{{ $sellerProfile->image }}" alt="seller pic">
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
        </div>
    </div>
</div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
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
@endsection