<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

require '../../app/config/conn.php';

if (isset($_GET['id'])) {
    $offerId = intval($_GET['id']);
    if ($offerId > 0) {
        $stmt = $conn->prepare("DELETE FROM discount_name WHERE id = ?");
        $stmt->bind_param("i", $offerId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Invalid offer ID."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Offer ID parameter missing."]);
}
?>
