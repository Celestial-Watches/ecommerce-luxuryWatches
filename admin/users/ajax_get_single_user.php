<?php
session_start();
require '../../app/config/conn.php';

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$user_id = $_GET['id']; // Ensure this is properly sanitized

try {
    $stmt = $conn->prepare("SELECT id, username, email, phone, role, status FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query error']);
}
?>
