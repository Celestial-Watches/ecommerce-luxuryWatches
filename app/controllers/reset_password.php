<?php
session_start();

require_once "../config/conn.php";
require '../../vendor/autoload.php'; // Ensure PHPMailer is autoloaded

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$success_message = "";

if (!isset($_SESSION['password_reset_in_progress'])) {
    // If the user did not come from the OTP verification process, redirect
    header("Location: forgot_password.php");
    exit();
}

// Check if token is provided and valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'])) {
    $token = $_POST['token'];
    $sql = "SELECT email FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $email);
            mysqli_stmt_fetch($stmt);
            $_SESSION['email'] = $email; // Store email in session for password update
        } else {
            $errors[] = "Invalid or expired token.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $errors[] = "Database error: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors) && isset($_POST['new_password'])) {
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($new_password)) {
        $errors[] = "New password is required";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $errors[] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $email = $_SESSION['email']; // Use the email stored in session

        $update_sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE email = ?";
        if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_password, $email);
            if (mysqli_stmt_execute($update_stmt)) {
                // Send confirmation email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'celestialwatches69@gmail.com';
                    $mail->Password = 'xvmjnggsmsnkavzt'; // Use env variable in future for security
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    // Recipients
                    $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Password Has Been Successfully Reset - Celestial Watches';
                    $mail->Body = '
<div style="font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; padding: 20px;">
    <div style="text-align: center;">
        <h2 style="color: #000; font-size: 24px;">Password Reset Confirmation</h2>
    </div>
    <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <p style="font-size: 16px;">
            Hello,
        </p>
        <p style="font-size: 16px;">
            We wanted to let you know that your password has been successfully updated for your <strong>Celestial Watches</strong> account. You can now log in using your new password.
        </p>
        <p style="font-size: 16px; color: #555;">
            If you did not make this change, or if you believe someone has accessed your account, please contact our support team immediately to secure your account.
        </p>
        <p style="font-size: 16px; color: #555;">
            For security reasons, we recommend that you regularly update your passwords and avoid sharing your account details with anyone.
        </p>
    </div>
    <div style="margin-top: 30px; text-align: center;">
        <p style="font-size: 14px; color: #888;">Best regards,</p>
        <p style="font-size: 14px; color: #888;"><strong>Celestial Watches Team</strong></p>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <a href="https://www.celestialwatches.com" style="font-size: 14px; color: white; background-color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Visit Celestial Watches
        </a>
    </div>
</div>';

                    $mail->AltBody = "Hello,\n\nWe wanted to let you know that your password has been successfully updated for your Celestial Watches account. 
If you did not make this change or believe someone has accessed your account, please contact our support team immediately.\n\nBest regards, Celestial Watches Team.";


                    $mail->send();
                    $success_message = "Your password has been successfully reset. A confirmation email has been sent.";

                    // unset($_SESSION['email']);

                    // Set session flag to indicate completion
                    $_SESSION['password_reset_complete'] = true; // Indicate the reset is complete
                    unset($_SESSION['password_reset_in_progress']);

                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 2000);
                          </script>";
                } catch (Exception $e) {
                    $errors[] = "Failed to send confirmation email. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $errors[] = "Something went wrong while updating the password.";
            }
            mysqli_stmt_close($update_stmt);
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
    <title>Reset Password - Celestial Watches</title>
    <link rel="stylesheet" href="../../src/assets/css/deskView.css" />
    <script src="../../src/assets/js/navigation.js"></script>
</head>

<body>

    
    <div class="login-container">
        <div class="login-box">
            <h1>Reset Password</h1>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            if (!empty($success_message)) {
                echo "<div class='alert alert-success'>$success_message</div>";
            } else {
            ?>
                <form action="reset_password.php" method="post">
                    <div class="input-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="login-button">Reset Password</button>
                </form>
            <?php } ?>
        </div>
    </div>
</body>

</html>