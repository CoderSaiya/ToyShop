<?php
session_start(); // Start session at the beginning of the script
require_once("./connect.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Retrieve and sanitize input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Username or password cannot be empty']);
    exit();
}

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => $conn->error]);
    exit();
}

$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if a user is found and validate password
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password_hash'])) {
        // Set session variables on successful login
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];

        // Determine redirection based on role
        if ($row['role'] === 'admin') {
            echo json_encode([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'redirect' => 'admin/admin.php',
                'role' => 'admin'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'redirect' => 'index.php',
                'role' => 'user'
            ]);
        }
    } else {
        // Incorrect password
        echo json_encode(['success' => false, 'error' => 'Mật khẩu không hợp lệ']);
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'error' => 'Không tìm thấy tài khoản']);
}

// Close connection
$stmt->close();
$conn->close();