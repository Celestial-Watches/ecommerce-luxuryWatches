<?php
session_start();

require_once "conn.php";

$errors = [];


$login_page_url = "login.php";

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $login_page_url) === false) {
    header("Location: $login_page_url");
    exit(); 
}

// Redirect if password reset is complete
if (isset($_SESSION['password_reset_complete']) && $_SESSION['password_reset_complete']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["otp"])) {
    $entered_otp = trim($_POST["otp"]);

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
    <link rel="stylesheet" href="/css/deskView.css" />
    <script src="/js/navigation.js"></script>
</head>

<body>
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
                <div class="input-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit" class="login-button">Verify OTP</button>
            </form>
        </div>
    </div>
</body>

</html>