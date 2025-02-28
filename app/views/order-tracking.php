<?php
session_start();
define('ALLOW_ACCESS', true);
require_once '../config/conn.php';

$currencySymbols = [
  'usd' => '$',
  'eur' => '€',
  'inr' => '₹',
  'gbp' => '£',
  'jpy' => '¥',
  'aud' => 'A$',
  'cny' => '¥',
];

if (!isset($_GET['transaction_id']) || !isset($_SESSION['user_id'])) {
  header('Location: /index.php');
  exit;
}
$transaction_id = $_GET['transaction_id'];
$stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ? AND user_id = ?");
$user_id = $_SESSION['user_id'] ?? 0;
$stmt->bind_param("si", $transaction_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
  echo "Transaction not found.";
  exit;
}
$transaction = $result->fetch_assoc();
$details = json_decode($transaction['details'], true);

// Determine active timeline step based on order_status.
// Map: pending/processing = step 1, shipped = step 2, in transit = step 3, completed/delivered = step 4.
$activeStep = 1;
$payment_status = strtolower($transaction['payment_status']);
$order_status = strtolower($transaction['order_status']);

if ($payment_status === 'success') {
  $activeStep = 2;
}
if ($order_status === 'shipped' || $order_status === 'in transit') {
  $activeStep = 3;
} elseif ($order_status === 'completed' || $order_status === 'delivered') {
  $activeStep = 4;
}

