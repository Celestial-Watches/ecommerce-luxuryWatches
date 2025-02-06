<?php
require '../../app/config/conn.php';

header('Content-Type: application/json');


$conn->set_charset("utf8mb4");
$query = "
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count 
    FROM users 
    GROUP BY month 
    ORDER BY month ASC
";
$result = $conn->query($query);
if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit;
}
$labels = [];
$data = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $data[] = $row['count'];
}
echo json_encode(['labels' => $labels, 'data' => $data]);
$result->free();
$conn->close();
