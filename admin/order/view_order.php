<?php
session_start();
session_regenerate_id(true);
ob_start();

define('ALLOW_ACCESS', true);
include '../panel.php';
require '../../app/config/conn.php';

if (
    !isset($_SESSION['user'], $_SESSION['admin'], $_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true
) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

// Query to fetch orders along with customer info
$query = "SELECT 
            t.transaction_id, 
            t.total_amount, 
            t.order_status, 
            t.payment_status, 
            t.created_at, 
            u.username AS customer_name, 
            u.email AS customer_email 
          FROM transactionss t 
          LEFT JOIN users u ON t.user_id = u.id 
          ORDER BY t.created_at DESC";
$result = mysqli_query($conn, $query);
$orders = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="Admin Panel - View Orders">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/src/assets/css/panel.css">
    <title>View Orders | Admin Panel</title>
    <style>
        .ViewOrdercontainer {
            margin: 40px auto;
            max-width: 1200px;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .ViewOrdercontainer h1 {
            font-size: 36px;
            margin-bottom: 30px;
            color: #222;
            text-align: center;
        }

        .filter-section {
            margin-bottom: 20px;
            text-align: center;
        }

        .filter-section select {
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
            appearance: none;
        }

        .orders-table th,
        .orders-table td {
            vertical-align: middle;
            font-size: 16px;
        }

        .orders-table th {
            background: #fafafa;
            border-bottom: 2px solid #e0e0e0;
        }

        .orders-table tbody tr {
            transition: background 0.3s ease;
        }

        .orders-table tbody tr:hover {
            background: #f9f9f9;
        }

        .action-btn {
            font-size: 14px;
            padding: 6px 10px;
            margin-right: 5px;
            border-radius: 4px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .btn-details {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        .btn-details:hover {
            background-color: #0056b3;
        }

        .btn-track {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }

        .btn-track:hover {
            background-color: #5a6268;
        }

        /* Container for AJAX loaded content */
        #orderContent {
            margin-top: 30px;
        }
    </style>
</head>

<body>
   <div class="ViewOrdercontainer">
        <h1>View Orders</h1>
        <div class="filter-section">
            <select id="order-filter">
                <option value="">All Orders</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <?php if (empty($orders)): ?>
            <p class="text-center" style="font-size:18px;">No orders found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr data-status="<?= htmlspecialchars(strtolower($order['order_status'])) ?>">
                                <td><?= htmlspecialchars($order['transaction_id']) ?></td>
                                <td><?= htmlspecialchars($order['customer_name'] . " (" . $order['customer_email'] . ")") ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td><?= htmlspecialchars($order['order_status']) ?></td>
                                <td><?= htmlspecialchars($order['payment_status']) ?></td>
                                <td><?= date("d M Y", strtotime($order['created_at'])) ?></td>
                                <td>
                                    <button class="action-btn btn-details" onclick="loadOrderDetails('<?= urlencode($order['transaction_id']) ?>')">Details</button>
                                    <button class="action-btn btn-track" onclick="loadOrderTracking('<?= urlencode($order['transaction_id']) ?>')">Track</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <!-- Container where AJAX content will be loaded -->
        <div id="orderContent"></div>
    </div>

    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Set the initial state as 'list'
        let currentState = "list";

        // Function to load order details via AJAX
        function loadOrderDetails(transaction_id) {
            currentState = "details";
            $.ajax({
                url: "order-details.php",
                data: {
                    transaction_id: transaction_id
                },
                success: function(response) {
                    $("#orderContent").html(response);
                },
                error: function() {
                    alert("Failed to load order details.");
                }
            });
        }

        // Function to load order tracking via AJAX
        function loadOrderTracking(transaction_id) {
            currentState = "tracking";
            $.ajax({
                url: "order-tracking.php",
                data: {
                    transaction_id: transaction_id
                },
                success: function(response) {
                    $("#orderContent").html(response);
                },
                error: function() {
                    alert("Failed to load order tracking.");
                }
            });
        }

        // Filter orders by order_status
        $("#order-filter").on("change", function() {
            var selectedStatus = $(this).val();
            $(".orders-table tbody tr").each(function() {
                if (!selectedStatus || $(this).data("status") === selectedStatus) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Ask for confirmation on page refresh when in details or tracking state
        window.addEventListener("beforeunload", function(e) {
            if (currentState === "details" || currentState === "tracking") {
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