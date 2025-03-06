<?php
session_start();
session_regenerate_id(true);
ob_start();

require '../../app/config/conn.php';

if (!isset($_SESSION['user'], $_SESSION['admin'], $_SESSION['authenticated']) ||
    $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['transaction_id']) || empty($_POST['transaction_id'])) {
        echo json_encode(['success' => false, 'message' => 'Transaction ID missing.']);
        exit();
    }
    $transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);
    $tracking_number = mysqli_real_escape_string($conn, $_POST['tracking_number']);

    // Update the tracking number and update order_status to 'shipped'
    $query = "UPDATE transactionss SET tracking_number = '$tracking_number', order_status = 'completed' WHERE transaction_id = '$transaction_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        exit();
    }
} else {
    header("Location: view_order.php");
    exit();
}
ob_end_flush();
?>
