@if($getData != null)
        @foreach($getData as $row)
            <li><a onClick="selectUsername('{{$row->id}}____{{$row->user_code}} ');" class="dropdown-item" value="{{$row->id}}" style="cursor: pointer;">{{$row->user_code}}</a></li>
        @endforeach
@endif