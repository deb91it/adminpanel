<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>

    <!-- For development, pass document through inliner -->
    <style type="text/css">

        * { margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; line-height: 1.65; }

        img { max-width: 100%; margin: 0 auto; display: block; }

        body, .body-wrap { width: 100% !important; height: 100%; background: #f8f8f8; }

        a { color: #F58225; text-decoration: none; }

        a:hover { text-decoration: underline; }

        .text-center { text-align: center; }

        .text-right { text-align: right; }

        .text-left { text-align: left; }

        .button { display: inline-block; color: white; background: #F58225; border: solid #F58225; border-width: 10px 20px 8px; font-weight: bold; border-radius: 4px; }

        .button:hover { text-decoration: none; }

        h1, h2, h3, h4, h5, h6 { margin-bottom: 20px; line-height: 1.25; }

        h1 { font-size: 32px; }

        h2 { font-size: 28px; }

        h3 { font-size: 24px; }

        h4 { font-size: 20px; }

        h5 { font-size: 16px; }

        p, ul, ol { font-size: 16px; font-weight: normal; margin-bottom: 20px; }

        .container { display: block !important; clear: both !important; margin: 0 auto !important; max-width: 700px !important; }

        .container table { width: 100% !important; border-collapse: collapse; }

        .container .masthead { padding: 80px 0; background: #F58225; color: white; }

        .container .masthead h1 { margin: 0 auto !important; max-width: 90%; text-transform: uppercase; }

        .container .content { background: white; padding: 30px 35px; }

        .container .content.footer { background: #F58226; }

        .container .content.footer p { margin-bottom: 0; color: #FFFFFF; text-align: center; font-size: 14px; }

        .container .content.footer a { color: #FFFFFF; text-decoration: none; font-weight: bold; }

        .container .content.footer a:hover { text-decoration: underline; }

        th {
            height: 50px;
        }
        th, td {
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {background-color: #FFF9F3;}
        .padding{
            padding: 2px;
        }

    </style>
</head>
<body>
<?php

$subTotal[$m_key] = 0;
$total_charge[$m_key] = 0;
$total[$m_key] = 0;
$cod_charge[$m_key] = 0;
$singleItem_charge[$m_key] = 0;
?>
<table class="body-wrap">
    <tr>
        <td class="container">

            <!-- Message start -->
            <table>
                <tr>
                    <td align="center" class="masthead">

                        <h1>Provati</h1>

                    </td>
                </tr>
                <tr>
                    <td class="content">

                        <h2>Hi {{!empty($merchant->business_name) ?  $merchant->business_name : 'Anonymous'}},</h2>
                        <p>Thank you for being with us. Please check the consignment list of this invoice </p>

                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th class="text-center padding"><strong>Consign ID</strong></th>
                                <th class="text-center padding"><strong>Order ID</strong></th>
                                <th class="text-center padding"><strong>Collected</strong></th>
                                <th class="text-center padding"><strong>Received</strong></th>
                                <th class="text-center padding"><strong>Delivery Fee</strong></th>
                                <th class="text-center padding"><strong>COD Fee</strong></th>
                                <th class="text-center padding"><strong>Entry Date</strong></th>
                                <th class="text-center padding"><strong>Totals</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                            @forelse($invoices[$m_key] as $singleItem)
                                <?php
                                $total[$m_key] = $total[$m_key] + ($singleItem->receive_amount - ($singleItem->charge + $singleItem->cod_charge));
                                $total_charge[$m_key] = $total_charge[$m_key] + $singleItem->charge;
                                $cod_charge[$m_key] = $cod_charge[$m_key] + $singleItem->cod_charge;
                                $singleItem_charge[$m_key] = $singleItem_charge[$m_key] + $singleItem->charge;
                                ?>
                                <tr>
                                    <td class="text-center padding">{{$singleItem->consignment_id}}</td>
                                    <td class="text-center padding">{{!empty($singleItem->merchant_order_id) ? $singleItem->merchant_order_id : '-----'}}</td>
                                    <td class="text-center padding">{{$singleItem->amount_to_be_collected}}</td>
                                    <td class="text-center padding">{{$singleItem->receive_amount}}</td>
                                    <td class="text-center padding">{{$singleItem->charge}}</td>
                                    <td class="text-center padding">{{$singleItem->cod_charge}}</td>
                                    <td class="text-center padding">{{date('M j, Y', strtotime($singleItem->created_at))}}</td>
                                    <td class="text-center padding">{{$singleItem->receive_amount - ($singleItem->charge + $singleItem->cod_charge)}}</td>
                                </tr>
                            @empty
                                <p></p>
                            @endforelse
                            <tr>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line text-center text-bold"><b>{{$singleItem_charge[$m_key]}}</b></td>
                                <td class="thick-line text-center text-bold"> <b>{{$cod_charge[$m_key]}}</b></td>
                                <td class="thick-line text-center"><strong></strong></td>
                                <td class="thick-line"></td>
                            </tr>

                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"></td>
                                <td class="no-line text-center"> </td>
                                <td class="no-line text-center padding"><strong>Total</strong></td>
                                <td class="no-line text-right text-bold padding"> <b>BDT {{$total[$m_key]}}</b></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php if (!empty($inv_notes) && !empty($inv_notes->notes)){ ?>
                            <h4>Note : {{$inv_notes->notes}}</h4>
                        <?php } ?>

                        <p>By the way, if you're wondering where you can find more of this fine meaty filler, visit <a href="http://provaticourier.com">Provati</a>.</p>

                        <p><em>– Provati</em></p>

                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td class="container">

            <!-- Message start -->
            <table>
                <tr>
                    <td class="content footer" align="center">
                        <p>Sent by <a href="http://provaticourier.com">Provati</a>, 564, Manipur, Mirpur, Dhaka-1216
                            Near Mollar mor, 60 Feet Road (Behind Yellow Kitchen).</p>
                        <p><a href="mailto:">provati.courier@gmail.com</a> | <a href="#">Unsubscribe</a></p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>