<form class="login100-form validate-form" method="post" action="/adm/change/data/member">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$headerTitle}}</h5>
    </div>
    <div class="modal-body">
        {{ csrf_field() }}
        @if($getData != null)
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" value="{{$getData->email}}" required="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>UserID</label>
                    <input type="text" class="form-control" name="user_code" value="{{$getData->user_code}}"
                        required="">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>Handphone</label>
                    <input type="text" name="hp" class="form-control" value="{{$getData->hp}}" required="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="full_name" value="{{$getData->full_name}}">
                </div>
            </div>
        </div>
        @php
        $affiliateArray = array(
        'Tidak ada',
        'KBB',
        'KBB-Pasif',
        'KBB-Aktif'
        );

        $no = 0;
        @endphp
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Gender</label>
                    <select class="form-control" name="gender">
                        @if($getData->gender == 1)
                        <option value="1" selected>Pria</option>
                        <option value="2">Wanita</option>
                        @else
                        <option value="1">Pria</option>
                        <option value="2" selected>Wanita</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Afiliasi</label>
                    <select class="form-control" name="affiliate">
                        @foreach ($affiliateArray as $affiliate)
                        @if($getData->affiliate == $no)
                        <option value="{{$no}}" selected>{{$affiliate}}</option>
                        @else
                        <option value="{{$no}}">{{$affiliate}}</option>
                        @endif
                        @php
                        $no++
                        @endphp
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <input type="hidden" name="cekId" value="{{$getData->id}}">
        @else
        Tidak ada data
        @endif
    </div>

    <div class="modal-footer">
        <div class="left-side">
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        <div class="divider"></div>
        @if($getData != null)
        <div class="right-side">
            <button type="submit" class="btn btn-info btn-link">Confirm</button>
        </div>
        @endif
    </div>
</form>
