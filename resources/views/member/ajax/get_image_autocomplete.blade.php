@if($productImages != null)
@foreach($productImages as $image)
<li><a onClick="selectName('{{$image->name}} ');" class="dropdown-item" value="{{$image->name}}"
        style="cursor: pointer;">{{$image->name}}</a></li>
@endforeach
@endif
