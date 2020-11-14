<!DOCTYPE html>
<!-- saved from url=(0051)https://www.sparksuite.com/open-source/invoice.html -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Invoice {{$getDataMaster->message}}</title>
        <style>
            body{
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align:center;
            color:rgb(0, 0, 0);
            font-weight: 700;
            }
            body h1{
            font-weight:300;
            margin-bottom:0px;
            padding-bottom:0px;
            color:#000;
            }
            body h3{
            font-weight:300;
            margin-top:10px;
            margin-bottom:20px;
            font-style:italic;
            color:#555;
            }
            body a{
            color:#06F;
            }
            .invoice-box{
            max-width:318px;
            margin:auto;
            padding:10px;
            border:1px solid #eee;
            font-size:12px;
            line-height:14px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color:rgb(0, 0, 0);
            }
            .invoice-box table{
            width:100%;
            line-height:inherit;
            text-align:left;
            }
            .invoice-box table td{
            padding:5px;
            vertical-align:top;
            }
            .invoice-box table tr td:nth-child(2){
            text-align:right;
            }
            .invoice-box table tr.top table td{
            padding-bottom:20px;
            }
            .invoice-box table tr.top table td.title{
            font-size:30px;
            line-height:20px;
            color:rgb(0, 0, 0);
            text-align: center;
            }
            .invoice-box table tr.information table td{
            }
            .invoice-box table tr.heading td{
            background:#eee;
            border-bottom:1px solid #ddd;
            font-weight:bold;
            }
            .invoice-box table tr.details td{
            padding-bottom:20px;
            }
            .invoice-box table tr.item td{
            border-bottom:1px solid #eee;
            }
            .invoice-box table tr.item.last td{
            border-bottom:none;
            }
            .invoice-box table tr.total td:nth-child(2){
            border-top:2px solid #eee;
            font-weight:bold;
            }
            /* @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
            }
            .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
            }
            } */
        </style>
    </head>
    <?php
    $return_buy = json_decode($getDataMaster->return_buy, true);
//    dd($getDataMaster);
    ?>
    <body>
        <div class="invoice-box" id="invoice">
            <table cellpadding="0" cellspacing="0">
                <tbody>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="title">
                                            <span style="font-size: 19px;margin: 0;">LUMBUNG NETWORK</span>
                                            <br>
                                            <p style="font-size: 13px;margin: 0;">
                                                <span style="border-bottom: 1px dotted;">Memberdayakan Pengeluaran<br>Menjadi Penghasilan</span>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="information">
                        <td colspan="2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{$getDataMaster->confirm_at}}
                                            <br>
                                            <small>{{$getDataMaster->ppob_code}}</small>
                                        </td>
                                        <td>
                                            Vendor: {{$dataUser->user_code}}
                                            <br>
                                            Buyer: {{$getMember->user_code}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="information">
                        <td colspan="2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{-- 4 = TELKOM, 5 = PLN Pasca, 6 = HP Pasca, 7 = BPJS, 8 = PDAM --}}
                                            {{$getDataMaster->message}}
                                            <br>
                                            ID Pel: {{$getDataMaster->product_name}}
                                            @if($getDataMaster->type != 3)
                                            <br>
                                            a/n: {{$return_buy['data']['customer_name']}}
                                            @endif

                                            {{-- PDAM & PLN Pasca --}}
                                            @if ($getDataMaster->type == 8)
                                            <br>
                                            Alamat: {{$return_buy['data']['desc']['alamat']}}
                                            @endif
                                            @if ($getDataMaster->type == 8 || $getDataMaster->type == 5)
                                            <br>
                                            <br>
                                            Detail:
                                            <br>
                                            Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                                            <br>
                                            @foreach ($return_buy['data']['desc']['detail'] as $detail)
                                            <br>
                                            Periode Tagihan: {{date('M Y', strtotime($detail['periode']))}}
                                            <br>
                                            Meter Awal: {{$detail['meter_awal']}}
                                            <br>
                                            Meter Akhir: {{$detail['meter_akhir']}}
                                            <br>
                                            Denda: {{$detail['denda']}}
                                            <br>

                                            @endforeach
                                            @endif

                                            {{-- PLN Prepaid --}}
                                            @if ($getDataMaster->type == 3)
                                            <?php $separate = explode('/', $return_buy['data']['sn']) ?>
                                            <br>
                                            a/n: {{$separate[1]}}
                                            <br>
                                            Tipe/Daya: {{$separate[2]}} / {{$separate[3]}}
                                            <br>
                                            Jumlah KWh: {{$separate[4]}}
                                            <br>
                                            Kode Token:
                                            <br>
                                            <span style="font-size: 18px;">{{$separate[0]}}</span>

                                            @endif

                                            {{-- BPJS --}}
                                            @if ($getDataMaster->type == 7)
                                            <br>
                                            Alamat: {{$return_buy['data']['desc']['alamat']}}
                                            <br>
                                            Lembar Tagihan: {{$return_buy['data']['desc']['lembar_tagihan']}}
                                            <br>
                                            Jumlah Peserta: {{$return_buy['data']['desc']['jumlah_peserta']}}
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr class="heading">
                        <td>
                            Detail
                        </td>
                        <td>
                            Harga
                        </td>
                    </tr>
                    <tr class="item">
                        <td>
                            Tagihan
                        </td>
                        <td>
                            Rp {{$getDataMaster->ppob_price - 2500}}
                        </td>
                    </tr>
                    <tr class="item last">
                        <td>
                            Biaya Admin
                        </td>
                        <td>
                            Rp 2500
                        </td>
                    </tr>
                    <tr class="total">
                        <td></td>
                        <td>
                            Total: Rp {{$getDataMaster->ppob_price}}
                        </td>
                    </tr>

                    <tr class="information">
                        <td colspan="2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if ($getDataMaster->type != 3)
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
                                        <td class="title">
                                            <p style="font-size: 11px;margin: 0;"><span style="border-bottom: 1px dotted;">Struk ini merupakan bukti pembayaran yang sah.</span></p>
                                            <p style="font-size: 14px;margin: 0;">Terima Kasih</p>
                                            <p style="font-size: 12px;margin: 0;">https://lumbung.network/</p>
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
        <button id="konvert">Generate PDF</button>&nbsp;&nbsp;
        <a id="konvert" href="/m/list/buy-ppob">Kembali</a>
        <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                document.getElementById("konvert")
                    .addEventListener("click", () => {
                        const invoice = this.document.getElementById("invoice");
                        console.log(invoice);
                        console.log(window);
                        var opt = {
                            margin: 1,
                            filename: 'invoice.pdf',
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2 },
                            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                        };
                        html2pdf().from(invoice).set(opt).save();
                    })
            }
        </script>
    </body>
</html>
