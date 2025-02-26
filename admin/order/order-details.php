<?php
session_start();
session_regenerate_id(true);
ob_start();

require '../../app/config/conn.php';

if (!isset($_SESSION['user'], $_SESSION['admin'], $_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

if (!isset($_GET['transaction_id']) || empty($_GET['transaction_id'])) {
    die("Transaction ID missing.");
}
$transaction_id = mysqli_real_escape_string($conn, $_GET['transaction_id']);
$query = "SELECT t.*, u.username AS customer_name, u.email AS customer_email 
          FROM transactions t 
          LEFT JOIN users u ON t.user_id = u.id 
          WHERE t.transaction_id = '$transaction_id'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Transaction not found.");
}
$transaction = mysqli_fetch_assoc($result);
$details = json_decode($transaction['details'], true);

// Handle form submission for updating order details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = mysqli_real_escape_string($conn, $_POST['order_status'] ?? $transaction['order_status']);
    $adminNote = mysqli_real_escape_string($conn, $_POST['admin_note'] ?? '');
    
    $updateQuery = "UPDATE transactions SET order_status = '$newStatus'";
    // Optionally, you might want to store the admin note in the details JSON or in a separate column.
    if (!empty($adminNote)) {
        $updateQuery .= ", details = JSON_SET(details, '$.admin_note', '$adminNote')";
    }
    $updateQuery .= " WHERE transaction_id = '$transaction_id'";
    
    if (mysqli_query($conn, $updateQuery)) {
        // Refresh the page data after update
        header("Location: order-details.php?transaction_id=" . urlencode($transaction_id));
        exit();
    } else {
        $updateError = "Failed to update order: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
  <style>
    .order-details-container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      color: #333;
    }
    .order-details-container h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    .order-summary, .customer-info, .items-section, .admin-controls {
      margin-bottom: 30px;
    }
    .order-summary p, .customer-info p {
      font-size: 16px;
      margin: 8px 0;
    }
    .items-section .item {
      padding: 15px;
      border-bottom: 1px solid #eee;
    }
    .items-section .item:last-child {
      border-bottom: none;
    }
    .admin-controls label {
      font-weight: 600;
    }
    .admin-controls select, .admin-controls textarea {
      width: 100%;
      padding: 10px;
      margin: 5px 0 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .update-btn {
      background: #222;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      text-transform: uppercase;
      font-size: 14px;
      transition: background 0.3s;
    }
    .update-btn:hover {
      background: #444;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      font-size: 16px;
      color: #007bff;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div class="order-details-container">
    <h1>Order Details - <?= htmlspecialchars($transaction['transaction_id']) ?></h1>
    <?php if (isset($updateError)): ?>
      <div class="alert alert-danger"><?= $updateError ?></div>
    <?php endif; ?>
    <div class="order-summary">
      <h3>Order Summary</h3>
      <p><strong>Date:</strong> <?= date("d M Y, H:i", strtotime($transaction['created_at'])) ?></p>
      <p><strong>Total:</strong> <?= htmlspecialchars($transaction['currency']) ?> <?= number_format($transaction['total_amount'], 2) ?></p>
      <p><strong>Shipping Cost:</strong> <?= htmlspecialchars($transaction['currency']) ?> <?= number_format($transaction['shipping_cost'], 2) ?></p>
      <p><strong>Tax:</strong> <?= htmlspecialchars($transaction['currency']) ?> <?= number_format($transaction['tax_amount'], 2) ?></p>
      <p><strong>Discount:</strong> <?= htmlspecialchars($transaction['currency']) ?> <?= number_format($transaction['discount_amount'], 2) ?></p>
      <p><strong>Payment Status:</strong> <?= htmlspecialchars($transaction['payment_status']) ?></p>
      <p><strong>Order Status:</strong> <?= htmlspecialchars($transaction['order_status']) ?></p>
      <?php if (!empty($transaction['tracking_number'])): ?>
        <p><strong>Tracking Number:</strong> <?= htmlspecialchars($transaction['tracking_number']) ?></p>
      <?php endif; ?>
    </div>
    
    <?php if (!empty($details['cart'])): ?>
    <div class="items-section">
      <h3>Items Purchased</h3>
      <?php foreach ($details['cart'] as $item): ?>
        <div class="item">
          <p><strong><?= htmlspecialchars($item['brand'] . ' ' . $item['name']) ?></strong></p>
          <p>Ref: <?= htmlspecialchars($item['ref_code']) ?></p>
          <p>Price: $<?= number_format($item['numericPrice'], 2) ?></p>
          <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
          <p>Subtotal: $<?= number_format($item['numericPrice'] * $item['quantity'], 2) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($details['customer'])): ?>
    <div class="customer-info">
      <h3>Customer &amp; Shipping Information</h3>
      <?php $customer = $details['customer']; ?>
      <p><strong>Name:</strong> <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($customer['email'] ?? $transaction['customer_email']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($customer['phone'] ?? '') ?></p>
      <p><strong>Address:</strong> <?= htmlspecialchars($customer['address'] . ', ' . $customer['city'] . ', ' . $customer['zip'] . ', ' . $customer['country']) ?></p>
    </div>
    <?php endif; ?>

    <!-- Admin Controls for Updating Order Status and Adding Admin Note -->
    <div class="admin-controls">
      <h3>Admin Controls</h3>
      <form method="POST" id="updateOrderForm">
        <label for="order_status">Update Order Status:</label>
        <select name="order_status" id="order_status" required>
          <option value="pending" <?= ($transaction['order_status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
          <option value="processing" <?= ($transaction['order_status'] === 'processing') ? 'selected' : '' ?>>Processing</option>
          <option value="shipped" <?= ($transaction['order_status'] === 'shipped') ? 'selected' : '' ?>>Shipped</option>
          <option value="completed" <?= ($transaction['order_status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
          <option value="cancelled" <?= ($transaction['order_status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <label for="admin_note">Admin Note (optional):</label>
        <textarea name="admin_note" id="admin_note" rows="3" placeholder="Enter note..."><?= isset($details['admin_note']) ? htmlspecialchars($details['admin_note']) : '' ?></textarea>
        <button type="submit" class="update-btn">Update Order</button>
      </form>
    </div>

    <a href="view_order.php" class="back-link">← Back to Orders</a>
  </div>
  
</body>
</html>
<?php ob_end_flush(); ?>
