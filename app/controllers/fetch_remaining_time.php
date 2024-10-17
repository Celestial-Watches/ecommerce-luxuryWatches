<?php
session_start();

// Calculate remaining time
$otp_expiry_time = $_SESSION['otp_expiry'] ?? 0;
$remaining_time = max(0, $otp_expiry_time - time());

// Return the remaining time as plain text
echo $remaining_time;
?>