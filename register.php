<?php
error_reporting(E_ALL); // عرض جميع الأخطاء
ini_set('display_errors', 1); // تمكين عرض الأخطاء على الشاشة

header('Content-Type: application/json'); // تعيين نوع المحتوى كـ JSON

// التحقق من أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // استقبال البيانات من النموذج
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        // تشفير كلمة المرور
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // الاتصال بقاعدة البيانات
        $host = 'localhost';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbName = 'revive_db';

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        // التحقق من الاتصال
        if ($conn->connect_error) {
            throw new Exception("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
        }

        // إدخال البيانات في قاعدة البيانات
        $query = "INSERT INTO users (username, email, phone, address, password) 
                  VALUES ('$username', '$email', '$phone', '$address', '$hashedPassword')";
        if ($conn->query($query)) {
            echo json_encode(['success' => true, 'message' => 'تم التسجيل بنجاح!']);
        } else {
            throw new Exception("حدث خطأ أثناء التسجيل: " . $conn->error);
        }

        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير صحيحة.']);
}
?>