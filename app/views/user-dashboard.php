<?php
session_start();

include '../config/conn.php';


$isLoggedIn = isset($_SESSION['user']) && is_array($_SESSION['user']);
$userData = [];

if ($isLoggedIn) {
    $userId = mysqli_real_escape_string($conn, $_SESSION['user']); 
    $query = "SELECT name, email FROM users WHERE id = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        $userData = [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $currentPassword = $_POST['current_password'];
    $otpCurrent = $_POST['otp_current'];
    $newPassword = $_POST['new_password'];
    $otpNew = $_POST['otp_new'];

    // Validate inputs and OTPs
    $errors = [];
    if (empty($name) || empty($email) || empty($currentPassword) || empty($otpCurrent) || empty($newPassword) || empty($otpNew)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Verify current password and OTP
    $userId = mysqli_real_escape_string($conn, $_SESSION['user']['user_id']);
    $query = "SELECT password, otp FROM users WHERE id = '$userId'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (!password_verify($currentPassword, $user['password'])) {
        $errors[] = "Incorrect current password.";
    }

    if ($otpCurrent !== $user['otp']) {
        $errors[] = "Incorrect OTP for current password.";
    }

    // If no errors, update the user data
    if (empty($errors)) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $newPasswordHash, $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            background-color: #f5f6f8;
            margin: 0;
            padding: 0;
        }
        .settings {
            display: flex;
            width: 100%;
            background: #fff;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .sidebar {
            width: 280px;
            background-color: #f9fafb;
            padding: 24px;
            border-right: 1px solid #e0e4e8;
            transition: transform 0.3s ease;
        }
        .aligned {
            display: flex;
            align-items: center;
        }
        .sidebar h2 {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            padding-left: 8px;
        }
        .sidebar h3 {
            font-size: 12px;
            color: #8a8f93;
            margin-top: 40px;
            margin-bottom: 12px;
            padding-left: 8px;
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
        .menu li a .icon {
            margin-right: 14px;
            font-size: 18px;
        }
        .menu li a:hover {
            background-color: #e5e9f0;
        }
        .menu li a.active {
            background-color: #e5e9f0;
            color: #007bff;
            font-weight: bold;
        }
        .content {
            flex: 1;
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
        .profile-picture {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-picture img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .profile-picture button {
            margin-right: 10px;
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
        .integrated-accounts {
            margin-top: 20px;
        }
        .integrated-accounts h4 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .account {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
        }
        .account button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .luxury-button {
            padding: 12px 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
            background-color: #000000;
            border: 2px solid #fff;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .luxury-button:hover {
            background-color: #fff;
            color: #4c4c4c;
            border-color: #4c4c4c;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }
        .luxury-button:focus {
            border-color: #ffbb00;
            box-shadow: 0 0 0 3px rgba(255, 187, 0, 0.5);
        }
        /* Responsive Styles */
        @media (max-width: 1024px) {
            .settings {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
            }
            .content {
                padding: 20px;
            }
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                width: 280px;
                padding: 20px;
                z-index: 999;
            }
            .settings {
                flex-direction: column;
                padding-top: 60px;
            }
            .content {
                padding: 20px;
            }
            .sidebar.open {
                transform: translateX(280px);
            }
            .sidebar h2,
            .sidebar h3 {
                text-align: center;
            }
            .sidebar h3 {
                margin-top: 20px;
            }
            .toggle-sidebar {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 4px;
                cursor: pointer;
                z-index: 1000;
            }
            .toggle-sidebar:focus {
                outline: none;
            }
            .luxury-button {
                font-size: 14px;
                padding: 10px 25px;
            }
        }
    </style>
</head>


<body>
    
    <div class="settings">
        <div class="sidebar">
            <span class="aligned"><a href="../../index.php" style="text-decoration: none;"><i class="ri-arrow-left-line"></i></a>
                <h2>Settings</h2>
            </span>
            <ul class="menu">
                <li><a href="#" class="active">Account</a></li>
                <li><a href="#"> NFT Customisation</a></li>
                <li><a href="#"> Sell and Exchange</a></li>
                <li><a href="#"> Auction participation</a></li>
                <li><a href="#"> Transaction History </a></li>
                <li><a href="#"> Manage address </a></li>
                <li><a href="#"> payment methods </a></li>
            </ul>
            <h3>SYSTEM</h3>
            <ul class="menu">
                <li><a href="#"> Notifications</a></li>
                <li><a href="#">Preferences</a></li>
            </ul>
        </div>
        <div class="content">
            <h3>Account</h3>
            <p>Real-time information and activities of your profile.</p>

            <?php if ($isLoggedIn): ?>
                <div class="profile-picture">
                    <img src="assets/image/user.png" alt="Profile picture">
                    <button class="luxury-button">Upload new picture</button>
                    <button class="luxury-button">Delete</button>
                </div>

                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">Profile updated successfully.</div>
                <?php endif; ?>

                <form action="user-dashboard.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($userData['name']); ?>">

                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($userData['email']); ?>">

                    <label>Current password</label>
                    <input type="password" name="current_password" placeholder="Current password">

                    <label>OTP for Current Password</label>
                    <input type="text" name="otp_current" placeholder="Enter OTP">

                    <label>New password</label>
                    <input type="password" name="new_password" placeholder="New password">

                    <label>OTP for New Password</label>
                    <input type="text" name="otp_new" placeholder="Enter OTP">

                    <button type="submit" name="update">Update</button>
                </form>
            <?php else: ?>
                <p>Please log in to view your account details.</p>
                <a href="../../app/controllers/login.php" class="luxury-button">Login</a>
                <a href="../../app/controllers/signin.php" class="luxury-button">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>