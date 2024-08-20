<?php
session_start();
$errors = [];
$success_message = "";
$registration_successful = false; // Initialize the variable

// Check if session is valid
if (!isset($_SESSION['user_agent']) || !isset($_SESSION['ip_address'])) {
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Validate user agent and IP address
if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] || $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
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

            // Check if the user already exists
            $checkSql = "SELECT * FROM users WHERE email = ? OR username = ?";
            if ($checkStmt = mysqli_prepare($conn, $checkSql)) {
                mysqli_stmt_bind_param($checkStmt, "ss", $email, $usernamee);
                mysqli_stmt_execute($checkStmt);
                $result = mysqli_stmt_get_result($checkStmt);
                
                if (mysqli_num_rows($result) == 0) { // Only insert if user does not exist
                    // Insert the new user into the database
                    $sql = "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "ssss", $usernamee, $email, $phone, $passwordHash);
                        if (mysqli_stmt_execute($stmt)) {
                            $registration_successful = true; // Set to true on successful registration

                            // Clear session data
                            unset($_SESSION['otp']);
                            unset($_SESSION['email']);
                            unset($_SESSION['username']);
                            unset($_SESSION['password']); // Clear the password from the session
                            unset($_SESSION['phone']); // Clear the phone number from the session
                            unset($_SESSION['otp_expiry']); // Clear the OTP expiry time from the session

                            // Set user session
                            $_SESSION['user'] = $usernamee; // Set user session
                            header("Location: index.php"); // Redirect to index.php
                            exit();
                        } else {
                            $errors[] = "Something went wrong. Please try again later.";
                        }
                    } else {
                        $errors[] = "Database preparation failed: " . mysqli_error($conn);
                    }
                } else {
                    $errors[] = "User already exists. Please log in.";
                }
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
        $mail->Username   = 'celestialwatches69@gmail.com'; // Your email
        $mail->Password   = 'xvmjnggsmsnkavzt'; // Your email password or App Password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
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