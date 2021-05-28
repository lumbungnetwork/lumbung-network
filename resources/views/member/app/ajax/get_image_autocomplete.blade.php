@if($productImages != null)
@foreach($productImages as $image)
<li><a onClick="selectName('{{$image->image}} ');" class="dropdown-item" value="{{$image->image}}"
                style="cursor: pointer;">{{$image->image}}</a></li>
@endforeach
@endif