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
                            <table class="table table-striped nowrap" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>UserID</th>
                                        <th>No. HP</th>
                                        <th>Jml.Direct Sponsor</th>
                                        <th>Total Bonus (Rp.)</th>
                                        <th>Bank</th>
                                        <th>No. Rek</th>
                                        <!--<th>###</th>-->
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
                                        ?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->username}}</td>
                                        <td>{{$row->hp}}</td>
                                        <td>{{$row->total_sponsor}}</td>
                                        <td>{{number_format($row->total_bonus, 0, ',', ',')}}</td>
                                        <td>{{$row->bank_name}}</td>
                                        <td>{{$row->account_no}}</td>
                                        <!--<td></td>-->
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="modal fade" id="popUp" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        $('#myTable').DataTable( {
                dom: 'Bfrtip',
                "deferRender": true,
                columnDefs: [
                    { orderable: false, targets: -1 }
                 ],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'export_bonus_xls' ,
                   }
                ],
                searching: false,
                 pagingType: "full_numbers",
                 "paging":   true,
                 "info":     false,
                 "ordering": true
        } );
    } );
</script>
@stop