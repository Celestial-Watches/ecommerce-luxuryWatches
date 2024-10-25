<?php

// Function: Set the default timezone.
// Purpose: Ensure that date and time functions use the correct timezone for calculations (like OTP expiry).

date_default_timezone_set('Asia/Kolkata');

// Function: Start the session.
// Purpose: To maintain user-specific data across multiple requests. This allows you to store the OTP and its expiry time.

session_start(); // Ensure session is started

require_once "../config/conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

$errors = [];
$success_message = "";

// Function: Verify that the request method is POST and an email is provided.
// Purpose: To determine if the script should process the OTP generation and sending.

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = trim($_POST["email"]);

//     Function: Validate the provided email address.
// Purpose: Ensure the email is not empty and is in a valid format before proceeding with database checks.

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {

//         Function: Check if the email exists in the database.
// Purpose: Confirm that the email belongs to a registered user before generating an OTP.

        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                
//                 Function: Create a 6-digit OTP.
// Purpose: Generate a random OTP to be sent to the user's email for verification.

                $otp = rand(100000, 999999);

//                 Function: Store the generated OTP and its expiry time in the session.
// Purpose: Keep track of the OTP and when it should expire (10 minutes from now).
                $_SESSION['otp'] = $otp;
                $_SESSION['otp_expiry'] = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expires in 10 minutes
                $_SESSION['email'] = $email;

//                 Function: Configure PHPMailer for sending emails.
// Purpose: Prepare the mailer with SMTP settings and recipient details for sending the OTP email.

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
                    $mail->Subject = 'OTP Verification for Password Reset - Celestial Watches';
                    $mail->Body = '
<div style="font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; padding: 20px;">
    <div style="text-align: center;">
        <h2 style="color: #000; font-size: 24px;">Password Reset OTP Verification</h2>
    </div>
    <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <p style="font-size: 16px;">
            Hello,
        </p>
        <p style="font-size: 16px;">
            You requested to reset your password for your <strong>Celestial Watches</strong> account. To proceed, please use the One-Time Password (OTP) provided below:
        </p>
        <p style="font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0;">
            Your OTP: <strong>' . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . '</strong>
        </p>
        <p style="font-size: 16px;">
            This OTP is valid for <strong>10 minutes</strong>. If you did not request a password reset, please disregard this email or contact our support team immediately.
        </p>
        <p style="font-size: 16px; color: #555;">
            For your security, please do not share this OTP with anyone. It can only be used once to reset your password.
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
$mail->AltBody = "Hello,\n\nYou requested to reset your password for your Celestial Watches account. 
Your OTP is: $otp. It is valid for 10 minutes.\n\nIf you did not request a password reset, 
please disregard this email or contact our support team. 
For your security, do not share this OTP with anyone. 
Best regards, Celestial Watches Team.";

                    // Send the OTP email
                    if ($mail->send()) {
                        // Reset the session variable if the OTP is successfully requested
                        unset($_SESSION['otp_request_completed']); // Allow new OTP requests
                        
                        // Set a session variable to indicate the user is in the password reset flow
                        $_SESSION['password_reset_in_progress'] = true;
                        
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

// var_dump($_SESSION); FOR LOGG PURPOSE
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Celestial Watches</title>
    <link rel="stylesheet" href="../../src/assets/css/deskView.css" />
    <script src="../../src/assets/js/navigation.js"></script>
</head>

<body>

<?php include '../../PHP/components/loader.php';
    ?>
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