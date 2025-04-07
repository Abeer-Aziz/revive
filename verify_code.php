<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$method = $data['method'];
$type = $data['type'];

// إنشاء رمز تحقق عشوائي
$verificationCode = rand(1000, 9999);

// حفظ الرمز في الجلسة (يمكنك استخدام قاعدة البيانات بدلاً من ذلك)
session_start();
$_SESSION['verification_code'] = $verificationCode;

// إرسال الرمز عبر البريد الإلكتروني أو SMS
if ($type === 'email') {
    // إرسال الرمز عبر البريد الإلكتروني
    $to = $method;
    $subject = "رمز التحقق لتسجيل الدخول";
    $message = "رمز التحقق الخاص بك هو: $verificationCode";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'فشل إرسال رمز التحقق.']);
    }
} elseif ($type === 'mobile') {
    // إرسال الرمز عبر SMS (يتطلب خدمة SMS مثل Twilio)
    // هذا مثال باستخدام Twilio (يجب تثبيت مكتبة Twilio)
    require_once 'twilio-php-main/src/Twilio/autoload.php'; // تعديل المسار حسب التثبيت

    $account_sid = 'your_account_sid';
    $auth_token = 'your_auth_token';
    $twilio_number = "your_twilio_number";

    $client = new Client($account_sid, $auth_token);

    try {
        $client->messages->create(
            $method, // رقم الجوال
            [
                'from' => $twilio_number,
                'body' => "رمز التحقق الخاص بك هو: $verificationCode"
            ]
        );
        echo json_encode(['success' => true, 'message' => 'تم إرسال رمز التحقق إلى جوالك.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'فشل إرسال رمز التحقق: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'نوع التحقق غير صحيح.']);
}
?>