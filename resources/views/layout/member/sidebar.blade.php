<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li class="has_sub">
                    <a href="{{ URL::to('/') }}/m/dashboard" class="waves-effect @if(Route::currentRouteName() == 'mainDashboard') active @endif">
                        <i class="zmdi zmdi-view-dashboard"></i>
                        <span> Dashboard </span> 
                    </a>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_myProfile' || Route::currentRouteName() == 'm_newProfile' || Route::currentRouteName() == 'm_myBank' || Route::currentRouteName() == 'm_myTron') active @endif">
                        <i class="zmdi zmdi-account-circle"></i> 
                        <span> User Profile</span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_myProfile' || Route::currentRouteName() == 'm_newProfile') class="active" @endif><a href="{{ URL::to('/') }}/m/profile">Profile</a></li>
                        <li @if(Route::currentRouteName() == 'm_myBank') class="active" @endif><a href="{{ URL::to('/') }}/m/bank">Bank</a></li>
                        <li @if(Route::currentRouteName() == 'm_myTron') class="active" @endif><a href="{{ URL::to('/') }}/m/tron">Tron</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_newSponsor' || Route::currentRouteName() == 'm_addPlacement' || Route::currentRouteName() == 'm_statusMember' || Route::currentRouteName() == 'm_newRO') active @endif">
                        <i class="zmdi zmdi-accounts"></i> 
                        <span> Member </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_newSponsor') class="active" @endif><a href="{{ URL::to('/') }}/m/add/sponsor">Daftar</a></li>
                        <li @if(Route::currentRouteName() == 'm_addPlacement') class="active" @endif><a href="{{ URL::to('/') }}/m/add/placement">Placement</a></li>
                        <li @if(Route::currentRouteName() == 'm_newRO') class="active" @endif><a href="{{ URL::to('/') }}/m/add/repeat-order">Subscription</a></li>
                        <li @if(Route::currentRouteName() == 'm_statusMember') class="active" @endif><a href="{{ URL::to('/') }}/m/status/member">Status</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_statusSponsor' || Route::currentRouteName() == 'm_myBinary' || Route::currentRouteName() == 'm_mySponsorTree') active @endif">
                        <i class="zmdi zmdi-device-hub"></i> 
                        <span> Networking </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_statusSponsor') class="active" @endif><a href="{{ URL::to('/') }}/m/status/sponsor">Sponsor</a></li>
                        <li @if(Route::currentRouteName() == 'm_myBinary') class="active" @endif><a href="{{ URL::to('/') }}/m/my/binary">Binary Tree</a></li>
                        <li @if(Route::currentRouteName() == 'm_mySponsorTree') class="active" @endif><a href="{{ URL::to('/') }}/m/my/sponsor-tree">Sponsor Tree</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_newPin' || Route::currentRouteName() == 'm_listTransactions' || Route::currentRouteName() == 'm_addTransferPin' 
                       || Route::currentRouteName() == 'm_myPinStock' || Route::currentRouteName() == 'm_myPinHistory' || Route::currentRouteName() == 'm_addTransaction') active @endif">
                        <i class="zmdi zmdi-archive"></i> 
                        <span> Pin </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_newPin') class="active" @endif><a href="{{ URL::to('/') }}/m/add/pin">Beli</a></li>
                        <li @if(Route::currentRouteName() == 'm_listTransactions' || Route::currentRouteName() == 'm_addTransaction') class="active" @endif><a href="{{ URL::to('/') }}/m/list/transactions">Transaksi</a></li>
                        <li @if(Route::currentRouteName() == 'm_addTransferPin') class="active" @endif><a href="{{ URL::to('/') }}/m/add/transfer-pin">Transfer</a></li>
                        <li @if(Route::currentRouteName() == 'm_myPinStock') class="active" @endif><a href="{{ URL::to('/') }}/m/pin/stock">Stock</a></li>
                        <li @if(Route::currentRouteName() == 'm_myPinHistory') class="active" @endif><a href="{{ URL::to('/') }}/m/pin/history">History</a></li>
                    </ul>
                </li>
                @if($dataUser->is_stockist == 1)
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_PenjualanReward' || Route::currentRouteName() == 'm_MemberStockistReport' || Route::currentRouteName() == 'm_StockistInputPruchase' || Route::currentRouteName() == 'm_StockistMyPruchaseSisa' || Route::currentRouteName() == 'm_StockistListPruchase') active @endif">
                        <i class="zmdi zmdi-shopping-basket"></i> 
                        <span> Stockist </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_StockistInputPruchase') class="active" @endif><a href="{{ URL::to('/') }}/m/purchase/input-stock">Input Stock</a></li>
                        <li @if(Route::currentRouteName() == 'm_StockistListPruchase') class="active" @endif><a href="{{ URL::to('/') }}/m/purchase/list-stock">List Transaksi</a></li>
                        <li @if(Route::currentRouteName() == 'm_StockistMyPruchaseSisa') class="active" @endif><a href="{{ URL::to('/') }}/m/purchase/my-stock">List Stock</a></li>
                        <li @if(Route::currentRouteName() == 'm_MemberStockistReport') class="active" @endif><a href="{{ URL::to('/') }}/m/stockist-report">Report</a></li>
                        <li @if(Route::currentRouteName() == 'm_PenjualanReward') class="active" @endif><a href="{{ URL::to('/') }}/m/stockist/penjualan-reward">Reward Penjualan</a></li>
                    </ul>
                </li>
                @endif
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_SearchStockist' || Route::currentRouteName() == 'm_BelanjaReward' || Route::currentRouteName() == 'm_historyShoping' ) active @endif">    
                        <i class="zmdi zmdi-shopping-cart"></i> 
                        <span> Belanja </span><span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_SearchStockist') class="active" @endif><a href="{{ URL::to('/') }}/m/search/stockist">Stockist</a></li>
                        <li @if(Route::currentRouteName() == 'm_historyShoping') class="active" @endif><a href="{{ URL::to('/') }}/m/history/shoping">History</a></li>
                        <li @if(Route::currentRouteName() == 'm_BelanjaReward') class="active" @endif><a href="{{ URL::to('/') }}/m/belanja-reward">Reward Belanja</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_myBonusSummary' || Route::currentRouteName() == 'm_myBonusSponsor' || Route::currentRouteName() == 'm_myBonusBinary' || Route::currentRouteName() == 'm_requestClaimReward' || Route::currentRouteName() == 'm_historyReward') active @endif">
                        <i class="zmdi zmdi-card-giftcard"></i> 
                        <span> Bonus </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_myBonusSummary') class="active" @endif><a href="{{ URL::to('/') }}/m/summary/bonus">Ringkasan</a></li>
                        <li @if(Route::currentRouteName() == 'm_myBonusSponsor') class="active" @endif><a href="{{ URL::to('/') }}/m/sponsor/bonus">Sponsor</a></li>
                        <li @if(Route::currentRouteName() == 'm_myBonusBinary') class="active" @endif><a href="{{ URL::to('/') }}/m/binary/bonus">Binary</a></li>
                        <li @if(Route::currentRouteName() == 'm_requestClaimReward') class="active" @endif><a href="{{ URL::to('/') }}/m/req/claim-reward">Reward</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_myBonusSaldo' || Route::currentRouteName() == 'm_requestWDeIDR') active @endif">
                        <i class="zmdi zmdi-money-box"></i> 
                        <span> Saldo </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_myBonusSaldo') class="active" @endif><a href="{{ URL::to('/') }}/m/saldo/bonus">Bonus</a></li>
                        <li @if(Route::currentRouteName() == 'm_requestWDeIDR') class="active" @endif><a href="{{ URL::to('/') }}/m/req/wd-eidr">eIDR</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_requestWD' || Route::currentRouteName() == 'm_historyWD' || Route::currentRouteName() == 'm_requestWDRoyalti') active @endif">
                        <i class="zmdi zmdi-money"></i> 
                        <span> Withdrawal </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_historyWD') class="active" @endif><a href="{{ URL::to('/') }}/m/history/wd">History WD</a></li>
                        <li @if(Route::currentRouteName() == 'm_requestWD') class="active" @endif><a href="{{ URL::to('/') }}/m/req/wd">Request WD</a></li>
                        <li @if(Route::currentRouteName() == 'm_requestWDRoyalti') class="active" @endif><a href="{{ URL::to('/') }}/m/req/wd-royalti">Request WD Royalti</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect @if(Route::currentRouteName() == 'm_ExplorerStatistic') active @endif">
                        <i class="zmdi zmdi-bookmark"></i> 
                        <span> Explorer </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li @if(Route::currentRouteName() == 'm_ExplorerStatistic') class="active" @endif><a href="{{ URL::to('/') }}/m/explorer/statistic">Statistik</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="{{ URL::to('/') }}/user_logout" class="waves-effect">
                        <i class="zmdi zmdi-power text-danger"></i>
                        <span> Logout </span> 
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>