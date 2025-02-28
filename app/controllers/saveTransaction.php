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

// A helper function to clean numeric strings
function cleanNumber($value)
{
    // Remove any character that is not a digit or a dot.
    return preg_replace('/[^\d.]/', '', $value);
}

// Extract additional fields from transactionData.
$total_amount    = isset($transactionData['total_amount'])    ? floatval(cleanNumber($transactionData['total_amount']))    : 0;
$shipping_cost   = isset($transactionData['checkoutShipping']) ? floatval(cleanNumber($transactionData['checkoutShipping'])) : 0;
$tax_amount      = isset($transactionData['checkoutTax'])      ? floatval(cleanNumber($transactionData['checkoutTax']))      : 0;
$discount_amount = isset($transactionData['checkoutDiscount']) ? floatval(cleanNumber($transactionData['checkoutDiscount'])) : 0;
$subtotal_amount = isset($transactionData['checkoutSubtotal']) ? floatval(cleanNumber($transactionData['checkoutSubtotal'])) : 0;
$currency        = isset($transactionData['currency'])         ? $transactionData['currency'] : 'USD';

// Format prices for storage
$total_amount = number_format($total_amount, 2, '.', '');
$shipping_cost = number_format($shipping_cost, 2, '.', '');
$tax_amount = number_format($tax_amount, 2, '.', '');
$discount_amount = number_format($discount_amount, 2, '.', '');
$subtotal_amount = number_format($subtotal_amount, 2, '.', '');

$order_status    = 'pending';
$payment_method  = isset($transactionData['customer']['paymentMethod'])
    ? $transactionData['customer']['paymentMethod'] : '';
$tracking_number = isset($transactionData['trackingNumber']) ? $transactionData['trackingNumber'] : '';

// Prepare an INSERT statement that includes all desired fields.
$stmt = $conn->prepare("INSERT INTO transactions 
    (transaction_id, user_id, details, total_amount, shipping_cost, tax_amount, discount_amount, subtotal_amount, currency, order_status, payment_method, tracking_number, payment_status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit;
}

// Bind parameters. The type string "sisddddsssssss" corresponds to:
// s: transaction_id (string)
// i: user_id (int)
// s: details (string)
// d: total_amount (double)
// d: shipping_cost (double)
// d: tax_amount (double)
// d: discount_amount (double)
// d: subtotal_amount (double)
// s: currency (string)
// s: order_status (string)
// s: payment_method (string)
// s: tracking_number (string)
// s: payment_status (string)
// s: created_at (string)

$stmt->bind_param("sisddddsssssss", $transaction_id, $user_id, $details_json, $total_amount, $shipping_cost, $tax_amount, $discount_amount, $subtotal_amount, $currency, $order_status, $payment_method, $tracking_number, $payment_status, $created_at);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to save transaction: ' . $stmt->error]);
    exit;
}

// Remove purchased items from wishlist (if applicable)
if (isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist'])) {
    foreach ($transactionData['cart'] as $item) {
        $product_id = $item['id'] ?? null;
        if ($product_id && in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function ($pid) use ($product_id) {
                return $pid != $product_id;
            });
        }
    }
}

echo json_encode(['success' => true]);
exit;
