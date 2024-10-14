<?php
date_default_timezone_set('Asia/Kolkata');
session_start(); // Ensure session is started

require_once "conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$errors = [];
$success_message = "";


$login_page_url = "login.php";

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $login_page_url) === false) {
    header("Location: $login_page_url");
    exit(); 
}

// If password reset is complete, unset the session variable
if (isset($_SESSION['password_reset_complete'])) {
    unset($_SESSION['password_reset_complete']); // Clear the session variable

    // Redirect to the login page to prevent further actions
    header('Location: login.php');
    exit();
}

// Check if OTP needs to be generated and sent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                // Generate a 6-digit OTP
                $otp = rand(100000, 999999);
                
                // Store the OTP and its expiry in the session
                $_SESSION['otp'] = $otp;
                $_SESSION['otp_expiry'] = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expires in 10 minutes
                $_SESSION['email'] = $email;

                // Send the OTP via email (using PHPMailer)
                $mail = new PHPMailer(true);
                try {
                    // SMTP settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'celestialwatches69@gmail.com';
                    $mail->Password   = 'xvmjnggsmsnkavzt';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    // Recipients
                    $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'OTP Verification for Password Reset';
                    $mail->Body    = "Your OTP for password reset is: <strong>$otp</strong>. It is valid for 10 minutes.";
                    $mail->AltBody = "Your OTP for password reset is: $otp. It is valid for 10 minutes.";

                    // Send the OTP email
                    if ($mail->send()) {
                        // Redirect to OTP verification page before any output
                        header("Location: verify_repass_otp.php");
                        exit();
                    } else {
                        $errors[] = "Failed to send OTP email.";
                    }
                } catch (Exception $e) {
                    $errors[] = "Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                $errors[] = "No account found with that email address.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Something went wrong. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Celestial Watches</title>
    <link rel="stylesheet" href="/css/deskView.css" />
    <script src="/js/navigation.js"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Forgot Password</h1>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            ?>
            <form action="forgot_password.php" method="post">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="login-button" name="forgot-password">Reset Password</button>
                <div class="login-footer">
                    <a href="login.php">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>