<?php
date_default_timezone_set('Asia/Kolkata');

session_start();

require_once "../config/conn.php";

// Loggin user activity
$userId = $_SESSION['user_id'];
$activityType = 'logout';
$description = 'User logout successfully.';

logUserActivity($userId, $activityType, $description);

if (isset($_COOKIE['temp'])) {
    setcookie('temp', '', time() - 3600, '/', false, true); 
}

setcookie("loggedYes", "", time() - 1, "/", false, true); 


if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    require_once "../config/conn.php"; 

     $usernamee = isset($_SESSION['user']) ? $_SESSION['user'] : $_SESSION['admin'];

    $updateSql = "UPDATE users SET status = 'NO' WHERE username = ?";
    if ($updateStmt = mysqli_prepare($conn, $updateSql)) {
        mysqli_stmt_bind_param($updateStmt, "s", $usernamee);
        if (!mysqli_stmt_execute($updateStmt)) {
            error_log("Failed to execute SQL update: " . mysqli_error($conn));
        }
        mysqli_stmt_close($updateStmt);
    } else {
        error_log("Failed to prepare SQL statement: " . mysqli_error($conn));
    }

    $_SESSION = [];
    $_SESSION['authenticated'] = false;
    unset($_SESSION['csrf_token']);
    unset($_SESSION['user'], $_SESSION['admin'], $_SESSION['otp_verified']); 

    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    setcookie("SSIDU", "", time() - 3600, "/", false, true);
    setcookie("PHPSESSID", "", time() - 1, "/", false, true);
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches | Exclusivity in Every Tick</title>
    <script src="../../src/assets/js/navigation.js" async></script>
</head>
<body>
</body>
</html>