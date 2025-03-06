<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['status'=>'error', 'message'=>'Unauthorized']);
    exit();
}
require_once '../../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['feedback'])) {
    $user_id = $_SESSION['user_id'];
    $request_id = (int)$_POST['request_id'];
    $feedback = trim($_POST['feedback']);

    // Insert feedback into a feedback table
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, request_id, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $user_id, $request_id, $feedback);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success', 'message'=>'Feedback submitted']);
    } else {
        echo json_encode(['status'=>'error', 'message'=>'Database error']);
    }
    exit();
}
?>
