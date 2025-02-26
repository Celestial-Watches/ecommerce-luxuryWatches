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
$query = "SELECT transaction_id, tracking_number, order_status, created_at FROM transactions WHERE transaction_id = '$transaction_id'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Transaction not found.");
}
$order = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Tracking | Admin Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="/src/assets/css/panel.css">
  <style>
    .tracking-container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      color: #333;
    }
    .tracking-container h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }
    .tracking-info {
      margin-bottom: 30px;
      font-size: 16px;
    }
    .tracking-info p {
      margin: 10px 0;
    }
    .update-form {
      margin-top: 20px;
    }
    .update-form input[type="text"] {
      padding: 10px;
      font-size: 16px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .update-form button {
      margin-top: 10px;
      padding: 10px 20px;
      background: #222;
      color: #fff;
      border: none;
      border-radius: 4px;
      text-transform: uppercase;
      font-size: 14px;
      transition: background 0.3s;
      cursor: pointer;
    }
    .update-form button:hover {
      background: #444;
    }
    .back-link {
      text-align: center;
      display: block;
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
  <div class="tracking-container">
    <h1>Order Tracking - <?= htmlspecialchars($order['transaction_id']) ?></h1>
    <div class="tracking-info">
      <p><strong>Order Date:</strong> <?= date("d M Y, H:i", strtotime($order['created_at'])) ?></p>
      <p><strong>Order Status:</strong> <?= htmlspecialchars($order['order_status']) ?></p>
      <p><strong>Current Tracking Number:</strong> 
        <?= !empty($order['tracking_number']) ? htmlspecialchars($order['tracking_number']) : '<span class="text-muted">Not updated yet</span>' ?>
      </p>
    </div>
    <div class="update-form">
      <h4>Update Tracking Number</h4>
      <input type="text" id="new_tracking_number" placeholder="Enter new tracking number" required>
      <button id="updateTrackingBtn">Update Tracking</button>
    </div>
    <a href="view_order.php" class="back-link">← Back to Orders</a>
  </div>
  
  <!-- jQuery for AJAX -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Flag to indicate unsaved changes in tracking update state.
    let isTrackingEditing = true;
    
    $("#updateTrackingBtn").on("click", function() {
      var newTracking = $("#new_tracking_number").val().trim();
      if(newTracking === "") {
        alert("Please enter a tracking number.");
        return;
      }
      
      if(!confirm("Are you sure you want to update the tracking number?")) {
        return;
      }
      
      $.ajax({
        url: "update-tracking.php",
        type: "POST",
        data: {
          transaction_id: "<?= htmlspecialchars($order['transaction_id']) ?>",
          tracking_number: newTracking
        },
        success: function(response) {
          try {
            var res = JSON.parse(response);
            if(res.success) {
              alert("Tracking number updated successfully.");
              isTrackingEditing = false; // mark editing as complete
              location.reload(); // refresh page to reflect update
            } else {
              alert("Failed to update tracking: " + res.message);
            }
          } catch(e) {
            alert("Unexpected error occurred.");
          }
        },
        error: function() {
          alert("Error updating tracking number.");
        }
      });
    });
    
    // Warn admin on refresh/navigation if unsaved changes exist in tracking state.
    window.addEventListener("beforeunload", function(e) {
      if(isTrackingEditing) {
        e.preventDefault();
        e.returnValue = "You have unsaved changes. Are you sure you want to leave?";
        return "You have unsaved changes. Are you sure you want to leave?";
      }
    });
  </script>
  
  <script src="/src/assets/js/panelNav.js" async></script>
  <script src="/src/assets/js/navigation.js" async></script>
</body>
</html>
<?php ob_end_flush(); ?>
