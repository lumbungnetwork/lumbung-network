<div class="p-2 text-left " id="getCreditTransfer-modal">
    <h4 class="text-xl font-light text-center">{{$modalTitle}}</h4>
    <form action="{{route($route)}}" method="POST" autocomplete="off" id="transfer-form">
        @csrf
        <p class="mt-4 text-md font-light">Enter receiver's username:</p>

        <div class="my-2 nm-inset-gray-50 rounded-xl">
            <input type="text" class="p-2 focus:outline-none bg-transparent w-full" name="receiver">
        </div>

        <p class="mt-4 text-md font-light">Enter amount:</p>

        <div class="my-2 nm-inset-gray-50 rounded-xl">
            <input type="text" inputmode="decimal" pattern="^[0-9]\d{0,9}(\.\d{1,3})?%?$"
                class="p-2 focus:outline-none bg-transparent w-full" name="amount">
        </div>
        <small id="totalDebit" class="text-sm font-extralight" hidden>Total Debit: $0</small><br>
        <p class="mt-4 text-md font-light">Confirm your password:</p>

        <div class="my-2 nm-inset-gray-50 rounded-xl">
            <input type="password" placeholder="your password" class="p-2 focus:outline-none bg-transparent w-full"
                name="password">
        </div>
        <small class="text-sm font-extralight">Transfer Fee: $0.3 (flat)</small><br>
        <small class="text-sm font-extralight">Available for transfer: ${{number_format($balance - 0.3, 2)}}</small><br>
        <div class="flex space-x-2 justify-end">
            <button type="button" onclick="swal.close()"
                class=" mt-3 p-3 bg-gray-400 rounded-2xl text-gray-700 text-xs focus:outline-none focus:bg-gray-600">Cancel</button>
            <button type="submit" id="convert-submit" onclick="swal.showLoading()"
                class=" mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Transfer</button>

        </div>
    </form>

</div>