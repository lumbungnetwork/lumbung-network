@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="p-4">
        @if ($user->is_profile == 0)
        <div style="font-size: 10px" class="mt-1 font-light leading-3 text-red-600"><b>WARNING:</b> Nama Lengkap hanya
            bisa
            diisi satu kali
            saja (permanen), pastikan mengisi sesuai dengan yang tercantum di Rekening Bank, bila anda hendak
            menggunakan Rekening Bank.</div>
        <div class="mt-3 nm-convex-gray-100 rounded-2xl p-2">
            <div class="mt-2 text-xs text-gray-500 font-light">Silakan lengkapi data diri anda:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text"
                    placeholder="Nama Lengkap" id="full_name" autocomplete="off">
            </div>
            <div style="font-size: 10px;" class="mt-12 mb-2 text-gray-400">Alamat:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <select onchange="getSearchKota(this.value)" name="provinsi" id="provinsi"
                    class="bg-transparent font-light text-xs text-gray-600 focus:outline-none w-full">
                    <option value="0">- Pilih Provinsi -</option>
                    @if(count($provinsi) > 0)
                    @foreach($provinsi as $row)
                    <option value="{{$row->id_prov}}">{{$row->nama}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <select onchange="getSearchKecamatan(this.value)" name="kota" id="kota"
                    class="bg-transparent font-light text-xs text-gray-600 focus:outline-none w-full">

                </select>
            </div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <select onchange="getSearchKelurahan(this.value)" name="kecamatan" id="kecamatan"
                    class="bg-transparent font-light text-xs text-gray-600 focus:outline-none w-full">

                </select>
            </div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <select name="kelurahan" id="kelurahan"
                    class="bg-transparent font-light text-xs text-gray-600 focus:outline-none w-full">

                </select>
            </div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text" name="alamat"
                    id="alamat" placeholder="Jalan" autocomplete="off">
            </div>
            <div class="flex justify-end">
                <button id="save-btn"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Simpan</button>
            </div>
        </div>
        @else
        {{-- if is_profile == 1 --}}
        <div class="mt-3 nm-convex-gray-100 rounded-2xl p-2">
            <div style="font-size: 10px;" class="mt-4 text-gray-400">Nama Lengkap:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" disabled type="text"
                    value="{{ $user->full_name }}" placeholder="Nama Lengkap">
            </div>
            <div class="mt-8 flex justify-end">
                <a href="{{ route('member.editProfile') }}">
                    <button
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Ubah
                        Alamat</button>
                </a>

            </div>
            <div style="font-size: 10px;" class="mt-4 text-gray-400">Provinsi:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text"
                    value="{{ $user->provinsi }}" disabled>
            </div>
            <div style="font-size: 10px;" class="mt-4 text-gray-400">Kota/Kab:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text"
                    value="{{ $user->kota }}" disabled>
            </div>
            <div style="font-size: 10px;" class="mt-4 text-gray-400">Kecamatan:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text"
                    value="{{ $user->kecamatan }}" disabled>
            </div>
            <div style="font-size: 10px;" class="mt-4 text-gray-400">Kelurahan:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text"
                    value="{{ $user->kelurahan }}" disabled>
            </div>
            <div style="font-size: 10px;" class="mt-4 text-gray-400">Jalan:</div>
            <div class="mb-4 nm-inset-gray-200 rounded-lg px-2 py-1 w-full">
                <input class="text-xs bg-transparent focus:outline-none ml-1 w-full" type="text"
                    value="{{ $user->alamat }}" disabled>
            </div>
        </div>
        @endif


    </div>
</div>



@include('member.components.mobile_sticky_nav')

@endsection

@section('scripts')
@if ($user->is_profile == 0)
<script>
    function getSearchKota(val) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.region.getSearchAddressRegionByType', ['type' => 'kota']) }}",
                    data: {provinsi:val},
                    success: function(url){
                            $( "#kota" ).empty();
                            $("#kota").html(url);
                    }
                });
            }
    
            function getSearchKecamatan(val) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.region.getSearchAddressRegionByType', ['type' => 'kecamatan']) }}",
                    data: {kota:val},
                    success: function(url){
                            $( "#kecamatan" ).empty();
                            $("#kecamatan").html(url);
                    }
                });
            }
    
            function getSearchKelurahan(val) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.region.getSearchAddressRegionByType', ['type' => 'kelurahan']) }}",
                    data: {kecamatan:val},
                    success: function(url){
                            $( "#kelurahan" ).empty();
                            $("#kelurahan").html(url);
                    }
                });
            }

            $('#save-btn').click( function () {
                let full_name = $('#full_name').val();
                let provinsi = $('#provinsi').val();
                let kota = $('#kota').val();
                let kecamatan = $('#kecamatan').val();
                let kelurahan = $('#kelurahan').val();
                let alamat = $('#alamat').val();
                let _token = '{{ csrf_token() }}'
                let href = window.location.href;

                if (!full_name || !provinsi || !kota || !kecamatan || !kelurahan || !alamat) {
                    Swal.fire('Belum Lengkap!', 'Periksa kembali data anda, semua kolom harus terisi', 'error');
                    return false;
                }

                Swal.fire('Memproses...');
                swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.region.postAddUserProfile') }}",
                    data: {
                        full_name:full_name,
                        provinsi:provinsi,
                        kota:kota,
                        kecamatan:kecamatan,
                        kelurahan:kelurahan,
                        alamat:alamat,
                        _token:_token,
                        },
                    success: function(response){
                        if(response.success) {
                            Swal.fire(
                                'Berhasil',
                                'Data profil telah disimpan!',
                                'success'
                            )
                            setTimeout(window.location.replace(href), 3000);
                        
                        } else {
                            Swal.fire(
                                'Gagal',
                                response.message,
                                'error'
                            )
                            setTimeout(window.location.replace(href), 3000);
                        }
                    }
                });

            })
</script>
@endif

@endsection