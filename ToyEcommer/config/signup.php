<?php
require_once("./connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';
$fName = $_POST['fName'] ?? '';
$lName = $_POST['lName'] ?? '';
$role = $_POST['role'] ?? '';

if (empty($username) || empty($password) || empty($email) || empty($fName) || empty($lName) || empty($role)) {
    echo json_encode(['success' => false, 'error' => 'Infomation cannot be empty']);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => $conn->error]);
    exit();
}

$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt->bind_param('ssssss', $username, $email , $hashedPassword, $fName, $lName, $role);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User registered successfully']);
        exit();
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to register user']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'User already exists']);
}

// Close connection
$stmt->close();
$conn->close();
