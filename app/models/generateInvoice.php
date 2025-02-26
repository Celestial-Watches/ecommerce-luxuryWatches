<?php
session_start();
require_once '../config/conn.php';
require_once('../../vendor/autoload.php');

use Spatie\Browsershot\Browsershot;

// Validate and retrieve transaction data
if (!isset($_GET['transaction_id'])) {
  die("Transaction ID required.");
}
$transaction_id = $_GET['transaction_id'];
$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ? AND user_id = ?");
$stmt->bind_param("si", $transaction_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
  die("Transaction not found.");
}
$transaction = $result->fetch_assoc();
$details = json_decode($transaction['details'], true);

// Prepare invoice data
$invoice_date = date("d/m/Y", strtotime($transaction['created_at']));
$invoice_no   = rand(10000, 99999);

// Shipping info from customer data
$customer = $details["customer"];
$invoiced_to_name    = $customer['firstName'] . " " . $customer['lastName'];
$invoiced_to_address = $customer['address'] . ", " . $customer['city'] . ", " . $customer['zip'] . ", " . $customer['country'];

// Merchant info (static)
$merchant_name    = "Celestial Watches Inc";
$merchant_address = "Marwadi University, Rajkot, Gujarat, India";
$merchant_email   = "celestialwatches69@gmail.com";

// --- Build Items Table ---
$items_html = "";
$sub_total  = 0;
foreach ($details['cart'] as $item) {
  $item_name  = htmlspecialchars($item['name']);
  $sku        = isset($item['sku']) ? htmlspecialchars($item['sku']) : '-';
  $quantity   = htmlspecialchars($item['quantity']);
  $unit_price = number_format($item['numericPrice'], 2);
  $line_total = number_format($item['numericPrice'] * $item['quantity'], 2);
  $sub_total += $item['numericPrice'] * $item['quantity'];

  $items_html .= '
    <tr>
      <td>
         <strong>' . $item_name . '</strong><br>
         <small>SKU: ' . $sku . '</small>
      </td>
      <td style="text-align:center;">' . $quantity . '</td>
      <td style="text-align:right;">$' . $unit_price . '</td>
      <td style="text-align:right;">$' . $line_total . '</td>
    </tr>';
}

// --- Use Checkout Totals Logic ---
$shipping = 5.00;
$discount = 0;
if ((isset($details['coupon']) && $details['coupon'] === 'SAVE10') ||
  (isset($customer['coupon']) && $customer['coupon'] === 'SAVE10')
) {
  $discount = $sub_total * 0.10;
}
$tax_calc = ($sub_total - $discount) * 0.08;
$tax      = number_format($tax_calc, 2);
$total    = number_format($sub_total - $discount + $shipping + $tax_calc, 2);

// --- Billing Information ---
$billing_same = isset($customer["billing"]["sameAsShipping"]) && $customer["billing"]["sameAsShipping"] === true;
if (!$billing_same && isset($customer["billing"])) {
  $billing_address = !empty($customer["billing"]["address"]) ? $customer["billing"]["address"] : $customer["address"];
  $billing_city    = !empty($customer["billing"]["city"]) ? $customer["billing"]["city"] : $customer["city"];
  $billing_zip     = !empty($customer["billing"]["zip"]) ? $customer["billing"]["zip"] : $customer["zip"];
  $billing_country = !empty($customer["billing"]["country"]) ? $customer["billing"]["country"] : $customer["country"];
} else {
  $billing_address = $customer["address"];
  $billing_city    = $customer["city"];
  $billing_zip     = $customer["zip"];
  $billing_country = $customer["country"];
}
$billing_name  = $invoiced_to_name;
$billing_phone = isset($customer["phone"]) ? $customer["phone"] : "N/A";

// --- Payment Method ---
$payment_method  = isset($customer["paymentMethod"]) ? $customer["paymentMethod"] : "N/A";
$payment_display = ($payment_method === "credit_card") ? "Credit/Debit Card" : ucfirst($payment_method);

