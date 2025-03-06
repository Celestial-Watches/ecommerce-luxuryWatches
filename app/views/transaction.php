<?php
session_start();
define('ALLOW_ACCESS', true);
require '../../vendor/autoload.php';
include '../config/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

// Escape the user ID for security
$userId = mysqli_real_escape_string($conn, $_SESSION["user_id"]);

// Query transaction history for this user
$query = "SELECT transaction_id, created_at, total_amount, order_status, payment_status FROM transactionss WHERE user_id = '$userId' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$transactions = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History | Celestial Watches</title>

    <!-- IONICONS -->

    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js" integrity="sha512-a+SUDuwNzXDvz4XrIcXHuCf089/iJAoN4lmrXJg18XnduKK6YlDHNRalv4yd1N40OKI80tFidF+rqTFKGPoWFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- JS Libraries -->

    <script src="/src/assets/js/navigation.js" async></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <!-- CSS -->

    <link rel="stylesheet" href="/src/assets/css/deskView.css" />
    <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/src/assets/css/google-header.css">

    <!-- FONTS -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">



    <style>
    /* Global Styles */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      color: #333;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
    
    /* Container */
    .TranContainer {
      max-width: 1200px;
      margin: 50px auto;
      padding: 40px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .TranContainer h1 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 36px;
      font-weight: 600;
      color: #222;
    }
    
    /* Filter Dropdown */
    .filter-TranContainer {
      text-align: center;
      margin-bottom: 40px;
    }
    .filter-TranContainer select {
      padding: 12px 20px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #fff;
      outline: none;
      transition: border-color 0.3s ease;
    }
    .filter-TranContainer select:focus {
      border-color: #a29bfe;
    }
    
    /* Cards Grid */
    .transaction-cardTranss {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }
    
    /* Card Styling */
    .cardTrans {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #e0e0e0;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .cardTrans:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    .cardTrans-header {
      padding: 20px;
      background: #f0f2f5;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e0e0e0;
    }
    .cardTrans-header h3 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
      color: #333;
    }
    .cardTrans-header span {
      font-size: 14px;
      color: #777;
    }
    .cardTrans-body {
      padding: 20px;
    }
    .cardTrans-body p {
      margin: 0 0 10px;
      font-size: 16px;
      color: #555;
    }
    .cardTrans-footer {
      padding: 15px 20px;
      background: #fafafa;
      display: flex;
      justify-content: flex-end;
      border-top: 1px solid #e0e0e0;
    }
    .Transbtn {
      padding: 10px 20px;
      background: #222;
      color: #fff;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      text-transform: uppercase;
      transition: background 0.3s ease;
    }
    .Transbtn:hover {
      background: #444;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .TranContainer h1 {
        font-size: 28px;
      }
      .cardTrans-header h3 {
        font-size: 18px;
      }
      .cardTrans-body p {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <?php include '../../PHP/components/navbar.php'; ?>
  <div class="TranContainer">
    <h1>Transaction History</h1>
    <div class="filter-TranContainer">
      <select id="order-filter">
        <option value="">All Orders</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>
    <?php if (empty($transactions)): ?>
      <p style="text-align:center; font-size: 18px;">You haven't made any purchases yet.</p>
    <?php else: ?>
      <div class="transaction-cardTranss" id="transactionCardTranss">
        <?php foreach ($transactions as $tx): ?>
          <div class="cardTrans" data-status="<?= htmlspecialchars(strtolower($tx['order_status'])) ?>">
            <div class="cardTrans-header">
              <h3>Order #<?= htmlspecialchars($tx['transaction_id']) ?></h3>
              <span><?= date("d M Y", strtotime($tx['created_at'])) ?></span>
            </div>
            <div class="cardTrans-body">
              <p><strong>Total:</strong> $<?= number_format($tx['total_amount'], 2) ?></p>
              <p><strong>Status:</strong> <?= htmlspecialchars($tx['order_status']) ?></p>
            </div>
            <div class="cardTrans-footer">
              <a class="Transbtn" href="order-tracking.php?transaction_id=<?= urlencode($tx['transaction_id']) ?>">Track Order</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php include '../../PHP/components/footer.php'; ?>

  <script>
    // Filter orders by order_status
    document.getElementById('order-filter').addEventListener('change', function() {
      var selectedStatus = this.value;
      var cards = document.querySelectorAll('.transaction-cardTranss .cardTrans');
      cards.forEach(function(card) {
        if (!selectedStatus || card.getAttribute('data-status') === selectedStatus) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  </script>
  <!-- Additional JS Files -->
  <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
  <script src="/src/assets/js/index.js" async></script>
  <script src="/src/assets/js/currency-language.js" async></script>
  <script src="/src/assets/js/cookie-monitor.js" async></script>
  <script src="/src/assets/js/imagePreview.js" async></script>
</body>
</html>