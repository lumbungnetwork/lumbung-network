@extends('member.components.main')
@section('content')

{{-- Top bar --}}
@include('member.components.topbar')

{{-- Content wrapper --}}
<div class="max-w-xs mx-auto">
    <div class="mt-4 px-4">
        <h2 class="text-lg text-gray-600">Pencarian untuk: <span class="italic">{{ $keyword }}</span></h2>
    </div>

    {{-- Filter --}}
    <div class="p-4">
        <div class="mt-4 nm-concave-gray-100 rounded-lg p-2">
            <div class="flex justify-between px-2">
                <p class="text-sm font-medium">Saring Pencarian</p>
                <form action="{{ route('member.shopping.postSearchProduct') }}" method="POST" id="filter-form">
                    @csrf
                    <input type="hidden" value="{{ $keyword }}" name="keyword">
                    <select onchange="filterProduct()" id="filter-select" name="filter"
                        class="text-xs bg-transparent focus:outline-none">
                        <option value="0" {{ (!$filter ? 'selected' : '') }}>Filter</option>
                        @if ($user->kota)
                        <option value="provinsi" {{ ($filter == 'provinsi' ? 'selected' : '') }}>Satu Provinsi</option>
                        <option value="kota" {{ ($filter == 'kota' ? 'selected' : '') }}>Satu Kota</option>
                        @endif
                        <option value="desc" {{ ($filter == 'desc' ? 'selected' : '') }}>Harga Tertinggi</option>
                        <option value="asc" {{ ($filter == 'asc' ? 'selected' : '') }}>Harga Terendah</option>
                    </select>
                </form>
                
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="px-1">
        <div id="products" class="flex flex-wrap justify-between w-full p-0">
            @if(count($products) > 0)
            @foreach ($products as $product)
            <div class="nm-flat-gray-50 rounded-xl p-0 mx-1 my-2" onclick="showDetail({{$product->id}})"
                style="max-width: 46%">
                <div>
                    <img class="object-cover rounded-tl-2xl rounded-tr-2xl"
                        src="{{ asset('/storage/products') }}/{{$product->image}}" alt="product-picture">
                </div>
                <div class="mt-1 py-1 text-center">
                    <h4 class="text-xs font-medium text-gray-700">{{$product->name}}</h4>
                    <p class="font-light text-gray-600" style="font-size: 12px;">{{$product->size}}</p>
                    <p class="text-xs text-black">Rp{{number_format($product->price)}}</p>
                    <p class="text-xs text-gray-600 font-extralight">Stok: <b>{{$product->qty}}</b></p>
                    <hr class="border border-gray-300">
                    <p class="text-xs text-gray-500 font-light">{{$product->seller->provinsi}}</p>
                    <p class="text-xs text-gray-600 font-light"><b>{{$product->seller->kota}}</b></p>
                </div>
            </div>
            @endforeach
            @else
            <div class="flex flex-col justify-center text-center px-4 py-2">
                <h6>Tidak ditemukan produk dengan kata kunci ini.</h6>
                <button onclick="history.back()"
                        class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Kembali</button>
            </div>
            @endif
        </div>
    </div>


</div>

@include('member.components.mobile_sticky_nav')

@endsection


@section('scripts')
<script type="text/javascript">
    let _token = '{{ csrf_token() }}';
    let keyword = '{{ $keyword }}';

    function showDetail(product_id) {
        $.ajax({
            type: "GET",
            url: "{{ route('ajax.shopping.getProductById') }}",
            data: {
                product_id:product_id,
                search:true
            },
            success: function(url){
                Swal.fire({
                    html: url,
                    showCancelButton: false,
                    showConfirmButton: false,
                    focusConfirm: false
                });
                
            }
        })
    }function filterProduct() {
        if ($('#filter-select').val() == 0) {
            return false;
        }
        Swal.fire('Memproses Filter');
        Swal.showLoading();
        $('#filter-form').submit();

    }




</script>
@endsection