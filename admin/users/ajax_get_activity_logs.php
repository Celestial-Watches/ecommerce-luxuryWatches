<?php
require '../../app/config/conn.php';

header('Content-Type: application/json');

$query = "SELECT u.username, a.activity_type, a.description, a.ip_address, a.user_agent, a.timestamp
          FROM user_activity_logs a
          JOIN users u ON a.user_id = u.id
          ORDER BY a.timestamp DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $activityLogs = [];
    while ($row = $result->fetch_assoc()) {
        $activityLogs[] = $row;
    }
    echo json_encode(['data' => $activityLogs]);
} else {
    echo json_encode(['data' => []]);
}

$conn->close();
?>
