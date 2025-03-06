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

// Fetch payment history from the database
$query = "SELECT * FROM payments ORDER BY payment_date DESC";
$result = mysqli_query($conn, $query);
$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment History | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
</head>
<body>
  <div class="container mt-5">
    <h1>Payment History</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Payment ID</th>
          <th>Transaction ID</th>
          <th>Amount</th>
          <th>Payment Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($payments as $payment): ?>
          <tr>
            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
            <td><?= htmlspecialchars($payment['transaction_id']) ?></td>
            <td>$<?= number_format($payment['amount'], 2) ?></td>
            <td><?= date("d M Y, H:i", strtotime($payment['payment_date'])) ?></td>
            <td><?= htmlspecialchars($payment['status']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="view_order.php" class="btn btn-primary">Back to Orders</a>
  </div>
</body>
</html>
<?php ob_end_flush(); ?>