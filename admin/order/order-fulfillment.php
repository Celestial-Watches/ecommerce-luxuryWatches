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

// Fetch orders that need fulfillment from the database
$query = "SELECT * FROM transactions WHERE order_status = 'processing' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Processing & Fulfillment | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
</head>
<body>
  <div class="container mt-5">
    <h1>Order Processing & Fulfillment</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Total Amount</th>
          <th>Order Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?= htmlspecialchars($order['transaction_id']) ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td>$<?= number_format($order['total_amount'], 2) ?></td>
            <td><?= date("d M Y, H:i", strtotime($order['created_at'])) ?></td>
            <td><?= htmlspecialchars($order['order_status']) ?></td>
            <td>
              <a href="order-details.php?transaction_id=<?= urlencode($order['transaction_id']) ?>" class="btn btn-info">View Details</a>
              <a href="order-tracking.php?transaction_id=<?= urlencode($order['transaction_id']) ?>" class="btn btn-warning">Track Order</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="view_order.php" class="btn btn-primary">Back to Orders</a>
  </div>
</body>
</html>
<?php ob_end_flush(); ?>