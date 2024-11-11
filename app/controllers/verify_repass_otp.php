<?php
session_start();

require_once "../config/conn.php";

$errors = [];

if (!isset($_SESSION['password_reset_in_progress']) || !$_SESSION['password_reset_in_progress']) {
    // If the session variable is not set, redirect to forgot password page
    header("Location: forgot_password.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve each digit of the OTP
    $otp_digits = [
        trim($_POST["otp_digit1"]),
        trim($_POST["otp_digit2"]),
        trim($_POST["otp_digit3"]),
        trim($_POST["otp_digit4"]),
        trim($_POST["otp_digit5"]),
        trim($_POST["otp_digit6"]),
    ];

    $entered_otp = implode('', $otp_digits); // Combine the digits into a single string

    if (empty($entered_otp)) {
        $errors[] = "OTP is required";
    } elseif (!isset($_SESSION['otp']) || $entered_otp != $_SESSION['otp']) {
        $errors[] = "Invalid OTP";
    } elseif (!isset($_SESSION['otp_expiry']) || strtotime($_SESSION['otp_expiry']) < time()) {
        $errors[] = "OTP has expired";
    } else {
        // OTP is valid
        $token = bin2hex(random_bytes(32));
        // Invalidate the OTP
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        // Generate reset token and expiry
        $email = $_SESSION['email'];
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store the reset token in the database
        $update_sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
            mysqli_stmt_bind_param($update_stmt, "sss", $token, $expiry, $email);

            if (mysqli_stmt_execute($update_stmt)) {
                // Redirect to a form that submits the token via POST
                echo "<form id='redirectForm' action='reset_password.php' method='post'>
                <input type='hidden' name='token' value='" . htmlspecialchars($token) . "'>
              </form>
              <script>
                document.getElementById('redirectForm').submit();
              </script>";
                exit();
            } else {
                $errors[] = "Error updating token and expiry: " . mysqli_error($conn);
            }
            mysqli_stmt_close($update_stmt);
        } else {
            $errors[] = "Error preparing statement: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="../../src/assets/css/deskView.css" />
    <script src="../../src/assets/js/navigation.js"></script>
</head>

<body>

    ?>
    <div class="login-container">
        <div class="login-box">
            <h1>Verify OTP</h1>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            ?>
            <form action="verify_repass_otp.php" method="post">
                <div class="input-group"> <!-- New class for OTP input group -->
                    <label for="otp">Enter OTP</label>
                    <div class="otp-inputs" style="    display: flex; gap: 2px; width: 100%;">
                        <input type="text" id="otp1" name="otp_digit1" maxlength="1" required class="otp-box otp-input" pattern="[0-9]*" inputmode="numeric" autofocus>
                        <input type="text" id="otp2" name="otp_digit2" maxlength="1" required class="otp-box otp-input" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp3" name="otp_digit3" maxlength="1" required class="otp-box otp-input" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp4" name="otp_digit4" maxlength="1" required class="otp-box otp-input" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp5" name="otp_digit5" maxlength="1" required class="otp-box otp-input" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" id="otp6" name="otp_digit6" maxlength="1" required class="otp-box otp-input" pattern="[0-9]*" inputmode="numeric">
                    </div>
                </div>
                <button type="submit" class="login-button">Verify OTP</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to handle focus shifting and backspace
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
</body>

</html>