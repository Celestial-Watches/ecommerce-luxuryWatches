<?php
require '../../app/config/conn.php';

header('Content-Type: application/json');


$conn->set_charset("utf8mb4");
$query = "SELECT id, username, email, phone, created_at, status, role FROM users";
$result = $conn->query($query);
if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit;
}
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode(['data' => $users]);
$result->free();
$conn->close();