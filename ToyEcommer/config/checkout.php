<?php
require_once("./connect.php");
require_once("./send_email_order.php");

if (!isset($_COOKIE['username'])) {
    die("Không tìm thấy cookie đăng nhập. Vui lòng đăng nhập lại.");
}

session_start();

$totalSum = isset($_POST['totalsum']) ? $_POST['totalsum'] : 0;
$productListJson = isset($_POST['productList']) ? $_POST['productList'] : '';
$productList = json_decode($productListJson, true);

if (is_array($productList)) {
    $productListString = implode('|', array_map(function ($item) {
        return implode('\\', $item);
    }, $productList));
} else {
    $productListString = '';
}

$_SESSION['productList'] = $productListString;

$username = $_COOKIE['username'];
$userInfoSql = $conn->query("SELECT * FROM users WHERE username = '$username'");
$userInfo = $userInfoSql->fetch_assoc();
$user_id = $userInfo['user_id'];

$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'stripe';

if ($paymentMethod === 'cod') {
    // Xử lý thanh toán khi nhận hàng (COD)
    $conn->query("START TRANSACTION;");

    $orderInsertSql = $conn->query("
        INSERT INTO Orders (user_id, total_price, status)
        VALUES ($user_id, $totalSum, 'pending');
    ");

    $order_id = $conn->insert_id;

    $productListString = $_SESSION['productList'];

    if (!$productListString) {
        $conn->query("ROLLBACK;");
        die("Danh sách sản phẩm không hợp lệ hoặc không được cung cấp.");
    }

    $products = explode('|', $productListString);
    $productList = [];

    foreach ($products as $product) {
        $attributes = explode('\\', $product);
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
    exit;
} else {
    // Xử lý thanh toán qua Stripe
    require_once(__DIR__ . "/../vendor/autoload.php");

    $stripe_secret_key = 'sk_test_51PtpAPGH56JC4HtHnS2pPQN0207Y20xnDYaKzeHgcJsojH5HEyl5KvyHSSiUV4hXgbNSOFL4B1WhCTCXnNDkDhjc00u8nHYvhC';
    \Stripe\Stripe::setApiKey($stripe_secret_key);

    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "http://localhost:3000/config/success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => "http://localhost:3000/cancel.php",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "vnd",
                    "unit_amount" => $totalSum,
                    "product_data" => [
                        "name" => "Tổng thanh toán"
                    ]
                ]
            ]
        ],
    ]);

    http_response_code(303);
    header("Location: " . $checkout_session->url);
    exit;
}
