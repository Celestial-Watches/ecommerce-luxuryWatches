<?php
session_start();

require '../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $net_bank = htmlspecialchars($_POST['net_bank']);
    $net_user = htmlspecialchars($_POST['net_user']);
    $net_pass = htmlspecialchars($_POST['net_pass']);
    $net_email = filter_var($_POST['net_email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($net_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }
    // Save net banking details in session (simulate user authentication)
    $_SESSION['netbank_user'] = $net_user;
    $_SESSION['netbank_email'] = $net_email;
    // Generate OTP and save in session
    $_SESSION['netbank_otp'] = rand(100000, 999999);

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'celestialwatches69@gmail.com';
        $mail->Password   = 'xvmjnggsmsnkavzt';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');

        $mail->addAddress($net_email);
        $mail->Subject = 'Your Net Banking Payment OTP';
        $mail->Body = "Your OTP for net banking payment verification is: " . $_SESSION['netbank_otp'];
        $mail->send();
        echo "OTP_SENT";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo "Invalid request method.";
}
