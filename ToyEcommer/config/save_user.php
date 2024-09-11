<?php
require_once("./connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve posted data
    $fname = $_POST['fName'] ?? null;
    $lname = $_POST['lName'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $birthday = $_POST['birthday'] ?? null;
    $pob_id = $_POST['pob'] ?? null;
    $oldPassword = $_POST['oldPassword'] ?? null;
    $newPassword = $_POST['newPassword'] ?? null;

    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];

        // Fetch user ID
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        if ($user_id) {
            // Start transaction
            $conn->begin_transaction();
            try {
                // Update user information
                if (isset($fname, $lname, $phone, $email, $gender, $birthday, $pob_id)) {
                    $stmt = $conn->prepare("UPDATE users 
                        SET first_name = ?, last_name = ?, phone = ?, email = ?, gender = ?, birthday = ?, pob = ? 
                        WHERE user_id = ?");
                    $stmt->bind_param("ssssssii", $fname, $lname, $phone, $email, $gender, $birthday, $pob_id, $user_id);

                    if (!$stmt->execute()) {
                        throw new Exception("Không thể cập nhật thông tin người dùng!");
                    }
                }

                // Update password if provided
                if ($oldPassword && $newPassword) {
                    // Check if old password matches
                    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $stmt->bind_result($hashedPassword);
                    $stmt->fetch();
                    $stmt->close();

                    if (password_verify($oldPassword, $hashedPassword)) {
                        // Hash new password and update
                        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                        $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
                        $stmt->bind_param("si", $newHashedPassword, $user_id);

                        if (!$stmt->execute()) {
                            throw new Exception("Không thể cập nhật mật khẩu!");
                        }
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Mật khẩu cũ không chính xác.']);
                        $conn->rollback();
                        $conn->close();
                        exit;
                    }
                }

                // Commit transaction
                $conn->commit();
                echo json_encode(['success' => true]);

            } catch (Exception $e) {
                // Rollback transaction
                $conn->rollback();
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Không tìm thấy người dùng']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Phương thức yêu cầu không hợp lệ']);
    }
    
    $conn->close();
}
?>
