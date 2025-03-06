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

// Fetch order reports from the database
$query = "SELECT * FROM order_reports ORDER BY report_date DESC";
$result = mysqli_query($conn, $query);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Reports & Analytics | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
</head>
<body>
  <div class="container mt-5">
    <h1>Order Reports & Analytics</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Report ID</th>
          <th>Report Type</th>
          <th>Generated Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reports as $report): ?>
          <tr>
            <td><?= htmlspecialchars($report['report_id']) ?></td>
            <td><?= htmlspecialchars($report['report_type']) ?></td>
            <td><?= date("d M Y, H:i", strtotime($report['report_date'])) ?></td>
            <td>
              <a href="view_report.php?report_id=<?= urlencode($report['report_id']) ?>" class="btn btn-info">View Report</a>
              <a href="download_report.php?report_id=<?= urlencode($report['report_id']) ?>" class="btn btn-success">Download Report</a>
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