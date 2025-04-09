<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

require '../../app/config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $discountCode           = trim($_POST['discount_code']);
    $discountName           = trim($_POST['discount_name']);
    $discountPercentage     = trim($_POST['discount_percentage']);
    $activeStatus           = isset($_POST['active_status']) ? intval($_POST['active_status']) : 0;
    $applicablePaymentMethods = trim($_POST['applicable_payment_methods']);
    $description            = trim($_POST['description']);
    $startDate              = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $endDate                = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    // Minimal required field check
    if (empty($discountCode) || empty($discountName) || empty($discountPercentage)) {
        header("Location: admin_offers.php?error=Missing+required+fields");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO discount_name 
        (discount_code, discount_name, discount_percentage, active_status, applicable_payment_methods, description, start_date, end_date, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    // Bind parameters (types: s = string, d = double, i = integer)
    // discount_code (s), discount_name (s), discount_percentage (d), active_status (i),
    // applicable_payment_methods (s), description (s), start_date (s), end_date (s)
    $stmt->bind_param("ssdissss", $discountCode, $discountName, $discountPercentage, $activeStatus, $applicablePaymentMethods, $description, $startDate, $endDate);
    
    if ($stmt->execute()) {
        header("Location: admin_offers.php?success=Offer+saved");
        exit();
    } else {
        header("Location: admin_offers.php?error=Offer+could+not+be+saved");
        exit();
    }
    $stmt->close();
} else {
    header("Location: admin_offers.php");
    exit();
}
?>
