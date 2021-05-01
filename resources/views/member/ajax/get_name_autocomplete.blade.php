@if($getData != null)
@foreach($getData as $row)
<li><a onClick="selectUsername('{{$row->id}}____{{$row->username}} ');" class="dropdown-item" value="{{$row->id}}"
        style="cursor: pointer;">{{$row->username}}</a></li>
@endforeach
@endif