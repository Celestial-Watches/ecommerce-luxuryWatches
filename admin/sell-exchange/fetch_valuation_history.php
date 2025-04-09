<?php
require '../../app/config/conn.php';

header('Content-Type: application/json');

$result = $conn->query("SELECT * FROM valuations ORDER BY created_at DESC");
$valuationHistory = $result->fetch_all(MYSQLI_ASSOC);

$chartLabels = array_map(function($row) {
    return date('Y-m-d H:i', strtotime($row['created_at']));
}, $valuationHistory);
$chartData = array_map(function($row) {
    return $row['adjusted_value'];
}, $valuationHistory);

echo json_encode([
    'status' => 'success',
    'data' => $valuationHistory,
    'chartLabels' => $chartLabels,
    'chartData' => $chartData
]);
?>