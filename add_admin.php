<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];

    if (empty($name) || empty($role)) {
        echo json_encode(["success" => false, "error" => "يرجى تعبئة جميع الحقول."]);
        exit;
    }

    // استخدام mysqli بشكل صحيح
    $stmt = $conn->prepare("INSERT INTO admins (name, role) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $role); // "ss" يعني أن كلا القيمتين نصية (string)

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "حدث خطأ أثناء إضافة المسؤول: " . $stmt->error]);
    }

    $stmt->close();
}
?>