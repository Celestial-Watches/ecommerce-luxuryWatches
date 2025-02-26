<?php
session_start();
require_once '../config/conn.php'; 

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$transaction_id = $data['transaction_id'];
$payment_status = $data['payment_status'];
$transactionData = $data['transactionData'];
$user_id = $_SESSION['user_id'] ?? 0;
$details_json = json_encode($transactionData);
$created_at = date('Y-m-d H:i:s');

// Extract additional fields from transactionData.
// Adjust these indexes as per your front-end data structure.
$total_amount    = isset($transactionData['total_amount'])    ? floatval($transactionData['total_amount'])    : 0;
$shipping_cost   = isset($transactionData['checkoutShipping']) ? floatval($transactionData['checkoutShipping']) : 0;
$tax_amount      = isset($transactionData['checkoutTax'])      ? floatval($transactionData['checkoutTax'])      : 0;
$discount_amount = isset($transactionData['checkoutDiscount']) ? floatval($transactionData['checkoutDiscount']) : 0;
$currency        = isset($transactionData['currency']) ? $transactionData['currency']           : 'USD';
$order_status    = 'pending'; 
$payment_method  = isset($transactionData['customer']['paymentMethod']) 
                   ? $transactionData['customer']['paymentMethod'] : '';
$tracking_number = isset($transactionData['trackingNumber']) ? $transactionData['trackingNumber'] : '';

// Prepare an INSERT statement that includes all desired fields.
$stmt = $conn->prepare("INSERT INTO transactions 
    (transaction_id, user_id, details, total_amount, shipping_cost, tax_amount, discount_amount, currency, order_status, payment_method, tracking_number, payment_status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit;
}

// Bind parameters. The type string "sisddddssssss" corresponds to:
// s: transaction_id (string)
// i: user_id (int)
// s: details (string)
// d: total_amount (double)
// d: shipping_cost (double)
// d: tax_amount (double)
// d: discount_amount (double)
// s: currency (string)
// s: order_status (string)
// s: payment_method (string)
// s: tracking_number (string)
// s: payment_status (string)
// s: created_at (string)
$stmt->bind_param("sisddddssssss", $transaction_id, $user_id, $details_json, $total_amount, $shipping_cost, $tax_amount, $discount_amount, $currency, $order_status, $payment_method, $tracking_number, $payment_status, $created_at);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to save transaction: ' . $stmt->error]);
    exit;
}

// Remove purchased items from wishlist (if applicable)
if (isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist'])) {
    foreach ($transactionData['cart'] as $item) {
        $product_id = $item['id'] ?? null;
        if ($product_id && in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function($pid) use ($product_id) {
                return $pid != $product_id;
            });
        }
    }
}

echo json_encode(['success' => true]);
exit;
?>
