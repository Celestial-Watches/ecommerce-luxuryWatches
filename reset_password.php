<?php
session_start();

require_once "conn.php";
require 'vendor/autoload.php'; // Ensure PHPMailer is autoloaded

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$success_message = "";

$login_page_url = "login.php";

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $login_page_url) === false) {
    header("Location: $login_page_url");
    exit(); 
}

// Check if the password reset process is already completed
if (isset($_SESSION['password_reset_complete']) && $_SESSION['password_reset_complete']) {
    header('Location: login.php');
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
                    $mail->Password = 'xvmjnggsmsnkavzt'; // Use env variable for security
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    // Recipients
                    $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Confirmation';
                    $mail->Body = 'Your password has been successfully reset. You can now log in with your new password.';

                    $mail->send();
                    $success_message = "Your password has been successfully reset. A confirmation email has been sent.";
                    
                    // Set session flag to indicate completion
                    $_SESSION['password_reset_complete'] = true;

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
    <link rel="stylesheet" href="/css/deskView.css" />
    <script src="/js/navigation.js"></script>
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