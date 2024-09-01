<?php
require_once("./connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fName'];
    $lname = $_POST['lName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $pob_id = $_POST['pob'];
    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();
        if (isset($fname, $lname, $phone, $email, $gender, $birthday, $pob_id)) {
            $stmt = $conn->prepare("UPDATE users 
            SET first_name = ?, last_name = ?, phone = ?, email = ?, gender = ?, birthday = ?, pob = ? 
            WHERE user_id = ?");
            $stmt->bind_param("ssssssii", $fname, $lname, $phone, $email, $gender, $birthday, $pob_id, $user_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, "error" => "Không thể cập nhật!"]);
            }
        }
        else{
            echo json_encode(['success' => false, 'error' => 'Không tìm thấy người dùng']);
        }
    }
    else{
        echo json_encode(['success' => false, 'error' => 'Phương thức yêu cầu không hợp lệ']);
    }
}
$conn->close();
