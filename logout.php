<?php
date_default_timezone_set('Asia/Kolkata');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    require_once "conn.php"; // Ensure database connection is established

    // Retrieve the username from the session
    $usernamee = $_SESSION['user'];

    // Update the user's status to 'NO' in the database
    $updateSql = "UPDATE users SET status = 'NO' WHERE username = ?";
    if ($updateStmt = mysqli_prepare($conn, $updateSql)) {
        mysqli_stmt_bind_param($updateStmt, "s", $usernamee);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);
    } else {
        error_log("Failed to prepare SQL statement: " . mysqli_error($conn));
    }

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

    // Redirect to the login page
    header("Location: login.php");
    exit();
} else {
    // If the user is not logged in, just redirect to the login page
    header("Location: login.php");
    exit();
}
?>