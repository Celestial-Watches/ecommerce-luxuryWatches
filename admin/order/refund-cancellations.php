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

// Fetch refund/cancellation requests from the database
$query = "SELECT * FROM refund_requests ORDER BY request_date DESC";
$result = mysqli_query($conn, $query);
$requests = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Refund/Cancellation Requests | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
</head>
<body>
  <div class="container mt-5">
    <h1>Refund/Cancellation Requests</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Transaction ID</th>
          <th>Customer</th>
          <th>Amount</th>
          <th>Request Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request): ?>
          <tr>
            <td><?= htmlspecialchars($request['request_id']) ?></td>
            <td><?= htmlspecialchars($request['transaction_id']) ?></td>
            <td><?= htmlspecialchars($request['customer_name']) ?></td>
            <td>$<?= number_format($request['amount'], 2) ?></td>
            <td><?= date("d M Y, H:i", strtotime($request['request_date'])) ?></td>
            <td><?= htmlspecialchars($request['status']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="view_order.php" class="btn btn-primary">Back to Orders</a>
  </div>
</body>
</html>
<?php ob_end_flush(); ?>