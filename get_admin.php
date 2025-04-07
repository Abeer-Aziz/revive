<?php
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin) {
        echo json_encode($admin);
    } else {
        echo json_encode(["error" => "لم يتم العثور على المسؤول."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "المعرف غير موجود في الرابط."]);
}
?>
