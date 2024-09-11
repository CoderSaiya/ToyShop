<?php
require_once('./connect.php');

$product_id = $_POST['product_id'];
$user_id = $_POST['user_id'];
$order_id = $_POST['order_id'];

$check_review_sql = "SELECT * FROM Reviews WHERE product_id = ? AND user_id = ? AND EXISTS (SELECT * FROM ProductOrders WHERE order_id = ? AND product_id = ?)";
$stmt = $conn->prepare($check_review_sql);
$stmt->bind_param('iiii', $product_id, $user_id, $order_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Trả về kết quả dưới dạng JSON
$response = ['review_exists' => $result->num_rows > 0];
echo json_encode($response);

// Đóng kết nối
$conn->close();