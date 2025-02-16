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

session_start(); 

// Regenerate the session ID on every page refresh
session_regenerate_id(true);

// Redirect logged-in users to index.php
if (isset($_SESSION['user'])) {
    header("Location: ../../index.php");
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
    require '../../vendor/autoload.php'; // Ensure PHPMailer is included
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

        // Recipients
        $mail->setFrom('celestialwatches69@gmail.com', 'Celestial Watches');
        $mail->addAddress($_SESSION['email']); // Use the email stored in the session

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your New OTP Code - Celestial Watches';
        $mail->addEmbeddedImage(dirname(__FILE__) . '/../../src/assets/image/newsletter.jpg', 'newsletter_image');
        $mail->Body = '
<div style="font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; padding: 20px;">
    <div style="text-align: center;">
        <img src="cid:newsletter_image" alt="Celestial Watches" style="max-width: 100%; height: auto; margin-bottom: 20px;">
    </div>
    <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #000; text-align: center;">Resend OTP Code</h2>
        <p style="font-size: 16px; text-align: center;">
            Hello <strong>' . htmlspecialchars($usernamee, ENT_QUOTES, 'UTF-8') . '</strong>,
        </p>
        <p style="font-size: 16px; text-align: center;">
            It looks like your previous verification attempt was unsuccessful. To complete your email verification, please use the new OTP code below:
        </p>
        <p style="font-size: 24px; font-weight: bold; color: #000; text-align: center; margin: 20px 0;">
            Your new OTP Code: <strong>' . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . '</strong>
        </p>
        <p style="font-size: 16px; text-align: center;">
            This code is valid for a limited time. If you don&#x27;t complete the verification within the next 5 minutes, please try again.
        </p>
        <p style="font-size: 16px; text-align: center; color: #555;">
            For your security, please do not share this code with anyone. If you did not request this email, please contact our support team.
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
        $mail->AltBody = "Your new OTP code is: $otp";

        // Send the email
        $mail->send();


        $_SESSION['otp'] = $otp;
        $success_message = "A new OTP has been sent to your email.";
    } catch (Exception $e) {
        $errors[] = "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
    }
}
// OTP verification process
if (isset($_POST['verify'])) {
    $input_otp = '';
    for ($i = 1; $i <= 6; $i++) {
        if (isset($_POST['otp_digit' . $i])) {
            $input_otp .= $_POST['otp_digit' . $i];
        }
    }

    // Check if the OTP has expired
    if ($is_expired) {
        $errors[] = "OTP has expired. Please request a new one.";
    } else {
        // Check if the input OTP matches the stored OTP
        if ($input_otp == $_SESSION['otp']) {
            // OTP is correct, proceed to create the user
            require_once "../config/conn.php";
            $email = $_SESSION['email'];
            $usernamee = $_SESSION['username'];
            $passwordHash = $_SESSION['password'];
            $phone = $_SESSION['phone'];

            // Check if the user already exists
            $checkSql = "SELECT * FROM users WHERE email = ? OR username = ?";
            if ($checkStmt = mysqli_prepare($conn, $checkSql)) {
                mysqli_stmt_bind_param($checkStmt, "ss", $email, $usernamee);
                mysqli_stmt_execute($checkStmt);
                $result = mysqli_stmt_get_result($checkStmt);

                if (mysqli_num_rows($result) == 0) {
                    $sql = "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "ssss", $usernamee, $email, $phone, $passwordHash);
                        if (mysqli_stmt_execute($stmt)) {
                            $registration_successful = true;

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
                            unset($_SESSION['password']); 
                            unset($_SESSION['phone']); 
                            unset($_SESSION['otp_expiry']); 
                            setcookie('SSIDU', '', time() - 3600, '/', '', false, true);



                            // Set user session
                            $_SESSION['user'] = $usernamee; 
                            $_SESSION["user_id"] = $user['id'];
                            setcookie("loggedYes", "true", time() + 3600, "/", false, true);
                            $_SESSION['otp_verified'] = true;
                            header("Location: ../../index.php"); 
                            exit(); 
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
    <link rel="stylesheet" href="../../src/assets/css/deskView.css">
    <script src="../../src/assets/js/navigation.js" async></script>
    <script>
        let remainingTime = <?php echo $remaining_time; ?>; // Initial remaining time in seconds
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <script src="../../src/assets/js/otp-verify-function.js" async></script>
    <style>
        /* General Alert Styles */
        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            /* Change to your preferred font */
        }

        /* Success Alert Styles */
        .alert-success {
            background-color: #d4edda;
            /* Light green background */
            color: #155724;
            /* Dark green text */
            border: 1px solid #c3e6cb;
            /* Dark green border */
            position: relative;
            /* Required for positioning the close button */
        }

        /* Close Button Styles */
        .alert .close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #155724;
            font-weight: bold;
            cursor: pointer;
        }

        .alert .close:hover {
            color: #0c743a;
            /* Darker green on hover */
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .alert {
            animation: fadeIn 0.5s ease-in;
        }
    </style>
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
                echo "<div class='alert alert-success'>
                        $success_message
                        <span class='close' onclick='this.parentElement.style.display=\"none\";'>&times;</span>
                      </div>";
            }
            ?>
            <form action="verify_otp.php" method="post" id="otp-form">
                <div class="input-group">
                    <label for="otp">Enter OTP</label>
                    <div class="otp-inputs" style="    display: flex; gap: 10px; width: 100%;">
                        <input type="text" id="otp1" name="otp_digit1" maxlength="1" required class="otp-box" pattern="[0-9]*" inputmode="numeric" autofocus>
                        <input type="text" id="otp2" name="otp_digit2" maxlength="1" required class="otp-box" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp3" name="otp_digit3" maxlength="1" required class="otp-box" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp4" name="otp_digit4" maxlength="1" required class="otp-box" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp5" name="otp_digit5" maxlength="1" required class="otp-box" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp6" name="otp_digit6" maxlength="1" required class="otp-box" pattern="[0-9]*" inputmode="numeric">
                    </div>
                </div>
                <button type="submit" class="login-button" name="verify" id="verify-button">Verify OTP</button>
                <div class="login-footer">
                    <a href="javascript:void(0);" onclick="document.getElementById('resend-form').submit();">Resend OTP</a>
                </div>
            </form>
            <form id="resend-form" action="verify_otp.php" method="post" style="display: none;">
                <input type="hidden" name="resend" value="1">
            </form>
        </div>
    </div>

    <script>
        document.getElementById('otp1').addEventListener('paste', function(event) {
            // Get the pasted data
            let pasteData = (event.clipboardData || window.clipboardData).getData('text');

            // Check if the pasted data is 6 digits
            if (pasteData.length === 6 && /^[0-9]+$/.test(pasteData)) {
                // Split the digits and fill the inputs
                const inputs = document.querySelectorAll('.otp-box');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].value = pasteData[i];
                }

                // Automatically click the verify button if all fields are filled
                document.getElementById('verify-button').click();

                // Prevent the default paste behavior
                event.preventDefault();
            }
        });
        document.querySelectorAll('.otp-box').forEach((input, index) => {
            input.addEventListener('input', function() {
                // Move to next input if the current input is filled
                if (this.value.length === 1 && index < 5) {
                    document.querySelectorAll('.otp-box')[index + 1].focus();
                }
            });

            // Handle backspace functionality
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    // Focus the previous input when backspace is pressed on an empty input
                    document.querySelectorAll('.otp-box')[index - 1].focus();
                }
            });
        });
    </script>
    <script src="../../src/assets/js/cookie-monitor.js" async></script>
</body>

</html>