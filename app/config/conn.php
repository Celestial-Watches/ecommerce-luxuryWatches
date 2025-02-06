<?php
date_default_timezone_set('Asia/Kolkata');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "celestial_watches";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET time_zone = '+05:30';");

function logUserActivity($userId, $activityType, $description = '') {
    global $conn;
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $stmt = $conn->prepare("INSERT INTO user_activity_logs (user_id, activity_type, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $activityType, $description, $ipAddress, $userAgent);
    $stmt->execute();
    $stmt->close();
  }
?>