<?php
require_once(__DIR__ . "/connect.php");
require_once(__DIR__ . "/../vendor/autoload.php");
require_once("./send_email_order.php");

if (!isset($_GET['session_id']) || !isset($_COOKIE['username'])) {
    die("Session ID hoặc username không hợp lệ.");
}

session_start();

$stripe_secret_key = 'sk_test_51PtpAPGH56JC4HtHnS2pPQN0207Y20xnDYaKzeHgcJsojH5HEyl5KvyHSSiUV4hXgbNSOFL4B1WhCTCXnNDkDhjc00u8nHYvhC';
\Stripe\Stripe::setApiKey($stripe_secret_key);

$session_id = $_GET['session_id'];

$session = \Stripe\Checkout\Session::retrieve($session_id);

if ($session->payment_status == 'paid') {
    $username = $_COOKIE['username'];
    $userInfoSql = $conn->query("SELECT * FROM users WHERE username = '$username'");
    $userInfo = $userInfoSql->fetch_assoc();
    $user_id = $userInfo['user_id'];

    $conn->query("START TRANSACTION;");

    $orderInsertSql = $conn->query("
    INSERT INTO Orders (user_id, total_price, status)
    VALUES ($user_id, $session->amount_total, 'pending');
    ");

    $order_id = $conn->insert_id;

    // Chuyển đổi chuỗi sản phẩm thành mảng
    $productListString = $_SESSION['productList'];
    // var_dump($productListString);

    if (!$productListString) {
        $conn->query("ROLLBACK;");
        die("Danh sách sản phẩm không hợp lệ hoặc không được cung cấp.");
    }

    // Chia chuỗi thành các sản phẩm
    $products = explode('|', $productListString);

    // Khởi tạo mảng sản phẩm
    $productList = [];

    foreach ($products as $product) {
        // Chia sản phẩm thành các thuộc tính
        $attributes = explode('\\', $product);

        // Kiểm tra số lượng thuộc tính
        // if (count($attributes) < 13) {
        //     $conn->query("ROLLBACK;");
        //     die("Dữ liệu sản phẩm không hợp lệ.");
        // }

        // Thêm sản phẩm vào danh sách
        $productList[] = [
            'productId' => $attributes[2],
            'quantity' => $attributes[3],
            'price' => $attributes[7],
        ];
    }

    foreach ($productList as $product) {
        $productId = $product['productId'];
        $quantity = $product['quantity'];
        $price = $product['price'];

        $productOrderSql = $conn->query("
            INSERT INTO ProductOrders (order_id, product_id, quantity, price)
            VALUES ($order_id, $productId, $quantity, $price);
        ");

        if (!$productOrderSql) {
            $conn->query("ROLLBACK;");
            die("Lỗi khi thêm sản phẩm vào đơn hàng: " . $conn->error);
        }

        // Cập nhật tồn kho
        $updateStockSql = $conn->query("
            UPDATE Products
            SET stock = stock - $quantity
            WHERE product_id = $productId;
        ");

        if (!$updateStockSql) {
            $conn->query("ROLLBACK;");
            die("Lỗi khi cập nhật số lượng tồn kho: " . $conn->error);
        }
    }

    //Xóa giỏ hàng
    $delCartSql = $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    $conn->query("COMMIT;");

    // Gửi email xác nhận đơn hàng
    $orderDetails = [
        'order_id' => $order_id,
        'invoice_symbol' => 'INV-' . $order_id,
        'invoice_number' => $order_id,
        'invoice_date' => date('Y-m-d'),
        'customer_name' => $username,
        'tax_id' => '1234567890'
    ];
    $products = getProductsByOrderId($order_id, $conn);

    sendOrderConfirmationEmail($userInfo['email'], $orderDetails, $products);
    header("Location: ../success_page.php");
} else {
    die("Thanh toán không thành công. Vui lòng thử lại.");
}