// Define the timeline steps
$timelineSteps = [
  1 => ['title' => 'Order Placed', 'desc' => 'Your order has been placed.'],
  2 => ['title' => 'Order Paid', 'desc' => 'Your payment has been received.'],
  3 => ['title' => 'Shipped', 'desc' => 'Your order has been shipped.'],
  4 => ['title' => 'Delivered', 'desc' => 'Your order has been delivered.']
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Tracking</title>

  <!-- ============= IONICONS =============  -->
  <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
  <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


  <!-- ============= JS =============  -->
  <script src="/src/assets/js/navigation.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>


  <!-- ============= CSS =============  -->
  <link rel="stylesheet" href="/src/assets/css/deskView.css" />
  <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="/src/assets/css/google-header.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />


  <!-- ============= FONTS=============  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Roboto:wght@300&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">


  <style>
    .tracking-page-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    /* Header / Title */
    .order-header {
      margin-bottom: 30px;
      text-align: center;
    }

    .order-header h1 {
      font-size: 24px;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .order-header .sub-info {
      font-size: 14px;
      color: #777;
    }

    /* Main Layout: 2 Columns */
    .tracking-main {
      display: grid;
      grid-template-columns: 1fr 0.8fr;
      gap: 30px;
    }

    /* Left Column */
    .left-column {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .card-box {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      padding: 20px;
    }

    .addresses {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .address-box h3 {
      font-size: 16px;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .address-box p {
      font-size: 14px;
      margin: 3px 0;
      line-height: 1.4;
    }

    .order-items h3 {
      font-size: 16px;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .item-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
    }

    .item-info p {
      margin: 2px 0;
      font-size: 14px;
    }

    .order-summary {
      margin-top: 20px;
      font-size: 14px;
    }

    .order-summary p {
      margin: 5px 0;
      display: flex;
      justify-content: space-between;
    }

    .order-summary p span {
      font-weight: 500;
    }

    .order-summary p strong {
      font-weight: 600;
    }

    /* Right Column: Timeline / Tracking */
    .right-column {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      padding: 20px;
    }

    .tracking-status h3 {
      font-size: 16px;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .status-badges {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 20px;
      justify-content: center;
    }

    .badge {
      font-size: 12px;
      background-color: #eee;
      padding: 6px 10px;
      border-radius: 4px;
      color: #555;
    }

    .badge.active {
      background-color: #007bff;
      color: #fff;
    }

    /* Timeline List */
    .timeline-list {
      list-style: none;
      margin: 0;
      padding: 0;
      border-left: 2px solid #eee;
    }

    .timeline-item {
      position: relative;
      padding: 15px 0 15px 30px;
      margin-left: -4px;
    }

    .timeline-item::before {
      content: "";
      position: absolute;
      left: -7px;
      top: 20px;
      width: 12px;
      height: 12px;
      background-color: #fff;
      border: 2px solid #ddd;
      border-radius: 50%;
      z-index: 1;
    }

    .timeline-item.active::before {
      border-color: #007bff;
      background-color: #007bff;
    }

    .timeline-item h4 {
      font-size: 14px;
      margin: 0 0 5px;
      font-weight: 600;
    }

    .timeline-item p {
      font-size: 13px;
      margin: 0;
      color: #777;
    }

    /* Download Invoice Button */
    .download-invoice-btn {
      display: inline-block;
      padding: 12px 20px;
      background-color: #007bff;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
      margin-top: 20px;
    }

    .download-invoice-btn:hover {
      background-color: #0056b3;
    }

    @media (max-width: 900px) {
      .tracking-main {
        grid-template-columns: 1fr;
      }

      .addresses {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <?php include '../../PHP/components/navbar.php'; ?>

  <!-- Loading Spinner -->
  <div id="loading-spinner" style="display: none;">
    <div class="spinner"></div>
  </div>

  <style>
    #loading-spinner {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .spinner {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #007bff;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>

  <div class="tracking-page-container">
    <!-- Header -->
    <div class="order-header">
      <h1>Order ID: <?= htmlspecialchars($transaction_id); ?></h1>
      <div class="sub-info">
        <span>Your order progress is shown below.</span>
      </div>
    </div>

    <!-- Main 2-column Layout -->
    <div class="tracking-main">
      <!-- LEFT COLUMN -->
      <div class="left-column">
        <!-- Addresses Card -->
        <div class="card-box">
          <div class="addresses">
            <div class="address-box">
              <h3>Seller Address</h3>
              <p>Celestial Watches</p>
              <p>Marwadi University</p>
              <p>Rajkot, Gujarat 360001</p>
              <p>India</p>
            </div>
            <div class="address-box">
              <h3>Buyer Address</h3>
              <p><?= htmlspecialchars($details['customer']['firstName'] . ' ' . $details['customer']['lastName']); ?></p>
              <p><?= htmlspecialchars($details['customer']['address'] ?? ''); ?></p>
              <p>
                <?= htmlspecialchars($details['customer']['city'] ?? ''); ?>,
                <?= htmlspecialchars($details['customer']['state'] ?? ''); ?>
                <?= htmlspecialchars($details['customer']['zip'] ?? ''); ?>
              </p>
              <p><?= htmlspecialchars($details['customer']['country'] ?? ''); ?></p>
            </div>
          </div>
        </div>

        <!-- Order Items Card -->
        <div class="card-box order-items">
          <h3>Order Items</h3>
          <?php foreach ($details['cart'] as $item): ?>
            <div class="item-row">
              <div class="item-info" style="display: flex; align-items: center; gap: 10px;">
                <?php if (!empty($item['image'])): ?>
                  <img src="<?= htmlspecialchars($item['image']) ?>" alt="Product Image" style="width:60px; height:60px; object-fit: cover; border-radius: 4px;">
                <?php endif; ?>
                <div>
                  <p><a href="details.php?id=<?= urlencode($item['id']) ?>"><strong><?= htmlspecialchars($item['name']); ?></strong></a></p>
                  <p>
                    <?= htmlspecialchars($item['quantity']); ?> x
                    <?= htmlspecialchars($currencySymbols[strtolower($transaction['currency'])]) . ' ' . number_format(
                      ($item['quantity'] <= 1)
                        ? $transaction['subtotal_amount']
                        : ($transaction['total_amount'] / $item['quantity']),
                      2
                    ); ?>
                  </p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="order-summary">
            <p><span>Payment Status:</span> <strong><?= htmlspecialchars($transaction['payment_status']); ?></strong></p>
            <p><span>Order Status:</span> <strong><?= htmlspecialchars($transaction['order_status']); ?></strong></p>
            <p><span>Tax:</span> <strong><?= htmlspecialchars($currencySymbols[strtolower($transaction['currency'])]) . ' ' . number_format($transaction['tax_amount'] ?? 0, 2); ?></strong></p>
            <p><span>Total Amount:</span> <strong><?= htmlspecialchars($currencySymbols[strtolower($transaction['currency'])]) . ' ' . number_format($transaction['total_amount'], 2); ?></strong></p>
            <p><span>Order Date:</span> <strong><?= htmlspecialchars($transaction['created_at']); ?></strong></p>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="right-column tracking-status">
        <h3>Tracking Status</h3>
        <!-- Dynamic Status Badges -->
        <div class="status-badges">
          <?php foreach ($timelineSteps as $step => $data): ?>
            <span class="badge <?= ($step <= $activeStep) ? 'active' : '' ?>">
              <?= $data['title'] ?>
            </span>
          <?php endforeach; ?>
        </div>

        <!-- Timeline of Shipping Events -->
        <ul class="timeline-list">
          <?php foreach ($timelineSteps as $step => $data): ?>
            <li class="timeline-item <?= ($step <= $activeStep) ? 'active' : '' ?>">
              <h4><?= $data['title'] ?></h4>
              <p><?= $data['desc'] ?></p>
            </li>
          <?php endforeach; ?>
        </ul>

        <?php if (!empty($transaction['tracking_number'])): ?>
          <div class="tracking-info" style="margin-top: 20px;">
            <h4>Tracking Details</h4>
            <p>Tracking Number: <?= $transaction['tracking_number'] ?></p>
            <a href="../controllers/tracking.php?= $transaction['tracking_number'] ?>"
              class="tracking-link">
              View Live Tracking
            </a>
          </div>
        <?php endif; ?>

        <!-- Download Invoice Button -->
        <a class="download-invoice-btn" href="/app/models/generateInvoice.php?transaction_id=<?= urlencode($transaction_id); ?>">
          Download Invoice
        </a>
      </div>
    </div>
  </div>

  <?php include '../../PHP/components/footer.php'; ?>

  <!-- Additional JS Files -->
  <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
  <script src="/src/assets/js/index.js" async></script>
  <script src="/src/assets/js/currency-language.js" async></script>
  <script src="/src/assets/js/cookie-monitor.js" async></script>
  <script src="/src/assets/js/imagePreview.js" async></script>
  <script>
    document.querySelector('.download-invoice-btn').addEventListener('click', (e) => {
      e.preventDefault();

      // Show the loading spinner
      document.getElementById('loading-spinner').style.display = 'flex';

      const transactionId = '<?= urlencode($transaction_id); ?>';
      fetch(`/app/models/generateInvoice.php?transaction_id=${transactionId}`)
        .then(response => response.blob())
        .then(blob => {
          // Hide the loading spinner
          document.getElementById('loading-spinner').style.display = 'none';

          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.style.display = 'none';
          a.href = url;
          a.download = `Invoice_${transactionId}.pdf`;
          document.body.appendChild(a);
          a.click();
          window.URL.revokeObjectURL(url);
        })
        .catch(() => {
          showNotification('Failed to download invoice.', true);
          document.getElementById('loading-spinner').style.display = 'none';
        });
    });
  </script>
</body>

</html>
<?php ob_end_flush(); ?>