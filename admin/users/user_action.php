<?php
session_start();
require '../../app/config/conn.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['admin'] !== true) {
    header("Location: ../../app/controllers/login.php");
    exit();
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$role = $_POST['role'] ?? '';
$status = $_POST['status'] ?? '';
$password = $_POST['password'] ?? '';

try {
    if ($user_id) {
        // Edit user logic
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            http_response_code(404);
            echo 'User not found.';
            exit();
        }

        $status = 'no';
        // Prepare update query
        $update_query = "UPDATE users SET username = ?, email = ?, phone = ?, role = ?, status = ?";

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $update_query .= ", password = ?";
            $params = [$username, $email, $phone, $role, $status, $hashed_password, $user_id];
        } else {
            $params = [$username, $email, $phone, $role, $status, $user_id];
        }

        $update_query .= " WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);
        $stmt->execute();

        echo 'User updated successfully.';
    } else {
        // Add new user logic
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            http_response_code(409);
            echo 'Email already exists.';
            exit();
        }

        // Password validation for new user
        if (empty($password)) {
            http_response_code(400);
            echo 'Password is required for new users.';
            exit();
        }

        // Hash password and insert new user
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $status = 'no'; 
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, role, status, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $username, $email, $phone, $role, $status, $hashed_password);
        $stmt->execute();


        echo 'User added successfully.';
    }
} catch (Exception $e) {
    http_response_code(500);
    echo 'Database error: ' . $e->getMessage();
}
