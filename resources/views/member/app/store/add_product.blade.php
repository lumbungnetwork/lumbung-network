@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        <div class="nm-flat-white rounded-2xl p-2">
            <form action="{{ route('member.store.postAddProduct') }}" method="POST" enctype="multipart/form-data"
                id="add-product-form" autocomplete="off">
                @csrf
                <div class="my-3 text-xs text-gray-500">Buat info produk anda:</div>
                <div style="font-size: 10px" class="text-gray-400 font-light">Nama Produk:</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                        name="name" id="name" required>
                </div>
                @error('name')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Ukuran Produk:</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                        name="size" id="size" placeholder="1Kg, 1L, 500gr..." required>
                </div>
                @error('size')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Harga Produk (Rp):</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" inputmode="numeric" pattern="[0-9]*"
                        class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none allownumericwithoutdecimal"
                        name="price" id="price" placeholder="Hanya angka saja, cth: 25000" required>
                </div>
                @error('price')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Deskripsi produk (Opsional)</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <textarea rows="3" class="bg-transparent w-full ml-2 text-xs font-extralight focus:outline-none"
                        name="desc" id="desc"
                        placeholder="Minyak Goreng berkualitas dengan 3x penyaringan..."></textarea>
                </div>
                @error('desc')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Kategori produk:</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <select name="category_id" id="category_id"
                        class="bg-transparent w-full ml-2 text-xs font-extralight focus:outline-none">
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div style="font-size: 10px" class="text-gray-400 font-light">Stock Awal:</div>
                <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                    <input type="text" inputmode="numeric" pattern="[0-9]*"
                        class="ml-2 w-10/12 text-xs font-extralight bg-transparent focus:outline-none allownumericwithoutdecimal"
                        name="qty" id="qty" value="0">
                </div>
                @error('qty')
                <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                @enderror
                <div id="search-image">
                    <div class="text-gray-400 text-xs font-light">Gambar Produk:</div>
                    <div style="font-size: 10px" class="text-gray-400 font-light">Sistem menggunakan model 'sharing'
                        gambar,
                        satu gambar produk dengan type/merk yang sama bisa dipakai oleh banyak penjual untuk menghemat
                        kapasitas penyimpanan. <br>Mohon untuk terlebih dulu memeriksa apakah gambar produk yang ingin
                        anda
                        pakai sudah tersedia sebelum memutuskan untuk Upload gambar baru.<br><br>Ketikkan 3-4 huruf awal
                        nama/merk produk, akan tampil list gambar tersedia, silakan klik untuk melihat gambar.</div>
                    <div class="mb-2 nm-inset-gray-100 rounded-xl p-2 overflow-x-scroll">
                        <input type="text" class="ml-2 w-full text-xs font-extralight bg-transparent focus:outline-none"
                            name="image" id="get-image" placeholder="ketikkan 3-4 huruf awal" required>
                    </div>
                    @error('image')
                    <div class="text-xs text-red-600 font-light">{{ $message }}</div>
                    @enderror
                    <div class="px-2">
                        <ul style="font-size: 10px"
                            class="font-light max-h-32 overflow-auto border border-solid border-gray-200 w-full hidden"
                            id="get_id-box"></ul>
                    </div>
                </div>


                <div class="mt-2">
                    <div id="img-preview" class="object-contain w-20 rounded-2xl">
                        <img src="{{ asset('/storage/products') }}/default.jpg" alt="default pic">
                    </div>
                    <div class="mt-2">
                        <button type="button" id="show-upload" style="font-size: 10px"
                            class="rounded-lg py-1 px-2 h-6 bg-gradient-to-br from-red-300 to-purple-300 font-light text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Saya
                            perlu Upload
                            Gambar</button>
                        <label id="upload"
                            class="hidden rounded-lg py-1 px-2 h-6 bg-gradient-to-br from-blue-300 to-purple-300 text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                            <span style="font-size: 10px" class="mt-2 font-light tracking-tighter">Upload Gambar</span>
                            <input type='file' accept="image/*" class="hidden" name="image" id="image" />
                        </label>
                    </div>

                </div>


            </form>
            <div class="mt-6 flex justify-end">
                <button id="submit-btn"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-300 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Simpan
                    Produk</button>
            </div>
        </div>
    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
<script>
    $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which> 57)) {
            event.preventDefault();
        }
    });

    $(document).ready(function(){
        $("#get-image").keyup(function(){
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.store.getSearchProductImage') }}" + "?name=" + $(this).val() ,
                success: function(data){
                    $("#get_id-box").show();
                    $("#get_id-box").html(data);
                }
            });
        });
    });

    function selectName(val) {
        let newImage = `<img src="{{ asset('/storage/products') }}/${val}" alt="${val}">`
        $("#get-image").val(val);
        $('#img-preview').empty();
        $('#img-preview').html(newImage);
        $("#get_id-box").hide();
    }

    $('#show-upload').click( function () {
        Swal.fire({
            title: 'Upload gambar?',
            text: "Apakah anda yakin gambar produk yang anda perlukan belum ada di daftar di atas?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Coba cek lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#search-image').remove();
                $('#show-upload').remove();
                $('#upload').show();
            }
        })
    })

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

    $('#submit-btn').click( function () {
        const getImage = document.getElementById("get-image");
        if (getImage) {
            $('#upload').remove(); 
        }
        Swal.fire('Memproses...');
        swal.showLoading();
        $('#add-product-form').submit();

    })
</script>
@endsection