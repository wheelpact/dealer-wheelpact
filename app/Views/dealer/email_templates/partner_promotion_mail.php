<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Promtion - Wheelpact</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

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
            display: block;
            text-align: center;
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

        @media (max-width: 600px) {

            .header,
            .content,
            .footer {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header email-logo">
            <img src="data:image/png;base64,<?php echo $orderDetails['logo']; ?>" alt="Wheelpact Logo">
        </div>
        <div class="content">
            <h2>Dear <?php echo ucwords($partnerInfo['name']); ?>,</h2>
            <p>We are thrilled to have you join our community of trusted dealers. Your registration was successful, and your dealer account is now active. Here are the details of your registration:</p>
            <h3>Promotion Details:</h3>
            <p><strong>Plan:</strong> <?php echo $planDetails['promotionName']; ?></p>
            <?php if ($promotionData['promotionUnder'] == 'vehicle') { ?>
                <p><strong>Promoted Vehicle:</strong> <?php echo $itemDetails['cmp_name'] . ' ' . $itemDetails['cmp_model_name'] . ' ' . $itemDetails['variantName']; ?></p>
            <?php } elseif ($promotionData['promotionUnder'] == 'showroom') { ?>
                <p><strong>Promoted Showroom:</strong> <?php echo $itemDetails['branchName']; ?></p>
            <?php } ?>
            <p><strong>Promoted Date:</strong> <?php echo date('d/m/Y', strtotime($promotionData['start_dt'])); ?></p>
            <p><strong>Promtion Valid Till:</strong> <?php echo date('d/m/Y', strtotime($promotionData['end_dt'])); ?></p>
            <p><strong>Total Amount:</strong> <?php echo $orderDetails['amount'] . ' ' . $orderDetails['currency']; ?></p>
            <h3>Need Assistance?</h3>
            <p>If you have any questions or need help getting started, our support team is here for you. Feel free to contact us at <a href="mailto:support@wheelpact.com">support@wheelpact.com</a> or call us at +91-1234567890.
            </p>
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