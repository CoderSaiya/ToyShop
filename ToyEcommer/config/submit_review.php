<?php
session_start();
require_once('./connect.php');

if (isset($_POST['submit_review'])) {
    $product_id = $_POST['product_id'];
    $order_id = $_POST['order_id'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if ($rating < 1 || $rating > 5) {
        $_SESSION['message'] = "Rating phải nằm trong khoảng từ 1 đến 5.";
        header("Location: orders.php");
        exit();
    }

    $checkProductQuery = "SELECT product_id FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($checkProductQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['message'] = "Sản phẩm không tồn tại.";
        header("Location: orders.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $product_id, $user_id, $rating, $comment);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Đánh giá của bạn đã được gửi thành công!";
    } else {
        $_SESSION['message'] = "Có lỗi xảy ra khi gửi đánh giá: " . $stmt->error;
    }

    $stmt->close();
    header("Location: ../index.php");
    exit();
}
