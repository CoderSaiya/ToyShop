<?php
require_once(__DIR__ . "/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($product_id, $quantity) && is_numeric($quantity) && $quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật số lượng']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không hợp lệ']);
}

$conn->close();
