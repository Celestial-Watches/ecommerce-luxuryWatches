<?php
session_start();
require '../../vendor/autoload.php';
include '../config/conn.php';

// Check if the user is logged in
$isUserLoggedIn = isset($_SESSION["user"]) && isset($_SESSION["user_id"]);
$userData = [];

if ($isUserLoggedIn) {
    $userId = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
    $query = "SELECT username, email, password FROM users WHERE id = '$userId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isUserLoggedIn) {
    // CSRF Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    // If user clicked the "Send OTP" button for password change
    if (isset($_POST['send_otp'])) {
        $currentPassword = trim($_POST['current_password']);
        if (empty($currentPassword)) {
            $error = "Please enter your current password to send OTP";
        } elseif (password_verify($currentPassword, $userData['password'])) {
            // Generate OTP and set expiry (10 minutes)
            $otp = rand(100000, 999999);
            $_SESSION['password_update_otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 600;
            $_SESSION['password_update_otp_sent'] = true;
            
            // Send OTP using PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'celestialwatches69@gmail.com';
                $mail->Password   = 'xvmjnggsmsnkavzt';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('celestialwatches69.com', 'Celestial Watches');
                $mail->addAddress($userData['email']);
                $mail->Subject = 'Your OTP for Password Change';
                $mail->Body = "Your OTP is: $otp (Valid for 10 minutes)";
                
                $mail->send();
                $messageInfo = "OTP sent to your email";
            } catch (Exception $e) {
                $error = "Failed to send OTP: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Incorrect current password";
        }
    }
    // If user clicked the "Save Changes" or "Update Password & Save Changes" button
    elseif (isset($_POST['save_changes'])) {
        // If an OTP has been sent, then user intends to update their password
        if (isset($_SESSION['password_update_otp_sent'])) {
            $enteredOtp = $_POST['otp'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            if (empty($enteredOtp) || empty($newPassword)) {
                $error = "Please enter OTP and new password to update password";
            } elseif (time() > $_SESSION['otp_expiry']) {
                $error = "OTP has expired. Please request a new one";
                unset($_SESSION['password_update_otp'], $_SESSION['password_update_otp_sent'], $_SESSION['otp_expiry']);
            } elseif ($enteredOtp != $_SESSION['password_update_otp']) {
                $error = "Invalid OTP";
            } else {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET username = '$name', email = '$email', password = '$newPasswordHash' WHERE id = '$userId'";
                if (mysqli_query($conn, $updateQuery)) {
                    $messageInfo = "Profile and password updated successfully";
                    unset($_SESSION['password_update_otp'], $_SESSION['password_update_otp_sent'], $_SESSION['otp_expiry']);
                } else {
                    $error = "Update failed: " . mysqli_error($conn);
                }
            }
        } else {
            // No password change desired; update only name and email.
            $updateQuery = "UPDATE users SET username = '$name', email = '$email' WHERE id = '$userId'";
            if (mysqli_query($conn, $updateQuery)) {
                $messageInfo = "Profile updated successfully";
            } else {
                $error = "Update failed: " . mysqli_error($conn);
            }
        }
    }
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6f8;
            margin: 0;
        }
        .container {
            margin: auto;
        }
        .settings {
            display: flex;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            padding: 20px;
            background: #f9fafb;
            border-right: 1px solid #e0e4e8;
            transition: transform 0.3s ease;
        }
        .sidebar .aligned {
            display: flex;
            align-items: center;
        }
        .sidebar h2 {
            font-size: 18px;
            margin-left: 10px;
            color: #333;
        }
        .sidebar h3 {
            font-size: 12px;
            color: #8a8f93;
            margin: 30px 0 12px 0;
            text-transform: uppercase;
        }
        .menu {
            list-style: none;
            padding: 0;
        }
        .menu li {
            margin-bottom: 12px;
        }
        .menu li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-size: 15px;
        }
        .menu li a:hover,
        .menu li a.active {
            background-color: #e5e9f0;
        }
        /* Content Styles */
        .content {
            padding: 40px;
        }
        .content h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            color: #666;
            margin-bottom: 20px;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        form label {
            display: block;
            font-weight: bold;
            margin-top: 15px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .luxury-button {
            padding: 12px 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
            background-color: #000;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 20px;
        }
        .luxury-button:hover {
            background-color: #333;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .settings {
                flex-direction: column;
            }
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 280px;
                transform: translateX(-100%);
                z-index: 1000;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .content {
                padding: 20px;
            }
            /* Toggle button visible only on mobile */
            .toggle-sidebar {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 10px 15px;
                border-radius: 4px;
                z-index: 1100;
            }
        }
        @media (min-width: 769px) {
            .toggle-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="toggle-sidebar" onclick="toggleSidebar()">Menu</button>
    
    <div class="container">
        <div class="settings">
            <div class="sidebar" id="sidebar">
                <div class="aligned">
                    <a href="../../index.php" style="text-decoration: none; color:rgb(165, 178, 181);">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <h2>Settings</h2>
                </div>
                <ul class="menu">
                    <li><a href="#" class="active">Account</a></li>
                    <li><a href="#">NFT Customisation</a></li>
                    <li><a href="selling.php">Sell and Exchange</a></li>
                    <li><a href="#">Auction Participation</a></li>
                    <li><a href="transaction.php">Transaction History</a></li>
                    <li><a href="#">Manage Address</a></li>
                    <li><a href="#">Payment Methods</a></li>
                </ul>
                <h3>System</h3>
                <ul class="menu">
                    <li><a href="#">Notifications</a></li>
                    <li><a href="#">Preferences</a></li>
                </ul>
            </div>
            <div class="content">
                <h3>Account Settings</h3>
                <?php if ($isUserLoggedIn): ?>
                    <?php if (isset($messageInfo)): ?>
                        <div class="alert alert-success"><?= $messageInfo ?></div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <label>Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($userData['username']) ?>" required>
                        
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
                        
                        <label>Current Password (for password change)</label>
                        <input type="password" name="current_password" placeholder="Enter current password if changing password">
                        
                        <?php if (isset($_SESSION['password_update_otp_sent'])): ?>
                            <label>OTP</label>
                            <input type="text" name="otp" placeholder="Enter 6-digit OTP" pattern="\d{6}" required>
                            
                            <label>New Password</label>
                            <input type="password" name="new_password" required>
                            
                            <button type="submit" name="save_changes" class="luxury-button">Update Password & Save Changes</button>
                        <?php else: ?>
                            <div style="display:flex; gap:10px; flex-wrap: wrap;">
                                <button type="submit" name="send_otp" class="luxury-button">Send OTP</button>
                                <button type="submit" name="save_changes" class="luxury-button">Save Profile Changes</button>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <p>Please log in to view your account details.</p>
                    <a href="../../app/controllers/login.php" class="luxury-button">Login</a>
                    <a href="../../app/controllers/signin.php" class="luxury-button">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        }
    </script>
</body>
</html>
