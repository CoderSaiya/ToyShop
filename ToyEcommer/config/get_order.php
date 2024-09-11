<?php
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
    $ordersSql = "
        SELECT O.order_id, O.total_price, O.status, O.created_at, O.user_id,
               P.product_id, P.name, P.description, PO.quantity, PO.price as product_price, P.image_url
        FROM Orders O
        JOIN ProductOrders PO ON O.order_id = PO.order_id
        JOIN Products P ON PO.product_id = P.product_id
        WHERE O.user_id = ?
    ";
    $stmt = $conn->prepare($ordersSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $orders[$row['order_id']]['order_info'] = [
            'user_id' => $row['user_id'],
            'total_price' => $row['total_price'],
            'status' => $row['status'],
            'created_at' => $row['created_at']
        ];
        $orders[$row['order_id']]['products'][] = [
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'quantity' => $row['quantity'],
            'price' => $row['product_price'],
            'image_url' => $row['image_url'],
        ];
    }

    $stmt->close();
}
