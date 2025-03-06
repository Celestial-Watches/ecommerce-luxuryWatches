<?php
chdir(dirname(__FILE__));
require_once '../config/conn.php';

// Update from paid to shipped (simulate after 2 minutes)
$conn->query("UPDATE transactionss 
  SET order_status = 'shipped', 
      shipped_at = NOW() 
  WHERE payment_status = 'success' 
    AND order_status = 'pending' 
    AND created_at <= NOW() - INTERVAL 2 MINUTE");

// Update from shipped to in_transit (simulate after 2 minutes)
$conn->query("UPDATE transactionss 
  SET order_status = 'in_transit',
      in_transit_at = NOW() 
  WHERE order_status = 'shipped' 
    AND shipped_at <= NOW() - INTERVAL 2 MINUTE");

// Update from in_transit to out_for_delivery (simulate after 2 minutes)
$conn->query("UPDATE transactionss 
  SET order_status = 'out_for_delivery',
      out_for_delivery_at = NOW() 
  WHERE order_status = 'in_transit' 
    AND in_transit_at <= NOW() - INTERVAL 2 MINUTE");

// Update from out_for_delivery to delivered (simulate after 2 minutes)
$conn->query("UPDATE transactionss 
  SET order_status = 'delivered',
      delivered_at = NOW() 
  WHERE order_status = 'out_for_delivery' 
    AND out_for_delivery_at <= NOW() - INTERVAL 2 MINUTE");

// Send shipping confirmation emails for newly shipped orders
$results = $conn->query("
  SELECT t.*, u.email 
  FROM transactionss t
  JOIN users u ON t.user_id = u.id
  WHERE order_status = 'shipped' 
    AND email_sent = 0
");

require '../../vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'celestialwatches69@gmail.com';
    $mail->Password   = 'xvmjnggsmsnkavzt';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');

    // Loop through each row and send an email
    while ($row = $results->fetch_assoc()) {
        $mail->clearAddresses();
        $mail->addAddress($row['email']);

        // Set email subject and body
        $mail->Subject = "Your Order #{$row['transaction_id']} Has Shipped!";
        $mail->Body = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Your Order Has Shipped!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        .cta-button {
            display: block;
            width: 100%;
            text-align: center;
            margin: 20px 0;
        }
        .cta-button a {
            background: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <img src='cid:logo_cid' alt='Celestial Watches'>
        </div>
        <div class='content'>
            <p>Hi {$row['customer_name']},</p>
            <p>Your order <strong>#{$row['transaction_id']}</strong> has been shipped!</p>
            <p><strong>Tracking Number:</strong> <span style='color: #007bff;'>{$row['tracking_number']}</span></p>
            <p>You can track your shipment anytime using the button below:</p>
            <div class='cta-button'>
                <a href='https://celestialwatches.xyz/app/controllers/tracking.php?tracking_number={$row['tracking_number']}'>
                    Track Your Order
                </a>
            </div>
            <p>Estimated delivery: <strong>2-5 business days</strong>.</p>
            <p>For any questions, feel free to contact our support team at 
                <a href='mailto:support@celestialwatches.xyz'>support@celestialwatches.xyz</a>.
            </p>
            <p>Thank you for choosing Celestial Watches. We appreciate your business!</p>
        </div>
        <div class='footer'>
            <p>Follow us on:
                <a href='https://facebook.com/celestialwatches'>Facebook</a> | 
                <a href='https://instagram.com/celestialwatches'>Instagram</a> | 
                <a href='https://twitter.com/celestialwatches'>Twitter</a>
            </p>
            <p>&copy; " . date('Y') . " Celestial Watches. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>";
        $mail->isHTML(true);
        $mail->addEmbeddedImage('../../celestial-logo.png', 'logo_cid');
        // Attempt to send the email
        if ($mail->send()) {
            $conn->query("UPDATE transactionss SET email_sent = 1 WHERE transaction_id = '{$row['transaction_id']}'");
        } else {
            // Log error or handle it as needed
            error_log("Email send failed for Order #{$row['transaction_id']}: " . $mail->ErrorInfo);
        }
    }
} catch (Exception $e) {
    error_log("PHPMailer Exception: " . $mail->ErrorInfo);
}
