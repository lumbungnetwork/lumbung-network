<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Belanja</title>
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


<body>
    <div class="invoice" id="invoice">
        <table cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td colspan="4">
                        <p class="centered" style="font-size: 13px; font-weight:600; margin-bottom: 0;">LUMBUNG NETWORK
                        </p>

                        <p class="centered">Memberdayakan Pengeluaran<br>Menjadi Penghasilan</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <p style="word-break: break-all;"></p>{{$dataSales->sale_date}}

                    </td>
                    <td colspan="3" style="text-align:right">
                        Seller: {{$shopName}}
                        <br>
                        Buyer: {{$dataSales->username}}
                    </td>
                </tr>

                <tr class="heading">
                    <th colspan="2">Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                </tr>

                @if($dataItem != null)

                @foreach($dataItem as $row)

                <tr class="item">
                    <td colspan="2">{{$row->size}} {{$row->name}}</td>
                    <td>{{number_format($row->amount)}}</td>
                    <td>Rp{{number_format($row->sale_price)}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>


            <tr class="total">

                <td style="text-align:right" colspan="4">
                    <strong style="font-size: 14px;">Total: Rp{{number_format($dataSales->sale_price)}}</strong>
                </td>
            </tr>


            <tr class="top">
                <td colspan="4">
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
    <a class="hidden-print" href="{{ url()->previous() }}">Kembali</a>&nbsp;&nbsp;
    <button class="hidden-print" id="btnPrint">Print Struk</button>&nbsp;&nbsp;

    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script> --}}
    <script type="text/javascript">
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
        window.print();
        });
        // window.onload = function () {
        //         document.getElementById("konvert")
        //             .addEventListener("click", () => {
        //                 const invoice = this.document.getElementById("invoice");
        //                 console.log(invoice);
        //                 console.log(window);
        //                 var opt = {
        //                     margin: 1,
        //                     filename: 'invoice.pdf',
        //                     image: { type: 'jpeg', quality: 0.98 },
        //                     html2canvas: { scale: 2 },
        //                     jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        //                 };
        //                 html2pdf().from(invoice).set(opt).save();
        //             })
        //     }


    </script>
</body>

</html>