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

// Fetch customer communications from the database
$query = "SELECT * FROM customer_communications ORDER BY communication_date DESC";
$result = mysqli_query($conn, $query);
$communications = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Communications | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
</head>
<body>
  <div class="container mt-5">
    <h1>Customer Communications</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Communication ID</th>
          <th>Customer</th>
          <th>Message</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($communications as $communication): ?>
          <tr>
            <td><?= htmlspecialchars($communication['communication_id']) ?></td>
            <td><?= htmlspecialchars($communication['customer_name']) ?></td>
            <td><?= htmlspecialchars($communication['message']) ?></td>
            <td><?= date("d M Y, H:i", strtotime($communication['communication_date'])) ?></td>
            <td>
              <a href="view_communication.php?communication_id=<?= urlencode($communication['communication_id']) ?>" class="btn btn-info">View</a>
              <a href="reply_communication.php?communication_id=<?= urlencode($communication['communication_id']) ?>" class="btn btn-success">Reply</a>
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