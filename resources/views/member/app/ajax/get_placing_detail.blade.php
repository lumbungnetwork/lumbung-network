<div class="-mx-2">
    <div class="my-1">
        <h5 class="text-xl text-gray-600 font-medium">Placing Detail</h5>
    </div>

    @if ($status)
    @if (count($downlines) > 0)
    <div class="my-1">
        <p class="text-sm text-gray-600 font-light">Silakan pilih downline yang hendak di-placement:</p>
    </div>
    <div class="mt-2">
        <form action="{{ route('member.network.postBinaryPlacement') }}" method="POST">
            @csrf
            <input type="hidden" name="upline_id" value="{{ $upline_id }}">
            <input type="hidden" name="position" value="{{ $position }}">
            <div class="nm-inset-gray-100 rounded-lg p-2">
                <select name="downline_id" class="bg-transparent ml-1 focus:outline-none w-full text-sm text-gray-700">
                    @foreach ($downlines as $downline)
                    <option value="{{ $downline->id }}">{{ $downline->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-3 flex justify-end space-x-2">
                <button type="button"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out"
                    onclick="Swal.close()">Batal</button>
                <button type="submit" onclick="Swal.showLoading()"
                    class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-green-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">
                    Konfirmasi &#10003;
                </button>
            </div>

        </form>



    </div>
    @else
    <div class="my-1">
        <h5 class="text-xl text-red-600 text-center font-medium">Tidak ada downline yang perlu di-placement saat ini!
        </h5>

    </div>
    <div class="my-1 flex justify-end">
        <button
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out"
            onclick="Swal.close()">Tutup</button>
    </div>
    @endif

    @else


    <div class="my-1">
        <h5 class="text-xl text-red-600 text-center font-medium">Ada yang salah dari proses placing!</h5>

    </div>
    <div class="my-1 flex justify-end">
        <button
            class="rounded-lg py-1 px-2 h-8 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out"
            onclick="Swal.close()">Tutup</button>
    </div>
    @endif
</div>