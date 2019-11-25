<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <title></title>
        <!--[if !mso]><!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type="text/css">
            #outlook a { padding: 0; }
            .ReadMsgBody { width: 100%; }
            .ExternalClass { width: 100%; }
            .ExternalClass * { line-height:100%; }
            body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
            table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
            img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
            p { display: block; margin: 13px 0; }
        </style>
        <style type="text/css">
            @media only screen and (max-width:480px) {
            @-ms-viewport { width:320px; }
            @viewport { width:320px; }
            }
        </style>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700" rel="stylesheet" type="text/css">
        <style type="text/css">
            @import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700);
        </style>
        <style type="text/css">
            @media only screen and (min-width:480px) {
            .mj-column-per-100 { width:100%!important; }
            }
        </style>
    </head>
    <body style="background: #EBF2F5;">
        <div class="mj-container" style="background-color:#EBF2F5;">
            <div style="margin:0px auto;max-width:600px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                    <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:30px;padding-top:0px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin:0px auto;border-radius:4px;max-width:600px;background:#fff;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;border-radius:4px;background:#fff;" align="center" border="0">
                    <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:1px;">
                                <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="background:white;" width="100%" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="word-wrap:break-word;font-size:0px;padding:20px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:20px;line-height:22px;text-align:left;">Berikut Data Member yang Melakukan Proses Withdraw {{date('d F Y',strtotime("-1 days"))}}</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:1px;">
                                <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="background:white;" width="100%" border="0">
                                        <thead>
                                            <tr>
                                                <th style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">No.</div>
                                                </th>
                                                <th style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">User ID</div>
                                                </th>
                                                <th style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">No. HP</div>
                                                </th>
                                                <th style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">Metode Pembayaran</div>
                                                </th>
                                                <th style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">Royalti</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($dataEmail != null)
                                                <?php $no = 0; ?>
                                                <?php foreach($dataEmail as $row){ ?>
                                                    <?php 
                                                    $no++; 
                                                    $metode = 'eIDR';
                                                    $detail = 'TWJtGQHBS88PfZTXvWAYhQEMrx36eX2F9Pc';
                                                    if($row->buy_metode == 2){
                                                        $metode = 'Transfer Bank';
                                                        $detail = 'BRI 
                                                            <br>
                                                            a/n <b>PT LUMBUNG MOMENTUM BANGSA</b>
                                                            <br>
                                                            033601001795562';
                                                    }
                                                    $royalti = 4/100 * $row->price;
                                                    ?>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                            <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">
                                                                {{$no}}
                                                            </div>
                                                        </td>
                                                        <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                            <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">
                                                                {{$row->user_code}}
                                                            </div>
                                                        </td>
                                                        <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                            <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">
                                                                {{$row->hp}}
                                                            </div>
                                                        </td>
                                                        <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                            <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">
                                                                {{$metode}} <br> <?php echo $detail ?>
                                                            </div>
                                                        </td>
                                                        <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 18px;" align="left">
                                                            <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:left;">
                                                                Rp. {{number_format($royalti, 0, ',', ',')}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:1px;">
                                <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="background:white;" width="100%" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="word-wrap:break-word;font-size:0px;padding:20px 30px 0" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:16px;text-align:left;">Terima Kasih </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table role="presentation" cellpadding="0" cellspacing="0" style="background:white;" width="100%" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="word-wrap:break-word;font-size:0px;padding:20px 30px 18px;" align="left">
                                                    <div style="cursor:auto;color:#000000;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:20px;line-height:22px;text-align:left;">Admin Lumbung Network</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin:0px auto;max-width:600px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                    <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:15px 0px 0px;">
                                <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="word-wrap:break-word;font-size:0px;padding:0px;" align="center">
                                                    <div style="cursor:auto;color:#939daa;font-family:Open Sans, Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;text-align:center;">
                                                        <a href="https://member.lumbung.network" style="text-decoration: none; color: inherit;">
                                                        Lumbung Network
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin:0px auto;max-width:600px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
                    <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:30px;padding-top:0px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>