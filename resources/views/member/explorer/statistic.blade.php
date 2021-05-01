@extends('layout.member.new_main')
@section('content')

<div class="wrapper">
    <div id="content">
        <div class="bg-gradient-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent w-100">
                <div class="container">
                    <a class="navbar-brand" href="{{ URL::to('/') }}/m/dashboard">
                        <i class="fa fa-arrow-left"></i> Beranda
                    </a>
                    <a href="{{ URL::to('/') }}/user_logout" class="btn btn-navbar btn-transparent">
                        <i class="fas fa-power-off text-danger icon-bottom"></i>
                    </a>
                </div>
            </nav>
        </div>
        <div class="mt-min-10">
            <div class="container">
                <div class="rounded-lg bg-white p-3 mb-3">
                    <h5 class="mb-3">Sepanjang Masa</h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow bg-light p-2">
                                <p>
                                    <a href="#">Total Aktivasi Keanggotaan</a>
                                </p>
                                <h6 class="text-warning">{{number_format($data['alltime']['account_activations'])}}</h6>
                                <button class="btn btn-sm btn-outline-warning float-right"
                                    onclick="showDetail('all', 'activations')">detail</button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="rounded-lg shadow bg-light p-2">
                                <p>
                                    Total LMB Diklaim:
                                </p>
                                <h6 class="text-warning">{{number_format($data['alltime']['lmb_claimed'])}}</h6>
                                <button class="btn btn-sm btn-outline-warning float-right"
                                    onclick="showDetail('all', 'lmb')">detail</button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="rounded-lg shadow bg-light p-2">
                                <p>
                                    Total Belanja Member di Stockist:
                                </p>
                                <button class="btn btn-sm btn-outline-warning float-right"
                                    onclick="showDetail('all', 'stockist-sales')">detail</button>
                                <h6 class="text-warning">Rp{{number_format($data['alltime']['stockist_sales'])}}</h6>

                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="rounded-lg shadow bg-light p-2">
                                <p>
                                    Total Belanja Member di Vendor:
                                </p>
                                <button class="btn btn-sm btn-outline-warning float-right"
                                    onclick="showDetail('all', 'vendor-sales')">detail</button>
                                <h6 class="text-warning">
                                    Rp{{number_format($data['alltime']['vendor_sales'])}}
                                </h6>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="rounded-lg shadow bg-light p-2">
                                <p>
                                    Total Dividend LMB didistribusikan:
                                </p>
                                <button class="btn btn-sm btn-outline-warning float-right"
                                    onclick="showDetail('all', 'dividend')">detail</button>
                                <h6 class="text-warning">Rp{{number_format($data['alltime']['lmb_dividend'])}}</h6>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="rounded-lg shadow bg-light p-2">
                                <p>
                                    Total Networking Bonus Dibayarkan:
                                </p>
                                <button class="btn btn-sm btn-outline-warning float-right"
                                    onclick="showDetail('all', 'network-bonus')">detail</button>
                                <h6 class="text-warning">Rp{{number_format($data['alltime']['network_bonus'])}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-white p-3 mb-3">

                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="table-responsive">
                                <h5>Bulan Lalu</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-active">
                                            <td><a class="text-info"
                                                    onclick="showDetail('last-month', 'activations')">Jumlah
                                                    Aktivasi</a></td>
                                            <td>{{number_format($data['last-month']['account_activations'])}}</td>
                                        </tr>
                                        <tr>
                                            <td><a class="text-info"
                                                    onclick="showDetail('last-month', 'network-bonus')">Network
                                                    Bonus</a></td>
                                            <td>Rp{{number_format($data['last-month']['network_bonus'])}}</td>
                                        </tr>
                                        <tr class="table-active">
                                            <td><a class="text-info" onclick="showDetail('last-month', 'lmb')">LMB
                                                    Diklaim</a></td>
                                            <td>{{number_format($data['last-month']['lmb_claimed'])}}</td>
                                        </tr>
                                        <tr>
                                            <td>Belanja Member (Stockist)</td>
                                            <td>Rp{{number_format($data['last-month']['stockist_sales'])}}</td>
                                        </tr>
                                        <tr>
                                            <td><a class="text-info"
                                                    onclick="showDetail('last-month', 'vendor-sales')">Belanja Member
                                                    (Vendor)</a></td>
                                            <td>Rp{{number_format($data['last-month']['vendor_sales'])}}
                                            </td>
                                        </tr>
                                        <tr class="table-active">
                                            <td><a class="text-info"
                                                    onclick="showDetail('last-month', 'dividend')">Dividend
                                                    LMB</a></td>
                                            <td>Rp{{number_format($data['last-month']['lmb_dividend'])}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
        @include('layout.member.nav')
    </div>
    <div class="overlay"></div>
</div>




@stop

@section('styles')
<link rel="stylesheet" href="{{ asset('asset_new/css/siderbar.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
@stop

@section('javascript')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script src="{{ asset('asset_new/js/sidebar.js') }}"></script>

<script>
    function showDetail(time, param) {
        $.ajax({
            type: "GET",
            url: "{{ URL::to('/') }}/api/v1/statistic/"+ time + "/" + param,
            dataType: 'JSON',
            success: function(response){
                var res = response.data;

                if(time == 'all' && param == 'activations') {
                    var content = `
                    <div style="text-align: left;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Member Baru: ${res.new_member}</li>
                            <li class="list-group-item">Resubscribe: ${res.resubscribe}</li>
                            <li class="list-group-item">Total: ${res.total}</li>
                        </ul>
                    </div>
                    `
                    swal(content);
                }

                if(param == 'lmb') {
                    var totalLMBclaimed = res.claimed_from_marketplace.total + res.claimed_from_network.total;
                    var content = `
                    <div style="text-align: left;">
                        <h6>Diklaim dari Jual-Beli:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Belanja di Stockist: ${res.claimed_from_marketplace.buy_on_stockist}</li>
                                <li class="list-group-item">Penjualan di Stockist: ${res.claimed_from_marketplace.sell_on_stockist}</li>
                                <li class="list-group-item">Belanja di Vendor: ${res.claimed_from_marketplace.buy_on_vendor}</li>
                                <li class="list-group-item">Penjualan di Vendor: ${res.claimed_from_marketplace.sell_on_vendor}</li>
                                <li class="list-group-item">Total: ${res.claimed_from_marketplace.total}</li>
                            </ul>
                            <br>
                        <h6>Diklaim dari Network:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Silver III: ${res.claimed_from_network.silver3}</li>
                                <li class="list-group-item">Silver II: ${res.claimed_from_network.silver2}</li>
                                <li class="list-group-item">Silver I: ${res.claimed_from_network.silver1}</li>
                                <li class="list-group-item">Gold III: ${res.claimed_from_network.gold3}</li>
                                <li class="list-group-item">Total: ${res.claimed_from_network.total}</li>
                            </ul>
                        <h6>Total: ${totalLMBclaimed}</h6>
                    </div>
                    `
                    swal(content);
                }

                if(time == 'all' && param == 'stockist-sales') {
                    var content = '<div style="text-align: left;"><ul class="list-group list-group-flush">'
                    for (var key in res.monthly) {
                        if (res.monthly.hasOwnProperty(key)) {
                            var val = res.monthly[key];
                            content +='<li class="list-group-item">' + key + ': ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val) + '</li>';
                        }
                    }
                    content +='</ul></div>' ;

                    swal(content);
                }

                if(time == 'all' && param == 'vendor-sales') {
                    var content = '<div style="text-align: left;"><h6>Produk Fisik</h6><ul class="list-group list-group-flush">'
                    for (var key in res.physical) {
                        if (res.physical.hasOwnProperty(key)) {
                            var val = res.physical[key];
                            content +='<li class="list-group-item">' + key + ': ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val) + '</li>';
                        }
                    }
                    content +='</ul><br><h6>Produk Digital</h6><ul class="list-group list-group-flush">';
                    for (var key in res.digital) {
                        if (res.digital.hasOwnProperty(key)) {
                            var val = res.digital[key];
                            content +='<li class="list-group-item">' + key + ': ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                                'IDR' }).format(val) + '</li>';
                        }
                    }
                    content +='</div>'

                    swal(content);
                }

                if(param == 'dividend') {
                var content = `
                    <div style="text-align: left;">
                    <h6>Rangkuman</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Dari Membership: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.membership_revenue) +`</li>
                        <li class="list-group-item">Kontribusi Stokis: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.stockist_contribution) +`</li>
                        <li class="list-group-item">Kontribusi Vendor: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.profit_share) +`</li>
                        <li class="list-group-item">Total: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.total) +`</li>
                    </ul>
                    <br>
                    <h6>Detail Kontribusi Vendor (Profit Sharing Pool)</h6>
                    <small>80% ke Dividend LMB, 15% ke Dividend LNS, 5% Operasional</small>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Pulsa & Paket Data: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.pulsa_data) +`</li>
                        <li class="list-group-item">PLN Prabayar: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.pln_prepaid) +`</li>
                        <li class="list-group-item">Telkom: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.telkom) +`</li>
                        <li class="list-group-item">PLN Pascabayar: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.pln_postpaid) +`</li>
                        <li class="list-group-item">HP Pascabayar: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.hp_postpaid) +`</li>
                        <li class="list-group-item">BPJS: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.bpjs) +`</li>
                        <li class="list-group-item">PDAM: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.pdam) +`</li>
                        <li class="list-group-item">Gas Negara: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.pgn) +`</li>
                        <li class="list-group-item">Multifinance: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.multifinance) +`</li>
                        <li class="list-group-item">E-money: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.emoney) +`</li>
                        <li class="list-group-item">Produk Fisik: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                            'IDR' }).format(res.profit_share_details.physical) +`</li>
                    </ul>
                    </div>
                `

                swal(content);
                }

                if(param == 'network-bonus') {
                var content = `
                    <div style="text-align: left;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Bonus Royalti: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.total_royalti_bonus) +`</li>
                        <li class="list-group-item">Bonus Harian (WD ke Bank): ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.total_wd_bank) +`</li>
                        <li class="list-group-item">Bonus Harian (Konversi ke eIDR): ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.total_konversi_eidr) +`</li>
                        <li class="list-group-item">Total: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.total) +`</li>
                    </ul>
                    </div>
                `

                swal(content);
                }

                if(time == 'last-month' && param == 'activations') {
                    var content = '<div style="text-align: left; font-size: 10px;"><dd>Member Baru: ' + res.new_member +'</dd><dd>Resubscribe: ' + res.resubscribe + '</dd><br>'
                    content += '<h6>Member Baru</h6><table class="table"><thead><tr><th>Username</th><th>Sponsor</th></tr></thead><tbody>'
                    for (var i = 0; i < res.detail_new_member.length; i++) {
                        content += '<tr><td>' + res.detail_new_member[i].username + '</td><td>' + res.detail_new_member[i].sp_name + '</td></tr>'
                    }

                    content +='</tbody></table><h6>Resubscribe</h6><table class="table"><thead><tr><th>Username</th><th>Sponsor</th></tr></thead><tbody>';

                    for (var i = 0; i < res.detail_resubscribe.length; i++) {
                        content += '<tr><td>' + res.detail_resubscribe[i].username + '</td><td>' + res.detail_resubscribe[i].sp_name + '</td></tr>'
                    }

                    content +='</tbody></table></div>'

                    swal(content);
                }

                if(time == 'last-month' && param == 'vendor-sales') {
                var content = `
                <div style="text-align: left;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Produk Fisik: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.physical) +`</li>
                    <li class="list-group-item">Produk Digital: ` + new Intl.NumberFormat('id-ID', { style: 'currency',
                        currency:
                        'IDR' }).format(res.digital) +`</li>
                    <li class="list-group-item">Total: ` + new Intl.NumberFormat('id-ID', { style: 'currency', currency:
                        'IDR' }).format(res.total) +`</li>
                </ul>
                </div>
                `

                swal(content);
                }

            }
        });
    }

    function swal(content) {
        Swal.fire({
            html: content,
            showCancelButton: false,
            showCloseButton: true,
            showConfirmButton: false
        })
    }
</script>

@stop