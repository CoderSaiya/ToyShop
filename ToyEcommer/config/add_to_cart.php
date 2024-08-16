<?php 
require_once ("./connect.php");

$username = $_POST["user_id"];
$product_id = $_POST["product_id"];
$default = 1;

$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();

$stmt->close();

$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0) {
    $stmt->bind_result($quantity);
    $stmt->fetch();
    $new_quantity = $quantity + 1;

    $stmt->close();

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
} else {
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param('iii', $user_id, $product_id, $default);
}

$stmt->execute();

echo ("Thêm vào giỏ hàng thành công!");

$stmt->close();
$conn->close();