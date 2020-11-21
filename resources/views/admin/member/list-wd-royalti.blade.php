@extends('layout.admin.main')
@section('content')
@include('layout.admin.sidebar')
<div class="main-panel">

    <?php //MENU HEADER  ?>
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <p class="navbar-brand">{{$headerTitle}}</p>
            </div>
        </div>
    </nav>

    <?php //MENU CONTENT  ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">List</h5>
                    </div>
                    <div class="card-body">
                        @if ( Session::has('message') )
                        <div class="widget-content mt10 mb10 mr15">
                            <div class="alert alert-{{ Session::get('messageclass') }}">
                                <button class="close" type="button" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span></button>
                                {{  Session::get('message')    }}
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <form method="post" name="emailCompose" id="emailCompose" action="/adm/check/wd-royalti">
                                {{ csrf_field() }}
                                <p class="form-group">
                                    <button type="submit" class="btn btn-primary" id="formCheck">Submit
                                        Transfer</button>
                                </p>
                                <table class="table table-striped nowrap" id="myTable">
                                    <thead class=" text-primary">
                                        <tr>
                                            <th><input type="checkbox" name="select_all" value="1"
                                                    id="example-select-all"></th>
                                            <th>No</th>
                                            <th>UserID</th>
                                            <th>Bank</th>
                                            <th>No. Rek / Alamat TRON</th>
                                            <th>Nama. Rek</th>
                                            <th>Tgl. WD</th>
                                            <th>Jml. WD (Rp.)</th>
                                            <th>Admin Fee (Rp.)</th>
                                            <th>Jml. Transfer (Rp.)</th>
                                            <th>###</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($getData != null)
                                        <?php
                                        $no = 0;
                                        ?>
                                        @foreach($getData as $row)
                                        <?php
                                            $no++;
                                            $jmlWD = $row->wd_total + $row->admin_fee;
                                        ?>
                                        @if ($row->is_tron == 0)
                                        <tr>
                                            <td><input type="checkbox" name="id[]" value="{{$row->id}}"></td>
                                            <td>{{$no}}</td>
                                            <td>{{$row->user_code}}</td>
                                            <td>{{$row->bank_name}}</td>
                                            <td>{{$row->account_no}}</td>
                                            <td>{{$row->account_name}}</td>
                                            <td>{{date('d M Y', strtotime($row->wd_date))}}</td>
                                            <td>{{number_format($jmlWD, 0, ',', ',')}}</td>
                                            <td>{{number_format($row->admin_fee, 0, ',', ',')}}</td>
                                            <td>{{number_format($row->wd_total, 0, ',', ',')}}</td>
                                            <td>
                                                <a rel="tooltip" data-toggle="modal" data-target="#popUp"
                                                    class="text-danger"
                                                    href="{{ URL::to('/') }}/ajax/adm/cek/reject-wd-royalti/{{$row->id}}">reject</a>
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($row->is_tron == 1)
                                        <tr>
                                            <td><input type="checkbox" name="id[]" value="{{$row->id}}"></td>
                                            <td>{{$no}}</td>
                                            <td>{{$row->user_code}}</td>
                                            <td>WD by eIDR</td>
                                            <td>{{$row->tron}}</td>
                                            <td>TRON</td>
                                            <td>{{date('d M Y', strtotime($row->wd_date))}}</td>
                                            <td>{{number_format($jmlWD, 0, ',', ',')}}</td>
                                            <td>{{number_format($row->admin_fee, 0, ',', ',')}}</td>
                                            <td>{{number_format($row->wd_total, 0, ',', ',')}}</td>
                                            <td>
                                                <a rel="tooltip" data-toggle="modal" data-target="#popUp"
                                                    class="text-danger"
                                                    href="{{ URL::to('/') }}/ajax/adm/cek/reject-wd-royalti/{{$row->id}}">reject</a>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-backdrop="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>
@stop


@section('styles')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
@stop

@section('javascript')
<script type="text/javascript" language="javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/buttons.html5.min.js') }}"></script>

<script type="text/javascript">
    $("#popUp").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
    $(document).ready(function() {
        var myTableRow =  $('#myTable').DataTable( {
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                dom: 'Bfrtip',
                "deferRender": true,
                columnDefs: [{
                    orderable: false,
                    targets: 0,
                }],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'export_xls' ,
                   }
                ],
                searching: false,
                 pagingType: "full_numbers",
                 "paging":   true,
                 "info":     false,
                 "ordering": true,
        } );

        $('#myTable #example-select-all').change(function() {
                var checked = $(this).is(":checked");
                $("input", myTableRow.rows({search:'applied'}).nodes()).each(function(){
                        if(checked){
                                $(this).attr("checked", true);
                        }
                        else {
                                $(this).attr("checked", false);
                        }
                });
        });
        $("form").submit(function() {
                $(myTableRow.rows({search:'applied'}).nodes()).find('input[type="checkbox"]:checked');
        });
    } );
</script>
@stop
