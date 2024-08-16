<?php
require_once(__DIR__ . "/connect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];

    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        if (isset($product_id, $user_id)) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $product_id, $user_id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể xóa sản phẩm']);
            }

            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy người dùng']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không hợp lệ']);
}

$conn->close();
