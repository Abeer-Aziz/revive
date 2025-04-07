<?php
header("Content-Type: application/json");

// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "revive_db");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "فشل الاتصال بقاعدة البيانات"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["id"]) || empty($_POST["id"])) {
        echo json_encode(["success" => false, "error" => "معرف غير صالح"]);
        exit;
    }

    $id = intval($_POST["id"]);

    // تنفيذ الحذف
    $sql = "DELETE FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
