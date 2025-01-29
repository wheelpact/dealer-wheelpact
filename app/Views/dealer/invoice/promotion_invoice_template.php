<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotion Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fcfcfc;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e8e8e8;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-logo img {
            width: 100%;
            height: 50px;
            object-fit: contain;
        }

        .content {
            padding: 20px;
        }

        .content h2,
        .content h3 {
            font-size: 20px;
            color: #017275;
            margin-bottom: 10px;
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        .content p {
            font-size: 16px;
            color: #000000;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777777;
        }

        .footer p {
            margin: 5px 0;
        }

        .invoice-box {
            padding: 20px;
            border-top: 1px solid #e8e8e8;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #f8f9fa;
            border-bottom: 1px solid #dddddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #f8f9fa;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #dddddd;
            font-weight: bold;
        }

        .print-button {
            margin: 20px 0;
            text-align: right;
        }
    </style>
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</head>

<body>

    <div class="email-container">
        <?php if (isset($orderDetails['printDownload']) && !empty($orderDetails['printDownload']) && $orderDetails['printDownload'] == "print") { ?>
            <div class="print-button">
                <button onclick="printInvoice()">Print Invoice</button>
            </div>
        <?php } ?>
        <?php if (isset($orderDetails['logo']) && !empty($orderDetails['logo'])) { ?>
            <div class="header email-logo">
                <img src="data:image/png;base64,<?php echo $orderDetails['logo']; ?>" alt="Wheelpact Logo">
            </div>
        <?php } ?>
        <div class="content">
            <div style="text-align: center;">
                <h2 class="text-center">Invoice</h2>
            </div>
            <p>Dear <?= $partnerInfo['name'] ?>,</p>
            <p>Thank you for your business. Below are the details of your invoice:</p>
            <div class="invoice-box">
                <table>
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        Invoice: #<?php echo $orderDetails['receipt']; ?><br>
                                        Date: <?php echo date("d-m-y", strtotime($orderDetails['created_dt'])); ?><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="heading">
                        <td> Particulars </td>
                        <td></td>
                    </tr>

                    <tr class="item">
                        <td>Plan</td>
                        <td>
                            <?php echo $planDetails['promotionName'] ?>
                        </td>
                    </tr>

                    <tr class="item">
                        <?php if ($promotionData['promotionUnder'] == 'vehicle') { ?>
                            <td>Promoted Vehicle</td>
                            <td>
                                <?php echo $itemDetails['cmp_name'] . ' ' . $itemDetails['cmp_model_name'] . ' ' . $itemDetails['variantName']; ?>
                            </td>
                        <?php } elseif ($promotionData['promotionUnder'] == 'showroom') { ?>
                            <td>Promoted Showroom</td>
                            <td>
                                <?php echo $itemDetails['branchName']; ?>
                            </td>
                        <?php } ?>
                    </tr>

                    <tr class="item">
                        <td>Promoted Date</td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($promotionData['start_dt'])); ?>
                        </td>
                    </tr>

                    <tr class="item">
                        <td>Promtion Valid Till:</td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($promotionData['end_dt'])); ?>
                        </td>
                    </tr>

                    <tr class="total">
                        <td>Total Amount</td>
                        <td>
                            <?php echo $orderDetails['amount'] . ' ' . $orderDetails['currency']; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <p>If you have any questions or need assistance, feel free to contact our support team at <a href="mailto:support@wheelpact.com">support@wheelpact.com</a> or call us at +91-1234567890.</p>
        </div>
        <div class="footer">
            <p>Thank you for choosing WheelPact!</p>
            <p>Product by Parastone Global Private Limited</p>
            <p>support@wheelpact.com | +91 12 345 67890</p>
            <p>(CIN: U62011MH2023PTC406427)</p>
        </div>
    </div>
</body>

</html>