<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

header('Content-Type: application/json');

// Get the JSON body from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$recipientEmail = $data['recipientEmail'] ?? '';

if (empty($recipientEmail)) {
    echo json_encode(['status' => 'error', 'message' => 'Email không được để trống']);
    exit;
}

$mail = new PHPMailer(true);

// try {
// SMTP configuration
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'sonysam.contacts@gmail.com';
$mail->Password   = 'rlmz fdka kxud ejzx';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

// Sender and recipient
$mail->setFrom('sonysam.contacts@gmail.com', 'admin');
$mail->addAddress($recipientEmail, 'Recipient Name');

// Email content
$mail->isHTML(true);
$mail->Subject = 'Tiêu đề';
$mail->Body    = 'Không mua đấm chết giờ';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();
echo json_encode(['status' => 'success', 'message' => 'Tin nhắn đã được gửi đi']);
// } catch (Exception $e) {
//     echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
// }
