<?php
session_start();
session_regenerate_id(true);
ob_start();
define('ALLOW_ACCESS', true);
require '../../app/config/conn.php';


require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// AUTH CHECK
if (
    !isset($_SESSION['user'], $_SESSION['admin'], $_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true ||
    $_SESSION['admin'] !== true
) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Update request status
    if (isset($_POST['update_status'])) {
        $request_id = (int)$_POST['request_id'];
        $new_status = $_POST['new_status'];

        // Update the requestss table
        $stmt = $conn->prepare("UPDATE requestss SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_status, $request_id);
        if ($stmt->execute()) {
            // Get the user_id of this request
            $stmtUser = $conn->prepare("SELECT user_id FROM requestss WHERE id = ?");
            $stmtUser->bind_param('i', $request_id);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();
            if ($resultUser->num_rows > 0) {
                $rowUser = $resultUser->fetch_assoc();
                $userIdOfThisRequest = $rowUser['user_id'];

                // Insert notification
                $title = "Request #$request_id Updated";
                $message = "Your request #$request_id status has been updated to $new_status.";
                $stmtNoti = $conn->prepare("
                    INSERT INTO notifications (user_id, title, message)
                    VALUES (?, ?, ?)
                ");
                $stmtNoti->bind_param('iss', $userIdOfThisRequest, $title, $message);
                $stmtNoti->execute();

                // Fetch user email from 'users' table
                $stmtEmail = $conn->prepare("SELECT email FROM users WHERE id = ?");
                $stmtEmail->bind_param('i', $userIdOfThisRequest);
                $stmtEmail->execute();
                $resEmail = $stmtEmail->get_result();
                if ($resEmail->num_rows > 0) {
                    $userEmailRow = $resEmail->fetch_assoc();
                    $userEmail = $userEmailRow['email'];

                    // SEND MAIL USING PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'celestialwatches69@gmail.com';
                        $mail->Password   = 'xvmjnggsmsnkavzt'; // Your App Password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = 465;

                        // Sender & Recipient
                        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
                        $mail->addAddress($userEmail);

                        // Email Content
                        $mail->isHTML(true); // Send as HTML
                        $mail->Subject = "Request #$request_id Updated";

                        // Create a simple HTML email
                        $htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Request #$request_id Updated</title>
  <style>
    /* Basic resets and styles */
    body {
      margin: 0; 
      padding: 0; 
      background: #f7f7f7; 
      font-family: Arial, sans-serif; 
      color: #333;
    }
    .email-container {
      max-width: 600px; 
      margin: 20px auto; 
      background: #ffffff; 
      padding: 20px; 
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .email-header {
      font-size: 20px; 
      font-weight: bold; 
      margin-bottom: 10px;
      color: #000;
    }
    .email-body {
      font-size: 14px; 
      line-height: 1.6; 
      margin-bottom: 20px;
    }
    .highlight {
      font-weight: bold; 
      color: #000;
    }
    .email-footer {
      font-size: 12px; 
      color: #999; 
      margin-top: 20px; 
      border-top: 1px solid #eee; 
      padding-top: 10px;
    }
    .brand-name {
      color: #000; 
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-header">Celestial Watches</div>
    <div class="email-body">
      <p>Hello,</p>
      <p>Your request <span class="highlight">#$request_id</span> has been updated to: 
         <span class="highlight">$new_status</span>.</p>
      <p>We appreciate your patience. If you have any questions, please reply to this email.</p>
      <p>Thank you!<br>
      <strong>Celestial Watches Support Team</strong></p>
    </div>
    <div class="email-footer">
      &copy; 2023 <a href="#" class="brand-name">Celestial Watches</a>. All rights reserved.
    </div>
  </div>
</body>
</html>
HTML;

                        $mail->Body = $htmlBody;
                        $mail->AltBody = "Hello,\n\nYour request #$request_id has been updated to: $new_status.\n\nThank you!";

                        $mail->send();
                    } catch (Exception $e) {
                        // do nothing
                    }
                }
            }

            echo json_encode(['status' => 'success', 'message' => 'Status updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
        }
        exit();
    }

    // 2) Send admin chat message
    if (isset($_POST['send_message'])) {
        $request_id = (int)$_POST['request_id'];
        $messageText = trim($_POST['message'] ?? '');
        if ($messageText) {
            $admin_id = $_SESSION['admin_id'] ?? 0;
            $stmt = $conn->prepare("
                INSERT INTO chat_messages 
                    (user_id, request_id, message, is_bot)
                VALUES (?, ?, ?, 1)
            ");
            $stmt->bind_param('iis', $admin_id, $request_id, $messageText);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Message sent.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
            }
        }
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit();
}

/* ---------------------------
   FETCH DATA FOR NORMAL PAGE LOAD
--------------------------- */

// 1) Fetch all requests (newest first)
$stmt = $conn->prepare("SELECT * FROM requestss ORDER BY id DESC");
$stmt->execute();
$requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 2) Determine current request
$currentRequestId = 0;
if (isset($_GET['request_id'])) {
    $currentRequestId = (int)$_GET['request_id'];
} elseif (!empty($requests)) {
    $currentRequestId = (int)$requests[0]['id'];
}

// 3) Fetch chat history for the selected request
$chatHistory = [];
if ($currentRequestId) {
    $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE request_id = ? ORDER BY created_at ASC");
    $stmt->bind_param('i', $currentRequestId);
    $stmt->execute();
    $chatHistory = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$currentRequestStatus = ''; 

foreach ($requests as $req) {
    if ($req['id'] === $currentRequestId) {
        $currentRequestStatus = $req['status'];
        break;
    }
}

include '../panel.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin - Manage Requests</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../src/assets/css/panel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ------------------------------
           GLOBAL / LAYOUT
        ------------------------------ */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #000;
            margin: 0;
            padding: 0;
        }

        ul{
            padding-left: 0 ;
        }

        .container-fluid {
            padding: 20px;
        }

        .row-gap-4 {
            row-gap: 1.5rem;
        }

        /* Header */
        .dashboard-header {
            margin-bottom: 20px;
        }

        .dashboard-header h2 {
            font-weight: 700;
            margin: 0;
        }

        .dashboard-subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-top: 5px;
        }

        .dashboard-date {
            text-align: right;
            color: #999;
            font-size: 0.85rem;
        }

        /* Stats (like "Finished", "Tracked", "Efficiency") */
        .stats-row {
            margin: 20px 0;
        }

        .stat-card {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .stat-title {
            font-size: 0.9rem;
            color: #777;
        }

        .stat-value {
            font-size: 1.3rem;
            font-weight: 600;
            color: #000;
        }

        .stat-subchange {
            font-size: 0.8rem;
            color: #999;
        }

        /* Performance chart */
        .performance-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
        }

        .performance-card h5 {
            font-weight: 600;
        }

        .chart-canvas {
            margin-top: 15px;
            width: 100%;
            height: 200px;
            /* Adjust as needed */
        }

        /* Current Requests */
        .requests-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
        }

        .requests-card h5 {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .request-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f0f0f0;
            padding: 10px 0;
            cursor: pointer;
        }

        .request-item:last-child {
            border-bottom: none;
        }

        .request-item strong {
            font-weight: 600;
        }

        .request-item small {
            color: #999;
        }

        .request-status {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
            background-color: #f1f1f1;
            color: #333;
        }

        .request-item:hover {
            background-color: #f8f8f8;
        }

        .active-request {
            background-color: #e8e8e8;
        }

        /* Right Column: Profile & Chat */
        .profile-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-card h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .profile-card small {
            color: #999;
        }

        .chat-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
        }

        .chat-header {
            font-weight: 600;
            margin-bottom: 15px;
        }

        .chat-messages {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .chat-message {
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            position: relative;
        }

        .chat-message p {
            margin: 0;
        }

        .chat-message-time {
            display: block;
            text-align: right;
            font-size: 0.75rem;
            color: #999;
            margin-top: 5px;
        }

        .admin-message {
            background-color: #000;
            color: #fff;
            align-self: flex-end;
        }

        .user-message {
            background-color: #f1f1f1;
            color: #000;
        }

        .chat-input textarea {
            resize: none;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100%;
            padding: 10px;
        }

        .chat-input button {
            margin-top: 10px;
            width: 100%;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px;
        }

        /* Update Status Form */
        .status-form {
            margin-top: 15px;
        }

        .status-form label {
            font-size: 0.85rem;
            color: #555;
        }

        .status-form select {
            width: 100%;
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .status-form button {
            margin-top: 10px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 15px;
        }

        /* Utility */
        .cursor-pointer {
            cursor: pointer;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row dashboard-header">
            <div class="col-8">
                <h2>Hello, Admin</h2>
                <div class="dashboard-subtitle">Track request progress here. You almost reached a goal!</div>
            </div>
            <div class="col-4 dashboard-date">
                <?php echo date('d M, Y'); ?>
            </div>
        </div>

        <!-- Stats Row (example placeholders) -->
        <div class="row stats-row">
            <div class="col-sm-3">
                <div class="stat-card">
                    <div class="stat-title">Finished</div>
                    <div class="stat-value">18</div>
                    <div class="stat-subchange">+8 tasks</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="stat-card">
                    <div class="stat-title">Tracked</div>
                    <div class="stat-value">31h</div>
                    <div class="stat-subchange text-danger">-6 hours</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="stat-card">
                    <div class="stat-title">Efficiency</div>
                    <div class="stat-value">93%</div>
                    <div class="stat-subchange text-success">+12%</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="stat-card">
                    <div class="stat-title">Pending</div>
                    <div class="stat-value">
                        <?php
                        // Example: Count how many requests are 'Under Review'
                        $pendingCount = array_reduce($requests, function ($carry, $item) {
                            return ($item['status'] === 'Under Review') ? $carry + 1 : $carry;
                        }, 0);
                        echo $pendingCount;
                        ?>
                    </div>
                    <div class="stat-subchange">Requests</div>
                </div>
            </div>
        </div>

        <!-- Middle Row: Performance Chart + Current Requests -->
        <div class="row row-gap-4">
            <!-- Performance Chart -->
            <div class="col-md-8">
                <div class="performance-card mb-4">
                    <h5>Performance</h5>
                    <!-- Actual Chart.js Canvas -->
                    <canvas id="performanceChart" class="chart-canvas"></canvas>
                </div>

                <div class="requests-card">
                    <h5>Current Requests</h5>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $r): ?>
                            <?php
                            $activeClass = ($r['id'] === $currentRequestId) ? 'active-request' : '';
                            ?>
                            <div class="request-item <?php echo $activeClass; ?> cursor-pointer"
                                onclick="window.location.href='?request_id=<?php echo $r['id']; ?>'">
                                <div>
                                    <strong>#<?php echo htmlspecialchars($r['id']); ?></strong>
                                    <br>
                                    <small><?php echo htmlspecialchars($r['exchange_watch']); ?></small>
                                </div>
                                <div class="request-status">
                                    <?php echo htmlspecialchars($r['status']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="mb-0">No requests available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Profile & Chat -->
            <div class="col-md-4">
                <!-- Profile Card (Admin Info or Placeholder) -->
                <div class="profile-card">
                    <h6><?php echo $_SESSION['user'] ?></h6>
                    <small>celestialwatches69@gmail.com</small>
                </div>

                <!-- Chat Section -->
                <?php if ($currentRequestId): ?>
                    <div class="chat-card">
                        <div class="chat-header">Activity (Request #<?php echo $currentRequestId; ?>)</div>

                        <!-- Chat Messages -->
                        <div class="chat-messages">
                            <?php foreach ($chatHistory as $msg): ?>
                                <div class="chat-message 
                            <?php echo $msg['is_bot'] ? 'admin-message' : 'user-message'; ?>">
                                    <p><?php echo htmlspecialchars($msg['message']); ?></p>
                                    <span class="chat-message-time">
                                        <?php echo date('H:i', strtotime($msg['created_at'])); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Update Status Form -->
                        <form id="updateStatusForm" class="status-form">
                            <input type="hidden" name="update_status" value="1">
                            <input type="hidden" name="request_id" value="<?= $currentRequestId; ?>">

                            <label for="new_status" class="form-label">Update Status</label>
                            <select name="new_status" id="new_status" class="form-select">
                                <option value="Pending"
                                    <?= ($currentRequestStatus === 'Pending') ? 'selected' : '' ?>>
                                    Pending
                                </option>
                                <option value="Under Evaluation"
                                    <?= ($currentRequestStatus === 'Under Evaluation') ? 'selected' : '' ?>>
                                    Under Evaluation
                                </option>
                                <option value="Approved"
                                    <?= ($currentRequestStatus === 'Approved') ? 'selected' : '' ?>>
                                    Approved
                                </option>
                                <option value="Rejected"
                                    <?= ($currentRequestStatus === 'Rejected') ? 'selected' : '' ?>>
                                    Rejected
                                </option>
                                <option value="In Transit"
                                    <?= ($currentRequestStatus === 'In Transit') ? 'selected' : '' ?>>
                                    In Transit
                                </option>
                                <option value="Completed"
                                    <?= ($currentRequestStatus === 'Completed') ? 'selected' : '' ?>>
                                    Completed
                                </option>
                            </select>

                            <button type="submit">Update</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="chat-card">
                        <div class="chat-header">Activity</div>
                        <p>Select a request to view the chat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // (A) Chart.js Setup
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const chartData = {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Requests Processed',
                data: [3, 5, 2, 6, 7, 4, 8],
                backgroundColor: 'rgba(0, 0, 0, 0.1)',
                borderColor: 'rgba(0, 0, 0, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        };
        const chartOptions = {
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#eee'
                    },
                    ticks: {
                        color: '#000'
                    }
                },
                x: {
                    grid: {
                        color: '#eee'
                    },
                    ticks: {
                        color: '#000'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#000'
                    }
                }
            }
        };
        const performanceChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: chartOptions
        });

        // (B) Update Status Form Submission (AJAX)
        updateStatusForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch('', {
                method: 'POST',
                body: new URLSearchParams(formData)
            });
            const data = await response.json();
            if (data.status === 'success') {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message);
            }
        });

        // (C) Send Message Form Submission (AJAX)
        const sendMessageForm = document.getElementById('sendMessageForm');
        if (sendMessageForm) {
            sendMessageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const response = await fetch('', {
                    method: 'POST',
                    body: new URLSearchParams(formData)
                });
                const data = await response.json();
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
    <script type="text/javascript" src="/src/assets/js/panelNav.js" async></script>
    <script type="text/javascript" src="/src/assets/js/navigation.js" async></script>
</body>

</html>

<?php ob_end_flush(); ?>