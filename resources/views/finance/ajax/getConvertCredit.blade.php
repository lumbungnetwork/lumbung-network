<div class="p-2 text-left " id="getConvertCredit-modal">
    <h4 class="text-xl font-light text-center">{{$modalTitle}}</h4>
    <form action="{{route($route)}}" method="POST" autocomplete="off" id="conversion-form">
        @csrf
        <p class="mt-4 text-md font-light">Enter amount:</p>

        <div class="mt-2 nm-inset-gray-50 rounded-xl">
            <input type="text" inputmode="numeric" pattern="[0-9]*" placeholder="minimum 2 USD"
                class="p-2 focus:outline-none bg-transparent w-full" name="amount" id="credit-amount">
        </div>
        @if ($type == 2)
        <small class="text-sm font-extralight">Conversion Fee: 1% or minimum $1;</small><br>
        <small id="converted" class="text-sm font-light text-green-500">Converted amount: $0</small><br>
        @endif
        <small class="text-sm font-extralight">Available: ${{number_format($balance)}}</small><br>
        <div class="flex space-x-2 justify-end">
            <button type="button" onclick="swal.close()"
                class=" mt-3 p-3 bg-gray-400 rounded-2xl text-gray-700 text-xs focus:outline-none focus:bg-gray-600">Cancel</button>
            <button type="submit" id="convert-submit" onclick="swal.showLoading()"
                class=" mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Convert</button>

        </div>
    </form>

</div>