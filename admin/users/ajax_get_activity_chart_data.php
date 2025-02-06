<?php
require '../../app/config/conn.php';

header('Content-Type: application/json');

$query = "SELECT DATE(timestamp) AS date, COUNT(*) AS activity_count
          FROM user_activity_logs
          GROUP BY DATE(timestamp)
          ORDER BY date DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $labels = [];
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['date'];
        $data[] = $row['activity_count'];
    }
    echo json_encode(['labels' => $labels, 'data' => $data]);
} else {
    echo json_encode(['labels' => [], 'data' => []]);
}

$conn->close();
?>
