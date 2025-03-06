<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userOtp = trim($_POST['net_otp']);
    if (!isset($_SESSION['netbank_otp'], $_SESSION['netbank_user'])) {
        echo "Session expired. Please try again.";
        exit;
    }
    if ($userOtp == $_SESSION['netbank_otp']) {
        unset($_SESSION['netbank_otp']);
        $_SESSION['netbank_verified'] = true;
        echo "OTP_VERIFIED";
    } else {
        echo "Invalid OTP! Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>