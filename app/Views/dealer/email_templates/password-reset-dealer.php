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
        <p>Hi <?php echo $dealerData['name']; ?>,</p>
        <p>We received a request to reset your password for your account at wheelpact</p>
        <p>Click button below to reset your password:</p>
        <a href="<?php echo $dealerData['resetLink']; ?>" class="button">Click Here</a>
        <p>If you have any questions or need assistance, feel free to contact our support team at <a href="mailto:support@wheelpact.com">support@wheelpact.com</a> or call us at +91-1234567890.</p>
    </div>
    <div class="footer">
        <p>Thank you for choosing WheelPact!</p>
        <p>Product by Parastone Global Private Limited</p>
        <p>support@wheelpact.com | +91 12 345 67890</p>
        <p>(CIN: U62011MH2023PTC406427)</p>
    </div>
</div>