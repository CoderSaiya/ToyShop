<?php
session_start();

// Khởi tạo mảng để lưu đánh giá
$reviews = [];

// Truy vấn đánh giá từ cơ sở dữ liệu
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Truy vấn để lấy đánh giá theo order_id
    $query = "SELECT r.product_id, u.username, r.rating, r.comment 
              FROM reviews r
              JOIN products p ON r.product_id = p.product_id
              JOIN users u ON u.user_id = r.user_id
              WHERE r.order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lưu kết quả vào mảng reviews
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
}