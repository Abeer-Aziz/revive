<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "revive_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من أن القيم تم إرسالها
    if (isset($_POST['name']) && isset($_POST['discount']) && isset($_POST['expiry_date'])) {
        $name = $_POST['name'];
        $discount = $_POST['discount'];
        $expiry = $_POST['expiry_date'];

        // استعلام SQL لإضافة العرض
        $sql = "INSERT INTO offers (name, discount, expiry_date) VALUES ('$name', '$discount', '$expiry_date')";

        if ($conn->query($sql) === TRUE) {
            echo "تم إضافة العرض بنجاح";
        } else {
            echo "خطأ: " . $conn->error;
        }
    } else {
        echo "البيانات غير مكتملة";
    }
}

$conn->close();
?>
