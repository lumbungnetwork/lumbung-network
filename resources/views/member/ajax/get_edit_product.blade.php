<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-edit" method="POST" action="/m/edit/product">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" name="name" class="form-control" id="name" value="{{$product->name}}"
                    autocomplete="off">
            </div>
            <div class="form-group">
                <label for="size">Ukuran</label>
                <input type="text" name="size" class="form-control" id="size" value="{{$product->size}}"
                    autocomplete="off">
            </div>
            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input inputmode="numeric" name="price" pattern="[0-9]*" type="text" class="form-control" id="price"
                    value="{{$product->price}}" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select class="form-control" name="category_id" id="category_id">
                    @foreach ($categories as $category)
                    @if ($category->id == $product->category_id)
                    <option value="{{$category->id}}" selected>{{$category->name}}</option>
                    @else
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endif
                    @endforeach

                </select>
            </div>
            <div class="form-group">
                <label for="desc">Deskripsi Produk (Opsional)</label>
                <textarea rows="2" class="form-control" name="desc" id="desc"
                    autocomplete="off">{{$product->desc}}</textarea>
            </div>
            <fieldset class="form-group">
                <label for="user_name">Gambar Produk</label><br>
                <small><em>Ketikkan 3-4 huruf awal, lalu pilih opsi yang tampil</em></small>
                <input type="text" class="form-control mb-0" id="get_name_edit" name="image" autocomplete="off"
                    value="{{$product->image}}">
                <ul class="typeahead dropdown-menu"
                    style="max-height: 120px; overflow: auto;border: 1px solid #ddd;width: 96%;margin-left: 11px; margin-top: -185px;"
                    id="get_id-box_edit">
                </ul>
            </fieldset>
            <div class="row py-2">
                <div class="col-6" id="product-image-edit">
                    <img src="{{ asset('/storage/products') }}/{{$product->image}}" style="max-width: 100px;" alt="">
                </div>
                <div class="col-6">
                    <a class="text-info" href="#">Request Gambar</a>
                </div>
                <div class="col-6">
                    <label for="qty">Stock</label>
                    <input inputmode="numeric" name="qty" pattern="[0-9]*" type="text" class="form-control" id="qty"
                        value="{{$product->qty}}" autocomplete="off">
                </div>
                <div class="col-6">
                    <!-- add class p-switch -->
                    <div style="font-size: 18px; margin-top: 40px;" class="pretty p-switch p-fill">
                        <input type="checkbox" id="is_active_switch">
                        <input type="hidden" id="is_active" name="is_active" value="{{$product->is_active}}">
                        <div class="state p-success p-on">
                            <label id="is_active_status">Aktif</label>
                        </div>
                    </div>

                </div>
            </div>

            <input type="hidden" value="{{$product->seller_id}}" name="seller_id">
            <input type="hidden" value="{{$product->id}}" name="product_id">

        </form>
    </div>
    <div class="modal-footer">
        <form id="form-delete" method="POST" action="/m/delete/product">
            {{ csrf_field() }}
            <input type="hidden" value="{{$product->id}}" name="product_id">
            <button type="button" class="btn btn-danger" onclick="deleteProduct()">Hapus Produk</button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="confirmEditProduct()">Ubah Produk</button>
    </div>
</div>
