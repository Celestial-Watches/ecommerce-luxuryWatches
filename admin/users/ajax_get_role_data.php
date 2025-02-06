<?php
session_start();
require '../../app/config/conn.php';

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

try {
    $stmt = $conn->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $stmt->execute();
    $roles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $labels = [];
    $data = [];
    foreach ($roles as $role) {
        $labels[] = ucfirst($role['role']);
        $data[] = $role['count'];
    }

    echo json_encode(['labels' => $labels, 'data' => $data]);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query error']);
}
?>
