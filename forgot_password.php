<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once "conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$errors = [];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                // Generate a unique token
                $token = bin2hex(random_bytes(32));
                
                // Set expiry time to 1 hour from now
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Store the token in the database
                $update_sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "sss", $token, $expiry, $email);
                    
                    if (mysqli_stmt_execute($update_stmt)) {
                        // Update the reset link to use the correct port
                        $reset_link = "http://localhost:3000/reset_password.php?token=" . urlencode($token);
                        
                        // Create a new PHPMailer instance
                        $mail = new PHPMailer(true);

                        try {
                            // Server settings
                            $mail->isSMTP();
                            $mail->Host       = 'smtp.gmail.com';
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'celestialwatches69@gmail.com'; // Your Gmail address
                            $mail->Password   = 'zhfrjslmmeghabiw'; // Use an App Password, not your regular password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 587;

                            // Recipients
                            $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
                            $mail->addAddress($email);

                            // Content
                            $mail->isHTML(true);
                            $mail->Subject = 'Password Reset Request';
                            $mail->Body    = "Click the following link to reset your password: <a href='$reset_link'>Reset Password</a>";

                            $mail->send();
                            $success_message = "Password reset instructions have been sent to your email.";
                        } catch (Exception $e) {
                            $errors[] = "Failed to send password reset email. Error: {$mail->ErrorInfo}";
                            error_log("PHPMailer Error: " . $mail->ErrorInfo); // Log the error for debugging
                        }
                    } else {
                        $errors[] = "Error updating token and expiry: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($update_stmt);
                } else {
                    $errors[] = "Something went wrong. Please try again later.";
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
            if (!empty($success_message)) {
                echo "<div class='alert alert-success'>$success_message</div>";
            }
            ?>
            <form action="forgot_password.php" method="post">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="login-button">Reset Password</button>
                <div class="login-footer">
                    <a href="login.php">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>