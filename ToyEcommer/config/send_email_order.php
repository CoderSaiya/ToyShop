<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

header('Content-Type: application/json');

function getProductsByOrderId($orderId, $conn)
{
    $stmt = $conn->prepare("
        SELECT p.name, po.quantity, po.price
        FROM ProductOrders po
        JOIN Products p ON po.product_id = p.product_id
        WHERE po.order_id = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'name' => $row['name'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        ];
    }

    return $products;
}

function createEmailTemplate($orderDetails, $products)
{
    $htmlContent = "<h1>HÓA ĐƠN ĐIỆN TỬ CỦA ĐƠN HÀNG {$orderDetails['order_id']}</h1>";
    $htmlContent .= "<h2>Thông Tin Hóa Đơn Điện Tử</h2>";
    $htmlContent .= "<ul>";
    $htmlContent .= "<li>Ký hiệu hóa đơn: {$orderDetails['invoice_symbol']}</li>";
    $htmlContent .= "<li>Số hóa đơn: {$orderDetails['invoice_number']}</li>";
    $htmlContent .= "<li>Ngày hóa đơn: {$orderDetails['invoice_date']}</li>";
    $htmlContent .= "<li>Tên khách hàng: {$orderDetails['customer_name']}</li>";
    $htmlContent .= "<li>Mã số thuế: {$orderDetails['tax_id']}</li>";
    $htmlContent .= "</ul>";

    $htmlContent .= "<h2>Sản Phẩm</h2>";
    $htmlContent .= "<table border='1' style='width:100%; border-collapse: collapse;'>";
    $htmlContent .= "<tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th></tr>";
    foreach ($products as $product) {
        $htmlContent .= "<tr>";
        $htmlContent .= "<td>{$product['name']}</td>";
        $htmlContent .= "<td>{$product['quantity']}</td>";
        $htmlContent .= "<td>" . number_format($product['price'], 0) . " đ</td>";
        $htmlContent .= "</tr>";
    }
    $htmlContent .= "</table>";

    return $htmlContent;
}

function sendOrderConfirmationEmail($recipientEmail, $orderDetails, $products)
{
    $mail = new PHPMailer(true);
    // Cấu hình SMTP
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sonysam.contacts@gmail.com';
    $mail->Password   = 'rlmz fdka kxud ejzx';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Người gửi và người nhận
    $mail->setFrom('sonysam.contacts@gmail.com', 'admin');
    $mail->addAddress($recipientEmail, 'Quý khách');

    // Nội dung Email
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Xác nhận đơn hàng';
    $mail->Body = createEmailTemplate($orderDetails, $products);
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Tin nhắn đã được gửi đi']);
}
