<div class="p-0 text-left ">
    <h4 class="text-xl font-light text-center">Upgrade Contract</h4>
    @if ($response == false)
    <div class="mt-4 p-1 ">
        <p class="text-xl text-red-600">Error, can't upgrade contract.</p>
    </div>
    @else
    <div class="mt-4 p-0 ">
        <div class="my-3 nm-inset-gray-50 rounded-2xl p-2">
            <p class="font-extralight">To Upgrade this contract to next Grade, you need to BURN <strong>10 LMB</strong>
            </p>
        </div>
        <img class="w-14 float-right" src="/image/tron-logo.png" alt="tron logo">
        <div class="clear-right"></div>
        <div class="hidden" id="active-info">
            <div class="flex items-center py-2">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                <p class="text-xs font-light ">Active</p>&nbsp;
                <p class="text-xs font-thin " id="showAddress"></p>
            </div>
        </div>
        <div class="" id="inactive-info">
            <div class="flex items-center py-2">
                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                <p class="text-sm font-light ">Inactive</p><br>

            </div>
            <p class="mt-2 text-sm font-light text-red-600">Use Tronlink or Dapp Enabled Browser</p>
        </div>

        <div class="font-light text-sm text-green-600">Available:</div>
        <div class="font-light text-sm text-green-600" id="LMBTRC10balance">0 LMB (TRC10)</div>
        <div class="font-light text-sm text-green-600" id="LMBTRC20balance">0 LMB (TRC20)</div>
        <div class=" p-2 flex justify-between">
            <button type="button" onclick="burn('trc10')"
                class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600"
                id="trc10-btn">TRC10</button>
            <button type="button" onclick="burn('trc20')"
                class="mt-3 p-3 bg-gray-500 rounded-2xl text-white text-xs focus:outline-none focus:bg-gray-600"
                id="trc20-btn">TRC20</button>
        </div>
        <p class="text-sm text-center text-gray-400">Click on the type of LMB you want to burn.</p>

    </div>
    @endif


</div>