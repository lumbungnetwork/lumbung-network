<!DOCTYPE html>
<!-- saved from url=(0051)https://www.sparksuite.com/open-source/invoice.html -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Invoice</title>
        <style>
            body{
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align:center;
            color:#777;
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
            max-width:800px;
            margin:auto;
            padding:30px;
            border:1px solid #eee;
            font-size:16px;
            line-height:24px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color:#555;
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
            line-height:30px;
            color:#333;
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
            @media only screen and (max-width: 600px) {
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
            }
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
                                            LUMBUNG NETWORK
                                            <br>
                                            <p style="font-size: 20px;margin: 0;">
                                                <span style="border-bottom: 1px dotted;">Memberdayakan Pengeluaran Menjadi Penghasilan</span>
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
                                            {{$getDataMaster->ppob_code}}
                                        </td>
                                        <td>
                                            Vendor: {{$getVendor->user_code}}
                                            <br>
                                            Buyer: {{$dataUser->user_code}}
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
                                            {{$getDataMaster->message}}
                                            <br>
                                            ID Pel: {{$getDataMaster->product_name}}
                                            <br>
                                            a/n: {{$return_buy['data']['customer_name']}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @if($getDataMaster->message != 'Pembayaran BPJS')
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
                            Rp {{$return_buy['data']['desc']['detail'][0]['nilai_tagihan']}}
                        </td>
                    </tr>
                    <tr class="item last">
                        <td>
                            Biaya Admin
                        </td>
                        <td>
                            Rp {{$return_buy['data']['desc']['detail'][0]['admin']}}
                        </td>
                    </tr>
                    <tr class="total">
                        <td></td>
                        <td>
                            Total: Rp {{$return_buy['data']['desc']['detail'][0]['nilai_tagihan'] + $return_buy['data']['desc']['detail'][0]['admin']}}
                        </td>
                    </tr>
                    @endif
                    @if($getDataMaster->message == 'Pembayaran BPJS')
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
                            Rp {{$getDataMaster->harga_modal}}
                        </td>
                    </tr>
                    <tr class="total">
                        <td></td>
                        <td>
                            Total: Rp {{$getDataMaster->harga_modal}}
                        </td>
                    </tr>
                    @endif
                    <tr class="information">
                        <td colspan="2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            No Ref/SN:
                                            <br>
                                            {{$return_buy['data']['sn']}}
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
                                            <p style="font-size: 20px;margin: 0;"><span style="border-bottom: 1px dotted;">Struk ini merupakan bukti pembayaran yang sah.</span></p>
                                            <p style="font-size: 20px;margin: 0;">Terima Kasih</p>
                                            <p style="font-size: 20px;margin: 0;">https://lumbung.network/</p>
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