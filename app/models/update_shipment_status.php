<?php
chdir(dirname(__FILE__));
require_once '../config/conn.php';

// Update status from paid to shipped (simulate shipping after 2 minutes)
$conn->query("UPDATE transactions 
  SET order_status = 'shipped', 
      tracking_number = CONCAT('TRK', UPPER(SUBSTRING(MD5(RAND()), 1, 10))),
      shipped_at = NOW() 
  WHERE payment_status = 'success' 
    AND order_status = 'pending' 
    AND created_at <= NOW() - INTERVAL 2 MINUTE");

// Update status from shipped to in_transit (simulate after 5 minutes)
$conn->query("UPDATE transactions 
  SET order_status = 'in_transit',
      in_transit_at = NOW() 
  WHERE order_status = 'shipped' 
    AND shipped_at <= NOW() - INTERVAL 5 MINUTE");

// Update status from in_transit to out_for_delivery (simulate after 2 hours)
$conn->query("UPDATE transactions 
  SET order_status = 'out_for_delivery',
      out_for_delivery_at = NOW() 
  WHERE order_status = 'in_transit' 
    AND in_transit_at <= NOW() - INTERVAL 2 HOUR");

// Update status from out_for_delivery to delivered (simulate after 1 hour)
$conn->query("UPDATE transactions 
  SET order_status = 'delivered',
      delivered_at = NOW() 
  WHERE order_status = 'out_for_delivery' 
    AND out_for_delivery_at <= NOW() - INTERVAL 1 HOUR");

// Send shipping confirmation emails for newly shipped orders
$results = $conn->query("
  SELECT t.*, u.email 
  FROM transactions t
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
        $mail->Body    = "Tracking Number: {$row['tracking_number']}\n"
            . "Track here: https://celestialwatches.xyz/app/controllers/tracking.php?tracking_number={$row['tracking_number']}";

        // Attempt to send the email
        if ($mail->send()) {
            $conn->query("UPDATE transactions SET email_sent = 1 WHERE transaction_id = '{$row['transaction_id']}'");
        } else {
            // Log error or handle it as needed
            error_log("Email send failed for Order #{$row['transaction_id']}: " . $mail->ErrorInfo);
        }
    }
} catch (Exception $e) {
    error_log("PHPMailer Exception: " . $mail->ErrorInfo);
}
