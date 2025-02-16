<?php
date_default_timezone_set('Asia/Kolkata');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "celestial_watches";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET time_zone = '+05:30';");

if (!isset($_SESSION['user_id'])) {
  if (!isset($_SESSION['admin'])) {
  if (!isset($_SESSION['guest_id'])) {
      $_SESSION['guest_id'] = 'guest_' . uniqid(); 
      $guest_username = "Guest_" . uniqid();
      $temp_email = $guest_username . "@guest.com";
      $temp_phone = "0000000000";
      $temp_password = password_hash(uniqid(), PASSWORD_DEFAULT);
      
      $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, reset_token, reset_token_expiry, status, role, created_at) VALUES (?, ?, ?, ?, ?, ?, 'NO', 'user', NOW())");
      $reset_token = '';
      $reset_token_expiry = '';
      $stmt->bind_param("ssssss", $guest_username, $temp_email, $temp_phone, $temp_password, $reset_token, $reset_token_expiry);
      
      if (!$stmt->execute()) {
          error_log("Guest insert failed: " . $stmt->error);
          echo "Error inserting guest data.";
      } else {
          $_SESSION['guest_db_id'] = $stmt->insert_id; 
      }
      
      $stmt->close();
    }
  }
} else {
  if (isset($_SESSION['guest_db_id'])) {
      $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND status = 'NO'");
      $stmt->bind_param("i", $_SESSION['guest_db_id']);
      $stmt->execute();
      $stmt->close();
      unset($_SESSION['guest_db_id']);
  }
  unset($_SESSION['guest_id']);
}

function logUserActivity($userId, $activityType, $description = '')
{
  global $conn;
  $ipAddress = $_SERVER['REMOTE_ADDR'];
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  $stmt = $conn->prepare("INSERT INTO user_activity_logs (user_id, activity_type, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $userId, $activityType, $description, $ipAddress, $userAgent);
  $stmt->execute();
  $stmt->close();
}

