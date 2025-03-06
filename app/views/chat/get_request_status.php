<?php
session_start();
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    exit();
}
require_once '../../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $user_id = $_SESSION['user_id'];
    $request_id = (int)$_POST['request_id'];
    
    $stmt = $conn->prepare("SELECT status FROM requestss WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $request_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'request_status' => $row['status']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Request not found']);
    }
    exit();
}
?>
