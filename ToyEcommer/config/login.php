<?php
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
        // Success
        echo json_encode(['success' => true]);
    } else {
        // Incorrect password.
        echo json_encode(['success' => false, 'error' => "Invalid password $password {$row['password_hash']}"]);
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'error' => 'User not found']);
}

// Close connection
$stmt->close();
$conn->close();
