@if($getData != null)
@foreach($getData as $row)
<li><a onClick="selectUsername('{{$row->seller_id}}___{{$row->shop_name}}');" class="dropdown-item"
        value="{{$row->seller_id}}" style="cursor: pointer;">{{$row->shop_name}}</a></li>
@endforeach
@endif
