<?php
require_once("./connect.php");

if (!isset($_COOKIE['username'])) {
    die("Không tìm thấy cookie đăng nhập. Vui lòng đăng nhập lại.");
}

session_start();

$totalSum = isset($_POST['totalsum']) ? $_POST['totalsum'] : 0;

$productListJson = isset($_POST['productList']) ? $_POST['productList'] : '';
$productList = json_decode($productListJson, true);

if (is_array($productList)) {
    $productListString = implode('|', array_map(function($item) {
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

require_once(__DIR__ . "/../vendor/autoload.php");

$stripe_secret_key = 'sk_test_51PtpAPGH56JC4HtHnS2pPQN0207Y20xnDYaKzeHgcJsojH5HEyl5KvyHSSiUV4hXgbNSOFL4B1WhCTCXnNDkDhjc00u8nHYvhC';
\Stripe\Stripe::setApiKey($stripe_secret_key);

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost:3000/success_page.php?session_id={CHECKOUT_SESSION_ID}",
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