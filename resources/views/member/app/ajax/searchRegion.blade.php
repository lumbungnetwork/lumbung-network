@if($data != null)
@if($type == 'kota')
<option value="0">- Pilih Kabupaten/Kota -</option>
@foreach($data as $row)
<option value="{{$row->id_kab}}">{{$row->nama}}</option>
@endforeach
@endif

@if($type == 'kecamatan')
<option value="0">- Pilih Kecamatan -</option>
@foreach($data as $row)
<option value="{{$row->id_kec}}">{{$row->nama}}</option>
@endforeach
@endif

@if($type == 'kelurahan')
<option value="0">- Pilih Kelurahan -</option>
@foreach($data as $row)
<option value="{{$row->id_kel}}">{{$row->nama}}</option>
@endforeach
@endif
@endif

@if($data == null)
<option value="">- Tidak Tersedia -</option>
@endif