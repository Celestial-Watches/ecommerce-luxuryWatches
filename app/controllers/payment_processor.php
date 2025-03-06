<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

session_start();

// Ensure user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($input['transactionData']) || !isset($input['paymentToken'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }

    // Process payment (simulated)
    $transactionData = $input['transactionData'];
    $paymentToken = $input['paymentToken'];

    // Generate a unique transaction ID
    $transactionId = 'TXN' . uniqid();

    // Currency settings
    $currencySymbols = [
        'usd' => '$',
        'eur' => '€',
        'inr' => '₹',
        'gbp' => '£',
        'jpy' => '¥',
        'aud' => 'A$',
        'cny' => '¥',
    ];
    $currency = strtolower($transactionData['currency']);
    $currencySymbol = $currencySymbols[$currency] ?? '$';

    // Calculate overall totals
    $grandTotal = number_format($transactionData['total_amount'], 2);
    $checkoutSubtotal = number_format($transactionData['checkoutSubtotal'], 2);
    $checkoutShipping = number_format($transactionData['checkoutShipping'], 2);
    $checkoutTax = number_format($transactionData['checkoutTax'], 2);
    $checkoutDiscount = number_format($transactionData['checkoutDiscount'], 2);
    $orderDate = date('Y-m-d H:i:s');
    $customerName = htmlspecialchars($transactionData['customer']['name']);
    $address = htmlspecialchars($transactionData['customer']['address']);

    // Send confirmation email using PHPMailer
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

        // Recipients
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($transactionData['customer']['email']);
        $mail->addCC('celestialwatches69@gmail.com');

        // Content settings
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation #' . $transactionId;

        $mailBody = "<html>
        <head>
          <style>
            body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; }
            .container { width: 100%; max-width: 600px; margin: auto; border: 1px solid #ddd; }
            .header { background-color: #f8f8f8; padding: 20px; text-align: center; }
            .header img { max-width: 150px; }
            .content { padding: 20px; }
            .content h1 { color: #008080; }
            .order-summary { background: #f0f0f0; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
            .order-summary h2 { margin-top: 0; }
            .order-summary ul { list-style: none; padding: 0; }
            .order-summary li { margin-bottom: 8px; }
            .btn { display: inline-block; padding: 10px 20px; margin-top: 20px; background: #008080; color: #fff; text-decoration: none; border-radius: 5px; }
            .footer { background-color: #f8f8f8; padding: 20px; text-align: center; font-size: 0.9em; color: #777; }
          </style>
        </head>
        <body>
          <div class='container'>
            <div class='header'>
              <img src='cid:logo_cid' alt='Celestial Watches'>
            </div>
            <div class='content'>
              <h1>Thank You for Your Order, {$customerName}!</h1>
              <p>We have received your order and it is now being processed. Below are your order details:</p>
              <div class='order-summary'>
                <h2>Order Summary</h2>
                <p><strong>Order ID:</strong> {$transactionId}</p>
                <p><strong>Order Date:</strong> {$orderDate}</p>
                <p><strong>Subtotal:</strong> {$currencySymbol}{$checkoutSubtotal}</p>
                <p><strong>Shipping:</strong> {$currencySymbol}{$checkoutShipping}</p>
                <p><strong>Tax:</strong> {$currencySymbol}{$checkoutTax}</p>
                <p><strong>Discount:</strong> {$currencySymbol}{$checkoutDiscount}</p>
                <p><strong>Total Amount:</strong> {$currencySymbol}{$grandTotal}</p>
                <h3>Items Ordered:</h3>
                <ul>";

        foreach ($transactionData['cart'] as $item) {
            $itemName = htmlspecialchars($item['name']);
            $quantity = htmlspecialchars($item['quantity']);
            $itemUnitPrice = isset($checkoutSubtotal) ? $transactionData['checkoutSubtotal'] : 0;
            $unitPrice = number_format($itemUnitPrice, 2);
            $lineTotal = number_format($itemUnitPrice * $item['quantity'], 2);

            $mailBody .= "<li>
                <strong>{$itemName}</strong> - Quantity: {$quantity} <br>
                Unit Price: {$currencySymbol}{$unitPrice} <br> Line Total: {$currencySymbol}{$lineTotal}
            </li>";
        }

        $mailBody .= "</ul>
              </div>
              <p>You can track your order status by clicking the button below:</p>
              <a class='btn' href='https://celestialwatches.xyz/app/views/order-tracking.php?transaction_id={$transactionId}'>Track Your Order</a>
              <p>If you have any questions or need assistance, please feel free to contact our support team at <a href='mailto:support@celestialwatches.com'>support@celestialwatches.com</a>.</p>
              <p>We appreciate your business and hope you enjoy your purchase!</p>
              <p>Best Regards,<br>Celestial Watches Team</p>
            </div>
            <div class='footer'>
              <p>&copy; " . date('Y') . " Celestial Watches. All rights reserved.</p>
            </div>
          </div>
        </body>
        </html>";

        $mail->Body    = $mailBody;
        $mail->AltBody = "Thank you for your order, {$customerName}! Order ID: {$transactionId}, Order Date: {$orderDate}, Total: {$currencySymbol}{$grandTotal}. Shipping Address: {$address}. For details, please visit our website.";
        $mail->addEmbeddedImage('../../celestial-logo.png', 'logo_cid');
        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
    }

    echo json_encode([
        'success' => true,
        'transaction_id' => $transactionId,
        'currency' => $transactionData['currency']
    ]);
    exit;
}
?>