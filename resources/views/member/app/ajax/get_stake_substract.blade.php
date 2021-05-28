<div class="m-0 p-0 text-left">
    <h4 class="my-2 text-lg font-light text-gray-600 text-center">Unstake LMB</h4>
    <p class="text-xs text-gray-500 font-medium">Staked LMB: {{$max}}</p>
    <div class="nm-inset-gray-200 py-1 rounded-2xl w-full flex justify-between">
        <input type="text" class="bg-transparent focus:outline-none ml-2 w-8/12" name="outputLMB" id="outputLMB">
        <button onclick="outputMax()"
            class="rounded-full mr-0 py-1 px-2 bg-gradient-to-br from-yellow-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Max</button>

    </div>
    <div class="mt-1 flex justify-end">
        <button id="stake-substract-btn"
            class="rounded-lg py-1 px-2 bg-gradient-to-br from-red-400 to-purple-300 text-xs font-medium text-gray-700 outline-none focus:outline-none hover:shadow-lg hover:from-green-200 transition duration-200 ease-in-out">Unstake</button>
    </div>
</div>