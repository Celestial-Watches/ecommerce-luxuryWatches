<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Kolkata');
session_start();
require_once "conn.php";

$errors = [];
$success_message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token is valid and not expired
    $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = trim($_POST["new_password"]);
                $confirm_password = trim($_POST["confirm_password"]);

                if (empty($new_password)) {
                    $errors[] = "New password is required";
                } elseif (strlen($new_password) < 8) {
                    $errors[] = "Password must be at least 8 characters long";
                }

                if ($new_password !== $confirm_password) {
                    $errors[] = "Passwords do not match";
                }

                if (empty($errors)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?";
                    if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                        mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $user['id']);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);
                        $success_message = "Your password has been successfully reset. You can now <a href='login.php'>login</a> with your new password.";
                    } else {
                        $errors[] = "Something went wrong. Please try again later.";
                    }
                }
            }
        } else {
            $errors[] = "Invalid or expired token. Please request a new password reset.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $errors[] = "Something went wrong. Please try again later.";
    }
} else {
    $errors[] = "Invalid request. Please use the reset link sent to your email.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Celestial Watches</title>
    <link rel="stylesheet" href="/css/deskView.css" />
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
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
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