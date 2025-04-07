<?php
// تشغيل عرض الأخطاء للمساعدة في التطوير
error_reporting(E_ALL);
ini_set('display_errors', 1);

// إعدادات الاتصال
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "revive_db";

// الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "فشل الاتصال بقاعدة البيانات"]);
    exit;
}

// تعيين الترميز
$conn->set_charset("utf8");

// تنفيذ الاستعلام
$sql = "SELECT id, name, discount, expiry_date AS expiry FROM offers ORDER BY expiry_date DESC";
$result = $conn->query($sql);

$offers = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $offers[] = $row;
    }
}

// إغلاق الاتصال
$conn->close();

// تحديد نوع المحتوى
header('Content-Type: application/json');

// إعادة البيانات
echo json_encode($offers);
