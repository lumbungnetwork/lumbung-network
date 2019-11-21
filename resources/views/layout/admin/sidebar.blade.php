<?php //SIDEBAR KIRI ?>
<div class="sidebar" data-color="brown" data-active-color="danger">
    <div class="logo">
        <a href="{{ URL::to('/') }}/adm/dashboard" class="simple-text logo-normal">
        &nbsp;&nbsp;
        Lumbung Admin
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            
            @if($dataUser->user_type == 3 || $dataUser->user_type == 2 || $dataUser->user_type == 1)
            <li>
                <a href="{{ URL::to('/') }}/adm/add-admin">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>Admin</p>
                </a>
            </li>
            
            <li>
                <a data-toggle="collapse" href="#pageLaporanBonus" class="collapsed" aria-expanded="false">
                    <i class="nc-icon nc-money-coins"></i>
                    <p>Laporan Bonus  <b class="caret"></b></p>
                </a>
                <div class="collapse" id="pageLaporanBonus" style="">
                    <ul class="nav">
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/bonus-sp">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Total Bonus Sponsor </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#pageLaporanWD" class="collapsed" aria-expanded="false">
                    <i class="nc-icon nc-money-coins"></i>
                    <p>Withdrawal  <b class="caret"></b></p>
                </a>
                <div class="collapse" id="pageLaporanWD" style="">
                    <ul class="nav">
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/wd">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Request Withdrawal </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/wd-eidr">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Request Konversi eIDR </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/claim-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Claim Reward </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/belanja-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Claim Belanja Reward </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/penjualan-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Claim Penjualan Reward </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/wd-royalti">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Request Withdrawal Royalti </span>
                            </a>
                         </li>
                         <li>
                            <a href="{{ URL::to('/') }}/adm/history/wd">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> History Withdrawal </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/history/wd-eidr">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> History Konversi eIDR </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/history/claim-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> History Claim Reward </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/history/belanja-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> History Belanja Reward </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/history/penjualan-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> History Penjualan Reward </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/history/wd-royalti">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> History Withdrawal Royalti</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#pagePin" class="collapsed" aria-expanded="false">
                    <i class="nc-icon nc-bank"></i>
                    <p>Pin  <b class="caret"></b></p>
                </a>
                <div class="collapse" id="pagePin" style="">
                    <ul class="nav">
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/transactions">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Data Order Pin </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#pageMember" class="collapsed" aria-expanded="false">
                    <i class="nc-icon nc-single-02"></i>
                    <p>Member  <b class="caret"></b></p>
                </a>
                <div class="collapse" id="pageMember" style="">
                    <ul class="nav">
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/member">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> List Member </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/purchases">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> List Product </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/req-stockist">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> List Pengajuan Stockist </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/req-input-stock">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> List Stockist Input Stock </span>
                            </a>
                        </li>
                        <?php
                        /*
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/confirm-belanja">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> List Konfirmasi Belanja </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/list/verification-royalti">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> List Verifikasi Royalti </span>
                            </a>
                        </li>
                         */
                        ?>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#pagesExamples" class="collapsed" aria-expanded="false">
                    <i class="nc-icon nc-settings-gear-65"></i>
                    <p>Setting  <b class="caret"></b></p>
                </a>
                <div class="collapse" id="pagesExamples" style="">
                    <ul class="nav">
                        <li>
                            <a href="{{ URL::to('/') }}/adm/add/pin-setting">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Pin </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/packages">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Package </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/bank">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Bank Perusahaan </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/bonus-start">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Bonus Start </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/') }}/adm/bonus-reward">
                            <span class="sidebar-mini-icon">+</span>
                            <span class="sidebar-normal"> Bonus Reward </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            
            <li>
                <a href="{{ URL::to('/') }}/user_logout">
                    <i class="nc-icon nc-button-power text-danger"></i>
                    <p>Log Out</p>
                </a>
            </li>
        </ul>
    </div>
</div>