// Build the complete HTML invoice with print-friendly design
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #' . $invoice_no . '</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    @page {
      size: A4;
      margin: 10mm;
    }
    @media print {
      body {
        -webkit-print-color-adjust: exact;
      }
      .invoice-container {
        page-break-after: avoid;
        page-break-before: avoid;
      }
      table, tr, td, th {
        page-break-inside: avoid;
      }
    }
    body {
      font-family: "Poppins", sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f2f5;
    }
    .invoice-container {
      max-width: 800px;
      margin: 20px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .invoice-header {
      background: linear-gradient(90deg, #4b79a1, #283e51);
      color: #fff;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .invoice-header h1 {
      font-size: 20px;
      margin: 0;
    }
    .invoice-meta {
      text-align: right;
      font-size: 12px;
    }
    .invoice-details {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }
    .invoice-details .billed-to,
    .invoice-details .pay-to {
      width: 48%;
      padding: 10px;
      background: #f7f9fb;
      border-radius: 4px;
      font-size: 12px;
    }
    .invoice-details h4 {
      margin-bottom: 8px;
      font-size: 14px;
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
      font-size: 12px;
    }
    table th {
      background-color: #f0f0f0;
      color: #333;
      padding: 8px;
      text-align: left;
    }
    table td {
      padding: 8px;
      border-bottom: 1px solid #e0e0e0;
      color: #555;
    }
    .totals {
      float: right;
      width: 40%;
      margin-top: 10px;
      font-size: 12px;
    }
    .totals table td {
      padding: 5px;
    }
    .totals tr.total-row td {
      font-weight: 600;
      border-top: 1px solid #ddd;
      font-size: 14px;
    }
    .info-section {
      margin-top: 15px;
      padding: 15px;
      background: #f7f9fb;
      border-radius: 4px;
      overflow: hidden;
      font-size: 12px;
    }
    .info-section div {
      width: 48%;
      float: left;
    }
    .info-section div:last-child {
      float: right;
      text-align: right;
    }
    .info-section h4 {
      margin-bottom: 8px;
      font-size: 14px;
      color: #333;
    }
    .clear {
      clear: both;
    }
    p.footer {
      text-align: center;
      font-size: 12px;
      color: #777;
      margin-top: 15px;
    }
    a {
      color: #4b79a1;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <div class="invoice-header">
      <h1>Invoice # ' . $invoice_no . '</h1>
      <div class="invoice-meta">
        <p>Date: ' . $invoice_date . '</p>
        <p>Transaction ID: ' . htmlspecialchars($transaction_id) . '</p>
      </div>
    </div>
    
    <div class="invoice-details">
      <div class="billed-to">
        <h4>Billed To:</h4>
        <p>' . htmlspecialchars($invoiced_to_name) . '<br>' . htmlspecialchars($invoiced_to_address) . '</p>
      </div>
      <div class="pay-to">
        <h4>Pay To:</h4>
        <p>' . htmlspecialchars($merchant_name) . '<br>' . htmlspecialchars($merchant_address) . '<br>' . htmlspecialchars($merchant_email) . '</p>
      </div>
    </div>
    
    <!-- Items Table -->
    <table>
      <thead>
        <tr>
          <th style="width:50%;">Description</th>
          <th style="text-align:center; width:10%;">QTY</th>
          <th style="text-align:right; width:20%;">Unit Price</th>
          <th style="text-align:right; width:20%;">Amount</th>
        </tr>
      </thead>
      <tbody>
        ' . $items_html . '
      </tbody>
    </table>
    
    <!-- Totals Section -->
    <div class="totals">
      <table>
        <tr>
          <td style="text-align:right;">Subtotal:</td>
          <td style="text-align:right;">$' . number_format($sub_total, 2) . '</td>
        </tr>
        <tr>
          <td style="text-align:right;">Discount:</td>
          <td style="text-align:right;">-$' . number_format($discount, 2) . '</td>
        </tr>
        <tr>
          <td style="text-align:right;">Shipping &amp; Handling:</td>
          <td style="text-align:right;">$' . number_format($shipping, 2) . '</td>
        </tr>
        <tr>
          <td style="text-align:right;">Tax (8%):</td>
          <td style="text-align:right;">$' . $tax . '</td>
        </tr>
        <tr class="total-row">
          <td style="text-align:right;">Total:</td>
          <td style="text-align:right;">$' . $total . '</td>
        </tr>
      </table>
    </div>
    <div class="clear"></div>
    
    <!-- Billing & Payment Information -->
    <div class="info-section">
      <div>
        <h4>Billing Information</h4>
        <p>' . htmlspecialchars($billing_name) . '<br>' .
  htmlspecialchars($billing_address) . '<br>' .
  htmlspecialchars($billing_city) . ', ' .
  htmlspecialchars($billing_zip) . '<br>' .
  htmlspecialchars($billing_country) . '<br> ' .
  htmlspecialchars($billing_phone) . '</p>
      </div>
      <div>
        <h4>Payment Method</h4>
        <p>' . htmlspecialchars($payment_display) . '<br>' .
  'Transaction ID: ' . htmlspecialchars($transaction["transaction_id"]) . '<br>' .
  '<a href="#">Right of Withdrawal</a></p>
      </div>
      <div class="clear"></div>
    </div>
    
    <p class="footer">Thank you for your business!</p>
  </div>
</body>
</html>
';

// Generate PDF using Browsershot with full CSS support
$pdfContent = Browsershot::html($html)
  ->noSandbox()
  ->timeout(120000)
  ->waitUntilNetworkIdle()
  ->paper('A4')
  ->format('A4')
  ->pdf();

// Send PDF headers and output PDF for download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Invoice_' . $transaction_id . '.pdf"');
echo $pdfContent;
exit;
