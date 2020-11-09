<ul class="nav justify-content-center fixed-bottom bg-white shadow w-100 fmenu">
    <li class="nav-item text-center">
        <a class="nav-link @if(Route::currentRouteName() == 'mainDashboard') active @endif" href="{{ URL::to('/') }}/m/dashboard">
            <i class="fa fa-home icon-bottom w-100"></i>
            <span>Beranda</span>
        </a>
    </li>
    <li class="nav-item text-center">
        <a class="nav-link @if(Route::currentRouteName() == 'mainNetworking') active @endif" href="{{ URL::to('/') }}/m/networking">
            <i class="fa fa-users icon-bottom w-100"></i>
            <span>Network</span>
        </a>
    </li>
    <li class="nav-item text-center">
        <a class="nav-link @if(Route::currentRouteName() == 'mainWallet' || Route::currentRouteName() == 'm_requestWD' || Route::currentRouteName() == 'm_historyWD' || Route::currentRouteName() == 'm_historyWDeIDR' || Route::currentRouteName() == 'm_myBonusSponsor' || Route::currentRouteName() == 'm_myBonusBinary') active @endif" href="{{ URL::to('/') }}/m/wallet">
            <i class="fa fa-wallet icon-bottom w-100"></i>
            <span>Dompet</span>
        </a>
    </li>
    <li class="nav-item text-center">
        <a class="nav-link @if(Route::currentRouteName() == 'mainExplorer' || Route::currentRouteName() == 'm_ExplorerStatistic' || Route::currentRouteName() == 'm_ExplorerUser') active @endif" href="{{ URL::to('/') }}/m/explorers">
            <i class="fa fa-search icon-bottom w-100"></i>
            <span>Explorer</span>
        </a>
    </li>
    <li class="nav-item text-center">
        <a class="nav-link @if(Route::currentRouteName() == 'mainMyAccount') active @endif" href="{{ URL::to('/') }}/m/my-account">
            <i class="fa fa-user icon-bottom w-100"></i>
            <span>Akun</span>
        </a>
    </li>
</ul>
