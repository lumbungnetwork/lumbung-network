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
                                    <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                    {{  Session::get('message')    }}
                                </div>
                            </div>
                        @endif
                         <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Peringkat</th>
                                        <th>Reward</th>
                                        <th>###</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($getData != null)
                                        <?php $no = 0; ?>
                                        @foreach($getData as $row)
                                            <?php $no++; ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                 <td>{{$row->name}}</td>
                                                <td>{{$row->reward_detail}}</td>
                                                <td>
                                                    <a rel="tooltip"  data-toggle="modal" data-target="#popUp" class="text-primary" href="{{ URL::to('/') }}/ajax/adm/edit/bonus-reward/{{$row->id}}">edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                             <div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
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

@section('javascript')
<script type="text/javascript">
    $("#popUp").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });
</script>
@stop
