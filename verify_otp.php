<?php
session_start();
$errors = [];
$success_message = "";

// Check if session is valid
if (!isset($_SESSION['user_agent']) || !isset($_SESSION['ip_address'])) {
    // Session data is missing, destroy session
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Validate user agent and IP address
if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] || $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    // User agent or IP address has changed, destroy session
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Check if OTP has expired
$otp_expiry_time = $_SESSION['otp_expiry'] ?? 0;
$is_expired = time() > $otp_expiry_time;

if (isset($_POST['verify'])) {
    $input_otp = $_POST['otp'];

    // Check if the OTP has expired
    if ($is_expired) {
        $errors[] = "OTP has expired. Please request a new one.";
    } else {
        // Check if the input OTP matches the stored OTP
        if ($input_otp == $_SESSION['otp']) {
            // OTP is correct, proceed to create the user
            require_once "conn.php";
            $email = $_SESSION['email'];
            $usernamee = $_SESSION['username']; // Get username from the session
            $passwordHash = $_SESSION['password']; // Get hashed password from the session
            $phone = $_SESSION['phone']; // Get phone number from the session

            // Insert the new user into the database
            $sql = "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $usernamee, $email, $phone, $passwordHash);
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Account created successfully. You will be redirected to the login page shortly.";
                    // Clear session data
                    unset($_SESSION['otp']);
                    unset($_SESSION['email']);
                    unset($_SESSION['username']);
                    unset($_SESSION['password']); // Clear the password from the session
                    unset($_SESSION['phone']); // Clear the phone number from the session
                    unset($_SESSION['otp_expiry']); // Clear the OTP expiry time from the session
                } else {
                    $errors[] = "Something went wrong. Please try again later.";
                }
            } else {
                $errors[] = "Database preparation failed: " . mysqli_error($conn);
            }
        } else {
            $errors[] = "Invalid OTP. Please try again.";
        }
    }
}

// Handle Resend OTP
if (isset($_POST['resend'])) {
    // Generate a new OTP
    $otp = rand(100000, 999999);
    
    // Set OTP expiry time (5 minutes from now)
    $_SESSION['otp_expiry'] = time() + 300; // 300 seconds = 5 minutes

    // Send OTP via email
    require 'vendor/autoload.php'; // Ensure PHPMailer is included
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@gmail.com'; // Your email
        $mail->Password   = 'your_password'; // Your email password or App Password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('your_email@gmail.com', 'Celestial Watches');
        $mail->addAddress($_SESSION['email']); // Use the email stored in the session

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your new OTP code is: <b>$otp</b>";
        $mail->AltBody = "Your new OTP code is: $otp";

        // Send the email
        $mail->send();

        // Store the new OTP in the session
        $_SESSION['otp'] = $otp;
        $success_message = "A new OTP has been sent to your email.";
    } catch (Exception $e) {
        $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Calculate remaining time
$remaining_time = max(0, $otp_expiry_time - time());
$minutes = floor($remaining_time / 60);
$seconds = $remaining_time % 60;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="/css/deskView.css" />
    <script>
        // Dynamic countdown timer
        let remainingTime = <?php echo $remaining_time; ?>; // Remaining time in seconds

        function updateTimer() {
            if (remainingTime <= 0) {
                document.getElementById('timer').innerHTML = "OTP has expired.";
                document.getElementById('verify-button').disabled = true; // Disable verify button
                return;
            }

            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            document.getElementById('timer').innerHTML = `Expires in: ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            remainingTime--;

            setTimeout(updateTimer, 1000); // Update every second
        }

        window.onload = updateTimer; // Start the timer when the page loads
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Verify OTP</h1>
            <?php
            // Display errors
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            if (!empty($success_message)) {
                echo "<div class='alert alert-success'>$success_message</div>";
            }
            ?>
            <form action="verify_otp.php" method="post">
                <div class="input-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit" class="login-button" name="verify">Verify OTP</button>
                <div class="login-footer">
                    <a href="javascript:void(0);" onclick="document.getElementById('resend-form').submit();">Resend OTP</a>
                    <span id="timer" style="float: right;">Expires in: <?php echo sprintf("%02d:%02d", $minutes, $seconds); ?></span>
                </div>
            </form>
            <form id="resend-form" action="verify_otp.php" method="post" style="display: none;">
                <input type="hidden" name="resend" value="1">
            </form>
        </div>
    </div>
</body>
</html>