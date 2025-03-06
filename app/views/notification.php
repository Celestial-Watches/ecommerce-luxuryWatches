<?php
session_start();
require_once '../config/conn.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Current user
$user_id = $_SESSION['user_id'];

// 1. Mark all as read (if requested)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    header("Location: notification.php");
    exit();
}

// 2. Fetch notifications for this user
$stmt = $conn->prepare("
    SELECT id, title, message, is_read, created_at
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 3. Group notifications by date
function groupNotificationsByDate($notifications)
{
    $grouped = [];
    foreach ($notifications as $notif) {
        $dateKey = date('Y-m-d', strtotime($notif['created_at']));
        if (!isset($grouped[$dateKey])) {
            $grouped[$dateKey] = [];
        }
        $grouped[$dateKey][] = $notif;
    }
    return $grouped;
}
$groupedNotifications = groupNotificationsByDate($results);

// Helper for friendly date labels
function friendlyDate($dateStr)
{
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    if ($dateStr === $today) return 'Today';
    if ($dateStr === $yesterday) return 'Yesterday';
    return date('j M Y', strtotime($dateStr));
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Notifications</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            padding: 20px;
        }

        /* Layout Row */
        .top-row {
            margin-bottom: 20px;
        }

        /* Notifications Header */
        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            /* Some space before the list */
        }

        .notifications-header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .mark-all-btn {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
        }

        .mark-all-btn:hover {
            text-decoration: underline;
        }

        /* Notification Groups */
        .notification-group {
            margin-bottom: 20px;
        }

        .notification-date {
            font-weight: 600;
            color: #666;
            margin-bottom: 10px;
        }

        .notification-item {
            background: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease;
        }

        .notification-item:hover {
            background-color: #f3f3f3;
        }

        .notification-item.unread {
            border-left: 4px solid #007bff;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row top-row">
            <!-- Left Column: User Navbar -->
            <div class="col-3">
                <?php include '../../PHP/components/user-navbar.php'; ?>
            </div>

            <!-- Right Column: Notifications -->
            <div class="col-9">
                <!-- Header -->
                <div class="notifications-header">
                    <h1>Notifications</h1>
                    <form method="POST" style="margin:0; padding:0;">
                        <button type="submit" name="mark_all_read" class="mark-all-btn">
                            Mark all as read
                        </button>
                    </form>
                </div>

                <!-- Notifications List -->
                <?php if (!empty($groupedNotifications)): ?>
                    <?php foreach ($groupedNotifications as $dateKey => $notificationsForDay): ?>
                        <div class="notification-group">
                            <div class="notification-date">
                                <?= friendlyDate($dateKey) ?>
                            </div>
                            <?php foreach ($notificationsForDay as $notif): ?>
                                <?php
                                $isUnread = !$notif['is_read'] ? 'unread' : '';
                                $timeFormatted = date('g:i A', strtotime($notif['created_at']));
                                ?>
                                <div class="notification-item <?= $isUnread ?>">
                                    <div class="notification-title">
                                        <?= htmlspecialchars($notif['title']) ?>
                                    </div>
                                    <div class="notification-body">
                                        <?= htmlspecialchars($notif['message']) ?>
                                    </div>
                                    <div class="notification-time">
                                        <?= $timeFormatted ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">No notifications currently.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>