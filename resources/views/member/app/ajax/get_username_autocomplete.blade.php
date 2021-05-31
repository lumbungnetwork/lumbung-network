@if($usernames != null)
@foreach($usernames as $row)
<li><a onClick="selectUsername('{{$row->id}}___{{$row->username}}');" class="dropdown-item" value="{{$row->id}}"
        style="cursor: pointer;">{{$row->username}}</a></li>
@endforeach
@endif