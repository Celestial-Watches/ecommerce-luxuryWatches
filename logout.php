<?php
date_default_timezone_set('Asia/Kolkata');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    require_once "conn.php"; // Ensure database connection is established

    // Retrieve the username from the session
    $usernamee = isset($_SESSION['user']) ? $_SESSION['user'] : $_SESSION['admin'];
    

    // Update the user's status to 'NO' in the database
    $updateSql = "UPDATE users SET status = 'NO' WHERE username = ?";
    if ($updateStmt = mysqli_prepare($conn, $updateSql)) {
        mysqli_stmt_bind_param($updateStmt, "s", $usernamee);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);
    } else {
        error_log("Failed to prepare SQL statement: " . mysqli_error($conn));
    }

    // Set authenticated to false
    $_SESSION['authenticated'] = false; // Set authenticated to false on logout

    // Unset specific session variables
    unset($_SESSION['user'], $_SESSION['admin']); // Unset both user and admin session variables

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Invalidate the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy any additional cookies if used for authentication
    setcookie("username", "", time() - 3600, "/"); // Set cookie to expire in the past

    // Redirect to login page
    header("Location: login.php");
    exit();
} else {
    // If the user is not logged in, just redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches - Exclusivity in Every Tick</title>
    <script src="/js/navigation.js"></script>
</head>
<body>
    
</body>
</html>
