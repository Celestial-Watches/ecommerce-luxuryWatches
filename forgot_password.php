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
$reset_link = ""; // Initialize to empty

// Check if OTP has already been sent
$otp_sent = isset($_SESSION['otp_sent']) ? $_SESSION['otp_sent'] : false;

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
                        // Prepare to send email using PHPMailer
                        $mail = new PHPMailer(true); // Create a new PHPMailer instance

                        try {
                            // Server settings
                            $mail->isSMTP();                                    // Set mailer to use SMTP
                            $mail->Host       = 'smtp.gmail.com';               // Specify main and backup SMTP servers
                            $mail->SMTPAuth   = true;                           // Enable SMTP authentication
                            $mail->Username   = 'celestialwatches69@gmail.com'; // SMTP username
                            $mail->Password   = 'xvmjnggsmsnkavzt';             // Your Gmail password or App Password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // Enable TLS encryption
                            $mail->Port       = 465;                            // TCP port to connect to

                            // Recipients
                            $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches'); // Sender's email and name
                            $mail->addAddress($email); // Add a recipient

                            // Content
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = 'Password Reset Request';
                            $mail->Body    = "Click the following link to reset your password: <a href='http://localhost:3000/reset_password.php?token=" . urlencode($token) . "'>Reset Password</a>";
                            $mail->AltBody = "Click the following link to reset your password: http://localhost:3000/reset_password.php?token=" . urlencode($token);

                            // Send the email
                            if ($mail->send()) {
                                $success_message = "Password reset instructions have been sent to your email.";
                                // Generate the reset link only after successful email sending
                                $reset_link = "http://localhost:3000/reset_password.php?token=" . urlencode($token);
                                $_SESSION['reset_link'] = $reset_link; // Store the reset link in the session
                                $_SESSION['otp_sent'] = true; // Set session variable to indicate OTP has been sent
                            } else {
                                $errors[] = "Failed to send password reset email. Mailer Error: {$mail->ErrorInfo}";
                            }
                        } catch (Exception $e) {
                            $errors[] = "Failed to send password reset email. Mailer Error: {$mail->ErrorInfo}";
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
            if (!empty($success_message)) {
                echo "<div class='alert alert-success'>$success_message</div>";
            }
            ?>
            <form action="forgot_password.php" method="post">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <?php if (!empty($reset_link)): ?>
                <div class="reset-link">
                    <p>Your reset link is: <a href="<?php echo htmlspecialchars($reset_link); ?>">Click here to reset your password</a></p>
                </div>
                <?php endif; ?>
                <button type="submit" class="login-button" <?php echo $otp_sent ? 'disabled' : ''; ?>>Reset Password</button>
                <div class="login-footer">
                    <a href="login.php">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>