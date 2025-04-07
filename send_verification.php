<?php
header('Content-Type: application/json');

session_start();

$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'];

if (isset($_SESSION['verification_code']) && $code == $_SESSION['verification_code']) {
    echo json_encode(['success' => true, 'message' => 'تم التحقق بنجاح.']);
} else {
    echo json_encode(['success' => false, 'message' => 'رمز التحقق غير صحيح.']);
}
?>