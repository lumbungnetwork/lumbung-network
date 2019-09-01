<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li class="has_sub">
                    <a href="{{ URL::to('/') }}/m/dashboard" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i><span> Dashboard </span> </a>
                </li>
                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-account-circle"></i> <span> User Profile</span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL::to('/') }}/m/profile">Profile</a></li>
                        <li><a href="{{ URL::to('/') }}/m/bank">Bank</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-accounts"></i> <span> Member </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL::to('/') }}/m/add/sponsor">Daftar</a></li>
                        <li><a href="{{ URL::to('/') }}/m/add/placement">Placement</a></li>
                        <li><a href="{{ URL::to('/') }}/m/status/member">Status</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-device-hub"></i> <span> Networking </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL::to('/') }}/m/status/sponsor">Sponsor</a></li>
                        <li><a href="{{ URL::to('/') }}/m/my/binary">Binary</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-archive"></i> <span> Pin </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL::to('/') }}/m/add/pin">Beli</a></li>
                        <li><a href="{{ URL::to('/') }}/m/list/transactions">Transaksi</a></li>
                        <li><a href="{{ URL::to('/') }}/m/add/transfer-pin">Transfer</a></li>
                        <li><a href="{{ URL::to('/') }}/m/pin/stock">Stock</a></li>
                        <li><a href="{{ URL::to('/') }}/m/pin/history">History</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-money-box"></i> <span> Bonus </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL::to('/') }}/m/summary/bonus">Ringkasan</a></li>
                        <li><a href="{{ URL::to('/') }}/m/sponsor/bonus">Sponsor</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-money-box"></i> <span> Saldo </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL::to('/') }}/m/saldo/bonus">Bonus</a></li>
                        <li><a href="#">eIDR</a></li>
                    </ul>
                </li>
                
<!--                <li class="has_sub">
                    <a class="waves-effect"><i class="zmdi zmdi-comment-alt-text"></i> <span> News </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="#">Berita</a></li>
                    </ul>
                </li>-->
                <li class="has_sub">
                    <a href="{{ URL::to('/') }}/user_logout" class="waves-effect"><i class="zmdi zmdi-power text-danger"></i><span> Logout </span> </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>