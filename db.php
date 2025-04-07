<?php
$servername = "localhost";
$username = "root";
$password = "";  // اتركه فارغًا إذا لم تغيّره
$dbname = "revive_db";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>
