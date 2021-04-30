@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                        <i class="fa fa-arrow-left"></i> Beranda
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn  btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">

                <div class="rounded-lg bg-white p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box tilebox-one">
                                <h6 class="text-muted m-b-20">Kalkulasi Royalti</h6>
                            </div>
                        </div>
                    </div>
                    <br>
                    <a class="float-right text-info" href="{{ route('m_requestWDRoyalti') }}">Legacy
                        Royalty</a>
                    <div class="mt-3 clearfix"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form class="login100-form validate-form" method="post"
                                        action="/m/royalti-calculation">
                                        @csrf
                                        <div class="row">
                                            <div class="col-6">
                                                <fieldset>
                                                    <select class="form-control" name="month" id="bank_name">
                                                        <option value="none">- Bulan -</option>
                                                        <option value="01">Januari</option>
                                                        <option value="02">Februari</option>
                                                        <option value="03">Maret</option>
                                                        <option value="04">April</option>
                                                        <option value="05">Mei</option>
                                                        <option value="06">Juni</option>
                                                        <option value="07">Juli</option>
                                                        <option value="08">Agustus</option>
                                                        <option value="09">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-6">
                                                <fieldset>
                                                    <select class="form-control" name="year" id="bank_name">
                                                        <option value="none">- Tahun -</option>
                                                        <option value="2019">2019</option>
                                                        <option value="2020">2020</option>
                                                        <option value="2021">2021</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <fieldset>
                                                    <button type="submit"
                                                        class="form-control btn btn-success">Cek</button>
                                                </fieldset>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2 mb-3">
                            @if($getData!= null)
                            <div class="row">
                                <div class="col-12 mt-3 mb-2">
                                    <h6 class="text-center text-muted">
                                        {{date('M-Y', strtotime($date . '-01'))}}</h6>
                                </div>

                                <div class="col-6">
                                    <div class="rounded-lg shadow bg-white p-2">
                                        <dd>Downline Belanja</dd>
                                        <h6 class="text-warning">{{number_format(count($getData))}}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-lg shadow bg-white p-2">
                                        <dd>Total Bonus</dd>
                                        <h6 class="text-warning">{{number_format(count($getData))}} LMB</h6>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="table-responsive mt-3">

                                <table id="dataTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Downline</th>
                                            <th>Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($getData != null)
                                        <?php $no = 0; ?>
                                        @foreach($getData as $row)
                                        <?php $no++;?>
                                        <tr>
                                            <td>{{$row->user_code}}</td>
                                            <td>{{$row->level_id}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>
</div>

@stop

@section('styles')
<link href="{{ asset('asset_member/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>
<script src="/asset_member/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/asset_member/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTable').DataTable( {
            lengthChange: false,
            dom: 'Bfrtip',
            "deferRender": true,
            order: [[1, 'asc']],
        })
    });
</script>
@stop