<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{$data->message}}</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'Helvetica Neue, Times New Roman';
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid darkgray;
            border-collapse: collapse;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .invoice {
            width: 200px;
            max-width: 210px;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }
    </style>
</head>
<?php
    $return_buy = json_decode($data->return_buy, true);
//    dd($data);
    ?>

<body>
    <div class="invoice" id="invoice">
        <table cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td colspan="2">
                        <p class="centered" style="font-size: 13px; font-weight:600; margin-bottom: 0;">LUMBUNG NETWORK
                        </p>

                        <p class="centered">Memberdayakan Pengeluaran<br>Menjadi Penghasilan</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="word-break: break-all;"></p>{{$data->confirm_at}}

                    </td>
                    <td style="text-align:right">

                        Seller: {{$data->seller->sellerProfile->shop_name}}
                        <br>
                        Buyer: {{$data->buyer->username}}
                    </td>
                </tr>
                <tr class="information">
            <tbody>
                <tr>
                    <td colspan="2">
                        {{-- 4 = BPJS, 5 = PLN Pasca, 6 = HP Pasca, 7 = Telkom, 8 = PDAM --}}
                        <span style="font-size: 14px; font-weight: 600;">Produk:
                            {{$data->message}}</span>
                        <br>
                        ID Pel: {{$data->product_name}}
                        @if($data->type != 3)
                        <br>
                        a/n: {{$return_buy['data']['customer_name']}}
                        @endif

                        {{-- PDAM & PLN Pasca --}}
                        @if ($data->type == 8)
                        <br>
                        @if(isset($return_buy['data']['desc']['alamat']))
                        Alamat: {{$return_buy['data']['desc']['alamat']}}
                        @endif
                        @endif
                        @if ($data->type == 8 || $data->type == 5)
                        <br>
                        <br>
                        Detail:
                        <br>
                        Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                        <br>
                        @if (isset($return_buy['data']['desc']['detail']))
                        @foreach ($return_buy['data']['desc']['detail'] as $detail)
                        <br>
                        Periode Tagihan: {{$detail['periode']}}
                        @if ( !empty($detail['meter_awal']) && !empty($detail['meter_akhir']))
                        <br>
                        Meter Awal: {{$detail['meter_awal']}}
                        <br>
                        Meter Akhir: {{$detail['meter_akhir']}}
                        @endif
                        @if (!empty($detail['denda']))
                        <br>
                        Denda: {{$detail['denda']}}
                        @endif
                        <br>

                        @endforeach
                        @endif
                        @endif

                        {{-- PLN Prepaid --}}
                        @if ($data->type == 3)
                        <?php $separate = explode('/', $return_buy['data']['sn']) ?>
                        <br>
                        a/n: {{$separate[1]}}
                        <br>
                        @if(isset($separate[2], $separate[3], $separate[3]))
                        Tipe/Daya: {{$separate[2]}} / {{$separate[3]}}
                        <br>
                        Jumlah KWh: {{$separate[4]}}
                        <br>
                        @else
                        Jumlah KWh: {{$separate[2]}}
                        <br>
                        @endif
                        <br>
                        Kode Token:
                        <br>
                        <span style="font-size: 14px; font-weight: 700;">{{$separate[0]}}</span>

                        @endif

                        {{-- Telkom (was BPJS  <2021) --}}
                        @if ($data->type == 7)
                        @if ($data->created_at < '2021-01-01 00:00:00' ) {{-- was BPJS --}} <br>
                            Alamat: {{$return_buy['data']['desc']['alamat']}}
                            <br>
                            Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                            <br>
                            Jumlah Peserta: {{$return_buy['data']['desc']['jumlah_peserta']}}

                            {{-- Change to Telkom --}}
                            @else
                            <br>
                            Detail:
                            <br>
                            Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                            <br>
                            @foreach ($return_buy['data']['desc']['detail'] as $detail)
                            <br>
                            Periode Tagihan: {{$detail['periode']}}
                            <br>
                            Nilai Tagihan: Rp{{number_format($detail['nilai_tagihan'] + 1000)}}
                            <br>
                            Admin: Rp{{number_format($detail['admin'])}}
                            <br>
                            @endforeach
                            @endif

                            @endif

                            {{-- BPJS (was Telkom <2021) --}}
                            @if ($data->type == 4)

                            @if ($data->created_at < '2021-01-01 00:00:00' ) {{-- was Telkom --}} <br>

                                <br>
                                Detail:
                                <br>
                                Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                                <br>
                                @foreach ($return_buy['data']['desc']['detail'] as $detail)
                                <br>
                                Periode Tagihan: {{$detail['periode']}}
                                <br>
                                Nilai Tagihan: Rp{{number_format($detail['nilai_tagihan'] + 1000)}}
                                <br>
                                Admin: Rp{{number_format($detail['admin'])}}
                                <br>
                                @endforeach

                                {{-- Change to BPJS --}}
                                @else
                                <br>
                                Alamat: {{$return_buy['data']['desc']['alamat']}}
                                <br>
                                Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                                <br>
                                Jumlah Peserta: {{$return_buy['data']['desc']['jumlah_peserta']}}

                                @endif

                                @endif
                                <br>
                    </td>
                </tr>
            </tbody>
            </tr>

            <tr class="heading">
                <td>
                    <b>Detail</b>
                </td>
                <td style="text-align:right">
                    <b>Harga</b>
                </td>
            </tr>
            <tr class="item">
                <td>
                    Tagihan
                </td>
                <td style="text-align:right">
                    Rp {{$data->ppob_price - 2500}}
                </td>
            </tr>
            <tr class="item last">
                <td>
                    Biaya Admin
                </td>
                <td style="text-align:right">
                    Rp 2500
                </td>
            </tr>
            <tr class="total">

                <td style="text-align:right" colspan="2">
                    <strong style="font-size: 14px;">Total: Rp {{$data->ppob_price}}</strong>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    @if ($data->type != 3)
                                    No Ref/SN:
                                    <br>
                                    {{$return_buy['data']['sn']}}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <p class="centered" style="font-size: 11px;margin: 0;"><span
                                            style="border-bottom: 1px dotted;">Struk ini merupakan bukti pembayaran
                                            yang sah.</span></p>
                                    <p class="centered" style="font-size: 14px;margin: 0;">Terima Kasih</p>
                                    <p class="centered" style="font-size: 12px;margin: 0;">https://lumbung.network/
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="editor"></div>
    <br>
    <br>
    <a class="hidden-print" href="{{url()->previous()}}">Kembali</a>
    <button class="hidden-print" id="btnPrint">Print Struk</button>&nbsp;&nbsp;

    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script type="text/javascript">
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
        window.print();
        });
    </script>
</body>

</html>