@if($getData != null)
    @if($type == 'kota')
        <option value="0">- Pilih Kabupaten/Kota -</option>
        @foreach($getData as $row)
            <option value="{{$row->kode}}">{{$row->nama}}</option>
        @endforeach
    @endif

    @if($type == 'kecamatan')
        <option value="0">- Pilih Kecamatan -</option>
        @foreach($getData as $row)
            <option value="{{$row->kode}}">{{$row->nama}}</option>
        @endforeach
    @endif

    @if($type == 'kelurahan')
        <option value="0">- Pilih Kelurahan -</option>
        @foreach($getData as $row)
            <option value="{{$row->kode}}">{{$row->nama}}</option>
        @endforeach
        @endif
@endif

@if($getData == null)
<option value="">- Tidak Tersedia -</option>
@endif