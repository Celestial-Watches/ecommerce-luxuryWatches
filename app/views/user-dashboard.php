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

    $name  = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    // If user clicked the "Send OTP" button for password change
    if (isset($_POST['send_otp'])) {
        $currentPassword = trim($_POST['current_password']);
        if (empty($currentPassword)) {
            $error = "Please enter your current password to send OTP";
        } elseif (password_verify($currentPassword, $userData['password'])) {
            // Generate OTP and set expiry (10 minutes)
            $otp = rand(100000, 999999);
            $_SESSION['password_update_otp']    = $otp;
            $_SESSION['otp_expiry']             = time() + 600;
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
                $mail->Body    = "Your OTP is: $otp (Valid for 10 minutes)";

                $mail->send();
                $messageInfo = "OTP sent to your email";
            } catch (Exception $e) {
                $error = "Failed to send OTP: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Incorrect current password";
        }
    }
    // If user clicked "Save Changes" or "Update Password & Save Changes"
    elseif (isset($_POST['save_changes'])) {
        // If an OTP has been sent, then user intends to update their password
        if (isset($_SESSION['password_update_otp_sent'])) {
            $enteredOtp   = $_POST['otp'] ?? '';
            $newPassword  = $_POST['new_password'] ?? '';
            if (empty($enteredOtp) || empty($newPassword)) {
                $error = "Please enter OTP and new password to update password";
            } elseif (time() > $_SESSION['otp_expiry']) {
                $error = "OTP has expired. Please request a new one";
                unset($_SESSION['password_update_otp'], $_SESSION['password_update_otp_sent'], $_SESSION['otp_expiry']);
            } elseif ($enteredOtp != $_SESSION['password_update_otp']) {
                $error = "Invalid OTP";
            } else {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "
                    UPDATE users 
                    SET username = '$name',
                        email    = '$email',
                        password = '$newPasswordHash'
                    WHERE id = '$userId'
                ";
                if (mysqli_query($conn, $updateQuery)) {
                    $messageInfo = "Profile and password updated successfully";
                    unset($_SESSION['password_update_otp'], $_SESSION['password_update_otp_sent'], $_SESSION['otp_expiry']);
                } else {
                    $error = "Update failed: " . mysqli_error($conn);
                }
            }
        } else {
            // No password change desired; update only name and email
            $updateQuery = "
                UPDATE users 
                SET username = '$name', 
                    email    = '$email' 
                WHERE id = '$userId'
            ";
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
<html lang="en">

<head>
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Remix Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">

    <style>
        /* RESET & GLOBAL */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F2F3F5;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAV-BAR */
        .nav-bar {
            width: 280px;
            background: #FFFFFF;
            border-right: 1px solid #E0E4E8;
            padding: 1.5rem 1rem;
            transition: transform 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
        }

        .nav-bar .aligned {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .nav-bar .aligned a {
            font-size: 1.2rem;
            color: #aaa;
            margin-right: 0.5rem;
        }

        .nav-bar h2 {
            font-size: 1.2rem;
            font-weight: 500;
            color: #333;
        }

        .nav-bar h3 {
            font-size: 0.75rem;
            color: #888;
            margin: 2rem 0 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-menu li {
            margin-bottom: 0.5rem;
        }

        .nav-menu li a {
            display: block;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            color: #333;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            background-color: #F0F1F3;
        }

        /* Toggle Button for Mobile */
        .toggle-nav-bar {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            z-index: 1101;
            /* Above nav-bar */
        }

        /* Container & Layout for Main Content */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            min-height: 100vh;
            /* push main content to the right to accommodate nav-bar */
            margin-left: 280px;
        }

        .settings {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            flex: 1;
            display: flex;
        }

        .content {
            flex: 1;
            padding: 2rem;
        }

        /* Headings & Paragraphs */
        .content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .content p {
            color: #666;
            margin-bottom: 1rem;
        }

        /* Form */
        form {
            max-width: 600px;
            margin-top: 1rem;
        }

        form label {
            display: block;
            margin: 1rem 0 0.3rem;
            font-size: 0.9rem;
            color: #555;
            font-weight: 500;
        }

        form input {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        form input:focus {
            outline: none;
            border-color: #999;
        }

        .button-group {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .luxury-button {
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
            background-color: #000;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .luxury-button:hover {
            background-color: #333;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #D4EDDA;
            color: #155724;
        }

        .alert-danger {
            background-color: #F8D7DA;
            color: #721C24;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .toggle-nav-bar {
                display: block;
            }

            .nav-bar {
                transform: translateX(-100%);
            }

            .nav-bar.active {
                transform: translateX(0);
            }

            .container {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Toggle Button -->
    <button class="toggle-nav-bar" onclick="toggleNavBar()">Menu</button>

    <!-- NAV-BAR -->
    <div class="nav-bar" id="navBar">
        <div class="aligned">
            <a href="../../index.php"><i class="ri-arrow-left-line"></i></a>
            <h2>Settings</h2>
        </div>

        <!-- Navigation -->
        <ul class="nav-menu">
            <li><a href="#" class="active">Account</a></li>
            <li><a href="#">NFT Customisation</a></li>
            <li><a href="selling.php">Sell & Exchange</a></li>
            <li><a href="#">Auction Participation</a></li>
            <li><a href="transaction.php">Transaction History</a></li>
            <li><a href="manageAddress.php">Manage Address</a></li>
            <li><a href="#">Payment Methods</a></li>
        </ul>

        <!-- Additional system items -->
        <h3>System</h3>
        <ul class="nav-menu">
            <li><a href="my_requests.php">My Stock Requests</a></li>
            <li><a href="notification.php">Notifications</a></li>
            <li><a href="#">Preferences</a></li>
        </ul>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="settings">
            <!-- Content Area -->
            <div class="content">
                <h3>Account Settings</h3>
                <?php if ($isUserLoggedIn): ?>
                    <?php if (isset($messageInfo)): ?>
                        <div class="alert alert-success"><?= $messageInfo ?></div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <!-- Account Form -->
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <label>Name</label>
                        <input type="text" name="name"
                            value="<?= htmlspecialchars($userData['username'] ?? '') ?>"
                            required>

                        <label>Email</label>
                        <input type="email" name="email"
                            value="<?= htmlspecialchars($userData['email'] ?? '') ?>"
                            required>

                        <label>Current Password (for password change)</label>
                        <input type="password" name="current_password"
                            placeholder="Enter current password if changing password">

                        <!-- If OTP has been sent, show OTP + new password fields -->
                        <?php if (isset($_SESSION['password_update_otp_sent'])): ?>
                            <label>OTP</label>
                            <input type="text" name="otp"
                                placeholder="Enter 6-digit OTP" pattern="\d{6}" required>

                            <label>New Password</label>
                            <input type="password" name="new_password" required>

                            <button type="submit" name="save_changes" class="luxury-button">
                                Update Password &amp; Save Changes
                            </button>
                        <?php else: ?>
                            <div class="button-group">
                                <button type="submit" name="send_otp" class="luxury-button">
                                    Send OTP
                                </button>
                                <button type="submit" name="save_changes" class="luxury-button">
                                    Save Profile Changes
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <p>Please log in to view and edit your account details.</p>
                    <a href="../../app/controllers/login.php" class="luxury-button">Login</a>
                    <a href="../../app/controllers/signin.php" class="luxury-button">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleNavBar() {
            var navBar = document.getElementById('navBar');
            navBar.classList.toggle('active');
        }
    </script>
</body>

</html>