<?php

// // Set the session cookie with secure attributes
session_set_cookie_params([
    'lifetime' => 86400,              // Session expires when the browser is closed
    'path' => '/',                // Available throughout the site
    'domain' => '',               // Leave empty for current domain
    'secure' => false,             // Only send over HTTPS
    'httponly' => true,           // Prevent JavaScript access
    'samesite' => 'Strict'        // Protect against CSRF
]);

session_start(); // Ensure session is started at the very beginning

// Regenerate the session ID on every page refresh
session_regenerate_id(true);

// Redirect logged-in users to index.php
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}


$errors = [];
$success_message = "";
$registration_successful = false; // Initialize the variable


// Check if session is valid
if (!isset($_SESSION['user_agent']) || !isset($_SESSION['ip_address'])) {
    setcookie('SSIDU', '', time() - 3600, '/', '', true, true); // Clear cookie on session invalidation
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Validate user agent and IP address
if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] || $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    setcookie('SSIDU', '', time() - 3600, '/', '', true, true); // Clear cookie on session invalidation
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Check if OTP has expired
$otp_expiry_time = $_SESSION['otp_expiry'] ?? 0;
$is_expired = time() > $otp_expiry_time;
$usernamee = $_SESSION['username'];

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
        $mail->Subject = 'Your New OTP Code';
        $mail->addEmbeddedImage(dirname(__FILE__) . '/image/newsletter.jpg', 'newsletter_image');
        $mail->Body    = '<img src="cid:newsletter_image">'
            . '<br>'
            . '<h2>Email Verification Code</h2>'
            . '<p>Hello ' . htmlspecialchars($usernamee, ENT_QUOTES, 'UTF-8') . ', Enter this code on the identity verification screen:</p>'
            . '<br>'
            . "Your new OTP code is: <b>$otp</b>"
            . '<br>'
            . '<p>This code will expire shortly. If you can&#8217;t find the verification code, try signing up again.</p>';
        $mail->AltBody = "Your new OTP code is: $otp";
       

        // Send the email
        $mail->send();

        $_SESSION['otp_verified'] = true;
        // Store the new OTP in the session
        $_SESSION['otp'] = $otp;
        $success_message = "A new OTP has been sent to your email.";
    } catch (Exception $e) {
        $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
    }
}
// OTP verification process
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
            $email = $_SESSION['email']; // Get email from the session
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

                            // Update status to 'YES'
                            $updateStatusSql = "UPDATE users SET status = 'YES' WHERE username = ?";
                            if ($updateStatusStmt = mysqli_prepare($conn, $updateStatusSql)) {
                                mysqli_stmt_bind_param($updateStatusStmt, "s", $usernamee);
                                mysqli_stmt_execute($updateStatusStmt);
                                mysqli_stmt_close($updateStatusStmt);
                            } else {
                                $errors[] = "Failed to update status: " . mysqli_error($conn);
                            }

                            // Clear session data
                            unset($_SESSION['otp']);
                            unset($_SESSION['username']);
                            unset($_SESSION['password']); // Clear the password from the session
                            unset($_SESSION['phone']); // Clear the phone number from the session
                            unset($_SESSION['otp_expiry']); // Clear the OTP expiry time from the session
                            setcookie('SSIDU', '', time() - 3600, '/', '', false, true);



                            // Set user session
                            $_SESSION['user'] = $usernamee; // Set user session
                            setcookie("loggedYes", "true", time() + 3600, "/", false, true);
                            $_SESSION['otp_verified'] = true;
                            header("Location: index.php"); // Redirect to index.php
                            exit(); // Ensure no further code is executed
                        } else {
                            $errors[] = "Something went wrong. Please try again later.";
                        }
                    } else {
                        $errors[] = "Database preparation failed: " . mysqli_error($conn);
                    }
                } else {
                    $errors[] = "User already exists. Please log in.";
                    header("Location: login.php");
                }
            }
        } else {
            $errors[] = "Invalid OTP. Please try again.";
        }
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
    <script src="/js/navigation.js"></script>


    <script>
        let remainingTime = <?php echo $remaining_time; ?>; // Initial remaining time in seconds

        // Function to update the timer display
        function updateTimer() {
            if (remainingTime <= 0) {
                document.getElementById('timer').innerHTML = "OTP has expired.";
                document.getElementById('verify-button').disabled = true; // Disable verify button
                return;
            }

            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            document.getElementById('timer').innerHTML = `Expires in: ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            remainingTime--; // Decrement remaining time on the client-side

            // Continue updating the timer every second
            setTimeout(updateTimer, 1000);
        }

        // Function to fetch the remaining time from the server using AJAX
        function fetchRemainingTime() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_remaining_time.php', true); // Your PHP file that returns remaining time
            xhr.onload = function() {
                if (this.status === 200) {
                    remainingTime = parseInt(this.responseText); // Update remainingTime with the server response
                }
            };
            xhr.send();
        }

        // Fetch the remaining time every 30 seconds (adjust as needed)
        setInterval(fetchRemainingTime, 30000); // Fetch updated time from the server

        // Start the timer when the page loads
        window.onload = updateTimer;
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
                <button type="submit" class="login-button" name="verify" id="verify-button">Verify OTP</button>
                <div class="login-footer">
                    <a href="javascript:void(0);" onclick="document.getElementById('resend-form').submit();">Resend OTP</a>
                    <span id="timer" style="float: right;">Expires in: <?php echo str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" . str_pad($seconds, 2, '0', STR_PAD_LEFT); ?></span>
                </div>
            </form>
            <form id="resend-form" action="verify_otp.php" method="post" style="display: none;">
                <input type="hidden" name="resend" value="1">
            </form>
        </div>
    </div>
</body>

</html>