<div class="p-2 text-left " id="getUSDTWithdraw-modal">
    <h4 class="text-xl font-light text-center">{{$modalTitle}}</h4>
    <form action="{{route($route)}}" method="POST" autocomplete="off" id="withdraw-form">
        @csrf
        <p class="mt-4 text-md font-light">Withdraw destination (TRC20):</p>

        <p class="text-sm font-medium">{{$tron}}</p>

        <p class="mt-4 text-md font-light">Enter amount:</p>

        <div class="my-2 nm-inset-gray-50 rounded-xl">
            <input type="text" inputmode="numeric" pattern="[0-9]*" placeholder="minimum 2 USD"
                class="p-2 focus:outline-none bg-transparent w-full" name="amount">
        </div>
        <p class="mt-4 text-md font-light">Confirm your password:</p>

        <div class="my-2 nm-inset-gray-50 rounded-xl">
            <input type="password" placeholder="your password" class="p-2 focus:outline-none bg-transparent w-full"
                name="password">
        </div>
        <small class="text-sm font-extralight">Transfer Fee: $1 (flat)</small><br>
        <small class="text-sm font-extralight">Available: ${{number_format($balance)}}</small><br>
        <div class="flex space-x-2 justify-end">
            <button type="button" onclick="swal.close()"
                class=" mt-3 p-3 bg-gray-400 rounded-2xl text-gray-700 text-xs focus:outline-none focus:bg-gray-600">Cancel</button>
            <button type="submit" id="convert-submit" onclick="swal.showLoading()"
                class=" mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600">Withdraw</button>

        </div>
    </form>

</div>