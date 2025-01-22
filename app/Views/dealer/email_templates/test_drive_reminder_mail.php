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
</style>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="email-container">
    <div class="header email-logo">
        <img src="<?php echo base_url(); ?>assets/vendors/images/wheelpact-logo.png">
    </div>
    <div class="content">
        <p>Hi <?php echo $testDriveData['customer_name']; ?>,</p>
        <p>This is an update about your request test drive:</p>

        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <th>Vehicle</th>
                    <td><?php echo $testDriveData['cmp_name'] . ' ' . $testDriveData['model_name'] . ' ' . $testDriveData['variant_name']; ?></td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td><?php echo $testDriveData['branch_name']; ?></td>
                </tr>
                <tr>
                    <th>Date of Visit</th>
                    <td><?php echo $testDriveData['formatted_dateOfVisit']; ?></td>
                </tr>
                <tr>
                    <th>Time of Visit</th>
                    <td><?php echo TEST_DRIVE_SLOTS[$testDriveData['timeOfVisit']]; ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo ucfirst($testDriveData['status']); ?></td>
                </tr>

                <?php if (in_array(strtolower($testDriveData['status']), ['rejected', 'canceled'])): ?>
                    <tr>
                        <th>Reason</th>
                        <td><?php echo $testDriveData['reason_selected']; ?></td>
                    </tr>
                    <tr>
                        <th>Note From Dealer</th>
                        <td><?php echo $testDriveData['dealer_comments']; ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <div class="footer">
        <p>Thank you for choosing WheelPact!</p>
        <p>Product by Parastone Global Private Limited</p>
        <p>support@wheelpact.com | +91 12 345 67890</p>
        <p>(CIN: U62011MH2023PTC406427)</p>
    </div>
</div>